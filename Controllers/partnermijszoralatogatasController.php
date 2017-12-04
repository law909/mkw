<?php

namespace Controllers;


class partnermijszoralatogatasController extends \mkwhelpers\MattableController {

    public function __construct($params) {
        $this->setEntityName('Entities\PartnerMIJSZOralatogatas');
        parent::__construct($params);
    }

    /**
     * @param \Entities\PartnerMIJSZOralatogatas $t
     * @param bool|false $forKarb
     * @return array
     */
    public function loadVars($t, $forKarb = false) {
        $tan = new \Controllers\partnerController($this->params);
        $x = array();
        if (!$t) {
            $t = new \Entities\PartnerMIJSZOralatogatas();
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
        $x['oraszam'] = $t->getOraszam();
        $x['ev'] = $t->getEv();
        $x['tanaregyeb'] = $t->getTanaregyeb();
        if ($forKarb) {
            $filter = new \mkwhelpers\FilterDescriptor();
            $filter->addFilter('partnertipus', '=', 1);
            $x['mijszoralatogatastanarlist'] = $tan->getSelectList(($t->getTanar() ? $t->getTanarId() : 0), $filter);
        }
        return $x;
    }

    public function getemptyrow() {
        $view = $this->createView('partnermijszoralatogataskarb.tpl');
        $view->setVar('mijszoralatogatas', $this->loadVars(null, true));
        echo $view->getTemplateResult();
    }

    public function getmainemptyrow() {
        $view = $this->getTemplateFactory()->createMainView('oralatogatasedit.tpl');
        $view->setVar('mijszoralatogatas', $this->loadVars(null, true));
        echo $view->getTemplateResult();
    }
}