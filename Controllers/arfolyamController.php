<?php
namespace Controllers;

use mkw\store;

class arfolyamController extends \mkwhelpers\JQGridController {

	public function __construct() {
		$this->setEntityName('Entities\Arfolyam');
		parent::__construct();
	}

	protected function loadCells($sor) {
		$valuta=$sor->getValutanem();
		return array($sor->getDatumString(),(isset($valuta)?$valuta->getNev():''),$sor->getArfolyam());
	}

	protected function setFields($obj,$params) {
		$obj->setDatum(new \DateTime(str_replace('.','-',$params->getStringRequestParam('datum'))));
		$obj->setArfolyam($params->getNumRequestParam('arfolyam'));
		$valutanem=store::getEm()->getReference('Entities\Valutanem',$params->getIntRequestParam('valutanem',0));
		try {
			$valutanem->getId();
			$obj->setValutanem($valutanem);
		}
		catch (\Doctrine\ORM\EntityNotFoundException $e) {
		}
		return $obj;
	}

	public function jsonlist($params) {
		$filter=array();
		if ($params->getBoolRequestParam('_search',false)) {
			if ($params->getStringRequestParam('datum','')!='') {
				$filter['fields'][]='datum';
				$filter['values'][]=$params->getStringRequestParam('datum','');
			}
			if ($params->getStringRequestParam('valutanem','')!='') {
				$filter['fields'][]='v.nev';
				$filter['values'][]=$params->getStringRequestParam('valutanem','');
			}
		}
		$rec=$this->getRepo()->getAll($filter,$this->getOrderArray($params));
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

	public function getarfolyam($params) {
		$arf=$this->getRepo()->getActualArfolyam($params->getIntRequestParam('valutanem'),$params->getStringRequestParam('datum'));
		if ($arf) {
			echo $arf->getArfolyam();
		}
		else {
			echo '1';
		}
	}
}