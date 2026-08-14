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
  > **Egy fejlécsorba 9 méret fér (E–M)**, mert az N–R oszlop (egyéb / TOT PCS / PRICE / TOTAL / DELIVERY DATE) a Mir
  > űrlapjának fix része. Ha egy termékfának ennél több mérete van a bizonylaton, a sorrendben hátrébb lévők a régi
  > „egyéb" (N) oszlopba mennek, a méret pedig a névhez ragad – ugyanaz a viselkedés, mint eddig a skálába nem illő
  > méreteknél. A `SZMR2026/000001`-en a BŐRRUHA-nál pont ez történik (54-es és fölötte).
  > **Egy régi hibát javítani kellett hozzá:** a csoportnevet adó `getCsoportNev()` a legmélyebb kitöltött kategóriát
  > kereste, de a ki nem töltött `termekfa2/3` mező ezen az adatbázison **a gyökérre mutat, nem üres** – ezért eddig
  > minden termék a „Termék kategóriák" csoportba esett (a törzsben is így jelent meg a csoportfejléc). Mostantól a
  > szülő nélküli (gyökér) kategória nem számít kitöltöttnek.
  > **Kipróbálva:** `SZMR2026/000009` → 3 fejlécsor (BŐRKABÁT S–6XL, TEXTIL KABÁT S–5XL, KEVLAR JEANS 29–42), a
  > mennyiségek a saját skálájuk oszlopaiba kerülnek; `SZMR2026/000001` → 3 fejlécsor és a BŐRRUHA túlcsordulása az N
  > oszlopba. A rendezés bizonyítottan a `sorrend` szerint megy (S=9600 … 6XL=10400, 29=2800 … 42=4200).

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

## Amit menet közben találtam – ezt nézd meg

**A Beállítások képernyő OK gombja kitörli a nem látszó fülek beállításait.** Amikor a beállítás mentését próbáltam, a
mentés **kinullázta a 6 Stripe paramétert** (köztük egy `sk_live_…` kulcsot!), és felvett egy `backorderstock=0` sort.
Ez **nem az én változtatásom hibája**: a `setup.tpl`-ben a Stripe blokk `{if ($setup.stripe)}` mögött van, a
`setupController::save()` viszont **feltétel nélkül** kiírja a Stripe mezőket a requestből – ami nincs a formon, az üres
stringként mentődik. Tehát minden olyan telepítésen, ahol a `setup.ini` kapcsolója nem egyezik a DB tartalmával, egy
ártatlan „OK" adatot veszít. Ezen a fejlesztői DB-n a mentés előtti állapotot **visszaállítottam** (a Stripe kulcsokat
és a `backorderstock` sort is), ellenőrizve, hogy a `parameterek` tábla a mentés előtti tartalmú.
Javaslat: a mentésben minden fülhöz ugyanaz a `{if}` feltétel kerüljön, mint a sablonban (vagy csak azt írja, ami
tényleg megérkezett a requestben). Ha kéred, megcsinálom – nem nyúltam hozzá, mert ez a mostani feladatokon kívül van.

**Az „Utánvét bankszámla" beállítást a teszt után kivettem** ebből a DB-ből, mert találomra választottam számlát.
A GLS-t használó telepítésen neked kell beállítanod, különben a képzett bankbizonylat a számla bankszámláját örökli.

## Amit érdemes még átgondolni

- **Mir export, 9 méret fölött:** most a `sorrend` szerinti első 9 méret kapja az oszlopot, a többi az N oszlopba megy.
  Lehetne inkább a legtöbb darabot vivő 9 méret, vagy a termékfát „meretsor" szerint tovább bontani – szólj, ha nem jó
  a mostani.
- **Mir export csoportneve:** a legmélyebb, gyökértől különböző kategória. Ha inkább mindig a `termekfa1`-et (a
  főkategóriát) szeretnéd fejlécsornak, az egy sor.
- **Minimum készlet nyomtatási kép:** ha kell külön „Nyomtat" gomb a szűrőképernyőre, lásd fent.
