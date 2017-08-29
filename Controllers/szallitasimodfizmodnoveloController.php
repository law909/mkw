<?php

namespace Controllers;

class szallitasimodfizmodnoveloController extends \mkwhelpers\MattableController {

    public function __construct($params) {
        $this->setEntityName('Entities\SzallitasimodFizmodNovelo');
        parent::__construct($params);
    }

    public function loadVars($t, $forKarb = false) {
        $oc = new fizmodController($this->params);
        $x = array();
        if (!$t) {
            $t = new \Entities\SzallitasimodFizmodNovelo();
            $this->getEm()->detach($t);
            $x['oper'] = 'add';
            $x['id'] = \mkw\store::createUID();
        }
        else {
            $x['oper'] = 'edit';
            $x['id'] = $t->getId();
        }
        $x['fizmodnev'] = $t->getFizmodNev();
        $x['osszeg'] = $t->getOsszeg();
        if ($forKarb) {
            $x['fizmodlist'] = $oc->getSelectList($t->getFizmod() ? $t->getFizmodId() : 0);
        }
        return $x;
    }

    protected function setFields($obj) {
        return $obj;
    }

    public function getemptyrow() {
        $view = $this->createView('szallitasimodfizmodnovelokarb.tpl');
        $view->setVar('fizmod', $this->loadVars(null, true));
        echo $view->getTemplateResult();
    }

}