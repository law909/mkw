<?php

namespace Controllers;

/**
 * Szállítói előleg: a beszállítónak a szállítói megrendelés (társbizonylat) alapján előre
 * fizetett összeg. Készletet nem mozgat, pénzt igen – a bevételezés mintájára kapcsolódik a
 * szállítói megrendeléshez.
 */
class szallitoielolegfejController extends bizonylatfejController
{

    public function __construct()
    {
        parent::__construct();
        $this->setBiztipus('szallitoieloleg');
        $this->setPageTitle('Szállítói előleg');
        $this->setPluralPageTitle('Szállítói előlegek');
    }

    public function onGetKarb($view, $record, $egyed, $oper, $id)
    {
        if ($oper == 'inherit') {
            $egyed = $this->inheritEgyed($egyed, $record, $id);
        }
        if (!\mkw\store::isPartnerAutocomplete()) {
            $partner = new partnerController();
            $filter = new \mkwhelpers\FilterDescriptor();
            $filter->addFilter('szallito', '=', true);
            $view->setVar('partnerlist', $partner->getSelectList(($record ? $record->getPartnerId() : 0), $filter));
        }

        $tarsbiztipus = 'szallmegr';
        $view->setVar('tarsbiztipus', $tarsbiztipus);
        $view->setVar(
            'tarsbizonylatlist',
            $this->buildTarsbizonylatList(
                $record ? $record->getPartnerId() : 0,
                $tarsbiztipus,
                $record ? $record->getTarsbizonylatId() : ''
            )
        );
        return $egyed;
    }
}
