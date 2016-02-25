<?php

namespace Controllers;

class SelejtfejController extends bizonylatfejController {

    public function __construct($params) {
        $this->biztipus = 'selejt';
        $this->setPageTitle('Selejtezés');
        $this->setPluralPageTitle('Selejtezések');
        parent::__construct($params);
    }

}
