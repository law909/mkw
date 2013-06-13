<?php
namespace Controllers;

use mkw\store;

class arfolyamController extends \mkwhelpers\JQGridController {

	public function __construct($params) {
		$this->setEntityName('Entities\Arfolyam');
		parent::__construct($params);
	}

	protected function loadCells($sor) {
		$valuta=$sor->getValutanem();
		return array($sor->getDatumString(),(isset($valuta)?$valuta->getNev():''),$sor->getArfolyam());
	}

	protected function setFields($obj) {
		$obj->setDatum(new \DateTime(str_replace('.','-',$this->params->getStringRequestParam('datum'))));
		$obj->setArfolyam($this->params->getNumRequestParam('arfolyam'));
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
			if ($this->params->getStringRequestParam('datum','')!='') {
				$filter['fields'][]='datum';
				$filter['values'][]=$this->params->getStringRequestParam('datum','');
			}
			if ($this->params->getStringRequestParam('valutanem','')!='') {
				$filter['fields'][]='v.nev';
				$filter['values'][]=$this->params->getStringRequestParam('valutanem','');
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

	public function htmllist() {
		$rec=$this->getRepo()->getAll(array(),array('datum'=>'asc'));
		$ret='<select>';
		foreach($rec as $sor) {
			$ret.='<option value="'.$sor->getId().'">'.$sor->getSzamlaszam().'</option>';
		}
		$ret.='</select>';
		echo $ret;
	}

	public function getarfolyam() {
		$arf=$this->getRepo()->getActualArfolyam($this->params->getIntRequestParam('valutanem'),$this->params->getStringRequestParam('datum'));
		if ($arf) {
			echo $arf->getArfolyam();
		}
		else {
			echo '1';
		}
	}
}