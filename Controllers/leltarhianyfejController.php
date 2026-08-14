<?php

namespace Controllers;

class leltarhianyfejController extends bizonylatfejController
{

    public function __construct()
    {
        parent::__construct();
        $this->setBiztipus('leltarhiany');
        $this->setPageTitle('Leltár hiány');
        $this->setPluralPageTitle('Leltár hiányok');
    }

}
