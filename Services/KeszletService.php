<?php

namespace Services;

use Doctrine\ORM\Query\ResultSetMapping;
use Entities\Bizonylatfej;
use Entities\Bizonylattipus;
use Entities\Raktar;
use Entities\Termek;
use Entities\TermekMinkeszlet;
use Entities\TermekValtozat;
use Entities\TermekValtozatMinkeszlet;
use mkwhelpers\FilterDescriptor;

/**
 * Készletszámítás: a bizonylattételekből összegzett raktárkészlet és foglalás, ebből a szabad
 * készlet (készlet − foglalás, a Beállítások szerint a min. készlettel is csökkentve), valamint a
 * polcon tartandó minimum ("min. bolti készlet") feloldása, ami a webshopon eladható mennyiséget
 * mindig szűkíti.
 *
 * A minimum feloldási létrája – a szűkebb beállítás nyer, raktáras érték üti a globálisat:
 *   1. termekvaltozatminkeszlet(változat, raktár)  – ha nem nulla
 *   2. termekminkeszlet(termek, raktár)            – ha nem nulla
 *   3. termekvaltozat.minkeszlet                   – ha nem nulla
 *   4. termek.minkeszlet                           – ahogy van
 *
 * $raktarid nélkül a létra a 3-4. lépés (globális minimum) – ezt látja a backorder is, ami
 * szándékosan sosem ad raktárat. A 2. és 4. lépés csak a változat nélküli termékeknél él:
 * változatos terméken a termékszintű minimum kötelezően nulla (lásd termekController és
 * \Services\KeszletszintExcelService).
 *
 * Ugyanennek a létrának a másik (SQL-be írt) implementációja a getMinKeszletSql().
 *
 * Statikus, mert entitásmetódusból is hívjuk, ahol nincs hova injektálni – ugyanezért nyúl
 * az entitás a \mkw\store::isFoglalas()-hoz.
 */
class KeszletService
{

    /** [raktarid][termekid] => érték|null – kérésen belüli cache */
    private static $termekCache = [];

    /** [raktarid][valtozatid] => érték|null */
    private static $valtozatCache = [];

    /** kulcs => ['keszlet' => …, 'mozgasdb' => …] */
    private static $keszletCache = [];

    /** kulcs => foglalt mennyiség */
    private static $foglalasCache = [];

    /** kulcs => még beérkezésre váró mennyiség */
    private static $erkezikCache = [];

    /** webshopnum => visible warehouse ids|null - per-request cache */
    private static $webshopRaktarCache = [];

    /** A szabad készlet számítási módjai (Beállítások, \mkw\consts::SzabadKeszletModszer). */
    public const SZABADKESZLET_FOGLALAS = 0;
    public const SZABADKESZLET_MINKESZLET = 1;

    /** a Beállítás kérésen belüli cache-e: a hiányzó paraméter sort az identity map nem jegyzi meg */
    private static $szabadKeszletModszer;

    /** A szabad készletből a min. készletet is levonjuk-e (a 2026-09-21 előtti számítás). */
    public static function isSzabadKeszletMinkeszlettel(): bool
    {
        self::$szabadKeszletModszer ??= (int)\mkw\store::getParameter(
            \mkw\consts::SzabadKeszletModszer,
            self::SZABADKESZLET_FOGLALAS
        );
        return self::$szabadKeszletModszer === self::SZABADKESZLET_MINKESZLET;
    }

    /** A szabad készlet képlete a felületi feliratokhoz. */
    public static function getSzabadKeszletFelirat(): string
    {
        return self::isSzabadKeszletMinkeszlettel() ? 'Készlet − min. készlet − foglalás' : 'Készlet − foglalás';
    }

    /**
     * @param \Entities\Termek|null $termek
     * @param \Entities\TermekValtozat|null $valtozat
     * @param int|null $raktarid
     *
     * @return mixed a létra szerint érvényes minimum – lehet null is (a hívók kivonják)
     */
    public static function getMinKeszlet($termek, $valtozat = null, $raktarid = null)
    {
        if ($raktarid) {
            $ertek = self::getRaktariErtek($termek, $valtozat, $raktarid);
            if (!is_null($ertek)) {
                return $ertek;
            }
        }
        // a decimal stringként hidratál, ezért a "nem nulla" teszt numerikus
        if ($valtozat && ($valtozat->getMinkeszlet() * 1)) {
            return $valtozat->getMinkeszlet();
        }
        return $termek?->getMinkeszlet();
    }

    /**
     * @param \Entities\Termek|\Entities\TermekValtozat $entity
     */
    public static function getKeszlet($entity, $datum = null, $raktarid = null, $nonegativ = false)
    {
        $keszlet = self::calcKeszletInfo($entity, $datum, $raktarid)['keszlet'];
        return ($nonegativ && $keszlet < 0) ? 0 : $keszlet;
    }

    /**
     * A termék vagy változat készlete, foglalása és érkező mennyisége raktáranként, az aktív
     * raktárakra – a készlet részletezők (terméklista, termék karbantartó, bizonylattétel)
     * közös adatforrása.
     *
     * @param \Entities\Termek|\Entities\TermekValtozat $entity
     *
     * @return array<int, array{raktarnev: string, keszlet: mixed, foglalt: mixed, erkezik: mixed}>
     */
    /**
     * Raktáranként készlet, foglalás, szabad készlet (getFreeStock()) és érkező mennyiség.
     * A szabad készlet nincs nullára vágva, hogy a hiány is látsszon.
     *
     * @param \Entities\Termek|\Entities\TermekValtozat $entity
     *
     * @return array<int, array{raktarid: int, raktarnev: string, keszlet: mixed, foglalt: mixed, szabad: mixed, erkezik: mixed}>
     */
    public static function getKeszletByRaktar($entity)
    {
        $res = [];
        foreach (\mkw\store::getEm()->getRepository(Raktar::class)->getAllActive() as $raktar) {
            $res[] = [
                'raktarid' => $raktar->getId(),
                'raktarnev' => $raktar->getNev(),
                'keszlet' => self::getKeszlet($entity, null, $raktar->getId()),
                'foglalt' => self::getFoglaltMennyiseg($entity, null, null, $raktar->getId()),
                'szabad' => self::getFreeStock($entity, null, $raktar->getId()),
                'erkezik' => self::getIncomingStock($entity, null, $raktar->getId()),
            ];
        }
        return $res;
    }

