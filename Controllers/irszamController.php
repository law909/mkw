<?php
namespace Controllers;

use mkw\store;

class irszamController extends \mkwhelpers\JQGridController {

	public function __construct($params) {
		$this->setEntityName('Entities\Irszam');
		parent::__construct($params);
	}

	protected function loadCells($obj) {
		return array($obj->getId(), $obj->getNev());
	}

	protected function setFields($obj) {
		$obj->setId($this->params->getStringRequestParam('id', $obj->getId()));
		$obj->setNev($this->params->getStringRequestParam('nev', $obj->getNev()));
		return $obj;
	}

	public function jsonlist() {
		$filter=array();
		if ($this->params->getBoolRequestParam('_search',false)) {
			if (!is_null($this->params->getRequestParam('id',NULL))) {
				$filter['fields'][]='id';
				$filter['values'][]=$this->params->getStringRequestParam('id');
			}
			if (!is_null($this->params->getRequestParam('nev',NULL))) {
				$filter['fields'][]='nev';
				$filter['values'][]=$this->params->getStringRequestParam('nev');
			}
		}
		$rec=$this->getRepo()->getAll($filter,$this->getOrderArray());
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