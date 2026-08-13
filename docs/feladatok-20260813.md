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
- bizonylaton fizetési mód váltáskor a program rákérdez, hogy mi legyen e létező pénztár és bank bizonylatokkal. Rontáskor és stornokor rontsa le. lehet hogy ez
  már le van fejlesztve, ellenőrizd
- bizonylattetelkarbformon a termék autocomplete mellett legyen 2 gomb: 1. "új", új fülön nyissa meg az új termék karbantartót; 2. "adatlap" ha van máár
  kiválasztva termék, nyissa meg a termék karbantartóját
- a projekt gyökérben van két GLS_*.xlsx, írj egy importot ami importálja ezeket az utánvétes kifizetéseket, csak azokat a sorokat kell importálni, amelyikek Q
  oszlopa (Beszedett utánvét összege (HUF)) nem nulla. csinálj nekik egy ideiglenes táblát bank tranzakció import mintájára, importkor próbáld meg kitalálni,
  melyik bizonylathoz tartozhat a befizetés:
  fuvarlevélszám, aztán név összeg és cím alapján. Ha olyan bizonylatot találsz ami nem mozgat pénzt akkor keresd meg a belőle készült pénzt mozgató
  bizonylatot. Az importált tételekhez lehessen bizonylatszámot megadni egy karbantartóban bank tranzakcióhoz hasonlóan.
- a projekt gyökérben van egy "Mir Order.xls", a szállítói megrendeléseknek csinálj egy gombot amivel ilyen formátumban ki lehet exportálni őket. A gomb legyen
  ott minden száll.megrendelés sorban, azt exportálja amelyik sorban van
- termék és partner karbantartón a dokumentumok fülön a dokumentum input mellett a ... gomb mellett van egy O gomb ami legyen gomb külsejű és nyissa meg a
  dokumentumot, ne pedig letöltse. A gomb captionje legyen "Megnyit"
- bolti eladásokat nem lehet lerontani, megnyomom a gombot és nem törénik meg a rontás. galad deployon ki tudod próbálni
- vegyél fel egy beállítást config.ini-be: path.dokumentum amit path.mediatar-on vagy path.ckfinder-en belül kell értelmezni. partner és termék dokumentumok
  fülre fentre tegyél egy új gombot: "Azonnali feltöltés", ezt megnyomva lehessen feltölteni egy fájlt a konfigban megadott mappába és szülesseb belőle egy
  dokumentum rekord. a rekord jelenjen is meg a karbantartón. figyelj rá hogy új terméknél/partnernél a dokumentum bekerül a mappába de a rekord nem mentődik
  csak a karbantartó mentésekor.
- 