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

    public function onGetKarb($view, $record, $egyed, $oper, $id, $stornotip)
    {
        switch ($oper) {
            case 'inherit':
                $egyed['id'] = \mkw\store::createUID();
                $egyed['parentid'] = $id;
                $kelt = date(\mkw\store::$DateFormat);
                $egyed['keltstr'] = $kelt;
                $egyed['teljesitesstr'] = $kelt;
                $egyed['esedekessegstr'] = \mkw\store::calcEsedekesseg($kelt, $record->getFizmod(), $record->getPartner());
                $egyed['reportfile'] = '';
                $view->setVar('reportfilelist', $this->getRepo()->getReportfileSelectList('', $this->getBiztipusId()));
                $egyed['tetelek'] = $this->copyTetelek($egyed['tetelek'], 'inherit');
                break;
            case 'storno':
                // without new ids the save would overwrite the ORIGINAL advance's lines with the negated ones
                $egyed['id'] = \mkw\store::createUID();
                $egyed['parentid'] = $id;
                $egyed['stornotip'] = $stornotip;
                $egyed['keltstr'] = date(\mkw\store::$DateFormat);
                $egyed['megjegyzes'] = $id . (\mkw\store::getTheme() === 'mkwcansas'
                    ? ' stornó bizonylata. Stornózás oka:'
                    : ' stornó bizonylata');
                $egyed['tetelek'] = $this->copyTetelek($egyed['tetelek'], 'storno');
                break;
        }
        return $egyed;
    }

    /** The lines as new lines of the new document, each pointing back at its original. */
    private function copyTetelek(array $tetelek, $oper)
    {
        $ttk = [];
        $cikl = 1;
        foreach ($tetelek as $tetel) {
            $tetel['parentid'] = $tetel['id'];
            $tetel['id'] = \mkw\store::createUID($cikl);
            $tetel['oper'] = $oper;
            $ttk[] = $tetel;
            $cikl++;
        }
        return $ttk;
    }

}
