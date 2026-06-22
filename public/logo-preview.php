<?php
// IDEIGLENES logó-előnézet. Élesítés előtt törölni. (noindex, hogy ne kerüljön keresőbe)
?>
<!DOCTYPE html>
<html lang="hu">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta name="robots" content="noindex">
  <title>Logó-előnézet — holborozzak.hu</title>
  <link rel="stylesheet" href="assets/style.css">
  <style>
    body { background: var(--cream); }
    .wrap { max-width: 980px; margin: 0 auto; padding: 2rem 1.25rem 4rem; }
    h1 { color: var(--wine-700); }
    .grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(260px, 1fr)); gap: 1.25rem; margin-top: 1.5rem; }
    .card { background: var(--paper); border: 1px solid var(--line); border-radius: 14px; padding: 1.5rem; box-shadow: 0 6px 24px rgba(74,14,28,.07); }
    .card h2 { margin: 0 0 1rem; font-size: 1.05rem; color: var(--wine-900); }
    .big { display: grid; place-items: center; height: 110px; }
    .big svg { width: 84px; height: 84px; }
    .lockup { display: flex; align-items: center; gap: .5rem; margin-top: 1rem; padding-top: 1rem; border-top: 1px dashed var(--line); }
    .lockup svg { width: 30px; height: 30px; }
    .lockup .wm { font-family: Georgia, serif; font-weight: 700; font-size: 1.5rem; color: var(--wine-700); }
    .lockup .wm b { color: var(--wine-900); font-weight: 700; }
    .note { color: var(--muted); font-size: .9rem; }
  </style>