    /**
     * @param \Entities\Termek|\Entities\TermekValtozat $entity
     */
    public static function getMozgasDb($entity, $datum = null, $raktarid = null)
    {
        return self::calcKeszletInfo($entity, $datum, $raktarid)['mozgasdb'];
    }

    private static function calcKeszletInfo($entity, $datum, $raktarid): array
    {
        $kulcs = self::cacheKey($entity, $datum, $raktarid);
        if (!array_key_exists($kulcs, self::$keszletCache)) {
            $filter = self::entityFilter($entity);
            self::addKeszletFilter($filter, $datum, $raktarid);
            $sor = self::sumMozgas($filter);
            self::$keszletCache[$kulcs] = [
                'keszlet' => $sor['mennyiseg'],
                'mozgasdb' => $sor['mozgasdb'],
            ];
        }
        return self::$keszletCache[$kulcs];
    }

    /**
     * A készletlekérdezés feltételei az entitás azonosítóján kívül – a soronkénti és a
     * kötegelt (preloadStock) ág ugyanezt használja, hogy a kettő ne csússzon szét.
     */
    private static function addKeszletFilter(FilterDescriptor $filter, $datum, $raktarid): void
    {
        $filter->addFilter('bt.mozgat', '=', 1);
        $filter->addSql('((bt.rontott = 0) OR (bt.rontott IS NULL))');
        $filter->addFilter('bf.teljesites', '<=', $datum ?: new \DateTime());
        self::addRaktarFilter($filter, $raktarid);
    }

    /**
     * The one place warehouse filtering happens. $raktarid may be a single id, an array of ids,
     * or null; on null the storefront narrows to the warehouses visible in the webshop, admin
     * does not.
     */
    private static function addRaktarFilter(FilterDescriptor $filter, $raktarid): void
    {
        $raktarid = self::resolveRaktar($raktarid);
        if (is_array($raktarid)) {
            $filter->addFilter('bf.raktar_id', 'IN', $raktarid);
        } elseif ($raktarid) {
            $filter->addFilter('bf.raktar_id', '=', $raktarid);
        }
    }

    /**
     * An explicitly requested warehouse always wins. Without one we only narrow on the
     * storefront, because admin wants to see every warehouse's stock. getWebshopRaktarIds()
     * returns null when every warehouse is visible, and then nothing is filtered at all.
     *
     * The cache keys are built from the raw $raktarid, not from this resolved value - within a
     * request the mode and the webshop are constant, so null always resolves the same way, and
     * the preload keys stay aligned with the per-row ones.
     *
     * @return int|int[]|null
     */
    private static function resolveRaktar($raktarid)
    {
        if ($raktarid || !\mkw\store::isMainMode()) {
            return $raktarid;
        }
        return self::getWebshopRaktarIds();
    }

    /**
     * Ids of the warehouses that contribute stock in the webshop, null if all of them do. Any
     * caller can pass the result as the service's warehouse parameter, which is how an
     * admin-side feed or export can be narrowed to one webshop.
     *
     * @return int[]|null
     */
    public static function getWebshopRaktarIds($webshopnum = null): ?array
    {
        $kulcs = (string)($webshopnum ?: \mkw\store::getWebshopNum());
        if (!array_key_exists($kulcs, self::$webshopRaktarCache)) {
            self::$webshopRaktarCache[$kulcs] = \mkw\store::getEm()
                ->getRepository(Raktar::class)
                ->getWebshopRaktarIds($webshopnum);
        }
        return self::$webshopRaktarCache[$kulcs];
    }

    /**
     * Ugyanaz a feltételhalmaz nyers SQL-fragmentumként, a bizonylattétel `bt` és a
     * bizonylatfej `bf` aliasra. A FIFO számítás (`Services\FifoService`) ezt használja,
     * hogy a két készletmodell mennyisége soha ne csússzon szét: amit itt átírunk, az
     * automatikusan átüt a FIFO-ra is.
     *
     * A stornó bizonylatokra szándékosan NEM szűrünk: a stornó egy normál, ellentétes
     * előjelű mozgás a folyamban, a kihagyása elrontaná az összeget.
     */
    public static function getKeszletWhereSql(bool $datum = false, bool $raktar = false): string
    {
        $felt = ['bt.mozgat = 1', '((bt.rontott = 0) OR (bt.rontott IS NULL))'];
        if ($datum) {
            $felt[] = 'bf.teljesites <= :fifodatum';
        }
        if ($raktar) {
            $felt[] = 'bf.raktar_id = :fiforaktar';
        }
        return implode(' AND ', $felt);
    }

    /**
     * A készlet- és a foglaláslekérdezés közös törzse: előjeles összeg és mozgásszám.
     */
    private static function sumMozgas(FilterDescriptor $filter): array
    {
        $rsm = new ResultSetMapping();
        $rsm->addScalarResult('mennyiseg', 'mennyiseg');
        $rsm->addScalarResult('mozgasdb', 'mozgasdb');

        $q = \mkw\store::getEm()->createNativeQuery(
            'SELECT SUM(bt.mennyiseg * bt.irany) AS mennyiseg, COUNT(*) AS mozgasdb'
            . ' FROM bizonylattetel bt'
            . ' LEFT OUTER JOIN bizonylatfej bf ON (bt.bizonylatfej_id=bf.id)'
            . $filter->getFilterString()
            ,
            $rsm
        );
        $q->setParameters($filter->getQueryParameters());
        $d = $q->getScalarResult();

        return [
            'mennyiseg' => $d[0]['mennyiseg'] ?? 0,
            'mozgasdb' => $d[0]['mozgasdb'] ?? 0,
        ];
    }

