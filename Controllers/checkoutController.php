<?php
namespace Controllers;

use mkw\Store;

class checkoutController extends \mkwhelpers\MattableController {

	public function __construct($params) {
		$this->setEntityName('Entities\Kosar');
		parent::__construct($params);
	}

	public function getCheckout() {
		$view=Store::getTemplateFactory()->createMainView('checkout.tpl');
		Store::fillTemplate($view);
		$szm=new szallitasimodController($this->params);
		$szlist=$szm->getSelectList(null);
		$view->setVar('szallitasimodlist',$szlist);
		$view->setVar('showerror', Store::getMainSession()->loginerror);
		$view->setVar('showaszflink', Store::getRouter()->generate('showstatlappopup',false,array('lap'=>'aszf')));
		Store::getMainSession()->loginerror = false;
		$view->printTemplateResult();
	}

	public function getFizmodList() {
		$view=Store::getTemplateFactory()->createMainView('checkoutfizmodlist.tpl');
		$szm=new fizmodController($this->params);
		$szlist=$szm->getSelectList(null,$this->params->getIntRequestParam('szallitasimod'));
		$view->setVar('fizmodlist',$szlist);
		echo $view->getTemplateResult();
	}
}