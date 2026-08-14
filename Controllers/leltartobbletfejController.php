<?php

namespace Controllers;

class leltartobbletfejController extends bizonylatfejController
{

    public function __construct()
    {
        parent::__construct();
        $this->setBiztipus('leltartobblet');
        $this->setPageTitle('Leltár többlet');
        $this->setPluralPageTitle('Leltár többletek');
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
