<?php

namespace Controllers;

use mkw\store;

class termekertesitoController extends \mkwhelpers\MattableController {

    public function __construct($params) {
        $this->setEntityName('Entities\TermekErtesito');
        parent::__construct($params);
    }

    protected function loadVars($t) {
        $x = array();
        if (!$t) {
            $t = new \Entities\TermekErtesito();
            $this->getEm()->detach($t);
        }
        $x['id'] = $t->getId();
        $x['createdstr'] = $t->getCreatedStr();
        $x['sentstr'] = $t->getSentStr();
        $termek = $t->getTermek();
        if ($termek) {
            $x['termek'] = $termek->toKosar(null);
        }
        return $x;
    }

    protected function saveData() {
        $tid = $this->params->getIntRequestParam('termekid');
        $email = $this->params->getStringRequestParam('email');
        if ($tid && $email) {
            parent::saveData();
        }
    }

    protected function setFields($obj) {
        $termek = $this->getEm()->getRepository('Entities\Termek')->find($this->params->getIntRequestParam('termekid'));
        if ($termek) {
            $obj->setTermek($termek);
            $pc = new partnerController($this->params);
            $partner = $pc->getLoggedInUser();
            if ($partner) {
                $obj->setPartner($partner);
            }
            $obj->setEmail($this->params->getStringRequestParam('email'));
        }
        return $obj;
    }

    public function getAllByPartner($partner) {
        $list = $this->getRepo()->getByPartner($partner);
        $ret = array();
        foreach ($list as $l) {
            $ret[] = $this->loadVars($l);
        }
        return $ret;
    }

}
