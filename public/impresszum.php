<?php
// Impresszum — SABLON. A [...] helyeket töltsd ki, és nézesd át jogásszal.
$pageTitle = 'Impresszum — holborozzak.hu';
$pageDescription = 'A holborozzak.hu üzemeltetőjének adatai és impresszuma.';
require __DIR__ . '/partials/header.php';
?>
  <div class="container">
    <article class="legal">
      <h1>Impresszum</h1>

      <p class="legal-note">⚠️ Ez egy kitöltendő sablon. A valós adatok megadása és
        jogi átnézés szükséges az élesítés előtt.</p>

      <h2>Az üzemeltető (szolgáltató) adatai</h2>
      <address>
        <strong>Név / cégnév:</strong> [Üzemeltető neve]<br>
        <strong>Székhely:</strong> [irányítószám, település, utca, házszám]<br>
        <strong>Adószám:</strong> [adószám]<br>
        <strong>Cégjegyzék-/nyilvántartási szám:</strong> [szám]<br>
        <strong>E-mail:</strong> [kapcsolati e-mail]<br>
        <strong>Telefon:</strong> [telefonszám]
      </address>

      <h2>Tárhelyszolgáltató</h2>
      <address>
        <strong>Név:</strong> Rackhost Zrt.<br>
        <strong>Cím:</strong> [Rackhost székhely — ellenőrizd a rackhost.hu oldalon]<br>
        <strong>E-mail:</strong> [Rackhost ügyfélszolgálati e-mail]<br>
        <strong>Weboldal:</strong> https://www.rackhost.hu
      </address>

      <h2>A weboldal célja</h2>
      <p>A holborozzak.hu Magyarország borhoz köthető eseményeit (borfesztiválok,
        bornapok, szüreti rendezvények) gyűjti össze és listázza tájékoztató jelleggel.</p>

      <h2>Szerzői jog</h2>
      <p>A weboldalon megjelenő tartalmak szerzői jogi védelem alatt állnak. Az
        események adatai tájékoztató jellegűek; a pontosságért a szervezők felelnek.</p>
    </article>
  </div>
<?php
require __DIR__ . '/partials/footer.php';
