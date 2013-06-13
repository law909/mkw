<?php
namespace Controllers;
use matt, matt\Exceptions, mkw\store;

class kontaktController extends matt\MattableController {

	public function __construct($generalDataLoader,$actionName=null,$commandString=null) {
		$this->setEntityName('Entities\Kontakt');
		$this->setEm(store::getEm());
		$this->setTemplateFactory(store::getTemplateFactory());
//		$this->setKarbFormTplName('?howto?karbform.tpl');
//		$this->setKarbTplName('?howto?karb.tpl');
//		$this->setListBodyRowTplName('?howto?lista_tbody_tr.tpl');
//		$this->setListBodyRowVarName('_egyed');
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

	public function loadVars($t) {
		$x=array();
		if ($t) {
			$x['id']=$t->getId();
			$x['nev']=$t->getNev();
			$x['telefon']=$t->getTelefon();
			$x['mobil']=$t->getMobil();
			$x['fax']=$t->getFax();
			$x['email']=$t->getEmail();
			$x['honlap']=$t->getHonlap();
			$x['megjegyzes']=$t->getMegjegyzes();
			$x['oper']='edit';
		}
		else {
			$x['id']=store::createUID();
			$x['nev']='';
			$x['telefon']='';
			$x['mobil']='';
			$x['fax']='';
			$x['email']='';
			$x['honlap']='';
			$x['megjegyzes']='';
			$x['oper']='add';
		}
		return $x;
	}

	protected function setFields($obj) {
		try {
			$obj->setNev($this->getStringParam('nev'));
			$obj->setTelefon($this->getStringParam('telefon'));
			$obj->setMobil($this->getStringParam('mobil'));
			$obj->setFax($this->getStringParam('fax'));
			$obj->setEmail($this->getStringParam('email'));
			$obj->setHonlap($this->getStringParam('honlap'));
			$obj->setMegjegyzes($this->getStringParam('megjegyzes'));
		}
		catch (matt\Exceptions\WrongValueTypeException $e){
		}
		return $obj;
	}

	protected function getemptyrow() {
		$view=$this->createView('partnerkontaktkarb.tpl');
		$view->setVar('kontakt',$this->loadVars(null));
		echo $view->getTemplateResult();
	}
}