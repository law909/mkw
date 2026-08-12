# Raktárankénti minimum készlet

Állapot: **lekódolva** (1-5. fázis). A böngészős E2E még hátra van, lásd a *Megvalósítás* fejezetet a terv végén: ott van az összes eltérés is, amit a kódolás
közben derült okok indokoltak.

Felmérés a mai `minboltikeszlet` mezők és a `\mkw\consts::NoMinKeszlet` /
`NoMinKeszletTermekkat` kapcsolók használatáról, valamint terv a raktáranként, termékenként/változatonként megadható minimum készletre.

## Kontextus

A `minboltikeszlet` ma **egyetlen globális szám** termékenként és változatonként: az a mennyiség, aminek a polcon kell maradnia, ezért nem eladható. A cég
viszont több raktárt (boltot) visz, és a polcon tartandó tartalék boltonként más — a központi raktárban nulla, egy kis boltban 1-2 darab. Ma erre nincs mód:
egyetlen érték vonatkozik az összes raktárra.

> A `minboltikeszlet` → `minkeszlet` **átnevezés egyelőre nem része a tervnek** — külön,
> későbbi lépés, lásd a Függeléket. Ebben a tervben minden a mai néven marad, beleértve az
> új táblákat és kérésparamétereket is.

---

# 1. rész — Felmérés

## 1.1 Hol tárolódik ma

| Hely                                  | Definíció                                                                                  |
|---------------------------------------|--------------------------------------------------------------------------------------------|
| `Entities/Termek.php:404-405`         | `@ORM\Column(type="decimal",precision=14,scale=2,nullable=true) private $minboltikeszlet;` |
| `Entities/TermekValtozat.php:193-194` | ugyanaz                                                                                    |

Oszlopnév implicit `minboltikeszlet` mindkét táblában, DB alapérték NULL. A Doctrine
`decimal` **stringként** hidratál — ezért látszik `"0.00"` PHP-ben igaznak, és ezért
`* 1` a truthiness-teszt a meglévő kódban.

**A feloldási szabály** — `TermekValtozat::calcMinboltikeszlet()`
(`Entities/TermekValtozat.php:938-945`): a **termék** nem-nulla értéke **felülírja** a változatét; különben a változat saját értéke (ami lehet `null`).

## 1.2 Ki írja (mindössze 2 hely)

Mindkettő a `termekController::setFields()`-ben, más-más konverterrel:

- `Controllers/termekController.php:288` — termék szint, `getFloatRequestParam('minboltikeszlet')`. **Mindig ír**, alapértéke `0.0` → egy olyan form POST,
  amiben nincs benne a mező, csendben nullázza. (Ezért 0 tartósan a darshan témán, aminek saját `termekkarbform.tpl`-je van.)
- `Controllers/termekController.php:764-771` — változat szint, a rejtett
  `valtozatminboltikeszletid[]` tömb vezérli, `getNumRequestParam('valtozatminboltikeszlet_'.$id)`.

Nincs importer, nincs A2A/UNAS szinkron, nincs CSV/Excel betöltés, és **nem** megy át a
`setEntityFieldsFromRequest()`-en. Az admin űrlap az egyetlen forrás.

## 1.3 Ki olvassa

**A „szabad készlet" képlet 13 helyen bemásolva:**
`getKeszlet() − getFoglaltMennyiseg() − calcMinboltikeszlet()`

| Fájl:sor                                                  | Kontextus                                                               |
|-----------------------------------------------------------|-------------------------------------------------------------------------|
| `Services/BackorderService.php:30`                        | `szabadKeszlet()` változat ág                                           |
| `Services/BackorderService.php:32`                        | termék ág — **nyers** `getMinboltikeszlet()`, nem `calc…` (aszimmetria) |
| `Entities/Termek.php:702`                                 | `toA2a()` → `stock`                                                     |
| `Controllers/mainController.php:337`                      | webshop színenkénti aggregálás                                          |
| `Controllers/mainController.php:418`                      | `termekm()` — **nincs** `max(…,0)`                                      |
| `Controllers/termekController.php:1080`, `:1128`          | méretválasztó AJAX — **nincs** `max(…,0)`                               |
| `Controllers/a2aController.php:134`, `:163`               | stock API                                                               |
| `Controllers/a2aController.php:715`                       | rendelésfelvétel: „not enough stock"                                    |
| `Controllers/exportController.php:1410`, `:1577`, `:1634` | feed / XLSX exportok                                                    |

**Nyers SQL** — `Controllers/keszletlistaController.php:146-162`, csak ha a
`minkeszletszamit` bejelölve. A `calcMinboltikeszlet()` SQL-be írt mása:
`IF((t.minboltikeszlet IS NOT NULL) AND (t.minboltikeszlet<>0), t.minboltikeszlet, _xx.minboltikeszlet)`. Csapda: a korrelált alkérdés **saját**
`LEFT JOIN termek t`-je árnyékolja a külső `t`-t, és ha nincs bizonylattétel, az egész `keszlet` oszlop NULL lesz.

**Tömb-projekciók** (nyers termékszintű getter, fallback nélkül): `Entities/Termek.php:794`
(`toTermekLista`), `:944` (`toKiemeltLista`), `:1002` (`toTermekLap`). Ellenőrizve: **egyik storefront sablon sem olvassa** ezt a kulcsot, és egyik fogyasztó
sem JSON-ozza.

## 1.4 Sablonok és JS

- `tpl/admin/default/termekkarbform.tpl:23` — fülcím, **a `{if ($setup.termekvaltozat)}` ágon belül** (`:21-25`)
- `:174-175` — termékszintű input az Általános fülön (`name="minboltikeszlet"`)
- `:472-481` — a változatonkénti rács, szintén a változat-`if`-en belül (a `{/if}` a `:482`)
- `tpl/admin/default/termeklista_tbody_tr.tpl:51-52` — megjelenítés a lista lenyitott sorában
- `$egyed.minboltikeszlet` / `$valtozat.minboltikeszlet` **nincs explicit beállítva** — a reflexiós `getEntityFieldsArray()`
  (`mkwhelpers/Controller.php:135-154`) állítja elő a mezőnévből
