<?php

namespace Traits;

/**
 * A törzs sorrend mezőjének újraképzése név szerint. Százasával lépked, hogy két elem közé
 * kézzel is be lehessen szúrni egyet.
 */
trait SorrendGenerator
{

    public function regenerateSorrend()
    {
        $sorrend = 0;
        // a nev oszlop utf8_hungarian_ci, tehát a rendezés az adatbázisra bízható
        foreach ($this->getRepo()->getAll([], ['nev' => 'ASC']) as $egyed) {
            $sorrend += 100;
            $egyed->setSorrend($sorrend);
        }
        $this->getEm()->flush();
        echo json_encode(['db' => (int)($sorrend / 100)]);
    }

}
