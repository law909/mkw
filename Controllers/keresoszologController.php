<?php
namespace Controllers;
use matt, matt\Exceptions, SIIKerES\store;

class keresoszologController extends matt\MattableController {

	public function __construct($generalDataLoader,$actionName=null,$commandString=null) {
		$this->setEntityName('Entities\Keresoszolog');
		$this->setEm(store::getEm());
		$this->setTemplateFactory(store::getTemplateFactory());
		$this->setKarbFormTplName('keresoszologkarbform.tpl');
		$this->setKarbTplName('keresoszologkarb.tpl');
		$this->setListBodyRowTplName('keresoszologlista_tbody_tr.tpl');
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
			/* MINTA ha nem kell, dobd ki
			if ($kat=$t->getKategoria()) {
				$x['cimkekatnev']=$kat->getNev();
			}
			else {
				$x['cimkekatnev']='';
			}
			*/
		}
		else {
			$x['id']=0;
			$x['nev']='';
			/* MINTA ha nem kell, dobd ki
			$x['cimkekatnev']='';
			*/
		}
		return $x;
	}

	protected function setFields($obj) {
		try {
			$obj->setNev($this->getStringParam('nev'));
			/* MINTA ha nem kell, dobd ki
			$ck=store::getEm()->getRepository('Entities\Termekcimkekat')->find($this->getIntParam('cimkecsoport'));
			if ($ck) {
				$obj->setKategoria($ck);
			}
			*/
		}
		catch (matt\Exceptions\WrongValueTypeException $e){
		}
		return $obj;
	}

	protected function getlistbody() {
		$view=$this->createView('keresoszologlista_tbody.tpl');

		$filter=array();
		if (!is_null($this->getParam('nevfilter',NULL))) {
			$filter['fields'][]='nev';
			$filter['values'][]=$this->getStringParam('nevfilter');
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
		$view=$this->createView('keresoszologlista.tpl');

		$view->setVar('pagetitle',t('keresoszolog'));
		$view->setVar('controllerscript','keresoszologlista.js');
		$view->printTemplateResult();
	}

	protected function viewlist() {
		$view=$this->createView('keresoszologlista.tpl');

		$view->setVar('pagetitle',t('keresoszolog'));
		$view->setVar('controllerscript','keresoszologlista.js');
		$view->setVar('orderselect',$this->getRepo()->getOrdersForTpl());
		$view->setVar('batchesselect',$this->getRepo()->getBatchesForTpl());
		$view->printTemplateResult();
	}

	protected function _getkarb($id,$oper,$tplname) {
		$view=$this->createView($tplname);

		$view->setVar('pagetitle',t('keresoszolog'));
		$view->setVar('controllerscript','keresoszologkarb.js');
		$view->setVar('formaction','/admin/keresoszolog/save');
		$view->setVar('oper',$oper);
		$record=$this->getRepo()->findWithJoins($id);
		$view->setVar('egyed',$this->loadVars($record));
		return $view->getTemplateResult();
	}

/* MINTA, ha nem kell akkor kidobandó
	protected function setmenulathato() {
		$id=$this->getIntParam('id');
		$kibe=$this->getBoolParam('kibe');
		$num=$this->getIntParam('num');
		$obj=$this->getRepo()->find($id);
		if ($obj) {
			switch ($num) {
				case 1:
					$obj->setMenu1Lathato($kibe);
					break;
				case 2:
					$obj->setMenu2Lathato($kibe);
					break;
				case 3:
					$obj->setMenu3Lathato($kibe);
					break;
				case 4:
					$obj->setMenu4Lathato($kibe);
					break;
			}
			store::getEm()->persist($obj);
			store::getEm()->flush();
		}
	}
*/
}