<?php
// IDEIGLENES dizájn-előnézet (lista/kártya ötvözet). Élesítés előtt törölni. (noindex)
?>
<!DOCTYPE html>
<html lang="hu">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta name="robots" content="noindex">
  <title>Dizájn-előnézet — eseménylista ötvözet</title>
  <link rel="stylesheet" href="assets/style.css">
  <style>
    body { background: var(--cream); }
    .pwrap { max-width: var(--maxw); margin: 0 auto; padding: 2rem 1.25rem 4rem; }
    .ptitle { color: var(--wine-700); }
    .pnote { color: var(--muted); font-size: .92rem; }
    .psection { margin: 2.5rem 0; }
    .plabel { display:inline-block; background:var(--wine-700); color:var(--cream); font-size:.8rem; font-weight:700; padding:.3rem .7rem; border-radius:999px; margin-bottom:1rem; }

    /* --- "Gazdag sorok" lista --- */
    .lst { background: var(--paper); border:1px solid var(--line); border-radius:14px; overflow:hidden; box-shadow: var(--shadow); }
    .lst__month { display:flex; align-items:center; gap:.55rem; padding:.55rem 1.1rem; background:#f3eee4; color:#7a6f66; font-weight:700; font-size:.85rem; text-transform:uppercase; letter-spacing:.05em; }
    .lst__dot { width:10px; height:10px; border-radius:3px; }
    .row { display:flex; align-items:center; gap:1rem; padding:.85rem 1.1rem; border-top:1px solid var(--line); text-decoration:none; color:inherit; }
    .row:first-of-type { border-top:none; }
    .row:hover { background:#faf6ef; }
    .row__img { width:64px; height:64px; border-radius:10px; object-fit:cover; flex:none; background:var(--wine-900); }
    .row__main { flex:1; min-width:0; }
    .row__title { margin:0; font-size:1.05rem; font-weight:700; color:var(--ink); }
    .row__sub { margin:.15rem 0 0; color:var(--muted); font-size:.9rem; }
    .row__right { display:flex; align-items:center; gap:1.25rem; white-space:nowrap; }
    .row__loc { color:var(--muted); font-size:.9rem; }
    .row__date { color:var(--wine-700); font-weight:700; font-size:.9rem; }
    .row__chev { color:var(--wine-700); font-size:1.1rem; }
    .pill { display:inline-block; font-size:.68rem; font-weight:700; padding:.12rem .5rem; border-radius:999px; background:var(--gold); color:var(--wine-900); margin-left:.5rem; vertical-align:middle; text-transform:uppercase; letter-spacing:.03em; }
    .pill--free { background:#e6efe0; color:#3f5a2a; }
    @media (max-width:640px){ .row__loc, .row__chev { display:none; } }
  </style>
</head>
<body>
  <div class="pwrap">
    <h1 class="ptitle">Eseménylista — ötvözet javaslatok</h1>
    <p class="pnote">Mintaadatokkal, a boros palettával. Írd meg, melyik irány tetszik (1 vagy 2), vagy mit kombináljunk belőlük.</p>

    <!-- ============ 1. JAVASLAT: GAZDAG SOROK ============ -->
    <div class="psection">
      <span class="plabel">1. javaslat — „Gazdag sorok" (hónapokra bontva)</span>
      <div class="lst">
        <div class="lst__month"><span class="lst__dot" style="background:#5a6b3b"></span> Július</div>

        <a class="row" href="#">
          <img class="row__img" src="assets/hero.jpg" alt="">
          <div class="row__main">
            <p class="row__title">Szent György-hegy Hajnalig <span class="pill pill--free">Ingyenes</span></p>
            <p class="row__sub">Borvidéki program · esti borkóstoló</p>
          </div>
          <div class="row__right">
            <span class="row__loc">Badacsonyi borvidék</span>
            <span class="row__date">júl. 18.</span>
            <span class="row__chev">→</span>
          </div>
        </a>

        <a class="row" href="#">
          <img class="row__img" src="assets/hero.jpg" alt="">
          <div class="row__main">
            <p class="row__title">Egri Bikavér Ünnep</p>
            <p class="row__sub">Borfesztivál · gasztronómia</p>
          </div>
          <div class="row__right">
            <span class="row__loc">Eger, Dobó tér</span>
            <span class="row__date">júl. 25 — 27.</span>
            <span class="row__chev">→</span>
          </div>
        </a>

        <div class="lst__month"><span class="lst__dot" style="background:#c8a14b"></span> Szeptember</div>

        <a class="row" href="#">
          <img class="row__img" src="assets/hero.jpg" alt="">
          <div class="row__main">
            <p class="row__title">Budapesti Borfesztivál <span class="pill">Kiemelt</span></p>
            <p class="row__sub">Borfesztivál · kóstoló</p>
          </div>
          <div class="row__right">
            <span class="row__loc">Budapest, Budai Vár</span>
            <span class="row__date">szept. 10 — 13.</span>
            <span class="row__chev">→</span>
          </div>
        </a>

        <div class="lst__month"><span class="lst__dot" style="background:#b5562a"></span> Október</div>

        <a class="row" href="#">
          <img class="row__img" src="assets/hero.jpg" alt="">
          <div class="row__main">
            <p class="row__title">Tokaji Szüreti Napok</p>
            <p class="row__sub">Szüreti rendezvény · borvidéki program</p>
          </div>
          <div class="row__right">
            <span class="row__loc">Tokaj</span>
            <span class="row__date">okt. 3 — 5.</span>
            <span class="row__chev">→</span>
          </div>
        </a>

        <a class="row" href="#">
          <img class="row__img" src="assets/hero.jpg" alt="">
          <div class="row__main">
            <p class="row__title">Villányi Vörösbor Fesztivál <span class="pill">Kiemelt</span></p>
            <p class="row__sub">Borfesztivál · koncertek</p>
          </div>
          <div class="row__right">
            <span class="row__loc">Villány</span>
            <span class="row__date">okt. 9 — 11.</span>
            <span class="row__chev">→</span>
          </div>
        </a>
      </div>
    </div>

    <!-- ============ 2. JAVASLAT: KIEMELT KÁRTYÁK + SOROK ============ -->
    <div class="psection">
      <span class="plabel">2. javaslat — Kiemelt kártyák felül + gazdag sorok alul</span>

      <div class="events-section__head">
        <h2 style="margin:.5rem 0 1rem; color:var(--wine-900);">Kiemelt események</h2>
      </div>
      <div class="events-grid">
        <article class="event-card">
          <a class="event-card__media" href="#">
            <img src="assets/hero.jpg" alt="Budapesti Borfesztivál" loading="lazy">
            <span class="event-card__badge">Kiemelt</span>
          </a>
          <div class="event-card__body">
            <p class="event-card__date"><time datetime="2026-09-10">2026. szept. 10–13.</time></p>
            <h3 class="event-card__title"><a href="#">Budapesti Borfesztivál</a></h3>
            <p class="event-card__meta">📍 Budapest, Budai Vár</p>
            <div class="event-card__tags"><span class="tag">Borfesztivál</span><span class="tag">Kóstoló</span></div>
          </div>
        </article>
        <article class="event-card">
          <a class="event-card__media" href="#">
            <img src="assets/hero.jpg" alt="Villányi Vörösbor Fesztivál" loading="lazy">
            <span class="event-card__badge">Kiemelt</span>
          </a>
          <div class="event-card__body">
            <p class="event-card__date"><time datetime="2026-10-09">2026. okt. 9–11.</time></p>
            <h3 class="event-card__title"><a href="#">Villányi Vörösbor Fesztivál</a></h3>
            <p class="event-card__meta">📍 Villány</p>
            <div class="event-card__tags"><span class="tag">Borfesztivál</span><span class="tag">Koncert</span></div>
          </div>
        </article>
      </div>

      <div class="events-section__head">
        <h2 style="margin:2rem 0 1rem; color:var(--wine-900);">Közelgő események</h2>
      </div>
      <div class="lst">
        <div class="lst__month"><span class="lst__dot" style="background:#5a6b3b"></span> Július</div>
        <a class="row" href="#">
          <img class="row__img" src="assets/hero.jpg" alt="">
          <div class="row__main">
            <p class="row__title">Szent György-hegy Hajnalig <span class="pill pill--free">Ingyenes</span></p>
            <p class="row__sub">Borvidéki program</p>
          </div>
          <div class="row__right">
            <span class="row__loc">Badacsonyi borvidék</span>
            <span class="row__date">júl. 18.</span>
            <span class="row__chev">→</span>
          </div>
        </a>
        <a class="row" href="#">
          <img class="row__img" src="assets/hero.jpg" alt="">
          <div class="row__main">
            <p class="row__title">Egri Bikavér Ünnep</p>
            <p class="row__sub">Borfesztivál · gasztronómia</p>
          </div>
          <div class="row__right">
            <span class="row__loc">Eger, Dobó tér</span>
            <span class="row__date">júl. 25 — 27.</span>
            <span class="row__chev">→</span>
          </div>
        </a>
        <div class="lst__month"><span class="lst__dot" style="background:#b5562a"></span> Október</div>
        <a class="row" href="#">
          <img class="row__img" src="assets/hero.jpg" alt="">
          <div class="row__main">
            <p class="row__title">Tokaji Szüreti Napok</p>
            <p class="row__sub">Szüreti rendezvény</p>
          </div>
          <div class="row__right">
            <span class="row__loc">Tokaj</span>
            <span class="row__date">okt. 3 — 5.</span>
            <span class="row__chev">→</span>
          </div>
        </a>
      </div>
    </div>

  </div>
</body>
</html>
