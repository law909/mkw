<?php
namespace Controllers;

use mkw\store;

class ?howto?Controller extends \mkwhelpers\MattableController {

	public function __construct($params) {
		$this->setEntityName('Entities\?Howto?');
		$this->setKarbFormTplName('?howto?karbform.tpl');
		$this->setKarbTplName('?howto?karb.tpl');
		$this->setListBodyRowTplName('?howto?lista_tbody_tr.tpl');
		$this->setListBodyRowVarName('_egyed');
		parent::__construct($params);
	}

	protected function loadVars($t) {
		$x=array();
		if (!$t) {
			$t=new \Entities\Termek();
			$this->getEm()->detach($t);
		}
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
		return $x;
	}

	protected function setFields($obj) {
		$obj->setNev($this->params->getStringRequestParam('nev'));
		/* MINTA ha nem kell, dobd ki
		$ck=store::getEm()->getRepository('Entities\Termekcimkekat')->find($this->params->getIntRequestParam('cimkecsoport'));
		if ($ck) {
			$obj->setKategoria($ck);
		}
		*/
		return $obj;
	}

	public function getlistbody() {
		$view=$this->createView('?howto?lista_tbody.tpl');

		$filter=array();
		if (!is_null($this->params->getRequestParam('nevfilter',NULL))) {
			$filter['fields'][]='nev';
			$filter['values'][]=$this->params->getStringRequestParam('nevfilter');
		}

		$this->initPager(
			$this->getRepo()->getCount($filter),
			$this->params->getIntRequestParam('elemperpage',30),
			$this->params->getIntRequestParam('pageno',1));

		$egyedek=$this->getRepo()->getWithJoins(
			$filter,
			$this->getOrderArray(),
			$this->getPager()->getOffset(),
			$this->getPager()->getElemPerPage());

		echo json_encode($this->loadDataToView($egyedek,'egyedlista',$view));
	}

	public function viewselect() {
		$view=$this->createView('?howto?lista.tpl');

		$view->setVar('pagetitle',t('?howto?'));
		$view->setVar('controllerscript','?howto?lista.js');
		$view->printTemplateResult();
	}

	public function viewlist() {
		$view=$this->createView('?howto?lista.tpl');

		$view->setVar('pagetitle',t('?howto?'));
		$view->setVar('controllerscript','?howto?lista.js');
		$view->setVar('orderselect',$this->getRepo()->getOrdersForTpl());
		$view->setVar('batchesselect',$this->getRepo()->getBatchesForTpl());
		$view->printTemplateResult();
	}

	protected function _getkarb($id,$oper,$tplname) {
		$view=$this->createView($tplname);

		$view->setVar('pagetitle',t('?howto?'));
		$view->setVar('controllerscript','?howto?karb.js');
		$view->setVar('formaction','/admin/?howto?/save');
		$view->setVar('oper',$oper);
		$record=$this->getRepo()->findWithJoins($id);
		$view->setVar('egyed',$this->loadVars($record));
		return $view->getTemplateResult();
	}

/* MINTA, ha nem kell akkor kidobandó
	protected function setmenulathato() {
		$id=$this->params->getIntRequestParam('id');
		$kibe=$this->params->getBoolRequestParam('kibe');
		$num=$this->params->getIntRequestParam('num');
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