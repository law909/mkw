# Feladatok – 2026-08-14

Mind az öt feladat kész, feladatonként külön commitban. **Böngészőben végig kipróbálva** (be
voltál lépve SYSADMIN-ként, nem kellett belépned): az aktív telepítés a `superzoneb2b`
`config.ini`-je, ami a `billy_mugenrace` fejlesztői adatbázisra megy. Minden tesztadatot
visszaállítottam, lásd lent.

- [x] **KÉSZ** Minimum készlet alatt listában legyen termékfa szűrő
  > A készlet kimutatáséval azonos jstree szűrő (`comp_termekfa.tpl` + `mkwcomp.termekfaFilter`), a kijelölt fák
  > **karkod-előtagja** megy a `termekfa1/2/3karkod` mezőkre, mind a változatos, mind a változat nélküli ágra. A riport
  > fejlécében kiírom a kijelölt kategóriák nevét.
  > **Kipróbálva:** a fa betölt (169 csomópont), a kijelölés a rejtett `fafilter` mezőbe kerül, mindhárom gomb elviszi.
  > `fafilter=36,38` → 584 sor; szűrő nélkül 3376 sor / 6945 hiány. Olyan kategóriára, ahol egy változatnak sincs
  > minimuma, üres a riport – SQL-lel ellenőriztem, hogy tényleg 0 sor tartozik oda, tehát nem a szűrő téved.

- [x] **KÉSZ** Minimum készlet alatt listában legyen egy input: Készlet + pipa
  > A szűrőn: `Készlet` szám input és mellette „a minimum készlet helyett ezt figyelje" pipa. Bekapcsolva a beírt érték a
  > küszöb **minden soron**, a raktáranként feloldott minimum helyett; a „Min. készlet" oszlopban és a hiányban is ez
  > szerepel, a riport fejléce külön kiírja.
  > **Döntés, amit érdemes tudni:** ilyenkor a „csak akinek van beállított minimuma" szűrés (`minkeszlet > 0`) sem
  > érvényes – különben a pipa nem tudna olyan terméket mutatni, aminek nincs minimuma, pedig pont az a kérdés, hogy
  > „mi van 5 darab alatt". Mellékhatásként **0-t beírva pont a negatív készletűek jönnek** (56 sor a próbán).
  > **Kipróbálva:** 5 + pipa → 1956 sor, mindegyiken 5 a min. oszlop; 0 + pipa → csak negatív készlet; pipa nélkül a
  > számok betűre ugyanazok, mint korábban (3376 / 6945), tehát nincs regresszió.

- [x] **KÉSZ** Minimum készlet alatt listának legyen nyomtatási képe
  > Az „OK" gomb eddig is egy `rep_`-sablonos, nyomtatásra való lapot nyitott új fülön (ez a kimutatások bevett
  > „nyomtatási képe" a programban), csak épp nem lehetett belőle egy kattintással nyomtatni. Ezt egészítettem ki:
  > **Nyomtatás gomb** a lap tetején (nyomtatáskor maga a gomb eltűnik) és **nyomtatási dátum** a táblázat alján, a
  > bizonylattétel lista mintájára.
  > **Ha nem ezt akartad**, hanem külön „Nyomtat" gombot a szűrőképernyőre (mint a bizonylattétel listán, ahol az „OK"
  > a képernyőre dolgozik), szólj – az 5 perc, csak akkor két gomb csinálná ugyanazt.
  > **Mellékesen kijavítva:** a hiány-oszlopnak `redtext` osztálya volt, ami a `rep.css`-ben **nem is létezett** – most
  > már tényleg piros a képernyőn, nyomtatásban meg fekete (mint a `lejart`).

