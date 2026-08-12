# Fizetési mód utólagos módosítása pénzt mozgató bizonylaton

*Készült: 2026-08-11. Kódolvasás + a galad fejlesztői adatbázison végzett, csak olvasó ellenőrzés alapján.*

> **2026-08-12: a 4. pont javításai elkészültek** (az adattisztítás kivételével, az továbbra is nyitott).
> A 3.1–3.6 alatti leírások az **eredeti, hibás** viselkedést dokumentálják; hogy mi lett belőlük,
> azt az 5. pont foglalja össze. A meglévő pénzmozgásokról **a felhasználó dönt** a mentéskor
> feltett kérdésre adott válasszal – lásd a 6. pontot.

A kérdés: mi történik az egyenleggel és a már létrejött pénztár-/bankbizonylatokkal, ha egy pénzt mozgató
bizonylaton **utólag** átállítják a fizetési módot (készpénzről utalásra vagy vissza).

Rövid válasz: **a készpénzről utalásra váltás könyvelésileg hibás állapotot hagy maga után** – az addig
képzett automatikus pénztárbizonylat érintetlenül életben marad, a számla kiegyenlítettnek látszik, a
pénztárban pedig ott van egy soha be nem folyt készpénz. A fordított irány (utalásról készpénzre) egy már
kézzel kiegyenlített számlán túlfizetést csinál. A váltásnak ráadásul **semmilyen nyoma nincs**.

---

## 1. Ami a váltáskor lefut

A fizetési mód a bizonylat karbon szabadon átírható (`bizonylatfejkarbform.tpl:207`), mentéskor a
`bizonylatfejController::setFields()` egyszerűen ráteszi a bizonylatra (`bizonylatfejController.php:800`).
Az érdemi munkát a mentés `onFlush`-ában a `Listeners\BizonylatfejListener` végzi
(`BizonylatfejListener.php:894` és `:907`), két, egymástól független lépésben:

### a) A folyószámla sorok mindig újraképződnek

`createFolyoszamla()` (`BizonylatfejListener.php:89`) a bizonylat **összes** saját folyószámla sorát
eldobja, és a mentés utáni állapot szerint írja újra. Kihagyja a sorképzést, ha

- a bizonylat nem mozgat pénzt (`penztmozgat = 0`), vagy
- a fizetési mód készpénzes (`fizmod.tipus = 'P'`) **és** a `kpfolyoszamla` beállítás ki van kapcsolva
  **és** a bizonylattípus nem képez automatikus pénztárbizonylatot.

Ez a rész a fizmód váltását helyesen követi: a sor a mentés utáni fizetési móddal, összeggel és
esedékességgel születik újra.

### b) Az automatikus pénztárbizonylat viszont NEM mindig

`createPenztarBizonylat()` (`BizonylatfejListener.php:458`) csak akkor jut el odáig, hogy a **korábban
képzett** pénztárbizonylatot megkeresse és rontsa, ha a bizonylat *most is* készpénzes. A metódus eleje
sorban ezeken a feltételeken lép ki – mindegyik **a régi pénztárbizonylat megkeresése előtt**:

| sor | kilépési feltétel |
|-----|-------------------|
| 460 | a bizonylattípusnak nincs automatikus pénztárbizonylata |
| 464 | a hívó maga rögzíti a pénzmozgást (`nincsautopenztarbizonylat`) |
| 471 | a bizonylat stornózott vagy rontott |
| 474 | **`penztmozgat = 0`**, vagy nincs partner |
| 478 | **a fizetési mód nem készpénzes (`tipus <> 'P'`)** |
| 482 | osztott fizmódnál részletfizetés van ütemezve |

A régi pénztárbizonylat lekérdezése (`getAutoPenztarBizonylat()`) csak az 503. sorban következik. Ami tehát
készpénzes állapotban létrejött, azt egy nem készpénzes állapotba váltás **nem takarítja el**.

---

## 2. Esetek

Az alábbi táblázat egy automatikus pénztárbizonylatot képző típusra vonatkozik (ezen a telepítésen:
`bevet`, `boltieladas`, `esetiszamla`, `keziszamla`, `szamla`).

