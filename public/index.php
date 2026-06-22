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
    <!-- 1. inkrementum: KÁRTYA-DIZÁJN statikus mintákkal. A valódi adatok a DB-ből
         a következő lépésben jönnek; a kép most a hero fotó placeholderként. -->
    <section class="events-section">
      <div class="events-section__head">
        <h2>Közelgő események</h2>
        <a class="events-section__more" href="#">Összes esemény →</a>
      </div>

      <div class="events-grid">

        <article class="event-card">
          <a class="event-card__media" href="#">
            <img src="assets/hero.jpg" alt="Budapesti Borfesztivál a Budai Várban" loading="lazy">
            <span class="event-card__badge">Kiemelt</span>
          </a>
          <div class="event-card__body">
            <p class="event-card__date"><time datetime="2026-09-10">2026. szept. 10–13.</time></p>
            <h3 class="event-card__title"><a href="#">Budapesti Borfesztivál</a></h3>
            <p class="event-card__meta">📍 Budapest, Budai Vár</p>
            <div class="event-card__tags">
              <span class="tag">Borfesztivál</span>
              <span class="tag">Kóstoló</span>
            </div>
          </div>
        </article>

        <article class="event-card">
          <a class="event-card__media" href="#">
            <img src="assets/hero.jpg" alt="Szent György-hegy Hajnalig borvidéki program" loading="lazy">
          </a>
          <div class="event-card__body">
            <p class="event-card__date"><time datetime="2026-07-18">2026. júl. 18.</time></p>
            <h3 class="event-card__title"><a href="#">Szent György-hegy Hajnalig</a></h3>
            <p class="event-card__meta">📍 Badacsonyi borvidék</p>
            <div class="event-card__tags">
              <span class="tag">Borvidéki program</span>
              <span class="tag tag--free">Ingyenes</span>
            </div>
          </div>
        </article>

        <article class="event-card">
          <a class="event-card__media" href="#">
            <img src="assets/hero.jpg" alt="Egri Bikavér Ünnep a belvárosban" loading="lazy">
          </a>
          <div class="event-card__body">
            <p class="event-card__date"><time datetime="2026-07-25">2026. júl. 25–27.</time></p>
            <h3 class="event-card__title"><a href="#">Egri Bikavér Ünnep</a></h3>
            <p class="event-card__meta">📍 Eger, Dobó tér</p>
            <div class="event-card__tags">
              <span class="tag">Borfesztivál</span>
              <span class="tag">Gasztronómia</span>
            </div>
          </div>
        </article>

      </div>
    </section>
  </div>
<?php
require __DIR__ . '/partials/footer.php';
