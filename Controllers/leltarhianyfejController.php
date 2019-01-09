<?php

namespace Controllers;

class LeltarhianyfejController extends bizonylatfejController {

    public function __construct($params) {
        $this->biztipus = 'leltarhiany';
        $this->setPageTitle('Leltár hiány');
        $this->setPluralPageTitle('Leltár hiányok');
        parent::__construct($params);
    }

}
