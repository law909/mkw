<?php
namespace Controllers;
use mkw\store;

class nullaslistatetelController extends \mkwhelpers\JQGridController {

	public function __construct($params) {
		$this->setEntityName('Entities\Bizonylattetel');
		parent::__construct($params);
	}

	protected function loadCells($obj) {
		return array($obj->getMedal_vevokod(),$obj->getMedal_serial(),$obj->getTermekNev(),$obj->getMennyiseg(),$obj->getHataridoStr());
	}

	protected function setFields($obj) {
		$termek=$this->getEm()->getRepository('Entities\Termek')->find($params->getIntRequestParam('termek'));
		if ($termek) {
			$obj->setTermek($termek);
		}
		$obj->setMedal_vevokod($this->params->getStringRequestParam('vevokod'));
		$obj->setMedal_serial($this->params->getStringRequestParam('serial'));
		$obj->setMennyiseg($this->params->getFloatRequestParam('mennyiseg',$obj->getMennyiseg()));
		$obj->setHatarido($this->params->getStringRequestParam('hatarido'));
		return $obj;
	}

	public function jsonlist() {
		$filter=array();
		$filter['fields'][]='bizonylatfej';
		$filter['clauses'][]='=';
		$filter['values'][]=$this->params->getStringRequestParam('bizonylatid');
		if ($this->params->getBoolRequestParam('_search',false)) {
			if (!is_null($this->params->getRequestParam('termek',NULL))) {
				$filter['fields'][]='termeknev';
				$filter['clauses'][]='';
				$filter['values'][]=$this->params->getStringRequestParam('termek');
			}
			if (!is_null($this->params->getRequestParam('hatarido',NULL))) {
				$filter['fields'][]='hatarido';
				$filter['clauses'][]='';
				$filter['values'][]=$this->params->getStringRequestParam('hatarido');
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