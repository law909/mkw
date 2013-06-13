<?php
namespace Controllers;
use mkw\store;

class afaController extends \mkwhelpers\JQGridController {

	public function __construct() {
		$this->setEntityName('Entities\Afa');
		$this->setEm(store::getEm());
		parent::__construct();
	}

	protected function loadCells($sor) {
		return array($sor->getNev(),$sor->getErtek());
	}

	protected function setFields($obj,$params) {
		$obj->setNev($params->getStringRequestParam('nev',$obj->getNev()));
		$obj->setErtek($params->getIntRequestParam('ertek',$obj->getErtek()));
		return $obj;
	}

	public function jsonlist($params) {
		$filter=array();
		if ($params->getBoolRequestParam('_search',false)) {
			if (!is_null($params->getRequestParam('ertek',NULL))) {
				$filter['fields'][]='ertek';
				$filter['values'][]=$params->getIntRequestParam('ertek');
			}
			if (!is_null($params->getParam('nev',NULL))) {
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
				'afakulcs'=>$sor->getErtek()
			);
		}
		return $res;
	}

	protected function htmllist() {
		$rec=$this->getRepo()->getAll(array(),array('nev'=>'asc'));
		$ret='<select>';
		foreach($rec as $sor) {
			$ret.='<option value="'.$sor->getId().'">'.$sor->getNev().'</option>';
		}
		$ret.='</select>';
		echo $ret;
	}
}