<?php
namespace Controllers;
use matt, matt\Exceptions, Entities, SIIKerES\store;

class fizmodController extends matt\JQGridController {

	public function __construct($generalDataLoader,$actionName=null,$commandString=null) {
		$this->setEntityName('Entities\Fizmod');
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
		return array($sor->getNev(),$sor->getTipus(),$sor->getHaladek(),$sor->getWebes());
	}

	protected function setFields($obj) {
		try {
			$obj->setNev($this->getStringParam('nev'));
			$obj->setTipus($this->getStringParam('tipus'));
			$obj->setHaladek($this->getIntParam('haladek'));
			$obj->setWebes($this->getBoolParam('webes'));
		}
		catch (matt\Exceptions\WrongValueTypeException $e){
		}
		return $obj;
	}

	protected function jsonlist() {
		$filter=array();
		if ($this->getBoolParam('_search',false)) {
			if (!is_null($this->getParam('tipus',NULL))) {
				$filter['fields'][]='tipus';
				$filter['values'][]=$this->getStringParam('tipus');
			}
			if (!is_null($this->getParam('nev',NULL))) {
				$filter['fields'][]='nev';
				$filter['values'][]=$this->getStringParam('nev');
			}
			if (!is_null($this->getParam('haladek',NULL))) {
				$filter['fields'][]='haladek';
				$filter['values'][]=$this->getIntParam('haladek');
			}
			if (!is_null($this->getParam('webes',NULL))) {
				$filter['fields'][]='webes';
				$filter['values'][]=$this->getBoolParam('webes');
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