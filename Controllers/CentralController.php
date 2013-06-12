<?php
namespace Controllers;
use matt;

class CentralController extends matt\Controller {

	public function handleRequest() {
		$command='';
		
		$x=$this->getStringParam(matt\URLCommand,'');
		if (isset($x)) {
			$command=$this->getStringParam(matt\URLCommand,'');
		}
		
		$commandpieces=explode(matt\URLCommandSeparator,$command);
		$nam=array_shift($commandpieces);
		if ($nam=='admin') {
			$this->setControllerName($nam);
			$this->setActionName(array_shift($commandpieces));
		}
		else {
			$this->setControllerName('main');
			$this->setActionName($nam);
		}
		$this->setCommandString(implode(matt\URLCommandSeparator,$commandpieces));

		$ctrl=$this->loadController($this->getControllerName());
		if ($ctrl) {
			$ctrl->handleRequest();
		}
	}
}