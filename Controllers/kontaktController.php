<?php
namespace Controllers;
use mkw\store;

class kontaktController extends \mkwhelpers\MattableController {

	public function __construct($params) {
		$this->setEntityName('Entities\Kontakt');
//		$this->setKarbFormTplName('?howto?karbform.tpl');
//		$this->setKarbTplName('?howto?karb.tpl');
//		$this->setListBodyRowTplName('?howto?lista_tbody_tr.tpl');
//		$this->setListBodyRowVarName('_egyed');
		parent::__construct($params);
	}

	public function loadVars($t) {
		$x=array();
		if (!$t) {
			$t=new \Entities\Kontakt();
			$this->getEm()->detach($t);
		}
		$x['id']=$t->getId();
		$x['nev']=$t->getNev();
		$x['telefon']=$t->getTelefon();
		$x['mobil']=$t->getMobil();
		$x['fax']=$t->getFax();
		$x['email']=$t->getEmail();
		$x['honlap']=$t->getHonlap();
		$x['megjegyzes']=$t->getMegjegyzes();
		$x['oper']='edit';
		return $x;
	}

	public function setFields($obj) {
		$obj->setNev($this->params->getStringRequestParam('nev'));
		$obj->setTelefon($this->params->getStringRequestParam('telefon'));
		$obj->setMobil($this->params->getStringRequestParam('mobil'));
		$obj->setFax($this->params->getStringRequestParam('fax'));
		$obj->setEmail($this->params->getStringRequestParam('email'));
		$obj->setHonlap($this->params->getStringRequestParam('honlap'));
		$obj->setMegjegyzes($this->params->getStringRequestParam('megjegyzes'));
		return $obj;
	}

	public function getemptyrow() {
		$view=$this->createView('partnerkontaktkarb.tpl');
		$view->setVar('kontakt',$this->loadVars(null));
		echo $view->getTemplateResult();
	}
}