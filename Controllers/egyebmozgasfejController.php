<?php

namespace Controllers;

class egyebmozgasfejController extends bizonylatfejController
{

    public function __construct()
    {
        $this->biztipus = 'egyeb';
        $this->setPageTitle('Egyéb mozgás');
        $this->setPluralPageTitle('Egyéb mozgások');
        parent::__construct();
    }

}