- [x] **KÉSZ** Mirorderexport: dinamikus méret oszlopok
  > A fejlécben már nem a két beégetett méretskála áll: **termékfánként egy sor**, benne a termékfa termékeinek az adott
  > bizonylaton szereplő méretei, a **`meret` entitás `sorrend`** mezője szerint rendezve. A méretsorok a D oszlopban a
  > termékfa nevét viszik, a méretek az E oszloptól. Ettől az oszlopcímke-sor és az első adatsor lefelé csúszik, a
  > sorok a **saját termékfájuk** oszlopkiosztását használják.
  > **A méretek száma nincs korlátozva** (2. kör): a méret-mátrix addig tart, ameddig a legtöbb méretet vivő termékfa
  > kívánja, és a mögötte álló oszlopok (egyéb / TOT PCS / PRICE / TOTAL / DELIVERY DATE) annyival csúsznak jobbra.
  > **9 méretig a minta űrlapjának kiosztása marad** (méretek E–M, egyéb N, TOT PCS O, PRICE P, TOTAL Q, DELIVERY DATE
  > R) – így a szokásos bizonylatokon a lap ugyanúgy néz ki, mint eddig. Az „egyéb" oszlop megmaradt, de már csak a
  > **méret nélküli** tételeket viszi (pl. szállítási költség sor), a méretcímke a névhez ragadva.
  > **Nevek a bizonylat nyelvén** (2. kör): a terméknév és a termékfa neve a `bizonylatnyelv` szerint megy ki. A
  > terméknévnél a sorrend: a tételen tárolt fordítás (`termeknev_l1`) → a termék aktuális fordítása (`termek.nev_l1`)
  > → a tétel eredeti neve. A második lépcső azért kell, mert a régi tételeken a `termeknev_l1` üres, a terméken viszont
  > ott az angol név. A szín és a méret a változat értéke, azt nem fordítjuk.
  > **Egy régi hibát javítani kellett hozzá:** a csoportnevet adó `getCsoportNev()` a legmélyebb kitöltött kategóriát
  > kereste, de a ki nem töltött `termekfa2/3` mező ezen az adatbázison **a gyökérre mutat, nem üres** – ezért eddig
  > minden termék a „Termék kategóriák" csoportba esett (a törzsben is így jelent meg a csoportfejléc). Mostantól a
  > szülő nélküli (gyökér) kategória nem számít kitöltöttnek.
  > **Kipróbálva:** `SZMR2026/000009` (en_us) → 3 fejlécsor angol kategórianévvel (LEATHER JACKET S–6XL, TEXTILE JACKET
  > S–5XL, KEVLAR JEANS 29–42), angol terméknevekkel, és **a minta űrlapjának eredeti oszlopkiosztásával** (TOT PCS az
  > O-ban). `SZMR2026/000001` (en_us) → a LEATHER SUIT-nak 14 mérete van (34–62), semmi nem csordul túl, az összegző
  > oszlopok a T–W-be csúsznak. `SZMR2026/000008` (hu_hu) → magyar nevek maradnak (GERINCVÉDŐ, ZANDONA).
  > `SZMR2025/000005` → a méret nélküli szállítási költség sor az „egyéb" oszlopba kerül, `(?: 1)` a névben.
  > A rendezés bizonyítottan a `sorrend` szerint megy (S=9600 … 6XL=10400, 29=2800 … 42=4200).

