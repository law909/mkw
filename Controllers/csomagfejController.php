<?php

namespace Controllers;

class csomagfejController extends bizonylatfejController
{

    public function __construct()
    {
        parent::__construct();
        $this->setBiztipus('csomag');
        $this->setPageTitle('Csomag');
        $this->setPluralPageTitle('Csomagok');
    }

    /**
     * @param $view
     * @param \Entities\Bizonylatfej $record
     * @param $egyed
     * @param $oper
     * @param $id
     *
     * @return mixed
     */
    public function onGetKarb($view, $record, $egyed, $oper, $id)
    {
        $source = $this->params->getStringRequestParam('source', '');
        if ($oper == 'inherit') {
            $egyed['id'] = \mkw\store::createUID();
            $egyed['parentid'] = $id;
            $kelt = date(\mkw\store::$DateFormat);
            $egyed['keltstr'] = $kelt;
            $egyed['teljesitesstr'] = $kelt;
            $egyed['esedekessegstr'] = \mkw\store::calcEsedekesseg($kelt, $record->getFizmod(), $record->getPartner());
            $egyed['reportfile'] = '';
            $view->setVar('reportfilelist', $this->getRepo()->getReportfileSelectList('', $this->getBiztipusId()));
            if ($this->isOrderSource($source)) {
                $egyed['megjegyzes'] = \mkw\store::translate('Rendelés szám', $record->getBizonylatnyelv()) . ': ' . $id;
            }
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
