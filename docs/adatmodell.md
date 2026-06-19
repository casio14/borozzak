# holborozzak.hu — Adatmodell (tervezet)

> Állapot: **tervezet, egyeztetés alatt**. A ✅ mezők lefedik a megbeszélt
> funkciókat; a 💡 jelölésűek az én javaslataim, ezekről te döntesz.

## Funkció → adat leképezés

Mire van szükség a kért funkciókhoz:

| Funkció | Milyen adat kell hozzá |
|---|---|
| Letisztult lista (Eventbrite-szerű kártyák) | cím, rövid leírás, kép, dátum, helyszín |
| Kiemelt események | `is_featured` jelölő |
| Tab: Közelgő események | `start_datetime` (jövőbeli) |
| Tab: E hétvégén | `start_datetime` / `end_datetime` (átfedés a hétvégével) |
| Tab: E hónapban | `start_datetime` / `end_datetime` (átfedés a hónappal) |
| Tab: Ingyenes események | `is_free` jelölő |
| Térképes megjelenítés | `latitude`, `longitude` (+ cím, helyszín név) |
| Hivatalos kép | `image_url` |
| Rövid leírás | `short_description` |
| Hivatalos honlap | `website_url` |

> **Fontos:** a tabok (Közelgő / E hétvégén / E hónapban / Ingyenes) nem igényelnek
> külön mezőt — ezek **lekérdezések** a dátum- és jelölő-mezőkre. Lásd lentebb.

---

## 1. Fő tábla: `events`

### Azonosítás
| Mező | Típus | Megjegyzés |
|---|---|---|
| `id` | INT UNSIGNED, PK, AUTO_INCREMENT | ✅ |
| `slug` | VARCHAR(255), UNIQUE | ✅ SEO-barát URL, pl. `budapesti-bor-napok-2026`. Kell a részletező oldalhoz. |
| `title` | VARCHAR(255), NOT NULL | ✅ Esemény neve |

### Leírás
| Mező | Típus | Megjegyzés |
|---|---|---|
| `short_description` | VARCHAR(500) | ✅ Rövid leírás a lista-kártyákhoz |
| `description` | TEXT | 💡 Hosszú leírás a részletező oldalhoz (érdemes különválasztani) |

### Időpont
| Mező | Típus | Megjegyzés |
|---|---|---|
| `start_datetime` | DATETIME, NOT NULL | ✅ Kezdés |
| `end_datetime` | DATETIME, NULL | ✅ Befejezés (több napos rendezvényekhez) |
| `is_all_day` | TINYINT(1) DEFAULT 0 | 💡 Egész napos esemény (ne jelenjen meg konkrét óra) |

### Helyszín (térképhez)
| Mező | Típus | Megjegyzés |
|---|---|---|
| `venue_name` | VARCHAR(255) | ✅ Helyszín neve, pl. „Budai Vár" |
| `address` | VARCHAR(255) | ✅ Utca, házszám |
| `city` | VARCHAR(120) | ✅ Település |
| `postal_code` | VARCHAR(20) | 💡 Irányítószám |
| `region_id` | INT UNSIGNED, FK → `wine_regions.id` | 💡 Borvidék (szűréshez, lásd 2. tábla) |
| `latitude` | DECIMAL(10,7) | ✅ Térkép koordináta |
| `longitude` | DECIMAL(10,7) | ✅ Térkép koordináta |

### Média
| Mező | Típus | Megjegyzés |
|---|---|---|
| `image_url` | VARCHAR(500) | ✅ Hivatalos kép URL-je |
| `image_alt` | VARCHAR(255) | 💡 Kép alt-szövege (akadálymentesség, SEO) |
| `image_credit` | VARCHAR(255) | 💡 Kép forrása/jogtulajdonosa (hivatalos képnél fontos lehet) |

### Linkek
| Mező | Típus | Megjegyzés |
|---|---|---|
| `website_url` | VARCHAR(500) | ✅ Hivatalos honlap |
| `ticket_url` | VARCHAR(500) | 💡 Jegyvásárlás linkje (gyakran külön a honlaptól) |
| `facebook_url` | VARCHAR(500) | 💡 Esemény Facebook-oldala |

