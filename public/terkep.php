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
            'url'   => eventUrl($e),
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
    . ' integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin="">'
    . '<link rel="stylesheet" href="https://unpkg.com/leaflet.markercluster@1.5.3/dist/MarkerCluster.css">';

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
  <script src="https://unpkg.com/leaflet.markercluster@1.5.3/dist/leaflet.markercluster.js"></script>
  <script>
  (function () {
    if (typeof L === 'undefined') { return; }
    var pts = <?= json_encode($points, JSON_UNESCAPED_UNICODE | JSON_HEX_TAG | JSON_HEX_AMP) ?>;

    function esc(s) { var d = document.createElement('div'); d.textContent = (s == null ? '' : String(s)); return d.innerHTML; }

    var grape = '<span class="grape-pin__icon"><svg viewBox="0 0 24 24" fill="currentColor">'
      + '<path d="M12 6.4c0-2 1.4-3.4 3.6-3.4" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"/>'
      + '<circle cx="10" cy="9" r="1.7"/><circle cx="14" cy="9" r="1.7"/>'
      + '<circle cx="8" cy="12.5" r="1.7"/><circle cx="12" cy="12.5" r="1.7"/><circle cx="16" cy="12.5" r="1.7"/>'
      + '<circle cx="10" cy="16" r="1.7"/><circle cx="14" cy="16" r="1.7"/><circle cx="12" cy="19.2" r="1.5"/></svg></span>';

    function pin(count, size) {
      return L.divIcon({
        className: 'grape-pin',
        html: grape + '<span class="grape-pin__count">' + count + '</span>',
        iconSize: [size, size], iconAnchor: [size / 2, size / 2], popupAnchor: [0, -(size / 2) + 2]
      });
    }

    var map = L.map('map', { scrollWheelZoom: false }).setView([47.16, 19.50], 7);
    L.tileLayer('https://{s}.basemaps.cartocdn.com/light_all/{z}/{x}/{y}{r}.png', {
      maxZoom: 19, subdomains: 'abcd',
      attribution: '&copy; OpenStreetMap, &copy; CARTO'
    }).addTo(map);

    // Zoom-alapú összevonás: kicsinyítve aggregál, ráközelítve szétválik
    var cluster = L.markerClusterGroup({
      showCoverageOnHover: false,
      maxClusterRadius: 50,
      iconCreateFunction: function (c) { return pin(c.getChildCount(), 46); }
    });

    var bounds = [];
    pts.forEach(function (p) {
      var loc = (p.venue ? p.venue + ', ' : '') + (p.city || '');
      var html = '<div class="map-popup"><strong>' + esc(p.title) + '</strong><br>'
        + esc(p.date) + '<br>' + esc(loc)
        + (p.free ? '<br><span class="map-popup__free">Ingyenes</span>' : '')
        + '<br><a class="map-popup__link" href="' + esc(p.url) + '">Részletek &rarr;</a></div>';
      cluster.addLayer(L.marker([p.lat, p.lng], { icon: pin(1, 40) }).bindPopup(html));
      bounds.push([p.lat, p.lng]);
    });
    map.addLayer(cluster);
    if (bounds.length) { map.fitBounds(bounds, { padding: [50, 50], maxZoom: 8 }); }
  })();
  </script>
<?php
require __DIR__ . '/partials/footer.php';
