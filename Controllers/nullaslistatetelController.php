<?php
namespace Controllers;
use matt, matt\Exceptions, mkw\store;

class nullaslistatetelController extends matt\JQGridController {

	public function __construct($generalDataLoader,$actionName=null,$commandString=null) {
		$this->setEntityName('Entities\Bizonylattetel');
		$this->setEm(store::getEm());
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
			throw new \matt\Exceptions\UnknownMethodException('"'.__CLASS__.'->'.$methodname.'" does not exist.');
		}
	}

	protected function loadCells($obj) {
		return array($obj->getMedal_vevokod(),$obj->getMedal_serial(),$obj->getTermekNev(),$obj->getMennyiseg(),$obj->getHataridoStr());
	}

	protected function setFields($obj) {
		$termek=$this->getEm()->getRepository('Entities\Termek')->find($this->getIntParam('termek'));
		if ($termek) {
			$obj->setTermek($termek);
		}
		$obj->setMedal_vevokod($this->getStringParam('vevokod'));
		$obj->setMedal_serial($this->getStringParam('serial'));
		$obj->setMennyiseg($this->getFloatParam('mennyiseg',$obj->getMennyiseg()));
		$obj->setHatarido($this->getStringParam('hatarido'));
		return $obj;
	}

	protected function jsonlist() {
		$filter=array();
		$filter['fields'][]='bizonylatfej';
		$filter['clauses'][]='=';
		$filter['values'][]=$this->getStringParam('bizonylatid');
		if ($this->getBoolParam('_search',false)) {
			if (!is_null($this->getParam('termek',NULL))) {
				$filter['fields'][]='termeknev';
				$filter['clauses'][]='';
				$filter['values'][]=$this->getStringParam('termek');
			}
			if (!is_null($this->getParam('hatarido',NULL))) {
				$filter['fields'][]='hatarido';
				$filter['clauses'][]='';
				$filter['values'][]=$this->getStringParam('hatarido');
			}
		}
		$rec=$this->getRepo()->getWithJoins($filter,$this->getOrderArray());
		echo json_encode($this->loadDataToView($rec));
	}

	/* MINTA ha nem kell, dobd ki
	public function getSelectList($selid) {
		$rec=$this->getRepo()->getAll(array(),array('nev'=>'ASC'));
		$res=array();
		foreach($rec as $sor) {
			$res[]=array('id'=>$sor->getId(),'caption'=>$sor->getNev(),'selected'=>($sor->getId()==$selid));
		}
		return $res;
	}
	*/
}