- **JS: nulla találat** az egész repóban
- `tpl/admin/darshan/termekkarbform.tpl` és `termeklista_tbody_tr.tpl` önálló másolatok, amikben **nincs** `minboltikeszlet` — a mátrix ott nem fog megjelenni

## 1.5 `NoMinKeszlet` és `NoMinKeszletTermekkat`

Definíció: `mkw/consts.php:505-506` — `'nominkeszlet'` és `'nominkeszlettermekkat'`. **Nem setup.ini kulcsok és nem entitásmezők**: a `parameterek` tábla
sorazonosítói (`Entities/Parameterek.php`), `\mkw\store::getParameter()`-rel olvasva. Egyik `*.ini`-ben sincs.

| Szerep                              | Hely                                                                     |
|-------------------------------------|--------------------------------------------------------------------------|
| Setup betöltés                      | `Controllers/setupController.php:787-802`                                |
| Setup mentés                        | `:2148-2155` (`nominkeszlet` `'0'/'1'`, a kategória egy **TermekFa id**) |
| Setup UI                            | `tpl/admin/default/setup.tpl:796-805` („Min. bolti készlet NEM számít")  |
| Kategóriaválasztó JS                | `js/admin/default/setupform.js:117-147` (jsTree)                         |
| Admin figyelmeztető sáv             | `tpl/admin/base.tpl:37-39`, adat: `mkw/generalDataLoader.php:62`         |
| **Egyetlen funkcionális fogyasztó** | `Services/BackorderService.php:23`, `:54-57`, `:175-178`                 |

Jelentés: ha `nominkeszlet` be van kapcsolva **és** a termék a megadott kategóriafában van, akkor a backorder-szétbontásnál a minimum **nem** kerül levonásra.
Semmilyen más készletút nem veszi figyelembe — a webshop, az exportok és az A2A mindig levonja. Ezért van a figyelmeztető sáv.

A kategóriaegyezés `Entities/Termek.php:3444-3448` — `str_starts_with` a három
`termekfaNkarkod` mezőn, tehát **részfa-egyezés**. A `BackorderService` ezért `id → karkod`
váltást csinál (`find(...)?->getKarkod()`).

> **Létező hiba:** ha a kategória `0` vagy törölt, a karkod `null`, és
> `str_starts_with($x, '')` **minden termékre igaz** → a minimum globálisan kikapcsol,
> plusz PHP 8.1 deprecation. Külön javítás, lásd 3.5.

## 1.6 Amire építünk: a raktár/készlet architektúra

- **Nincs tárolt raktárankénti készletsor.** A készlet mindig menet közben áll össze:
  `SUM(bt.mennyiseg * bt.irany)` a `bizonylattetel` + `bizonylatfej` fölött, a raktár dimenzió a `bizonylatfej.raktar_id`. Az `Entities/Keszlet.php` csak
  újraépíthető FIFO-tétel gyorsítótár (`FifoRepository::clearData()` üríti), készletellenőrzésre soha nem olvassuk.
- `Termek::getKeszlet($datum, $raktarid, $nonegativ)` és
  `TermekValtozat::getKeszlet($datum, $raktarid, $nonegativ)` **már raktárparaméteres**.
- `Termek::getFoglaltMennyiseg($kivevebiz, $datum, $raktarid)` szintén — de
  `TermekValtozat::getFoglaltMennyiseg($kivevebiz)` **nem adja ki** a `$datum`/`$raktarid`
  paramétert, holott a privát `calcFoglalasInfo()` fogadja. Ezt szélesíteni kell.
- **Memoizációs csapda** (`Entities/TermekValtozat.php:333-354`): `getKeszlet()` olvasás után **törli** a `$keszletinfo` cache-t, `getMozgasDb()` **nem**. Egy
  `getMozgasDb()` utáni
  `getKeszlet($datum, $raktarid)` a szűretlen cache-elt értéket adja vissza. Ma nem hibás szám (`termekController.php:104-113` mindkettőt paraméter nélkül
  hívja), de a raktárparaméter bevezetésével élessé válik.
- **A storefront nem raktártudatos**: nincs `Webshop` entitás, nincs raktár kulcs egyetlen
  `*.ini`-ben sem, nincs session-raktár. Az alapraktár a `parameterek.raktar` sor. Figyelem: `store::getDefaultRaktarId()` (`mkw/store.php:1263-1271`) felülírja
  a bejelentkezett **admin** dolgozó raktárával — storefronton/exportban **nem használható**.
- Raktárszűrt precedensek: `Controllers/boltieladasController.php:200-213` (POS),
  `Controllers/keszletlistaController.php:52-86`, `Controllers/listaController.php:18-99`,
  `Controllers/termekController.php:1688-1706` + `tpl/admin/default/termekkeszletreszletezo.tpl`.

> **Előfeltétel-hiba:** `RaktarRepository::getAllActive()` az `archiv <> 1` szűrőt használja,
> ami SQL háromértékű logikában **kizárja az `archiv IS NULL` sorokat**. A `raktar.archiv`
> nullable, alapérték nélkül — ahol NULL, ott a metódus üres listát ad. A mátrix ilyenkor
> nulla raktároszlopot rajzolna. Javítandó a UI előtt (3.5).

---

# 2. rész — Célállapot

## 2.1 Döntések

1. A mai két oszlop **változatlanul marad** (név, típus, jelentés): ez a globális, raktárfüggetlen érték. Az átnevezés későbbi, önálló lépés (Függelék).
2. **Két külön új tábla** a raktárankénti értékeknek (termék+raktár, változat+raktár), a meglévő `minboltikeszlet` névhez igazodva.
3. ~~A **precedencia marad**: nem-nulla **termék**érték elnyomja a változatét.~~ **Felülírva 2026-08-12:** a szűkebb beállítás nyer, a **változat** üti a
   terméket – és változatos terméken a termékszintű minimum kötelezően nulla, lásd 2.2.
4. A nem raktárszűrt helyek (webshop, A2A, exportok, backorder) **számszerűen változatlanok**.
5. Admin UI: a meglévő `ValtozatMinBoltiKeszletTab` átalakítása **változat × raktár mátrixszá**. Nincs új MattableController, nincs új listaképernyő.
6. **Egyszeri adatmigráció**: a mai globális értékek bemásolása az **alapraktár**
   (`parameterek.raktar`) celláiba.

## 2.2 A feloldási létra

```
$raktarid megadva:
  1. termekvaltozatminboltikeszlet(termekvaltozat_id=V, raktar_id=R) — ha nem nulla
  2. termekminboltikeszlet(termek_id=T, raktar_id=R)                 — ha nem nulla
$raktarid nélkül, vagy ha 1-2 nem talált:
  3. termekvaltozat.minboltikeszlet                                  — ha nem nulla
  4. termek.minboltikeszlet                                          — ahogy van
```

Raktár-szint üti a globálist, szinten belül a **változat** üti a terméket (a szűkebb beállítás nyer). **`$raktarid === null` esetén a létra a 3→4 lépés, a
globális minimum** — a `BackorderService` szándékosan sosem ad raktárat, tehát mindig ezt látja. Máshol a szabály: ha van raktár, a raktáras érték, ha nincs,
a globális.

**Változatos terméken a termékszintű minimum kötelezően nulla** (2026-08-12): a minimumot csak a változatokhoz lehet megadni. A 2. és 4. lépés így gyakorlatilag
csak a változat nélküli termékeknél él. Ezt két helyen kényszerítjük ki:
`termekController::saveMinBoltiKeszletMatrix()` (a rács termék sora zárolt, mentéskor a globális 0 és a raktáras sorok törlődnek) és
`\Services\MinKeszletExcelService::import()` (a változatos termék sorát nullázza, és figyelmeztetést tesz a hibalistába, ha volt benne érték). Ugyanennek a
service-nek az **exportja** a változatos termék sorát ki sem írja, csak a változatokét. A meglévő adatot a `runonce` 0118-as blokkja rendezi – **csak
superzoneb2b-n**: a termékszintű értéket (globális és raktáras) rámásolja minden változatra, aztán a változatos termékek termékszintjét nullázza.

**Biztonsági tulajdonság:** ahol a mátrixot senki nem tölti ki és a migráció sem írt sort, ott a létra mindig a 3-4. lépésre esik.

**Csapda mindkét implementációban:** a „nem nulla" tesztnek numerikusnak kell lennie (`* 1` PHP-ben, `NULLIF(x, 0)` SQL-ben) — a `"0.00"` string igaz.

## 2.3 Az új séma

`Entities/TermekMinboltikeszlet.php` (tábla `termekminboltikeszlet`) és
`Entities/TermekValtozatMinboltikeszlet.php` (tábla `termekvaltozatminboltikeszlet`). Minta: `Entities/TermekAr.php` és `Entities/Dolgozoparameterek.php`.

| Mező                        | Leképzés                                                                                              |
|-----------------------------|-------------------------------------------------------------------------------------------------------|
| `id`                        | `integer`, AUTO                                                                                       |
| `created` / `lastmod`       | Gedmo Timestampable, `datetime` nullable (mint `TermekAr`)                                            |
| `termek` / `termekvaltozat` | `ManyToOne`, `nullable=false`, `onDelete="cascade"`                                                   |
| `raktar`                    | `ManyToOne`, `nullable=false`, `onDelete="cascade"`                                                   |
| `minboltikeszlet`           | `decimal(14,2)`, `nullable=true` — azonos a globális oszloppal                                        |
| kulcs                       | valódi `@ORM\UniqueConstraint` a `(termek_id, raktar_id)` ill. `(termekvaltozat_id, raktar_id)` páron |

Az oszlop- és táblanevek szándékosan a **mai** nevet viszik tovább: amíg az átnevezés nem történt meg, egyetlen `grep minboltikeszlet` megtalálja az egész
funkciót. Az átnevezéskor a négy oszlop és a két táblanév együtt mozdul (Függelék).

`raktar_id` **NOT NULL** — nincs „globális sor" ebben a táblában, ezért a unique constraint tényleg működik (a MySQL a NULL-okat különbözőnek veszi, itt ez nem
jön elő). Ezért nem kell a `TermekAr` nem-egyedi indexes kerülőútja.

