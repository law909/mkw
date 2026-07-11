<?php

namespace Controllers;

use Entities\SzallitasimodFizmodNovelo;

class szallitasimodfizmodnoveloController extends \mkwhelpers\MattableController
{

    public function __construct()
    {
        $this->setEntityName(SzallitasimodFizmodNovelo::class);
        parent::__construct();
    }

    public function loadVars($t, $forKarb = false)
    {
        $oc = new fizmodController();
        $x = [];
        if (!$t) {
            $t = new \Entities\SzallitasimodFizmodNovelo();
            $this->getEm()->detach($t);
            $x['oper'] = 'add';
            $x['id'] = \mkw\store::createUID();
        } else {
            $x['oper'] = 'edit';
            $x['id'] = $t->getId();
        }
        $x = $this->getEntityFieldsArray($t, $x);

        $x['fizmodnev'] = $t->getFizmod()?->getNev();
        if ($forKarb) {
            $x['fizmodlist'] = $oc->getSelectList($t->getFizmod()?->getId());
        }
        return $x;
    }

    protected function setFields($obj)
    {
        return $obj;
    }

    public function getemptyrow()
    {
        $view = $this->createView('szallitasimodfizmodnovelokarb.tpl');
        $view->setVar('fizmod', $this->loadVars(null, true));
        echo $view->getTemplateResult();
    }

}