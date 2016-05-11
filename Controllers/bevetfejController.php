<?php

namespace Controllers;

class BevetfejController extends bizonylatfejController {

    public function __construct($params) {
        $this->biztipus = 'bevet';
        $this->setPageTitle('Bevételezés');
        $this->setPluralPageTitle('Bevételezések');
        parent::__construct($params);
    }

    public function onGetKarb($view, $record, $egyed, $oper, $id) {
        if ($oper == 'inherit') {
            $egyed['id'] = \mkw\store::createUID();
            $egyed['parentid'] = $id;
            $kelt = date(\mkw\store::$DateFormat);
            $egyed['keltstr'] = $kelt;
            $egyed['teljesitesstr'] = $kelt;
            $egyed['esedekessegstr'] = \mkw\store::calcEsedekesseg($kelt, $record->getFizmod(), $record->getPartner());
            $ttk = array();
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
        $partner = new partnerController($this->params);
        $filter = new \mkwhelpers\FilterDescriptor();
        $filter->addFilter('szallito', '=', true);
        $view->setVar('partnerlist', $partner->getSelectList(($record ? $record->getPartnerId() : 0), $filter));
        return $egyed;
    }

}
