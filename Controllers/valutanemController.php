<?php
namespace Controllers;
use mkw\store;

class valutanemController extends \mkwhelpers\JQGridController {

	public function __construct() {
		$this->setEntityName('Entities\Valutanem');
		parent::__construct();
	}

	protected function loadCells($sor) {
		$bank=$sor->getBankszamla();
		return array($sor->getNev(),$sor->getKerekit(),
				$sor->getHivatalos(),$sor->getMincimlet(),
				(isset($bank)?$bank->getSzamlaszam():''));
	}

	protected function setFields($obj,$params) {
		$obj->setNev($params->getStringRequestParam('nev'));
		$obj->setKerekit($params->getBoolRequestParam('kerekit'));
		$obj->setHivatalos($params->getBoolRequestParam('hivatalos'));
		$obj->setMincimlet($params->getIntRequestParam('mincimlet'));
		$bankszla=store::getEm()->getReference('Entities\Bankszamla',$params->getIntRequestParam('bankszamla'));
		try {
			$bankszla->getId();
			$obj->setBankszamla($bankszla);
		}
		catch (\Doctrine\ORM\EntityNotFoundException $e) {
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
			$res[]=array(
				'id'=>$sor->getId(),
				'caption'=>$sor->getNev(),
				'selected'=>($sor->getId()==$selid),
				'bankszamla'=>$sor->getBankszamlaId()
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

	public function getRendszerValuta() {
		$p=$this->getRepo()->find(store::getParameter('valutanem'));
		return $p;
	}
}