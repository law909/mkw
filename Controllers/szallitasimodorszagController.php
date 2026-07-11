<?php

namespace Controllers;

use Entities\SzallitasimodOrszag;

class szallitasimodorszagController extends \mkwhelpers\MattableController
{

    public function __construct()
    {
        $this->setEntityName(SzallitasimodOrszag::class);
        parent::__construct();
    }

    public function loadVars($t, $forKarb = false)
    {
        $valutanem = new valutanemController();
        $oc = new orszagController();
        $x = [];
        if (!$t) {
            $t = new \Entities\SzallitasimodOrszag();
            $this->getEm()->detach($t);
            $x['oper'] = 'add';
            $x['id'] = \mkw\store::createUID();
        } else {
            $x['oper'] = 'edit';
            $x['id'] = $t->getId();
        }
        $x = $this->getEntityFieldsArray($t, $x);
        $x['orszagnev'] = $t->getOrszag()?->getNev();
        $x['valutanemnev'] = $t->getValutanem()?->getNev();
        if ($forKarb) {
            $x['valutanemlist'] = $valutanem->getSelectList($t->getValutanem()?->getId());
            $x['orszaglist'] = $oc->getSelectList($t->getOrszag()?->getId(), true);
        }
        return $x;
    }

    protected function setFields($obj)
    {
        return $obj;
    }

    public function getemptyrow()
    {
        $view = $this->createView('szallitasimodorszagkarb.tpl');
        $view->setVar('orszag', $this->loadVars(null, true));
        echo $view->getTemplateResult();
    }

}