<?php
namespace mkwhelpers;

define(__NAMESPACE__.'\URLCommand','com');
define(__NAMESPACE__.'\URLCommandSeparator','/');
define(__NAMESPACE__.'\defaultNamespace','Controllers\\');
define(__NAMESPACE__.'\controllerPostfix','Controller');

abstract class Controller {

	private $templateFactory;
	protected $generalDataLoader;
	protected $params;

	public function __construct($params) {
		$this->setTemplateFactory(\mkw\Store::getTemplateFactory());
		$this->generalDataLoader=\mkw\Store::getGdl();
		$this->params=$params;
	}

	protected function setTemplateFactory($path) {
		$this->templateFactory=$path;
	}

	protected function getTemplateFactory() {
		return $this->templateFactory;
	}

	public function createView($tplfilename) {
		$view=$this->getTemplateFactory()->createView($tplfilename);
		$this->generalDataLoader->loadData($view);
		return $view;
	}
}