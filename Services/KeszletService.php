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
 * Készletszámítás: a bizonylattételekből összegzett raktárkészlet és foglalás, a polcon tartandó
 * minimum ("min. bolti készlet") feloldása, és a háromból a szabad készlet.
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
 * \Services\MinKeszletExcelService).
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
     * Raktáranként készlet, foglalás, szabad készlet és érkező mennyiség. A szabad készlet
     * nincs nullára vágva, hogy a hiány is látsszon.
     *
     * @param \Entities\Termek|\Entities\TermekValtozat $entity
     *
     * @return array<int, array{raktarid: int, raktarnev: string, keszlet: mixed, foglalt: mixed, szabad: mixed, erkezik: mixed}>
     */
    public static function getKeszletByRaktar($entity)
    {
        $valtozat = $entity instanceof TermekValtozat ? $entity : null;
        $termek = $valtozat ? $valtozat->getTermek() : $entity;
        $res = [];
        foreach (\mkw\store::getEm()->getRepository(Raktar::class)->getAllActive() as $raktar) {
            $res[] = [
                'raktarid' => $raktar->getId(),
                'raktarnev' => $raktar->getNev(),
                'keszlet' => self::getKeszlet($entity, null, $raktar->getId()),
                'foglalt' => self::getFoglaltMennyiseg($entity, null, null, $raktar->getId()),
                'szabad' => self::calcAvailableStock($termek, $valtozat, null, $raktar->getId(), null, false),
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
        if ($raktarid) {
            $filter->addFilter('bf.raktar_id', '=', $raktarid);
        }
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
            $parts[] = $e instanceof \DateTimeInterface ? $e->format('Y-m-d H:i:s') : (string)$e;
        }
        return implode('|', $parts);
    }

    /**
     * A szabad készlet egyetlen implementációja:
     * készlet − foglalt − minimum, $clamp esetén nullára vágva.
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
     * A termék még raktáron lévő egyedi azonosítói.
     *
     * @param \Entities\Termek $termek
     * @param int|null $valtozatid csak az adott változat azonosítói
     * @param string $term LIKE szűrő az azonosítóra (autocomplete)
     * @param int|null $raktarid csak az adott raktár készlete
     *
     * @return string[]
     */
    public static function getEgyediazonositoKeszlet($termek, $valtozatid = null, $term = '', $raktarid = null)
    {
        $rsm = new ResultSetMapping();
        $rsm->addScalarResult('azonosito', 'azonosito');

        $sql = 'SELECT bt.termekegyediazonosito AS azonosito'
            . ' FROM bizonylattetel bt'
            . ' LEFT OUTER JOIN bizonylatfej bf ON (bt.bizonylatfej_id = bf.id)'
            . ' WHERE bt.termek_id = :termekid'
            . ' AND bt.mozgat = 1'
            . ' AND ((bt.rontott = 0) OR (bt.rontott IS NULL))'
            . ' AND bt.termekegyediazonosito IS NOT NULL'
            . " AND bt.termekegyediazonosito <> ''"
            . ' AND bt.termekegyediazonosito LIKE :term';
        $params = [
            'termekid' => $termek->getId(),
            'term' => '%' . $term . '%',
        ];
        if ($valtozatid) {
            $sql .= ' AND bt.termekvaltozat_id = :valtozatid';
            $params['valtozatid'] = $valtozatid;
        }
        if ($raktarid) {
            $sql .= ' AND bf.raktar_id = :raktarid';
            $params['raktarid'] = $raktarid;
        }
        $sql .= ' GROUP BY bt.termekegyediazonosito'
            . ' HAVING SUM(bt.mennyiseg * bt.irany) > 0'
            . ' ORDER BY bt.termekegyediazonosito ASC';

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
        if ($raktarid) {
            $filter->addFilter('bf.raktar_id', '=', $raktarid);
        }
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
        if ($raktarid) {
            $filter->addFilter('bf.raktar_id', '=', $raktarid);
        }
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
        $agak = [];
        if ($raktarparam) {
            if ($valtozatid) {
                $agak[] = 'NULLIF((SELECT vmk.minkeszlet FROM termekvaltozatminkeszlet vmk'
                    . ' WHERE vmk.termekvaltozat_id = ' . $valtozatid . ' AND vmk.raktar_id = :' . $raktarparam . '), 0)';
            }
            $agak[] = 'NULLIF((SELECT tmk.minkeszlet FROM termekminkeszlet tmk'
                . ' WHERE tmk.termek_id = ' . $termekid . ' AND tmk.raktar_id = :' . $raktarparam . '), 0)';
        }
        if ($valtozatmin) {
            $agak[] = 'NULLIF(' . $valtozatmin . ', 0)';
        }
        $agak[] = $termekmin;
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
