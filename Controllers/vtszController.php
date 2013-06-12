<?php
namespace Controllers;
use matt, matt\Exceptions, Entities, SIIKerES\store;

class vtszController extends matt\JQGridController {

	public function __construct($generalDataLoader,$actionName=null,$commandString=null) {
		$this->setEntityName('Entities\Vtsz');
		$this->setEm(store::getEm());
		parent::__construct($generalDataLoader,$actionName,$commandString);
	}

	public function handleRequest() {
		$methodname=$this->getActionName();
		if ($this->mainMethodExists(__CLASS__,$methodname)) {
			$this->$methodname();
		}
		elseif ($this->adminMethodExists(__CLASS__,$methodname)) {
				$this->$methodname();
		}
		else {
			throw new matt\Exceptions\UnknownMethodException('"'.__CLASS__.'->'.$methodname.'" does not exist.');
		}
	}

	protected function loadCells($sor) {
		$afa=$sor->getAfa();
		return array(
				$sor->getNev(),
				(isset($afa)?$afa->getNev():''),
				$sor->getKozvetitett());
	}

	protected function setFields($obj) {
		try {
			$obj->setNev($this->getStringParam('nev',$obj->getNev()));
			$afa=store::getEm()->getReference('Entities\Afa',$this->getIntParam('afa',$obj->getAfa()));
			$obj->setAfa($afa);
			$obj->setKozvetitett($this->getBoolParam('kozvetitett'));
			return $obj;
		}
		catch (matt\Exceptions\WrongValueTypeException $e){
		}
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