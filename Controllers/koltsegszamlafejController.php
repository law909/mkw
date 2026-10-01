<?php

namespace Controllers;

class koltsegszamlafejController extends bizonylatfejController
{

    public function __construct()
    {
        parent::__construct();
        $this->setBiztipus('koltsegszamla');
        $this->setPageTitle('Költségszámla');
        $this->setPluralPageTitle('Költségszámlák');
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
        return $egyed;
    }

}
