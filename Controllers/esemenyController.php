<?php
namespace Controllers;
use mkw\store;

class esemenyController extends \mkwhelpers\MattableController {

	public function __construct($params) {
		$this->setEntityName('Entities\Esemeny');
		$this->setKarbFormTplName('esemenykarbform.tpl');
		$this->setKarbTplName('esemenykarb.tpl');
		$this->setListBodyRowTplName('esemenylista_tbody_tr.tpl');
		$this->setListBodyRowVarName('_egyed');
		parent::__construct($params);
	}

	protected function loadVars($t) {
		$x=array();
		if (!$t) {
			$t=new \Entities\Esemeny();
			$this->getEm()->detach($t);
		}
		$x['id']=$t->getId();
		$x['bejegyzes']=$t->getBejegyzes();
		$x['leiras']=$t->getLeiras();
		$x['letrehozva']=$t->getLetrehozva();
		$x['esedekes']=$t->getEsedekes();
		$x['esedekesstr']=$t->getEsedekesStr();
		$x['partnernev']=$t->getPartnerNev();
		return $x;
	}

	protected function setFields($obj) {
		$ck=store::getEm()->getRepository('Entities\Partner')->find($this->params->getIntRequestParam('partner',0));
		if ($ck) {
			$obj->setPartner($ck);
		}
		$obj->setBejegyzes($this->params->getStringRequestParam('bejegyzes'));
		$obj->setLeiras($this->params->getStringRequestParam('leiras'));
		$obj->setEsedekes($this->params->getStringRequestParam('esedekes'));
		return $obj;
	}

	public function getlistbody() {
		$view=$this->createView('esemenylista_tbody.tpl');

		$filterarr=array();
		if (!is_null($this->params->getRequestParam('bejegyzesfilter',NULL))) {
			$filterarr['fields'][]=array('_xx.bejegyzes','_xx.leiras','a.nev');
			$filterarr['values'][]=$this->params->getStringRequestParam('bejegyzesfilter');
			$filterarr['clauses'][]='';
		}

		$fv=$this->params->getStringRequestParam('dtfilter');
		if ($fv!=='') {
			$filterarr['fields'][]='_xx.esedekes';
			$filterarr['clauses'][]='>=';
			$filterarr['values'][]=store::convDate($fv);
		}

		$fv=$this->params->getStringRequestParam('difilter');
		if ($fv!=='') {
			$filterarr['fields'][]='_xx.esedekes';
			$filterarr['clauses'][]='<=';
			$filterarr['values'][]=store::convDate($fv);
		}

		$this->initPager($this->getRepo()->getCount($filterarr));

		$egyedek=$this->getRepo()->getWithJoins(
			$filterarr,
			$this->getOrderArray(),
			$this->getPager()->getOffset(),
			$this->getPager()->getElemPerPage());

		echo json_encode($this->loadDataToView($egyedek,'egyedlista',$view));
	}

	public function viewselect() {
		$view=$this->createView('esemenylista.tpl');

		$view->setVar('pagetitle',t('Események'));
		$view->printTemplateResult();
	}

	public function viewlist() {
		$view=$this->createView('esemenylista.tpl');

		$view->setVar('pagetitle',t('Események'));
		$view->setVar('orderselect',$this->getRepo()->getOrdersForTpl());
		$view->setVar('batchesselect',$this->getRepo()->getBatchesForTpl());
		$view->printTemplateResult();
	}

	protected function _getkarb($tplname) {
		$id=$this->params->getRequestParam('id',0);
		$oper=$this->params->getRequestParam('oper','');
		$view=$this->createView($tplname);

		$view->setVar('pagetitle',t('Esemény'));
		$view->setVar('formaction','/admin/esemeny/save');
		$view->setVar('oper',$oper);
		$record=$this->getRepo()->findWithJoins($id);
		$view->setVar('egyed',$this->loadVars($record));

		$partner=new partnerController($this->params);
		$view->setVar('partnerlist',$partner->getSelectList(($record?$record->getPartnerId():0)));

		return $view->getTemplateResult();
	}
}