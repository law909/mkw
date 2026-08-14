# GLS utánvét import

Állapot: **lekódolva és böngészőben végigpróbálva** a galad fejlesztői adatbázison
(2026-08-14); a párosított tételekből azóta **bankbizonylat is képezhető** – lásd a
*Bankbizonylat a párosított tételekből* fejezetet.

A GLS „Actual pcl statuses" kimutatásából a **beszedett** utánvétek átemelése egy átmeneti
táblába, a bank tranzakció import mintájára, és a befizetéshez tartozó bizonylat megkeresése.

---

## A bemenet

A GLS xlsx első két sora fejléc-szöveg, a 3. sor az oszlopnevek, az adat a 4. sortól jön.
Az oszlopkiosztás fix (a GLS mindig ugyanezt a lapot adja), ezért – a banki importtal
ellentétben – **nincs formátumválasztó**.

| oszlop | tartalom | hova |
|--------|----------|------|
| A | Csomagszám | `csomagszam` (= fuvarlevélszám) |
| B | Regisztrált utánvét összege | `regisztraltosszeg` |
| C | Státusz | `statusz` |
| E / F | Felvétel / státusz dátuma (Excel-sorszám) | `felvetel`, `statuszdatum` |
| H / I | Ügyfél / utánvét hivatkozás | `ugyfelhivatkozas`, `utanvethivatkozas` |
| K / L | Címzett / átvevő neve | `nev`, `atvevo` |
| M / N / O / P | Irányítószám, város, utca, ország | `irszam`, `varos`, `utca`, `orszag` |
| **Q** | **Beszedett utánvét összege** | `osszeg` |

**Csak a nem nulla Q oszlopú sorok kerülnek be** – a regisztrált, de be nem szedett utánvét
még nem pénz. A két mintafájlon ez 2/6 és 4/16 sor.

Az újraimportálás duplikátumszűrője a **csomagszám**: a már beimportált csomag kimarad, tehát
ugyanazt a kimutatást kétszer feltöltve nem keletkezik duplikátum.

## A párosítás

Három lépcső, a biztosabbtól a bizonytalanabb felé. Az első, ami talál, nyer.

1. **Fuvarlevélszám.** A csomagszámot a `bizonylatfej.fuvarlevelszam` mezőben keressük
   (`LOCATE`, mert a mezőben több csomagszám is állhat). Ez a legerősebb kötés: a
   `Services\GLSService` a címke kérésekor pontosan ezt a `ParcelNumber`-t írja oda.
2. **Hivatkozás.** A kimutatás hivatkozás-mezőiben gyakran maga a bizonylatszám áll (a 2025-ös
   mintafájlban `MR2025/002079`). Csak **pontos** bizonylatszám-egyezést fogadunk el; ha a mező
   webshopos rendelésszám (a 2026-os fájlban `5705`), ez az ág egyszerűen nem talál semmit.
3. **Név + összeg + cím.** Címzett neve előtag-egyezéssel (a GLS levághatja) a `szallnev`-re
   vagy a `partnernev`-re, a beszedett összeg a bruttóval pontosan, és ha van irányítószám,
   annak is egyeznie kell (`szallirszam` vagy `partnerirszam`).

### A pénzt mozgató bizonylat

A megtalált bizonylat gyakran **nem mozgat pénzt** (megrendelés, szállítólevél) – befizetést
nem lehet rá könyvelni. Ezért a jelöltek leszármazottait is végigjárjuk (szélességi bejárás,
mert megrendelés → szállítólevél → számla lánc is előfordul), és azt a bizonylatot adjuk
vissza, amelyiknek a **típusa és a saját jelölője is** pénzt mozgat.

Ez egyben a többes találat feloldása is: a képzett bizonylat **átveszi az előd
fuvarlevélszámát**, tehát egy megrendelés és a belőle készült számla egyaránt illik a
csomagszámra. A bejárás után a kettőből egy pénzt mozgató bizonylat marad.

| eset | eredmény |
|------|----------|
| pontosan egy pénzt mozgató bizonylat jött ki | az lesz a találat |
| egyik jelöltnek sincs pénzt mozgató ága, de egyetlen jelölt van | a jelölt (jobb egy pontos, de még nem számlázott hivatkozás, mint a semmi) |
| több pénzt mozgató bizonylat, vagy több pénzt nem mozgató jelölt | **üresen hagyjuk** – egy téves találat rossz bizonylatra könyvelné a pénzt |

## A felület

- **Kereskedelem → Bank, pénztár → GLS utánvétek** – lista, szűrő csomagszámra, névre és a
  párosítatlanokra, „Import", „Csoportos művelet + Futtat" és „Párosít" gombbal.
- **GLS utánvét import** – a fájlfeltöltő oldal. Az import a végén megmondja, hány sorból hány
  új tétel lett és ebből hány kapott bizonylatszámot.
- A karbantartón minden mező **csak olvasható**, kivéve a **Bizonylatszámok**-at és az
  **Inaktív** jelölőt – ugyanaz a szerep, mint a bank tranzakciónál: a program tippel, az ember
  javít.
