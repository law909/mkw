<?php

namespace Controllers;

class bevetfejController extends bizonylatfejController
{

    public function __construct()
    {
        parent::__construct();
        $this->setBiztipus('bevet');
        $this->setPageTitle('Bevételezés');
        $this->setPluralPageTitle('Bevételezések');
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
