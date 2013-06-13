<?php
namespace Controllers;
use mkw\store;

class statlapController extends \mkwhelpers\MattableController {

	public function __construct($generalDataLoader,$actionName=null,$commandString=null) {
		$this->setEntityName('Entities\Statlap');
		$this->setEm(store::getEm());
		$this->setTemplateFactory(store::getTemplateFactory());
		$this->setKarbFormTplName('statlapkarbform.tpl');
		$this->setKarbTplName('statlapkarb.tpl');
		$this->setListBodyRowTplName('statlaplista_tbody_tr.tpl');
		$this->setListBodyRowVarName('_egyed');
		parent::__construct($generalDataLoader,$actionName,$commandString);
	}

	protected function loadVars($t) {
		$x=array();
		if ($t) {
			$x['id']=$t->getId();
			$x['oldalcim']=$t->getOldalcim();
			$x['slug']=$t->getSlug();
			$x['szoveg']=$t->getSzoveg();
			$x['seodescription']=$t->getSeodescription();
			$x['seokeywords']=$t->getSeokeywords();
		}
		else {
			$x['id']=0;
			$x['oldalcim']='';
			$x['slug']='';
			$x['szoveg']='';
			$x['seodescription']='';
			$x['seokeywords']='';
		}
		return $x;
	}

	protected function setFields($obj) {
		$obj->setOldalcim($this->getStringParam('oldalcim'));
		$obj->setSzoveg($this->getStringParam('szoveg'));
		$obj->setSeodescription($this->getStringParam('seodescription'));
		$obj->setSeokeywords($this->getStringParam('seokeywords'));
		return $obj;
	}

	protected function getlistbody() {
		$view=$this->createView('statlaplista_tbody.tpl');

		$filter=array();
		if (!is_null($this->getParam('nevfilter',NULL))) {
			$filter['fields'][]='oldalcim';
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
		$view=$this->createView('statlaplista.tpl');

		$view->setVar('pagetitle',t('Statikus lapok'));
		$view->setVar('controllerscript','statlaplista.js');
		$view->printTemplateResult();
	}

	protected function viewlist() {
		$view=$this->createView('statlaplista.tpl');

		$view->setVar('pagetitle',t('Statikus lapok'));
		$view->setVar('controllerscript','statlaplista.js');
		$view->setVar('orderselect',$this->getRepo()->getOrdersForTpl());
		$view->setVar('batchesselect',$this->getRepo()->getBatchesForTpl());
		$view->printTemplateResult();
	}

	protected function _getkarb($id,$oper,$tplname) {
		$view=$this->createView($tplname);

		$view->setVar('pagetitle',t('Statikus lap'));
		$view->setVar('controllerscript','statlapkarb.js');
		$view->setVar('formaction','/admin/statlap/save');
		$view->setVar('oper',$oper);
		$record=$this->getRepo()->find($id);
		$view->setVar('egyed',$this->loadVars($record));
		return $view->getTemplateResult();
	}

	public function getstatlap($statlap) {
		$t=array();
		$t['szoveg']=$statlap->getSzoveg();
		return $t;
	}
}