<?php
namespace Controllers;
use mkw\store;

class termekkapcsolodoController extends \mkwhelpers\MattableController {

	public function __construct($generalDataLoader,$actionName=null,$commandString=null) {
		$this->setEntityName('Entities\TermekKapcsolodo');
		$this->setEm(store::getEm());
		$this->setTemplateFactory(store::getTemplateFactory());
//		$this->setKarbFormTplName('?howto?karbform.tpl');
//		$this->setKarbTplName('?howto?karb.tpl');
//		$this->setListBodyRowTplName('?howto?lista_tbody_tr.tpl');
//		$this->setListBodyRowVarName('_egyed');
		parent::__construct($generalDataLoader,$actionName,$commandString);
	}

	public function loadVars($t,$forKarb=false) {
		$termek=new termekController($this->generalDataLoader);
		$x=array();
		if ($t) {
			$x['id']=$t->getId();
			$x['termek']=$t->getTermek();
			$x['termeknev']=$t->getTermekNev();
			$x['altermek']=$t->getAlTermek();
			$x['altermeknev']=$t->getAlTermekNev();
			$x['altermekkepurl']=$t->getAlTermek()->getKepUrlSmall();
			if ($forKarb) {
				$x['termeklist']=$termek->getSelectList(($t->getAlTermek()?$t->getAlTermek()->getId():0));
			}
			$x['oper']='edit';
		}
		else {
			$x['id']=store::createUID();
			$x['termek']=0;
			$x['termeknev']='';
			$x['altermek']=0;
			$x['altermeknev']='';
			$x['altermekkepurl']='';
			if ($forKarb) {
				$x['termeklist']=$termek->getSelectList(0);
			}
			$x['oper']='add';
		}
		return $x;
	}

	protected function setFields($obj) {
		$ck=store::getEm()->getRepository('Entities\Termek')->find($this->getIntParam('altermek'));
		if ($ck) {
			$obj->setAlTermek($ck);
		}
		return $obj;
	}

	protected function getemptyrow() {
		$view=$this->createView('termektermekkapcsolodokarb.tpl');
		$view->setVar('kapcsolodo',$this->loadVars(null,true));
		echo $view->getTemplateResult();
	}
}