| Váltás | Folyószámla | Pénztár-/bankbizonylat | Eredmény |
|--------|-------------|------------------------|----------|
| készpénz → készpénz (másik kp. fizmód) | újraképződik | változatlan (az összevetés a fizmódot nem nézi) | rendben |
| készpénz → **utalás/kártya** | újraképződik (tartozás) | **a kp. pénztárbizonylat életben marad** | **HIBÁS** – lásd 3.1 |
| készpénz → készpénz, **másik pénztár** | újraképződik | a régit rontja, újat képez a másik pénztárban | rendben |
| **utalás → készpénz** (még kiegyenlítetlen) | újraképződik | új pénztárbizonylat keletkezik | rendben |
| **utalás → készpénz** (már kézzel kiegyenlítve bankbizonylattal) | újraképződik | a bankbizonylat marad + **új** pénztárbizonylat | **HIBÁS** – lásd 3.2 |
| bármi → „nincs pénzmozgás" fizmód | a bizonylat sorai eltűnnek | **a pénztárbizonylat életben marad** | **HIBÁS** – lásd 3.3 |
| „nincs pénzmozgás" → utalás | – | – | **HIBÁS** – lásd 3.4 |
| utalás → utalás (másik banki fizmód) | újraképződik | nincs mit tenni (nincs automatikus bankbizonylat) | rendben |

Olyan telepítésen, ahol `kpfolyoszamla = 0` **és** a bizonylattípus nem képez automatikus
pénztárbizonylatot, a fizmód váltása tiszta: készpénzesen egyáltalán nincs folyószámla sor, utalásra váltva
megjelenik a tartozás, visszaváltva eltűnik. A galad telepítésen viszont `kpfolyoszamla = 1`, és mind az öt
fenti típus képez automatikus pénztárbizonylatot.

---

## 3. Hibák

### 3.1 Készpénzről utalásra váltva a pénztárbizonylat árván marad — **súlyos**

**Mi történik.** A bizonylat kap egy új, pozitív folyószámla sort (tartozás), a korábbi automatikus
pénztárbizonylat viszont megmarad, és vele együtt annak ellentétes előjelű folyószámla sora is (azt a
`PenztarbizonylatfejListener::createFolyoszamla()` a pénztárbizonylat tételéből képzi, és `hivatkozottbizonylat`-ként
az eredeti bizonylatra mutat). A kettő kioltja egymást.

**Következmény.**

1. A számla **kiegyenlítettnek látszik**, pedig az utalás még be sem érkezett – nem jelenik meg a
   kintlévőség listán, és nem kap „Kiegyenlít" gombot.
2. A **pénztárban ott van egy készpénz, ami sosem folyt be**. A pénztárjelentés és a pénztárzárás
   hibás egyenleget mutat, a fizikai kasszával nem fog egyezni.
3. Ha a partner utóbb tényleg utal, és azt bankbizonylattal rögzítik, a számla **túlfizetésbe** megy.

**Bizonyíték az adatbázisban.** A galad fejlesztői adatbázisban pontosan egy ilyen bizonylat van:

```
BO2026/000003   Bolti eladás   fizmod: bankkártya (tipus = B)   bruttó: 52 990
  created  2026-08-07 21:15:32
  lastmod  2026-08-07 21:20:16
él hozzá:  PENZ3B2026/000002 (pénztárbizonylat, rontott = 0, 52 990)
           created 2026-08-07 21:19:14

folyószámla sorai:
  #291  +52 990  irány  1  rontott 0   ← a bizonylaté (tartozás)
  #289  -52 990  irány -1  rontott 0   ← az ÉLŐ pénztárbizonylaté
  #290  -52 990  irány -1  rontott 1   ← egy korábbi, RONTOTT pénztárbizonylaté (PENZ1B2026/000004)
egyenleg: 0
```

Az időrend maga a bizonyíték: a pénztárbizonylat 21:19-kor keletkezett (akkor még készpénzes volt a
bizonylat), a bizonylatot 21:20-kor mentették újra – ekkor lett bankkártyás –, és a pénztárbizonylat
életben maradt. A #290-es sor egyben azt is mutatja, hogy a **pénztárváltás** ága működik: azt a
pénztárbizonylatot a rendszer szabályosan rontotta.

