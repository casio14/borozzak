# holborozzak.hu

## Mi ez a projekt?

Egy weboldal, amely összegyűjti és felsorolja a **magyarországi borhoz köthető
eseményeket** (borfesztiválok, bornapok, szüreti rendezvények stb.).

Példák az eseményekre:
- Budapesti Bor Napok
- Szent György-hegy Hajnalig
- (és további magyar boros rendezvények)

A weboldal címe: **holborozzak.hu**

## Cél és terjedelem

- A fő funkció egyszerű: **az események felsorolása** (lista/áttekintés).
- Nincs szükség bonyolult funkciókra (regisztráció, fizetés stb.) — a hangsúly
  a rendezvények áttekinthető megjelenítésén van.

## Design / megjelenés

- **Borhoz köthető színek** használata (pl. mély bordó/burgundi vörös, szőlőlevél-zöld,
  arany/aranysárga, krém/pergamen háttér).
- Magyar nyelvű felület.

## Technikai megjegyzések

- **Adattárolás:** MySQL adatbázis (saját szerveren). Az események adatait
  adatbázisban tároljuk, nem statikus fájlban.
- **Tech stack:** PHP (szerveroldali renderelés) + MySQL adatbázis.
  Frontend: sima HTML/CSS (borhoz köthető színek). Nincs build lépés.
- **Webszerver (Rackhost, FTP):**
  - Kiszolgáló: `wh11.rackhost.hu`
  - Felhasználónév: `c105746ptrk`
  - Célkönyvtár (deploy ide): `/web/kissptrk.hu/`
  - Jelszó: **GitHub repository secret**-ben (`FTP_PASSWORD`), NEM a kódban.
  - Megjegyzés: a domain `holborozzak.hu`, de a könyvtár neve `kissptrk.hu` —
    ellenőrizni, hogy ez-e a `holborozzak.hu` document rootja.
- **GitHub repo:** `git@github.com:casio14/borozzak.git`
- **Deploy:** GitHub Actions (`.github/workflows/deploy.yml`) → `main`-re
  pusholáskor a `public/` mappa tartalmát felmásolja a webszerverre
  (`SamKirkland/FTP-Deploy-Action`, csak a változott fájlok).
  - **Protokoll: sima `ftp`** — a Rackhost FTP szervere NEM támogatja az FTPS-t
    (`AUTH TLS` → `500`). A jelszó így titkosítatlanul utazik (lásd biztonsági TODO).
  - **Az FTP-login a docrootba érkezik**, ezért a `server-dir` RELATÍV (`borozzak/`),
    nem abszolút. Abszolút `/web/kissptrk.hu/...` duplikálná a könyvtárat.
  - **Ideiglenes cím:** https://kissptrk.hu/borozzak/ (amíg a `holborozzak.hu` nem áll).
  - **Verziózás: szemantikus, git tag-es.** A `VERSION` fájl tartja a
    `major.minor`-t (pl. `1.0`); a patch automatikusan a meglévő tagek alapján +1.
    Minden sikeres deploy `vX.Y.Z` git taget hoz létre, és a verzió megjelenik
    az oldal láblécében (a CI által generált `public/version.php`-ból).
  - **Major/minor léptetés:** kézzel írd át a `VERSION` fájlt (a patch onnantól 0-ról indul).
  - **Rollback / adott verzió:** Actions → Run workflow → a "Use workflow from"
    legördülőből válaszd a kívánt tag-et; ilyenkor nem készül új tag, csak újra deployol.
  - **TODO (takarítás):** az első hibás deploy árvafájljai a szerveren a
    `/web/kissptrk.hu/web/kissptrk.hu/borozzak/` alatt maradtak — FTP-n/fájlkezelőből törölhetők.
  - **TODO (biztonság):** ha a Rackhost ad SFTP/SSH-t, váltani titkosított feltöltésre.
- **Adatbázis (MySQL, Rackhost):**
  - Kiszolgáló: `mysql.rackhost.hu`
  - Adatbázis neve: `c105746holborozzak`
  - Felhasználónév: `c105746ptrk`
  - Jelszó: **GitHub repository secret**-ben (`DB_PASSWORD`), NEM a kódban.
  - Port: 3306 (alapértelmezett, ellenőrizni)
  - **Kívülről is elérhető** → migrációkat futtathatunk helyi gépről / CI-ből is.
- **Fejlesztési mód:** inkrementálisan haladunk, kis lépésekben.

## Funkciók (frontend)

- Letisztult, modern lista (Eventbrite-szerű kártyák).
- Kiemelt események (`is_featured`).
- Tabok: **Közelgő**, **Kiemelt**, **E hétvégén**, **E hónapban**, **Ingyenes**.
  (Ezek lekérdezések a dátum/jelölő mezőkre, nem külön adatok.)
- Térképes megjelenítés (`latitude`/`longitude`).

## SEO & AI-kereső (GEO) — KIEMELT CÉL

