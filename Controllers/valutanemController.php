<?php
namespace Controllers;
use matt, matt\Exceptions, Entities, SIIKerES\store;

class valutanemController extends matt\JQGridController {

	public function __construct($generalDataLoader,$actionName=null,$commandString=null) {
		$this->setEntityName('Entities\Valutanem');
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
		$bank=$sor->getBankszamla();
		return array($sor->getNev(),$sor->getKerekit(),
				$sor->getHivatalos(),$sor->getMincimlet(),
				(isset($bank)?$bank->getSzamlaszam():''));
	}

	protected function setFields($obj) {
		try {
			$obj->setNev($this->getStringParam('nev'));
			$obj->setKerekit($this->getBoolParam('kerekit'));
			$obj->setHivatalos($this->getBoolParam('hivatalos'));
			$obj->setMincimlet($this->getIntParam('mincimlet'));
			$bankszla=store::getEm()->getReference('Entities\Bankszamla',$this->getIntParam('bankszamla'));
			try {
				$bankszla->getId();
				$obj->setBankszamla($bankszla);
			}
			catch (\Doctrine\ORM\EntityNotFoundException $e) {
			}
			return $obj;
		}
		catch (matt\Exceptions\WrongValueTypeException $e){
		}
	}

	protected function jsonlist() {
		$filter=array();
		if ($this->getBoolParam('_search',false)) {
			if (!is_null($this->getParam('nev',NULL))) {
				$filter['fields'][]='nev';
				$filter['values'][]=$this->getStringParam('nev');
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
				'bankszamla'=>$sor->getBankszamlaId()
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
	
	public function getRendszerValuta() {
		$p=$this->getRepo()->find(store::getParameter('valutanem'));
		return $p;
	}
}