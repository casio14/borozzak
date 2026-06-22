<?php
declare(strict_types=1);

// holborozzak.hu — Eseménytérkép: a borrendezvények interaktív térképen.

require __DIR__ . '/db.php';
require __DIR__ . '/lib/events.php';

$pageTitle = 'Eseménytérkép — Magyarország borrendezvényei a térképen | holborozzak.hu';
$pageDescription = 'Magyarország borrendezvényei egy interaktív térképen — találd meg a '
    . 'hozzád legközelebbi borfesztivált, bornapot, kóstolót és szüreti rendezvényt.';
$activeNav = 'terkep';

$base = ((!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? 'https' : 'http')
    . '://' . ($_SERVER['HTTP_HOST'] ?? 'holborozzak.hu');
$dir = rtrim(dirname($_SERVER['SCRIPT_NAME'] ?? '/'), '/');

$events = [];
try {
    $events = fetchUpcomingEvents(db());
} catch (Throwable $e) {
    error_log('terkep.php DB hiba: ' . $e->getMessage());
}

// Térképpontok (csak koordinátával rendelkező események)
$points = [];
foreach ($events as $e) {
    if (!empty($e['latitude']) && !empty($e['longitude'])) {
        $points[] = [
            'title' => $e['title'],
            'lat'   => (float) $e['latitude'],
            'lng'   => (float) $e['longitude'],
            'date'  => formatDateRange($e['start_datetime'], $e['end_datetime']),
            'city'  => $e['city'],
            'venue' => $e['venue_name'],
            'free'  => (int) $e['is_free'] === 1,
        ];
    }
}

// SEO / AI strukturált adat (közös függvény)
$ld = eventsItemListJsonLd($events, $base, $dir, 'Borrendezvények Magyarországon — térkép');
if ($ld) {
    $jsonLd = $ld;
}

// Leaflet CSS (csak ezen az oldalon)
$headExtra = '<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css"'
    . ' integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin="">';

require __DIR__ . '/partials/header.php';
?>
  <div class="container">
    <div class="map-head">
      <h1>Eseménytérkép</h1>
      <span class="map-head__count"><?= count($events) ?> esemény</span>
    </div>
  </div>

  <div id="map" class="event-map" role="region" aria-label="Borrendezvények térképe"></div>

  <div class="container">
    <section class="events-section">
      <div class="events-section__head"><h2>Az események a térképen</h2></div>
      <?php if (!$events): ?>
        <p class="section-intro">Hamarosan kerülnek fel az események. 🍷</p>
      <?php else: ?>
        <ul class="map-list">
          <?php foreach ($events as $e): ?>
            <li>
              <strong><?= h($e['title']) ?></strong> —
              <?= h(formatDateRange($e['start_datetime'], $e['end_datetime'])) ?>
              <?php if (!empty($e['city'])): ?> · <?= h($e['city']) ?><?php endif; ?>
              <?php if (!empty($e['region_name'])): ?> (<?= h($e['region_name']) ?> borvidék)<?php endif; ?>
            </li>
          <?php endforeach; ?>
        </ul>
      <?php endif; ?>
    </section>
  </div>

  <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"
    integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>
  <script>
  (function () {
    if (typeof L === 'undefined') { return; }
    var pts = <?= json_encode($points, JSON_UNESCAPED_UNICODE | JSON_HEX_TAG | JSON_HEX_AMP) ?>;

    function esc(s) { var d = document.createElement('div'); d.textContent = (s == null ? '' : String(s)); return d.innerHTML; }

    var glass = '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">'
      + '<path d="M8 4h8l-1 6a3 3 0 0 1-6 0z"/><line x1="12" y1="16" x2="12" y2="20"/><line x1="9" y1="20" x2="15" y2="20"/></svg>';
    var wineIcon = L.divIcon({ className: 'wine-pin', html: glass, iconSize: [38, 38], iconAnchor: [19, 19], popupAnchor: [0, -17] });

    var map = L.map('map', { scrollWheelZoom: false }).setView([47.16, 19.50], 7);
    L.tileLayer('https://{s}.basemaps.cartocdn.com/light_all/{z}/{x}/{y}{r}.png', {
      maxZoom: 19, subdomains: 'abcd',
      attribution: '&copy; OpenStreetMap, &copy; CARTO'
    }).addTo(map);

    var bounds = [];
    pts.forEach(function (p) {
      var loc = (p.venue ? p.venue + ', ' : '') + (p.city || '');
      var html = '<div class="map-popup"><strong>' + esc(p.title) + '</strong><br>'
        + esc(p.date) + '<br>' + esc(loc)
        + (p.free ? '<br><span class="map-popup__free">Ingyenes</span>' : '') + '</div>';
      L.marker([p.lat, p.lng], { icon: wineIcon }).addTo(map).bindPopup(html);
      bounds.push([p.lat, p.lng]);
    });
    if (bounds.length) { map.fitBounds(bounds, { padding: [50, 50], maxZoom: 8 }); }
  })();
  </script>
<?php
require __DIR__ . '/partials/footer.php';
