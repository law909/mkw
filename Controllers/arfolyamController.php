<?php
namespace Controllers;
use Doctrine\ORM\Query\Expr\Func;

use matt, matt\Exceptions, Entities, SIIKerES\store;

class arfolyamController extends matt\JQGridController {

	public function __construct($generalDataLoader,$actionName=null,$commandString=null) {
		$this->setEntityName('Entities\Arfolyam');
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
		$valuta=$sor->getValutanem();
		return array($sor->getDatumString(),(isset($valuta)?$valuta->getNev():''),$sor->getArfolyam());
	}

	protected function setFields($obj) {
		try {
			$obj->setDatum(new \DateTime(str_replace('.','-',$this->getStringParam('datum'))));
			$obj->setArfolyam($this->getNumParam('arfolyam'));
			$valutanem=store::getEm()->getReference('Entities\Valutanem',$this->getIntParam('valutanem',0));
			try {
				$valutanem->getId();
				$obj->setValutanem($valutanem);
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
			if ($this->getStringParam('datum','')!='') {
				$filter['fields'][]='datum';
				$filter['values'][]=$this->getStringParam('datum','');
			}
			if ($this->getStringParam('valutanem','')!='') {
				$filter['fields'][]='v.nev';
				$filter['values'][]=$this->getStringParam('valutanem','');
			}
		}
		$rec=$this->getRepo()->getAll($filter,$this->getOrderArray());
		echo json_encode($this->loadDataToView($rec));
	}

	public function getSelectList($selid) {
		$rec=$this->getRepo()->getAll(array(),array('datum'=>'ASC'));
		$res=array();
		foreach($rec as $sor) {
			$res[]=array('id'=>$sor->getId(),'caption'=>$sor->getNev(),'selected'=>($sor->getId()==$selid));
		}
		return $res;
	}

	protected function htmllist() {
		$rec=$this->getRepo()->getAll(array(),array('datum'=>'asc'));
		$ret='<select>';
		foreach($rec as $sor) {
			$ret.='<option value="'.$sor->getId().'">'.$sor->getSzamlaszam().'</option>';
		}
		$ret.='</select>';
		echo $ret;
	}

	protected function getarfolyam() {
		$arf=$this->getRepo()->getActualArfolyam($this->getIntParam('valutanem'),$this->getStringParam('datum'));
		if ($arf) {
			echo $arf->getArfolyam();
		}
		else {
			echo '1';
		}
	}
}