**Inverz `OneToMany` kollekciót ne vegyünk fel** sem a `Termek`, sem a `TermekValtozat`
entitásra: minden olvasás a kötegelt service-en megy, a törlést a DB-szintű `cascade`
intézi, az íráshoz pedig a tulajdonos oldal elég. Egy lusta kollekció a `Termek`-en csak meghívná az N+1-et sablonból. Precedens inverz nélküli gyerekentitásra:
`TermekAr::$arsav`.

---

# 3. rész — Fázisok

Az 1-3. fázis **nem változtat egyetlen számot sem**; a funkció a 4. fázisban válik láthatóvá.

## 3.1 Fázis 1 — Központi feloldó és készlethelper

**`Services\MinBoltiKeszletService`** — statikus metódusok, kérés-szintű statikus cache-sel. Minta: `Services/DolgozoParameterService.php`
(`private static $cache`, kötegelt `load()`,
`clearCache()`). Azért statikus, mert **entitásmetódusból** hívjuk, ahol nincs hova injektálni — ugyanezért nyúl az entitás ma is a `\mkw\store::isFoglalas()`
-hoz.

```php
public static function getMinKeszlet($termek, $valtozat = null, $raktarid = null);
public static function preload(array $termekids, array $valtozatids, $raktarid = null): void;
public static function clearCache(): void;
public static function calcAvailableStock($termek, $valtozat = null, $datum = null,
    $raktarid = null, $kivevebiz = null, $clamp = true, $ignoreminkeszlet = false);
```

