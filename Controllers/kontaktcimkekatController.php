<?php
namespace Controllers;
use matt, matt\Exceptions, SIIKerES\store;

class kontaktcimkekatController extends matt\JQGridController {

	public function __construct($generalDataLoader,$actionName=null,$commandString=null) {
		$this->setEntityName('Entities\Kontaktcimkekat');
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
		return array($sor->getNev(),$sor->getLathato());
	}

	protected function setFields($obj) {
		$obj->setNev($this->getStringParam('nev',$obj->getNev()));
		$obj->setLathato($this->getBoolParam('lathato'));
		return $obj;
	}

	protected function jsonlist() {
		$filter=array();
		if ($this->getBoolParam('_search',false)) {
			if (!is_null($this->getParam('lathato',NULL))) {
				$filter['fields'][]='lathato';
				$filter['values'][]=$this->getBoolParam('lathato');
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
		$rec=$this->getRepo()->getAll(array(),array('nev'=>'ASC'));
		$res=array();
		foreach($rec as $sor) {
			$res[]=array('id'=>$sor->getId(),'caption'=>$sor->getNev(),'selected'=>($sor->getId()==$selid));
		}
		return $res;
	}

	public function getWithCimkek($selected) {
		$cimkekat=$this->getRepo()->getWithJoins(array(),array('_xx.nev'=>'asc','c.nev'=>'asc'));
		$res=array();
		foreach($cimkekat as $kat) {
			$adat=array();
			$cimkek=$kat->getCimkek();
			foreach($cimkek as $cimke) {
				$adat[]=array(
					'id'=>$cimke->getId(),
					'caption'=>$cimke->getNev(),
					'selected'=>($selected&&($selected->contains($cimke))?true:false)
				);
			}
			$res[]=array(
				'id'=>$kat->getId(),
				'caption'=>$kat->getNev(),
				'sanitizedcaption'=>str_replace('.','',$kat->getSlug()), //matt\Filter::toPermalink($kat->getNev())),
				'cimkek'=>$adat
			);
		}
		return $res;
	}
}