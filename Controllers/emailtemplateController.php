<?php
namespace Controllers;
use matt, matt\Exceptions, SIIKerES\store;

class emailtemplateController extends matt\MattableController {

	public function __construct($generalDataLoader,$actionName=null,$commandString=null) {
		$this->setEntityName('Entities\Emailtemplate');
		$this->setEm(store::getEm());
		$this->setTemplateFactory(store::getTemplateFactory());
		$this->setKarbFormTplName('emailtemplatekarbform.tpl');
		$this->setKarbTplName('emailtemplatekarb.tpl');
		$this->setListBodyRowTplName('emailtemplatelista_tbody_tr.tpl');
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
			$x['szoveg']=$t->getSzoveg();
		}
		else {
			$x['id']=0;
			$x['nev']='';
			$x['szoveg']='';
		}
		return $x;
	}

	protected function setFields($obj) {
		try {
			$obj->setNev($this->getStringParam('nev'));
			$obj->setSzoveg($this->getStringParam('szoveg'));
		}
		catch (matt\Exceptions\WrongValueTypeException $e){
		}
		return $obj;
	}

	protected function getlistbody() {
		$view=$this->createView('emailtemplatelista_tbody.tpl');

		$filter=array();
		if (!is_null($this->getParam('nevfilter',NULL))) {
			$filter['fields'][]='nev';
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
		$view=$this->createView('emailtemplatelista.tpl');

		$view->setVar('pagetitle',t('emailtemplate'));
		$view->setVar('controllerscript','emailtemplatelista.js');
		$view->printTemplateResult();
	}

	protected function viewlist() {
		$view=$this->createView('emailtemplatelista.tpl');

		$view->setVar('pagetitle',t('emailtemplate'));
		$view->setVar('controllerscript','emailtemplatelista.js');
		$view->setVar('orderselect',$this->getRepo()->getOrdersForTpl());
		$view->setVar('batchesselect',$this->getRepo()->getBatchesForTpl());
		$view->printTemplateResult();
	}

	protected function _getkarb($id,$oper,$tplname) {
		$view=$this->createView($tplname);

		$view->setVar('pagetitle',t('emailtemplate'));
		$view->setVar('controllerscript','emailtemplatekarb.js');
		$view->setVar('formaction','/admin/emailtemplate/save');
		$view->setVar('oper',$oper);
		$record=$this->getRepo()->find($id);
		$view->setVar('egyed',$this->loadVars($record));
		return $view->getTemplateResult();
	}
}