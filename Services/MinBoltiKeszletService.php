<?php

namespace Services;

use Entities\TermekMinboltikeszlet;
use Entities\TermekValtozatMinboltikeszlet;

/**
 * A polcon tartandó minimum ("min. bolti készlet") feloldása és a szabad készlet számítása.
 *
 * A feloldási létra – raktáras érték üti a globálisat, szinten belül a termék a változatot:
 *   1. termekminboltikeszlet(termek, raktár)            – ha nem nulla
 *   2. termekvaltozatminboltikeszlet(változat, raktár)  – ha nem nulla
 *   3. termek.minboltikeszlet                           – ha nem nulla
 *   4. termekvaltozat.minboltikeszlet                   – ahogy van
 *
 * $raktarid nélkül a létra a 3-4. lépés, ami betűre a régi TermekValtozat::calcMinboltikeszlet().
 * Ugyanennek a létrának a másik (SQL-be írt) implementációja a keszletlistaController::getData().
 *
 * Statikus, mert entitásmetódusból is hívjuk, ahol nincs hova injektálni – ugyanezért nyúl
 * az entitás a \mkw\store::isFoglalas()-hoz.
 */
class MinBoltiKeszletService
{

    /** [raktarid][termekid] => érték|null – kérésen belüli cache */
    private static $termekCache = [];

    /** [raktarid][valtozatid] => érték|null */
    private static $valtozatCache = [];

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
        if ($termek && ($termek->getMinboltikeszlet() * 1)) {
            return $termek->getMinboltikeszlet();
        }
        if ($valtozat) {
            return $valtozat->getMinboltikeszlet();
        }
        return $termek?->getMinboltikeszlet();
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
        $keszlet = $o->getKeszlet($datum, $raktarid);
        if (!$ignorefoglalas) {
            $keszlet -= $o->getFoglaltMennyiseg($kivevebiz, $datum, $raktarid);
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
     * @param string $termekmin a termék globális minimumát adó kifejezés (pl. `t.minboltikeszlet`)
     * @param string $valtozatid a változat azonosítója (pl. `_xx.id`)
     * @param string $valtozatmin a változat globális minimuma (pl. `_xx.minboltikeszlet`)
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
            $agak[] = 'NULLIF((SELECT tmk.minboltikeszlet FROM termekminboltikeszlet tmk'
                . ' WHERE tmk.termek_id = ' . $termekid . ' AND tmk.raktar_id = :' . $raktarparam . '), 0)';
            if ($valtozatid) {
                $agak[] = 'NULLIF((SELECT vmk.minboltikeszlet FROM termekvaltozatminboltikeszlet vmk'
                    . ' WHERE vmk.termekvaltozat_id = ' . $valtozatid . ' AND vmk.raktar_id = :' . $raktarparam . '), 0)';
            }
        }
        $agak[] = 'NULLIF(' . $termekmin . ', 0)';
        if ($valtozatmin) {
            $agak[] = $valtozatmin;
        }
        $agak[] = '0';
        return 'COALESCE(' . implode(',', $agak) . ')';
    }

    /**
     * A létra 1-2. lépése. null, ha egyik szinten sincs nem nulla raktáras érték.
     */
    private static function getRaktariErtek($termek, $valtozat, $raktarid)
    {
        $termekid = $termek?->getId();
        if ($termekid) {
            self::loadTermek([$termekid], $raktarid);
            $ertek = self::$termekCache[$raktarid][$termekid] ?? null;
            if ($ertek * 1) {
                return $ertek;
            }
        }
        $valtozatid = $valtozat?->getId();
        if ($valtozatid) {
            self::loadValtozat([$valtozatid], $raktarid);
            $ertek = self::$valtozatCache[$raktarid][$valtozatid] ?? null;
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
        $sorok = \mkw\store::getEm()->getRepository(TermekMinboltikeszlet::class)
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
        $sorok = \mkw\store::getEm()->getRepository(TermekValtozatMinboltikeszlet::class)
            ->getByTermekValtozatIds($keresendo, $raktarid);
        foreach ($keresendo as $id) {
            self::$valtozatCache[$raktarid][$id] = $sorok[$id][$raktarid] ?? null;
        }
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
