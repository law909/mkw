<?php

namespace Controllers;


class partnermijsztanitasController extends \mkwhelpers\MattableController {

    public function __construct($params) {
        $this->setEntityName('Entities\PartnerMIJSZTanitas');
        parent::__construct($params);
    }

    /**
     * @param \Entities\PartnerMIJSZTanitas $t
     * @param bool|false $forKarb
     * @return array
     */
    public function loadVars($t, $forKarb = false) {
        $szint = new \Controllers\mijszgyakorlasszintController($this->params);
        $x = array();
        if (!$t) {
            $t = new \Entities\PartnerMIJSZTanitas();
            $this->getEm()->detach($t);
            $x['oper'] = 'add';
            $x['id'] = \mkw\store::createUID();
        }
        else {
            $x['oper'] = 'edit';
            $x['id'] = $t->getId();
        }
        $x['helyszin'] = $t->getHelyszin();
        $x['mikor'] = $t->getMikor();
        $x['napnev'] = $t->getNapNev();
        $x['szintnev'] = $t->getSzintNev();
        if ($forKarb) {
            $x['mijsztanitasszintlist'] = $szint->getSelectList(($t->getSzint() ? $t->getSzintId() : 0));
            $x['mijsztanitasnaplist'] = \mkw\store::getDaynameSelectList($t->getNap());
        }
        return $x;
    }

    public function getemptyrow() {
        $view = $this->createView('partnermijsztanitaskarb.tpl');
        $view->setVar('mijsztanitas', $this->loadVars(null, true));
        echo $view->getTemplateResult();
    }

    public function getmainemptyrow() {
        $view = $this->getTemplateFactory()->createMainView('tanitasedit.tpl');
        $view->setVar('mijsztanitas', $this->loadVars(null, true));
        echo $view->getTemplateResult();
    }
}