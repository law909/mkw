<?php
namespace Controllers;
use Entities\Munkakor;

use matt, matt\Exceptions, SIIKerES\store;

class dolgozoController extends matt\MattableController {

	public function __construct($generalDataLoader,$actionName=null,$commandString=null) {
		$this->setEntityName('Entities\Dolgozo');
		$this->setEm(store::getEm());
		$this->setTemplateFactory(store::getTemplateFactory());
		$this->setKarbFormTplName('dolgozokarbform.tpl');
		$this->setKarbTplName('dolgozokarb.tpl');
		$this->setListBodyRowTplName('dolgozolista_tbody_tr.tpl');
		$this->setListBodyRowVarName('_egyed');
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

	protected function loadVars($t) {
		$x=array();
		if ($t) {
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
		}
		else {
			$x['id']=0;
			$x['nev']='';
			$x['irszam']='';
			$x['varos']='';
			$x['utca']='';
			$x['telefon']='';
			$x['email']='';
			$x['szulido']=new \DateTime();
			$x['szulidostr']=date(store::$DateFormat);
			$x['szulhely']='';
			$x['evesmaxszabi']=0;
			$x['munkaviszonykezdete']=new \DateTime();
			$x['munkaviszonykezdete']=date(store::$DateFormat);
			$x['munkakornev']='';
			$x['muveletnev']='';
		}
		return $x;
	}

	protected function setFields($obj) {
		try {
			$obj->setNev($this->getStringParam('nev'));
			$obj->setIrszam($this->getStringParam('irszam'));
			$obj->setVaros($this->getStringParam('varos'));
			$obj->setUtca($this->getStringParam('utca'));
			$obj->setTelefon($this->getStringParam('telefon'));
			$obj->setEmail($this->getStringParam('email'));
			$obj->setSzulido($this->getStringParam('szulido'));
			$obj->setSzulhely($this->getStringParam('szulhely'));
			$obj->setEvesmaxszabi($this->getIntParam('evesmaxszabi'));
			$obj->setMunkaviszonykezdete($this->getStringParam('munkaviszonykezdete'));

			$ck=store::getEm()->getRepository('Entities\Termek')->find($this->getIntParam('muvelet',0));
			if ($ck) {
				$obj->setMuvelet($ck);
			}
			$ck=store::getEm()->getRepository('Entities\Munkakor')->find($this->getIntParam('munkakor',0));
			if ($ck) {
				$obj->setMunkakor($ck);
			}
		}
		catch (matt\Exceptions\WrongValueTypeException $e){
		}
		return $obj;
	}

	protected function getlistbody() {
		$view=$this->createView('dolgozolista_tbody.tpl');

		$filterarr=array();
		if (!is_null($this->getParam('nevfilter',NULL))) {
			$filterarr['fields'][]='nev';
			$filterarr['values'][]=$this->getStringParam('nevfilter');
		}

		$this->initPager(
			$this->getRepo()->getCount($filterarr),
			$this->getIntParam('elemperpage',30),
			$this->getIntParam('pageno',1));

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

	protected function viewselect() {
		$view=$this->createView('dolgozolista.tpl');

		$view->setVar('pagetitle',t('Dolgozók'));
		$view->setVar('controllerscript','dolgozolista.js');
		$view->printTemplateResult();
	}

	protected function viewlist() {
		$view=$this->createView('dolgozolista.tpl');

		$view->setVar('pagetitle',t('Dolgozók'));
		$view->setVar('controllerscript','dolgozolista.js');
		$view->setVar('orderselect',$this->getRepo()->getOrdersForTpl());
		$view->setVar('batchesselect',$this->getRepo()->getBatchesForTpl());
		$view->printTemplateResult();
	}

	protected function _getkarb($id,$oper,$tplname) {
		$view=$this->createView($tplname);

		$view->setVar('pagetitle',t('Dolgozó'));
		$view->setVar('controllerscript','dolgozokarb.js');
		$view->setVar('formaction','/admin/dolgozo/save');
		$view->setVar('oper',$oper);
		$record=$this->getRepo()->findWithJoins($id);
		$view->setVar('egyed',$this->loadVars($record));
		$munkakor=new munkakorController($this->generalDataLoader);
		$view->setVar('munkakorlist',$munkakor->getSelectList(($record?$record->getMunkakorId():0)));
		$muvelet=new termekController($this->generalDataLoader);
		$view->setVar('muveletlist',$muvelet->getSelectList(($record?$record->getMuveletId():0)));
		return $view->getTemplateResult();
	}
}