    /**
     * Every product / variant with a non-zero stock in the warehouse on the date, on the same
     * conditions as getKeszlet(). A product with variants comes as its variants; its lines without
     * a variant are left out, the same way the inventory sheet lists only the variants.
     *
     * @return array<int, array{termekid: int, valtozatid: int|null, keszlet: float}>
     */
    public static function getNonZeroStockList($datum, $raktarid): array
    {
        $filter = new FilterDescriptor();
        self::addKeszletFilter($filter, $datum, $raktarid);
        $filter->addSql('bt.termek_id IS NOT NULL');
        $filter->addSql(
            '((bt.termekvaltozat_id IS NOT NULL)'
            . ' OR NOT EXISTS (SELECT 1 FROM termekvaltozat tvx WHERE tvx.termek_id=bt.termek_id))'
        );

        $rsm = new ResultSetMapping();
        $rsm->addScalarResult('termekid', 'termekid');
        $rsm->addScalarResult('valtozatid', 'valtozatid');
        $rsm->addScalarResult('mennyiseg', 'mennyiseg');
        $q = \mkw\store::getEm()->createNativeQuery(
            'SELECT bt.termek_id AS termekid, bt.termekvaltozat_id AS valtozatid, SUM(bt.mennyiseg * bt.irany) AS mennyiseg'
            . ' FROM bizonylattetel bt'
            . ' LEFT OUTER JOIN bizonylatfej bf ON (bt.bizonylatfej_id=bf.id)'
            . $filter->getFilterString()
            . ' GROUP BY bt.termek_id, bt.termekvaltozat_id'
            . ' HAVING SUM(bt.mennyiseg * bt.irany) <> 0',
            $rsm
        );
        $q->setParameters($filter->getQueryParameters());

        $ret = [];
        foreach ($q->getScalarResult() as $sor) {
            $ret[] = [
                'termekid' => (int)$sor['termekid'],
                'valtozatid' => $sor['valtozatid'] ? (int)$sor['valtozatid'] : null,
                'keszlet' => (float)$sor['mennyiseg'],
            ];
        }
        return $ret;
    }

    private static function entityFilter($entity): FilterDescriptor
    {
        $filter = new FilterDescriptor();
        $filter->addFilter(self::idMezo($entity), '=', $entity->getId());
        return $filter;
    }

    /**
     * A sumMozgas() kötegelt párja: ugyanaz az összeg és mozgásszám, de egyszerre sok
     * termékre/változatra, azonosítónként csoportosítva.
     *
     * @return array<int, array{mennyiseg: mixed, mozgasdb: mixed}>
     */
    private static function sumMozgasByEntity(string $mezo, array $ids, FilterDescriptor $filter): array
    {
        $filter->addFilter($mezo, 'IN', $ids);

        $rsm = new ResultSetMapping();
        $rsm->addScalarResult('entityid', 'entityid');
        $rsm->addScalarResult('mennyiseg', 'mennyiseg');
        $rsm->addScalarResult('mozgasdb', 'mozgasdb');

        $q = \mkw\store::getEm()->createNativeQuery(
            'SELECT ' . $mezo . ' AS entityid, SUM(bt.mennyiseg * bt.irany) AS mennyiseg, COUNT(*) AS mozgasdb'
            . ' FROM bizonylattetel bt'
            . ' LEFT OUTER JOIN bizonylatfej bf ON (bt.bizonylatfej_id=bf.id)'
            . $filter->getFilterString()
            . ' GROUP BY ' . $mezo
            ,
            $rsm
        );
        $q->setParameters($filter->getQueryParameters());

        $ret = [];
        foreach ($q->getScalarResult() as $sor) {
            $ret[(int)$sor['entityid']] = [
                'mennyiseg' => $sor['mennyiseg'] ?? 0,
                'mozgasdb' => $sor['mozgasdb'] ?? 0,
            ];
        }
        return $ret;
    }

    private static function idMezo($entity): string
    {
        return 'bt.' . self::idOszlop($entity);
    }

    /**
     * Ismeretlen típusra inkább elhasalunk: szűrő nélkül az egész bizonylattetel tábla összegződne.
     */
    private static function idOszlop($entity): string
    {
        if ($entity instanceof Termek) {
            return 'termek_id';
        }
        if ($entity instanceof TermekValtozat) {
            return 'termekvaltozat_id';
        }
        throw new \InvalidArgumentException(
            'Termek vagy TermekValtozat kell, kapott: ' . get_debug_type($entity)
        );
    }

    /**
     * Az entitás azonossága a kulcs, nem az állapota – a proxy és a betöltött entitás
     * ugyanarra a sorra ugyanazt a kulcsot adja. $datum nélkül a "most" a kérésen belül
     * befagy; a mozgásokat író flush a BizonylattetelListener-ből üríti a cache-t.
     */
    private static function cacheKey($entity, ...$extra): string
    {
        return self::cacheKeyFor(self::idMezo($entity), $entity->getId(), ...$extra);
    }

    private static function cacheKeyFor(string $mezo, $id, ...$extra): string
    {
        $parts = [$mezo, $id];
        foreach ($extra as $e) {
            if (is_array($e)) {
                $parts[] = implode(',', $e);
                continue;
            }
            $parts[] = $e instanceof \DateTimeInterface ? $e->format('Y-m-d H:i:s') : (string)$e;
        }
        return implode('|', $parts);
    }

    /**
     * Szabad készlet: készlet − foglalt, a Beállítások szerint (isSzabadKeszletMinkeszlettel())
     * a min. készlettel is csökkentve. Nincs nullára vágva: a hiány is látsszon.
     *
     * @param \Entities\Termek|\Entities\TermekValtozat $entity
     * @param \Entities\Bizonylatfej|int|null $kivevebiz ezt a bizonylatot nem számítjuk a foglalásba
     */
    public static function getFreeStock($entity, $datum = null, $raktarid = null, $kivevebiz = null)
    {
        if (!$entity) {
            return 0;
        }
        $ret = self::getKeszlet($entity, $datum, $raktarid)
            - self::getFoglaltMennyiseg($entity, $kivevebiz, $datum, $raktarid);
        if (self::isSzabadKeszletMinkeszlettel()) {
            $valtozat = $entity instanceof TermekValtozat ? $entity : null;
            $ret -= self::getMinKeszlet($valtozat ? $valtozat->getTermek() : $entity, $valtozat, $raktarid);
        }
        return $ret;
    }

