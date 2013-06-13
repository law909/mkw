<?php
namespace Controllers;
use mkwhelpers;

class CentralController extends mkwhelpers\Controller {

	public function handleRequest() {
		$command='';

		$x=$this->getStringParam(mkwhelpers\URLCommand,'');
		if (isset($x)) {
			$command=$this->getStringParam(mkwhelpers\URLCommand,'');
		}

		$commandpieces=explode(mkwhelpers\URLCommandSeparator,$command);
		$nam=array_shift($commandpieces);
		if ($nam=='admin') {
			$this->setControllerName($nam);
			$this->setActionName(array_shift($commandpieces));
		}
		else {
			$this->setControllerName('main');
			$this->setActionName($nam);
		}
		$this->setCommandString(implode(mkwhelpers\URLCommandSeparator,$commandpieces));

		$ctrl=$this->loadController($this->getControllerName());
		if ($ctrl) {
			$ctrl->handleRequest();
		}
	}
}