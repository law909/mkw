<?php
namespace Controllers;
use mkw\store;

class teendoController extends \mkwhelpers\MattableController {

	public function __construct($generalDataLoader,$actionName=null,$commandString=null) {
		$this->setEntityName('Entities\Teendo');
		$this->setEm(store::getEm());
		$this->setTemplateFactory(store::getTemplateFactory());
		$this->setKarbFormTplName('teendokarbform.tpl');
		$this->setKarbTplName('teendokarb.tpl');
		$this->setListBodyRowTplName('teendolista_tbody_tr.tpl');
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
			$x['elvegezve']=$t->getElvegezve();
			$x['elvegezve_mikor']=$t->getElvegezveMikor();
			$x['elvegezve_mikorstr']=$t->getElvegezveMikorStr();
			$x['esedekes']=$t->getEsedekes();
			$x['esedekesstr']=$t->getEsedekesStr();
			$x['partner']=$t->getPartner();
			$x['partnernev']=$t->getPartnerNev();
		}
		else {
			$x['id']=0;
			$x['bejegyzes']='';
			$x['leiras']='';
			$x['letrehozva']=new \DateTime();
			$x['elvegezve']=false;
			$x['elvegezve_mikor']=new \DateTime();;
			$x['elvegezve_mikorstr']=date(store::$DateFormat);
			$x['esedekes']=new \DateTime();
			$x['esedekesstr']=date(store::$DateFormat);
			$x['partner']=null;
			$x['partnernev']='';
		}
		return $x;
	}

	protected function setFields($obj) {
		$ck=store::getEm()->getRepository('Entities\Partner')->find($this->getIntParam('partner'));
		if ($ck) {
			$obj->setPartner($ck);
		}
		$obj->setBejegyzes($this->getStringParam('bejegyzes'));
		$obj->setLeiras($this->getStringParam('leiras'));
		$obj->setEsedekes($this->getDateParam('esedekes'));
		$obj->setElvegezve($this->getBoolParam('elvegezve'));
		return $obj;
	}

	protected function getlistbody() {
		$view=$this->createView('teendolista_tbody.tpl');

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
		$view=$this->createView('teendolista.tpl');

		$view->setVar('pagetitle',t('Teendők'));
		$view->setVar('controllerscript','teendolista.js');
		$view->printTemplateResult();
	}

	protected function viewlist() {
		$view=$this->createView('teendolista.tpl');

		$view->setVar('pagetitle',t('Teendők'));
		$view->setVar('controllerscript','teendolista.js');
		$view->setVar('orderselect',$this->getRepo()->getOrdersForTpl());
		$view->setVar('batchesselect',$this->getRepo()->getBatchesForTpl());
		$view->printTemplateResult();
	}

	protected function _getkarb($id,$oper,$tplname) {
		$view=$this->createView($tplname);

		$view->setVar('pagetitle',t('Teendő'));
		$view->setVar('controllerscript','teendokarb.js');
		$view->setVar('formaction','/admin/teendo/save');
		$view->setVar('oper',$oper);
		$record=$this->getRepo()->findWithJoins($id);
		$view->setVar('egyed',$this->loadVars($record));

		$partner=new partnerController($this->generalDataLoader);
		$view->setVar('partnerlist',$partner->getSelectList(($record?$record->getPartnerId():0)));

		return $view->getTemplateResult();
	}

	protected function setflag() {
		$id=$this->getIntParam('id');
		$kibe=$this->getBoolParam('kibe');
		$flag=$this->getStringParam('flag','');
		$obj=$this->getRepo()->find($id);
		if ($obj) {
			switch ($flag) {
				case 'elvegezve':
					$obj->setElvegezve($kibe);
					break;
			}
			store::getEm()->persist($obj);
			store::getEm()->flush();
		}
	}
}