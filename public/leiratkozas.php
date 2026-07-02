<?php
declare(strict_types=1);

// holborozzak.hu — hírlevél leiratkozás.
// Két út: tokenes link (?t=..., a kiküldött levelekből) vagy e-mail címes űrlap.
// Az űrlapos út szándékosan semleges választ ad (nem árulja el, szerepel-e a cím a listán).

require __DIR__ . '/db.php';
require __DIR__ . '/lib/events.php';
require __DIR__ . '/lib/subscribers.php';

$pageTitle = 'Leiratkozás a hírlevélről — holborozzak.hu';
$pageDescription = 'Leiratkozás a holborozzak.hu hírleveléről.';
$robots = 'noindex,follow';
$activeNav = '';

$msg = null;      // megjelenítendő üzenet
$msgOk = true;    // zöld (siker) vagy piros (hiba) stílus
$confirmToken = null; // tokenes link: megerősítésre várunk (GET-re nem törlünk!)

try {
    $pdo = db();
    ensureSubscribersTable($pdo);

    if ($_SERVER['REQUEST_METHOD'] === 'POST' && trim((string) ($_POST['t'] ?? '')) !== '') {
        // Tokenes leiratkozás megerősítése (a levélbeli link gombja).
        $st = $pdo->prepare('DELETE FROM subscribers WHERE unsubscribe_token = :t');
        $st->execute([':t' => trim((string) $_POST['t'])]);
        header('Location: leiratkozas.php?' . ($st->rowCount() > 0 ? 'kesz=1' : 'ervenytelen=1'));
        exit;
    }

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        // Űrlapos leiratkozás e-mail címmel (PRG). Semleges válasz: nem áruljuk
        // el, hogy a cím szerepelt-e a listán.
        $email = trim((string) ($_POST['email'] ?? ''));
        if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $st = $pdo->prepare('DELETE FROM subscribers WHERE email = :e');
            $st->execute([':e' => mb_strtolower($email, 'UTF-8')]);
            header('Location: leiratkozas.php?ok=1');
        } else {
            header('Location: leiratkozas.php?hiba=1');
        }
        exit;
    }

    $token = trim((string) ($_GET['t'] ?? ''));
    if ($token !== '') {
        // Tokenes link GET-tel: NEM törlünk azonnal (levelező-előolvasók ellen),
        // csak megerősítő gombot mutatunk.
        $confirmToken = $token;
    } elseif (isset($_GET['kesz'])) {
        $msg = 'Sikeresen leiratkoztál — nem küldünk több levelet erre a címre.';
    } elseif (isset($_GET['ervenytelen'])) {
        $msg = 'Ez a leiratkozó link érvénytelen, vagy erről a címről már leiratkoztál.';
        $msgOk = false;
    } elseif (isset($_GET['ok'])) {
        $msg = 'Ha a megadott cím szerepelt a listánkban, töröltük — nem kapsz több levelet.';
    } elseif (isset($_GET['hiba'])) {
        $msg = 'Érvénytelen e-mail cím. Kérlek ellenőrizd, és próbáld újra.';
        $msgOk = false;
    }
} catch (Throwable $e) {
    error_log('leiratkozas.php DB hiba: ' . $e->getMessage());
    $msg = 'Hiba történt. Kérlek próbáld újra később.';
    $msgOk = false;
}

require __DIR__ . '/partials/header.php';
?>
  <div class="container">
    <section class="news-band" style="margin-top: 1.5rem;">
      <div class="news-band__inner">
        <span class="news-band__eyebrow">🍷 Hírlevél</span>
        <h1 style="font-size: 1.7rem; margin: .2rem 0 .4rem; color: var(--wine-900);">Leiratkozás a hírlevélről</h1>

        <?php if ($msg !== null): ?>
          <p class="news-band__msg<?= $msgOk ? '' : ' news-band__msg--err' ?>"><?= h($msg) ?></p>
        <?php endif; ?>

        <?php if ($confirmToken !== null): ?>
          <p class="news-band__lead">Biztosan leiratkozol a holborozzak.hu hírleveléről?</p>
          <form class="news-form" method="post" action="leiratkozas.php" style="justify-content: center;">
            <input type="hidden" name="t" value="<?= h($confirmToken) ?>">
            <button type="submit" class="btn btn--primary">Igen, leiratkozom</button>
          </form>
        <?php else: ?>
          <p class="news-band__lead">Add meg az e-mail címed, és töröljük a hírlevél-listánkról.</p>
          <form class="news-form" method="post" action="leiratkozas.php">
            <input type="email" name="email" required placeholder="email@cim.hu" aria-label="E-mail cím">
            <button type="submit" class="btn btn--primary">Leiratkozom</button>
          </form>
        <?php endif; ?>

        <p class="news-band__note">Meggondoltad magad? <a href="./#hirlevel">Feliratkozás újra a főoldalon →</a></p>
      </div>
    </section>
  </div>
<?php
require __DIR__ . '/partials/footer.php';