**Javaslat.** A `createPenztarBizonylat()` „nem készpénzes / nem mozgat pénzt" ágain ne csak `return`
legyen, hanem előbb a meglévő automatikus pénztárbizonylat rontása. Kódszinten annyi, hogy a
474. és 478. sor `return`-je elé bekerül a `getAutoPenztarBizonylat()` + `rontPenztarBizonylatfej()` pár
(a 482-es osztott fizmódos ágra ugyanez igaz). Így az egyszer létrejött bizonylat **nem vész el** – rontott
lesz, tehát a listán és a naplóban továbbra is látszik, csak a pénzmozgásból esik ki.

### 3.2 Utalásról készpénzre váltva a kézi bankbizonylat mellé új pénztárbizonylat kerül — **súlyos**

A `getAutoPenztarBizonylat()` csak a **pénztárbizonylatok** között keres (`Penztarbizonylatfej` repository).
Ha a számlát korábban kézzel, **bankbizonylattal** egyenlítették ki, arról nem tud: készpénzre váltva
képez egy automatikus pénztárbizonylatot, a bankbizonylat pedig marad. A számla ettől túlfizetettnek
látszik, és a pénztár is többletet mutat.

**Javaslat.** Az összevetést ki kell terjeszteni a bizonylatra hivatkozó, nem rontott bankbizonylat
tételekre is: ha van ilyen, ne képződjön automatikus pénztárbizonylat (a kiegyenlítés már megtörtént),
vagy legalább figyelmeztessen a felület.

### 3.3 A „Kintlévőséget/tartozást képez" pipa levétele is árván hagyja a pénztárbizonylatot

Ugyanaz a mechanizmus, mint a 3.1-nél, csak a 474. soron lép ki. A bizonylat folyószámla sorai eltűnnek,
`getEgyenleg()` fixen 0-t ad (`Bizonylatfej.php:1134`), a pénztárbizonylat folyószámla sora viszont
megmarad – így a **partner összesített egyenlegén** megjelenik egy párját vesztett jóváírás. Ezen az
adatbázison jelenleg nincs ilyen sor (0 db), tehát a hiba egyelőre elméleti, de a kódút azonos.

### 3.4 A „nincs pénzmozgás" fizmódról visszaváltva a pipa nem jön vissza

A `Bizonylatfej::setFizmod()` (`Bizonylatfej.php:3205`) `nincspenzmozgas` fizmódnál `penztmozgat = false`-t
állít, de a visszaváltás nem állítja vissza. A formon a `syncPenztmozgat()` (`bizonylathelper.js:14`) az
eredeti értéket egy `data-elozoertek` attribútumban őrzi – csakhogy egy már mentett bizonylat
újranyitásakor az „eredeti érték" a DB-ből olvasott `false`, tehát a visszaváltás után a pipa **kikapcsolva
marad**. A számla így nem képez kintlévőséget: nem lesz benne a tartozás- és kintlevőség-listákban.

Ez nem adatvesztés, a felhasználó kézzel vissza tudja tenni a pipát – de csöndben rossz az alapértelmezés.

### 3.5 A fizetési mód változásának nincs nyoma — **követhetőségi hiány**

A bizonylaton csak a **státusz** változása naplózódik (`Bizonylatstatusznaplo`,
`BizonylatfejListener::logStatuszValtozasok()`). A fizetési mód, a `penztmozgat` pipa és a pénztár váltása
nem kerül naplóba; a `bizonylatfej.lastmod` csak annyit mond, hogy „valamikor hozzányúltak". Utólag csak
a rontott pénztárbizonylatokból lehet visszakövetkeztetni, hogy volt váltás – abból viszont épp az az eset
hiányzik, amelyik nem ront (3.1).

**Javaslat.** A `Bizonylatstatusznaplo` mintájára a fizetési mód / `penztmozgat` / pénztár változását is
naplózni kellene, dolgozóval és időponttal. Ez a legkisebb változtatás, ami a „követhető legyen, hogy
valami változás volt és mi történt" elvárást teljesíti.

### 3.6 NAV-hoz beküldött számlán is szabadon átírható a fizetési mód

A fizetési mód `select` a formon semmilyen állapotban nincs zárolva. A `loadVars()` kiszámol ugyan egy
`$x['vegleges']` jelzőt a NAV-eredmény alapján (`bizonylatfejController.php:572`), de a bizonylat
sablonjaiban ezt semmi nem használja fel. Egy már beküldött számlán a fizetési mód megváltoztatása a NAV
adatszolgáltatással is eltér a valóságtól (helyesen módosító okiratot kellene kiállítani).

