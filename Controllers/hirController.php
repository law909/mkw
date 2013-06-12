<?php
namespace Controllers;
use matt, matt\Exceptions, SIIKerES\store;

class hirController extends matt\MattableController {

	public function __construct($generalDataLoader,$actionName=null,$commandString=null) {
		$this->setEntityName('Entities\Hir');
		$this->setEm(store::getEm());
		$this->setTemplateFactory(store::getTemplateFactory());
		$this->setKarbFormTplName('hirkarbform.tpl');
		$this->setKarbTplName('hirkarb.tpl');
		$this->setListBodyRowTplName('hirlista_tbody_tr.tpl');
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
			$x['cim']=$t->getCim();
			$x['slug']=$t->getSlug();
			$x['sorrend']=$t->getSorrend();
			$x['forras']=$t->getForras();
			$x['lead']=$t->getLead();
			$x['szoveg']=$t->getSzoveg();
			$x['datum']=$t->getDatum();
			$x['datumstr']=$t->getDatumStr();
			$x['elsodatum']=$t->getElsodatum();
			$x['elsodatumstr']=$t->getElsodatumStr();
			$x['utolsodatum']=$t->getUtolsodatum();
			$x['utolsodatumstr']=$t->getUtolsodatumStr();
			$x['lathato']=$t->getLathato();
			$x['seodescription']=$t->getSeodescription();
			$x['seokeywords']=$t->getSeokeywords();
		}
		else {
			$x['id']=0;
			$x['cim']='';
			$x['slug']='';
			$x['sorrend']=0;
			$x['forras']='';
			$x['lead']='';
			$x['szoveg']='';
			$x['datum']='';
			$x['datumstr']=date(store::$DateFormat);
			$x['elsodatum']='';
			$x['elsodatumstr']=date(store::$DateFormat);
			$x['utolsodatum']='';
			$x['utolsodatumstr']=date(store::$DateFormat);
			$x['lathato']=true;
			$x['seodescription']='';
			$x['seokeywords']='';
		}
		return $x;
	}

	protected function setFields($obj) {
		try {
			$obj->setCim($this->getStringParam('cim'));
			$obj->setSorrend($this->getIntParam('sorrend'));
			$obj->setForras($this->getStringParam('forras'));
			$obj->setLead($this->getStringParam('lead'));
			$obj->setSzoveg($this->getStringParam('szoveg'));
			$obj->setLathato($this->getBoolParam('lathato'));
			$obj->setDatum($this->getDateParam('datum'));
			$obj->setElsodatum($this->getDateParam('elsodatum'));
			$obj->setUtolsodatum($this->getDateParam('utolsodatum'));
			$obj->setSeodescription($this->getStringParam('seodescription'));
			$obj->setSeokeywords($this->getStringParam('seokeywords'));
		}
		catch (matt\Exceptions\WrongValueTypeException $e){
		}
		return $obj;
	}

	protected function getlistbody() {
		$view=$this->createView('hirlista_tbody.tpl');

		$filter=array();
		if (!is_null($this->getParam('nevfilter',NULL))) {
			$filter['fields'][]='cim';
			$filter['values'][]=$this->getStringParam('nevfilter');
		}

		$this->initPager(
			$this->getRepo()->getCount($filter),
			$this->getIntParam('elemperpage',30),
			$this->getIntParam('pageno',1));

		$egyedek=$this->getRepo()->getAll(
			$filter,
			$this->getOrderArray(),
			$this->getPager()->getOffset(),
			$this->getPager()->getElemPerPage());

		echo json_encode($this->loadDataToView($egyedek,'egyedlista',$view));
	}

	protected function viewselect() {
		$view=$this->createView('hirlista.tpl');

		$view->setVar('pagetitle',t('Hírek'));
		$view->setVar('controllerscript','hirlista.js');
		$view->printTemplateResult();
	}

	protected function viewlist() {
		$view=$this->createView('hirlista.tpl');

		$view->setVar('pagetitle',t('Hír'));
		$view->setVar('controllerscript','hirlista.js');
		$view->setVar('orderselect',$this->getRepo()->getOrdersForTpl());
		$view->setVar('batchesselect',$this->getRepo()->getBatchesForTpl());
		$view->printTemplateResult();
	}

	protected function _getkarb($id,$oper,$tplname) {
		$view=$this->createView($tplname);

		$view->setVar('pagetitle',t('Hír'));
		$view->setVar('controllerscript','hirkarb.js');
		$view->setVar('formaction','/admin/hir/save');
		$view->setVar('oper',$oper);
		$record=$this->getRepo()->find($id);
		$view->setVar('egyed',$this->loadVars($record));
		return $view->getTemplateResult();
	}

	protected function setlathato() {
		$id=$this->getIntParam('id');
		$kibe=$this->getBoolParam('kibe');
		$obj=$this->getRepo()->find($id);
		if ($obj) {
			$obj->setLathato($kibe);
			store::getEm()->persist($obj);
			store::getEm()->flush();
		}
	}

	public function gethirlist() {
		$t=array();
		$hirek=$this->getRepo()->getMaiHirek();
		foreach($hirek as $hir) {
			$t[]=$hir->convertToArray();
		}
		return $t;
	}

	public function getfeedhirlist() {
		return $this->getRepo()->getFeedHirek();
	}
}