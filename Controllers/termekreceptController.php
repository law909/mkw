<?php
namespace Controllers;

use mkw\store;

class termekreceptController extends \mkwhelpers\MattableController {

    public function __construct($params) {
        $this->setEntityName('Entities\TermekRecept');
//		$this->setKarbFormTplName('?howto?karbform.tpl');
//		$this->setKarbTplName('?howto?karb.tpl');
//		$this->setListBodyRowTplName('?howto?lista_tbody_tr.tpl');
//		$this->setListBodyRowVarName('_egyed');
        parent::__construct($params);
    }

    public function loadVars($t, $forKarb = false) {
        $termek = new termekController($this->params);
        $tipus = new termekrecepttipusController($this->params);
        $x = array();
        if (!$t) {
            $t = new \Entities\TermekRecept();
            $this->getEm()->detach($t);
            $x['oper'] = 'add';
            $x['id'] = store::createUID();
        }
        else {
            $x['oper'] = 'edit';
            $x['id'] = $t->getId();
        }
        $x['mennyiseg'] = $t->getMennyiseg();
        $x['kotelezo'] = $t->getKotelezo();
        $x['termek'] = $t->getTermek();
        $x['termeknev'] = $t->getTermekNev();
        $x['altermek'] = $t->getAlTermek();
        $x['altermeknev'] = $t->getAlTermekNev();
        if ($forKarb) {
            $x['termeklist'] = $termek->getSelectList(($t->getAlTermek() ? $t->getAlTermek()->getId() : 0));
            $x['tipuslist'] = $tipus->getSelectList(($t->getTipusId()));
        }
        return $x;
    }

    /**
     * @param $obj \Entities\TermekRecept
     * @return mixed
     */
    protected function setFields($obj) {
        $obj->setMennyiseg($this->params->getFloatRequestParam('mennyiseg'));
        $obj->setKotelezo($this->params->getBoolRequestParam('kotelezo', false));
        $ck = store::getEm()->getRepository('Entities\Termek')->find($this->params->getIntRequestParam('altermek'));
        if ($ck) {
            $obj->setAlTermek($ck);
        }
        $ck = store::getEm()->getRepository('Entities\TermekReceptTipus')->find($this->params->getIntRequestParam('tipus'));
        if ($ck) {
            $obj->setTipus($ck);
        }
        return $obj;
    }

    public function getemptyrow() {
        $view = $this->createView('termektermekreceptkarb.tpl');
        $view->setVar('recept', $this->loadVars(null, true));
        echo $view->getTemplateResult();
    }
}