`preload()` két `IN (...)` lekérdezéssel tölt, és a **nem talált id-kre `null`-t ír a cache-be**, hogy a hiányok ne generáljanak egyesével újabb lekérdezést. A
`getMinKeszlet()` **ne kapjon `: float` visszatérési típust** — a 4. lépés ma is `null`-t adhat vissza, és a hívóhelyek kivonják (`5 - null === 5`); típus
nélkül bizonyíthatóan semleges a refaktor.

Belépési pontok az entitásokon (a 13 hívóhely diffje így egysoros marad):

```php
// Entities/TermekValtozat.php — a meglévő metódus kap egy paramétert, a törzse a service-re cserélődik
public function calcMinboltikeszlet($raktarid = null) {
    return \Services\KeszletService::getMinKeszlet($this->getTermek(), $this, $raktarid);
}
// Entities/Termek.php — ÚJ
public function calcMinboltikeszlet($raktarid = null) {
    return \Services\KeszletService::getMinKeszlet($this, null, $raktarid);
}
```

A `TermekValtozat::calcMinboltikeszlet()` szignatúrája tisztán bővül (minden mai hívó argumentum nélkül hívja), tehát a 8 hívóhely érintetlen marad.

**A szabad készlet egyetlen implementációja** — `calcAvailableStock()`:
`getKeszlet($datum,$raktarid) − getFoglaltMennyiseg($kivevebiz,$datum,$raktarid) − minimum`,
`$clamp` esetén `max(…, 0)`. Entitásonként egy egysoros `getAvailableStock(...)` burkoló.

- A `$raktarid` **mind a 13 hívóhelyen `null`** marad ebben a fázisban → minden szám változatlan.
- A 3 ma nem klippelő hely (`mainController:418`, `termekController:1080`, `:1128`) ebben a commitban kapjon explicit `$clamp = false`-t, hogy a diff szó
  szerint no-op legyen. Ezek a sablonokban `keszlet <= 0`-t tesztelnek, tehát a klippelés bizonyíthatóan semleges — a váltás mehet külön, jelölt commitban.
- A `nominkeszlet` kapcsolót **ne süssük bele** az alapútvonalba (globális, és ma csak a backorder nézi) — erre való a `$ignoreminkeszlet`, amit egyedül a
  `BackorderService` ad át. Ezzel a `BackorderService.php:32` termék/változat aszimmetriája is magától megszűnik (a nyers `getMinboltikeszlet()` helyére az új
  `Termek::calcMinboltikeszlet()` lép).

**Ne konvertáljuk** azokat a helyeket, amik szándékosan nem vonnak minimumot:
`Controllers/exportController.php:950, 972, 1048, 1085, 1254, 1293`,
`Entities/Termek.php:2942, 2947`, `Controllers/termekvaltozatController.php:59-60`.

**Két együtt javítandó apróság ugyanitt:**

- `TermekValtozat::getFoglaltMennyiseg($kivevebiz, $datum = null, $raktarid = null)` — tisztán bővítő; a privát `calcFoglalasInfo()` már fogadja mindhármat, és
  minden meglévő hívó nulla vagy egy argumentumot ad át. **A típusszűrő-aszimmetriát ne bántsuk**
  (`Termek` csak `megrendeles`, `TermekValtozat` `megrendeles`+`webshopbiz`) — az régi és a storefront számait mozdítaná.
- `getMozgasDb()` is törölje a `$keszletinfo`-t olvasás után, mint `getKeszlet()`. Ezzel a hibaosztály szerkezetileg megszűnik. Ára: +1 lekérdezés
  változatonként **kizárólag** a termékszerkesztőn (`termekController.php:108`), számszerűen azonos eredménnyel. A `$keszletinfo`/`$foglalasinfo` (`:26-27`)
  mehet `private`-ba.

## 3.2 Fázis 2 — Az új táblák

A két entitás + repository létrehozása, az `entity-change` skill szerint. Még senki nem olvassa és nem írja őket.

```bash
grep '^main.theme' config.ini      # a céladatbázis ellenőrzése
./generateproxies.sh
./updatesql.sh && cat update.sql   # KAPU: pontosan 2 CREATE TABLE, semmi más.
                                   # Ha bármi DROP/CHANGE van benne a termek/termekvaltozat
                                   # táblán → ÁLLJ
./updateschema.sh
```

Egyetlen `Listeners/` osztály sem nyúl a `Termek`/`TermekValtozat`/`Raktar` entitáshoz (`grep -i raktar Listeners/` → nulla találat), tehát nincs `onFlush`, ami
felülírná az új settereket.

Repository-metódusok (nincs `setOrders()`, mert nincs listaképernyő):

```php
public function getByTermekIds(array $termekids, $raktarid = null): array;  // [termekid][raktarid] => érték
public function getRowsByTermek($termekid): array;                          // raktarid szerint indexelve
```

## 3.3 Fázis 3 — Adatmigráció + admin mátrix

### 3.3.1 Az egyszeri adatmigráció

A mai globális értékek bemásolása az **alapraktár** (`parameterek.raktar`) celláiba, hogy a mátrix a jelenlegi állapotot mutassa, ne üres rácsot.

**Számszerűen semleges**: az alapraktárban a sor ugyanazt az értéket adja, mint a globális oszlop, a többi raktár pedig a globális oszlopra esik vissza.

`runonce.php` végére (az utolsó marker `'0111'`, tehát `'0112'`; a legutóbbi blokkok idiómája: `if ($DBVersion < 'NNNN')` a `:17`-en egyszer beolvasott értékre,
`executeStatement()`, végén `setParameter(DBVersion, 'NNNN')`):

