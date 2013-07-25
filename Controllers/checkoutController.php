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
		$view->printTemplateResult();
	}
}