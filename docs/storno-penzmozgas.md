# Storno és a kapcsolódó pénzmozgás

*Terv, 2026-08-14. Kódolvasás alapján, még nincs lekódolva.*

Előzmény: `docs/fizmód váltás bizonylaton következményei.md` — ott a **fizetési mód váltása** kapta meg
a „mi legyen a meglévő pénzmozgással?" kérdést. Ez a terv ugyanezt viszi végig a **stornón**, és
mellékesen befoltoz két lyukat, ami a mai kódban maradt.

---

## 1. Mi van ma

### 1.1 Rontáskor csak a pénztárbizonylat rontódik, a bank nem

`BizonylatfejListener.php:1120`:

```php
if ($entity->getRontott()) {
    $this->rontPenztarBizonylat($entity);   // csak Penztarbizonylatfej
} else {
    $this->createPenztarBizonylat($entity);
}
```

`rontPenztarBizonylat()` a `Penztarbizonylatfej` repositoryban keres. A bizonylatra hivatkozó élő
**bankbizonylat** érintetlen marad, vagyis egy lerontott számla kiegyenlítése továbbra is ott van a
folyószámlán. A fizmód-váltás ága ezt már helyesen csinálja (`rontKapcsolodoPenzmozgas()` mindkettőt
rontja) — csak a ront-ág maradt ki.

**Ez egyértelmű hiba, függetlenül a storno-kérdéstől.**

### 1.2 Stornókor semmi nem történik a pénzmozgással

`createPenztarBizonylat()` a `getStornozott() || getRontott()` ágon kilép (`:500`), tehát a stornózott
bizonylat pénztár-/bankbizonylatához nem nyúlunk. A storno bizonylat viszont képez magának egy
ellentétes irányú pénztárbizonylatot (`$stornoszorzo`, `:542`).

### 1.3 A folyószámla három jelzője, és amelyik hiányzik

Egy folyószámla sor akkor esik ki az egyenlegből, ha `storno`, `stornozott` vagy `rontott`. Ezt
minden összesítés egységesen nézi:

| lekérdezés | szűrő |
|---|---|
| `FolyoszamlaRepository::getSumByPartner()` | `storno=0 AND stornozott=0 AND rontott=0` |
| `getKintlevosegByValutanem()`, `getLejartKintlevosegByValutanem()` | `WHERE (storno=0) AND (stornozott=0)` |
| `kintlevoseglistaController`, `tartozaslistaController` | `bf.stornozott = false` |

A **bizonylat** saját sorai megkapják a jelzőket (`BizonylatfejListener::createFSzla():74-76`), a
**pénztár- és bankbizonylat** sorai viszont soha:

```php
// PenztarbizonylatfejListener:123-124  és  BankbizonylatfejListener:123-124
$fszla->setStorno(false);
$fszla->setStornozott(false);
```

Nekik egyedül a `rontott` marad. Ezért lóg a stornózott számlán egy párját vesztett jóváírás, ha a
storno nem képez ellentételt — és ezért nem látszik sehol, hogy utalásos esetben **vissza kell utalni**
a pénzt.

---

## 2. A négy eset, amit le kell fednie

| # | eset | mi a helyes |
|---|------|-------------|
| 1 | **készpénzes számla, a pénzt visszaadjuk a pénztárból** | az eredeti pénztárbizonylat marad, a storno képez egy ellentétes irányú kiadást — a kassza mindkét mozgást mutatja |
| 2 | **készpénzes számla, a pénz nem mozdult** (a vevő elállt) | az eredeti pénztárbizonylatot rontani kell, a storno ne képezzen ellentételt |
| 3 | **utalásos, kiegyenlítetlen számla** | nincs pénzmozgás, nincs mit rontani — csak a storno |
| 4 | **utalásos, már kiegyenlített számla** | vissza kell utalni; ez **tartozásként látsszon a storno számlán**, és a visszautalás bankbizonylata egyenlítse ki |

Az 1. és a 2. között a program nem tud dönteni: ez üzleti tény. **Meg kell kérdezni.**

---

## 3. A megoldás