```php
if ($DBVersion < '0112') {
    // a mai globális min.bolti készlet az alapraktár cellájába kerül, hogy a raktáras
    // mátrix a jelenlegi állapotot mutassa; a globális oszlop marad a többi raktár fallbackje
    $conn = \mkw\store::getEm()->getConnection();
    $tablakvannak = $conn->executeQuery(
        'SELECT COUNT(*) FROM information_schema.TABLES'
        . ' WHERE TABLE_SCHEMA = DATABASE() AND TABLE_NAME IN (?, ?)',
        ['termekminboltikeszlet', 'termekvaltozatminboltikeszlet']
    )->fetchOne();
    if ($tablakvannak == 2) {
        $raktarid = \mkw\store::getParameter(\mkw\consts::Raktar);
        $vanraktar = $raktarid
            ? $conn->executeQuery('SELECT COUNT(*) FROM raktar WHERE id = ?', [$raktarid])->fetchOne()
            : 0;
        if ($vanraktar) {
            $conn->executeStatement(
                'INSERT IGNORE INTO termekminboltikeszlet (termek_id, raktar_id, minboltikeszlet, created)'
                . ' SELECT t.id, ?, t.minboltikeszlet, NOW() FROM termek t'
                . ' WHERE (t.minboltikeszlet IS NOT NULL) AND (t.minboltikeszlet <> 0)',
                [$raktarid]
            );
            $conn->executeStatement(
                'INSERT IGNORE INTO termekvaltozatminboltikeszlet (termekvaltozat_id, raktar_id, minboltikeszlet, created)'
                . ' SELECT v.id, ?, v.minboltikeszlet, NOW() FROM termekvaltozat v'
                . ' WHERE (v.minboltikeszlet IS NOT NULL) AND (v.minboltikeszlet <> 0)',
                [$raktarid]
            );
        }
        \mkw\store::setParameter(\mkw\consts::DBVersion, '0112');
    }
    // ha a táblák még nincsenek meg (a runonce megelőzte az updateschema.sh-t), a DBVersion
    // NEM lép — a blokk a következő admin kérésen újra próbálkozik
}
```

Miért így:

- **A táblalét ellenőrzése kötelező.** A `runonce.php` az `index.php:163`-ban töltődik be, a
  `case (substr($match['name'], 0, 5) === 'admin')` ágban — tehát az első admin kérésen fut, ami megelőzheti a kézi `./updateschema.sh`-t. Hiányzó táblára az
  `INSERT` **minden admin kérést** fatalra vinne. A DBVersion ilyenkor szándékosan nem lép, így a migráció később magától lefut.
- **`INSERT IGNORE`** — a `UNIQUE` kulcs miatt idempotens; ha valaki már kitöltött cellákat kézzel, azokat nem írja felül. Precedens a fájlban:
  `runonce.php:1273`.
- **Csak nem-nulla érték** kerül át: a `0` a létrában „nincs beállítva", nullás sorokat fölösleges létrehozni.
- **`created` kézzel** (`NOW()`), mert a nyers SQL megkerüli a Gedmo Timestampable-t.
- Ha a `parameterek.raktar` üres vagy nem létező raktárra mutat, a migráció **kimarad** — a globális oszlopok így is működnek tovább, tehát nincs adatvesztés,
  csak a mátrix indul üresen.

> **Az egyetlen valódi mellékhatása** — és ezt a UI-ban jelezni kell: a migráció után az
> alapraktárnak **saját, explicit értéke** lesz. Ha az admin ezután a „Minden raktár"
> (globális) cellát 2-ről 3-ra írja, az alapraktár **2 marad**, mert a raktáras sor üti a
> globálisat. Enyhítés a 3.3.2-ben: az üres cellákban `placeholder`-ként ott az örökölt
> érvényes érték, a kitöltött cellák pedig vizuálisan elkülönülnek.

### 3.3.2 Sablon

A `tpl/admin/default/termekkarbform.tpl`-ben:

