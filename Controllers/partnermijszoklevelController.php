<?php

namespace Controllers;


class partnermijszoklevelController extends \mkwhelpers\MattableController {

    public function __construct($params) {
        $this->setEntityName('Entities\PartnerMIJSZOklevel');
        parent::__construct($params);
    }

    /**
     * @param \Entities\PartnerMIJSZOklevel $t
     * @param bool|false $forKarb
     * @return array
     */
    public function loadVars($t, $forKarb = false) {
        $ok = new mijszoklevelkibocsajtoController($this->params);
        $osz = new mijszoklevelszintController($this->params);
        $x = array();
        if (!$t) {
            $t = new \Entities\PartnerMIJSZOklevel();
            $this->getEm()->detach($t);
            $x['oper'] = 'add';
            $x['id'] = \mkw\store::createUID();
        }
        else {
            $x['oper'] = 'edit';
            $x['id'] = $t->getId();
        }
        $x['oklevelev'] = $t->getOklevelev();
        if ($forKarb) {
            $x['mijszoklevelkibocsajtolist'] = $ok->getSelectList(($t->getMIJSZOklevelkibocsajto() ? $t->getMIJSZOklevelkibocsajtoId() : 0));
            $x['mijszoklevelszintlist'] = $osz->getSelectList(($t->getMIJSZOklevelszint() ? $t->getMIJSZOklevelszintId() : 0));
        }
        return $x;
    }

    public function getemptyrow() {
        $view = $this->createView('partnermijszoklevelkarb.tpl');
        $view->setVar('mijszoklevel', $this->loadVars(null, true));
        echo $view->getTemplateResult();
    }

    public function getmainemptyrow() {
        $view = $this->getTemplateFactory()->createMainView('okleveledit.tpl');
        $view->setVar('mijszoklevel', $this->loadVars(null, true));
        echo $view->getTemplateResult();
    }
}