- A **Párosít** gomb a bizonylatszám nélküli tételeken újra lefuttatja a keresést – pl. ha az
  importálás óta elkészült a számla.

## Bankbizonylat a párosított tételekből

A lista fejlécében a **Csoportos művelet → „Bank bizonylatok létrehozása" + Futtat** a párosított
tételekből bankbizonylatot képez – a `banktranzakcioController::generateBankbizonylat()`
mintájára, ezekkel a különbségekkel:

- **Mindig bevétel** (`irany = 1`): az utánvét a futárszolgálattól érkező pénz.
- A bizonylat **saját bankszámlája** a Beállítások → Alapértelmezések fül **„Utánvét bankszámla"**
  értéke (`\mkw\consts::UtanvetBankszamla`). Ha nincs beállítva, a hivatkozott számla
  bankszámlája marad – az viszont számlától függően üres is lehet, ezért érdemes beállítani.
- A tétel **jogcíme** az „Automatikus bankbizonylat jogcíme" beállításból jön (ugyanaz, mint a
  banki importnál), a dátuma a GLS státusz dátuma, az `erbizonylatszam` a csomagszám.
- Csak az kerül sorra, ami **párosított, nem inaktív és még nincs bankbizonylata**. Kipipált
  sorokkal csak azok, pipa nélkül minden ilyen tétel.
- Ha a tétel bizonylatszáma nem létező bizonylatra mutat, a tétel **kimarad** (a válaszüzenet
  megmondja, hányról van szó) – így egy elgépelt bizonylatszám nem könyvel rossz helyre.
- A kész tételen a `bankbizonylatkesz` jelző áll be: a lista sora ettől már nem szerkeszthető, és
  a művelet újrafuttatása nem csinál duplikátumot.

## Érintett fájlok

| fájl | mi |
|------|-----|
| `Entities/GLSUtanvet.php`, `Entities/GLSUtanvetRepository.php` | az átmeneti tábla (`glsutanvet`), csoportos művelet |
| `Controllers/glsutanvetController.php` | lista, karb, import, párosítás, bankbizonylat képzés |
| `Controllers/setupController.php`, `tpl/admin/default/setup.tpl`, `mkw/consts.php` | az „Utánvét bankszámla" beállítás |
| `tpl/admin/default/glsutanvet*.tpl` | lista, karb, feltöltő |
| `js/admin/default/glsutanvet.js`, `glsutanvetupload.js` | |
| `adminroute.php` | 9 route |
| `runonce.php` 0122 | a két menüpont |

## Hogyan lett ellenőrizve

Böngészőben, a galad fejlesztői adatbázison:

| eset | eredmény |
|------|----------|
| a 2026-os fájl importja | 6 sorból 2 tétel (csak a beszedett utánvétesek) |
| a 2025-ös fájl importja | 16 sorból 4 tétel |
| ugyanaz a fájl újra | 0 új tétel (csomagszám szerinti duplikátumszűrés) |
| fuvarlevélszám párosítás, pénzt nem mozgató bizonylatra | `MR2026/000001` (megrendelés) – nincs pénzt mozgató ága, ezért ő a találat |
| ugyanaz, miután a megrendelésből számla készült | **`SZ2026/000003`** – a lánc feloldva, a megrendelés helyett a számla |
| hivatkozás párosítás (`ugyfelhivatkozas = MR2026/000001`) | `SZ2026/000003` – a lánc itt is feloldódik |
| név + összeg + cím párosítás | `MR2026/000002` |
| a 2025-ös sorok (`MR2025/…` nem létezik ezen a DB-n) | nincs találat – a hivatkozás-ág nem tippel |
| karbantartó: kézzel átírt bizonylatszám | elmentődött |

A bankbizonylat képzés böngészőben, a **mugenrace fejlesztői adatbázison** (2026-08-14), egy ideiglenes
tétellel egy valódi számlára (`SZ2026/001190`, 159 240 Ft) – utána minden visszaállítva:

| eset | eredmény |
|------|----------|
| „Utánvét bankszámla" beállítás | a Beállítások → Alapértelmezések fülön megjelenik, elmentődik |
| a csoportos művelet a kipipált soron | 1 bankbizonylat (`BANK2026/000391`), a beállított saját bankszámlával, a számla partnerével |
| a bankbizonylat tétele | `irany = 1`, bruttó 159 240, jogcím „Számla kiegyenlítés", dátum a GLS státusz dátuma, `erbizonylatszam` = csomagszám |
| folyószámla | egy sor keletkezett, `irany = -1` (a listener fordított előjellel könyvel) |
| újrafuttatás | „0 bankbizonylat készült." – a `bankbizonylatkesz` jelző miatt nincs duplikátum |
| a kész sor a listán | nem szerkeszthető, „Bankbizonylat kész" felirattal |
| nem létező bizonylatszámmal | „0 bankbizonylat készült, 1 tétel kimaradt (nincs meg a hivatkozott bizonylat)." |