    /**
     * A webshopon eladható mennyiség: készlet − foglalt − min. bolti készlet, $clamp esetén
     * nullára vágva. A szabad készlet beállítása erre nem hat, lásd getFreeStock().
     *
     * @param bool $ignoreminkeszlet a nominkeszlet kapcsolóhoz – csak a BackorderService adja át
     * @param bool $ignorefoglalas a nyers raktárkészletet néző riportoknak
     */
    public static function calcAvailableStock(
        $termek,
        $valtozat = null,
        $datum = null,
        $raktarid = null,
        $kivevebiz = null,
        $clamp = true,
        $ignoreminkeszlet = false,
        $ignorefoglalas = false
    ) {
        $o = $valtozat ?: $termek;
        if (!$o) {
            return 0;
        }
        $keszlet = self::getKeszlet($o, $datum, $raktarid);
        if (!$ignorefoglalas) {
            $keszlet -= self::getFoglaltMennyiseg($o, $kivevebiz, $datum, $raktarid);
        }
        if (!$ignoreminkeszlet) {
            $keszlet -= self::getMinKeszlet($termek, $valtozat, $raktarid);
        }
        if ($clamp) {
            $keszlet = max($keszlet, 0);
        }
        return $keszlet;
    }

    /**
     * A termékhez felkínálható egyedi azonosítók: ami készleten van, és ami érkezik (érkezik
     * jelölésű tételen szerepel, de még nem jött meg). A megérkezett darab a készletes ágon jön
     * vissza, az eladott egyiken sem.
     *
     * @param \Entities\Termek $termek
     * @param int|null $valtozatid csak az adott változat azonosítói
     * @param string $term LIKE szűrő az azonosítóra (autocomplete)
     * @param int|null $raktarid csak az adott raktár készlete
     *
     * @return array<int, array{azonosito: string, erkezik: bool}>
     */
    public static function getEgyediazonositoKeszlet($termek, $valtozatid = null, $term = '', $raktarid = null)
    {
        $params = [
            'termekid' => $termek->getId(),
            'term' => '%' . $term . '%',
        ];
        $kozos = ' WHERE bt.termek_id = :termekid'
            . ' AND ((bt.rontott = 0) OR (bt.rontott IS NULL))'
            . ' AND bt.termekegyediazonosito IS NOT NULL'
            . " AND bt.termekegyediazonosito <> ''"
            . ' AND bt.termekegyediazonosito LIKE :term';
        if ($valtozatid) {
            $kozos .= ' AND bt.termekvaltozat_id = :valtozatid';
            $params['valtozatid'] = $valtozatid;
        }
        if ($raktarid) {
            $kozos .= ' AND bf.raktar_id = :raktarid';
            $params['raktarid'] = $raktarid;
        }

        $keszleten = 'SELECT bt.termekegyediazonosito AS azonosito'
            . ' FROM bizonylattetel bt'
            . ' LEFT OUTER JOIN bizonylatfej bf ON (bt.bizonylatfej_id = bf.id)'
            . $kozos
            . ' AND bt.mozgat = 1'
            . ' GROUP BY bt.termekegyediazonosito'
            . ' HAVING SUM(bt.mennyiseg * bt.irany) > 0';

        // az érkező tétel nem mozgat készletet, ezért a fenti lekérdezésbe soha nem fér bele;
        // a NOT EXISTS azt zárja ki, ami időközben már megjött vagy el is fogyott
        $erkezik = 'SELECT DISTINCT bt.termekegyediazonosito AS azonosito'
            . ' FROM bizonylattetel bt'
            . ' JOIN bizonylatfej bf ON (bt.bizonylatfej_id = bf.id)'
            . $kozos
            . ' AND bt.erkezik = 1'
            . ' AND NOT EXISTS (SELECT 1 FROM bizonylattetel m WHERE m.termek_id = bt.termek_id'
            . ' AND m.termekegyediazonosito = bt.termekegyediazonosito AND m.mozgat = 1'
            . ' AND ((m.rontott = 0) OR (m.rontott IS NULL)))';

        $ret = [];
        foreach (self::egyediazonositoSorok($keszleten, $params) as $azonosito) {
            $ret[$azonosito] = ['azonosito' => $azonosito, 'erkezik' => false];
        }
        foreach (self::egyediazonositoSorok($erkezik, $params) as $azonosito) {
            $ret[$azonosito] ??= ['azonosito' => $azonosito, 'erkezik' => true];
        }
        uksort($ret, 'strnatcasecmp');
        return array_values($ret);
    }

    /** @return string[] */
    private static function egyediazonositoSorok($sql, array $params): array
    {
        $rsm = new ResultSetMapping();
        $rsm->addScalarResult('azonosito', 'azonosito');
        $q = \mkw\store::getEm()->createNativeQuery($sql, $rsm);
        $q->setParameters($params);
        $ret = [];
        foreach ($q->getScalarResult() as $r) {
            $ret[] = $r['azonosito'];
        }
        return $ret;
    }

    /**
     * @param \Entities\Termek|\Entities\TermekValtozat $entity
     * @param \Entities\Bizonylatfej|int|null $kivevebiz ezt a bizonylatot nem számítjuk bele
     */
    public static function getFoglaltMennyiseg($entity, $kivevebiz = null, $datum = null, $raktarid = null)
    {
        if ($kivevebiz instanceof Bizonylatfej) {
            $kivevebiz = $kivevebiz->getId();
        }
        return self::calcFoglalas($entity, $kivevebiz, $datum, $raktarid);
    }

    private static function calcFoglalas($entity, $kivevebiz, $datum, $raktarid)
    {
        $foglalotipusok = Bizonylattipus::getFoglalIdList();
        if (!$foglalotipusok) {
            return 0;
        }
        $kulcs = self::cacheKey($entity, $kivevebiz, $datum, $raktarid);
        if (!array_key_exists($kulcs, self::$foglalasCache)) {
            $filter = self::entityFilter($entity);
            self::addFoglalasFilter($filter, $foglalotipusok, $kivevebiz, $datum, $raktarid);
            self::$foglalasCache[$kulcs] = self::sumMozgas($filter)['mennyiseg'] * -1;
        }
        return self::$foglalasCache[$kulcs];
    }

