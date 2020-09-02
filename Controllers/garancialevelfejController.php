<?php

namespace Controllers;

class garancialevelfejController extends bizonylatfejController {

    public function __construct($params) {
        $this->biztipus = 'garancialevel';
        $this->setPageTitle('Garancialevél');
        $this->setPluralPageTitle('Garancialevelek');
        parent::__construct($params);
    }

    public function onGetKarb($view, $record, $egyed, $oper, $id) {
        $source = $this->params->getStringRequestParam('source', '');
        switch ($oper) {
            case 'inherit':
                $egyed['id'] = \mkw\store::createUID();
                $egyed['parentid'] = $id;
                $kelt = date(\mkw\store::$DateFormat);
                $egyed['keltstr'] = $kelt;
                $egyed['esedekessegstr'] = \mkw\store::calcEsedekesseg($kelt, $record->getFizmod(), $record->getPartner());
                switch ($source) {
                    case 'megrendeles':
                        $egyed['megjegyzes'] = \mkw\store::translate('Rendelés', $record->getBizonylatnyelv()) . ': ' . $id;
                        break;
                }
                $ttk = array();
                $cikl = 1;
                foreach($egyed['tetelek'] as $tetel) {
                    $tetel['parentid'] = $tetel['id'];
                    $tetel['id'] = \mkw\store::createUID($cikl);
                    $tetel['oper'] = 'inherit';
                    $ttk[] = $tetel;
                    $cikl++;
                }
                $egyed['tetelek'] = $ttk;
                break;
        }
        return $egyed;
    }

}
