<?php

namespace Controllers;

class bizsablonfejController extends bizonylatfejController {

    public function __construct($params) {
        $this->biztipus = 'bizsablon';
        $this->setPageTitle('Biz. sablon');
        $this->setPluralPageTitle('Biz. sablonok');
        parent::__construct($params);
    }

    public function setVars($view) {
        parent::setVars($view);
        $view->setVar('datumtolfilter', null);
    }

    public function getszamlakarb() {
        $megrendszam = $this->params->getStringRequestParams('id');
        $szamlac = new SzamlafejController($this->params);
        $szamlac->getkarb('bizonylatfejkarb.tpl', $megrendszam, 'add');
    }
}