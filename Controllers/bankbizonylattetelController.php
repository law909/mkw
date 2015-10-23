<?php

namespace Controllers;

use mkw\store;

class bankbizonylattetelController extends \mkwhelpers\MattableController {

	public function __construct($params) {
		$this->setEntityName('Entities\Bankbizonylattetel');
//		$this->setKarbFormTplName('?howto?karbform.tpl');
//		$this->setKarbTplName('?howto?karb.tpl');
//		$this->setListBodyRowTplName('?howto?lista_tbody_tr.tpl');
//		$this->setListBodyRowVarName('_egyed');
		parent::__construct($params);
	}

	public function loadVars($t, $forKarb = false) {
        $oper = $this->params->getStringRequestParam('oper');
		$termek = new termekController($this->params);
		$vtsz = new vtszController($this->params);
		$afa = new afaController($this->params);
		$x = array();
		if (!$t) {
			$t = new \Entities\Bankbizonylattetel();
			$this->getEm()->detach($t);
			$x['id'] = store::createUID();
			$x['oper'] = 'add';
		}
		else {
			$x['id'] = $t->getId();
			$x['oper'] = 'edit';
		}
        $x['netto'] = $t->getNetto();
        $x['afa'] = $t->getAfa();
        $x['brutto'] = $t->getBrutto();

        $x['mainurl'] = store::getConfigValue('mainurl');
		return $x;
	}

	protected function setFields($obj) {
		return $obj;
	}

	public function getemptyrow() {
        $biztipus = $this->params->getStringRequestParam('type');
		$view = $this->createView('bankbizonylattetelkarb.tpl');
		$view->setVar('tetel', $this->loadVars(null, true));
        $bt = $this->getRepo('Entities\Bizonylattipus')->find($biztipus);
        $bt->setTemplateVars($view);
		echo $view->getTemplateResult();
	}

}