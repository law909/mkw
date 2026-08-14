# Minimum készlet alatti termékek riportja

Állapot: **lekódolva**, böngészős E2E még hátra. Branch: `minkeszlet-riport`.

Megmutatja, hogy egy adott raktárban, egy adott napon mely termékek/változatok készlete van a **raktárra vonatkozó** minimum alatt. Kiírja a készletet, a
minimumig hiányzó mennyiséget, és egy másik, megadott raktárban lévő készletet – hogy látszódjon, van-e honnan átmozgatni.

Menü: Kimutatások → **Minimum készlet alatt** (a Készlet kimutatás mellett, azonos, 40-es jogosultsággal). A menüsort a `runonce` 0114-es blokkja teszi be.

## Kimenetek

| Gomb                    | Mit ad                                                                                                                                                   |
|-------------------------|----------------------------------------------------------------------------------------------------------------------------------------------------------|
| **OK**                  | képernyős riport (`rep_minkeszlet.tpl`): cikkszám, vonalkód, termék, változat, készlet, min. készlet, hiány, másik raktár készlete, alul a hiány összege |
| **Export**              | ugyanez Excelben                                                                                                                                         |
| **Export bizonylathoz** | szűkebb Excel: **termék id, változat id, cikkszám, vonalkód, mennyiség** – a mennyiség a minimumig való feltöltéshez szükséges darab                     |

A szűrő: dátum, raktár (kötelező – a minimum raktárfüggő), a másik raktár (elhagyható), a
gyártó, a **termékfa** (a készlet kimutatáséval azonos jstree, a kijelölt ágak karkod-előtagja
megy a `termekfa1/2/3karkod` mezőkre), és a **Készlet** input a mellette lévő pipával.

**A Készlet input**: bekapcsolt pipánál a beírt érték a küszöb minden soron a raktáranként
feloldott minimum helyett – tehát „mi van 5 darab alatt", függetlenül attól, be van-e állítva
minimum. Ilyenkor a „nincs beállítva minimum" szűrés (`minkeszlet > 0`) sem érvényes, így
0-t beírva pont a negatív készletűek jönnek. A pipa nélkül a riport számai változatlanok.

A képernyős riportnak **nyomtatási képe** is van: a lap tetején egy „Nyomtatás" gomb (nyomtatáskor
maga a gomb eltűnik), a táblázat alján a nyomtatás dátuma – ugyanaz a `rep.css`, ami a többi
kimutatásé.

## Hogyan számol

- **Készlet**: `SUM(mennyiseg * irany)` a mozgató, nem rontott bizonylattételekre, `teljesites <=`
  a megadott napig, az adott raktárban – betűre ugyanaz a szűrés, mint a `Termek::getKeszlet()`-ben. A lerontott bizonylat tételei is rontottak
  (`Bizonylatfej::setRontott()` végigviszi), ezért a tétel rontott jelzője elég.
- **Minimum**: a raktárankénti feloldási létra (változat@raktár → termék@raktár → változat globális → termék globális), lásd
  `docs/raktarankenti-minimum-keszlet.md`. Változatos terméknél a termékszintű minimum nulla, tehát ott csak a változatok értéke számít.
- **Bekerül a riportra**, aminek a minimuma nem nulla **és** a készlete a minimum alatt van. A nulla minimum a létrában „nincs beállítva", ezért az ilyen sorok
  nem érdekesek.
- **Hiány** = minimum − készlet.
- **Változat nélküli termékek is benne vannak**: azokra a bizonylattétel a termékre hivatkozik változat nélkül, ezért a lekérdezés két ágból (`UNION ALL`) áll.
  A `keszletlista` ezt nem tudja – ott csak a `termekvaltozat` tábla hajtja a riportot.

## A létra SQL-mása egy helyre került

A raktárankénti minimumnak eddig két implementációja volt: a `MinBoltiKeszletService::getMinKeszlet()`
és a `keszletlistaController` SQL-je. Ez a riport lett volna a harmadik, ezért a SQL-változat átkerült a service-be:

```php
\Services\KeszletService::getMinKeszletSql($termekid, $termekmin, $valtozatid, $valtozatmin, $raktarparam)
```

A `keszletlistaController` is ezt hívja – a számai bizonyítottan változatlanok (a korábbi regressziós próba ugyanazokat az értékeket adja). A változat nélküli
ághoz a változat-paraméterek üresen maradnak, raktárfüggetlen értékhez a `$raktarparam`.

## Érintett fájlok

| Fájl                                        | Mi történt                                         |
|---------------------------------------------|----------------------------------------------------|
| `Controllers/minkeszletlistaController.php` | **új** – lekérdezés + 3 kimenet                    |
| `tpl/admin/default/minkeszletlista.tpl`     | **új** – szűrőképernyő                             |
| `tpl/admin/default/rep_minkeszlet.tpl`      | **új** – képernyős riport                          |
| `js/admin/default/minkeszletlista.js`       | **új** – a három gomb                              |
| `adminroute.php`                            | 4 új route                                         |
| `runonce.php`                               | 0114: menüpont                                     |
| `../Services/KeszletService.php`            | `getMinKeszletSql()` – a létra SQL-mása egy helyre |
| `Controllers/keszletlistaController.php`    | a beépített SQL helyett a service-t hívja          |
| `../themes/admin/default/rep.css`           | `.noprint`, és a `.redtext` (eddig nem is létezett) |

## Ellenőrzés

Élő adaton, ideiglenes minimumokkal (30 változatra raktáras, 10 termékre globális), minden esetben takarítással:

- **140 sor keresztellenőrizve**: a lekérdezés készlete és minimuma minden soron megegyezik a
  `Termek/TermekValtozat::getKeszlet()` és a `MinBoltiKeszletService::getMinKeszlet()` értékével.
- Minden soron teljesül, hogy a minimum > 0 és a készlet a minimum alatt van.
- A raktáras minimum üt: ugyanaz a változat az 1-es raktárban 100, a 2-esben 3 minimummal jelenik meg.
- Változat nélküli termék is bekerül, üres változat id-vel.
- A másik raktár oszlopa tényleg a másik raktár készlete.
- Raktár nélkül üres a riport; a rendezés cikkszám szerint növekvő.
- A `keszletlista` regressziós próbája a refaktor után is ugyanazokat a számokat adja.
- Mindkét Excel valódi xlsx; a bizonylathoz való export visszaolvasva:
  `["Termék ID","Változat ID","Cikkszám","Vonalkód","Mennyiség"]` / `["1557","4330","116L-ALV-01-M","8057803223008","95"]`.

**Böngészőben még ellenőrizendő:**

1. A menüpont megjelenik a Kimutatások alatt (a `runonce` 0114 lefutása után).
2. A dátumválasztó és a két raktárválasztó működik, a riport új lapon nyílik.
3. Mindhárom gomb ugyanazt a szűrőt küldi a maga útvonalára.
4. Nagy termékszámnál a futásidő elfogadható (a lekérdezés termékenként/változatonként számol készletet, mint a `keszletlista`).

### A termékfa szűrő, a küszöb és a nyomtatási kép (2026-08-14, mugenrace fejlesztői DB, böngészőben)

- A fa betölt (169 csomópont), a kijelölés a rejtett `fafilter` mezőbe kerül, és mindhárom gomb elviszi.
- Szűrő nélkül 3376 sor / 6945 hiány; ugyanez a szám jön akkor is, ha a Készlet inputba írunk, de a pipa nincs bekapcsolva – **nincs regresszió**.
- `fafilter=36,38` (Textil kabát + Kesztyű): 584 sor, a riportfejlécben a két kategória neve.
- Olyan kategóriára, ahol egy változatnak sincs minimuma (`fafilter=175`), üres a riport – SQL-lel ellenőrizve, hogy tényleg 0 az érintett sor.
- Készlet = 5 + pipa: 1956 sor, mindegyiken 5 a „Min. készlet" oszlop.
- Készlet = 0 + pipa: 56 sor, mindegyik negatív készletű.
- A riporton ott a Nyomtatás gomb és a nyomtatási dátum.
