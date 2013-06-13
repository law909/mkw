<?php
namespace Controllers;
use mkw\store;

class uzletkotoController extends \mkwhelpers\MattableController {

	public function __construct($generalDataLoader,$actionName=null,$commandString=null) {
		$this->setEntityName('Entities\Uzletkoto');
		$this->setEm(store::getEm());
		$this->setTemplateFactory(store::getTemplateFactory());
		$this->setKarbFormTplName('uzletkotokarbform.tpl');
		$this->setKarbTplName('uzletkotokarb.tpl');
		$this->setListBodyRowTplName('uzletkotolista_tbody_tr.tpl');
		$this->setListBodyRowVarName('_uzletkoto');
		parent::__construct($generalDataLoader,$actionName,$commandString);
	}

	protected function loadVars($t) {
		$x=array();
		if ($t) {
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
		}
		else {
			$x['id']=0;
			$x['nev']='';
			$x['cim']='';
			$x['irszam']='';
			$x['varos']='';
			$x['utca']='';
			$x['telefon']='';
			$x['mobil']='';
			$x['fax']='';
			$x['email']='';
			$x['honlap']='';
			$x['megjegyzes']='';
		}
		return $x;
	}

	/*
	 *  EntityController->save() hívja, ezért kell protected-nek lennie
	 */
	protected function setFields($obj) {
		$obj->setNev($this->getStringParam('nev'));
		$obj->setIrszam($this->getStringParam('irszam'));
		$obj->setVaros($this->getStringParam('varos'));
		$obj->setUtca($this->getStringParam('utca'));
		$obj->setTelefon($this->getStringParam('telefon'));
		$obj->setMobil($this->getStringParam('mobil'));
		$obj->setFax($this->getStringParam('fax'));
		$obj->setEmail($this->getStringParam('email'));
		$obj->setHonlap($this->getStringParam('honlap'));
		$obj->setMegjegyzes($this->getStringParam('megjegyzes'));
		return $obj;
	}

	protected function getlistbody() {
		$view=$this->createView('uzletkotolista_tbody.tpl');

		$filter=array();

		if (!is_null($this->getParam('nevfilter',NULL))) {
			$filter['fields'][]='nev';
			$filter['values'][]=$this->getStringParam('nevfilter');
		}

		$this->initPager(
			$this->getRepo()->getCount($filter),
			$this->getIntParam('elemperpage',30),
			$this->getIntParam('pageno',1));

		$uk=$this->getRepo()->getWithJoins(
			$filter,
			$this->getOrderArray(),
			$this->getPager()->getOffset(),
			$this->getPager()->getElemPerPage());

		echo json_encode($this->loadDataToView($uk,'uzletkotolista',$view));
	}

	protected function viewlist() {
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

	protected function _getkarb($id,$oper,$tplname) {
		$view=$this->createView($tplname);
		$view->setVar('pagetitle',t('Üzletkötő'));
		$view->setVar('oper',$oper);

		$partner=$this->getRepo()->findWithJoins($id);
		// loadVars utan nem abc sorrendben adja vissza

		$view->setVar('uzletkoto',$this->loadVars($partner));
		$view->printTemplateResult();
	}

	protected function htmllist() {
		$rec=$this->getRepo()->getAll(array(),array('nev'=>'asc'));
		$ret='<select>';
		foreach($rec as $sor) {
			$ret.='<option value="'.$sor->getId().'">'.$sor->getNev().'</option>';
		}
		$ret.='</select>';
		echo $ret;
	}
}