    private static function addFoglalasFilter(
        FilterDescriptor $filter,
        array $foglalotipusok,
        $kivevebiz,
        $datum,
        $raktarid
    ): void {
        $filter->addFilter('bt.foglal', '=', 1);
        $filter->addSql('((bt.rontott = 0) OR (bt.rontott IS NULL))');
        $filter->addFilter('bf.teljesites', '<=', $datum ?: new \DateTime());
        $filter->addFilter('bf.bizonylattipus_id', 'IN', $foglalotipusok);
        if ($kivevebiz) {
            $filter->addFilter('bf.id', '<>', $kivevebiz);
        }
        self::addRaktarFilter($filter, $raktarid);
    }

    /**
     * A még beérkezésre váró mennyiség: az „érkezik" státuszú bizonylatokon szereplő mennyiség
     * mínusz az, ami ezekre a bizonylatokra társbizonylatként hivatkozó bizonylatokon (tipikusan
     * a bevéteken) már megjött. A tétel `erkezik` mezője származtatott, lásd
     * \Entities\Bizonylattetel::setErkezik().
     *
     * Nincs nullára vágva: a túlszállítás negatív számként látszik, ahogy a szabad készletnél is.
     *
     * @param \Entities\Termek|\Entities\TermekValtozat $entity
     */
    public static function getIncomingStock($entity, $datum = null, $raktarid = null)
    {
        $kulcs = self::cacheKey($entity, $datum, $raktarid);
        if (!array_key_exists($kulcs, self::$erkezikCache)) {
            $rendelt = self::entityFilter($entity);
            $rendelt->addFilter('bt.erkezik', '=', 1);
            self::addErkezikCommonFilter($rendelt, $datum, $raktarid);

            $megjott = self::entityFilter($entity);
            self::addMegjottFilter($megjott, self::idOszlop($entity));
            self::addErkezikCommonFilter($megjott, $datum, $raktarid);

            self::$erkezikCache[$kulcs] = self::sumMozgas($rendelt)['mennyiseg']
                - self::sumMozgas($megjott)['mennyiseg'];
        }
        return self::$erkezikCache[$kulcs];
    }

    /**
     * A foglalást adó bizonylatok, bizonylatonként a foglalt mennyiséggel. A szűrés a
     * getFoglaltMennyiseg()-é, így a sorok összege a foglalt mennyiség.
     *
     * @param \Entities\Termek|\Entities\TermekValtozat $entity
     *
     * @return array<int, array{id: string, tipusid: string, kelt: string|null, partnernev: string|null, mennyiseg: float}>
     */
    public static function getFoglaloBizonylatok($entity, $raktarid = null): array
    {
        $foglalotipusok = Bizonylattipus::getFoglalIdList();
        if (!$foglalotipusok) {
            return [];
        }
        $filter = self::entityFilter($entity);
        self::addFoglalasFilter($filter, $foglalotipusok, null, null, $raktarid);
        return self::listBizonylatok($filter, -1);
    }

    /**
     * Az érkezést nyilvántartó bizonylatok a még várt mennyiséggel (rendelt − a társbizonylatokon
     * már megjött). A szűrés a getIncomingStock()-é, a teljesen megjött bizonylat kimarad.
     *
     * @param \Entities\Termek|\Entities\TermekValtozat $entity
     *
     * @return array<int, array{id: string, tipusid: string, kelt: string|null, partnernev: string|null, mennyiseg: float}>
     */
    public static function getErkeztetoBizonylatok($entity, $raktarid = null): array
    {
        $rendelt = self::entityFilter($entity);
        $rendelt->addFilter('bt.erkezik', '=', 1);
        self::addErkezikCommonFilter($rendelt, null, $raktarid);
        $sorok = self::listBizonylatok($rendelt, 1);
        if (!$sorok) {
            return [];
        }

        $megjott = self::entityFilter($entity);
        self::addMegjottFilter($megjott, self::idOszlop($entity));
        self::addErkezikCommonFilter($megjott, null, $raktarid);
        $megjottsorok = self::sumMozgasByTarsbizonylat($megjott);

        $ret = [];
        foreach ($sorok as $sor) {
            $sor['mennyiseg'] -= $megjottsorok[$sor['id']] ?? 0;
            if ($sor['mennyiseg'] != 0) {
                $ret[] = $sor;
            }
        }
        return $ret;
    }

    /**
     * Bizonylatonként összegzett mozgás: a sumMozgas() bizonylatfejre csoportosított párja.
     *
     * @param int $elojel −1 a foglalásnál, hogy a kimenő mennyiség pozitívként jelenjen meg
     */
    private static function listBizonylatok(FilterDescriptor $filter, int $elojel): array
    {
        $rsm = new ResultSetMapping();
        $rsm->addScalarResult('id', 'id');
        $rsm->addScalarResult('tipusid', 'tipusid');
        $rsm->addScalarResult('kelt', 'kelt');
        $rsm->addScalarResult('partnernev', 'partnernev');
        $rsm->addScalarResult('mennyiseg', 'mennyiseg');

        $q = \mkw\store::getEm()->createNativeQuery(
            'SELECT bf.id AS id, bf.bizonylattipus_id AS tipusid, bf.kelt AS kelt, bf.partnernev AS partnernev,'
            . ' SUM(bt.mennyiseg * bt.irany) * ' . $elojel . ' AS mennyiseg'
            . ' FROM bizonylattetel bt'
            . ' LEFT OUTER JOIN bizonylatfej bf ON (bt.bizonylatfej_id=bf.id)'
            . $filter->getFilterString()
            . ' GROUP BY bf.id, bf.bizonylattipus_id, bf.kelt, bf.partnernev'
            . ' ORDER BY bf.kelt ASC, bf.id ASC'
            ,
            $rsm
        );
        $q->setParameters($filter->getQueryParameters());

        $ret = [];
        foreach ($q->getScalarResult() as $sor) {
            $sor['mennyiseg'] = (float)$sor['mennyiseg'];
            $ret[] = $sor;
        }
        return $ret;
    }

