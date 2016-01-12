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

    public function onGetKarb($view, $record, $egyed, $oper, $id, $stornotip) {
        $source = $this->params->getStringRequestParam('source', '');
        switch ($oper) {
            case 'inherit':
                $egyed['id'] = store::createUID();
                $egyed['parentid'] = $id;
                $kelt = date(\mkw\Store::$DateFormat);
                $egyed['keltstr'] = $kelt;
                $egyed['teljesitesstr'] = $kelt;
                $egyed['esedekessegstr'] = \mkw\Store::calcEsedekesseg($kelt, $record->getFizmod(), $record->getPartner());
                $egyed['reportfile'] = '';
                $view->setVar('reportfilelist', $this->getRepo()->getReportfileSelectList('', $this->biztipus));
                switch ($source) {
                    case 'megrendeles':
                        $egyed['megjegyzes'] = 'Rendelés: ' . $id;
                        $arf = $this->getRepo('Entities\Arfolyam')->getActualArfolyam($egyed['valutanem'], new \DateTime(\mkw\Store::convDate($egyed['teljesitesstr'])));
                        if ($arf) {
                            $egyed['arfolyam'] = $arf->getArfolyam();
                        }
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
                    if ($source == 'megrendeles') {
                        $tetel['nettoegysarhuf'] = $tetel['nettoegysar'] * $egyed['arfolyam'];
                        $tetel['bruttoegysarhuf'] = $tetel['bruttoegysar'] * $egyed['arfolyam'];
                        $tetel['nettohuf'] = $tetel['netto'] * $egyed['arfolyam'];
                        $tetel['bruttohuf'] = $tetel['brutto'] * $egyed['arfolyam'];
                        $tetel['afahuf'] = $tetel['afa'] * $egyed['arfolyam'];
                    }
                    $ttk[] = $tetel;
                    $cikl++;
                }
                $egyed['tetelek'] = $ttk;
                break;
            case 'storno':
                $egyed['id'] = store::createUID();
                $egyed['parentid'] = $id;
                $egyed['stornotip'] = $stornotip;
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
