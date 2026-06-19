<?php
// holborozzak.hu — kezdőoldal.
// Jelenleg: layout váz + hero (brand, kereső). A következő inkrementumban
// ide kerül a kiemelt és közelgő események listája.

$pageTitle = 'holborozzak.hu — Magyarország borrendezvényei egy helyen';
$pageDescription = 'Fedezd fel Magyarország legjobb bor-eseményeit: fesztiválok, kóstolók '
    . 'és pincelátogatások Tokajtól Villányig — egy helyen, mindig naprakészen.';

require __DIR__ . '/partials/header.php';
?>
  <section class="hero">
    <div class="hero__inner">
      <p class="hero__eyebrow">Magyarország borrendezvényei</p>
      <h1>Fedezd fel az ország legjobb bor-eseményeit</h1>
      <p class="hero__lead">
        Fesztiválok, kóstolók és pincelátogatások Tokajtól Villányig
        — egy helyen, mindig naprakészen.
      </p>
      <!-- A kereső a listás inkrementumban lesz élesítve (akkor szűr az eseményekre). -->
      <form class="hero__search" role="search" method="get" action="">
        <input id="hero-kereso" type="search" name="q"
               placeholder="Keresés helyszín, borvidék vagy esemény szerint…"
               aria-label="Keresés helyszín, borvidék vagy esemény szerint">
        <button type="submit">Keresés</button>
      </form>
    </div>
  </section>

  <div class="container">
    <p class="section-intro">Hamarosan: a kiemelt és közelgő események listája. 🍷</p>
  </div>
<?php
require __DIR__ . '/partials/footer.php';