    /**
     * @return array<string, float> rendelő bizonylatszám => a rá hivatkozó társbizonylatokon megjött mennyiség
     */
    private static function sumMozgasByTarsbizonylat(FilterDescriptor $filter): array
    {
        $rsm = new ResultSetMapping();
        $rsm->addScalarResult('tars', 'tars');
        $rsm->addScalarResult('mennyiseg', 'mennyiseg');

        $q = \mkw\store::getEm()->createNativeQuery(
            'SELECT bf.tarsbizonylat_id AS tars, SUM(bt.mennyiseg * bt.irany) AS mennyiseg'
            . ' FROM bizonylattetel bt'
            . ' LEFT OUTER JOIN bizonylatfej bf ON (bt.bizonylatfej_id=bf.id)'
            . $filter->getFilterString()
            . ' GROUP BY bf.tarsbizonylat_id'
            ,
            $rsm
        );
        $q->setParameters($filter->getQueryParameters());

        $ret = [];
        foreach ($q->getScalarResult() as $sor) {
            $ret[$sor['tars']] = (float)$sor['mennyiseg'];
        }
        return $ret;
    }

    private static function addErkezikCommonFilter(FilterDescriptor $filter, $datum, $raktarid): void
    {
        $filter->addSql('((bt.rontott = 0) OR (bt.rontott IS NULL))');
        $filter->addFilter('bf.teljesites', '<=', $datum ?: new \DateTime());
        self::addRaktarFilter($filter, $raktarid);
    }

    /**
     * A már megjött mennyiség sorai: csak azok a társbizonylatok, amelyek éppen ennek a
     * terméknek/változatnak az érkezését zárják le. A sor saját azonosítójára korrelál, ezért
     * az egy entitásos és a kötegelt (preloadStock) ág ugyanezt használja.
     */
    private static function addMegjottFilter(FilterDescriptor $filter, string $oszlop): void
    {
        $filter->addSql(
            'bf.tarsbizonylat_id IN (SELECT ebt.bizonylatfej_id FROM bizonylattetel ebt'
            . ' WHERE (ebt.erkezik = 1) AND (ebt.' . $oszlop . ' = bt.' . $oszlop . '))'
        );
    }

    /** A terméklista készletszűrőjének mértékei. */
    public const SZURO_MEZOK = ['keszlet', 'szabad', 'foglalt', 'erkezik'];

    /**
     * Azok a termékek, amelyeknek a termékszintű (az összes változatot összegző) készlete,
     * szabad készlete, foglalása vagy érkező mennyisége a feltételnek megfelel – a terméklista
     * készletszűrőjéhez. A szűrések a soronkénti számítás (getKeszlet(), getFoglaltMennyiseg(),
     * getFreeStock(), getIncomingStock()) natív SQL párjai. A szabad készletből a beállítás szerint
     * a termékszintű min. készlet is levonódik, ezért a mozgás nélküli termék is lehet nem nulla;
     * a nulla feltételt a hívó `<> 0`-val és NOT IN-nel kérdezi.
     *
     * @param string $mezo a SZURO_MEZOK egyike
     * @param string $relacio `>`, `<` vagy `<>` (nullához képest)
     * @param int|null $raktarid null esetén minden raktár együtt (céges szint)
     *
     * @return int[]
     */
    public static function getTermekIdsByKeszlet(string $mezo, string $relacio, $raktarid = null): array
    {
        if (!in_array($mezo, self::SZURO_MEZOK, true) || !in_array($relacio, ['>', '<', '<>'], true)) {
            throw new \InvalidArgumentException('Ismeretlen készletszűrő: ' . $mezo . ' ' . $relacio);
        }
        $params = ['most' => (new \DateTime())->format('Y-m-d H:i:s')];
        $foglalo = [];
        foreach (array_values(Bizonylattipus::getFoglalIdList()) as $i => $tipus) {
            $foglalo[] = ':ft' . $i;
            $params['ft' . $i] = $tipus;
        }
        $mozgat = 'bt.mozgat = 1';
        $foglal = $foglalo ? '(bt.foglal = 1 AND bf.bizonylattipus_id IN (' . implode(',', $foglalo) . '))' : '(1 = 0)';
        $rendelt = 'bt.erkezik = 1';
        // az addMegjottFilter() termékszintű párja, EXISTS-szel, hogy a fej indexét használja
        $megjott = 'EXISTS (SELECT 1 FROM bizonylattetel ebt WHERE ebt.bizonylatfej_id = bf.tarsbizonylat_id'
            . ' AND ebt.erkezik = 1 AND ebt.termek_id = bt.termek_id)';
        $osszeg = static fn($feltetel) => 'SUM(CASE WHEN ' . $feltetel . ' THEN bt.mennyiseg * bt.irany ELSE 0 END)';
        [$szukites, $ertek] = match ($mezo) {
            'keszlet' => [$mozgat, $osszeg($mozgat)],
            'foglalt' => [$foglal, '-' . $osszeg($foglal)],
            // készlet − foglalt, és a foglalás kimenő (negatív) mozgás: a kettő összege
            'szabad' => ['(' . $mozgat . ' OR ' . $foglal . ')', $osszeg($mozgat) . ' + ' . $osszeg($foglal)],
            'erkezik' => ['(' . $rendelt . ' OR ' . $megjott . ')', $osszeg($rendelt) . ' - ' . $osszeg($megjott)],
        };
        $raktarparam = $raktarid ? 'raktar' : '';
        $levonas = ($mezo === 'szabad' && self::isSzabadKeszletMinkeszlettel())
            ? ' - ' . self::getMinKeszletSql('t.id', 't.minkeszlet', '', '', $raktarparam)
            : '';
        $sql = 'SELECT t.id AS id FROM termek t'
            . ' LEFT JOIN (SELECT bt.termek_id AS tid, ' . $ertek . ' AS ertek FROM bizonylattetel bt'
            . ' LEFT OUTER JOIN bizonylatfej bf ON (bt.bizonylatfej_id = bf.id)'
            . ' WHERE ((bt.rontott = 0) OR (bt.rontott IS NULL)) AND (bf.teljesites <= :most) AND ' . $szukites
            . ($raktarparam ? ' AND (bf.raktar_id = :raktar)' : '')
            . ' GROUP BY bt.termek_id) m ON (m.tid = t.id)'
            . ' WHERE (COALESCE(m.ertek, 0)' . $levonas . ') ' . $relacio . ' 0';
        if ($raktarparam) {
            $params['raktar'] = $raktarid;
        }
        $rsm = new ResultSetMapping();
        $rsm->addScalarResult('id', 'id');
        $q = \mkw\store::getEm()->createNativeQuery($sql, $rsm);
        $q->setParameters($params);
        return array_map('intval', array_column($q->getScalarResult(), 'id'));
    }