### 3.1 A kérdés

A storno bizonylat **mentésekor**, ha a stornózott bizonylathoz tartozik élő pénztár- vagy
bankbizonylat, ugyanolyan párbeszéd jön fel, mint fizmód-váltáskor:

> **A stornózott bizonylathoz az alábbi pénzmozgás(ok) tartoznak: …**
> **Visszafizeti a pénzt?**
> — *Igen, visszafizetem* / *Nem, csak rontsa a pénzmozgást* / *Mégsem*

A listát a **már meglévő** `/admin/bizonylatfej/kapcsolodopenzmozgas` végpont adja
(`bizonylatfejController.php:2404`), csak a `bizszam` paraméterbe a form `parentid` mezője megy az `id`
helyett. A végpont már ma is pénztár- **és** bankbizonylatot is felsorol, tehát nem kell hozzányúlni.

Ha nincs élő pénzmozgás (3. eset), nem kérdezünk.

### 3.2 A válasz hordozója: `penztmozgat` a storno bizonylaton

Nem kell új mező. A válasz a storno bizonylat meglévő **„Kintlévőséget/tartozást képez"**
(`bizonylatfej.penztmozgat`) jelölőjébe megy:

| válasz | storno `penztmozgat` | stornózott bizonylat pénzmozgása |
|--------|----------------------|----------------------------------|
| **Visszafizetem** | `1` | marad élő (a pénz tényleg mozgott) |
| **Csak rontsa** | `0` | **rontott** (pénztár és bank egyaránt) |
| nincs élő pénzmozgás | `0` | – |

Ez pontosan az a szemantika, amit a mező neve mond: a storno bizonylat akkor mozgat pénzt, ha tényleg
visszajár valami. A 3. esetben nem jár vissza semmi, ezért ott is `0`.

### 3.3 A folyószámla-jelzők javítása

Két, egymástól független egysoros:

**a)** `BizonylatfejListener::createFSzla()` — a storno bizonylat sorára ne kerüljön rá a kiszűrő
`storno` jelző. Ha a sor egyáltalán létrejött, akkor `penztmozgat = 1`, vagyis a válasz „visszafizetem"
volt, tehát a sornak **látszania kell**. (`createFolyoszamla()` `penztmozgat = 0` esetén már ma is
kilép a sorképzés előtt, `:124`.)

```php
- $fszla->setStorno($bizonylat->getStorno());
+ // a storno bizonylat sora csak akkor születik meg, ha penztmozgat=1, azaz tényleg visszajár
+ // a pénz – olyankor pedig látszania kell az egyenlegben (visszafizetendő tartozás)
+ $fszla->setStorno(false);
```

**b)** `PenztarbizonylatfejListener::createFolyoszamla()` és `BankbizonylatfejListener::createFolyoszamla()` —
a pénzmozgás sora örökölje a hivatkozott bizonylat `stornozott` jelzőjét a mai fix `false` helyett.
Így a stornózott számla kiegyenlítése nem lóg ott jóváírásként.

### 3.4 Az ellentétel

A készpénzes ág magától jó: a storno `penztmozgat = 1`-gyel megy a `createPenztarBizonylat()`-ba, ami
képez egy ellentétes irányú kiadást (`$stornoszorzo`). `penztmozgat = 0` esetén viszont a `:513` sor
(`!$bizfej->getPenztmozgat()`) miatt kilép — tehát a 2. esetben magától **nem** képez ellentételt.
Külön kód nem kell hozzá.

Bankbizonylatot a rendszer sosem képez automatikusan, ezért a 4. esetben nem lesz ellentétel — épp
ezért marad nyitva a tartozás, ahogy kell.

---

## 4. Mi lesz a négy esetből

Példa: 10 egység, számla `irany = -1`, tehát a folyószámla sor iránya `+1`.

