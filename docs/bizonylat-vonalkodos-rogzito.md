# Bizonylat vonalkódos (POS) rögzítő

Állapot: **lekódolva**, böngészős E2E még hátra. Branch: `bizonylat-pos-rogzito`.

Harmadik rögzítési mód a bizonylat karbon: a **fej a klasszikus rögzítőé**, a **tételfelvitel pedig
a főoldali bolti eladásé** (vonalkód / keresés → egysoros tételek). A bizonylatlista eszköztárában a
„+" és a gyorsrögzítő gomb mellől érhető el.

## Miért így

A karb már ismert egy második módot (`quick=1`), ahol a fej változatlan és csak a tételblokk más –
a POS ennek a mintának a harmadik esete (`pos=1`). Ezért:

- **A mentőág nem változott.** A `setFields()` gyorsrögzítő ága pontosan azt a mezőkészletet várja,
  amit a POS-sor ad (termék, változat, ÁFA, mennyiség, kedvezmény, e.nettó, nettó, bruttó), a többit
  – nettó/bruttó/ÁFA/HUF – a szerver számolja a `bizonylattetelController::calcAr()`-ral. Egyetlen
  sor kellett: `$quick = getBool('quick') || getBool('pos')`.
- **A POS-sor eleve a klasszikus mezőneveket viseli** (`tetelid[]`, `tetel*_<uid>`), tehát nincs
  szükség a gyorsrögzítőnél használt `q…` → `tetel…` fordításra a `beforeSerialize`-ben.
- A fej validációja (`checkBizonylatFej`) változatlanul fut. A tételösszeg-ellenőrzők
  (`checkTetelOsszegek`, egyedi azonosítós ellenőrzések) osztály szerint keresnek klasszikus
  sorokat, ezért POS módban üresen futnak – ahogy a gyorsrögzítőnél is.

## Ami nem vehető át a bolti eladásból

A `boltieladasController` végpontjai a **bolti vevőre** és a globális alapraktárra áraznak. Itt a
partner, a raktár és a valutanem a **fejből** jön, és menet közben változhat, ezért külön végpontok
kellettek: `Controllers/bizonylatposController.php` (`findtermek`, `kereses`, `gettermek`,
`gettetel`). Minden hívás magával viszi a fej aktuális állapotát, így az árazás és a készletjelzés
fejfüggő. A keresési logika (változat → termék, vonalkód → cikkszám sorrend) és a
`TermekRepository::getBoltieladasTermek*()` viszont közös.

## Élesítés

- **Kapcsoló:** `setup.ini` `vonalkod = 1`. E nélkül sem a gomb, sem a végpontok nincsenek
  bekötve (`adminroute.php`), és a `getkarb()` sem fogadja el a `pos=1`-et.
  Ma 1: `galad`, `darshan`, `b2bhungary`, `mugenrace`, `mugenrace2026`.
- **Hol látszik:** ugyanott, ahol a „+" (`csinalhatUjSzamlat`), tehát minden bizonylattípus
  listáján – a `bizonylatfejlista.tpl` közös.

## Érintett fájlok

| Fájl | Mi történt |
|---|---|
| `Controllers/bizonylatposController.php` | **új** – kereső/árazó végpontok a fej kontextusával |
| `Controllers/bizonylatfejController.php` | `pos` paraméter a `getkarb()`-ban; a `setFields()` tételága a `pos`-t is gyorsrögzítőként kezeli |
| `adminroute.php` | 4 új route `setup.vonalkod` mögött |
| `tpl/admin/default/bizonylattetelposkarb.tpl` | **új** – egy POS tételsor, klasszikus mezőnevekkel |
| `tpl/admin/default/bizonylattetelposvaltozat.tpl` | **új** – változatválasztó |
| `tpl/admin/default/bizonylatfejkarbform.tpl` | `{if ($pos)}` ág a tételblokkban + rejtett `pos` mező |
| `tpl/admin/default/bizonylatfejkarb.tpl` | `bizonylatpos.js` betöltése POS módban |
| `tpl/admin/default/bizonylatfejlista.tpl` | `data-vonalkod` attribútum |
| `js/admin/default/bizonylatpos.js` | **új** – a tételfelvitel |
| `js/admin/default/bizonylathelper.js` | POS bekötése, összesítő, „nincs tétel" ellenőrzés |
| `js/admin/default/jquery.mattable.js` | `posAddLink` / `posAddVisible` – a harmadik gomb |
| `themes/admin/default/style.css` | `bizonylatpos-*` szabályok |

## Ellenőrzés

Szerveroldalon megtörtént: a 4 route bekötése `vonalkod = 1` mellett; `findtermek` vonalkódra
tételsort, ismeretlen kódra `none`-t ad; változatos termékre változatválasztó jön; a `kereses` 4
karaktertől listáz; a kiadott sor mind a 11 mezőneve a klasszikus névtér szerinti; a karbform mind a
három módban (klasszikus / quick / POS) a helyes és kizárólagos tételblokkot rendereli, a fej és az
összesítő mindháromban ugyanaz.

**Böngészőben még ellenőrizendő:**

1. A bizonylatlistán megjelenik-e a harmadik gomb, és a `viewkarb?id=0&oper=add&pos=1`-re visz-e.
2. Vonalkód beolvasása / Enter → tételsor; az Enter nem küldi be a bizonylatot.
3. Névre keresés 4 karaktertől, változatos terméknél változatválasztó.
4. Mennyiség / kedvezmény / bruttó egységár átírása → sor és fejösszesítő frissül.
5. Mentés: a fej minden mezője a klasszikus szerint mentődik, a tételek a bevitt mennyiséggel és
   árral, a nettó/ÁFA/HUF a szerver számítása szerint.
6. Üres kosárral a mentés hibaüzenetet ad, nem hoz létre tétel nélküli bizonylatot.
7. Partner- vagy raktárváltás a fejben → az **ezután** felvett sorok árazása/készletjelzése az újat
   követi (a már felvett sorokat szándékosan nem árazzuk át).
