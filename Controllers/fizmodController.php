<?php
namespace Controllers;
use mkw\store;

class fizmodController extends \mkwhelpers\JQGridController {

	public function __construct() {
		$this->setEntityName('Entities\Fizmod');
		parent::__construct();
	}

	protected function loadCells($sor) {
		return array($sor->getNev(),$sor->getTipus(),$sor->getHaladek(),$sor->getWebes());
	}

	protected function setFields($obj,$params) {
		$obj->setNev($params->getStringRequestParam('nev'));
		$obj->setTipus($params->getStringRequestParam('tipus'));
		$obj->setHaladek($params->getIntRequestParam('haladek'));
		$obj->setWebes($params->getBoolRequestParam('webes'));
		return $obj;
	}

	public function jsonlist($params) {
		$filter=array();
		if ($params->getBoolRequestParam('_search',false)) {
			if (!is_null($params->getRequestParam('tipus',NULL))) {
				$filter['fields'][]='tipus';
				$filter['values'][]=$params->getStringRequestParam('tipus');
			}
			if (!is_null($params->getRequestParam('nev',NULL))) {
				$filter['fields'][]='nev';
				$filter['values'][]=$params->getStringRequestParam('nev');
			}
			if (!is_null($params->getRequestParam('haladek',NULL))) {
				$filter['fields'][]='haladek';
				$filter['values'][]=$params->getIntRequestParam('haladek');
			}
			if (!is_null($params->getRequestParam('webes',NULL))) {
				$filter['fields'][]='webes';
				$filter['values'][]=$params->getBoolRequestParam('webes');
			}
		}
		$rec=$this->getRepo()->getAll($filter,$this->getOrderArray($params));
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