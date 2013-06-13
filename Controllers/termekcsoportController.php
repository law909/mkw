<?php
namespace Controllers;
use mkw\store;

class termekcsoportController extends \mkwhelpers\JQGridController {

	public function __construct() {
		$this->setEntityName('Entities\TermekCsoport');
		parent::__construct();
	}

	protected function loadCells($sor) {
		return array($sor->getNev(),$sor->getKeszletfkviszam(),$sor->getArbevetelfkviszam(),$sor->getElabefkviszam());
	}

	protected function setFields($obj,$params) {
		$obj->setNev($params->getStringRequestParam('nev'));
		$obj->setKeszletfkviszam($params->getStringRequestParam('keszletfkviszam'));
		$obj->setArbevetelfkviszam($params->getStringRequestParam('arbevetelfkviszam'));
		$obj->setElabefkviszam($params->getStringRequestParam('elabefkviszam'));
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

	public function htmllist() {
		$rec=$this->getRepo()->getAll(array(),array('nev'=>'asc'));
		$ret='<select>';
		foreach($rec as $sor) {
			$ret.='<option value="'.$sor->getId().'">'.$sor->getNev().'</option>';
		}
		$ret.='</select>';
		echo $ret;
	}
}