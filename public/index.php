<?php
// holborozzak.hu — kezdőoldal.
// Jelenleg: layout váz + hero (brand). A következő inkrementumban ide kerül
// a kiemelt és közelgő események listája.

$pageTitle = 'holborozzak.hu — Magyarország borrendezvényei egy helyen';
$pageDescription = 'Fedezd fel Magyarország legjobb borrendezvényeit: borfesztiválok, '
    . 'bornapok és szüreti rendezvények egy helyen — közelgő, kiemelt és ingyenes programok, térképpel.';

require __DIR__ . '/partials/header.php';
?>
  <section class="hero">
    <div class="hero__inner">
      <h1>Fedezd fel Magyarország legjobb borrendezvényeit</h1>
      <hr class="divider">
      <p class="hero__lead">
        Borfesztiválok, bornapok és szüreti rendezvények egy helyen — közelgő,
        kiemelt és ingyenes programok, térképpel.
      </p>
    </div>
  </section>

  <div class="container">
    <p class="section-intro">Hamarosan: a kiemelt és közelgő események listája. 🍷</p>
  </div>
<?php
require __DIR__ . '/partials/footer.php';
