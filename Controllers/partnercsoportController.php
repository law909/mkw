<?php
namespace Controllers;
use mkw\store;

class partnercsoportController extends \mkwhelpers\JQGridController {

	public function __construct($generalDataLoader,$actionName=null,$commandString=null) {
		$this->setEntityName('Entities\PartnerCsoport');
		$this->setEm(store::getEm());
		parent::__construct($generalDataLoader,$actionName,$commandString);
	}

	protected function loadCells($sor) {
		return array($sor->getNev(),$sor->getTipusnev(),$sor->getFkviszam());
	}

	protected function setFields($obj) {
		$obj->setNev($this->getStringParam('nev'));
		$obj->setTipus($this->getStringParam('tipus'));
		$obj->setFkviszam($this->getStringParam('fkviszam'));
		return $obj;
	}

	protected function jsonlist() {
		$filter=array();
		if ($this->getBoolParam('_search',false)) {
			$filter['fields'][]='tipus';
			$filter['values'][]=$this->getStringParam('tipus');
			$filter['fields'][]='nev';
			$filter['values'][]=$this->getStringParam('nev');
			$filter['fields'][]='fkviszam';
			$filter['values'][]=$this->getStringParam('fkviszam');
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