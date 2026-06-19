<?php
/**
 * Közös lábléc + dokumentum zárás. A verziót a CI-generált version.php adja.
 */
$APP_VERSION = 'dev';
$APP_BUILD_DATE = '';
$versionFile = __DIR__ . '/../version.php';
if (is_file($versionFile)) {
    include $versionFile; // beállítja: $APP_VERSION, $APP_BUILD_DATE
}
?>
  </main>
  <footer class="site-footer">
    <p>
      🍷 holborozzak.hu · <?= htmlspecialchars('v' . $APP_VERSION, ENT_QUOTES) ?><?php
        if ($APP_BUILD_DATE) { echo ' · ' . htmlspecialchars($APP_BUILD_DATE, ENT_QUOTES); }
      ?>
    </p>
  </footer>
</body>
</html>
