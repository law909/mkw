<?php
namespace Controllers;

use mkw\store;

class termekertesitoController extends \mkwhelpers\MattableController {

	public function __construct($params) {
		$this->setEntityName('Entities\TermekErtesito');
		parent::__construct($params);
	}

	protected function setFields($obj) {
		$termek=$this->getEm()->getRepository('Entities\Termek')->find($this->params->getIntRequestParam('termekid'));
		if ($termek) {
			$obj->setTermek($termek);
			$pc=new partnerController($this->params);
			$partner=$pc->getLoggedInUser();
			if ($partner) {
				$obj->setPartner($partner);
			}
			$obj->setEmail($this->params->getStringRequestParam('email'));
		}
		return $obj;
	}
}