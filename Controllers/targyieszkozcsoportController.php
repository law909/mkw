<?php
namespace Controllers;
use mkw\store;

class targyieszkozcsoportController extends \mkwhelpers\JQGridController {

	public function __construct($generalDataLoader,$actionName=null,$commandString=null) {
		$this->setEntityName('Entities\TargyieszkozCsoport');
		$this->setEm(store::getEm());
		parent::__construct($generalDataLoader,$actionName,$commandString);
	}

	protected function loadCells($sor) {
		return array($sor->getNev(),$sor->getBeszerzesiktgfkviszam(),$sor->getEcsleirasfkviszam(),$sor->getEcsktgfkviszam());
	}

	protected function setFields($obj) {
		$obj->setNev($this->getStringParam('nev'));
		$obj->setBeszerzesiktgfkviszam($this->getStringParam('beszerzesiktgfkviszam'));
		$obj->setEcsleirasfkviszam($this->getStringParam('ecsleirasfkviszam'));
		$obj->setEcsktgfkviszam($this->getStringParam('ecsktgfkviszam'));
		return $obj;
	}

	protected function jsonlist() {
		$filter=array();
		if ($this->getBoolParam('_search',false)) {
			if (!is_null($this->getParam('nev',NULL))) {
				$filter['fields'][]='nev';
				$filter['values'][]=$this->getStringParam('nev');
			}
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

	protected function htmllist() {
		$rec=$this->getRepo()->getAll(array(),array('nev'=>'asc'));
		$ret='<select>';
		foreach($rec as $sor) {
			$ret.='<option value="'.$sor->getId().'">'.$sor->getNev().'</option>';
		}
		$ret.='</select>';
		echo $ret;
	}
}