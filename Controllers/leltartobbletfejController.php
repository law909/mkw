<?php

namespace Controllers;

class LeltartobbletfejController extends bizonylatfejController
{

    public function __construct()
    {
        $this->biztipus = 'leltartobblet';
        $this->setPageTitle('Leltár többlet');
        $this->setPluralPageTitle('Leltár többletek');
        parent::__construct();
    }

    public function onGetKarb($view, $record, $egyed, $oper, $id)
    {
        if (!\mkw\store::isPartnerAutocomplete()) {
            $partner = new partnerController();
            $filter = new \mkwhelpers\FilterDescriptor();
            $filter->addFilter('szallito', '=', true);
            $view->setVar('partnerlist', $partner->getSelectList(($record ? $record->getPartnerId() : 0), $filter));
        }
        return $egyed;
    }

}
