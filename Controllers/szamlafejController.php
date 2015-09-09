<?php

namespace Controllers;

use mkw\store;

class SzamlafejController extends bizonylatfejController {

    public function __construct($params) {
        $this->biztipus = 'szamla';
        $this->setPageTitle('Számla');
        $this->setPluralPageTitle('Számlák');
        parent::__construct($params);
    }

    public function onGetKarb($view, $record, $egyed, $oper, $id) {
        $source = $this->params->getStringRequestParam('source', '');
        switch ($oper) {
            case 'inherit':
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
                break;
            case 'storno':
                $egyed['id'] = store::createUID();
                $egyed['parentid'] = $id;
                $kelt = date(\mkw\Store::$DateFormat);
                $egyed['keltstr'] = $kelt;
//                $egyed['teljesitesstr'] = $kelt;
//                $egyed['esedekessegstr'] = \mkw\Store::calcEsedekesseg($kelt, $record->getFizmod(), $record->getPartner());
                $egyed['megjegyzes'] = $id . ' stornó bizonylata';
                $ttk = array();
                $cikl = 1;
                foreach($egyed['tetelek'] as $tetel) {
                    $tetel['parentid'] = $tetel['id'];
                    $tetel['id'] = store::createUID($cikl);
                    $tetel['oper'] = 'storno';
                    $ttk[] = $tetel;
                    $cikl++;
                }
                $egyed['tetelek'] = $ttk;
                break;
        }
        return $egyed;
    }

}
