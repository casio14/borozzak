<?php
/**
 * Közös fejléc + dokumentum nyitás.
 * Beillesztés előtt opcionálisan állítható:
 *   $pageTitle, $pageDescription
 */
$pageTitle = $pageTitle
    ?? 'holborozzak.hu — Magyarország borhoz köthető eseményei';
$pageDescription = $pageDescription
    ?? 'Magyarország borhoz köthető eseményei egy helyen — borfesztiválok, bornapok, szüreti rendezvények.';
?>
<!DOCTYPE html>
<html lang="hu">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title><?= htmlspecialchars($pageTitle, ENT_QUOTES) ?></title>
  <meta name="description" content="<?= htmlspecialchars($pageDescription, ENT_QUOTES) ?>">
  <link rel="stylesheet" href="assets/style.css">
</head>
<body>
  <header class="site-header">
    <div class="site-header__inner">
      <a class="brand" href="./">hol<span class="brand__accent">borozzak</span>.hu</a>
      <p class="brand__tagline">Magyarország borhoz köthető eseményei egy helyen</p>
    </div>
  </header>
  <main class="container">