- [x] **KÉSZ** GLS utánvét: bank bizonylatok a párosított tételekből
  > **Beállítás:** Beállítások → Alapértelmezések fül → **„Utánvét bankszámla"** (`\mkw\consts::UtanvetBankszamla`), a
  > bankszámla törzsből választható. Ez lesz a képzett bankbizonylat saját számlája; ha üresen hagyod, a hivatkozott
  > számla bankszámlája marad (az viszont sokszor üres).
  > **Felület:** a GLS utánvét lista fejlécében az „Import" mellett **Csoportos művelet [Bank bizonylatok
  > létrehozása] + Futtat + Párosít**. A Párosít maradt, ami volt: a bizonylatszám nélküli tételeken újra lefuttatja a
  > keresést (ez az „a betöltött tételek és a számlák párosítása").
  > **A képzés** a `banktranzakcioController::generateBankbizonylat()` mintája, ezekkel a különbségekkel: mindig
  > **bevétel** (`irany = 1`, az utánvét befelé jövő pénz), a jogcím az „Automatikus bankbizonylat jogcíme" beállításból
  > jön, a tétel dátuma a GLS státusz dátuma, az `erbizonylatszam` a csomagszám. Csak a **párosított, nem inaktív, még
  > bankbizonylat nélküli** tételek jönnek szóba – kipipált sorokkal csak azok, pipa nélkül mind.
  > **Új mező:** `glsutanvet.bankbizonylatkesz` (a `banktranzakcio` mintájára) – ez akadályozza meg a dupla könyvelést,
  > és a kész sor a listán már nem szerkeszthető, „Bankbizonylat kész" felirattal. A `./updateschema.sh` a fejlesztői
  > DB-n lefutott, **a többi telepítésen ki kell adni** (`ALTER TABLE glsutanvet ADD bankbizonylatkesz TINYINT(1) NOT NULL`).
  > **Kipróbálva** egy ideiglenes GLS tétellel egy valódi számlára (`SZ2026/001190`, 159 240 Ft): 1 bankbizonylat
  > keletkezett a beállított bankszámlával és a számla partnerével, a tétele `irany = 1`, bruttó 159 240, jogcím „Számla
  > kiegyenlítés"; a folyószámlára egy sor került `irany = -1`-gyel (a listener fordítja). Újrafuttatás: „0 bankbizonylat
  > készült." Nem létező bizonylatszámmal: „0 bankbizonylat készült, 1 tétel kimaradt". **Utána mindent töröltem**
  > (bankbizonylat fej + tétel + folyószámla sor + a teszt GLS tétel), a bizonylatszámozásban nem maradt lyuk.

---

- [x] **KÉSZ (utólag kért)** A Beállítások mentés hibája: az OK gomb kitörölte a nem látszó fülek beállításait
  > **A hiba:** a `setup.tpl` sok blokkja `{if}` mögött van (`setup.ini` kapcsoló, `main.theme`, vagy adatfüggő
  > feltétel), a `setupController::save()` viszont **feltétel nélkül** írta ki ezeket a paramétereket. Ami nincs a
  > formon, az üres stringként érkezik, a kikapcsolt pipa meg hamisként – így egy ártatlan „OK" némán kitörölte annak a
  > fülnek a beállításait, amelyik ezen a telepítésen ki van kapcsolva. Nálam ez a **6 Stripe paramétert** vitte el
  > (köztük egy `sk_live_…` kulcsot); visszaállítottam.
  > **A javítás** az UNAS fülön már meglévő minta általánosítása: minden feltételes blokkba került egy jelzőmező
  > (`<input name="…van" type="hidden" value="1">`), a controllerben pedig egy táblázat
  > (`setupController::CONDITIONAL_BLOCKS`) mondja meg, melyik jelzőhöz melyik paraméterek tartoznak. A `setObj()`
  > egyetlen ponton kihagyja azt a paramétert, amelyiknek a blokkja nem renderelődött. Az UNAS-ra írt `setUnasObj()`
  > ezzel feleslegessé vált, kikerült.
  > **15 blokk** van védve: ársávok, automatikus pénztárbizonylat, darshan fizetési módok, jóga fül, teljesítmény
  > kezdő éve, spanyol mezők, változat-típusok, változat sorrend, multishop fül, MPTNGY fül, gyártói import fül,
  > mugenrace fül, Barion, Stripe, UNAS.
  > **Új feltételes blokknál** két dolog kell: a jelzőmező a blokkba, és a paraméterei a táblázatba.
  > **Kipróbálva** a superzoneb2b/mugenrace fejlesztői DB-n: mentés után a `parameterek` tábla **betűre ugyanaz**
  > maradt (a Stripe kulcsok is), a renderelt fülek mezői viszont továbbra is mentődnek (a multishop fül
  > `kezdotermekkategoria5` mezőjébe írt teszt érték elmentődött). A tesztadatot visszaállítottam.
  > **Két apróság, ami mentéskor felvesz egy-egy új sort** (`backorderstock=0`, `utanvetbankszamla=''`): ezek
  > feltétel nélküli mezők, csak eddig nem volt soruk a táblában – az értékük az alapértelmezés, tehát nem változtatnak
  > semmin.

---

## Megjegyzések

**Az „Utánvét bankszámla" beállítást a teszt után kivettem** ebből a DB-ből, mert találomra választottam számlát.
A GLS-t használó telepítésen neked kell beállítanod, különben a képzett bankbizonylat a számla bankszámláját örökli.

## Amit érdemes még átgondolni

- **Mir export csoportneve:** a legmélyebb, gyökértől különböző kategória. Ha inkább mindig a `termekfa1`-et (a
  főkategóriát) szeretnéd fejlécsornak, az egy sor.
- **Mir export, 9 méretnél kevesebb:** a lap ilyenkor is 9 méretoszlopot tart fenn, hogy a minta űrlapjának kiosztása
  (TOT PCS az O oszlopban) megmaradjon. Ha inkább mindig szorosan a méretek után jöjjenek az összegző oszlopok, a
  `MINMERETOSZLOP` konstanst kell 0-ra venni.
- **Minimum készlet nyomtatási kép:** ha kell külön „Nyomtat" gomb a szűrőképernyőre, lásd fent.
