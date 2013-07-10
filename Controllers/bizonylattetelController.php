<?php
namespace Controllers;
use mkw\ArCalculator;

use mkw\store;

class bizonylattetelController extends \mkwhelpers\MattableController {

	public function __construct($params) {
		$this->setEntityName('Entities\Bizonylattetel');
//		$this->setKarbFormTplName('?howto?karbform.tpl');
//		$this->setKarbTplName('?howto?karb.tpl');
//		$this->setListBodyRowTplName('?howto?lista_tbody_tr.tpl');
//		$this->setListBodyRowVarName('_egyed');
		parent::__construct($params);
	}

	public function loadVars($t,$forKarb=false) {
		$termek=new termekController($this->params);
		$vtsz=new vtszController($this->params);
		$afa=new afaController($this->params);
		$x=array();
		if (!$t) {
			$t=new \Entities\Bizonylattetel();
			$this->getEm()->detach($t);
			$x['id']=store::createUID();
			$x['oper']='add';
		}
		else {
			$x['id']=$t->getId();
			$x['oper']='edit';
		}
		$x['termek']=$t->getTermekId();
		$x['termeknev']=$t->getTermeknev();
		$x['cikkszam']=$t->getCikkszam();
		$x['me']=$t->getMe();
		$x['mennyiseg']=$t->getMennyiseg();
		$x['nettoegysar']=$t->getNettoegysar();
		$x['bruttoegysar']=$t->getBruttoegysar();
		$x['netto']=$t->getNetto();
		$x['brutto']=$t->getBrutto();
		$x['nettoegysarhuf']=$t->getNettoegysarhuf();
		$x['bruttoegysarhuf']=$t->getBruttoegysarhuf();
		$x['nettohuf']=$t->getNettohuf();
		$x['bruttohuf']=$t->getBruttohuf();
		$x['hataridostr']=$t->getHataridoStr();
		if ($forKarb) {
			// autocomplete van select helyett
			//$x['termeklist']=$termek->getBizonylattetelSelectList(($t->getTermek()?$t->getTermek()->getId():0));
			$x['vtszlist']=$vtsz->getSelectList(($t->getVtsz()?$t->getVtsz()->getId():0));
			$x['afalist']=$afa->getSelectList(($t->getAfa()?$t->getAfa()->getId():0));
		}
		return $x;
	}

	protected function setFields($obj) {
		return $obj;
	}

	public function getemptyrow() {
		$view=$this->createView('bizonylattetelkarb.tpl');
		$view->setVar('tetel',$this->loadVars(null,true));
		echo $view->getTemplateResult();
	}

	public function getar() {
		$termek=$this->getEm()->getRepository('Entities\Termek')->find($this->params->getIntRequestParam('termek'));
		$valtoza=null;
		if ($this->params->getIntRequestParam('valtozat')) {
			$valtozat=$this->getEm()->getRepository('Entities\TermekValtozat')->find($this->params->getIntRequestParam('valtozat'));
		}
		if ($termek) {
			echo $termek->getNettoAr($valtozat);
		}
		else {
			echo 0;
		}
	}

	public function calcar() {
		$afaent=$this->getEm()->getRepository('Entities\Afa')->find($this->params->getIntRequestParam('afa'));
		$arfolyam=$this->params->getNumRequestParam('arfolyam',1);
		$nettoegysar=$this->params->getNumRequestParam('nettoegysar',0);
		$mennyiseg=$this->params->getNumRequestParam('mennyiseg',0);

		$bruttoegysar=$afaent->calcBrutto($nettoegysar);
		$netto=$nettoegysar*$mennyiseg;
		$brutto=$afaent->calcBrutto($netto);
		$afa=$brutto-$netto;

		$nettoegysarhuf=$nettoegysar*$arfolyam;
		$bruttoegysarhuf=$bruttoegysar*$arfolyam;
		$nettohuf=$netto*$arfolyam;
		$bruttohuf=$brutto*$arfolyam;
		$afahuf=$afa*$arfolyam;
		echo json_encode(
			array(
				'nettoegysar'=>$nettoegysar,
				'bruttoegysar'=>$bruttoegysar,
				'netto'=>$netto,
				'brutto'=>$brutto,
				'afa'=>$afa,
				'nettoegysarhuf'=>$nettoegysarhuf,
				'bruttoegysarhuf'=>$bruttoegysarhuf,
				'nettohuf'=>$nettohuf,
				'bruttohuf'=>$bruttohuf,
				'afahuf'=>$afahuf
			)
		);
	}
}