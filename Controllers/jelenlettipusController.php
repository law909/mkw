<?php
namespace Controllers;
use mkw\store;

class jelenlettipusController extends \mkwhelpers\JQGridController {

	public function __construct() {
		$this->setEntityName('Entities\Jelenlettipus');
		parent::__construct();
	}

	protected function loadCells($obj) {
		return array($obj->getNev());
	}

	protected function setFields($obj,$params) {
		$obj->setNev($params->getStringRequestParam('nev',$obj->getNev()));
		return $obj;
	}

	public function jsonlist($params) {
		$filter=array();
		if ($params->getBoolRequestParam('_search',false)) {
			if (!is_null($params->getRequestParam('nev',NULL))) {
				$filter['fields'][]='nev';
				$filter['values'][]=$params->getStringRequestParam('nev');
			}
		}
		$rec=$this->getRepo()->getAll($filter,$this->getOrderArray($params));
		echo json_encode($this->loadDataToView($rec));
	}

	public function getSelectList($selid) {
		$rec=$this->getRepo()->getAll(array(),array('nev'=>'ASC'));
		$res=array();
		foreach($rec as $sor) {
			$res[]=array('id'=>$sor->getId(),'caption'=>$sor->getNev(),'selected'=>($sor->getId()==$selid));
		}
		return $res;
	}
}