- **`:21-25`** — a fül `<li>`-je **ki a `{if ($setup.termekvaltozat)}`-ból**
  (`#MinBoltiKeszletTab`, felirat „Min. bolti készlet"), különben változat nélküli deploymenteknél nincs hol megadni a termékszintű raktáras értéket.
- **`:174-175`** — az Általános fül `MinboltikeszletEdit` sora **törlendő**. Két azonos
  `name` egy formban „az utolsó nyer" — a globális termékérték egyetlen gazdája a mátrix „Minden raktár" oszlopa legyen.
- **`:472-481`** — a rács helyére a mátrix, a `{/if}`-en (`:482`) **kívülre** mozgatva:

```
┌ Min. bolti készlet ─────────────────────────────┐
│               │ Minden raktár │ Központi │ Bolt1 │
│ Termék        │     [ 2 ]     │  [ 2 ]   │ [   ] │
│ Piros - M     │     [   ]     │  [   ]   │ [ 1 ] │
│ Piros - L     │     [   ]     │  [   ]   │ [ 1 ] │
└─────────────────────────────────────────────────┘
Üres cella = az öröklött érték él (szürkén látszik). 0 vagy üres = nincs beállítva.
A termék sorában megadott érték felülírja az alatta lévő változatokat ugyanabban az oszlopban.
```

Az üres cellák `placeholder`-e a létra szerint érvényes érték — így látszik, mit örököl a cella, és melyik szám explicit. A változatsorok továbbra is
`{if ($setup.termekvaltozat)}`
mögött. A rejtett `valtozatminboltikeszletid[]` **`<td>`-n belülre** kerüljön (a mai
`<div>`-es változat azért ússza meg, mert nem táblázat).

### 3.3.3 Kérésparaméterek

**Egyetlen meglévő paraméter sem változik** — csak három új jön:

| Név                                                      | Jelentés                                        |
|----------------------------------------------------------|-------------------------------------------------|
| `minboltikeszlet`                                        | termék, globális *(változatlan)*                |
| `valtozatminboltikeszlet_<valtozatid>`                   | változat, globális *(változatlan)*              |
| `valtozatminboltikeszletid[]`                            | mely változatsorok jelentek meg *(változatlan)* |
| `termekraktariminboltikeszlet_<raktarid>`                | termék, raktár *(új)*                           |
| `valtozatraktariminboltikeszlet_<valtozatid>_<raktarid>` | változat, raktár *(új)*                         |
| `minboltikeszletraktarid[]`                              | mely raktároszlopok jelentek meg *(új)*         |

A két rejtett tömb írja le a **ténylegesen kirajzolt rács kiterjedését** — ez teszi biztonságossá a „hiányzik ⇒ törlés" szabályt.

### 3.3.4 `loadVars` / `_getkarb`

- `_getkarb()` beállítja a `minboltikeszletraktarak` oszloplistát.
- `loadVars()` **egy** lekérdezéssel tölti a termék raktáras sorait, és **egy** további
  `WHERE termekvaltozat_id IN (…)` lekérdezéssel az összes változatét. Összesen **+2 lekérdezés**, változatszámtól függetlenül — nem a `getKeszletByRaktar()`
  N+1 mintája.

### 3.3.5 Melyik raktárak jelenjenek meg

`RaktarRepository::getAllActive()` **plusz** minden olyan raktár, amire ennél a terméknél már van sor, akkor is, ha archivált (a sorok úgyis be vannak töltve,
tehát ingyen van). Így az archivált raktáron ragadt érték látható és törölhető, nem kísért csendben. A `mozgat = 1`-re szűkítés **ne** legyen: elrejtene
raktárakat marginális megtakarításért.

### 3.3.6 Üres mező jelentése

**Üres *vagy* nulla ⇒ a sor törlése.** A létra „ha nem nulla" tesztje miatt a tárolt `0` és a hiányzó sor viselkedésben azonos; nullákat tárolni csak hízlalná a
táblát.

### 3.3.7 `max_input_vars` — a legvalószínűbb hibabejelentés

60 változat × 8 raktár = **617 mező** csak a mátrixból (480 változat×raktár + 60 változat globális + 60 rejtett + 8 termék×raktár + 1 + 8), egy amúgy is több
száz mezős űrlap tetején. A PHP alapértelmezett `max_input_vars = 1000` fölött a `$_POST` **csendben csonkolódik** — a tünet: „a form végén lévő fülek nem
mentődnek". (A docker stack `50000`-en van
[`docker/php-fpm.conf:20`], az éles hosztok ismeretlenek.)

Enyhítés, preferencia sorrendben:

1. **Üres mezők kizárása beküldéskor** (~8 sor a `js/admin/default/termek.js` submit kezelőjében): az üres `input[type=number]`-eket `disabled`-re állítva az
   `ajaxForm`
   kihagyja őket. Egy jórészt üres mátrix így ~70 változóba fér. Pontosan azért biztonságos, mert a „hiányzik ⇒ törlés" és az „üres ⇒ törlés" **ugyanaz a
   szabály**. A két rejtett tömböt soha ne szűrjük.
2. A `max_input_vars` megemelésének dokumentálása.
3. Vészkijárat 60×20-as méretnél: a fül külön AJAX-panelre és saját mentőútvonalra — csak akkor, ha valaki tényleg falnak megy.

### 3.3.8 `setFields` mentőág

A `:764-771` blokk kibővítve: **két kötegelt `SELECT`**, majd tisztán memóriából döntés (nem 488 `findOneBy()`), raktáranként `upsert`, ha az érték nem nulla,
`remove`, ha nulla vagy üres. A `Raktar` példányok egyszer felépített `[id => Raktar]` mapból jönnek. Csak a `minboltikeszletraktarid[]`-ben szereplő raktárakat
érintjük — a ki nem rajzolt sorok érintetlenek maradnak.
`afterSave()` hívja a `\Services\MinBoltiKeszletService::clearCache()`-t.

## 3.4 Fázis 4 — Raktárszűrt fogyasztók (**itt lesz látható a funkció**)

### `keszletlistaController` SQL

A minimumot ki kell emelni az aggregáló alkérdésből a külső `SELECT`-be, ahol a külső
`_xx` / `t` aliasra korrelál — ezzel az árnyékoló belső `LEFT JOIN termek t` is eltűnik.

```php
if ($raktar) {
    $minexpr = 'COALESCE('
        . 'NULLIF((SELECT tmk.minboltikeszlet FROM termekminboltikeszlet tmk'
        . ' WHERE tmk.termek_id = _xx.termek_id AND tmk.raktar_id = :mkraktar), 0),'
        . 'NULLIF((SELECT vmk.minboltikeszlet FROM termekvaltozatminboltikeszlet vmk'
        . ' WHERE vmk.termekvaltozat_id = _xx.id AND vmk.raktar_id = :mkraktar), 0),'
        . 'NULLIF(t.minboltikeszlet, 0),'
        . '_xx.minboltikeszlet,'
        . '0)';
    $mkparams = ['mkraktar' => $raktar];
} else {
    $minexpr = 'COALESCE(NULLIF(t.minboltikeszlet, 0), _xx.minboltikeszlet, 0)';
}
```

- `:mkraktar` nem ütközik: a `getFilterString('_xx','p')` `:p1…`-et, a `('_xx','r')` `:r1…`-et generál. **Csak a `$raktar` ágban szabad kötni** — a
  `createNativeQuery` „Invalid parameter number"-t dob olyan paraméterre, ami nincs benne az SQL-ben.
