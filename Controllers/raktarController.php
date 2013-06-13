<?php
namespace Controllers;
use mkw\store;

class raktarController extends \mkwhelpers\JQGridController {

	public function __construct($generalDataLoader,$actionName=null,$commandString=null) {
		$this->setEntityName('Entities\Raktar');
		$this->setEm(store::getEm());
		parent::__construct($generalDataLoader,$actionName,$commandString);
	}

	protected function loadCells($obj) {
		return array($obj->getNev(),$obj->getMozgat());
	}

	protected function setFields($obj) {
		$obj->setNev($this->getStringParam('nev',$obj->getNev()));
		$obj->setMozgat($this->getBoolParam('mozgat',$obj->getMozgat()));
		return $obj;
	}

	protected function jsonlist() {
		$filter=array();
		if ($this->getBoolParam('_search',false)) {
			$filter['fields'][]='nev';
			$filter['values'][]=$this->getStringParam('nev');
		}
		$rec=$this->getRepo()->getAll($filter,$this->getOrderArray());
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