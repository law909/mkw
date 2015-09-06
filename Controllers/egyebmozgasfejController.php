<?php

namespace Controllers;

class EgyebmozgasfejController extends bizonylatfejController {

    public function __construct($params) {
        $this->biztipus = 'egyeb';
        $this->setPageTitle('Egyéb mozgás');
        $this->setPluralPageTitle('Egyéb mozgások');
        parent::__construct($params);
    }

}