- `$raktar` ma a `createFilter()` lokálisa (`:55`) — privát mezőbe kell emelni, mint a `raktarnev`.
- `NULLIF(x, 0)` a `* 1` teszt SQL-megfelelője, `DECIMAL`-on pontos.
- **Egy szándékos viselkedésváltozás**, jelezni kell: a mozgás nélküli változat `keszlet`-je eddig NULL volt, most `-min`. A 4-es szűrő („ami negatív") ezért
  újonnan tartalmazza őket, ha a `minkeszletszamit` be van kapcsolva. A kikapcsolt ág változatlan marad.

### További raktárszűrt hívók

`$raktarid` átadása: `Controllers/boltieladasController.php:203` (POS),
`Controllers/listaController.php:69`, `Controllers/bizonylattetellistaController.php:183,191`.

## 3.5 Fázis 5 — Opcionális megerősítés (külön commitokban)

1. **`getAllActive()` NULL-biztonság** — `((_xx.archiv IS NULL) OR (_xx.archiv <> 1))`. Ugyanez a lyuk: `Controllers/listaController.php:171`,
   `bizonylattetellistaController`. **Ez a 3. fázis előfeltétele** minden olyan deploymenten, ahol a `raktar.archiv` lehet NULL.
2. **`nominkeszlet` karkod-csapda** — `if ($nominkeszlet && $nominkeszletkat && …)` a
   `BackorderService`-ben, és `if (!$kat) { return false; }` az `isInTermekKategoria()`-ban. **Viselkedést változtat**: ahol ma `nominkeszlet = 1` beállított
   kategória nélkül, ott most valójában nincs minimum, és a javítás visszakapcsolja. Ezért külön, bejelentett commit.
3. **`termekController.php:288` őr** — `getFloatRequestParam` ma mindig ír, alapértéke `0.0`, így egy olyan sablon, amiben nincs a mező (darshan), minden
   mentésnél nulláz.
   `if ($this->params->existsRequestParam('minboltikeszlet'))` őrrel megszűnik.
4. `raktarController::getSelectList()` archivált raktárakat is ad (`getAll()`), szemben a
   `getAllActive()`-val — régi következetlenség, csak jegyzet.
5. `getKeszletByRaktar()` N+1 megszüntetése (`termekController:1688-1706`,
   `termekvaltozatController:407-429`), `preload()` a `keszletlista`-ban.

A `nominkeszlet` / `nominkeszlettermekkat` **jelentése nem változik**; a létra eredményét nullázza, amit a `$ignoreminkeszlet = true` valósít meg. A
paraméterkulcs sorazonosító, **nem nevezhető át**.

---

# 4. rész — Kockázatok

| Kockázat                                                        | Hatás                                                       | Kezelés                                                                                                        |
|-----------------------------------------------------------------|-------------------------------------------------------------|----------------------------------------------------------------------------------------------------------------|
| A migráció fut, mielőtt az új táblák léteznek                   | Minden admin kérés fatalra fut                              | 3.3.1: `information_schema` táblalét-ellenőrzés, és a DBVersion nem lép, amíg nincs meg                        |
| Az alapraktár cellája „lefagy" a migrált értéken                | A globális cella későbbi módosítása nem hat az alapraktárra | 3.3.2: örökölt érték `placeholder`-ként, explicit cellák vizuálisan elkülönítve; a mátrix alatti magyarázó sor |
| `max_input_vars` csonkolás                                      | **Csendes részleges mentés**                                | JS-es üres-mező szűrés + limit dokumentálása                                                                   |
| `getAllActive()` üres `archiv IS NULL`-nál                      | A mátrix nulla raktároszlopot rajzol                        | 3.5/1, a 3. fázis előtt                                                                                        |
| `"0.00"` truthiness                                             | A létra rossz lépcsőn áll meg                               | `* 1` PHP-ben, `NULLIF(…, 0)` SQL-ben                                                                          |
| A létra két implementációja szétcsúszik                         | Két képernyő más számot mond                                | Csak kettő van (service + `keszletlista` SQL) — egysoros kereszthivatkozó komment mindkettőbe                  |
| `keszletlista` „ami negatív" bővül                              | Riport sorszám változik                                     | 3.4, bejelenteni                                                                                               |
| A darshan téma kimarad                                          | A mátrix ott nem jelenik meg                                | Saját 406 soros sablonmásolat — külön feladat                                                                  |
| Precedencia-meglepetés (termék@raktár üti a változat@globálist) | Admin zavar, adatvesztés nincs                              | A mátrix alatti magyarázó sor                                                                                  |

---

# 5. rész — Ellenőrzés

**Fázis 1 (helperek)** — számszerű regresszió-ellenőrzés élő adaton:

1. Egy webshop termékoldal és a méretválasztó ugyanazt a készletszámot mutatja, mint előtte.
2. `/admin/keszletlista` „Min.készlet számít" bekapcsolva, ugyanaz a sorhalmaz.
3. Backorder szétbontás egy tesztrendelésen: ugyanaz a teljesíthető/backorder felosztás.

**Fázis 2 (táblák)**

4. `./updatesql.sh && cat update.sql` → **pontosan 2 `CREATE TABLE`**, semmi más.

**Fázis 3 (migráció + UI)**

5. Migráció előtt: `SELECT COUNT(*) FROM termek WHERE minboltikeszlet IS NOT NULL AND minboltikeszlet <> 0;`
   — a `termekminboltikeszlet` sorszámának utána ezzel kell egyeznie.
6. `SELECT * FROM parameterek WHERE id = 'dbversion';` → `0112`. Ha nem lépett, az új táblák hiányoztak — futtasd az `./updateschema.sh`-t és tölts újra egy
   admin oldalt.
7. Termékszerkesztő → „Min. bolti készlet" fül: minden aktív raktár oszlopként megjelenik (ha nulla oszlop látszik → 3.5/1 nem futott le), és az alapraktár
   oszlopa ki van töltve.
8. Kitöltés egy másik raktárra, mentés, újratöltés → megmarad;
   `SELECT * FROM termekminboltikeszlet;` a várt sorokat adja, nulla/üres cellára **nincs sor**.
9. Egy cella kiürítése + mentés → a sor eltűnik.
10. **A migráció után minden szám marad a régi** — ez a legfontosabb ellenőrzés (az 1-3. pont megismétlése).

**Fázis 4**

11. `/admin/keszletlista` konkrét raktárral, „Min.készlet számít": a kitöltött termékre a raktáras minimum jön le, a többire a globális.
12. Bolti eladás (POS) a beállított raktárban: a „raktáron" jelzés a raktáras minimumot veszi.

**Böngészős E2E**: `docker compose up`, `mkw.test`, `./galad.sh` az aktív deployment (jelenlegi `main.theme = galad`).

---

# Függelék — `minboltikeszlet` → `minkeszlet` átnevezés (későbbre halasztva)

Nem része ennek a tervnek, de a döntés indoklása és a csapda maradjon dokumentálva.

**A csapda:** az `orm:schema-tool:update --force` **nem ismeri fel az oszlop-átnevezést**:
`ADD minkeszlet … DROP minboltikeszlet`-et generál, ami csendben és visszafordíthatatlanul elviszi az összes tárolt minimumot. A DDL-t ezért kézzel kell
futtatni, a kód élesítése előtt:

```sql
ALTER TABLE termek CHANGE minboltikeszlet minkeszlet DECIMAL (14,2) DEFAULT NULL;
ALTER TABLE termekvaltozat CHANGE minboltikeszlet minkeszlet DECIMAL (14,2) DEFAULT NULL;
```

A `runonce.php` erre **nem elég** biztosítéknak: az `index.php:163` miatt csak admin (és cron)
kérésre fut, tehát ha az élesítés utáni első kérés egy storefront oldal, minden
`SELECT … termek.minkeszlet` MySQL 1054 *Unknown column*-ra fut, amíg valaki be nem lép.

**Az átnevezés érintettsége** (a jelen terv megvalósítása után négy oszlop és két táblanév):
`Entities/Termek.php:404-405, :3366-3377`, `Entities/TermekValtozat.php:193-194, :933-953`, a két új entitás + tábla, `Proxies/` (regenerálás),
`Controllers/termekController.php:288, :764-771`
(a kérésparaméter-nevekkel együtt), a 13 olvasóhely, a `keszletlista` SQL,
`Entities/Termek.php:794, :944, :1002` tömbkulcsok (ellenőrizve: nem szerződésesek — egyetlen sablon sem olvassa és nincs JSON-fogyasztó),
`tpl/admin/default/termekkarbform.tpl` és
`termeklista_tbody_tr.tpl`. JS: nulla találat. A darshan sablonmásolatokban nincs találat.

**Nulla kockázatú alternatíva:** `@ORM\Column(name="minboltikeszlet", type="decimal", …)
private $minkeszlet;` — a PHP property, az accessorok és minden `getEntityFieldsArray()`
tömbkulcs átnevezhető **DDL nélkül**. Precedens: `Entities/Hir.php:36`. Ára: a fizikai oszlopnév marad, tehát a riport-SQL-ekben és a `keszletlista`
lekérdezésben a régi név él tovább.

---

# Megvalósítás — eltérések a tervtől

A terv 1-5. fázisa lekódolva. Amiben a kód eltér a fenti szövegtől:

- **A runonce marker `0113`, nem `0112`.** A `0112`-t időközben elvitte a cron napló menüpontja.
- **A `keszletlista` „ami negatív" szűrője NEM bővül.** A terv szerint a mozgás nélküli változat
  `keszlet`-je `-min`-re változna; a tényleges splice `(SELECT SUM(…)) - min`, és SQL-ben
  `NULL - 2 = NULL`, tehát az ilyen sorok továbbra is NULL-ok maradnak. Élő adaton ellenőrizve:
  a szűrő sorszáma a régi (3 sor), és csak akkor nő, ha a raktáras minimum tényleg negatívba viszi egy mozgással rendelkező változat készletét. A 4. rész
  kockázati táblájának ez a sora tehát tárgytalan — nincs bejelentenivaló.
- **A `bizonylattetellistaController` készletoszlopai nyers készleten maradtak.** A 3.4 felsorolja a `:183,191` sorokat, de ott a raktárankénti oszlop a
  teljesíthetőség tervezését szolgálja, és a
  `getAvailableStock()` a foglalást is levonná — köztük *annak a rendelésnek* a foglalását is, amelyik sorát épp nézzük (a csoportosított nézetben nincs
  egyetlen `$kivevebiz`, amit ki lehetne venni). A `getAllActive()`-ra váltás viszont ott is megtörtént (3.5/1).
- **A POS és a „boltban nincs, máshol van" riport átállt `getAvailableStock()`-ra**, de nem ugyanúgy. A `calcAvailableStock()` kapott egy `$ignorefoglalas`
  kapcsolót is (a `$clamp` és az
  `$ignoreminkeszlet` mellé), így a szabad készletnek továbbra is egyetlen implementációja van:
    - **POS**: minimum **és** foglalás levonva – ami a pénztárnál tényleg eladható.
    - **„boltban nincs, máshol van"**: `$ignorefoglalas = true`, tehát csak a minimum jön le. A riport a bolt polckészletét nézi, nem a szabad készletet.

  Számszerű változás így egyedül a POS „raktáron" jelzésén van (`foglalas = 1` deploymenteken).
- **`jquery.mattkarb.js`: új `beforeSubmit` passzthrough.** A 3.3.7/1 üres-mező-szűréshez a
  `jquery.form` mezőtömbjét kell szűrni; a mattkarb eddig csak `beforeSerialize`-t adott tovább. A passzthrough beállítás nélkül semleges, minden más karb
  képernyő változatlan.
- **A 3.5/4 (`raktarController::getSelectList()` következetlensége) és a 3.5/5 (`getKeszletByRaktar()`
  N+1) nem készült el** — a terv is jegyzetként, illetve opcionálisként jelölte őket.
