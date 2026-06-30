<?php
declare(strict_types=1);

// holborozzak.hu — esemény .ics (naptárhoz adás). ics.php?e=<id>
// Univerzális iCalendar fájl (Google/Apple/Outlook). Csak közzétett esemény.

require __DIR__ . '/db.php';
require __DIR__ . '/lib/events.php';

$id = (int) ($_GET['e'] ?? 0);
$event = null;
if ($id > 0) {
    try {
        $st = db()->prepare("SELECT * FROM events WHERE id = ? AND status = 'published' LIMIT 1");
        $st->execute([$id]);
        $event = $st->fetch() ?: null;
    } catch (Throwable $e) {
        error_log('ics.php DB hiba: ' . $e->getMessage());
    }
}

if (!$event) {
    http_response_code(404);
    header('Content-Type: text/plain; charset=utf-8');
    echo 'Esemény nem található.';
    exit;
}

/** Helyi (Budapest) idő → UTC iCal időbélyeg (Ymd\THis\Z). */
function icsUtc(string $dt): string
{
    return (new DateTimeImmutable($dt, new DateTimeZone('Europe/Budapest')))
        ->setTimezone(new DateTimeZone('UTC'))
        ->format('Ymd\THis\Z');
}

/** iCal szöveg-escape (RFC 5545). */
function icsEsc(string $s): string
{
    $s = str_replace('\\', '\\\\', $s);
    $s = str_replace([",", ";"], ["\\,", "\\;"], $s);
    $s = str_replace(["\r\n", "\n", "\r"], '\\n', $s);
    return $s;
}

$base = ((!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? 'https' : 'http')
    . '://' . ($_SERVER['HTTP_HOST'] ?? 'holborozzak.hu');
$dir = rtrim(dirname($_SERVER['SCRIPT_NAME'] ?? '/'), '/');
$url = eventUrl($event, $base, $dir);

$start = $event['start_datetime'];
$end   = !empty($event['end_datetime'])
    ? $event['end_datetime']
    : (new DateTimeImmutable($start))->modify('+2 hours')->format('Y-m-d H:i:s');

$location = trim(($event['venue_name'] ? $event['venue_name'] . ', ' : '')
    . ($event['address'] ? $event['address'] . ', ' : '')
    . ($event['city'] ?? ''));
$desc = trim((string) ($event['short_description'] ?? ''));
$desc = $desc !== '' ? ($desc . '\\n\\n' . $url) : $url;

$lines = [
    'BEGIN:VCALENDAR',
    'VERSION:2.0',
    'PRODID:-//holborozzak.hu//Esemenyek//HU',
    'CALSCALE:GREGORIAN',
    'METHOD:PUBLISH',
    'BEGIN:VEVENT',
    'UID:event-' . (int) $event['id'] . '@holborozzak.hu',
    'DTSTAMP:' . gmdate('Ymd\THis\Z'),
    'DTSTART:' . icsUtc($start),
    'DTEND:' . icsUtc($end),
    'SUMMARY:' . icsEsc((string) $event['title']),
    'LOCATION:' . icsEsc($location),
    'DESCRIPTION:' . icsEsc($desc),
    'URL:' . icsEsc($url),
    'END:VEVENT',
    'END:VCALENDAR',
];

$slug = $event['slug'] ?? ('esemeny-' . $id);
header('Content-Type: text/calendar; charset=utf-8');
header('Content-Disposition: attachment; filename="' . $slug . '.ics"');
echo implode("\r\n", $lines) . "\r\n";
