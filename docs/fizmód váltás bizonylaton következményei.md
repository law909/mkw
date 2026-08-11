# Fizetési mód utólagos módosítása pénzt mozgató bizonylaton

*Készült: 2026-08-11. Kódolvasás + a galad fejlesztői adatbázison végzett, csak olvasó ellenőrzés alapján.*

> **2026-08-12: a 4. pont javításai elkészültek** (az adattisztítás kivételével, az továbbra is nyitott).
> A 3.1–3.6 alatti leírások az **eredeti, hibás** viselkedést dokumentálják; hogy mi lett belőlük,
> azt az 5. pont foglalja össze.

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
| 3.1 | **javítva** | Készpénzről utalásra/kártyára váltva a `createPenztarBizonylat()` a kilépés előtt rontja az automatikus pénztárbizonylatot. A bizonylat egyenlege megnyílik, a tartozás visszakerül a kintlévőségek közé. |
| 3.2 | **javítva** | Ha él a bizonylatra hivatkozó, nem rontott bankbizonylat tétel, készpénzre váltva sem képződik automatikus pénztárbizonylat (`vanEloBankKiegyenlites()`). |
| 3.3 | **javítva** | A `penztmozgat` pipa levétele ugyanazon az úton rontja az automatikus pénztárbizonylatot. |
| 3.4 | **javítva** | A `syncPenztmozgat()` betöltéskor a bizonylattípus alapértelmezését őrzi meg korábbi értéknek, így a „nincs pénzmozgás" fizetési módról visszaváltva a pipa visszajön. |
| 3.5 | **javítva** | Új `bizonylatvaltozasnaplo` tábla: a fizetési mód, a `penztmozgat` és a pénztár változása naplózódik (ki, mikor, miről mire). A bizonylatlista naplógombja mostantól a státuszváltásokkal együtt, időrendben mutatja. |
| 3.6 | **javítva** | A NAV-hoz beküldött (DONE/WAITING) számlán a fizetési mód `select` tiltott; rejtett mező viszi tovább a jelenlegi értéket, és egy magyarázó sor jelzi az okát. A belőle képzett vagy stornó bizonylaton nincs zárolás. |
| 4/2. adattisztítás | **nyitva** | Szándékosan kimaradt – a meglévő adatokhoz nem nyúltunk. |

### Amire figyelni kell

- **Csak az automatikus pénztárbizonylatot rontjuk.** A kézzel rögzítettet nem: az egy ember állítása
  arról, hogy a pénz fizikailag mozgott, azt nem írhatja felül egy fizmód-átállítás. A megkülönböztetés a
  pénztárbizonylat megjegyzésén megy (`BizonylatfejListener::AUTOPENZTARMEGJEGYZES`). Ez azt is jelenti,
  hogy **kézi pénztárbizonylatnál a 3.1 tünete megmarad** – ott a felhasználónak kell dönteni a sorsáról.
  Ez tudatos választás; ha inkább az kell, hogy a kézit is rontsuk, egy sor a `rontAutoPenztarBizonylat()`-ban.
- A `koltsegszamla` típusnak **nincs** automatikus pénztárbizonylata, ezért a hozzájuk kézzel rögzített
  (galadon 66 db) pénztárbizonylatot a javítás nem érinti.
- A `bizonylatvaltozasnaplo` tábla új: minden telepítésen kell rá egy `./updateschema.sh`.
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
```

A 3.6 és a 3.4 böngészőben lett ellenőrizve (a NAV-eredmény, illetve a `nincspenzmozgas` jelző
ideiglenes átállításával, utána visszaállítva): a zárolt `select` mellett a form továbbra is a helyes
fizetési módot küldi be, a „nincs pénzmozgás"-ról visszaváltva pedig a pipa visszajön.
