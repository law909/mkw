<?php
namespace Controllers;
use mkw\store;

class egyebtorzsController extends \mkwhelpers\Controller {

	public function view() {
		$view=$this->createView('egyebtorzslista.tpl');
		$this->generalDataLoader->loadData($view);
		$view->setVar('pagetitle',t('Egyéb adatok'));
		$view->printTemplateResult();
	}
}