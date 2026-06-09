<?php

namespace Controllers;

class kolcsonzesfejController extends bizonylatfejController
{

    public function __construct()
    {
        $this->biztipus = 'kolcsonzes';
        $this->setPageTitle('Kölcsönzés');
        $this->setPluralPageTitle('Kölcsönzések');
        parent::__construct();
    }

    public function setVars($view)
    {
        parent::setVars($view);
        $view->setVar('datumtolfilter', null);
    }

    public function getszamlakarb()
    {
        $megrendszam = $this->params->getStringRequestParams('id');
        $szamlac = new SzamlafejController();
        $szamlac->getkarb('bizonylatfejkarb.tpl', $megrendszam, 'add');
    }
}