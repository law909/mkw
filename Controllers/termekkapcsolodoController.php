<?php
namespace Controllers;

use mkw\store;

class termekkapcsolodoController extends \mkwhelpers\MattableController {

    public function __construct($params) {
        $this->setEntityName('Entities\TermekKapcsolodo');
//		$this->setKarbFormTplName('?howto?karbform.tpl');
//		$this->setKarbTplName('?howto?karb.tpl');
//		$this->setListBodyRowTplName('?howto?lista_tbody_tr.tpl');
//		$this->setListBodyRowVarName('_egyed');
        parent::__construct($params);
    }

    public function loadVars($t, $forKarb = false) {
        $termek = new termekController($this->params);
        $x = array();
        if (!$t) {
            $t = new \Entities\TermekKapcsolodo();
            $this->getEm()->detach($t);
            $x['oper'] = 'add';
            $x['id'] = store::createUID();
        }
        else {
            $x['oper'] = 'edit';
            $x['id'] = $t->getId();
        }
        $x['termek'] = $t->getTermek();
        $x['termeknev'] = $t->getTermekNev();
        $x['altermek'] = $t->getAlTermek();
        $x['altermekid'] = ($t->getAlTermek() ? $t->getAlTermek()->getId() : 0);
        $x['altermeknev'] = $t->getAlTermekNev();
        $x['altermekkepurl'] = $t->getAlTermek() ? $t->getAlTermek()->getKepUrlSmall() : '';
        if ($forKarb) {
//            $x['termeklist'] = $termek->getSelectList(($t->getAlTermek() ? $t->getAlTermek()->getId() : 0));
        }
        return $x;
    }

    protected function setFields($obj) {
        $ck = store::getEm()->getRepository('Entities\Termek')->find($this->params->getIntRequestParam('altermek'));
        if ($ck) {
            $obj->setAlTermek($ck);
        }
        return $obj;
    }

    public function getemptyrow() {
        $view = $this->createView('termektermekkapcsolodokarb.tpl');
        $view->setVar('kapcsolodo', $this->loadVars(null, true));
        echo $view->getTemplateResult();
    }
}