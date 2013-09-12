<?php

namespace Controllers;

use mkw\store;

class raktarController extends \mkwhelpers\JQGridController {

	public function __construct($params) {
		$this->setEntityName('Entities\Raktar');
		parent::__construct($params);
	}

	protected function loadCells($obj) {
		return array($obj->getNev(), $obj->getMozgat());
	}

	protected function setFields($obj) {
		$obj->setNev($this->params->getStringRequestParam('nev', $obj->getNev()));
		$obj->setMozgat($this->params->getBoolRequestParam('mozgat'));
		return $obj;
	}

	public function jsonlist() {
		$filter = array();
		if ($this->params->getBoolRequestParam('_search', false)) {
			$filter['fields'][] = 'nev';
			$filter['values'][] = $this->params->getStringRequestParam('nev');
		}
		$rec = $this->getRepo()->getAll($filter, $this->getOrderArray());
		echo json_encode($this->loadDataToView($rec));
	}

	public function getSelectList($selid) {
		$rec = $this->getRepo()->getAll(array(), array('nev' => 'ASC'));
		$res = array();
		foreach ($rec as $sor) {
			$res[] = array('id' => $sor->getId(), 'caption' => $sor->getNev(), 'selected' => ($sor->getId() == $selid));
		}
		return $res;
	}

}