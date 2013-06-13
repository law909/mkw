<?php
namespace Controllers;
use mkw\store;

class esemenyController extends \mkwhelpers\MattableController {

	public function __construct($generalDataLoader,$actionName=null,$commandString=null) {
		$this->setEntityName('Entities\Esemeny');
		$this->setEm(store::getEm());
		$this->setTemplateFactory(store::getTemplateFactory());
		$this->setKarbFormTplName('esemenykarbform.tpl');
		$this->setKarbTplName('esemenykarb.tpl');
		$this->setListBodyRowTplName('esemenylista_tbody_tr.tpl');
		$this->setListBodyRowVarName('_egyed');
		parent::__construct($generalDataLoader,$actionName,$commandString);
	}

	protected function loadVars($t) {
		$x=array();
		if ($t) {
			$x['id']=$t->getId();
			$x['bejegyzes']=$t->getBejegyzes();
			$x['leiras']=$t->getLeiras();
			$x['letrehozva']=$t->getLetrehozva();
			$x['esedekes']=$t->getEsedekes();
			$x['esedekesstr']=$t->getEsedekesStr();
			$x['partnernev']=$t->getPartnerNev();
		}
		else {
			$x['id']=0;
			$x['bejegyzes']='';
			$x['leiras']='';
			$x['letrehozva']=new \DateTime();
			$x['esedekes']=new \DateTime();
			$x['esedekesstr']=date(store::$DateFormat);
			$x['partner']=null;
			$x['partnernev']='';
		}
		return $x;
	}

	protected function setFields($obj) {
		$ck=store::getEm()->getRepository('Entities\Partner')->find($this->getIntParam('partner',0));
		if ($ck) {
			$obj->setPartner($ck);
		}
		$obj->setBejegyzes($this->getStringParam('bejegyzes'));
		$obj->setLeiras($this->getStringParam('leiras'));
		$obj->setEsedekes($this->getStringParam('esedekes'));
		return $obj;
	}

	protected function getlistbody() {
		$view=$this->createView('esemenylista_tbody.tpl');

		$filterarr=array();
		if (!is_null($this->getParam('bejegyzesfilter',NULL))) {
			$filterarr['fields'][]=array('_xx.bejegyzes','_xx.leiras','a.nev');
			$filterarr['values'][]=$this->getStringParam('bejegyzesfilter');
			$filterarr['clauses'][]='';
		}

		$fv=$this->getStringParam('dtfilter');
		if ($fv!=='') {
			$filterarr['fields'][]='_xx.esedekes';
			$filterarr['clauses'][]='>=';
			$filterarr['values'][]=store::convDate($fv);
		}

		$fv=$this->getStringParam('difilter');
		if ($fv!=='') {
			$filterarr['fields'][]='_xx.esedekes';
			$filterarr['clauses'][]='<=';
			$filterarr['values'][]=store::convDate($fv);
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

	protected function viewselect() {
		$view=$this->createView('esemenylista.tpl');

		$view->setVar('pagetitle',t('Események'));
		$view->setVar('controllerscript','esemenylista.js');
		$view->printTemplateResult();
	}

	protected function viewlist() {
		$view=$this->createView('esemenylista.tpl');

		$view->setVar('pagetitle',t('Események'));
		$view->setVar('controllerscript','esemenylista.js');
		$view->setVar('orderselect',$this->getRepo()->getOrdersForTpl());
		$view->setVar('batchesselect',$this->getRepo()->getBatchesForTpl());
		$view->printTemplateResult();
	}

	protected function _getkarb($id,$oper,$tplname) {
		$view=$this->createView($tplname);

		$view->setVar('pagetitle',t('Esemény'));
		$view->setVar('controllerscript','esemenykarb.js');
		$view->setVar('formaction','/admin/esemeny/save');
		$view->setVar('oper',$oper);
		$record=$this->getRepo()->findWithJoins($id);
		$view->setVar('egyed',$this->loadVars($record));

		$partner=new partnerController($this->generalDataLoader);
		$view->setVar('partnerlist',$partner->getSelectList(($record?$record->getPartnerId():0)));

		return $view->getTemplateResult();
	}
}