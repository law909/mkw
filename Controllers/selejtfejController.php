<?php

namespace Controllers;

class selejtfejController extends bizonylatfejController
{

    public function __construct()
    {
        parent::__construct();
        $this->setBiztipus('selejt');
        $this->setPageTitle('Selejtezés');
        $this->setPluralPageTitle('Selejtezések');
    }

}
