<?php
namespace Controllers;
use mkw\store;

class termekvaltozatController extends \mkwhelpers\MattableController {

	public function __construct($generalDataLoader,$actionName=null,$commandString=null) {
		$this->setEntityName('Entities\TermekValtozat');
		$this->setEm(store::getEm());
		$this->setTemplateFactory(store::getTemplateFactory());
//		$this->setKarbFormTplName('?howto?karbform.tpl');
//		$this->setKarbTplName('?howto?karb.tpl');
//		$this->setListBodyRowTplName('?howto?lista_tbody_tr.tpl');
//		$this->setListBodyRowVarName('_egyed');
		parent::__construct($generalDataLoader,$actionName,$commandString);
	}

	public function loadVars($t,$termek,$forKarb=false) {
		$tvatc=new termekvaltozatadattipusController($this->generalDataLoader);
		$tkepc=new termekkepController($this->generalDataLoader);
		$x=array();
		if ($t) {
			$x['id']=$t->getId();
			$x['adattipus1id']=$t->getAdatTipus1Id();
			$x['adattipus1nev']=$t->getAdatTipus1Nev();
			$x['adattipus1lista']=$tvatc->getSelectList($t->getAdatTipus1Id());
			$x['ertek1']=$t->getErtek1();
			$x['adattipus2id']=$t->getAdatTipus2Id();
			$x['adattipus2nev']=$t->getAdatTipus2Nev();
			$x['adattipus2lista']=$tvatc->getSelectList($t->getAdatTipus2Id());
			$x['ertek2']=$t->getErtek2();
			$x['lathato']=$t->getLathato();
			$x['elerheto']=$t->getElerheto();
			$x['termekfokep']=$t->getTermekfokep();
			$x['kepurl']=$t->getKepurl();
			$x['kepleiras']=$t->getKepleiras();
			$x['keplista']=$tkepc->getSelectList($t->getTermek(),$t->getKepid());
			$x['kepid']=$t->getKepid();
			$x['netto']=$t->getNetto();
			$x['brutto']=$t->getBrutto();
			$x['oper']='edit';
		}
		else {
			$x['id']=store::createUID();
			$x['adattipus1id']='';
			$x['adattipus1nev']='';
			$x['adattipus1lista']=$tvatc->getSelectList(0);
			$x['ertek1']='';
			$x['adattipus2id']='';
			$x['adattipus2nev']='';
			$x['adattipus2lista']=$tvatc->getSelectList(0);
			$x['ertek2']='';
			$x['lathato']=false;
			$x['elerheto']=false;
			$x['termekfokep']=false;
			$x['kepurl']='';
			$x['kepleiras']='';
			$x['keplista']=$termek?$tkepc->getSelectList($termek,NULL):array();
			$x['kepid']=0;
			$x['netto']=0;
			$x['brutto']=0;
			$x['termek']['id']=$termek->getId();
			$x['oper']='add';
		}
		return $x;
	}

	protected function setFields($obj) {
		$obj->setLathato($this->getBoolParam('lathato',false));
		/* MINTA ha nem kell, dobd ki
		$ck=store::getEm()->getRepository('Entities\Termekcimkekat')->find($this->getIntParam('cimkecsoport'));
		if ($ck) {
			$obj->setKategoria($ck);
		}
		*/
		return $obj;
	}

	protected function getemptyrow() {
		$termek=store::getEm()->getRepository('Entities\Termek')->find($this->getIntParam('termekid'));
		$view=$this->createView('termektermekvaltozatkarb.tpl');
		$view->setVar('valtozat',$this->loadVars(null,$termek,true));
		echo $view->getTemplateResult();
	}

	protected function delall() {
		$termek=store::getEm()->getRepository('Entities\Termek')->find($this->getIntParam('termekid'));
		$valtozatok=$termek->getValtozatok();
		foreach($valtozatok as $valt) {
			//$termek->removeValtozat($valt);
			$this->getEm()->remove($valt);
		}
		$this->getEm()->flush();
	}

	protected function generate() {
		$termek=store::getEm()->getRepository('Entities\Termek')->find($this->getIntParam('termekid'));

		$adattipus1=$this->getIntParam('valtozatadattipus1');
		$ertek1=$this->getStringParam('valtozatertek1');
		$adattipus2=$this->getIntParam('valtozatadattipus2');
		$ertek2=$this->getStringParam('valtozatertek2');
		$netto=$this->getNumParam('valtozatnettogen');
		$brutto=$this->getNumParam('valtozatbruttogen');
		$elerheto=$this->getBoolParam('valtozatelerheto',false);
		$lathato=$this->getBoolParam('valtozatlathato',false);
		$termekfokep=$this->getBoolParam('valtozattermekfokep',false);
		$kepid=$this->getIntParam('valtozatkepid');

		if (($adattipus1&&$ertek1)||($adattipus2&&ertek2)) {
			$ertekek1=split(';',$ertek1);
			$ertekek2=split(';',$ertek2);

			$at1=$this->getEm()->getRepository('Entities\TermekValtozatAdatTipus')->find($adattipus1);
			$at2=$this->getEm()->getRepository('Entities\TermekValtozatAdatTipus')->find($adattipus2);
			foreach($ertekek1 as $ertek1) {
				foreach($ertekek2 as $ertek2) {
					$valtdb=0;
					$valtozat=new \Entities\TermekValtozat();
					$termek->addValtozat($valtozat);
					$valtozat->setLathato($lathato);
					if ($termek->getNemkaphato()) {
						$valtozat->setElerheto(false);
					}
					else {
						$valtozat->setElerheto($elerheto);
					}
	//					$valtozat->setBrutto($brutto);
					$valtozat->setNetto($netto);
					$valtozat->setTermekfokep($termekfokep);

					if ($at1&&$ertek1) {
						$valtozat->setAdatTipus1($at1);
						$valtozat->setErtek1($ertek1);
						$valtdb++;
					}

					if ($at2&&$ertek2) {
						$valtozat->setAdatTipus2($at2);
						$valtozat->setErtek2($ertek2);
						$valtdb++;
					}

					$kep=$this->getEm()->getRepository('Entities\TermekKep')->find($kepid);
					if ($kep) {
						$valtozat->setKep($kep);
					}

					if ($valtdb>0) {
						$this->getEm()->persist($valtozat);
					}
					else {
						$termek->removeValtozat($valtozat);
					}
				}
			}
			$this->getEm()->flush();
		}

		$view=$this->createView('termektermekvaltozatkarb.tpl');
		$valtozatok=$termek->getValtozatok();
		$result='';
		foreach($valtozatok as $valt) {
			$view->setVar('valtozat',$this->loadVars($valt,$termek,true));
			$result.=$view->getTemplateResult();
		}
		echo $result;
	}
}