<?php

namespace Controllers;

class szallmegrfejController extends bizonylatfejController
{

    public function __construct()
    {
        parent::__construct();
        $this->setBiztipus('szallmegr');
        $this->setPageTitle('Szállítói megrendelés');
        $this->setPluralPageTitle('Szállítói megrendelések');
    }

}
