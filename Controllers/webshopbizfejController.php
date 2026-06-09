<?php

namespace Controllers;

use Entities\Bizonylatfej;
use Entities\Bizonylattetel;
use Entities\TermekFa;

class webshopbizfejController extends bizonylatfejController
{

    public function __construct()
    {
        $this->biztipus = 'webshopbiz';
        $this->setPageTitle('Webshop rendelés');
        $this->setPluralPageTitle('Webshop rendelések');
        parent::__construct();
    }

    public function setVars($view)
    {
        parent::setVars($view);
        $view->setVar('datumtolfilter', null);
    }

}