</head>
<body>
  <div class="wrap">
    <h1>Logó-koncepciók</h1>
    <p class="note">Mindegyik a boros palettával (burgundi + arany + szőlőlevél-zöld). Írd meg, melyik tetszik (A/B/C/D), és bekötöm a fejlécbe — színt/részletet még finomíthatunk.</p>

    <div class="grid">

      <!-- A: térkép-tű + pohár -->
      <div class="card">
        <h2>A — Térkép-tű + borospohár <span class="note">(„hol” + „bor”)</span></h2>
        <div class="big">
          <svg viewBox="0 0 32 32" aria-hidden="true">
            <path d="M16 3C10.8 3 6.5 7.1 6.5 12.3 6.5 18.6 16 28.5 16 28.5S25.5 18.6 25.5 12.3C25.5 7.1 21.2 3 16 3Z" fill="#722f37"/>
            <g fill="#c8a14b">
              <path d="M12.7 7.2h6.6l-1.05 2.9a2.45 2.45 0 0 1-4.5 0z"/>
              <rect x="15.4" y="10.1" width="1.2" height="3.6"/>
              <rect x="13.4" y="13.4" width="5.2" height="1.2" rx=".6"/>
            </g>
          </svg>
        </div>
        <div class="lockup">
          <svg viewBox="0 0 32 32" aria-hidden="true">
            <path d="M16 3C10.8 3 6.5 7.1 6.5 12.3 6.5 18.6 16 28.5 16 28.5S25.5 18.6 25.5 12.3C25.5 7.1 21.2 3 16 3Z" fill="#722f37"/>
            <g fill="#c8a14b">
              <path d="M12.7 7.2h6.6l-1.05 2.9a2.45 2.45 0 0 1-4.5 0z"/>
              <rect x="15.4" y="10.1" width="1.2" height="3.6"/>
              <rect x="13.4" y="13.4" width="5.2" height="1.2" rx=".6"/>
            </g>
          </svg>
          <span class="wm">hol<b>borozzak</b>.hu</span>
        </div>
      </div>

      <!-- B: elegáns borospohár -->
      <div class="card">
        <h2>B — Elegáns borospohár</h2>
        <div class="big">
          <svg viewBox="0 0 32 32" aria-hidden="true" fill="none" stroke="#722f37" stroke-width="1.8" stroke-linejoin="round" stroke-linecap="round">
            <path d="M9 5h14c0 7-4 10.5-7 10.5S9 12 9 5Z"/>
            <path d="M10.4 8c1.1 4 3.6 5.4 5.6 5.4S20.5 12 21.6 8Z" fill="#c8a14b" stroke="none"/>
            <line x1="16" y1="15.5" x2="16" y2="24"/>
            <line x1="11" y1="24.5" x2="21" y2="24.5"/>
          </svg>
        </div>
        <div class="lockup">
          <svg viewBox="0 0 32 32" aria-hidden="true" fill="none" stroke="#722f37" stroke-width="1.8" stroke-linejoin="round" stroke-linecap="round">
            <path d="M9 5h14c0 7-4 10.5-7 10.5S9 12 9 5Z"/>
            <path d="M10.4 8c1.1 4 3.6 5.4 5.6 5.4S20.5 12 21.6 8Z" fill="#c8a14b" stroke="none"/>
            <line x1="16" y1="15.5" x2="16" y2="24"/>
            <line x1="11" y1="24.5" x2="21" y2="24.5"/>
          </svg>
          <span class="wm">hol<b>borozzak</b>.hu</span>
        </div>
      </div>

      <!-- C: kör alakú jelvény (badge) -->
      <div class="card">
        <h2>C — Kör jelvény (pohárral) <span class="note">favicon-barát</span></h2>
        <div class="big">
          <svg viewBox="0 0 32 32" aria-hidden="true">
            <circle cx="16" cy="16" r="14" fill="#4a0e1c"/>
            <circle cx="16" cy="16" r="14" fill="none" stroke="#c8a14b" stroke-width="1.5"/>
            <g fill="none" stroke="#e3cd97" stroke-width="1.6" stroke-linejoin="round" stroke-linecap="round">
              <path d="M11 8h10c0 5-3 7.5-5 7.5S11 13 11 8Z"/>
              <line x1="16" y1="15.5" x2="16" y2="22"/>
              <line x1="12.5" y1="22.5" x2="19.5" y2="22.5"/>
            </g>
          </svg>
        </div>
        <div class="lockup">
          <svg viewBox="0 0 32 32" aria-hidden="true">
            <circle cx="16" cy="16" r="14" fill="#4a0e1c"/>
            <circle cx="16" cy="16" r="14" fill="none" stroke="#c8a14b" stroke-width="1.5"/>
            <g fill="none" stroke="#e3cd97" stroke-width="1.6" stroke-linejoin="round" stroke-linecap="round">
              <path d="M11 8h10c0 5-3 7.5-5 7.5S11 13 11 8Z"/>
              <line x1="16" y1="15.5" x2="16" y2="22"/>
              <line x1="12.5" y1="22.5" x2="19.5" y2="22.5"/>
            </g>
          </svg>
          <span class="wm">hol<b>borozzak</b>.hu</span>
        </div>
      </div>

      <!-- D: letisztult szőlőfürt -->
      <div class="card">
        <h2>D — Letisztult szőlőfürt <span class="note">(a mostani igényesebb verziója)</span></h2>
        <div class="big">
          <svg viewBox="0 0 32 32" aria-hidden="true">
            <path d="M17 8c.5-2.5 2.8-3.8 5-3.6-.2 2.4-2.2 4-4.3 4.2" fill="#5a6b3b"/>
            <g fill="#722f37">
              <circle cx="13" cy="12" r="2.4"/><circle cx="17.8" cy="12" r="2.4"/>
              <circle cx="15.4" cy="15.6" r="2.4"/><circle cx="11" cy="16" r="2.4"/>
              <circle cx="20" cy="16" r="2.4"/><circle cx="13" cy="19.4" r="2.4"/>
              <circle cx="17.8" cy="19.4" r="2.4"/><circle cx="15.4" cy="23" r="2.4"/>
            </g>
          </svg>
        </div>
        <div class="lockup">
          <svg viewBox="0 0 32 32" aria-hidden="true">
            <path d="M17 8c.5-2.5 2.8-3.8 5-3.6-.2 2.4-2.2 4-4.3 4.2" fill="#5a6b3b"/>
            <g fill="#722f37">
              <circle cx="13" cy="12" r="2.4"/><circle cx="17.8" cy="12" r="2.4"/>
              <circle cx="15.4" cy="15.6" r="2.4"/><circle cx="11" cy="16" r="2.4"/>
              <circle cx="20" cy="16" r="2.4"/><circle cx="13" cy="19.4" r="2.4"/>
              <circle cx="17.8" cy="19.4" r="2.4"/><circle cx="15.4" cy="23" r="2.4"/>
            </g>
          </svg>
          <span class="wm">hol<b>borozzak</b>.hu</span>
        </div>
      </div>

    </div>
  </div>
</body>
</html>