**Javaslat.** A meglévő `vegleges` jelzővel érdemes lenne letiltani (vagy legalább megerősítéshez kötni)
a fizetési mód átírását a beküldött számlákon.

---

## 4. Mit érdemes tenni – sorrendben

1. **3.1** javítása: a nem készpénzes / nem pénzmozgató ágakon rontsuk az automatikus pénztárbizonylatot.
   Ez az egyetlen olyan hiba, ami *magától*, csendben állít elő könyvelésileg hibás állapotot.
2. **Adattisztítás**: a `f.tipus <> 'P'` fizetési módú, nem rontott bizonylatokhoz tartozó élő
   pénztárbizonylatok felülvizsgálata. A lekérdezés (galadon 1 találat):
   ```sql
   SELECT b.id, f.nev, pf.id, pt.brutto
     FROM bizonylatfej b
     JOIN fizmod f ON f.id = b.fizmod_id
     JOIN penztarbizonylattetel pt ON pt.hivatkozottbizonylat = b.id
     JOIN penztarbizonylatfej pf ON pf.id = pt.penztarbizonylatfej_id
    WHERE pf.rontott = 0 AND b.rontott = 0 AND f.tipus <> 'P';
   ```
3. **3.5** naplózás – enélkül a hasonló esetek utólag nem deríthetők ki.
4. **3.2** és **3.6** – ritkább, de valós pénzügyi eltérést okozó esetek.
5. **3.3**, **3.4** – kisebb súlyú, de olcsón javítható.

Egyik javaslat sem törli a már létrejött bizonylatokat: a helyes viselkedés mindenütt a **rontás**, ami
megőrzi a bizonylatot és a sorszámát, csak kiveszi a pénzmozgásból.

---

## 5. Mi készült el (2026-08-12)

| Pont | Állapot | Mit csinál most |
|------|---------|-----------------|
| 3.1 | **javítva** | Készpénzről utalásra/kártyára váltva a rendszer felkínálja a bizonylathoz tartozó pénzmozgások rontását; a rontást kérve a bizonylat egyenlege megnyílik, a tartozás visszakerül a kintlévőségek közé. |
| 3.2 | **javítva** | Ha él a bizonylatra hivatkozó, nem rontott bankbizonylat tétel, készpénzre váltva sem képződik automatikus pénztárbizonylat (`vanEloBankKiegyenlites()`). |
| 3.3 | **javítva** | A `penztmozgat` pipa levétele ugyanazt a kérdést teszi fel, és ugyanazon az úton ront. |
| 3.4 | **javítva** | A `syncPenztmozgat()` betöltéskor a bizonylattípus alapértelmezését őrzi meg korábbi értéknek, így a „nincs pénzmozgás" fizetési módról visszaváltva a pipa visszajön. |
| 3.5 | **javítva** | Naplózás a fizetési mód, a `penztmozgat` és a pénztár változásáról (ki, mikor, miről mire). A bizonylatlista naplógombja időrendben mutatja – lásd a 8. pontot. |
| 3.6 | **másképp megoldva** | A `vegleges` (NAV-eredmény) alapú zárolás kikerült. Helyette a karb **egészben** szerkeszthetetlen, ha a bizonylat ki van nyomtatva és a típusa `editprinted = 0` – lásd a 7. pontot. Így nem csak a fizetési mód védett, hanem minden mező. |
| 4/2. adattisztítás | **nyitva** | Szándékosan kimaradt – a meglévő adatokhoz nem nyúltunk. |

### Amire figyelni kell

- **A rontás nem automatikus: a felhasználó dönt** (6. pont). A kézzel rögzített pénzmozgásra is
  vonatkozik – épp ez a kérdés értelme. A rontás nem törlés: a bizonylat a sorszámával együtt megmarad,
  csak kiesik a pénzmozgásból.
- A `koltsegszamla` típusnak **nincs** automatikus pénztárbizonylata, ezért a `createPenztarBizonylat()`
  már a legelső feltételen kilép rá. A hozzájuk kézzel rögzített (galadon 66 db) pénztárbizonylatokat a
  javítás **nem érinti** – csak az automatikus pénztárbizonylatot képző öt típuson (`bevet`, `boltieladas`,
  `esetiszamla`, `keziszamla`, `szamla`) fut le a ront-ág.
