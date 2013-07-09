<?php
namespace Controllers;
use mkw\store;

class uzletkotoController extends \mkwhelpers\MattableController {

	public function __construct($params) {
		$this->setEntityName('Entities\Uzletkoto');
		$this->setKarbFormTplName('uzletkotokarbform.tpl');
		$this->setKarbTplName('uzletkotokarb.tpl');
		$this->setListBodyRowTplName('uzletkotolista_tbody_tr.tpl');
		$this->setListBodyRowVarName('_uzletkoto');
		parent::__construct($params);
	}

	protected function loadVars($t) {
		$x=array();
		if (!$t) {
			$t=new \Entities\Uzletkoto();
			$this->getEm()->detach($t);
		}
		$x['id']=$t->getId();
		$x['nev']=$t->getNev();
		$x['cim']=$t->getCim();
		$x['irszam']=$t->getIrszam();
		$x['varos']=$t->getVaros();
		$x['utca']=$t->getUtca();
		$x['telefon']=$t->getTelefon();
		$x['mobil']=$t->getMobil();
		$x['fax']=$t->getFax();
		$x['email']=$t->getEmail();
		$x['honlap']=$t->getHonlap();
		$x['megjegyzes']=$t->getMegjegyzes();
		return $x;
	}

	/*
	 *  EntityController->save() hívja, ezért kell protected-nek lennie
	 */
	protected function setFields($obj) {
		$obj->setNev($this->params->getStringRequestParam('nev'));
		$obj->setIrszam($this->params->getStringRequestParam('irszam'));
		$obj->setVaros($this->params->getStringRequestParam('varos'));
		$obj->setUtca($this->params->getStringRequestParam('utca'));
		$obj->setTelefon($this->params->getStringRequestParam('telefon'));
		$obj->setMobil($this->params->getStringRequestParam('mobil'));
		$obj->setFax($this->params->getStringRequestParam('fax'));
		$obj->setEmail($this->params->getStringRequestParam('email'));
		$obj->setHonlap($this->params->getStringRequestParam('honlap'));
		$obj->setMegjegyzes($this->params->getStringRequestParam('megjegyzes'));
		return $obj;
	}

	public function getlistbody() {
		$view=$this->createView('uzletkotolista_tbody.tpl');

		$filter=array();

		if (!is_null($this->params->getRequestParam('nevfilter',NULL))) {
			$filter['fields'][]='nev';
			$filter['values'][]=$this->params->getStringRequestParam('nevfilter');
		}

		$this->initPager($this->getRepo()->getCount($filter));

		$uk=$this->getRepo()->getWithJoins(
			$filter,
			$this->getOrderArray(),
			$this->getPager()->getOffset(),
			$this->getPager()->getElemPerPage());

		echo json_encode($this->loadDataToView($uk,'uzletkotolista',$view));
	}

	public function viewlist() {
		$view=$this->createView('uzletkotolista.tpl');
		$view->setVar('pagetitle',t('Üzletkötők'));
		$view->setVar('orderselect',$this->getRepo()->getOrdersForTpl());
		$view->setVar('batchesselect',$this->getRepo()->getBatchesForTpl());
		$view->printTemplateResult();
	}

	public function getSelectList($selid) {
		$rec=$this->getRepo()->getAll(array(),array('nev'=>'ASC'));
		$res=array();
		foreach($rec as $sor) {
			$res[]=array('id'=>$sor->getId(),'caption'=>$sor->getNev(),'selected'=>($sor->getId()==$selid));
		}
		return $res;
	}

	protected function _getkarb($tplname) {
		$id=$this->params->getRequestParam('id',0);
		$oper=$this->params->getRequestParam('oper','');
		$view=$this->createView($tplname);
		$view->setVar('pagetitle',t('Üzletkötő'));
		$view->setVar('oper',$oper);

		$partner=$this->getRepo()->findWithJoins($id);
		// loadVars utan nem abc sorrendben adja vissza

		$view->setVar('uzletkoto',$this->loadVars($partner));
		$view->printTemplateResult();
	}

	public function htmllist() {
		$rec=$this->getRepo()->getAll(array(),array('nev'=>'asc'));
		$ret='<select>';
		foreach($rec as $sor) {
			$ret.='<option value="'.$sor->getId().'">'.$sor->getNev().'</option>';
		}
		$ret.='</select>';
		echo $ret;
	}
}