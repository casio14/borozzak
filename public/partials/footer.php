<?php
/**
 * Közös lábléc + dokumentum zárás. A verziót a CI-generált version.php adja.
 */
$APP_VERSION = 'dev';
$versionFile = __DIR__ . '/../version.php';
if (is_file($versionFile)) {
    include $versionFile; // beállítja: $APP_VERSION (és $APP_BUILD_DATE, már nem használjuk)
}
?>
  </main>
  <footer class="site-footer">
    <div class="site-footer__inner">
      <nav class="site-footer__legal" aria-label="Jogi információk">
        <a href="impresszum.php">Impresszum</a>
        <a href="aszf.php">ÁSZF</a>
        <a href="adatvedelem.php">Adatvédelem</a>
      </nav>
      <p class="site-footer__copy">
        🍷 holborozzak.hu · © <?= date('Y') ?> · <?= htmlspecialchars('v' . $APP_VERSION, ENT_QUOTES) ?>
      </p>
    </div>
  </footer>
</body>
</html>