- A napló új táblát igényel: minden telepítésen kell rá egy `./updateschema.sh` (lásd a 8. pontot).
  A galad és a superzoneb2b fejlesztői adatbázisán már megvan.

### Hogyan lett ellenőrizve

A javítások a galad fejlesztői adatbázisán, a `BO2026/000003` bizonylaton, **tranzakcióban futtatva és
visszagörgetve** lettek próbálva (az adatbázisban nem maradt nyoma):

```
1. lepes: KESZPENZRE allitva   elo penztarbizonylat = [PENZ1B2026/000007]   egyenleg = 0
2. lepes: UTALASRA valtva      elo penztarbizonylat = []                    egyenleg = 52990   <<< a javitas
   naplo: [Fizetési mód: bankkártya -> KÉSZPÉNZ, Fizetési mód: KÉSZPÉNZ -> ÁTUTALÁS]

3.3  penztmozgat = 0 utan      elo penztarbizonylat = []
     naplo: [..., Kintlévőséget/tartozást képez: igen -> nem]
3.2  elo bankbizonylat mellett keszpenzre valtva: NEM keletkezett uj penztarbizonylat

kezire atirt penztarbizonylat, bankkartya -> atutalas:
     penztarbizonylat rontott = 1, egyenleg = 52990   (a kezit is rontja)
```

A 3.6 és a 3.4 böngészőben lett ellenőrizve (a NAV-eredmény, illetve a `nincspenzmozgas` jelző
ideiglenes átállításával, utána visszaállítva): a zárolt `select` mellett a form továbbra is a helyes
fizetési módot küldi be, a „nincs pénzmozgás"-ról visszaváltva pedig a pipa visszajön.

---

## 6. A mentéskor feltett kérdés (2026-08-12)

A 3.1/3.3 javítása eredetileg magától rontotta a meglévő pénzmozgást. Ez két okból nem volt jó:
a kézzel rögzített pénztárbizonylat egy ember állítása arról, hogy a pénz fizikailag mozgott, és
egy teljesen más okból indított mentés is hozzányúlt volna. Ezért a döntés a felhasználóé lett.

**Mikor kérdez.** Ha a mentésben megváltozik a **fizetési mód** vagy a **„Kintlévőséget/tartozást
képez"** jelölő, és a bizonylathoz tartozik még élő pénztár- vagy bankbizonylat. Új bizonylatnál
nincs mit rontani, ott nem kérdez. A pénztár átállítását nem kérdezi meg: azt a meglévő
összevetés (`autoPenztarBizonylatEgyezik()`) amúgy is helyesen kezeli.

**Mit kérdez.** A párbeszéd felsorolja az érintett bizonylatokat (típus, sorszám, kelt, összeg), és
három válasz közül lehet választani:

| Válasz | Mi történik |
|--------|-------------|
| **Rontsa** | A bizonylathoz tartozó összes élő pénztár- ÉS bankbizonylat rontott lesz. |
| **Maradjanak** | Egyik pénzmozgáshoz sem nyúlunk. |
| **Mégsem** | A mentés elmarad, vissza a szerkesztéshez. |

**A válasz a naplóba kerül** – „Kapcsolódó pénzmozgás: rontva" vagy „… változatlanul hagyva" –,
a fizetési mód / jelölő változása mellé, ugyanabban az időrendi listában.

A naplót a bizonylatlista sorában a **jegyzettömb ikon** (Bizonylat napló) nyitja. Ez korábban csak
azokon a típusokon látszott, ahol a bizonylatstátusz-szerkesztő be van kapcsolva – tehát épp a
számlán, költségszámlán, bolti eladáson **nem**. Mivel a napló már nem csak státuszváltást mutat,
a gomb mostantól minden bizonylattípus listáján ott van.

**Ha a fizetési mód nem változik, a mentés hozzá sem nyúl a pénzmozgásokhoz.** Ez a fontos
biztonsági tulajdonság: egy megjegyzés átírása nem érinti a pénztárat.

**Alapértelmezés: nem rontunk.** Ha a kérdés bármiért elmarad (nem a karb formról jön a mentés,
elszáll a lekérdezés), a rejtett mező marad 0-n. Inkább maradjon a régi állapot, mint hogy magától
tűnjön el egy pénzmozgás.

