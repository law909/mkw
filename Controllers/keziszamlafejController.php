<?php

namespace Controllers;

use mkw\store;

class KeziszamlafejController extends bizonylatfejController {

    public function __construct($params) {
        $this->biztipus = 'keziszamla';
        $this->setPageTitle('Kézi számla');
        $this->setPluralPageTitle('Kézi számlák');
        parent::__construct($params);
    }

    public function onGetKarb($view, $record, $egyed, $oper, $id) {
        $source = $this->params->getStringRequestParam('source', '');
        if ($oper == 'inherit') {
            $egyed['id'] = store::createUID();
            $egyed['parentid'] = $id;
            $kelt = date(\mkw\Store::$DateFormat);
            $egyed['keltstr'] = $kelt;
            $egyed['teljesitesstr'] = $kelt;
            $egyed['esedekessegstr'] = \mkw\Store::calcEsedekesseg($kelt, $record->getFizmod(), $record->getPartner());
            switch ($source) {
                case 'megrendeles':
                    $egyed['megjegyzes'] = 'Rendelés: ' . $id;
                    break;
                case 'szallito':
                    $egyed['megjegyzes'] = 'Szállítólevél: ' . $id;
                    break;
            }
            $ttk = array();
            $cikl = 1;
            foreach($egyed['tetelek'] as $tetel) {
                $tetel['parentid'] = $tetel['id'];
                $tetel['id'] = store::createUID($cikl);
                $tetel['oper'] = 'inherit';
                $ttk[] = $tetel;
                $cikl++;
            }
            $egyed['tetelek'] = $ttk;
        }
    }

}
