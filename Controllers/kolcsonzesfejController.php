<?php

namespace Controllers;

class kolcsonzesfejController extends bizonylatfejController
{

    public function __construct()
    {
        parent::__construct();
        $this->setBiztipus('kolcsonzes');
        $this->setPageTitle('Kölcsönzés');
        $this->setPluralPageTitle('Kölcsönzések');
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