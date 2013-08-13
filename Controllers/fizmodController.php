<?php
namespace Controllers;
use mkw\store;

class fizmodController extends \mkwhelpers\JQGridController {

	public function __construct($params) {
		$this->setEntityName('Entities\Fizmod');
		parent::__construct($params);
	}

	protected function loadCells($sor) {
		return array($sor->getId(),$sor->getNev(),$sor->getTipus(),$sor->getHaladek(),$sor->getWebes(),$sor->getLeiras());
	}

	protected function setFields($obj) {
		$obj->setNev($this->params->getStringRequestParam('nev'));
		$obj->setTipus($this->params->getStringRequestParam('tipus'));
		$obj->setHaladek($this->params->getIntRequestParam('haladek'));
		$obj->setWebes($this->params->getBoolRequestParam('webes'));
		$obj->setLeiras($this->params->getStringRequestParam('leiras'));
		return $obj;
	}

	public function jsonlist() {
		$filter=array();
		if ($this->params->getBoolRequestParam('_search',false)) {
			if (!is_null($this->params->getRequestParam('tipus',NULL))) {
				$filter['fields'][]='tipus';
				$filter['values'][]=$this->params->getStringRequestParam('tipus');
			}
			if (!is_null($this->params->getRequestParam('nev',NULL))) {
				$filter['fields'][]='nev';
				$filter['values'][]=$this->params->getStringRequestParam('nev');
			}
			if (!is_null($this->params->getRequestParam('haladek',NULL))) {
				$filter['fields'][]='haladek';
				$filter['values'][]=$this->params->getIntRequestParam('haladek');
			}
			if (!is_null($this->params->getRequestParam('webes',NULL))) {
				$filter['fields'][]='webes';
				$filter['values'][]=$this->params->getBoolRequestParam('webes');
			}
		}
		$rec=$this->getRepo()->getAll($filter,$this->getOrderArray());
		echo json_encode($this->loadDataToView($rec));
	}

	public function getSelectList($selid) {
		$rec=$this->getRepo()->getAll(array(),array('nev'=>'ASC'));
		$res=array();
		foreach($rec as $sor) {
			$res[]=array(
				'id'=>$sor->getId(),
				'caption'=>$sor->getNev(),
				'selected'=>($sor->getId()==$selid),
				'fizhatido'=>$sor->getHaladek(),
				'bank'=>($sor->getTipus()=='B'?'1':'0')
			);
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