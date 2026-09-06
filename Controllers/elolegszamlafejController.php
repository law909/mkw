<?php

namespace Controllers;

/**
 * Advance invoice: its own document type with its own (ELO) number range. The type has mozgat=0 and
 * foglal=0, so it neither moves nor reserves stock - an advance is money, not goods. The offsetting
 * happens on the final invoice, see \Services\ElolegService.
 */
class elolegszamlafejController extends bizonylatfejController
{

    public function __construct()
    {
        parent::__construct();
        $this->setBiztipus('elolegszamla');
        $this->setPageTitle('Előlegszámla');
        $this->setPluralPageTitle('Előlegszámlák');
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
            $view->setVar('reportfilelist', $this->getRepo()->getReportfileSelectList('', $this->getBiztipusId()));
            // header-only inheritance: the order's goods are NOT carried over, one advance line is made
            $egyed['tetelek'] = \Services\ElolegService::buildInheritedLines($record);
        }
        return $egyed;
    }

}
