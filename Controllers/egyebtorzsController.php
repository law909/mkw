<?php
namespace Controllers;
use mkw\store;

class egyebtorzsController extends \mkwhelpers\Controller {

	public function __construct($generalDataLoader,$actionName=null,$commandString=null) {
		$this->setTemplateFactory(store::getTemplateFactory());
		parent::__construct($generalDataLoader,$actionName,$commandString);
	}

	protected function view() {
		$view=$this->createView('egyebtorzslista.tpl');
		$this->generalDataLoader->loadData($view);
		$view->setVar('pagetitle',t('Egyéb adatok'));
		$view->printTemplateResult();
	}
}