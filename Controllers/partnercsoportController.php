<?php
namespace Controllers;
use mkw\store;

class partnercsoportController extends \mkwhelpers\JQGridController {

	public function __construct() {
		$this->setEntityName('Entities\PartnerCsoport');
		parent::__construct();
	}

	protected function loadCells($sor) {
		return array($sor->getNev(),$sor->getTipusnev(),$sor->getFkviszam());
	}

	protected function setFields($obj,$params) {
		$obj->setNev($params->getStringRequestParam('nev'));
		$obj->setTipus($params->getStringRequestParam('tipus'));
		$obj->setFkviszam($params->getStringRequestParam('fkviszam'));
		return $obj;
	}

	public function jsonlist($params) {
		$filter=array();
		if ($params->getBoolRequestParam('_search',false)) {
			$filter['fields'][]='tipus';
			$filter['values'][]=$params->getStringRequestParam('tipus');
			$filter['fields'][]='nev';
			$filter['values'][]=$params->getStringRequestParam('nev');
			$filter['fields'][]='fkviszam';
			$filter['values'][]=$params->getStringRequestParam('fkviszam');
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