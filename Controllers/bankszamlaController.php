<?php
namespace Controllers;
use mkw\store;

class bankszamlaController extends \mkwhelpers\JQGridController {

	public function __construct($params) {
		$this->setEntityName('Entities\Bankszamla');
		parent::__construct($params);
	}

	protected function loadCells($sor) {
		$valuta=$sor->getValutanem();
		return array($sor->getBanknev(),$sor->getBankcim(),
			$sor->getSzamlaszam(),$sor->getIban(),
			$sor->getSwift(),(isset($valuta)?$valuta->getNev():''));
	}

	protected function setFields($obj) {
		$obj->setBanknev($this->params->getStringRequestParam('banknev'));
		$obj->setBankcim($this->params->getStringRequestParam('bankcim'));
		$obj->setSzamlaszam($this->params->getStringRequestParam('szamlaszam'));
		$obj->setSwift($this->params->getStringRequestParam('swift'));
		$obj->setIban($this->params->getStringRequestParam('iban'));
		$valutanem=store::getEm()->getReference('Entities\Valutanem',$this->params->getIntRequestParam('valutanem',0));
		try {
			$valutanem->getId();
			$obj->setValutanem($valutanem);
		}
		catch (\Doctrine\ORM\EntityNotFoundException $e) {
		}
		return $obj;
	}

	public function jsonlist() {
		$filter=array();
		if ($this->params->getBoolRequestParam('_search',false)) {
			if (!is_null($this->params->getRequestParam('banknev',NULL))) {
				$filter['fields'][]='banknev';
				$filter['values'][]=$this->params->getStringRequestParam('banknev');
			}
			if (!is_null($this->getParam('bankcim',NULL))) {
				$filter['fields'][]='bankcim';
				$filter['values'][]=$this->params->getStringRequestParam('bankcim');
			}
			if (!is_null($this->getParam('szamlaszam',NULL))) {
				$filter['fields'][]='szamlaszam';
				$filter['values'][]=$this->params->getStringRequestParam('szamlaszam');
			}
			if (!is_null($this->getParam('swift',NULL))) {
				$filter['fields'][]='swift';
				$filter['values'][]=$this->params->getStringRequestParam('swift');
			}
			if (!is_null($this->getParam('iban',NULL))) {
				$filter['fields'][]='iban';
				$filter['values'][]=$this->params->getStringRequestParam('iban');
			}
			if (!is_null($this->getParam('valutanem',NULL))) {
				$filter['fields'][]='v.nev';
				$filter['values'][]=$this->params->getStringRequestParam('valutanem');
			}
		}
		$rec=$this->getRepo()->getAll($filter,$this->getOrderArray());
		echo json_encode($this->loadDataToView($rec));
	}

	public function getSelectList($selid) {
		$rec=$this->getRepo()->getAll(array(),array('szamlaszam'=>'ASC'));
		$res=array();
		foreach($rec as $sor) {
			$res[]=array('id'=>$sor->getId(),'caption'=>$sor->getSzamlaszam(),'selected'=>($sor->getId()==$selid));
		}
		return $res;
	}

	public function htmllist() {
		$rec=$this->getRepo()->getAll(array(),array('szamlaszam'=>'ASC'));
		$ret='<select>';
		foreach($rec as $sor) {
			$ret.='<option value="'.$sor->getId().'">'.$sor->getSzamlaszam().'</option>';
		}
		$ret.='</select>';
		echo $ret;
	}
}