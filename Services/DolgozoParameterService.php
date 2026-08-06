<?php

namespace Services;

use Entities\Dolgozo;
use Entities\Dolgozoparameterek;

/**
 * Dolgozó szintű paraméterek olvasása/írása.
 *
 * Ugyanaz a szerep, mint a `\mkw\store::getParameter()` / `setParameter()` párosnak
 * a globális `parameterek` táblán, csak itt minden érték a bejelentkezett dolgozóhoz
 * tartozik (`dolgozoparameterek` tábla, lásd {@see \Entities\Dolgozoparameterek}).
 *
 * Használat:
 *   \Services\DolgozoParameterService::getParameter('/admin/orszag/viewlist');
 *   \Services\DolgozoParameterService::setParameter('/admin/orszag/viewlist', '1');
 *
 * A dolgozó azonosítója az admin session `pk` mezőjéből jön (lásd
 * `dolgozoController::login()`). A SYSADMIN belépésnek `pk = -1`, azaz nincs mögötte
 * dolgozó sor: ilyenkor minden olvasás az alapértelmezéssel tér vissza, az írás pedig
 * csendben nem csinál semmit – a hívónak nem kell külön ágat írnia rá.
 *
 * Kérésen belül a dolgozó összes paramétere egyszer töltődik be, utána memóriából megy.
 */
class DolgozoParameterService
{

    /** A mattable listák "Mindig nyitva" (szűrő nyitva tartása) pipája */
    const MINDIGNYITVA = 'mindignyitva';

    /** A bal oldali menü egy menücsoportjának nyitott/zárt állapota */
    const MENUCSOPORT = 'menucsoportnyitva';

    /** dolgozoid => [par => ertek] – kérésen belüli cache */
    private static $cache = [];

    /**
     * Lista-szintű paraméter kulcsa: a lista URL-jének elérési útja (? nélkül) és a
     * paraméter neve, pl. `/admin/orszag/viewlist_mindignyitva`. Egy listához több
     * paraméter is tartozhat, ezért a puszta útvonal önmagában nem elég kulcsnak.
     */
    public static function getListKey($path, $par)
    {
        return $path . '_' . $par;
    }

    /**
     * Egy menücsoport nyitott/zárt állapotának kulcsa, pl. `menucsoportnyitva_7`.
     * Hogy mi az alapértelmezés mentett sor hiányában, azt a hívó dönti el
     * (lásd menuController::isMenucsoportNyitva()).
     */
    public static function getMenucsoportKey($mcsid)
    {
        return self::MENUCSOPORT . '_' . $mcsid;
    }

    /**
     * A bejelentkezett dolgozó azonosítója, vagy null, ha nincs (pl. SYSADMIN, kijelentkezett).
     */
    public static function getDolgozoId()
    {
        $pk = \mkw\store::getAdminSession()->pk;
        if ($pk && $pk > 0) {
            return (int)$pk;
        }
        return null;
    }

    public static function getParameter($par, $default = null)
    {
        return self::getParameterFor(self::getDolgozoId(), $par, $default);
    }

    public static function getParameterFor($dolgozoid, $par, $default = null)
    {
        if (!$dolgozoid) {
            return $default;
        }
        $params = self::load($dolgozoid);
        if (array_key_exists($par, $params) && $params[$par] !== null) {
            return $params[$par];
        }
        return $default;
    }

    public static function getIntParameter($par, $default = null)
    {
        $er = self::getParameter($par, $default);
        if (is_numeric($er)) {
            return (int)$er;
        }
        return $default;
    }

    /**
     * Kapcsoló jellegű paraméterekhez (a mentett érték '1' vagy '0').
     * A PHP a '0' stringet is hamisnak veszi, így a castolás elég.
     */
    public static function getBoolParameter($par, $default = false)
    {
        $er = self::getParameter($par, null);
        if ($er === null) {
            return $default;
        }
        return (bool)$er;
    }

    public static function setParameter($par, $ertek)
    {
        self::setParameterFor(self::getDolgozoId(), $par, $ertek);
    }

    public static function setParameterFor($dolgozoid, $par, $ertek)
    {
        if (!$dolgozoid) {
            return;
        }
        $em = \mkw\store::getEm();
        $dolgozo = $em->getRepository(Dolgozo::class)->find($dolgozoid);
        if (!$dolgozo) {
            return;
        }
        /** @var \Entities\Dolgozoparameterek $p */
        $p = $em->getRepository(Dolgozoparameterek::class)->getByDolgozoAndPar($dolgozoid, $par);
        if (!$p) {
            $p = new Dolgozoparameterek();
            $p->setDolgozo($dolgozo);
            $p->setPar($par);
        }
        $p->setErtek($ertek);
        $em->persist($p);
        $em->flush();
        self::$cache[$dolgozoid][$par] = $ertek;
    }

    public static function clearParameter($par)
    {
        self::clearParameterFor(self::getDolgozoId(), $par);
    }

    public static function clearParameterFor($dolgozoid, $par)
    {
        if (!$dolgozoid) {
            return;
        }
        $em = \mkw\store::getEm();
        $p = $em->getRepository(Dolgozoparameterek::class)->getByDolgozoAndPar($dolgozoid, $par);
        if ($p) {
            $em->remove($p);
            $em->flush();
        }
        unset(self::$cache[$dolgozoid][$par]);
    }

    /**
     * A memóriabeli cache eldobása – csak akkor kell, ha ugyanazon kérésen belül
     * más úton (natív SQL, másik EntityManager) módosultak a sorok.
     */
    public static function clearCache()
    {
        self::$cache = [];
    }

    private static function load($dolgozoid): array
    {
        if (!array_key_exists($dolgozoid, self::$cache)) {
            self::$cache[$dolgozoid] = \mkw\store::getEm()
                ->getRepository(Dolgozoparameterek::class)
                ->getAllForDolgozo($dolgozoid);
        }
        return self::$cache[$dolgozoid];
    }

}
