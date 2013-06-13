<?php
namespace Controllers;
use mkw\store;

class bankszamlaController extends \mkwhelpers\JQGridController {

	public function __construct($generalDataLoader,$actionName=null,$commandString=null) {
		$this->setEntityName('Entities\Bankszamla');
		$this->setEm(store::getEm());
		parent::__construct($generalDataLoader,$actionName,$commandString);
	}

	protected function loadCells($sor) {
		$valuta=$sor->getValutanem();
		return array($sor->getBanknev(),$sor->getBankcim(),
			$sor->getSzamlaszam(),$sor->getIban(),
			$sor->getSwift(),(isset($valuta)?$valuta->getNev():''));
	}

	protected function setFields($obj) {
		$obj->setBanknev($this->getStringParam('banknev'));
		$obj->setBankcim($this->getStringParam('bankcim'));
		$obj->setSzamlaszam($this->getStringParam('szamlaszam'));
		$obj->setSwift($this->getStringParam('swift'));
		$obj->setIban($this->getStringParam('iban'));
		$valutanem=store::getEm()->getReference('Entities\Valutanem',$this->getIntParam('valutanem',0));
		try {
			$valutanem->getId();
			$obj->setValutanem($valutanem);
		}
		catch (\Doctrine\ORM\EntityNotFoundException $e) {
		}
		return $obj;
	}

	protected function jsonlist() {
		$filter=array();
		if ($this->getBoolParam('_search',false)) {
			if (!is_null($this->getParam('banknev',NULL))) {
				$filter['fields'][]='banknev';
				$filter['values'][]=$this->getStringParam('banknev');
			}
			if (!is_null($this->getParam('bankcim',NULL))) {
				$filter['fields'][]='bankcim';
				$filter['values'][]=$this->getStringParam('bankcim');
			}
			if (!is_null($this->getParam('szamlaszam',NULL))) {
				$filter['fields'][]='szamlaszam';
				$filter['values'][]=$this->getStringParam('szamlaszam');
			}
			if (!is_null($this->getParam('swift',NULL))) {
				$filter['fields'][]='swift';
				$filter['values'][]=$this->getStringParam('swift');
			}
			if (!is_null($this->getParam('iban',NULL))) {
				$filter['fields'][]='iban';
				$filter['values'][]=$this->getStringParam('iban');
			}
			if (!is_null($this->getParam('valutanem',NULL))) {
				$filter['fields'][]='v.nev';
				$filter['values'][]=$this->getStringParam('valutanem');
			}
		}
		$rec=$this->getRepo()->getAll($filter,$this->getOrderArray());
		echo json_encode($this->loadDataToView($rec));
	}

	public function getSelectList($selid) {
		$rec=$this->getRepo()->getAll(array(),array('szamlaszam'=>'ASC'));
		$res=array();
		foreach($rec as $sor) {
			$res[]=array('id'=>$sor->getId(),'caption'=>$sor->getSzamlaszam(),'selected'=>($sor->getId()==$selid));
		}
		return $res;
	}

	protected function htmllist() {
		$rec=$this->getRepo()->getAll(array(),array('szamlaszam'=>'ASC'));
		$ret='<select>';
		foreach($rec as $sor) {
			$ret.='<option value="'.$sor->getId().'">'.$sor->getSzamlaszam().'</option>';
		}
		$ret.='</select>';
		echo $ret;
	}
}