### Amit tudni kell a „Maradjanak" válaszról

Ilyenkor a korábban dokumentált tünetek szándékosan megmaradnak – de már a felhasználó döntéséből,
naplózva:

- készpénzről utalásra váltva a számla továbbra is kiegyenlítettnek látszik, és a pénztárban marad
  a készpénz;
- a `penztmozgat` levételekor a bizonylat saját folyószámla sorai eltűnnek, a pénztárbizonylaté
  viszont megmarad, tehát a partner egyenlegén **jóváírásként** jelenik meg (a példabizonylaton
  −52 990). Ez logikusan következik abból, hogy a pénz tényleg befolyt, a bizonylat viszont már nem
  képez tartozást.

### Érintett fájlok

- `Listeners/BizonylatfejListener.php` – `rontKapcsolodoPenzmozgas()`, `getEloPenztarBizonylatok()`,
  `getEloBankBizonylatok()`, `rontBankBizonylatfej()`, `PENZMOZGASTERINTOMEZOK`
- `Entities/Bizonylatfej.php` – két nem perzisztált jelölő: `rontkapcsolodopenzmozgas` (a válasz),
  `penzugyimezovaltozott` (a listener tölti)
- `Controllers/bizonylatfejController.php` – `getKapcsolodoPenzmozgas()` végpont, a válasz átvétele
- `js/admin/default/bizonylathelper.js` – `penzugyiMezoValtozott()`, `kerdezPenzmozgasrol()`

---

## 7. Csak olvasható karb (2026-08-12)

A 3.6 eredetileg a NAV-eredményhez kötötte a fizetési mód zárolását. Ez két sebből vérzett: csak
egyetlen mezőt védett, és olyan bizonylatokat is érintetlenül hagyott, amiket amúgy sem szabadna
módosítani. Helyette a karb egésze zárható lett.

**A szabály.** A bizonylat karbja szerkeszthetetlenül töltődik be, ha a bizonylat **ki van
nyomtatva** (`nyomtatva = 1`) és a **típusa nem engedi a nyomtatás utáni szerkesztést**
(`bizonylattipus.editprinted = 0`). A bizonylatlista eddig is csak ekkor rejtette el a szerkesztés
linket – de a karb URL-jét beírva a form így is megnyílt és menthető volt.

A képzett és a stornó bizonylat **nem** zárolt: azok új rekordok, ki kell tudni tölteni őket.

**Általános, nem bizonylat-specifikus.** A `jquery.mattkarb.js` kapott egy csak olvasható
üzemmódot, ami bármelyik karb formon használható: elég a formra tenni a `data-readonly="1"`-et
(vagy a setupban átadni a `readonly: true`-t). Ilyenkor a widget

- letiltja az összes űrlapmezőt (input, select, textarea, button) a Mégsem gomb kivételével,
- elrejti a mentés gombot,
- elrejti a sorfelvevő/-törlő ikonlinkeket – ezek nem űrlapmezők, de a repóban egységesen a
  `ui-icon-circle-plus` / `ui-icon-circle-minus` ikont viselik,