### Ár / ingyenesség
| Mező | Típus | Megjegyzés |
|---|---|---|
| `is_free` | TINYINT(1) DEFAULT 0 | ✅ Ingyenes-e (az „Ingyenes" tabhoz) |
| `price_info` | VARCHAR(255) | 💡 Szabad szöveges ár, pl. „Belépő 3 000 Ft-tól" |
| `price_min` | DECIMAL(10,2), NULL | 💡 Min. ár (ha „ár szerint" szűrnél/rendeznél) |
| `currency` | CHAR(3) DEFAULT 'HUF' | 💡 Pénznem |

### Kiemelés / állapot
| Mező | Típus | Megjegyzés |
|---|---|---|
| `is_featured` | TINYINT(1) DEFAULT 0 | ✅ Kiemelt esemény (a „Kiemelt" tabhoz + nagyobb kártya) |
| `featured_until` | DATE, NULL | 💡 Eddig kiemelt (utána automatikusan lekerül) |
| `status` | ENUM('draft','published','cancelled') DEFAULT 'draft' | 💡 Vázlat / közzétett / lemondva — hogy ne látsszon félkész esemény |

### Szervező
| Mező | Típus | Megjegyzés |
|---|---|---|
| `organizer_name` | VARCHAR(255) | 💡 Szervező neve |

### Időbélyegek
| Mező | Típus | Megjegyzés |
|---|---|---|
| `created_at` | TIMESTAMP DEFAULT CURRENT_TIMESTAMP | 💡 Létrehozás |
| `updated_at` | TIMESTAMP ON UPDATE CURRENT_TIMESTAMP | 💡 Módosítás |

### Indexek
- `UNIQUE(slug)`
- `INDEX(start_datetime)` — rendezés és „közelgő" szűrés
- `INDEX(is_featured)`, `INDEX(is_free)`, `INDEX(status)`
- `INDEX(region_id)`

---

## 2. 💡 `wine_regions` — borvidékek (segédtábla)

Magyarországon **22 hivatalos borvidék** van. Külön táblában tárolva tudsz
borvidék szerint szűrni és térképen csoportosítani.

| Mező | Típus |
|---|---|
| `id` | INT UNSIGNED, PK |
| `name` | VARCHAR(120) — pl. „Tokaji", „Villányi", „Badacsonyi" |
| `slug` | VARCHAR(120), UNIQUE |

---

## 3. 💡 `categories` + `event_categories` — címkék (több-a-többhöz)

A fix tabokon túl rugalmas szűrést ad (egy esemény több címkét is kaphat).
Példa címkék: *borfesztivál, szüreti rendezvény, kóstoló, gasztronómia,
koncert, családi program*.

`categories`: `id`, `name`, `slug`
`event_categories`: `event_id` (FK), `category_id` (FK) — összetett PK

---

## A tabok mint lekérdezések (nem külön mező!)

```text
Közelgő    : status='published' AND start_datetime >= NOW()
             ORDER BY start_datetime ASC
Kiemelt    : status='published' AND is_featured=1
             AND (featured_until IS NULL OR featured_until >= CURDATE())
E hétvégén : status='published' AND átfedés(hét szombat 00:00 .. vasárnap 23:59)
E hónapban : status='published' AND átfedés(hónap első napja .. utolsó napja)
Ingyenes   : status='published' AND is_free=1
```

> **Átfedés-logika** (több napos eseményekhez): egy esemény „beleér" egy [R_kezd, R_vég]
> időszakba, ha `start_datetime <= R_vég AND COALESCE(end_datetime, start_datetime) >= R_kezd`.

---

## További megfontolásra érdemes dolgok (💡 opcionális)

1. **Évente ismétlődő rendezvények** (pl. a Bor Napok minden évben): a legegyszerűbb,
   ha **évente új sort** veszünk fel (a `slug`-ban benne az évszám). Külön ismétlődés-
   logika túlbonyolítaná. Erről most döntsünk.
2. **Több kép / galéria**: ha eseményenként több képet akarsz, az külön `event_images`
   tábla lenne. Most elég lehet az egy hivatalos kép.
3. **„Megtelt / elfogyott" jelző** (`sold_out`).
4. **Akadálymentesség** jelölő.
5. **Népszerűség / megtekintésszám** rendezéshez.
6. **Forrás / import-időbélyeg**, ha később automatikusan gyűjtenénk be adatokat.

---

## Analitika (kattintás-statisztika, bevételhez)

Cél: kimutatni, **melyik eseményre hányan kattintottak**, és időbeli statisztikákat
készíteni (a szervezőknek bizonyítható érték → bevételi alap).

- **`event_interactions`** — nyers napló, soronként egy interakció, időbélyeggel.
  Típusok: `view` (részletoldal-megtekintés), `click_website`, `click_ticket`
  (kimenő kattintások — ezek a legértékesebbek). Ebből bármilyen trend/konverzió
  számolható. GDPR: csak **hashelt IP** (`ip_hash`), nyers PII nélkül.
- **`event_impressions_daily`** — a lista-megjelenések (impressziók) **napi
  összesítésben** (nagy volumen, ezért nem soronként). UPSERT-tel növelve.

**Kattintás-számlálás technikája:** a kimenő linkek egy átirányítón mennek át
(`go.php?e=ID&t=ticket|website`), ami naplóz, majd 302-vel továbbküld → szerveroldali,
megbízható számolás JS nélkül.

**Megfontolandó (később):** süti-tájékoztató/consent a `session_id`-hoz, bot-szűrés
user-agent alapján, és opcionális denormalizált `view_count`/`click_count` cache az
`events` táblán a gyors megjelenítéshez.

## Eldöntött kérdések ✅

1. Beépített extra mezők: `description`, `status`, `ticket_url`, `featured_until`,
   `image_alt`, `image_credit`, `created_at`/`updated_at`, `price_info`, `region_id`.
2. Borvidék (`wine_regions`) **és** címkék (`categories`) — mindkettő bekerült.
3. Ismétlődő rendezvények: **évente új sor** (évszám a slugban).

A megvalósított séma: `db/schema.sql`.
