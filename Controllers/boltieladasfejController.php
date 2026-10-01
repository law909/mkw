<?php

namespace Controllers;

/**
 * Bolti eladás bizonylatok admin CRUD-ja (lista + szerkesztő), a számla (szamlafejController)
 * mintájára. A teljes lista/karb/mentés gépezetet a bizonylatfejController biztosítja, itt csak
 * a bizonylattípust és a fejléceket állítjuk be. A POS gyorsrögzítő külön él (boltieladasController).
 */
class boltieladasfejController extends bizonylatfejController
{

    public function __construct()
    {
        parent::__construct();
        $this->setBiztipus('boltieladas');
        $this->setPageTitle('Bolti eladás');
        $this->setPluralPageTitle('Bolti eladások');
    }

    public function onGetKarb($view, $record, $egyed, $oper, $id)
    {
        if ($oper == 'inherit') {
            $egyed = $this->inheritEgyed($egyed, $record, $id);
        }
        return $egyed;
    }

}
