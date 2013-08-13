<?php
namespace Controllers;

use mkw\store;

class szallitasimodController extends \mkwhelpers\JQGridController {

	public function __construct($params) {
		$this->setEntityName('Entities\Szallitasimod');
		parent::__construct($params);
	}

	protected function loadCells($obj) {
		return array($obj->getNev(),$obj->getWebes(),$obj->getLeiras(),$obj->getFizmodok());
	}

	protected function setFields($obj) {
		$obj->setNev($this->params->getStringRequestParam('nev',$obj->getNev()));
		$obj->setWebes($this->params->getBoolRequestParam('webes'));
		$obj->setLeiras($this->params->getStringRequestParam('leiras'));
		$obj->setFizmodok($this->params->getStringRequestParam('fizmodok'));
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
		$rec=$this->getRepo()->getAllWebes();
		$res=array();
		$vanvalasztott=false;
		foreach($rec as $sor) {
			$r=array(
				'id'=>$sor->getId(),
				'caption'=>$sor->getNev(),
				'leiras'=>$sor->getLeiras()
			);
			if ($selid) {
				$r['selected']=$sor->getId()==$selid;
			}
			else {
				if (!$vanvalasztott) {
					$r['selected']=true;
					$vanvalasztott=true;
				}
				else {
					$r['selected']=false;
				}
			}
			$res[]=$r;
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