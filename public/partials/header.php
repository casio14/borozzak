<?php
/**
 * Közös fejléc + dokumentum nyitás, beépített SEO / AI-kereső (GEO) fundamentummal.
 *
 * Beillesztés előtt opcionálisan állítható:
 *   $pageTitle, $pageDescription, $canonicalUrl, $ogType, $ogImage, $robots, $jsonLd
 */
$siteName = 'holborozzak.hu';

$pageTitle = $pageTitle
    ?? 'holborozzak.hu — Magyarország borhoz köthető eseményei';
$pageDescription = $pageDescription
    ?? 'Magyarország borhoz köthető eseményei egy helyen — borfesztiválok, bornapok, szüreti rendezvények, közelgő és ingyenes programok térképpel.';

// Abszolút URL-ek a tényleges kérésből (így bármely domainen helyes: ideiglenes is, végleges is)
$scheme  = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? 'https' : 'http';
$host    = $_SERVER['HTTP_HOST'] ?? 'holborozzak.hu';
$baseUrl = $scheme . '://' . $host;
$path    = strtok($_SERVER['REQUEST_URI'] ?? '/', '?'); // query nélkül a canonicalhoz

$canonicalUrl = $canonicalUrl ?? ($baseUrl . $path);
$ogType  = $ogType  ?? 'website';
$robots  = $robots  ?? 'index,follow';

// --- Strukturált adat: alap WebSite + Organization minden oldalon ---
$defaultJsonLd = [
    [
        '@context'    => 'https://schema.org',
        '@type'       => 'WebSite',
        'name'        => $siteName,
        'url'         => $baseUrl . '/',
        'description' => $pageDescription,
        'inLanguage'  => 'hu-HU',
    ],
    [
        '@context' => 'https://schema.org',
        '@type'    => 'Organization',
        'name'     => $siteName,
        'url'      => $baseUrl . '/',
    ],
];
$jsonLd = isset($jsonLd) ? array_merge($defaultJsonLd, $jsonLd) : $defaultJsonLd;
$jsonLdFlags = JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES;
?>
<!DOCTYPE html>
<html lang="hu">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title><?= htmlspecialchars($pageTitle, ENT_QUOTES) ?></title>
  <meta name="description" content="<?= htmlspecialchars($pageDescription, ENT_QUOTES) ?>">
  <meta name="robots" content="<?= htmlspecialchars($robots, ENT_QUOTES) ?>">
  <link rel="canonical" href="<?= htmlspecialchars($canonicalUrl, ENT_QUOTES) ?>">
  <meta name="theme-color" content="#722f37">

  <!-- Open Graph -->
  <meta property="og:type" content="<?= htmlspecialchars($ogType, ENT_QUOTES) ?>">
  <meta property="og:site_name" content="<?= htmlspecialchars($siteName, ENT_QUOTES) ?>">
  <meta property="og:locale" content="hu_HU">
  <meta property="og:title" content="<?= htmlspecialchars($pageTitle, ENT_QUOTES) ?>">
  <meta property="og:description" content="<?= htmlspecialchars($pageDescription, ENT_QUOTES) ?>">
  <meta property="og:url" content="<?= htmlspecialchars($canonicalUrl, ENT_QUOTES) ?>">
<?php if (!empty($ogImage)): ?>
  <meta property="og:image" content="<?= htmlspecialchars($ogImage, ENT_QUOTES) ?>">
<?php endif; ?>

  <!-- Twitter Card -->
  <meta name="twitter:card" content="<?= !empty($ogImage) ? 'summary_large_image' : 'summary' ?>">
  <meta name="twitter:title" content="<?= htmlspecialchars($pageTitle, ENT_QUOTES) ?>">
  <meta name="twitter:description" content="<?= htmlspecialchars($pageDescription, ENT_QUOTES) ?>">
<?php if (!empty($ogImage)): ?>
  <meta name="twitter:image" content="<?= htmlspecialchars($ogImage, ENT_QUOTES) ?>">
<?php endif; ?>

  <link rel="stylesheet" href="assets/style.css">

  <!-- Strukturált adat (Schema.org JSON-LD) — SEO + AI-kereső -->
<?php foreach ($jsonLd as $block): ?>
  <script type="application/ld+json">
<?= json_encode($block, $jsonLdFlags) ?>
  </script>
<?php endforeach; ?>
</head>
<body>
  <header class="site-header">
    <div class="site-header__inner">
      <a class="brand" href="./" aria-label="holborozzak.hu — kezdőlap">
        <svg class="brand__icon" width="30" height="30" viewBox="0 0 32 32" aria-hidden="true">
          <path d="M16 7c1.8-2.6 5.2-2.8 6.6-1.8-.6 2.6-3.2 3.8-5.4 3.8" fill="#5a6b3b"/>
          <g fill="currentColor">
            <circle cx="16" cy="12" r="2.6"/>
            <circle cx="12" cy="16" r="2.6"/>
            <circle cx="20" cy="16" r="2.6"/>
            <circle cx="16" cy="16.5" r="2.6"/>
            <circle cx="13.5" cy="20.5" r="2.6"/>
            <circle cx="18.5" cy="20.5" r="2.6"/>
            <circle cx="16" cy="24.5" r="2.6"/>
          </g>
        </svg>
        <span class="brand__text">hol<span class="brand__accent">borozzak</span>.hu</span>
      </a>

      <nav class="site-nav" aria-label="Fő navigáció">
        <span class="site-nav__links">
          <!-- TODO: a céloldalak a következő inkrementumokban készülnek el -->
          <a href="#">Borvidékek</a>
          <a href="#">Naptár</a>
          <a href="#">Térkép</a>
        </span>
        <a class="site-nav__search" href="#hero-kereso" aria-label="Keresés">
          <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true">
            <circle cx="11" cy="11" r="7"/>
            <line x1="21" y1="21" x2="16.5" y2="16.5"/>
          </svg>
        </a>
      </nav>
    </div>
  </header>
  <main>
