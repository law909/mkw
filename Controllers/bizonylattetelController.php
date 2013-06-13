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
		if ($t) {
			$x['id']=$t->getId();
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
				$x['termeklist']=$termek->getBizonylattetelSelectList(($t->getTermek()?$t->getTermek()->getId():0));
				$x['vtszlist']=$vtsz->getSelectList(($t->getVtsz()?$t->getVtsz()->getId():0));
				$x['afalist']=$afa->getSelectList(($t->getAfa()?$t->getAfa()->getId():0));
			}
			$x['oper']='edit';
		}
		else {
			$x['id']=store::createUID();
			$x['termek']=0;
			$x['termeknev']='';
			$x['cikkszam']='';
			$x['me']='';
			$x['mennyiseg']=0;
			$x['nettoegysar']=0;
			$x['bruttoegysar']=0;
			$x['netto']=0;
			$x['brutto']=0;
			$x['nettoegysarhuf']=0;
			$x['bruttoegysarhuf']=0;
			$x['nettohuf']=0;
			$x['bruttohuf']=0;
			$x['hataridostr']='';
			if ($forKarb) {
				$x['termeklist']=$termek->getBizonylattetelSelectList(0);
				$x['vtszlist']=$vtsz->getSelectList(0);
				$x['afalist']=$afa->getSelectList(0);
			}
			$x['oper']='add';
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
		$calc=new ArCalculator(
			$this->getEm()->getRepository('Entities\Valutanem')->find($this->params->getIntRequestParam('valutanem')),
			$this->getEm()->getRepository('Entities\Partner')->find($this->params->getIntRequestParam('partner')),
			$this->getEm()->getRepository('Entities\Termek')->find($this->params->getIntRequestParam('termek')));
		echo $calc->getPartnerAr();
	}

	public function calcar() {
		$afa=$this->getEm()->getRepository('Entities\Afa')->find($this->params->getIntRequestParam('afa'));
		$arfolyam=$this->params->getNumRequestParam('arfolyam',1);
		$nettoegysar=$this->params->getNumRequestParam('nettoegysar',0);
		$mennyiseg=$this->params->getNumRequestParam('mennyiseg',0);

		$bruttoegysar=$afa->calcBrutto($nettoegysar);
		$netto=$nettoegysar*$mennyiseg;
		$brutto=$afa->calcBrutto($netto);
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