    /**
     * Egy listaoldal készlete, foglalása és érkező mennyisége hat lekérdezéssel. Enélkül a soronkénti
     * getKeszlet()/getFoglaltMennyiseg() termékenként és változatonként külön SUM-ot indít:
     * a terméklistán ez 30 termékre több száz kérdés volt.
     *
     * A cache kulcsa ugyanaz, amit a soronkénti hívás képez, ezért a $datum/$raktarid/$kivevebiz
     * hármasnak egyeznie kell azzal, ahogy utána kérdezik – a listák raktár és dátum nélkül
     * kérdezik, ezért annak az alapesetnek szól.
     */
    public static function preloadStock(array $termekids, array $valtozatids, $datum = null, $raktarid = null): void
    {
        self::preloadKeszlet('bt.termek_id', $termekids, $datum, $raktarid);
        self::preloadKeszlet('bt.termekvaltozat_id', $valtozatids, $datum, $raktarid);
        self::preloadFoglalas('bt.termek_id', $termekids, $datum, $raktarid);
        self::preloadFoglalas('bt.termekvaltozat_id', $valtozatids, $datum, $raktarid);
        self::preloadErkezik('bt.termek_id', $termekids, $datum, $raktarid);
        self::preloadErkezik('bt.termekvaltozat_id', $valtozatids, $datum, $raktarid);
    }

    private static function preloadKeszlet(string $mezo, array $ids, $datum, $raktarid): void
    {
        $keresendo = self::getUncached($mezo, $ids, self::$keszletCache, [$datum, $raktarid]);
        if (!$keresendo) {
            return;
        }
        $filter = new FilterDescriptor();
        self::addKeszletFilter($filter, $datum, $raktarid);
        $sorok = self::sumMozgasByEntity($mezo, $keresendo, $filter);
        foreach ($keresendo as $id) {
            // a mozgás nélküli termékre is írunk, különben soronként újra megkérdeznénk
            $kulcs = self::cacheKeyFor($mezo, $id, $datum, $raktarid);
            self::$keszletCache[$kulcs] = [
                'keszlet' => $sorok[$id]['mennyiseg'] ?? 0,
                'mozgasdb' => $sorok[$id]['mozgasdb'] ?? 0,
            ];
        }
    }

    private static function preloadFoglalas(string $mezo, array $ids, $datum, $raktarid): void
    {
        $foglalotipusok = Bizonylattipus::getFoglalIdList();
        if (!$foglalotipusok) {
            return;
        }
        $keresendo = self::getUncached($mezo, $ids, self::$foglalasCache, [null, $datum, $raktarid]);
        if (!$keresendo) {
            return;
        }
        $filter = new FilterDescriptor();
        self::addFoglalasFilter($filter, $foglalotipusok, null, $datum, $raktarid);
        $sorok = self::sumMozgasByEntity($mezo, $keresendo, $filter);
        foreach ($keresendo as $id) {
            $kulcs = self::cacheKeyFor($mezo, $id, null, $datum, $raktarid);
            self::$foglalasCache[$kulcs] = ($sorok[$id]['mennyiseg'] ?? 0) * -1;
        }
    }

    private static function preloadErkezik(string $mezo, array $ids, $datum, $raktarid): void
    {
        $keresendo = self::getUncached($mezo, $ids, self::$erkezikCache, [$datum, $raktarid]);
        if (!$keresendo) {
            return;
        }
        $rendelt = new FilterDescriptor();
        $rendelt->addFilter('bt.erkezik', '=', 1);
        self::addErkezikCommonFilter($rendelt, $datum, $raktarid);
        $rendeltsorok = self::sumMozgasByEntity($mezo, $keresendo, $rendelt);

        $megjott = new FilterDescriptor();
        self::addMegjottFilter($megjott, substr($mezo, 3));
        self::addErkezikCommonFilter($megjott, $datum, $raktarid);
        $megjottsorok = self::sumMozgasByEntity($mezo, $keresendo, $megjott);

        foreach ($keresendo as $id) {
            $kulcs = self::cacheKeyFor($mezo, $id, $datum, $raktarid);
            self::$erkezikCache[$kulcs] = ($rendeltsorok[$id]['mennyiseg'] ?? 0)
                - ($megjottsorok[$id]['mennyiseg'] ?? 0);
        }
    }

    /**
     * Sok termék/változat raktáras sorának betöltése két lekérdezéssel, hogy a soronkénti
     * getMinKeszlet() ne fusson N+1-be.
     */
    public static function preload(array $termekids, array $valtozatids, $raktarid = null): void
    {
        if (!$raktarid) {
            return;
        }
        if ($termekids) {
            self::loadTermek($termekids, $raktarid);
        }
        if ($valtozatids) {
            self::loadValtozat($valtozatids, $raktarid);
        }
    }

    public static function clearCache(): void
    {
        self::$termekCache = [];
        self::$valtozatCache = [];
        self::$webshopRaktarCache = [];
        self::$szabadKeszletModszer = null;
        self::clearKeszletCache();
    }

    /**
     * Csak a bizonylattételekből számolt részt üríti – erre a mozgásokat író flush után van szükség.
     */
    public static function clearKeszletCache(): void
    {
        self::$keszletCache = [];
        self::$foglalasCache = [];
        self::$erkezikCache = [];
    }

