<?php

namespace Controllers;

class egyebmozgasfejController extends bizonylatfejController
{

    public function __construct()
    {
        parent::__construct();
        $this->setBiztipus('egyeb');
        $this->setPageTitle('Egyéb mozgás');
        $this->setPluralPageTitle('Egyéb mozgások');
    }

}
