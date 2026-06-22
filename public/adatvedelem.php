<?php
// Adatkezelési tájékoztató — SABLON. A [...] helyeket töltsd ki, és nézesd át jogásszal.
$pageTitle = 'Adatkezelési tájékoztató — holborozzak.hu';
$pageDescription = 'A holborozzak.hu adatkezelési és adatvédelmi tájékoztatója (GDPR).';
require __DIR__ . '/partials/header.php';
?>
  <div class="container">
    <article class="legal">
      <h1>Adatkezelési tájékoztató</h1>

      <p class="legal-note">⚠️ Ez egy kitöltendő sablon/vázlat a GDPR alapján.
        Élesítés előtt töltsd ki és vizsgáltasd át adatvédelmi szakértővel/jogásszal.</p>

      <p>Hatályos: [dátum]</p>

      <h2>1. Az adatkezelő</h2>
      <p>[Üzemeltető neve], székhely: [cím], e-mail: [e-mail].</p>

      <h2>2. Milyen adatokat kezelünk?</h2>
      <ul>
        <li><strong>Látogatottsági statisztika:</strong> az események megtekintései és
          kattintásai, <strong>anonimizált (hashelt) IP-cím</strong>, böngésző-azonosító
          (user agent), hivatkozó oldal — a szolgáltatás működtetése és statisztika céljából.</li>
        <li><strong>Munkamenet-azonosító (süti):</strong> az egyedi látogatók becsléséhez.</li>
      </ul>
      <p>Közvetlen személyazonosításra alkalmas adatot (név, e-mail) jelenleg nem
        gyűjtünk a látogatóktól.</p>

      <h2>3. Az adatkezelés célja és jogalapja</h2>
      <p>Cél: a weboldal működtetése, a tartalom fejlesztése és látogatottsági
        statisztika. Jogalap: az üzemeltető jogos érdeke, illetve sütik esetén a
        Felhasználó hozzájárulása.</p>

      <h2>4. Sütik (cookie-k)</h2>
      <p>A weboldal a működéshez és a statisztikához sütiket használhat. A
        hozzájárulásról szóló tájékoztató/beállítás a [süti-sáv] segítségével érhető el.</p>

      <h2>5. Adattárolás ideje</h2>
      <p>A statisztikai adatokat [időtartam] ideig őrizzük, azt követően töröljük
        vagy anonimizáljuk.</p>

      <h2>6. Adatfeldolgozók</h2>
      <p>Tárhelyszolgáltató: Rackhost Zrt. (lásd Impresszum).</p>

      <h2>7. Az érintett jogai</h2>
      <p>A Felhasználót megilleti a tájékoztatáshoz, hozzáféréshez, helyesbítéshez,
        törléshez, korlátozáshoz és tiltakozáshoz való jog. Kérelmek: [e-mail].</p>

      <h2>8. Jogorvoslat</h2>
      <p>Panasszal a Nemzeti Adatvédelmi és Információszabadság Hatósághoz (NAIH)
        lehet fordulni (1055 Budapest, Falk Miksa utca 9-11.; ugyfelszolgalat@naih.hu).</p>
    </article>
  </div>
<?php
require __DIR__ . '/partials/footer.php';
