<?php
// holborozzak.hu — kezdőoldal.
// Jelenleg a layout váz (fejléc/lábléc/CSS) + ideiglenes hero.
// A következő inkrementumban ide kerül az eseménylista.

$pageTitle = 'holborozzak.hu — Magyarország borhoz köthető eseményei';
require __DIR__ . '/partials/header.php';
?>
  <section class="hero">
    <h1>Hamarosan: a magyar boros események egy helyen</h1>
    <hr class="divider">
    <p>
      Borfesztiválok, bornapok és szüreti rendezvények — közelgő, kiemelt és
      ingyenes események, térképpel. Most épül az eseménylista. 🍷
    </p>
  </section>
<?php
require __DIR__ . '/partials/footer.php';
