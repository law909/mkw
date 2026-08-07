<?php

namespace Controllers;

class garanciaugyfejController extends bizonylatfejController
{

    public function __construct()
    {
        parent::__construct();
        $this->setBiztipus('garanciaugy');
        $this->setPageTitle('Garanciális ügy');
        $this->setPluralPageTitle('Garanciális ügyek');
    }

}
