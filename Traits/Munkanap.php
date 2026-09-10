<?php

namespace Traits;

use Entities\Dolgozo;
use Entities\Unnepnap;
use mkwhelpers\FilterDescriptor;

/**
 * „Melyik nap munkanap” – a jelenléti ív és a szabadság kimutatás közös szabálya: a dolgozó
 * munkanapja (`munkanap1`–`munkanap7`), ha nincs rá `unnepnap` rekord.
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

    protected function isMunkanap(Dolgozo $dolgozo, \DateTime $nap, array $unnepnapok)
    {
        return $dolgozo->isMunkanap($nap)
            && !isset($unnepnapok[$nap->format(\mkw\store::$SQLDateFormat)]);
    }

    /** Hány munkanap esik az időszakba a dolgozó munkarendje szerint. */
    protected function countMunkanapok(Dolgozo $dolgozo, \DateTime $tol, \DateTime $ig, array $unnepnapok)
    {
        $db = 0;
        $nap = clone $tol;
        while ($nap <= $ig) {
            if ($this->isMunkanap($dolgozo, $nap, $unnepnapok)) {
                $db++;
            }
            $nap->modify('+1 day');
        }
        return $db;
    }

}
