<?php
namespace Controllers;
use mkw\store;

class fizmodController extends \mkwhelpers\JQGridController {

	public function __construct($params) {
		$this->setEntityName('Entities\Fizmod');
		parent::__construct($params);
	}

	protected function loadCells($sor) {
		return array($sor->getId(),$sor->getNev(),$sor->getTipus(),$sor->getHaladek(),$sor->getWebes(),$sor->getLeiras(),$sor->getSorrend());
	}

	protected function setFields($obj) {
		$obj->setNev($this->params->getStringRequestParam('nev'));
		$obj->setTipus($this->params->getStringRequestParam('tipus'));
		$obj->setHaladek($this->params->getIntRequestParam('haladek'));
		$obj->setWebes($this->params->getBoolRequestParam('webes'));
		$obj->setLeiras($this->params->getStringRequestParam('leiras'));
		$obj->setSorrend($this->params->getIntRequestParam('sorrend'));
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

	public function getSelectList($selid=null, $szallmod=null, $exc=null) {
		$rec=$this->getRepo()->getAllWebesBySzallitasimod($szallmod,$exc);
		$res=array();
		$vanvalasztott=false;
		foreach($rec as $sor) {
			$r=array(
				'id'=>$sor->getId(),
				'caption'=>$sor->getNev(),
				'fizhatido'=>$sor->getHaladek(),
				'leiras'=>$sor->getLeiras(),
				'bank'=>($sor->getTipus()=='B'?'1':'0')
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