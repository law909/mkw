<?php
namespace Controllers;
use matt, matt\Exceptions, SIIKerES\store;

class termekreceptController extends matt\MattableController {

	public function __construct($generalDataLoader,$actionName=null,$commandString=null) {
		$this->setEntityName('Entities\TermekRecept');
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

	public function loadVars($t,$forKarb=false) {
		$termek=new termekController($this->generalDataLoader);
		$x=array();
		if ($t) {
			$x['id']=$t->getId();
			$x['mennyiseg']=$t->getMennyiseg();
			$x['kotelezo']=$t->getKotelezo();
			$x['termek']=$t->getTermek();
			$x['termeknev']=$t->getTermekNev();
			$x['altermek']=$t->getAlTermek();
			$x['altermeknev']=$t->getAlTermekNev();
			if ($forKarb) {
				$x['termeklist']=$termek->getSelectList(($t->getAlTermek()?$t->getAlTermek()->getId():0));
			}
			$x['oper']='edit';
		}
		else {
			$x['id']=store::createUID();
			$x['mennyiseg']=0;
			$x['kotelezo']=false;
			$x['termek']=0;
			$x['termeknev']='';
			$x['altermek']=0;
			$x['altermeknev']='';
			if ($forKarb) {
				$x['termeklist']=$termek->getSelectList(0);
			}
			$x['oper']='add';
		}
		return $x;
	}

	protected function setFields($obj) {
		try {
			$obj->setMennyiseg($this->getFloatParam('mennyiseg'));
			$obj->setKotelezo($this->getBoolParam('kotelezo',false));
			$ck=store::getEm()->getRepository('Entities\Termek')->find($this->getIntParam('altermek'));
			if ($ck) {
				$obj->setAlTermek($ck);
			}
		}
		catch (matt\Exceptions\WrongValueTypeException $e){
		}
		return $obj;
	}

	protected function getemptyrow() {
		$view=$this->createView('termektermekreceptkarb.tpl');
		$view->setVar('recept',$this->loadVars(null,true));
		echo $view->getTemplateResult();
	}
}