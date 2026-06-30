<?php
declare(strict_types=1);

require __DIR__ . '/auth.php';
require __DIR__ . '/../lib/events.php';
require_admin();

// Beküldött (jóváhagyásra váró) események + állapot-számlálók
$drafts = [];
$counts = ['draft' => 0, 'published' => 0, 'cancelled' => 0];
try {
    $pdo = db();
    $drafts = $pdo->query(
        "SELECT id, title, city, start_datetime, submitter_name, submitter_email, created_at
         FROM events WHERE status = 'draft' ORDER BY created_at DESC"
    )->fetchAll();
    foreach ($pdo->query("SELECT status, COUNT(*) AS c FROM events GROUP BY status") as $r) {
        $counts[$r['status']] = (int) $r['c'];
    }
} catch (Throwable $e) {
    error_log('admin index DB hiba: ' . $e->getMessage());
}

$cssVer = @filemtime(__DIR__ . '/../assets/style.css') ?: time();
?>
<!DOCTYPE html>
<html lang="hu">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta name="robots" content="noindex,nofollow">
  <title>Admin — holborozzak.hu</title>
  <link rel="stylesheet" href="../assets/style.css?v=<?= $cssVer ?>">
</head>
<body class="admin-body">
  <div class="admin-bar">
    <span class="admin-bar__title">holborozzak.hu — admin</span>
    <span><a href="../" target="_blank">Oldal megtekintése ↗</a> &nbsp;·&nbsp; <a href="logout.php">Kilépés</a></span>
  </div>

  <main class="admin-main">
    <h1>Beküldött események</h1>
    <p class="admin-stats">
      Jóváhagyásra vár: <strong><?= (int) $counts['draft'] ?></strong> ·
      Közzétett: <strong><?= (int) $counts['published'] ?></strong> ·
      Lemondott: <strong><?= (int) $counts['cancelled'] ?></strong>
    </p>

    <?php if (!$drafts): ?>
      <div class="admin-empty">Jelenleg nincs jóváhagyásra váró beküldés. 🍷</div>
    <?php else: ?>
      <table class="admin-table">
        <thead>
          <tr>
            <th>Esemény</th>
            <th>Helyszín</th>
            <th>Időpont</th>
            <th>Beküldő</th>
            <th>Beküldve</th>
          </tr>
        </thead>
        <tbody>
          <?php foreach ($drafts as $d): ?>
            <tr>
              <td>
                <strong><?= h($d['title']) ?></strong>
                <span class="admin-pill admin-pill--draft">draft</span>
              </td>
              <td><?= h($d['city'] ?: '—') ?></td>
              <td><?= h(formatDateRange($d['start_datetime'], null)) ?></td>
              <td>
                <?= h($d['submitter_name'] ?: '—') ?><br>
                <?php if (!empty($d['submitter_email'])): ?>
                  <a href="mailto:<?= h($d['submitter_email']) ?>"><?= h($d['submitter_email']) ?></a>
                <?php endif; ?>
              </td>
              <td><?= h((new DateTimeImmutable($d['created_at']))->format('Y-m-d H:i')) ?></td>
            </tr>
          <?php endforeach; ?>
        </tbody>
      </table>
      <p class="admin-note">A jóváhagyás / szerkesztés / elutasítás műveletek a következő
        lépésben kerülnek be — előbb egyeztetjük, hogyan szeretnéd kezelni őket.</p>
    <?php endif; ?>
  </main>
</body>
</html>