| | eredeti számla sora | eredeti pénzmozgás sora | storno sora | storno ellentétele | **storno egyenlege** |
|---|---|---|---|---|---|
| **1. kp, visszaadjuk** | +10, `stornozott=1` → ki | −10, örökli `stornozott` → ki | −10, **él** | +10 pénztárbizonylat, él | **0** |
| **2. kp, nem mozdult** | +10, `stornozott=1` → ki | **rontott** → ki | nincs (`penztmozgat=0`) | nincs | **0** |
| **3. utalás, kiegyenlítetlen** | +10, `stornozott=1` → ki | nincs | nincs (`penztmozgat=0`) | nincs | **0** |
| **4. utalás, kiegyenlített** | +10, `stornozott=1` → ki | −10, örökli `stornozott` → ki | −10, **él** | nincs | **−10 = visszautalandó** |

A 4. esetben a visszautalást a stornóra hivatkozó bankbizonylattal rögzítik (+10) → az egyenleg 0.
Az 1. esetben a pénztárjelentésben mindkét mozgás (bevét és kiadás) rendesen látszik.

---

## 5. Nyitott kérdés: a meglévő storno bizonylatok

A 3.3/a változás **visszamenőleg** is hat: minden meglévő storno bizonylat sora, amin
`penztmozgat = 1` (mert a storno a stornózottól örökölte), hirtelen bekerül az egyenlegbe. Ahol nem
volt tényleges visszafizetés, ott ez elrontja a partner egyenlegét.

Két út, **a döntés még nyitott**:

1. **Runonce migráció**: a meglévő storno bizonylatokon `penztmozgat = 0`, kivéve ahol a stornózott
   bizonylathoz tartozik élő pénztár-/bankbizonylat. Ez a mai egyenleget megőrzi, mert amelyik storno
   sora eddig kiesett, az ezután sem kerül be.
2. **Külön mező** (`stornopenzvisszajar`) a `penztmozgat` helyett, alapból `0`. A múlt így garantáltan
   érintetlen, cserébe egy új oszlop és egy plusz fogalom.

Az 1. javasolt: nem hoz be új fogalmat, és a `penztmozgat` jelentése amúgy is pontosan ez.
Kódolás előtt telepítésenként meg kell nézni, hány storno bizonylat érintett:

```sql
SELECT COUNT(*) FROM bizonylatfej WHERE storno = 1 AND penztmozgat = 1;
```

A galad fejlesztői adatbázison **0** — ott egyáltalán nincs storno bizonylat, tehát a kockázat
kizárólag az éles telepítéseken mérhető fel.

---

## 6. Érintett fájlok

| fájl | mi változik |
|------|-------------|
| `Listeners/BizonylatfejListener.php` | `:1120` ront-ág: a bankbizonylatot is rontsa (a meglévő `rontKapcsolodoPenzmozgas()` újrahasznosítható); `createFSzla()` `setStorno()` |
| `Listeners/PenztarbizonylatfejListener.php` | `createFolyoszamla()` — `setStornozott()` a hivatkozott bizonylatból |
| `Listeners/BankbizonylatfejListener.php` | ugyanaz |
| `Controllers/bizonylatfejController.php` | a `getKapcsolodoPenzmozgas()` marad, a storno mentésénél a válasz átvétele a `penztmozgat`-ba |
| `js/admin/default/bizonylathelper.js` | új `kerdezStornoPenzrol()` a meglévő `kerdezPenzmozgasrol()` mintájára; a storno formon (`oper=storno`) fut, `parentid`-vel |
| `runonce.php` | az 5. pont szerinti migráció, ha az 1. utat választjuk |

## 7. Mit kell majd ellenőrizni

Mind a négy eset a galad fejlesztői adatbázison, tranzakcióban futtatva és visszagörgetve, a
`docs/fizmód váltás…` 5. pontjának mintájára:

- a partner összesített egyenlege minden esetben a 4. pont táblázata szerinti,
- a kintlévőség- és tartozáslistán a 4. eset storno bizonylata **megjelenik**, a többi nem,
- a pénztárjelentés az 1. esetben két mozgást mutat, a 2.-ban egyet sem,
- rontáskor a bankbizonylat is rontott lesz (ma nem),
- a bizonylatnaplóba a válasz is bekerül, a fizmód-váltás mintájára.
