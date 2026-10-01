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
                $egyed = $this->inheritEgyed($egyed, $record, $id);
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
                $egyed['tetelek'] = $this->copyTetelek($egyed['tetelek'], $this->stornoOperation);
                break;
        }
        return $egyed;
    }

}