Erős keresőoptimalizálás **és** AI-ajánlás-barátság (ChatGPT, Perplexity, Google AI
Overviews) kiemelt projektcél. Minden új oldalnál tartsd be a `docs/seo-geo.md`
checklistet: szerveroldali HTML, szemantikus markup (`<time datetime>`), oldalankénti
egyedi title/description/canonical, slug-URL-ek, **Schema.org JSON-LD** (eseménynél
`Event`, listán `ItemList`), Open Graph/Twitter, `sitemap.xml`, és AI-crawlereket
engedő `robots.txt`. A `partials/header.php` már tartalmazza a meta/canonical/OG/
JSON-LD vázat (alap `WebSite`+`Organization`); `$jsonLd`-vel bővíthető oldalanként.

## Projekt szerkezet

- `public/` — **a deployolt weboldal** (csak ez kerül a webszerverre). PHP + HTML/CSS.
  - `db.php` — PDO MySQL kapcsolat (`db()` függvény, singleton). A configot a
    `config.php`-ból olvassa.
  - `config.php` — **generált**, NEM gitben: éles környezetben a CI hozza létre a
    `DB_PASSWORD` secretből; lokálisan a `config.example.php`-ból másolod.
  - `health.php` — ideiglenes DB-egészség ellenőrző (élesítés előtt törölni/védeni).
  - `version.php` — generált verziófájl (CI).
  - `terkep.php` — **Eseménytérkép**: Leaflet + CARTO világos csempék, **szőlőfürt
    jelölők darabszám-jelvénnyel**, **markercluster** (zoom-alapú összevonás/szétválás),
    popup részletlinkkel. SEO: szerveroldali lista + `Event`/`ItemList` JSON-LD.
  - `naptar.php` — **Eseménynaptár**: havi naptárrács (hét-első nézet), eseményekkel a
    napjukon, hónaplépegetéssel (`?ev=&ho=`). A Naptár menüpont ide mutat.
  - `esemeny.php` — esemény **részletoldal** (`esemeny.php?slug=…`): teljes `Event`
    JSON-LD, canonical, OG-kép; 404 a nem létezőre. A kártyák/sorok/térkép ide linkelnek.
    (Szép URL `/esemeny/<slug>` később, a végleges domainen, rewrite-tal.)
  - `assets/app.js` — progresszív fejlesztés (részleges szűrés, no-jump).
  - `lib/events.php` — esemény-lekérdezések + megjelenítési segédfüggvények
    (magyar dátumformázás, státusz-pirula, hónap-csoportosítás, `h()` escape).
  - **`index.php` = nyitóoldal (landing):** hero + kiemelt kártyák + „Böngéssz másképp"
    csempék (Összes esemény / Térkép / Naptár) + közelgő események előnézet.
  - **`esemenyek.php` = teljes lista:** tabok + multiselect szűrők (borvidék/kategória) +
    rendezés + hónapokra bontott sor-lista. Az „Események" menü ide mutat. Itt él az
    AJAX-os `#esemenyek-region` (részleges szűrés, `app.js`). `listUrl()` ide mutat.
  - Közös: kártya (`event-card`) / sor (`event-row`) naptár-dátumkockával, státusz-pirulákkal,
    `ItemList`+`Event` JSON-LD (SEO/AI). Cache-busting: `style.css?v=<filemtime>`.
  - `assets/style.css` — közös stíluslap (boros paletta CSS-változókban).
  - `partials/header.php`, `partials/footer.php` — közös layout váz (minden oldal ezt használja).
    - **TODO (elnapolva):** a logó még nyitott — jelenleg ideiglenes szőlőfürt-SVG van.
      Felmerült irány: „A" koncepció = térkép-tű + borospohár (a „hol borozzak?" játék).
- `db/` — adatbázis séma (`schema.sql`), `seed.sql` (minta események), migrációk. NEM kerül a webszerverre.
- `docs/` — tervdokumentumok (pl. `adatmodell.md`). NEM kerül a webszerverre.
- `.github/workflows/` — CI/CD (deploy).

## Adatmodell

Részletes terv: `docs/adatmodell.md`. Tényleges séma: `db/schema.sql`.

Táblák:
- **`events`** — fő tábla (cím, slug, rövid+hosszú leírás, kezdő/záró időpont,
  helyszín+koordináták, hivatalos kép, linkek, ingyenes/ár, kiemelés, állapot,
  időbélyegek).
- **`wine_regions`** — 22 magyar borvidék (segédtábla, FK az eventsből).
- **`categories`** + **`event_categories`** — címkék, több-a-többhöz kapcsolat.
- **`event_interactions`** — analitika: nyers kattintás/megtekintés napló (időbélyeggel,
  hashelt IP-vel). Kimenő kattintások (`click_website`/`click_ticket`) + `view`.
- **`event_impressions_daily`** — lista-megjelenések napi összesítésben (nagy volumen).

**Bevételi cél:** a kattintás-statisztikákból kimutatás a szervezőknek. Migráció:
`db/migrations/001_add_analytics.sql` (élő DB-n is futtatható). Kattintást átirányító
(`go.php`) naplóz majd. GDPR-t és bot-szűrést szem előtt tartani.

Karakterkészlet: `utf8mb4`. Ismétlődő (évente megrendezett) eseménynél évente
új sort veszünk fel (évszám a slugban).
