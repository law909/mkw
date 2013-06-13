<?php
namespace mkwhelpers;

define(__NAMESPACE__.'\URLCommand','com');
define(__NAMESPACE__.'\URLCommandSeparator','/');
define(__NAMESPACE__.'\defaultNamespace','Controllers\\');
define(__NAMESPACE__.'\controllerPostfix','Controller');

abstract class Controller {

	private $rawRequest;
	private $actionName;
	private $commandString;
	private $controllerName='';
	private $templateFactory;
	protected $generalDataLoader;

	public function __construct($generalDataLoader,$actionName=null,$commandString=null) {
		$this->rawRequest=$_REQUEST;
		$this->setActionName($actionName);
		$this->commandString=$commandString;
		$this->generalDataLoader=$generalDataLoader;
	}

	protected function redirectToDefaultController() {
		Header('Location: /');
	}

//	protected function redirectTo404() {
//		Header($_SERVER['SERVER_PROTOCOL'].' 404 Not Found');
//		throw new RedirectTo404Exception();
//	}

	public function controllerExists($controllerName) {
		return class_exists(defaultNamespace.$controllerName,true);
	}

	public function loadController($controllerName) {
		if ($this->controllerExists($controllerName)) {
			$fullname=defaultNamespace.$controllerName;
			return new $fullname($this->generalDataLoader,$this->getActionName(),$this->getCommandString());
		}
		else {
			throw new RedirectTo404Exception();
//			$this->redirectToDefaultController();
		}
	}

	public function adminMethodExists($class,$method) {
		return $this->methodExists('protected',$class,$method);
	}

	public function mainMethodExists($class,$method) {
		return $this->methodExists('public',$class,$method);
	}

	protected function methodExists($type,$class,$method) {
		if (method_exists($class,$method)) {
			$refl=new \ReflectionMethod($class,$method);
			switch ($type) {
				case 'public':
					return $refl->isPublic();
				case 'protected':
					return $refl->isProtected();
			}
		}
		return false;
	}

	public function getControllerName() {
		return $this->controllerName;
	}

	public function setControllerName($controllerName) {
		$this->controllerName=$controllerName.controllerPostfix;
	}

	public function getActionName() {
	    return $this->actionName;
	}

	public function setActionName($actionName) {
		$this->actionName=$actionName;
	}

	public function getCommandString() {
	    return $this->commandString;
	}

	public function setCommandString($commandString) {
	    $this->commandString = $commandString;
	}

	public function getParam($key,$default=NULL) {
		if (array_key_exists($key,$this->rawRequest)) {
			return $this->rawRequest[$key];
		}
		else {
			return $default;
		}
	}

	public function getBoolParam($key,$default=false) {
		$er=$this->getParam($key,$default);
		if ($er) {
			$er=strtolower($er);
			if ($er==='false'||$er==='no'||$er==='off') {
				return false;
			}
			else {
				return true;
			}
		}
		return false;
	}

	public function getNumParam($key,$default=0) {
		$er=$this->getParam($key,$default);
		return $er*1;
	}

	public function getIntParam($key,$default=0) {
		$er=$this->getParam($key,$default);
		return (int)$er;
	}

	public function getFloatParam($key,$default=0.0) {
		$er=$this->getParam($key,$default);
		return (float)$er;
	}

	public function getStringParam($key,$default='') {
		$er=$this->getParam($key,$default);
		return (string)$er;
	}

	public function getDateParam($key,$default='') {
		$er=$this->getParam($key,$default);
		return (string)$er;
	}

	public function getArrayParam($key,$default=array()) {
		$er=$this->getParam($key,$default);
		if (!is_array($er)) {
			return $default;
		}
		return $er;
	}

	public function getRawRequest() {
		return $this->rawRequest;
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