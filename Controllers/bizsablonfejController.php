<?php

namespace Controllers;

class bizsablonfejController extends bizonylatfejController
{

    public function __construct()
    {
        $this->biztipus = 'bizsablon';
        $this->setPageTitle('Biz. sablon');
        $this->setPluralPageTitle('Biz. sablonok');
        parent::__construct();
    }

    public function setVars($view)
    {
        parent::setVars($view);
        $view->setVar('datumtolfilter', null);
    }

    public function getszamlakarb()
    {
        $megrendszam = $this->params->getStringRequestParam('id');
        $szamlac = new SzamlafejController();
        $szamlac->getkarb('bizonylatfejkarb.tpl', $megrendszam, 'add');
    }
}