<?php

namespace Controllers;

class LeltartobbletfejController extends bizonylatfejController {

    public function __construct($params) {
        $this->biztipus = 'leltartobblet';
        $this->setPageTitle('Leltár többlet');
        $this->setPluralPageTitle('Leltár többletek');
        parent::__construct($params);
    }

    public function onGetKarb($view, $record, $egyed, $oper, $id) {
        if (!\mkw\store::isPartnerAutocomplete()) {
            $partner = new partnerController($this->params);
            $filter = new \mkwhelpers\FilterDescriptor();
            $filter->addFilter('szallito', '=', true);
            $view->setVar('partnerlist', $partner->getSelectList(($record ? $record->getPartnerId() : 0), $filter));
        }
        return $egyed;
    }

}
