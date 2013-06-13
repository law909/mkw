<?php
namespace Controllers;
use mkw\store;

class nullaslistatetelController extends \mkwhelpers\JQGridController {

	public function __construct() {
		$this->setEntityName('Entities\Bizonylattetel');
		parent::__construct();
	}

	protected function loadCells($obj) {
		return array($obj->getMedal_vevokod(),$obj->getMedal_serial(),$obj->getTermekNev(),$obj->getMennyiseg(),$obj->getHataridoStr());
	}

	protected function setFields($obj,$params) {
		$termek=$this->getEm()->getRepository('Entities\Termek')->find($params->getIntRequestParam('termek'));
		if ($termek) {
			$obj->setTermek($termek);
		}
		$obj->setMedal_vevokod($params->getStringRequestParam('vevokod'));
		$obj->setMedal_serial($params->getStringRequestParam('serial'));
		$obj->setMennyiseg($params->getFloatRequestParam('mennyiseg',$obj->getMennyiseg()));
		$obj->setHatarido($params->getStringRequestParam('hatarido'));
		return $obj;
	}

	public function jsonlist($params) {
		$filter=array();
		$filter['fields'][]='bizonylatfej';
		$filter['clauses'][]='=';
		$filter['values'][]=$params->getStringRequestParam('bizonylatid');
		if ($params->getBoolRequestParam('_search',false)) {
			if (!is_null($params->getRequestParam('termek',NULL))) {
				$filter['fields'][]='termeknev';
				$filter['clauses'][]='';
				$filter['values'][]=$params->getStringRequestParam('termek');
			}
			if (!is_null($params->getRequestParam('hatarido',NULL))) {
				$filter['fields'][]='hatarido';
				$filter['clauses'][]='';
				$filter['values'][]=$params->getStringRequestParam('hatarido');
			}
		}
		$rec=$this->getRepo()->getWithJoins($filter,$this->getOrderArray($params));
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