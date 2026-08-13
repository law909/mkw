# `minboltikeszlet` → `minkeszlet` átnevezés

Állapot: **kész**, a fejlesztői DB-n (galad) végigfuttatva és böngészőben ellenőrizve.

## Mi változott

Egyetlen mechanikus átnevezés, három írásmóddal:

| régi | új |
|------|-----|
| `minboltikeszlet` | `minkeszlet` |
| `Minboltikeszlet` | `Minkeszlet` |
| `MinBoltiKeszlet` | `MinKeszlet` |

Érintett rétegek:

- **Entitás**: `Entities/TermekMinkeszlet.php`, `Entities/TermekValtozatMinkeszlet.php` + a két repository
  (fájlnév is átnevezve). Tábla `termekminkeszlet` / `termekvaltozatminkeszlet`, unique index `…_egyedi`.
- **Oszlop**: `termek.minkeszlet`, `termekvaltozat.minkeszlet`, és a két raktáras tábla `minkeszlet` oszlopa.
- **Metódus**: `get/setMinkeszlet()`, `calcMinkeszlet()`, `termekController::get/saveMinKeszletMatrix()`.
- **Kérésparaméter / Smarty kulcs**: `minkeszlet`, `minkeszletraktarid[]`, `valtozatminkeszletid[]`,
  `termekraktariminkeszlet_<raktarid>`, `valtozatraktariminkeszlet_<valtozatid>_<raktarid>`,
  `valtozatminkeszlet_<valtozatid>`, `$egyed.minkeszletraktarak`, `$egyed.minkeszletsorok`.
- **DOM id**: `#MinKeszletTab`, `#MinKeszletMatrix`, `#MinkeszletEdit`.

A megjelenő feliratok (`Min. készlet`) nem változtak.

## DB migráció

A `runonce.php` **legelején**, a `DBVersion`-lánc **előtt** fut, saját jelölővel
(`\mkw\consts::MinkeszletRename`) — nem DBVersion-blokként. Ez szándékos: a lánc alsóbb blokkjai
(0113, 0117, 0118) már az új néven mozgatják a min. készlet adatot, tehát mire azok sorra kerülnek,
az átnevezésnek meg kell történnie. Ha DBVersion-blokk lenne a lánc végén, egy régi (0113 alatti)
telepítésen a 0113 üres oszlopból töltene.

A blokk `information_schema`-őrökkel dolgozik, ezért tetszőlegesen sokszor lefuthat, és mind a négy
állapotot kezeli:

| állapot | mit csinál |
|---------|------------|
| csak a régi tábla/oszlop van | `RENAME TABLE` / `CHANGE COLUMN` — adat helyben marad |
| a régi és az új is van (az `./updateschema.sh` már lefutott) | `INSERT … ON DUPLICATE KEY UPDATE` a régiből az újba, majd `DROP TABLE` a régit; oszlopnál `UPDATE … SET minkeszlet = minboltikeszlet` a nulla/NULL cellákra, majd `DROP COLUMN` |
| csak az új van | nem csinál semmit |
| a `…_egyedi` index régi nevű | `RENAME INDEX` |

## Deploy sorrend — fontos

**Előbb egy admin kérés (runonce), utána `./updateschema.sh`.**

A `orm:schema-tool:update --force` a kezelt táblákból eldobja azokat az oszlopokat, amiket az entitás
nem ismer (ellenőrizve). Ha az `updateschema.sh` fut előbb, a `termek.minboltikeszlet` és a
`termekvaltozat.minboltikeszlet` **tartalma elvész**, mielőtt a runonce átmenthetné — a két raktáras
tábla viszont túléli (a schema-tool az ismeretlen táblákat nem dobja el), és azokat a runonce
utólag is átmenti.

Az `updateschema.sh` a runonce után már csak négy kozmetikus `RENAME INDEX`-et generál
(a Doctrine `IDX_…` hash a táblanévből jön), adatot nem érint.
