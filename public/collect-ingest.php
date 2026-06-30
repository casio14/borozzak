<?php
declare(strict_types=1);

// holborozzak.hu — esemény-jelölt fogadó (a napi gyűjtő ide POST-olja a találatokat).
// Token-védett. A DB-írás itt, a szerveren történik (a CI nem éri el közvetlenül a MySQL-t).

require __DIR__ . '/db.php';
require __DIR__ . '/lib/candidates.php'; // candidateDedupKey, candidateDuplicate, eventDuplicate (+ events.php)

header('Content-Type: application/json; charset=utf-8');
header('X-Robots-Tag: noindex, nofollow');

/** A megosztott token a config.php-ból (éles: COLLECT_TOKEN secret). */
function collectToken(): string
{
    $cfg = __DIR__ . '/config.php';
    if (is_file($cfg)) {
        $c = require $cfg;
        return (string) ($c['collect_token'] ?? '');
    }
    return '';
}

$raw = file_get_contents('php://input') ?: '';
$data = json_decode($raw, true);
$items = is_array($data['events'] ?? null) ? $data['events'] : [];

// Token: fejlécből vagy a kérés törzséből (ha a hoszt szűrné a fejlécet)
$expected = collectToken();
$given = (string) ($_SERVER['HTTP_X_COLLECT_TOKEN'] ?? '');
if ($given === '' && is_array($data)) {
    $given = (string) ($data['token'] ?? '');
}
if ($expected === '' || !hash_equals($expected, $given)) {
    http_response_code(403);
    echo json_encode(['error' => 'forbidden', 'hint' => $expected === '' ? 'config collect_token ures (deploy kell)' : 'token nem egyezik']);
    exit;
}

$added = 0;
$skipped = 0;
try {
    $pdo = db();
    $ins = $pdo->prepare(
        "INSERT INTO event_candidates
            (source_url, title, short_description, start_datetime, end_datetime,
             venue_name, city, region_name, website_url, image_url, dedup_key, status)
         VALUES
            (:src, :title, :short, :start, :end,
             :venue, :city, :region, :web, :img, :dedup, 'new')"
    );
    foreach ($items as $d) {
        if (!is_array($d)) {
            continue;
        }
        $title = trim((string) ($d['title'] ?? ''));
        if ($title === '') {
            continue;
        }
        $start = toMysqlDatetime((string) ($d['start_datetime'] ?? ''));
        $city  = trim((string) ($d['city'] ?? ''));
        $dedup = candidateDedupKey($title, $start, $city);

        if (candidateDuplicate($pdo, $dedup) || eventDuplicate($pdo, $title, $start, $city)) {
            $skipped++;
            continue;
        }
        $ins->execute([
            ':src'    => ($d['source_url'] ?? '') ?: (($d['website_url'] ?? '') ?: null),
            ':title'  => $title,
            ':short'  => ($d['short_description'] ?? '') ?: null,
            ':start'  => $start,
            ':end'    => toMysqlDatetime((string) ($d['end_datetime'] ?? '')),
            ':venue'  => ($d['venue_name'] ?? '') ?: null,
            ':city'   => $city !== '' ? $city : null,
            ':region' => ($d['region_name'] ?? '') ?: null,
            ':web'    => ($d['website_url'] ?? '') ?: null,
            ':img'    => ($d['image_url'] ?? '') ?: null,
            ':dedup'  => $dedup,
        ]);
        $added++;
    }
} catch (Throwable $e) {
    error_log('collect-ingest.php hiba: ' . $e->getMessage());
    http_response_code(500);
    echo json_encode(['error' => 'db', 'message' => $e->getMessage()]);
    exit;
}

echo json_encode(['received' => count($items), 'added' => $added, 'skipped' => $skipped]);
