<?php
declare(strict_types=1);

/**
 * Esemény-gyűjtő (GitHub Actions, 3 naponta).
 *
 * A Claude `web_search` eszközével KERES az interneten közelgő, magyar borhoz
 * köthető eseményeket, majd az eredményt HTTPS-en POST-olja a weboldal token-védett
 * `collect-ingest.php` végpontjára — a DB-írás ott, a szerveren történik (a CI nem
 * éri el közvetlenül a Rackhost MySQL-t). A jóváhagyás az adminban kézi.
 *
 * Env: ANTHROPIC_API_KEY, ANTHROPIC_MODEL (opc.), COLLECT_URL, COLLECT_TOKEN.
 */

function envv(string $k, string $def = ''): string
{
    $v = getenv($k);
    return $v === false ? $def : $v;
}

function httpPost(string $url, string $body, array $headers): array
{
    $ch = curl_init($url);
    curl_setopt_array($ch, [
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_POST           => true,
        CURLOPT_TIMEOUT        => 120,
        CURLOPT_HTTPHEADER     => $headers,
        CURLOPT_POSTFIELDS     => $body,
    ]);
    $resp = curl_exec($ch);
    $err  = curl_error($ch);
    $code = (int) curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);
    if ($resp === false) {
        throw new RuntimeException('HTTP hiba: ' . $err);
    }
    return [$code, (string) $resp];
}

/** Az első [...] JSON-tömb kinyerése a szövegből. */
function extractJsonArray(string $s): string
{
    $s = trim($s);
    $start = strpos($s, '[');
    $end   = strrpos($s, ']');
    if ($start === false || $end === false || $end < $start) {
        return '[]';
    }
    return substr($s, $start, $end - $start + 1);
}

/** A modellhez illő web search eszköz-verzió (újabbakon dinamikus szűrés = token-takarékos). */
function webSearchToolType(string $model): string
{
    $m = strtolower($model);
    foreach (['opus-4-8', 'opus-4-7', 'opus-4-6', 'sonnet-4-6', 'fable'] as $tag) {
        if (strpos($m, $tag) !== false) {
            return 'web_search_20260209';
        }
    }
    return 'web_search_20250305';
}

/** Claude web search hívás (pause_turn-kezeléssel) → záró szöveg. */
function searchEventsViaClaude(string $apiKey, string $model, string $system, string $userText): string
{
    $messages = [['role' => 'user', 'content' => $userText]];
    $tools = [['type' => webSearchToolType($model), 'name' => 'web_search', 'max_uses' => 8]];
    $headers = [
        'content-type: application/json',
        'x-api-key: ' . $apiKey,
        'anthropic-version: 2023-06-01',
    ];

    for ($i = 0; $i < 8; $i++) {
        $payload = json_encode([
            'model'      => $model,
            'max_tokens' => 4000,
            'system'     => $system,
            'messages'   => $messages,
            'tools'      => $tools,
        ], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);

        [$code, $resp] = httpPost('https://api.anthropic.com/v1/messages', $payload, $headers);
        $data = json_decode($resp, true);
        if ($code >= 400) {
            throw new RuntimeException('AI hiba: ' . ($data['error']['message'] ?? ('HTTP ' . $code)));
        }

        $messages[] = ['role' => 'assistant', 'content' => $data['content'] ?? []];

        if (($data['stop_reason'] ?? '') === 'pause_turn') {
            continue; // szerveroldali eszköz fut tovább — újraküldjük
        }

        $text = '';
        foreach (($data['content'] ?? []) as $b) {
            if (($b['type'] ?? '') === 'text') {
                $text .= (string) $b['text'];
            }
        }
        return $text;
    }
    return '';
}

// ---------------------------------------------------------------------------

$apiKey       = envv('ANTHROPIC_API_KEY');
$model        = envv('ANTHROPIC_MODEL', 'claude-haiku-4-5');
$collectUrl   = envv('COLLECT_URL');
$collectToken = envv('COLLECT_TOKEN');

if ($apiKey === '') {
    fwrite(STDERR, "Hiányzik az ANTHROPIC_API_KEY.\n");
    exit(1);
}
if ($collectUrl === '' || $collectToken === '') {
    fwrite(STDERR, "Hiányzik a COLLECT_URL vagy a COLLECT_TOKEN.\n");
    exit(1);
}

$today = (new DateTimeImmutable('now', new DateTimeZone('Europe/Budapest')))->format('Y-m-d');

$system = "Magyar borrendezvény-kutató vagy. A web_search eszközzel KERESS az interneten "
    . "KÖZELGŐ (a mai naptól számított kb. 6 hónapon belüli) magyarországi, borhoz köthető "
    . "eseményeket: borfesztiválok, bornapok, szüreti rendezvények, kóstolók, pincék programjai. "
    . "Futtass több, változatos keresést (különböző borvidékek, hónapok, rendezvénytípusok).\n"
    . "A végén KIZÁRÓLAG egyetlen JSON TÖMBÖT adj vissza (markdown és magyarázat nélkül), ahol minden elem: "
    . "{title, start_datetime, end_datetime, city, venue_name, region_name, website_url, source_url, short_description, image_url}.\n"
    . "Az image_url az eseményhez tartozó kép közvetlen URL-je, ha találsz ilyet (különben üres string).\n"
    . "Dátumformátum: 'YYYY-MM-DDTHH:MM:SS' (ismeretlen idő: 00:00:00); ha nincs adat, üres string. "
    . "A region_name a 22 magyar borvidék egyike legyen, ha azonosítható. A source_url az az oldal, ahol az "
    . "esemény megerősítve szerepel. CSAK valós, forrással alátámasztott eseményeket adj vissza — soha ne találj ki adatot.";

$user = "Mai dátum: {$today}. Keress legalább 10-15 közelgő, valós magyar borrendezvényt, és add vissza a JSON tömböt.";

echo "[" . date('c') . "] Keresés indul (model={$model})…\n";

try {
    $text = searchEventsViaClaude($apiKey, $model, $system, $user);
} catch (Throwable $e) {
    fwrite(STDERR, 'Keresés hiba: ' . $e->getMessage() . "\n");
    exit(1);
}

$items = json_decode(extractJsonArray($text), true);
if (!is_array($items)) {
    fwrite(STDERR, "Nem sikerült értelmezni a választ JSON-ként.\n");
    fwrite(STDERR, "Nyers válasz (eleje): " . substr($text, 0, 1500) . "\n");
    exit(1);
}
if (count($items) === 0) {
    // Diagnosztika: lássuk, mit adott vissza a modell.
    fwrite(STDERR, "0 elem. Nyers válasz (eleje): " . substr($text, 0, 1500) . "\n");
}
echo "[" . date('c') . "] Talált elemek: " . count($items) . " — beküldés a weboldalra…\n";

try {
    $payload = json_encode(['token' => $collectToken, 'events' => $items], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
    [$code, $resp] = httpPost($collectUrl, $payload, [
        'content-type: application/json',
        'x-collect-token: ' . $collectToken,
    ]);
    if ($code >= 400) {
        fwrite(STDERR, "Ingest hiba (HTTP {$code}): {$resp}\n");
        exit(1);
    }
    $r = json_decode($resp, true);
    $added   = (int) ($r['added'] ?? 0);
    $skipped = (int) ($r['skipped'] ?? 0);
    echo "[" . date('c') . "] Kész. Új jelölt: {$added}, kihagyott (duplikált): {$skipped}.\n";
} catch (Throwable $e) {
    fwrite(STDERR, 'Beküldés hiba: ' . $e->getMessage() . "\n");
    exit(1);
}

exit(0);
