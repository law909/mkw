<?php
namespace Controllers;
use mkw\store;

class hirController extends \mkwhelpers\MattableController {

	public function __construct($params) {
		$this->setEntityName('Entities\Hir');
		$this->setKarbFormTplName('hirkarbform.tpl');
		$this->setKarbTplName('hirkarb.tpl');
		$this->setListBodyRowTplName('hirlista_tbody_tr.tpl');
		$this->setListBodyRowVarName('_egyed');
		parent::__construct($params);
	}

	protected function loadVars($t) {
		$x=array();
		if (!$t) {
			$t=new \Entities\Hir();
			$this->getEm()->detach($t);
		}
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
		return $x;
	}

	protected function setFields($obj) {
		$obj->setCim($this->params->getStringRequestParam('cim'));
		$obj->setSorrend($this->params->getIntRequestParam('sorrend'));
		$obj->setForras($this->params->getStringRequestParam('forras'));
		$obj->setLead($this->params->getOriginalStringRequestParam('lead'));
		$obj->setSzoveg($this->params->getOriginalStringRequestParam('szoveg'));
		$obj->setLathato($this->params->getBoolRequestParam('lathato'));
		$obj->setDatum($this->params->getDateRequestParam('datum'));
		$obj->setElsodatum($this->params->getDateRequestParam('elsodatum'));
		$obj->setUtolsodatum($this->params->getDateRequestParam('utolsodatum'));
		$obj->setSeodescription($this->params->getStringRequestParam('seodescription'));
		$obj->setSeokeywords($this->params->getStringRequestParam('seokeywords'));
		return $obj;
	}

	public function getlistbody() {
		$view=$this->createView('hirlista_tbody.tpl');

		$filter=array();
		if (!is_null($this->params->getRequestParam('nevfilter',NULL))) {
			$filter['fields'][]='cim';
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
		$view=$this->createView('hirlista.tpl');

		$view->setVar('pagetitle',t('Hírek'));
		$view->printTemplateResult();
	}

	public function viewlist() {
		$view=$this->createView('hirlista.tpl');

		$view->setVar('pagetitle',t('Hír'));
		$view->setVar('orderselect',$this->getRepo()->getOrdersForTpl());
		$view->setVar('batchesselect',$this->getRepo()->getBatchesForTpl());
		$view->printTemplateResult();
	}

	protected function _getkarb($tplname) {
		$id=$this->params->getRequestParam('id',0);
		$oper=$this->params->getRequestParam('oper','');
		$view=$this->createView($tplname);

		$view->setVar('pagetitle',t('Hír'));
		$view->setVar('formaction','/admin/hir/save');
		$view->setVar('oper',$oper);
		$record=$this->getRepo()->find($id);
		$view->setVar('egyed',$this->loadVars($record));
		return $view->getTemplateResult();
	}

	public function setlathato() {
		$id=$this->params->getIntRequestParam('id');
		$kibe=$this->params->getBoolRequestParam('kibe');
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