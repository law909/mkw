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
- a projekt gyökérben van két GLS_*.xlsx, írj egy importot ami importálja ezeket az utánvétes kifizetéseket, csak azokat a sorokat kell importálni, amelyikek Q
  oszlopa (Beszedett utánvét összege (HUF)) nem nulla. csinálj nekik egy ideiglenes táblát bank tranzakció import mintájára, importkor próbáld meg kitalálni,
  melyik bizonylathoz tartozhat a befizetés:
  fuvarlevélszám, aztán név összeg és cím alapján. Ha olyan bizonylatot találsz ami nem mozgat pénzt akkor keresd meg a belőle készült pénzt mozgató
  bizonylatot. Az importált tételekhez lehessen bizonylatszámot megadni egy karbantartóban bank tranzakcióhoz hasonlóan.
- a projekt gyökérben van egy "Mir Order.xls", a szállítói megrendeléseknek csinálj egy gombot amivel ilyen formátumban ki lehet exportálni őket. A gomb legyen
  ott minden száll.megrendelés sorban, azt exportálja amelyik sorban van
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
- vegyél fel egy beállítást config.ini-be: path.dokumentum amit path.mediatar-on vagy path.ckfinder-en belül kell értelmezni. partner és termék dokumentumok
  fülre fentre tegyél egy új gombot: "Azonnali feltöltés", ezt megnyomva lehessen feltölteni egy fájlt a konfigban megadott mappába és szülesseb belőle egy
  dokumentum rekord. a rekord jelenjen is meg a karbantartón. figyelj rá hogy új terméknél/partnernél a dokumentum bekerül a mappába de a rekord nem mentődik
  csak a karbantartó mentésekor.
- 