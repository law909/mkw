<?php

namespace Controllers;

class LeltarhianyfejController extends bizonylatfejController
{

    public function __construct()
    {
        $this->setBiztipus('leltarhiany');
        $this->setPageTitle('Leltár hiány');
        $this->setPluralPageTitle('Leltár hiányok');
        parent::__construct();
    }

}
