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
        $partner = new partnerController($this->params);
		$jogcim = new jogcimController($this->params);
        $valutanem = new valutanemController($this->params);
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
        $x['datumstr'] = $t->getDatumStr();
        $x['netto'] = $t->getNetto();
        $x['afa'] = $t->getAfa();
        $x['brutto'] = $t->getBrutto();
        $x['partner'] = $t->getPartnerId();
        $x['partnernev'] = $t->getPartnernev();
        $x['jogcim'] = $t->getJogcimId();
        $x['jogcimnev'] = $t->getJogcimnev();
        $x['hivatkozottdatumstr'] = $t->getHivatkozottdatumStr();
        $x['hivatkozottbizonylat'] = $t->getHivatkozottbizonylat();
        $x['valutanem'] = $t->getValutanemId();
        $x['valutanemnev'] = $t->getValutanemnev();

        if ($forKarb) {
            $x['partnerlist'] = $partner->getSelectList($t->getPartnerId());
            $x['jogcimlist'] = $jogcim->getSelectList($t->getJogcimId());
            $x['valutanemlist'] = $valutanem->getSelectList($t->getValutanemId());
        }

		return $x;
	}

	protected function setFields($obj) {
		return $obj;
	}

	public function getemptyrow() {
        $biztipus = $this->params->getStringRequestParam('type');
		$view = $this->createView('bankbizonylattetelkarb.tpl');

        $tetel = $this->loadVars(null, true);
		$view->setVar('tetel', $tetel);

        $bt = $this->getRepo('Entities\Bizonylattipus')->find($biztipus);
        $bt->setTemplateVars($view);

        $res = array(
            'html' => $view->getTemplateResult(),
            'id' => $tetel['id']
        );
		echo json_encode($res);
	}

}