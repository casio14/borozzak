<?php
declare(strict_types=1);

// holborozzak.hu — Eseménynaptár: havi naptárrács, dátum szerinti böngészés.

require __DIR__ . '/db.php';
require __DIR__ . '/lib/events.php';

$base = ((!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? 'https' : 'http')
    . '://' . ($_SERVER['HTTP_HOST'] ?? 'holborozzak.hu');
$dir = rtrim(dirname($_SERVER['SCRIPT_NAME'] ?? '/'), '/');

$now = new DateTimeImmutable('now');
$year  = (int) ($_GET['ev'] ?? $now->format('Y'));
$month = (int) ($_GET['ho'] ?? $now->format('n'));
if ($month < 1 || $month > 12) { $month = (int) $now->format('n'); }
if ($year < 2000 || $year > 2100) { $year = (int) $now->format('Y'); }

$first       = $now->setDate($year, $month, 1)->setTime(0, 0, 0);
$daysInMonth = (int) $first->format('t');
$monthStart  = $first;
$monthEnd    = $first->setDate($year, $month, $daysInMonth)->setTime(23, 59, 59);
$prev        = $first->modify('-1 month');
$next        = $first->modify('+1 month');
$monthTitle  = $year . '. ' . HU_MONTHS[$month];

$events = [];
try {
    $events = fetchEventsBetween(db(), $monthStart->format('Y-m-d H:i:s'), $monthEnd->format('Y-m-d H:i:s'));
} catch (Throwable $e) {
    error_log('naptar.php DB hiba: ' . $e->getMessage());
}

// Napra bontás: minden napra a rá eső események
$daysEvents = array_fill(1, $daysInMonth, []);
foreach ($events as $e) {
    $s  = new DateTimeImmutable($e['start_datetime']);
    $en = !empty($e['end_datetime']) ? new DateTimeImmutable($e['end_datetime']) : $s;
    $startDay = ($s  < $monthStart) ? 1 : (int) $s->format('j');
    $endDay   = ($en > $monthEnd)   ? $daysInMonth : (int) $en->format('j');
    for ($d = max(1, $startDay); $d <= min($daysInMonth, $endDay); $d++) {
        $daysEvents[$d][] = $e;
    }
}

$pageTitle = "Eseménynaptár — {$monthTitle} | holborozzak.hu";
$pageDescription = "Borrendezvények naptára ({$monthTitle}): nézd meg, mely napokon vannak "
    . "borfesztiválok, bornapok és kóstolók Magyarországon.";
$activeNav = 'naptar';

$ld = eventsItemListJsonLd($events, $base, $dir, "Borrendezvények — {$monthTitle}");
if ($ld) {
    $jsonLd = $ld;
}

require __DIR__ . '/partials/header.php';
?>
  <div class="container">
    <div class="cal-head">
      <h1>Eseménynaptár</h1>
      <div class="cal-nav">
        <a class="cal-nav__btn" href="naptar.php?ev=<?= $prev->format('Y') ?>&amp;ho=<?= $prev->format('n') ?>" aria-label="Előző hónap">‹</a>
        <span class="cal-nav__title"><?= h($monthTitle) ?></span>
        <a class="cal-nav__btn" href="naptar.php?ev=<?= $next->format('Y') ?>&amp;ho=<?= $next->format('n') ?>" aria-label="Következő hónap">›</a>
        <a class="cal-nav__today" href="naptar.php">Ma</a>
      </div>
    </div>

    <div class="cal">
      <div class="cal__dow"><span>Hét</span><span>Kedd</span><span>Sze</span><span>Csüt</span><span>Pén</span><span>Szo</span><span>Vas</span></div>
      <div class="cal__grid">
        <?php
        $leading = (int) $first->format('N') - 1;          // hány üres cella a hónap eleje előtt
        for ($i = 0; $i < $leading; $i++) {
            echo '<div class="cal__cell cal__cell--blank"></div>';
        }
        for ($d = 1; $d <= $daysInMonth; $d++):
            $isToday = ($year === (int) $now->format('Y') && $month === (int) $now->format('n') && $d === (int) $now->format('j'));
            $dayEvents = $daysEvents[$d];
        ?>
          <div class="cal__cell<?= $isToday ? ' cal__cell--today' : '' ?>">
            <span class="cal__day"><?= $d ?></span>
            <?php foreach (array_slice($dayEvents, 0, 3) as $e): ?>
              <a class="cal__event<?= (int) $e['is_free'] === 1 ? ' cal__event--free' : '' ?>"
                 href="<?= h(eventUrl($e)) ?>" title="<?= h($e['title']) ?>"><?= h($e['title']) ?></a>
            <?php endforeach; ?>
            <?php if (count($dayEvents) > 3): ?>
              <span class="cal__more">+<?= count($dayEvents) - 3 ?> további</span>
            <?php endif; ?>
          </div>
        <?php endfor; ?>
        <?php
        $trailing = (7 - (($leading + $daysInMonth) % 7)) % 7;
        for ($i = 0; $i < $trailing; $i++) {
            echo '<div class="cal__cell cal__cell--blank"></div>';
        }
        ?>
      </div>
    </div>

    <?php if (!$events): ?>
      <p class="section-intro">Ebben a hónapban nincs rögzített esemény.
        <a href="esemenyek.php">Nézd meg az összeset →</a></p>
    <?php endif; ?>
  </div>
<?php
require __DIR__ . '/partials/footer.php';
