# Leltár felvételi lista vonalkódos rögzítése

Állapot: **lekódolva**, böngészős E2E még hátra. Branch: `leltar-felvetel`.

A leltár tény adatait eddig csak Excelen át lehetett rögzíteni: „Felvételi ív" export → papíron
számolás → „Tény adat betöltés" import. Ez a képernyő ugyanazt a felvételi listát tölti, de
vonalkódos beolvasással, a bolti eladás kezelésével.

**Nem bizonylat készül.** A rögzítés a `leltartetel` táblába megy (`tenymennyiseg`), pontosan oda,
ahová az Excel-import is ír. A felvételi lista **üresen indul** és beolvasásról beolvasásra épül:
leltáranként, termék+változat páronként egy sor. Bizonylat a leltár **zárásakor** keletkezik
(leltárhiány / leltártöbblet), változatlanul a `leltarfejController::zar()`-ban.

## Kezelés

Elérés: a leltárlistán a sor „Felvétel" linkje, **csak nyitott leltárnál**. Új lapon nyílik, mint a
felvételi ív és a tény adat betöltés.

- **A vonalkód mező a bolti eladásé**: a táblázat alatt ül, 4 karaktertől név/cikkszám
  autocomplete, Enterre vonalkódos keresés. Előbb változatra keresünk (vonalkód, majd cikkszám),
  aztán termékre; változatos terméknél változatválasztó jön.
- **Egy beolvasás = +1.** Külön mennyiség mező nincs, ahogy a bolti eladáson sincs.
- **Ugyanaz a termék többször beolvasva összeadódik** – az első beolvasás létrehozza a sort, a
  továbbiak növelik. Aki kétszer szkennel, kettőt számolt.
- A sor tény mennyisége **utólag átírható**, a sor **törölhető**.
- A táblázat a leltár eddig felvett sorait mutatja, a legutóbbi elöl, rövid kiemeléssel; oldal-
  újratöltés után is megmarad. A gépi készlet és az eltérés is látszik (piros: hiány, zöld: többlet).

## Döntések

- **Nincs mentés gomb: minden beolvasás azonnal mentődik.** A felvételi lista nem atomi dokumentum,
  és egy félbeszakadt számolás nem veszhet el. Ezért nincs kliensoldali kosár sem, mint a POS-on.
- **A `gepimennyiseg` új sor létrehozásakor a leltár raktárának mai készlete** – ugyanaz, amit a
  felvételi ív export ad. A zárás amúgy is újraszámolja a készletet, tehát ez tájékoztató adat.
- **A nyitottság minden módosító végponton külön ellenőrződik**, nem csak az oldal megnyitásakor:
  a zárás közben nyitva felejtett fül se írhasson zárt leltárba.
- **A tétel csak a saját leltárán át módosítható** – a `settetel`/`deltetel` ellenőrzi, hogy a
  megadott tétel tényleg ahhoz a leltárhoz tartozik-e.

## Egy javítás, ami kellett hozzá

A `leltarfejController::zar()` feltétel nélkül a **változatot** kérdezte a készletről:

```php
$valtozat = $tetel->getTermekvaltozat();
$keszlet = $valtozat->getKeszlet($zarasstr, $raktarid);
```

Változat nélküli terméknél ez fatal. Ilyen sort az import eddig is létre tudott hozni (a `B` oszlop
üresen hagyásával). A zárás most a termék készletére esik vissza, ha nincs változat.

## Érintett fájlok

| Fájl | Mi történt |
|---|---|
| `Controllers/leltarfelvetelController.php` | **új** – oldal + kereső/könyvelő végpontok |
| `tpl/admin/default/leltarfelvetel.tpl` | **új** – a képernyő |
| `tpl/admin/default/leltarfelveteltetel.tpl` | **új** – egy sor |
| `tpl/admin/default/leltarfelvetelvaltozat.tpl` | **új** – változatválasztó |
| `js/admin/default/leltarfelvetel.js` | **új** – a kezelés |
| `adminroute.php` | 7 új route az `isClosed()` ágban |
| `tpl/admin/default/leltarfejlista_tbody_tr.tpl` | „Felvétel" link nyitott leltárnál |
| `js/admin/default/leltarfej.js` | a link gombosítása |
| `Controllers/leltarfejController.php` | `zar()` változat nélküli tételre |
| `themes/admin/default/style.css` | `leltarfelvetel-*` szabályok |

## Ellenőrzés

Szerveroldalon, eldobható teszt-leltáron, üres felvételi listáról indulva, minden esetben
takarítással: első beolvasás → a sor létrejön, tény 1, gépi mennyiség = raktárkészlet; háromszor még
→ 4 és **továbbra is egy sor**; másik termék → új sor; tény felülírás; törlés → a sor eltűnik, a
másik megmarad; zárt leltáron mindhárom módosító végpont elutasít és az adat érintetlen marad. Az
oldal újratöltve visszahozza a felvett sorokat a tény mennyiségekkel, a vonalkód mező a táblázat
alatt ül, és nincs külön mennyiség mező. Zárt és nem létező leltárra hibaüzenet.

**Böngészőben még ellenőrizendő:**

1. A „Felvétel" link csak nyitott leltárnál látszik, és új lapon nyílik.
2. Kézi vonalkódolvasó: a beolvasás záró Enterje könyveli a sort, nem küld be semmit.
3. A frissült sor a lista tetejére kerül és rövid kiemelést kap.
4. Változatválasztó → a kiválasztott változat sora bekerül.
5. Tény mennyiség átírása és a sor törlése mentődik (oldalfrissítés után is).
6. A felvett tény adat a leltár zárásakor a hiány/többlet bizonylatokba a várt módon kerül be.
