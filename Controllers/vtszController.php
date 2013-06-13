<?php
namespace Controllers;
use mkw\store;

class vtszController extends \mkwhelpers\JQGridController {

	public function __construct() {
		$this->setEntityName('Entities\Vtsz');
		parent::__construct();
	}

	protected function loadCells($sor) {
		$afa=$sor->getAfa();
		return array(
				$sor->getNev(),
				(isset($afa)?$afa->getNev():''),
				$sor->getKozvetitett());
	}

	protected function setFields($obj,$params) {
		$obj->setNev($params->getStringRequestParam('nev',$obj->getNev()));
		$afa=store::getEm()->getReference('Entities\Afa',$params->getIntRequestParam('afa',$obj->getAfa()));
		$obj->setAfa($afa);
		$obj->setKozvetitett($params->getBoolRequestParam('kozvetitett'));
		return $obj;
	}

	public function jsonlist($params) {
		$filter=array();
		if ($params->getBoolRequestParam('_search',false)) {
			if (!is_null($params->getRequestParam('afa',NULL))) {
				$filter['fields'][]='a.nev';
				$filter['values'][]=$params->getStringRequestParam('afa');
			}
			if (!is_null($params->getRequestParam('nev',NULL))) {
				$filter['fields'][]='nev';
				$filter['values'][]=$params->getStringRequestParam('nev');
			}
		}
		$rec=$this->getRepo()->getAll($filter,$this->getOrderArray($params));
		echo json_encode($this->loadDataToView($rec));
	}

	public function getSelectList($selid) {
		// TODO ezen gyorsitani kell, getAll helyett ScalarResult
		$rec=$this->getRepo()->getAll(array(),array('nev'=>'ASC'));
		$res=array();
		foreach($rec as $sor) {
			$res[]=array(
				'id'=>$sor->getId(),
				'caption'=>$sor->getNev(),
				'selected'=>($sor->getId()==$selid),
				'afa'=>$sor->getAfaId()
			);
		}
		return $res;
	}
}