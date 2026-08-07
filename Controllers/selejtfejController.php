<?php

namespace Controllers;

class selejtfejController extends bizonylatfejController
{

    public function __construct()
    {
        $this->setBiztipus('selejt');
        $this->setPageTitle('Selejtezés');
        $this->setPluralPageTitle('Selejtezések');
        parent::__construct();
    }

}
