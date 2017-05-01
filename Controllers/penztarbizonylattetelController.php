<?php

namespace Controllers;

use mkw\store;

class penztarbizonylattetelController extends \mkwhelpers\MattableController {

	public function __construct($params) {
		$this->setEntityName('Entities\Penztarbizonylattetel');
//		$this->setKarbFormTplName('?howto?karbform.tpl');
//		$this->setKarbTplName('?howto?karb.tpl');
//		$this->setListBodyRowTplName('?howto?lista_tbody_tr.tpl');
//		$this->setListBodyRowVarName('_egyed');
		parent::__construct($params);
	}

	public function loadVars($t, $forKarb = false) {
        $oper = $this->params->getStringRequestParam('oper');
        $partner = new partnerController($this->params);
		$jogcim = new jogcimController($this->params);
		$x = array();
		if (!$t) {
			$t = new \Entities\Penztarbizonylattetel();
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
        $x['jogcim'] = $t->getJogcimId();
        $x['jogcimnev'] = $t->getJogcimnev();
        $x['hivatkozottdatumstr'] = $t->getHivatkozottdatumStr();
        $x['hivatkozottbizonylat'] = $t->getHivatkozottbizonylat();
        $x['szoveg'] = $t->getSzoveg();

        if ($forKarb) {
            $x['jogcimlist'] = $jogcim->getSelectList($t->getJogcimId());
        }

		return $x;
	}

	protected function setFields($obj) {
		return $obj;
	}

	public function getemptyrow() {
		$view = $this->createView('penztarbizonylattetelkarb.tpl');

        $tetel = $this->loadVars(null, true);
		$view->setVar('tetel', $tetel);

        $bt = $this->getRepo('Entities\Bizonylattipus')->find('penztar');
        $bt->setTemplateVars($view);

        $res = array(
            'html' => $view->getTemplateResult(),
            'id' => $tetel['id']
        );
		echo json_encode($res);
	}

}