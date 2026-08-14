# GLS utánvét import

Állapot: **lekódolva és böngészőben végigpróbálva** a galad fejlesztői adatbázison
(2026-08-14). A pénzt mozgató bizonylat képzése (bank-/pénztárbizonylat) **nem** része –
lásd a *Ami szándékosan kimaradt* fejezetet.

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
  párosítatlanokra, „Import" és „Párosít" gombbal.
- **GLS utánvét import** – a fájlfeltöltő oldal. Az import a végén megmondja, hány sorból hány
  új tétel lett és ebből hány kapott bizonylatszámot.
- A karbantartón minden mező **csak olvasható**, kivéve a **Bizonylatszámok**-at és az
  **Inaktív** jelölőt – ugyanaz a szerep, mint a bank tranzakciónál: a program tippel, az ember
  javít.
- A **Párosít** gomb a bizonylatszám nélküli tételeken újra lefuttatja a keresést – pl. ha az
  importálás óta elkészült a számla.

## Ami szándékosan kimaradt

A bank tranzakciónál van egy „Bank bizonylatok létrehozása" csoportos művelet, ami a párosított
tételekből tényleg megcsinálja a bankbizonylatot. **Ez itt nincs**: a feladat a táblát, az
importot, a párosítást és a karbantartót kérte. Ha kell, a
`banktranzakcioController::generateBankbizonylat()` mintájára pénztár- vagy bankbizonylat
képezhető belőle – az utánvét jellemzően a futárszolgálat átutalásaként érkezik, tehát
bankbizonylat való hozzá, egy „GLS utánvét" jogcímmel.

## Érintett fájlok

| fájl | mi |
|------|-----|
| `Entities/GLSUtanvet.php`, `Entities/GLSUtanvetRepository.php` | az átmeneti tábla (`glsutanvet`) |
| `Controllers/glsutanvetController.php` | lista, karb, import, párosítás |
| `tpl/admin/default/glsutanvet*.tpl` | lista, karb, feltöltő |
| `js/admin/default/glsutanvet.js`, `glsutanvetupload.js` | |
| `adminroute.php` | 8 route |
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
