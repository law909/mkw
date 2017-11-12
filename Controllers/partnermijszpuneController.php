<?php

namespace Controllers;


class partnermijszpuneController extends \mkwhelpers\MattableController {

    public function __construct($params) {
        $this->setEntityName('Entities\PartnerMIJSZPune');
        parent::__construct($params);
    }

    /**
     * @param \Entities\PartnerMIJSZPune $t
     * @param bool|false $forKarb
     * @return array
     */
    public function loadVars($t, $forKarb = false) {
        $x = array();
        if (!$t) {
            $t = new \Entities\PartnerMIJSZPune();
            $this->getEm()->detach($t);
            $x['oper'] = 'add';
            $x['id'] = \mkw\store::createUID();
        }
        else {
            $x['oper'] = 'edit';
            $x['id'] = $t->getId();
        }
        $x['ev'] = $t->getEv();
        $x['honap'] = $t->getHonap();
        return $x;
    }

    public function getemptyrow() {
        $view = $this->createView('partnermijszpunekarb.tpl');
        $view->setVar('mijszpune', $this->loadVars(null, true));
        echo $view->getTemplateResult();
    }

    public function getmainemptyrow() {
        $view = $this->getTemplateFactory()->createMainView('puneedit.tpl');
        $view->setVar('mijszpune', $this->loadVars(null, true));
        echo $view->getTemplateResult();
    }
}