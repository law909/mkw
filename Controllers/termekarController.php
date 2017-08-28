<?php

namespace Controllers;

use mkw\store;

class termekarController extends \mkwhelpers\MattableController {

    public function __construct($params) {
        $this->setEntityName('Entities\TermekAr');
//		$this->setKarbFormTplName('?howto?karbform.tpl');
//		$this->setKarbTplName('?howto?karb.tpl');
//		$this->setListBodyRowTplName('?howto?lista_tbody_tr.tpl');
//		$this->setListBodyRowVarName('_egyed');
        parent::__construct($params);
    }

    public function loadVars($t, $forKarb = false) {
        $valutanem = new valutanemController($this->params);
        $x = array();
        if (!$t) {
            $t = new \Entities\TermekAr();
            $this->getEm()->detach($t);
            $x['oper'] = 'add';
            $x['id'] = store::createUID();
        }
        else {
            $x['oper'] = 'edit';
            $x['id'] = $t->getId();
        }
        $x['termek'] = $t->getTermek();
        $x['valutanem'] = $t->getValutanem();
        $x['azonosito'] = $t->getAzonosito();
        $x['netto'] = $t->getNetto();
        $x['brutto'] = $t->getBrutto();
        if ($forKarb) {
            $x['valutanemlist'] = $valutanem->getSelectList(($t->getValutanem() ? $t->getValutanemId() : 0));
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
        $view = $this->createView('termektermekarkarb.tpl');
        $view->setVar('ar', $this->loadVars(null, true));
        echo $view->getTemplateResult();
    }

    public function getSelectList($selid = null) {
        $rec = $this->getRepo()->getExistingArsavok();
        $res = array();
        foreach ($rec as $sor) {
            $res[] = array(
                'id' => $sor['azonosito'],
                'caption' => $sor['azonosito'],
                'selected' => ($sor['azonosito'] == $selid),
                'valutanemid' => $sor['valutanemid'],
                'valutanem' => $sor['valutanem']
            );
        }
        return $res;
    }

}
