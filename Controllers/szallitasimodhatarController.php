<?php

namespace Controllers;

class szallitasimodhatarController extends \mkwhelpers\MattableController {

    public function __construct($params) {
        $this->setEntityName('Entities\SzallitasimodHatar');
        parent::__construct($params);
    }

    public function loadVars($t, $forKarb = false) {
        $valutanem = new valutanemController($this->params);
        $x = array();
        if (!$t) {
            $t = new \Entities\SzallitasimodHatar();
            $this->getEm()->detach($t);
            $x['oper'] = 'add';
            $x['id'] = \mkw\Store::createUID();
        }
        else {
            $x['oper'] = 'edit';
            $x['id'] = $t->getId();
        }
        $x['valutanemnev'] = $t->getValutanemNev();
        $x['hatarertek'] = $t->getHatarertek();
        $x['osszeg'] = $t->getOsszeg();
        if ($forKarb) {
            $x['valutanemlist'] = $valutanem->getSelectList(($t->getValutanem() ? $t->getValutanemId() : 0));
        }
        return $x;
    }

    protected function setFields($obj) {
        return $obj;
    }

    public function getemptyrow() {
        $view = $this->createView('szallitasimodhatarkarb.tpl');
        $view->setVar('hatar', $this->loadVars(null, true));
        echo $view->getTemplateResult();
    }

}