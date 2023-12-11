<?php

namespace Controllers;

class szallmegrfejController extends bizonylatfejController
{

    public function __construct($params)
    {
        $this->biztipus = 'szallmegr';
        $this->setPageTitle('Szállítói megrendelés');
        $this->setPluralPageTitle('Szállítói megrendelések');
        parent::__construct($params);
    }

}
