<?php
declare(strict_types=1);

// holborozzak.hu — hírlevél feliratkozás (POST). PRG: feldolgozás után visszairányít.

require __DIR__ . '/db.php';
require __DIR__ . '/lib/subscribers.php';

$email = trim((string) ($_POST['email'] ?? ''));
$ok = false;

if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
    try {
        $pdo = db();
        ensureSubscribersTable($pdo);
        // Leiratkozó token már feliratkozáskor készül (a későbbi levelek linkjéhez).
        $st = $pdo->prepare('INSERT IGNORE INTO subscribers (email, unsubscribe_token) VALUES (:e, :t)');
        $st->execute([
            ':e' => mb_strtolower($email, 'UTF-8'),
            ':t' => bin2hex(random_bytes(16)),
        ]);
        $ok = true;
    } catch (Throwable $e) {
        error_log('newsletter.php DB hiba: ' . $e->getMessage());
    }
}

header('Location: ./?hirlevel=' . ($ok ? 'ok' : 'hiba') . '#hirlevel');
exit;
