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
        $this->biztipus = 'boltieladas';
        $this->setPageTitle('Bolti eladás');
        $this->setPluralPageTitle('Bolti eladások');
        parent::__construct();
    }

    public function onGetKarb($view, $record, $egyed, $oper, $id)
    {
        if ($oper == 'inherit') {
            $egyed['id'] = \mkw\store::createUID();
            $egyed['parentid'] = $id;
            $kelt = date(\mkw\store::$DateFormat);
            $egyed['keltstr'] = $kelt;
            $egyed['teljesitesstr'] = $kelt;
            $egyed['esedekessegstr'] = \mkw\store::calcEsedekesseg($kelt, $record->getFizmod(), $record->getPartner());
            $egyed['reportfile'] = '';
            $view->setVar('reportfilelist', $this->getRepo()->getReportfileSelectList('', $this->biztipus));
            $ttk = [];
            $cikl = 1;
            foreach ($egyed['tetelek'] as $tetel) {
                $tetel['parentid'] = $tetel['id'];
                $tetel['id'] = \mkw\store::createUID($cikl);
                $tetel['oper'] = 'inherit';
                $ttk[] = $tetel;
                $cikl++;
            }
            $egyed['tetelek'] = $ttk;
        }
        return $egyed;
    }

}
