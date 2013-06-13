<?php
namespace Controllers;

use matt\Exceptions, mkw\store;

class nullaslistaController extends bizonylatfejController {

	public function __construct($generalDataLoader,$actionName=null,$commandString=null) {
		parent::__construct($generalDataLoader,$actionName,$commandString);
		$this->setEntityName('Entities\Megrendelesfej');
		$this->setEm(store::getEm());
		$this->setTemplateFactory(store::getTemplateFactory());
		$this->setKarbFormTplName('nullaslistafejkarbform.tpl');
		$this->setKarbTplName('nullaslistafejkarb.tpl');
		$this->setListBodyRowTplName('nullaslistafejlista_tbody_tr.tpl');
		$this->setListBodyRowVarName('_egyed');
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
			throw new \matt\Exceptions\UnknownMethodException('"'.__CLASS__.'->'.$methodname.'" does not exist.');
		}
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
		if (!is_null($this->getParam('idfilter',NULL))) {
			$filter['fields'][]='id';
			$filter['values'][]=$this->getStringParam('idfilter');
		}

		$this->initPager(
			$this->getRepo()->getCount($filter),
			$this->getIntParam('elemperpage',30),
			$this->getIntParam('pageno',1));

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

	protected function _getkarb($id,$oper,$tplname) {
		$view=$this->createView($tplname);
		$this->setVars($view);

		$view->setVar('pagetitle',t('Nullás lista'));
		$view->setVar('controllerscript','nullaslistafejkarb.js');
		$view->setVar('formaction','/admin/nullaslista/save');
		$view->setVar('oper',$oper);
		$record=$this->getRepo()->findWithJoins($id);
		$view->setVar('egyed',$this->loadVars($record,true));
		$partner=new partnerController($this->generalDataLoader);
		$view->setVar('partnerlist',$partner->getSelectList(($record?$record->getPartnerId():0)));
		$raktar=new raktarController($this->generalDataLoader);
		if (!$record||!$record->getRaktarId()) {
			$raktarid=store::getParameter('raktar',0);
		}
		else {
			$raktarid=$record->getRaktarId();
		}
		$view->setVar('raktarlist',$raktar->getSelectList($raktarid));
		$fizmod=new fizmodController($this->generalDataLoader);
		$view->setVar('fizmodlist',$fizmod->getSelectList(($record?$record->getFizmodId():0)));
		$valutanem=new valutanemController($this->generalDataLoader);
		if (!$record||!$record->getValutanemId()) {
			$valutaid=store::getParameter('valutanem',0);
		}
		else {
			$valutaid=$record->getValutanemId();
		}
		$view->setVar('valutanemlist',$valutanem->getSelectList($valutaid));
		$bankszla=new bankszamlaController($this->generalDataLoader);
		$view->setVar('bankszamlalist',$bankszla->getSelectList(($record?$record->getBankszamlaId():0)));
		$view->setVar('esedekessegalap',store::getParameter('esedekessegalap',1));
		return $view->getTemplateResult();
	}
}