    /**
     * Ugyanaz a létra natív SQL-ben, riportlekérdezésekhez – a getMinKeszlet() párja, hogy a két
     * implementáció ne csússzon szét. A NULLIF a „ha nem nulla" lépcső: a DECIMAL "0.00"
     * numerikusan nulla, de nem NULL.
     *
     * Változat nélküli (csak termékszintű) ághoz a $valtozatid/$valtozatmin maradjon üres,
     * raktárfüggetlen értékhez a $raktarparam.
     *
     * @param string $termekid a termék azonosítóját adó SQL kifejezés (pl. `_xx.termek_id`)
     * @param string $termekmin a termék globális minimumát adó kifejezés (pl. `t.minkeszlet`)
     * @param string $valtozatid a változat azonosítója (pl. `_xx.id`)
     * @param string $valtozatmin a változat globális minimuma (pl. `_xx.minkeszlet`)
     * @param string $raktarparam a raktár kötött paraméterének neve, kettőspont nélkül
     *
     * @return string
     */
    public static function getMinKeszletSql(
        $termekid,
        $termekmin,
        $valtozatid = '',
        $valtozatmin = '',
        $raktarparam = ''
    ) {
        return self::getKeszletSzintSql('min', $termekid, $termekmin, $valtozatid, $valtozatmin, $raktarparam);
    }

    /** A raktáras készletszintek táblái – a létra a kettőnél ugyanaz, csak más tábla és oszlop. */
    private const SZINTEK = [
        'min' => ['termektabla' => 'termekminkeszlet', 'valtozattabla' => 'termekvaltozatminkeszlet', 'mezo' => 'minkeszlet'],
        'opt' => ['termektabla' => 'termekoptkeszlet', 'valtozattabla' => 'termekvaltozatoptkeszlet', 'mezo' => 'optkeszlet'],
    ];

    /**
     * @param string $tipus a SZINTEK kulcsa: `min` (minimum) vagy `opt` (optimális) készlet
     */
    public static function getKeszletSzintSql(
        $tipus,
        $termekid,
        $termekertek,
        $valtozatid = '',
        $valtozatertek = '',
        $raktarparam = ''
    ) {
        $szint = self::SZINTEK[$tipus];
        $agak = [];
        if ($raktarparam) {
            if ($valtozatid) {
                $agak[] = 'NULLIF((SELECT vmk.' . $szint['mezo'] . ' FROM ' . $szint['valtozattabla'] . ' vmk'
                    . ' WHERE vmk.termekvaltozat_id = ' . $valtozatid . ' AND vmk.raktar_id = :' . $raktarparam . '), 0)';
            }
            $agak[] = 'NULLIF((SELECT tmk.' . $szint['mezo'] . ' FROM ' . $szint['termektabla'] . ' tmk'
                . ' WHERE tmk.termek_id = ' . $termekid . ' AND tmk.raktar_id = :' . $raktarparam . '), 0)';
        }
        if ($valtozatertek) {
            $agak[] = 'NULLIF(' . $valtozatertek . ', 0)';
        }
        $agak[] = $termekertek;
        $agak[] = '0';
        return 'COALESCE(' . implode(',', $agak) . ')';
    }

    /**
     * A létra 1-2. lépése. null, ha egyik szinten sincs nem nulla raktáras érték.
     */
    private static function getRaktariErtek($termek, $valtozat, $raktarid)
    {
        $valtozatid = $valtozat?->getId();
        if ($valtozatid) {
            self::loadValtozat([$valtozatid], $raktarid);
            $ertek = self::$valtozatCache[$raktarid][$valtozatid] ?? null;
            if ($ertek * 1) {
                return $ertek;
            }
        }
        $termekid = $termek?->getId();
        if ($termekid) {
            self::loadTermek([$termekid], $raktarid);
            $ertek = self::$termekCache[$raktarid][$termekid] ?? null;
            if ($ertek * 1) {
                return $ertek;
            }
        }
        return null;
    }

    private static function loadTermek(array $termekids, $raktarid): void
    {
        $keresendo = self::getMissing($termekids, self::$termekCache[$raktarid] ?? []);
        if (!$keresendo) {
            return;
        }
        $sorok = \mkw\store::getEm()->getRepository(TermekMinkeszlet::class)
            ->getByTermekIds($keresendo, $raktarid);
        // a nem talált id-kre is írunk, hogy a hiány ne generáljon egyesével újabb lekérdezést
        foreach ($keresendo as $id) {
            self::$termekCache[$raktarid][$id] = $sorok[$id][$raktarid] ?? null;
        }
    }

    private static function loadValtozat(array $valtozatids, $raktarid): void
    {
        $keresendo = self::getMissing($valtozatids, self::$valtozatCache[$raktarid] ?? []);
        if (!$keresendo) {
            return;
        }
        $sorok = \mkw\store::getEm()->getRepository(TermekValtozatMinkeszlet::class)
            ->getByTermekValtozatIds($keresendo, $raktarid);
        foreach ($keresendo as $id) {
            self::$valtozatCache[$raktarid][$id] = $sorok[$id][$raktarid] ?? null;
        }
    }

    /**
     * A még be nem töltött azonosítók, az adott cache kulcsképzése szerint.
     *
     * @param array $kulcsextra a cacheKeyFor() további kulcsrészei, a hívó sorrendjében
     */
    private static function getUncached(string $mezo, array $ids, array $cache, array $kulcsextra): array
    {
        $ret = [];
        foreach (self::getMissing($ids, []) as $id) {
            if (!array_key_exists(self::cacheKeyFor($mezo, $id, ...$kulcsextra), $cache)) {
                $ret[$id] = $id;
            }
        }
        return $ret;
    }

    private static function getMissing(array $ids, array $cache): array
    {
        $ret = [];
        foreach ($ids as $id) {
            $id = (int)$id;
            if ($id && !array_key_exists($id, $cache) && !array_key_exists($id, $ret)) {
                $ret[$id] = $id;
            }
        }
        return $ret;
    }

}
