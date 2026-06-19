<?php
// holborozzak.hu — ideiglenes placeholder oldal.
// A valódi eseménylistát a következő inkrementumokban építjük rá.
?>
<!DOCTYPE html>
<html lang="hu">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>holborozzak.hu — hamarosan</title>
  <meta name="description" content="Magyarország borhoz köthető eseményei egy helyen.">
  <style>
    :root {
      --bor-melyveros: #4a0e1c;   /* mély burgundi */
      --bor-veros:     #722f37;   /* bor vörös */
      --bor-arany:     #c8a14b;   /* arany */
      --bor-krem:      #f5efe6;   /* krém / pergamen */
      --bor-zold:      #5a6b3b;   /* szőlőlevél zöld */
    }
    * { box-sizing: border-box; }
    body {
      margin: 0;
      min-height: 100vh;
      display: flex;
      flex-direction: column;
      align-items: center;
      justify-content: center;
      font-family: Georgia, 'Times New Roman', serif;
      color: var(--bor-krem);
      background: radial-gradient(circle at 50% 0%, var(--bor-veros) 0%, var(--bor-melyveros) 70%);
      text-align: center;
      padding: 2rem;
    }
    h1 {
      font-size: clamp(2rem, 6vw, 3.5rem);
      margin: 0 0 .5rem;
      letter-spacing: .02em;
    }
    h1 .accent { color: var(--bor-arany); }
    p.lead {
      font-size: clamp(1rem, 2.5vw, 1.25rem);
      max-width: 32rem;
      line-height: 1.6;
      color: #e9ddcf;
    }
    .divider {
      width: 80px;
      height: 3px;
      background: var(--bor-arany);
      border: none;
      margin: 1.5rem auto;
      border-radius: 2px;
    }
    footer {
      position: fixed;
      bottom: 1rem;
      font-size: .8rem;
      color: rgba(245, 239, 230, .55);
    }
  </style>
</head>
<body>
  <main>
    <h1>hol<span class="accent">borozzak</span>.hu</h1>
    <hr class="divider">
    <p class="lead">
      Magyarország borhoz köthető eseményei — borfesztiválok, bornapok és
      szüreti rendezvények egy helyen. <strong>Hamarosan!</strong>
    </p>
  </main>
  <footer>
    🍷 holborozzak.hu
  </footer>
</body>
</html>
