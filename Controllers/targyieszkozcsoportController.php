<?php
namespace Controllers;
use mkw\store;

class targyieszkozcsoportController extends \mkwhelpers\JQGridController {

	public function __construct($params) {
		$this->setEntityName('Entities\TargyieszkozCsoport');
		parent::__construct($params);
	}

	protected function loadCells($sor) {
		return array($sor->getNev(),$sor->getBeszerzesiktgfkviszam(),$sor->getEcsleirasfkviszam(),$sor->getEcsktgfkviszam());
	}

	protected function setFields($obj) {
		$obj->setNev($this->params->getStringRequestParam('nev'));
		$obj->setBeszerzesiktgfkviszam($this->params->getStringRequestParam('beszerzesiktgfkviszam'));
		$obj->setEcsleirasfkviszam($this->params->getStringRequestParam('ecsleirasfkviszam'));
		$obj->setEcsktgfkviszam($this->params->getStringRequestParam('ecsktgfkviszam'));
		return $obj;
	}

	public function jsonlist() {
		$filter=array();
		if ($this->params->getBoolRequestParam('_search',false)) {
			if (!is_null($this->params->getRequestParam('nev',NULL))) {
				$filter['fields'][]='nev';
				$filter['values'][]=$this->params->getStringRequestParam('nev');
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