<?php

namespace Traits;

use Entities\Dolgozo;
use Entities\Dolgozoszabadsag;
use Entities\Unnepnap;
use mkwhelpers\FilterDescriptor;

/**
 * „Melyik nap munkanap” – a jelenléti ív és a szabadság kimutatás közös szabálya: a dolgozó
 * munkanapja (`munkanap1`–`munkanap7`), ha nincs rá `unnepnap` rekord, valamint minden nap,
 * amire munkanap típusú bejegyzése van (`Dolgozoszabadsag::TIPUS_MUNKANAP`), ünnepnapon is.
 * A vasárnap sosem munkanap, akkor sem, ha a munkarendben be van pipálva.
 */
trait Munkanap
{

    /** @return array kulcs = Y-m-d, hogy a napi ellenőrzés ne járjon lekérdezéssel */
    protected function getUnnepnapok(\DateTime $tol, \DateTime $ig)
    {
        $filter = new FilterDescriptor();
        $filter->addFilter('datum', '>=', $tol->format(\mkw\store::$SQLDateFormat));
        $filter->addFilter('datum', '<=', $ig->format(\mkw\store::$SQLDateFormat));
        $result = [];
        /** @var Unnepnap $unnepnap */
        foreach ($this->getRepo(Unnepnap::class)->getAll($filter) as $unnepnap) {
            $result[$unnepnap->getDatumString()] = true;
        }
        return $result;
    }

    /**
     * @param Dolgozoszabadsag[] $bejegyzesek
     *
     * @return array kulcs = Y-m-d, a munkanap típusú bejegyzések napjai
     */
    protected function getMunkanapBejegyzesNapok(array $bejegyzesek)
    {
        $result = [];
        foreach ($bejegyzesek as $bejegyzes) {
            if (!$bejegyzes->isMunkanap() || !$bejegyzes->getDatumtol() || !$bejegyzes->getDatumig()) {
                continue;
            }
            $nap = clone $bejegyzes->getDatumtol();
            while ($nap <= $bejegyzes->getDatumig()) {
                $result[$nap->format(\mkw\store::$SQLDateFormat)] = true;
                $nap->modify('+1 day');
            }
        }
        return $result;
    }

    /** @param array $munkanapok {@see getMunkanapBejegyzesNapok()} */
    protected function isMunkanap(Dolgozo $dolgozo, \DateTime $nap, array $unnepnapok, array $munkanapok = [])
    {
        if ((int)$nap->format('N') === 7) {
            return false;
        }
        $kulcs = $nap->format(\mkw\store::$SQLDateFormat);
        return isset($munkanapok[$kulcs])
            || ($dolgozo->isMunkanap($nap) && !isset($unnepnapok[$kulcs]));
    }

    /** Hány munkanap esik az időszakba a dolgozó munkarendje szerint. */
    protected function countMunkanapok(Dolgozo $dolgozo, \DateTime $tol, \DateTime $ig, array $unnepnapok, array $munkanapok = [])
    {
        $db = 0;
        $nap = clone $tol;
        while ($nap <= $ig) {
            if ($this->isMunkanap($dolgozo, $nap, $unnepnapok, $munkanapok)) {
                $db++;
            }
            $nap->modify('+1 day');
        }
        return $db;
    }

}
