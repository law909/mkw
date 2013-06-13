<?php
namespace Controllers;

use mkw\store;

class dolgozoController extends \mkwhelpers\MattableController {

	public function __construct($params) {
		$this->setEntityName('Entities\Dolgozo');
		$this->setKarbFormTplName('dolgozokarbform.tpl');
		$this->setKarbTplName('dolgozokarb.tpl');
		$this->setListBodyRowTplName('dolgozolista_tbody_tr.tpl');
		$this->setListBodyRowVarName('_egyed');
		parent::__construct($params);
	}

	protected function loadVars($t) {
		$x=array();
		if (!$t) {
			$t=new \Entities\Dolgozo();
			$this->getEm()->detach($t);
		}
		$x['id']=$t->getId();
		$x['nev']=$t->getNev();
		$x['irszam']=$t->getIrszam();
		$x['varos']=$t->getVaros();
		$x['utca']=$t->getUtca();
		$x['telefon']=$t->getTelefon();
		$x['email']=$t->getEmail();
		$x['szulido']=$t->getSzulido();
		$x['szulidostr']=$t->getSzulidoStr();
		$x['szulhely']=$t->getSzulhely();
		$x['evesmaxszabi']=$t->getEvesmaxszabi();
		$x['munkaviszonykezdete']=$t->getMunkaviszonykezdete();
		$x['munkaviszonykezdetestr']=$t->getMunkaviszonykezdeteStr();
		$x['munkakornev']=$t->getMunkakorNev();
		$x['muveletnev']=$t->getMuveletNev();
		return $x;
	}

	protected function setFields($obj) {
		$obj->setNev($this->params->getStringRequestParam('nev'));
		$obj->setIrszam($this->params->getStringRequestParam('irszam'));
		$obj->setVaros($this->params->getStringRequestParam('varos'));
		$obj->setUtca($this->params->getStringRequestParam('utca'));
		$obj->setTelefon($this->params->getStringRequestParam('telefon'));
		$obj->setEmail($this->params->getStringRequestParam('email'));
		$obj->setSzulido($this->params->getStringRequestParam('szulido'));
		$obj->setSzulhely($this->params->getStringRequestParam('szulhely'));
		$obj->setEvesmaxszabi($this->params->getIntRequestParam('evesmaxszabi'));
		$obj->setMunkaviszonykezdete($this->params->getStringRequestParam('munkaviszonykezdete'));

		$ck=store::getEm()->getRepository('Entities\Termek')->find($this->params->getIntRequestParam('muvelet',0));
		if ($ck) {
			$obj->setMuvelet($ck);
		}
		$ck=store::getEm()->getRepository('Entities\Munkakor')->find($this->params->getIntRequestParam('munkakor',0));
		if ($ck) {
			$obj->setMunkakor($ck);
		}
		return $obj;
	}

	public function getlistbody() {
		$view=$this->createView('dolgozolista_tbody.tpl');

		$filterarr=array();
		if (!is_null($this->params->getRequestParam('nevfilter',NULL))) {
			$filterarr['fields'][]='nev';
			$filterarr['values'][]=$this->params->getStringRequestParam('nevfilter');
		}

		$this->initPager($this->getRepo()->getCount($filterarr));

		$egyedek=$this->getRepo()->getWithJoins(
			$filterarr,
			$this->getOrderArray(),
			$this->getPager()->getOffset(),
			$this->getPager()->getElemPerPage());

		echo json_encode($this->loadDataToView($egyedek,'egyedlista',$view));
	}

	public function getSelectList($selid) {
		$rec=$this->getRepo()->getAllForSelectList(array(),array('nev'=>'ASC'));
		$res=array();
		foreach($rec as $sor) {
			$res[]=array('id'=>$sor['id'],'caption'=>$sor['nev'],'selected'=>($sor['id']==$selid));
		}
		return $res;
	}

	public function viewselect() {
		$view=$this->createView('dolgozolista.tpl');

		$view->setVar('pagetitle',t('Dolgozók'));
		$view->setVar('controllerscript','dolgozolista.js');
		$view->printTemplateResult();
	}

	public function viewlist() {
		$view=$this->createView('dolgozolista.tpl');

		$view->setVar('pagetitle',t('Dolgozók'));
		$view->setVar('controllerscript','dolgozolista.js');
		$view->setVar('orderselect',$this->getRepo()->getOrdersForTpl());
		$view->setVar('batchesselect',$this->getRepo()->getBatchesForTpl());
		$view->printTemplateResult();
	}

	protected function _getkarb($tplname) {
		$id=$this->params->getRequestParam('id',0);
		$oper=$this->params->getRequestParam('oper','');
		$view=$this->createView($tplname);

		$view->setVar('pagetitle',t('Dolgozó'));
		$view->setVar('controllerscript','dolgozokarb.js');
		$view->setVar('formaction','/admin/dolgozo/save');
		$view->setVar('oper',$oper);
		$record=$this->getRepo()->findWithJoins($id);
		$view->setVar('egyed',$this->loadVars($record));
		$munkakor=new munkakorController($this->params);
		$view->setVar('munkakorlist',$munkakor->getSelectList(($record?$record->getMunkakorId():0)));
		$muvelet=new termekController($this->params);
		$view->setVar('muveletlist',$muvelet->getSelectList(($record?$record->getMuveletId():0)));
		return $view->getTemplateResult();
	}
}