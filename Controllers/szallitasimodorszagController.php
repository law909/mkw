<?php

namespace Controllers;

class szallitasimodorszagController extends \mkwhelpers\MattableController {

    public function __construct($params) {
        $this->setEntityName('Entities\SzallitasimodOrszag');
        parent::__construct($params);
    }

    public function loadVars($t, $forKarb = false) {
        $valutanem = new valutanemController($this->params);
        $oc = new orszagController($this->params);
        $x = array();
        if (!$t) {
            $t = new \Entities\SzallitasimodOrszag();
            $this->getEm()->detach($t);
            $x['oper'] = 'add';
            $x['id'] = \mkw\store::createUID();
        }
        else {
            $x['oper'] = 'edit';
            $x['id'] = $t->getId();
        }
        $x['orszagnev'] = $t->getOrszagNev();
        $x['valutanemnev'] = $t->getValutanemNev();
        $x['hatarertek'] = $t->getHatarertek();
        $x['osszeg'] = $t->getOsszeg();
        if ($forKarb) {
            $x['valutanemlist'] = $valutanem->getSelectList(($t->getValutanem() ? $t->getValutanemId() : 0));
            $x['orszaglist'] = $oc->getSelectList($t->getOrszag() ? $t->getOrszagId() : 0);
        }
        return $x;
    }

    protected function setFields($obj) {
        return $obj;
    }

    public function getemptyrow() {
        $view = $this->createView('szallitasimodorszagkarb.tpl');
        $view->setVar('orszag', $this->loadVars(null, true));
        echo $view->getTemplateResult();
    }

}