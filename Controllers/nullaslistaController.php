<?php
namespace Controllers;

use mkw\store;

class nullaslistaController extends bizonylatfejController {

	public function __construct($params) {
		$this->setEntityName('Entities\Megrendelesfej');
		$this->setKarbFormTplName('nullaslistafejkarbform.tpl');
		$this->setKarbTplName('nullaslistafejkarb.tpl');
		$this->setListBodyRowTplName('nullaslistafejlista_tbody_tr.tpl');
		$this->setListBodyRowVarName('_egyed');
		parent::__construct($params);
	}

	protected function setVars($view) {
		$view->setVar('showteljesites',false);
		$view->setVar('showesedekesseg',false);
		$view->setVar('showhatarido',true);
		$view->setVar('showvalutanem',true);
	}

	protected function getlistbody() {
		$view=$this->createView('nullaslistafejlista_tbody.tpl');
		$this->setVars($view);
		$filter=array();
		if (!is_null($this->params->getRequestParam('idfilter',NULL))) {
			$filter['fields'][]='id';
			$filter['values'][]=$this->params->getStringRequestParam('idfilter');
		}

		$this->initPager($this->getRepo()->getCount($filter));

		$egyedek=$this->getRepo()->getWithJoins(
			$filter,
			$this->getOrderArray(),
			$this->getPager()->getOffset(),
			$this->getPager()->getElemPerPage());

		echo json_encode($this->loadDataToView($egyedek,'egyedlista',$view));
	}

	protected function viewselect() {
		$view=$this->createView('nullaslistalista.tpl');
		$this->setVars($view);

		$view->setVar('pagetitle',t('Nullás lista'));
		$view->setVar('controllerscript','nullaslistafejlista.js');
		$view->printTemplateResult();
	}

	protected function viewlist() {
		$view=$this->createView('nullaslistafejlista.tpl');
		$this->setVars($view);

		$view->setVar('pagetitle',t('Nullás lista'));
		$view->setVar('controllerscript','nullaslistafejlista.js');
		$view->setVar('orderselect',$this->getRepo()->getOrdersForTpl());
		$view->setVar('batchesselect',$this->getRepo()->getBatchesForTpl());
		$view->printTemplateResult();
	}

	protected function _getkarb($tplname) {
		$id=$this->params->getRequestParam('id',0);
		$oper=$this->params->getRequestParam('oper','');
		$view=$this->createView($tplname);
		$this->setVars($view);

		$view->setVar('pagetitle',t('Nullás lista'));
		$view->setVar('controllerscript','nullaslistafejkarb.js');
		$view->setVar('formaction','/admin/nullaslista/save');
		$view->setVar('oper',$oper);
		$record=$this->getRepo()->findWithJoins($id);
		$view->setVar('egyed',$this->loadVars($record,true));
		$partner=new partnerController($this->params);
		$view->setVar('partnerlist',$partner->getSelectList(($record?$record->getPartnerId():0)));
		$raktar=new raktarController($this->params);
		if (!$record||!$record->getRaktarId()) {
			$raktarid=store::getParameter('raktar',0);
		}
		else {
			$raktarid=$record->getRaktarId();
		}
		$view->setVar('raktarlist',$raktar->getSelectList($raktarid));
		$fizmod=new fizmodController($this->params);
		$view->setVar('fizmodlist',$fizmod->getSelectList(($record?$record->getFizmodId():0)));
		$valutanem=new valutanemController($this->params);
		if (!$record||!$record->getValutanemId()) {
			$valutaid=store::getParameter('valutanem',0);
		}
		else {
			$valutaid=$record->getValutanemId();
		}
		$view->setVar('valutanemlist',$valutanem->getSelectList($valutaid));
		$bankszla=new bankszamlaController($this->params);
		$view->setVar('bankszamlalist',$bankszla->getSelectList(($record?$record->getBankszamlaId():0)));
		$view->setVar('esedekessegalap',store::getParameter('esedekessegalap',1));
		return $view->getTemplateResult();
	}
}