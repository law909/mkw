<?php

namespace Controllers;

use mkw\store;

class bizonylattipusController extends \mkwhelpers\MattableController {

    public function __construct($params) {
        $this->setEntityName('Entities\Bizonylattipus');
        parent::__construct($params);
    }

    public function getSelectList($selid = null) {
        $rec = $this->getRepo()->getAll(array(), array('nev' => 'ASC'));
        $res = array();
        foreach ($rec as $sor) {
            $res[] = array(
                'id' => $sor->getId(),
                'caption' => $sor->getNev(),
                'selected' => ($sor->getId() == $selid)
            );
        }
        return $res;
    }
}