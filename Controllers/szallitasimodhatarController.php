<?php

namespace Controllers;

use Entities\SzallitasimodHatar;

class szallitasimodhatarController extends \mkwhelpers\MattableController
{

    public function __construct()
    {
        $this->setEntityName(SzallitasimodHatar::class);
        parent::__construct();
    }

    public function loadVars($t, $forKarb = false)
    {
        $valutanem = new valutanemController();
        $x = [];
        if (!$t) {
            $t = new \Entities\SzallitasimodHatar();
            $this->getEm()->detach($t);
            $x['oper'] = 'add';
            $x['id'] = \mkw\store::createUID();
        } else {
            $x['oper'] = 'edit';
            $x['id'] = $t->getId();
        }
        $x = $this->getEntityFieldsArray($t, $x);
        $x['valutanemnev'] = $t->getValutanem()?->getNev();
        if ($forKarb) {
            $x['valutanemlist'] = $valutanem->getSelectList($t->getValutanem()?->getId());
        }
        return $x;
    }

    protected function setFields($obj)
    {
        return $obj;
    }

    public function getemptyrow()
    {
        $view = $this->createView('szallitasimodhatarkarb.tpl');
        $view->setVar('hatar', $this->loadVars(null, true));
        echo $view->getTemplateResult();
    }

}