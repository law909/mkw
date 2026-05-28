<?php

namespace Controllers;

class fixlocaleController extends \mkwhelpers\Controller
{

    public function run()
    {
        $conn = \mkw\store::getEm()->getConnection();

        $bizonylattetelDb = $conn->executeStatement(
            'UPDATE bizonylattetel bt'
            . ' INNER JOIN termek t ON bt.termek_id = t.id'
            . ' SET bt.termeknev_l1 = t.nev_l1'
            . ' WHERE (bt.termeknev_l1 IS NULL OR bt.termeknev_l1 = \'\')'
            . ' AND t.nev_l1 IS NOT NULL AND t.nev_l1 <> \'\''
        );

        $fizmodDb = $conn->executeStatement(
            'UPDATE bizonylatfej bf'
            . ' INNER JOIN fizmod f ON bf.fizmod_id = f.id'
            . ' SET bf.fizmodnev_l1 = f.nev_l1'
            . ' WHERE (bf.fizmodnev_l1 IS NULL OR bf.fizmodnev_l1 = \'\')'
            . ' AND f.nev_l1 IS NOT NULL AND f.nev_l1 <> \'\''
        );

        $szallitasimodDb = $conn->executeStatement(
            'UPDATE bizonylatfej bf'
            . ' INNER JOIN szallitasimod sz ON bf.szallitasimod_id = sz.id'
            . ' SET bf.szallitasimodnev_l1 = sz.nev_l1'
            . ' WHERE (bf.szallitasimodnev_l1 IS NULL OR bf.szallitasimodnev_l1 = \'\')'
            . ' AND sz.nev_l1 IS NOT NULL AND sz.nev_l1 <> \'\''
        );

        echo 'bizonylattetel.termeknev_l1 frissítve: ' . $bizonylattetelDb . '<br>';
        echo 'bizonylatfej.fizmodnev_l1 frissítve: ' . $fizmodDb . '<br>';
        echo 'bizonylatfej.szallitasimodnev_l1 frissítve: ' . $szallitasimodDb . '<br>';
    }
}
