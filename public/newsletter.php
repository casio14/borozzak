<?php
declare(strict_types=1);

// holborozzak.hu — hírlevél feliratkozás (POST). PRG: feldolgozás után visszairányít.

require __DIR__ . '/db.php';

$email = trim((string) ($_POST['email'] ?? ''));
$ok = false;

if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
    try {
        $pdo = db();
        // A tábla automatikus létrehozása, ha még nincs (nem kell külön migráció).
        $pdo->exec(
            "CREATE TABLE IF NOT EXISTS subscribers (
                id         INT UNSIGNED NOT NULL AUTO_INCREMENT,
                email      VARCHAR(255) NOT NULL,
                created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
                PRIMARY KEY (id),
                UNIQUE KEY uq_subscribers_email (email)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci"
        );
        $st = $pdo->prepare("INSERT IGNORE INTO subscribers (email) VALUES (:e)");
        $st->execute([':e' => mb_strtolower($email, 'UTF-8')]);
        $ok = true;
    } catch (Throwable $e) {
        error_log('newsletter.php DB hiba: ' . $e->getMessage());
    }
}

header('Location: ./?hirlevel=' . ($ok ? 'ok' : 'hiba') . '#hirlevel');
exit;
