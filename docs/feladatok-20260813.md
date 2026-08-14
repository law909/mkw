- [x] **KÉSZ** minboltikeszlet szövegekből csinálj minkeszlet-et változónevekben, metódusnevekben, entitásnevekben, mindenhol ahol előfordul. FIGYELEM! úgy írd
  meg a runonce-t, hogy lesz deploy ahol előtte és utána is futni fog sok blokk és updateschema már lefutott
  > Részletek: `docs/minkeszlet-atnevezes.md`. A migráció a `runonce.php` **legelejére** került, a DBVersion-lánc elé, saját jelölővel
  > (`consts::MinkeszletRename`) — mert a lánc 0113/0117/0118 blokkja már az új néven mozgatja az adatot, tehát addigra az átnevezésnek meg kell történnie.
  > Mind a négy állapotot kezeli (csak régi / régi+új / csak új / régi nevű index), information_schema-őrökkel, akárhányszor lefuthat.
  > **Deploy sorrend:** előbb egy admin kérés (runonce), utána `./updateschema.sh`. Leellenőriztem: a schema-tool a kezelt táblákból **eldobja** az ismeretlen
  > oszlopot, tehát fordított sorrendben a `termek.minboltikeszlet` / `termekvaltozat.minboltikeszlet` tartalma elvész, mielőtt a runonce átmenthetné. A két
  > raktáras táblát a schema-tool nem bántja, azt a runonce utólag is átmenti.
  > A megjelenő feliratok („Min. készlet") nem változtak, csak az azonosítók. A `docs/raktarankenti-minimum-keszlet.md` terv szándékosan a régi néven maradt
  > (történeti dokumentum), csak egy hivatkozó megjegyzést tettem bele.
- [x] **KÉSZ** minimum készlet export/importban a raktár oszlopok neve legyen id_nev, így tudod azonosítani a raktárt akkor is ha megváltozik a neve
  export/import között
  > `Services/MinKeszletExcelService.php`. Az export fejléce most `3_KISKER RAKTÁR`, az import a `^(\d+)_` előtagból veszi a raktár id-t.
  > A csak nevet tartalmazó régi fejlécet továbbra is elfogadja (visszafelé kompatibilis a korábban letöltött fájlokkal), az ismeretlen fejléc pedig
  > ugyanúgy figyelmeztetést ad és kimarad. Kipróbálva: átnevezett raktárfejléccel is a jó raktárra kerül az érték.
  > Mellékesen: a fejléceket eddig a raktárnevekre is ráeresztette a `t()` fordító, ezt szétszedtem – a raktárnév nem fordítandó.
- [ ] **TERV KÉSZ, KÓDOLÁS VÁR JÓVÁHAGYÁSRA** bizonylaton fizetési mód váltáskor a program rákérdez, hogy mi legyen e létező pénztár és bank bizonylatokkal.
  Rontáskor és stornokor rontsa le. lehet hogy ez már le van fejlesztve, ellenőrizd. bizonylat stornokor ha van pénztárbizonylat, akkor jöjjön fel egy kérdés
  hogy visszafizeti a pénztárból a pénzt vagy csak rontja a pénztárbizonylatot. ha visszafizeti a pénzt, akkor storno bizonylat is pnztmozgat, ha csak rontja a
  pénztárbizonylatot akkor storno bizonylat nem mozgat pénzt és rontani kell a pénztárbizonylatot.
  > **Terv: `docs/storno-penzmozgas.md`.** Kérted, hogy előbb csak a terv készüljön el, ezért kódolni még nem kezdtem.
  >
  > **Ellenőrzés eredménye — a fizmód-váltáskori rákérdezés már kész** (2026-08-12, `docs/fizmód váltás bizonylaton következményei.md`): pénztár- és
  > bankbizonylatra egyaránt, három válasszal (Rontsa / Maradjanak / Mégsem), a válasz a bizonylatnaplóba is bekerül.
  >
  > **Két hiány maradt:**
  > 1. `BizonylatfejListener.php:1120` — rontáskor csak a *pénztár*bizonylatot rontja (`rontPenztarBizonylat()`), a bizonylatra hivatkozó élő *bank*bizonylatot
  >    nem. Ez egyértelmű hiba, a storno-kérdéstől függetlenül.
  > 2. `PenztarbizonylatfejListener:123-124` és `BankbizonylatfejListener:123-124` — a pénzmozgás folyószámla sora fixen `setStorno(false)` /
  >    `setStornozott(false)`, tehát a stornózott számla kiegyenlítése bent marad az egyenlegben párját vesztett jóváírásként.
  >
  > A javasolt megoldás a `penztmozgat` mezőt használja a válasz hordozójának (nem kell új oszlop), és mind a négy általad felsorolt esetet lefedi — a
  > kiegyenlített utalásos storno a storno számlán visszautalandó tartozásként fog látszani. **Egy nyitott döntés van** a tervben (5. pont): a meglévő storno
  > bizonylatokat runonce-szal igazítsuk-e, vagy inkább külön mező legyen. A galad fejlesztői DB-n 0 érintett sor van, ezt éles adaton kell megnézni.
- [x] **KÉSZ** bizonylattetelkarbformon a termék autocomplete mellett legyen 2 gomb: 1. "új", új fülön nyissa meg az új termék karbantartót; 2. "adatlap" ha van
  máár kiválasztva termék, nyissa meg a termék karbantartóját
  > Új közös részsablon: `tpl/admin/default/bizonylatteteltermekgombok.tpl`, beemelve a `bizonylattetelkarb.tpl` mindkét ágába (autocomplete és sima select is)
  > és a `bizonylattetelquickkarb.tpl`-be. Mindkét gomb új lapra nyit (`target="_blank"`), hogy a félig kitöltött bizonylat ne vesszen el.
  > „Új" → `/admin/termek/viewkarb?id=0&oper=add`, statikus href. „Adatlap" → a href kattintáskor áll össze a sor aktuális `.js-termekid` értékéből
  > (`bizonylathelper.js`), mert a tétel terméke a betöltés után is változhat. Ha nincs kiválasztott termék, „Előbb válasszon terméket." üzenet jön.
  > Storno tételen az „Új" nem jelenik meg (ott a terméket sem lehet cserélni), az „Adatlap" igen.
  > Böngészőben kipróbálva az SZ2026/000002 számlán: mindkét gomb kirajzolódik, a href jól áll össze, üres termékre jön az üzenet.
- [x] **KÉSZ** a projekt gyökérben van két GLS_*.xlsx, írj egy importot ami importálja ezeket az utánvétes kifizetéseket, csak azokat a sorokat kell importálni,
  amelyikek Q oszlopa (Beszedett utánvét összege (HUF)) nem nulla. csinálj nekik egy ideiglenes táblát bank tranzakció import mintájára, importkor próbáld meg
  kitalálni, melyik bizonylathoz tartozhat a befizetés:
  fuvarlevélszám, aztán név összeg és cím alapján. Ha olyan bizonylatot találsz ami nem mozgat pénzt akkor keresd meg a belőle készült pénzt mozgató
  bizonylatot. Az importált tételekhez lehessen bizonylatszámot megadni egy karbantartóban bank tranzakcióhoz hasonlóan.
  > **Részletes leírás: `docs/gls-utanvet-import.md`.** Menü: Bank, pénztár → **GLS utánvétek** és **GLS utánvét import** (a runonce 0122 teszi be).
  > Új tábla `glsutanvet` (`Entities/GLSUtanvet.php`), a duplikátumszűrő a csomagszám – ugyanaz a kimutatás kétszer feltöltve nem csinál duplikátumot.
  >
  > **Egy dolgot hozzátettem a kért két lépcsőhöz** (szólj, ha nem kell): a fuvarlevélszám után, a név+összeg+cím **előtt** megnézem a kimutatás
  > hivatkozás-mezőit is – a 2025-ös mintafájlban ott áll maga a bizonylatszám (`MR2025/002079`). Csak **pontos** bizonylatszám-egyezést fogadok el, tehát ha a
  > mező webshopos rendelésszám (a 2026-os fájlban `5705`), ez az ág nem talál semmit és nem is tippel.
  >
  > **Amit a kódolás közben derült ki:** a képzett bizonylat **átveszi az előd fuvarlevélszámát**, tehát egy megrendelés és a belőle készült számla egyaránt
  > illik a csomagszámra. Ezért a párosítás nem „egy találat vagy semmi", hanem összegyűjti a jelölteket, végigjárja a leszármazottaikat, és a **pénzt mozgató**
  > bizonylatot választja – ez oldja fel a láncot is, meg a te „ha nem mozgat pénzt, keresd meg a belőle készültet" kérésedet is. Ha több pénzt mozgató
  > bizonylat jönne ki, üresen hagyja: egy téves találat rossz bizonylatra könyvelné a pénzt.
  >
  > **Ami szándékosan kimaradt:** a párosított tételekből **nem** képez bank-/pénztárbizonylatot (a bank tranzakciónál erre van csoportos művelet). A feladat a
  > táblát, az importot, a párosítást és a karbantartót kérte. Ha kell a könyvelés is, a `banktranzakcioController::generateBankbizonylat()` mintájára megy –
  > az utánvét a futárszolgálat átutalásaként érkezik, tehát bankbizonylat való hozzá egy „GLS utánvét" jogcímmel. Szólj, és megcsinálom.
  >
  > **Kipróbálva** mindkét mintafájllal (2/6 és 4/16 sor jött be, az újraimport 0 újat csinált), és mind a három párosítási ág külön is – lásd a doksi végén az
  > ellenőrzési táblázatot.
- [x] **KÉSZ** a projekt gyökérben van egy "Mir Order.xls", a szállítói megrendeléseknek csinálj egy gombot amivel ilyen formátumban ki lehet exportálni őket. A
  gomb legyen ott minden száll.megrendelés sorban, azt exportálja amelyik sorban van
  > **Gomb:** „Mir" a szállítói megrendelés lista minden sorában (`bizonylatfejlista_tbody_tr.tpl`, `bizonylattipusid=='szallmegr'` mögött), új lapra tölt le.
  > **Kód:** `Services/MirOrderExcelService.php` + `szallmegrfejController::mirExport()` + `GET /admin/szallmegrfej/mirexport`.
  > **A formátum méret-mátrix:** egy sor = egy termék+szín, az oszlopok a méretek. A két méretskála (nadrág 29–42 az F oszloptól, felsőrész S–6XL az E-től) a
  > minta 3. és 4. sorának fejléce – ezek a formátum részei, ezért fixen beégetve mennek ki, nem a bizonylatból jönnek.
  > **Megjegyzés/döntés:** ami egyik skálába sem illik (pl. a galad adatain a „70"-es méret), az az **N oszlopba** kerül – az is benne van a `TOT PCS`
  > összegben –, a mérete pedig zárójelben a névhez ragad (`GÁZBOWDEN CFMOTO 125NK (70: 2)`), hogy a szállító lássa, mit rendeltünk. Így semmi nem vész el
  > csendben. Ha inkább kimaradna vagy hibát kellene dobnia, szólj.
  > Csoportfejléc-sor (a mintában `KEVLAR JEANS:`) a termék legmélyebb kitöltött kategóriájából képződik; ha nincs kategória, nincs fejlécsor.
  > **Kipróbálva:** létrehoztam egy `SZMR2026/000001` szállítói megrendelést a galad fejlesztői DB-n (TESZT SZALLITO, 5 db KABÁT ARMR SUKO 1.0 KÉK/S, 1000 Ft) –
  > a gomb kirajzolódik, a letöltés valódi xlsx, a tartalom a helyes oszlopokba kerül. **Ez a teszt bizonylat bent maradt az adatbázisban**, hogy ki tudd
  > próbálni; ha nem kell, rontsd le.
- [x] **KÉSZ** termék és partner karbantartón a dokumentumok fülön a dokumentum input mellett a ... gomb mellett van egy O gomb ami legyen gomb külsejű és
  nyissa meg a dokumentumot, ne pedig letöltse. A gomb captionje legyen "Megnyit"
  > **Ok: a `.button()` hívásból kimaradt.** A `dokumentumtarkarb.tpl` két megnyitó linket tartalmaz: a „Web cím" sorét (`js-dokopenbutton`) és a
  > „Dokumentum" sorét (`js-dokopen2button`). A `termek.js`, `partner.js` és `rendezveny.js` a jQuery UI `.button()`-t csak az elsőre hívta meg, ezért a
  > második csupasz szövegként látszott. (A `bizonylathelper.js` mindkettőt gombosítja – ott jó is volt.) Mind a hat helyre bekerült a hiányzó osztály.
  > A felirat `O` → `Megnyit`, és a link kapott `rel="noopener"`-t is.
  > **A letöltés ügye:** a link a statikus fájlra mutat (`/kepek/...`), az Apache `application/pdf`-fel és `Content-Disposition` nélkül szolgálja ki, tehát
  > semmi nem kényszerít letöltést. Böngészőben kipróbálva (ideiglenes dokumentum rekorddal termék 1-en, utána törölve): a PDF **új fülön megnyílik**, nem
  > töltődik le. Ami maradhat: a böngésző azokat a típusokat, amiket nem tud megjeleníteni (docx, xlsx, zip), mindig letölti – ezt kódból nem lehet
  > felülbírálni. Ha nálad PDF-re is letöltés jön, az a Chrome „PDF-ek letöltése megnyitás helyett" beállítása.
- [x] **KÉSZ** bolti eladásokat nem lehet lerontani, megnyomom a gombot és nem törénik meg a rontás. galad deployon ki tudod próbálni
  > **Ok: hiányzott a route.** A gomb a `/admin/<entitas>/ront` címre POST-ol (`bizonylathelper.js`), és `boltieladasfej`-re ez a route nem létezett az
  > `adminroute.php`-ban. Az AltoRouter nem talált egyezést, ezért a kérés csendben, 200-zal és üres törzzsel tért vissza — pontosan az a tünet, amit írtál.
  > Egy sor a javítás (`adminroute.php`, a `boltieladasfej` blokk `isClosed()` ágába, a többi bizonylattípus mintájára).
  > **Átnéztem az összes bizonylatfej-listát is:** `van viewlist, de nincs ront` = `boltieladasfej`, `esetiszamlafej`, `leltarfej`, `szamlafej`. Ebből
  > az `esetiszamla` és a `szamla` `showstorno = 1`, tehát ott a lista storno gombokat rajzol ront helyett — nincs szükségük rá; a `leltarfej` pedig saját
  > lista sablont használ, amiben nincs ront gomb. Vagyis egyedül a bolti eladás volt hibás.
  > **Kipróbálva galadon** a BO2026/000004-en: a rontás lefutott (`bizonylatfej.rontott`, `bizonylattetel.rontott`, a folyószámla sor újraképződött
  > rontottként), majd a bizonylatot **visszaállítottam** az eredeti állapotába (a folyószámla sor id-je 338→348, a tartalma azonos).
- [x] **KÉSZ** vegyél fel egy beállítást config.ini-be: path.dokumentum amit path.mediatar-on vagy path.ckfinder-en belül kell értelmezni. partner és termék
  dokumentumok fülre fentre tegyél egy új gombot: "Azonnali feltöltés", ezt megnyomva lehessen feltölteni egy fájlt a konfigban megadott mappába és szülesseb
  belőle egy dokumentum rekord. a rekord jelenjen is meg a karbantartón. figyelj rá hogy új terméknél/partnernél a dokumentum bekerül a mappába de a rekord nem
  mentődik csak a karbantartó mentésekor.
  > **Beállítás:** `path.dokumentum` (config.ini), a médiatár gyökeréhez képest értelmezve. Alapértelmezés `dokumentum`, tehát beállítás nélkül is működik;
  > a mappát az első feltöltéskor magától létrehozza. Dokumentálva a CLAUDE.md konfigurációs fejezetében, és beírtam a `galadconfig.ini`-be is.
  > **Új fájlok:** `Services/DokumentumUploadService.php` (mappa feloldás + létrehozás), `Controllers/dokumentumtarController.php` (a közös feltöltő végpont),
  > `js/admin/default/dokumentumtar.js`, `tpl/admin/default/dokumentumfeltoltes.tpl`.
  > **Egy végpont mindkét törzshöz:** a `dokumentumtarkarb.tpl` sor mezőnevei nem függenek attól, melyik entitáshoz tartozik a dokumentum (a `Dokumentumtar`
  > single-table öröklődés `osztaly` diszkriminátorát a karb mentése tölti ki), ezért nem kellett külön termék/partner végpont.
  > **A rekord tényleg csak mentéskor születik meg:** a válasz `oper = add` állapotú sor, amit a karb a saját mentésével visz be. Kipróbálva mind a három
  > esetben: meglévő terméken (mentés után létrejött a rekord), meglévő partneren, és **új terméken** (`id=0&oper=add`) – ott a fájl bekerült a mappába,
  > rekord nem keletkezett. Utána minden tesztfájlt és rekordot töröltem.
  > **Mellékesen:** a médiatár HTTP-őrei (`requireAdmin`, `requireWritable`, `requireSameOrigin`, `checkPostMaxSize`, `json`, `jsonError`) átkerültek a
  > `Traits/MediatarGuard.php`-ba, hogy az új végpont ugyanazt a védelmet kapja duplikálás nélkül. A route szándékosan **nincs** a `mediatar` kapcsoló mögött:
  > a `path.dokumentum` a CKFinder-es telepítéseken is értelmes.
  > A feltöltés a `MediatarService`-en megy keresztül, tehát örökli a kiterjesztés- és tartalomellenőrzést, a névtisztítást és az ütközésfeloldást.
-

---

## Biztonsági hiba, amit a kódolás után találtunk (2026-08-14)

A commit utáni biztonsági ellenőrzés **könyvtárbejárást / tetszőleges fájl írását** jelezte a GLS importban. Megnéztem: **valós volt, és súlyosabb is, mint
elsőre látszik.** Javítva, kipróbálva.

**Mi volt a baj.** Az import a feltöltött fájlt a *beküldött néven* mentette a `storage/`-ba:

```php
$filenev = \mkw\store::storagePath($_FILES['toimport']['name']);   // a nev tamado-vezerelt
move_uploaded_file($_FILES['toimport']['tmp_name'], $filenev);
```

A `store::storagePath()` sima string-összefűzés, tehát a `../../` a névben kilép a könyvtárból. **És a `storage/`-t az Apache kiszolgálja** – ezt ebben a
munkamenetben ellenőriztem –, úgyhogy egy `.php` feltöltése nem csak fájlírás, hanem **kódfuttatás** lett volna. Admin jogosultság kell hozzá, tehát nem
kívülről támadható, de egy admin fiókból shellt adott volna.

**Mit javítottam.**

1. `Controllers/glsutanvetController.php` – a célnevet most **teljes egészében a program állítja elő** (`uniqid()` + fehérlistás kiterjesztés), a beküldött név
   egyáltalán nem kerül bele. Csak `.xls` / `.xlsx` mehet, van `is_uploaded_file()` ellenőrzés, és a hibás fájl sem marad a lemezen.
2. ~~**`storage/.htaccess`** – a `kepek/.htaccess` mintájára, hogy a `storage/`-ban semmilyen körülmények között ne futhasson PHP.~~
   **Ezt te levetted** (`641c14b2b`), tudomásul vettem. A lyukat így kizárólag az 1. pont zárja – ami elég is, mert a beküldött név sehol nem kerül az
   útvonalba, tehát `.php` nevű fájl nem is jöhet létre a `storage/`-ban. Csak a mélységi védelem esett ki: ha valaha bekerül egy új feltöltő, ami megkerüli a
   közös segédfüggvényt, azt már nem fogja meg semmi.

**Kipróbálva:** a `gonosz.php`, a `../../gonosz.php` és a `gonosz.xlsx.php` feltöltését az import visszautasítja; a `../../gonosz.xlsx` néven feltöltött
**érvényes** fájl a `storage/glsutanvet-<uniqid>.xlsx`-be kerül, a projekt gyökerébe semmi nem íródott. (Amíg a `.htaccess` fent volt, a `storage/`-ba tett
teszt PHP 403-at adott és nem futott le.)

### A többi feltöltő végpont javítása (kérésedre)

Ugyanez a minta a repó többi importálójában is élt. Végigvittem: **21 hívási hely** javítva, közös segédfüggvénnyel.

**`mkw\store::moveUploadedFile($mezo, $prefix, $engedett)`** – új, a `storagePath()` szomszédja. Ellenőrzi az `is_uploaded_file()`-t, a kiterjesztést
fehérlistázza (`xls, xlsx, xlsm, csv, txt, xml, ods` – egyik sem olyan, amit a webszerver futtatna), a célnevet pedig **teljes egészében ő állítja elő**
(`uniqid($prefix)` + a fehérlistás kiterjesztés). A beküldött névből semmi nem kerül az útvonalba, tehát se `../`, se `.php` nem érvényesül.

| fájl | hely |
|------|------|
| `importController.php` | 11 |
| `galadCGMImportController`, `galadSuomyImportController`, `galadProductImportController`, `galadOxfordImportController` | 4 |
| `banktranzakcioController`, `leltarfejController`, `partnertermekkedvezmenyuploadController` | 3 |
| `bizonylattetelController`, `minkeszletimportController` | 2 (ezek `basename()`-mel a bejárást már kivédték, a `.php` kiterjesztést nem) |
| `glsutanvetController` | 1 (a saját javításom is átkerült a közös függvényre) |

**Amihez nem nyúltam, mert nem volt sebezhető:** hat `importController`-beli hely fix, beégetett fájlnévre ír (`reintex.csv`, `tutisportimport.csv`,
`vaterarendeles.csv`, `vateratermek.csv`, `copydepotermek.xml`, `copydepokeszlet.xml`) – oda a beküldött név nem jut el. A `MediatarService::upload()` pedig
eleve saját, szigorúbb ellenőrzést futtat.

**Mellékhatás, ami inkább javulás:** eddig hiányzó vagy hibás fájl esetén ezek a végpontok fatal errorral szálltak el (a `IOFactory::identify()` egy nem
létező útvonalra). Mostantól „Hiányzó vagy nem elfogadott típusú fájl." üzenettel térnek vissza.

**Kipróbálva:** a GLS, a min. készlet és a bank tranzakció importja érvényes xlsx-szel változatlanul működik (24 változat min. készlete frissült, a GLS
duplikátumszűrő is helyesen 0 újat csinált), `.php` néven feltöltve mindhárom elutasítja. A `storage/`-ban nem maradt szemétfájl, az adatok nem mozdultak.

**Ellenőrzés kódszinten:** `grep -rn "storagePath(\$_FILES" Controllers/ Services/` – nincs találat.

---

## Deploy után jött elő: a dokumentum fül „+" gombja fatalra futott (2026-08-14)

```
MappingException: The target-entity Entities\termek cannot be found in 'Entities\TermekDok#termek'
```

**Nem a mostani munkából származó hiba, de a deployom váltotta ki.** Három entitásban a kapcsolat célosztálya **kisbetűvel** volt beírva:

| fájl | volt | lett |
|------|------|------|
| `Entities/TermekDok.php` | `targetEntity="termek"` | `targetEntity="Termek"` |
| `Entities/PartnerDok.php` | `targetEntity="partner"` | `targetEntity="Partner"` |
| `Entities/BizonylatDok.php` | `targetEntity="bizonylatfej"` | `targetEntity="Bizonylatfej"` |

**Miért csak most.** A `Entities\termek` osztály nem létezik – a fájl `Termek.php`. A fejlesztői gépen (macOS) a fájlrendszer nem érzékeny a kis- és
nagybetűre, ezért a Composer autoloader megtalálja; **a szerveren (Linux) nem.** A Doctrine ezt a `validateRuntimeMetadata()`-ban ellenőrzi, ami **csak akkor
fut le, ha a metaadat nincs a cache-ben**. A mostani entitás-változások (új `GLSUtanvet`, a `minkeszlet` átnevezés) miatt a metaadat-cache újraépült, és ekkor
robbant a évek óta ott lapuló hiba.

**Nincs DDL-változás** (`./updatesql.sh` → „Nothing to update"), csak a leképezés neve javult; proxy újragenerálva.

**Átnéztem az összes entitást ugyanerre a hibára:** a `targetEntity` és a `repositoryClass` értékek most mind pontosan egyeznek a valódi osztálynevekkel
(0 eltérés). Az `orm:validate-schema` a javítás előtt és után is ugyanazt a 11, **korábbról meglévő** figyelmeztetést adja (Termekcsoport, Leltarfej, Partner
– ezek nem futásidejű hibák), a három Dok entitás egyikben sem szerepel.

### Ugyanez a csapda máshol – kérésedre kitakarítva

Az osztálynév eltért a fájlnévtől kis/nagybetűben **12 helyen**. Ezek **ma működnek**, mert mindenhol a fájlnévvel egyező alakkal hivatkozunk rájuk, tehát a
`file_exists()` / az autoloader megtalálja a fájlt – a PHP osztálynevek pedig kis/nagybetűre érzéketlenek. De ha bárki egyszer a „szép" alakot írja le
(`\Controllers\BevetfejController`), az Linuxon azonnal fatal. Az osztálynevet igazítottam a fájlnévhez (a repó mind a 201 controller-fájlja kisbetűvel kezdődik):

| hely | volt → lett |
|------|-------------|
| `Controllers/` – 8 bizonylat-controller | `class BevetfejController` → `class bevetfejController`; ugyanígy `autokiserofej`, `keziszamlafej`, `kivetfej`, `koltsegszamlafej`, `leltarhianyfej`, `leltartobbletfej`, `szallitofej` |
| `mkwhelpers/` – 4 egyedi DQL függvény | `class IfElse` → `class ifelse`; `Now` → `now`, `Rand` → `rand`, `Year` → `year` (a `bootstrap.php` `mkwhelpers\ifelse` / `\now` / `\rand` / `\year` néven regisztrálja őket) |

**Kipróbálva:** a nyolc controller mind a 16 végpontja (lista + listatörzs) és a karbantartóik 200-at adnak; az öt egyedi DQL függvény (`YEAR`, `NOW`, `IF`,
`RAND`, `CURDATE`) valódi lekérdezésben lefut. Az egész kódbázisban (`Entities`, `Controllers`, `Services`, `Traits`, `Listeners`, `mkwhelpers`, `mkw`) most
**0 fájlnév/osztálynév eltérés** van.

---

## Összegzés (2026-08-14)

**8 feladat kész, 1 vár rád** (a fizmód/storno pénzmozgás – ott kérted, hogy előbb csak a terv készüljön el: `docs/storno-penzmozgas.md`).

Minden feladat külön commitban van. Böngészőben (galad, `mkw.test`) végig tudtam tesztelni, be voltál jelentkezve SYSADMIN-ként.

### Deploy előtt olvasd el

- **`minboltikeszlet` → `minkeszlet`:** a szervereken **előbb egy admin kérés (runonce), utána `./updateschema.sh`**. Fordított sorrendben a
  `termek.minboltikeszlet` / `termekvaltozat.minboltikeszlet` tartalma elvész. Részletek: `docs/minkeszlet-atnevezes.md`.
- **Új tábla:** `glsutanvet` – kell rá `./updateschema.sh` minden telepítésen.
- **Új config kulcs:** `path.dokumentum` (van alapértelmezése: `dokumentum`, tehát beállítás nélkül is működik).
- **Új runonce blokk:** 0122 – a két GLS menüpont.

### Amit a fejlesztői adatbázisban hagytam

Teszteléshez hoztam létre őket, hogy ki tudd próbálni a funkciókat. Ha nem kellenek, rontsd le / töröld:

| bizonylat / adat | miért | mihez tartozik |
|---|---|---|
| `SZMR2026/000001` szállítói megrendelés (TESZT SZALLITO) | a „Mir" export gomb kipróbálása | Mir export |
| `MR2026/000001` megrendelés + `SZ2026/000003` számla | a fuvarlevélszám-párosítás és a „pénzt mozgató leszármazott" ág bizonyítása | GLS import |
| `MR2026/000002` megrendelés | a név+összeg+cím párosítás bizonyítása | GLS import |
| 6 sor a `glsutanvet` táblában | a két mintafájl importja | GLS import |

A `SZ2026/000003` miatt jelenik meg a főoldalon az „1 db számla nincs beküldve a NAV-nak!" figyelmeztetés – ez a teszt számla, nem valódi hiány.

Minden más tesztadatot (min. készlet sorok, dokumentum rekordok, feltöltött fájlok, a lerontott `BO2026/000004`) visszaállítottam az eredeti állapotába.
 