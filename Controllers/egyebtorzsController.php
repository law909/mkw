<?php
namespace Controllers;
use matt, matt\Exceptions, Entities, mkw\store;

class egyebtorzsController extends matt\Controller {

	public function __construct($generalDataLoader,$actionName=null,$commandString=null) {
		$this->setTemplateFactory(store::getTemplateFactory());
		parent::__construct($generalDataLoader,$actionName,$commandString);
	}

	public function handleRequest() {
		$methodname=$this->getActionName();
		if ($this->mainMethodExists(__CLASS__,$methodname)) {
			$this->$methodname();
		}
		elseif ($this->adminMethodExists(__CLASS__,$methodname)) {
				$this->$methodname();
		}
		else {
			throw new matt\Exceptions\UnknownMethodException('"'.__CLASS__.'->'.$methodname.'" does not exist.');
		}
	}

	protected function view() {
		$view=$this->createView('egyebtorzslista.tpl');
		$this->generalDataLoader->loadData($view);
		$view->setVar('pagetitle',t('Egyéb adatok'));
		$view->printTemplateResult();
	}
}