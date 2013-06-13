<?php
namespace Controllers;
use mkw\store;

class vtszController extends \mkwhelpers\JQGridController {

	public function __construct($generalDataLoader,$actionName=null,$commandString=null) {
		$this->setEntityName('Entities\Vtsz');
		$this->setEm(store::getEm());
		parent::__construct($generalDataLoader,$actionName,$commandString);
	}

	protected function loadCells($sor) {
		$afa=$sor->getAfa();
		return array(
				$sor->getNev(),
				(isset($afa)?$afa->getNev():''),
				$sor->getKozvetitett());
	}

	protected function setFields($obj) {
		$obj->setNev($this->getStringParam('nev',$obj->getNev()));
		$afa=store::getEm()->getReference('Entities\Afa',$this->getIntParam('afa',$obj->getAfa()));
		$obj->setAfa($afa);
		$obj->setKozvetitett($this->getBoolParam('kozvetitett'));
		return $obj;
	}

	protected function jsonlist() {
		$filter=array();
		if ($this->getBoolParam('_search',false)) {
			if (!is_null($this->getParam('afa',NULL))) {
				$filter['fields'][]='a.nev';
				$filter['values'][]=$this->getStringParam('afa');
			}
			if (!is_null($this->getParam('nev',NULL))) {
				$filter['fields'][]='nev';
				$filter['values'][]=$this->getStringParam('nev');
			}
		}
		$rec=$this->getRepo()->getAll($filter,$this->getOrderArray());
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