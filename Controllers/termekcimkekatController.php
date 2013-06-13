<?php
namespace Controllers;
use mkw\store;

class termekcimkekatController extends \mkwhelpers\JQGridController {

	private $termekcimkek;

	public function __construct($params) {
		$this->setEntityName('Entities\Termekcimkekat');
		parent::__construct($params);
	}

	protected function loadCells($obj) {
		return array($obj->getNev(),$obj->getSorrend(),$obj->getTermeklaponlathato(),$obj->getTermekszurobenlathato(),
			$obj->getTermeklistabanlathato(),$obj->getTermekakciodobozbanlathato());
	}

	protected function setFields($obj) {
		$obj->setNev($this->params->getStringRequestParam('nev',$obj->getNev()));
		$obj->setSorrend($this->params->getIntRequestParam('sorrend'));
		$obj->setTermeklaponlathato($this->params->getBoolRequestParam('termeklaponlathato'));
		$obj->setTermekszurobenlathato($this->params->getBoolRequestParam('termekszurobenlathato'));
		$obj->setTermeklistabanlathato($this->params->getBoolRequestParam('termeklistabanlathato'));
		$obj->setTermekakciodobozbanlathato($this->params->getBoolRequestParam('termekakciodobozbanlathato'));
		return $obj;
	}

	public function jsonlist() {
		$filter=array();
		if ($this->params->getBoolRequestParam('_search',false)) {
			if (!is_null($this->params->getRequestParam('nev',NULL))) {
				$filter['fields'][]='nev';
				$filter['values'][]=$this->params->getStringRequestParam('nev');
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
		// TODO sok cimke eseten ez meg lehet lassu, bar gyorsitottam
		$cimkekat=$this->getRepo()->getScalarWithJoins(array(),array('_xx.sorrend'=>'asc','_xx.nev'=>'asc','c.nev'=>'asc'));
		$res=array();
		foreach($cimkekat as $sor) {
			if (!array_key_exists($sor['id'],$res)) {
				$res[$sor['id']]=array(
					'id'=>$sor['id'],
					'caption'=>$sor['nev'],
					'sanitizedcaption'=>str_replace('.','',$sor['slug']),
					'cimkek'=>array(
						array(
							'id'=>$sor['cid'],
							'caption'=>$sor['cnev'],
							'selected'=>$selected&&(in_array($sor['cid'],$selected))
						)
					));
			}
			else {
				$res[$sor['id']]['cimkek'][]=array('id'=>$sor['cid'],
					'caption'=>$sor['cnev'],
					'selected'=>$selected&&(in_array($sor['cid'],$selected))
				);
			}
		}
		return $res;
	}

	private function termekidcount($mibol,$miben) {
		$cnt=0;
		if (count($miben)==0) {
			$cnt=count($mibol);
		}
		else {
			foreach($mibol as $egy) {
				if (in_array($egy->getId(),$miben)) {
					$cnt++;
				}
			}
		}
		return $cnt;
	}

	public function getForTermekSzuro($termekids,$selectedids,$szurttermekids) {
		$this->termekcimkek=$this->getEm()->getRepository('Entities\Termekcimketorzs')->getAllNative();
		$sid=array();
		foreach($selectedids as $sids) {
			foreach($sids as $ertek) {
				$sid[]=$ertek;
			}
		}
		$rec=$this->getRepo()->getForTermekSzuro($termekids,$sid);
		$res=array();
		foreach($rec as $sor) {
			$crec=$sor->getCimkek();
			$cimkek=array();
			foreach($crec as $csor) {
				$cimkek[]=array(
					'id'=>$csor->getId(),
					'caption'=>$csor->getNev(),
					'selected'=>in_array($csor->getId(),$sid),
					'termekdb'=>$this->termekidcount($csor->getTermekek(),$szurttermekids)
				);
			}
			$res[]=array(
				'id'=>$sor->getId(),
				'caption'=>$sor->getNev(),
				'cimkek'=>$cimkek
			);
		}
		unset($sid);
		return $res;
	}
}