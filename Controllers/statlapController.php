<?php
namespace Controllers;
use mkw\store;

class statlapController extends \mkwhelpers\MattableController {

	public function __construct($params) {
		$this->setEntityName('Entities\Statlap');
		$this->setKarbFormTplName('statlapkarbform.tpl');
		$this->setKarbTplName('statlapkarb.tpl');
		$this->setListBodyRowTplName('statlaplista_tbody_tr.tpl');
		$this->setListBodyRowVarName('_egyed');
		parent::__construct($params);
	}

	protected function loadVars($t) {
		$x=array();
		if (!$t) {
			$t=new \Entities\Statlap();
			$this->getEm()->detach($t);
		}
		$x['id']=$t->getId();
		$x['oldalcim']=$t->getOldalcim();
		$x['slug']=$t->getSlug();
		$x['szoveg']=$t->getSzoveg();
		$x['seodescription']=$t->getSeodescription();
		$x['seokeywords']=$t->getSeokeywords();
		return $x;
	}

	protected function setFields($obj) {
		$obj->setOldalcim($this->params->getStringRequestParam('oldalcim'));
		$obj->setSzoveg($this->params->getStringRequestParam('szoveg'));
		$obj->setSeodescription($this->params->getStringRequestParam('seodescription'));
		$obj->setSeokeywords($this->params->getStringRequestParam('seokeywords'));
		return $obj;
	}

	public function getlistbody() {
		$view=$this->createView('statlaplista_tbody.tpl');

		$filter=array();
		if (!is_null($this->params->getRequestParam('nevfilter',NULL))) {
			$filter['fields'][]='oldalcim';
			$filter['values'][]=$this->params->getStringRequestParam('nevfilter');
		}

		$this->initPager($this->getRepo()->getCount($filter));

		$egyedek=$this->getRepo()->getAll(
			$filter,
			$this->getOrderArray(),
			$this->getPager()->getOffset(),
			$this->getPager()->getElemPerPage());

		echo json_encode($this->loadDataToView($egyedek,'egyedlista',$view));
	}

	public function viewselect() {
		$view=$this->createView('statlaplista.tpl');

		$view->setVar('pagetitle',t('Statikus lapok'));
		$view->setVar('controllerscript','statlaplista.js');
		$view->printTemplateResult();
	}

	public function viewlist() {
		$view=$this->createView('statlaplista.tpl');

		$view->setVar('pagetitle',t('Statikus lapok'));
		$view->setVar('controllerscript','statlaplista.js');
		$view->setVar('orderselect',$this->getRepo()->getOrdersForTpl());
		$view->setVar('batchesselect',$this->getRepo()->getBatchesForTpl());
		$view->printTemplateResult();
	}

	protected function _getkarb($tplname) {
		$id=$this->params->getRequestParam('id',0);
		$oper=$this->params->getRequestParam('oper','');
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