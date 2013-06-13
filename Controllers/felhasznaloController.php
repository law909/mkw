<?php
namespace Controllers;
use mkw\store;

class felhasznaloController extends \mkwhelpers\JQGridController {

	public function __construct() {
		$this->setEntityName('Entities\Felhasznalo');
		parent::__construct();
	}

	protected function loadCells($obj) {
		return array($obj->getNev(),$obj->getFelhasznalonev(),$obj->getJelszo(),$obj->getUzletkotoNev());
	}

	protected function setFields($obj,$params) {
		$obj->setNev($params->getStringRequestParam('nev',$obj->getNev()));
		$obj->setFelhasznalonev($params->getStringRequestParam('felhasznalonev',$obj->getFelhasznalonev()));
		$obj->setJelszo($params->getStringRequestParam('jelszo',$obj->getJelszo()));
		$ck=store::getEm()->getRepository('Entities\Uzletkoto')->find($params->getIntRequestParam('uzletkoto',0));
		if ($ck) {
			$obj->setUzletkoto($ck);
		}
		else {
		}
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