- elrejti, amit a hívó a **`js-karbmodosito`** osztállyal megjelöl (a szöveges műveleti linkeknek,
  pl. „Tételek betöltése xlsx-ből"),
- ráteszi a konténerre a `mattkarb-readonly` osztályt (a CSS ettől hagyja olvashatóan a tiltott
  mezőket, nem halványítja el őket).

A navigációs linkek (nyomtatás, PDF, kapcsolódó bizonylatok, termékkarton) szándékosan maradnak:
azok nem módosítják a rekordot.

**Szerveroldali kapu is van.** A `MattableController` kapott egy `isReadonly($record)` hookot
(alapból mindig `false`), amit a `saveData()` az `edit` és a `del` ágon megnéz, és kivételt dob.
A kliensoldali tiltás így csak kényelem: egy kézzel összerakott POST sem megy át. A `bizonylatfej`
leszármazottja implementálja a fenti nyomtatva/editprinted szabályt.

### Ellenőrzés

| Eset | Eredmény |
|------|----------|
| `SZ2026/000001` (számla, nyomtatva=1, editprinted=0) | 86 mezőből 86 tiltva, OK gomb eltűnt, Mégsem maradt, „új tétel"/„töröl" ikonok és az importgombok elrejtve, fejlécben a magyarázat |
| `SZ2026/000002` (számla, nyomtatva=0) | teljesen szerkeszthető, 0 tiltott mező |
| `SZ2026/000001` + `oper=inherit` | teljesen szerkeszthető (új rekord) |
| Kézi `POST /admin/szamlafej/save` a zárolt számlára | a `belsomegjegyzes` és a `lastmod` sem változott |
| költségszámla (editprinted=1), nyomtatva bármi | nem zárolt |

---

## 8. Egyesített bizonylat napló (2026-08-12)

A napló először két táblán élt: a régi `bizonylatstatusznaplo` (státuszváltás) és az e munka során
született `bizonylatvaltozasnaplo` (pénzügyi mezők). A kettőt a képernyő úgyis egy időrendi listába
fésülte, a szétválasztás csak terhet jelentett. Helyettük **egyetlen `bizonylatnaplo`** van.

### Mit naplóz

| Esemény | Mikor | „Mit" oszlop | Erről → Erre |
|---------|-------|--------------|--------------|
| `letrehozas` | a bizonylat elmentésekor, először | Létrehozás | – |
| `mentes` | minden későbbi mentéskor, **akkor is, ha semmi nem változott** | Mentés | – |
| `mezovaltozas` | naplózott mező változásakor | a mező neve | régi → új érték |

Naplózott mezők: **státusz**, **fizetési mód**, **kintlévőséget/tartozást képez**, **pénztár**,
**nyomtatás**. A nyomtatás azért fér ide, mert a `setNyomtatva()` a `nyomtatva` jelölőt írja át –
így a nyomtatás visszavonása is látszik (`igen → nem`). Ide kerül a fizetésimód-váltáskor feltett
kérdésre adott válasz is („Kapcsolódó pénzmozgás: rontva / változatlanul hagyva").

A `letrehozas` és a `mezovaltozas` a `BizonylatfejListener`-ből jön (az insert eseményből, illetve
a UnitOfWork changesetjéből, még a recompute előtt). A `mentes` a controller `afterSave()`-jéből:
a listener csak akkor látna bármit, ha tényleg változott valami, a mentés ténye viszont akkor is a
bizonylat története, ha a felhasználó csak megnyitotta és rábólintott.

### Ki csinálta

A `SYSADMIN` belépésnek nincs `Dolgozo` rekordja (`pk = -1`), ezért a `getLoggedInDolgozo()` null-t
adott, és a „Módosította" oszlop üresen maradt. Az új `store::getLoggedInDolgozoNev()` ilyenkor a
munkamenetből veszi a nevet, így a napló SYSADMIN-ként is megmondja, ki járt a bizonyla­ton.

### Migráció és a régi táblák

A `runonce.php` 0116-os blokkja átemeli a régi két tábla sorait (a státuszsorokból
`mezovaltozas` / „Státusz" lesz). **A régi táblákat nem dobjuk el**: az `updateschema.sh`
`--complete` nélkül fut, tehát érintetlenül maradnak. Miután a napló rendben van, kézzel
eldobhatók:

```sql
DROP TABLE bizonylatstatusznaplo;
DROP TABLE bizonylatvaltozasnaplo;
```

### Ellenőrzés

Tranzakcióban futtatva és visszagörgetve:

```
MIGRACIO UTAN      mezovaltozas  Státusz                Rögzítve -> Teljesítve
FIZMOD VALTAS      mezovaltozas  Fizetési mód           bankkártya -> KÉSZPÉNZ
                   mezovaltozas  Kapcsolódó pénzmozgás  (-) -> rontva
NYOMTATAS UTAN     mezovaltozas  Nyomtatás              nem -> igen
LETREHOZAS UTAN    letrehozas    Létrehozás
MENTES (edit)      mentes        Mentés
MENTES (add)       – nem keletkezett új sor (az a Létrehozás)
```

Böngészőből, valódi mentéssel (SZ2026/000002, változtatás nélkül): egyetlen sor keletkezett –
`Mentés`, `Módosította = SYSADMIN`. A számla adatai (nettó/áfa/bruttó, tételszám, folyószámla
egyenleg) változatlanok, csak a `lastmod` frissült.
