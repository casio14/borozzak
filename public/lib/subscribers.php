<?php
declare(strict_types=1);

// holborozzak.hu — hírlevél-feliratkozók: közös tábla-létrehozás (newsletter.php + leiratkozas.php).

/**
 * Létrehozza a subscribers táblát, ha még nincs; régebbi telepítésnél pótolja
 * az unsubscribe_token oszlopot (a leiratkozó linkekhez).
 */
function ensureSubscribersTable(PDO $pdo): void
{
    $pdo->exec(
        "CREATE TABLE IF NOT EXISTS subscribers (
            id                INT UNSIGNED NOT NULL AUTO_INCREMENT,
            email             VARCHAR(255) NOT NULL,
            unsubscribe_token VARCHAR(64) NULL,
            created_at        TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
            PRIMARY KEY (id),
            UNIQUE KEY uq_subscribers_email (email)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci"
    );

    // Token-oszlop pótlása, ha a tábla még a régi séma szerint jött létre.
    $st = $pdo->query(
        "SELECT COUNT(*) FROM information_schema.COLUMNS
         WHERE TABLE_SCHEMA = DATABASE() AND TABLE_NAME = 'subscribers'
           AND COLUMN_NAME = 'unsubscribe_token'"
    );
    if ((int) $st->fetchColumn() === 0) {
        $pdo->exec('ALTER TABLE subscribers ADD COLUMN unsubscribe_token VARCHAR(64) NULL');
    }
}
