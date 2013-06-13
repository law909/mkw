<?php
namespace Controllers;
use mkw\store;

class termekvaltozatController extends \mkwhelpers\MattableController {

	public function __construct($params) {
		$this->setEntityName('Entities\TermekValtozat');
//		$this->setKarbFormTplName('?howto?karbform.tpl');
//		$this->setKarbTplName('?howto?karb.tpl');
//		$this->setListBodyRowTplName('?howto?lista_tbody_tr.tpl');
//		$this->setListBodyRowVarName('_egyed');
		parent::__construct($params);
	}

	public function loadVars($t,$termek,$forKarb=false) {
		$tvatc=new termekvaltozatadattipusController($this->params);
		$tkepc=new termekkepController($this->params);
		$x=array();
		if (!$t) {
			$t=new \Entities\TermekKapcsolodo();
			$this->getEm()->detach($t);
			$x['oper']='add';
			$x['id']=store::createUID();
			$x['termek']['id']=$termek->getId();
		}
		else {
			$x['oper']='edit';
			$x['id']=$t->getId();
		}
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
		return $x;
	}

	protected function setFields($obj) {
		$obj->setLathato($this->params->getBoolRequestParam('lathato',false));
		/* MINTA ha nem kell, dobd ki
		$ck=store::getEm()->getRepository('Entities\Termekcimkekat')->find($this->getIntRequestParam('cimkecsoport'));
		if ($ck) {
			$obj->setKategoria($ck);
		}
		*/
		return $obj;
	}

	public function getemptyrow() {
		$termek=store::getEm()->getRepository('Entities\Termek')->find($this->params->getIntRequestParam('termekid'));
		$view=$this->createView('termektermekvaltozatkarb.tpl');
		$view->setVar('valtozat',$this->loadVars(null,$termek,true));
		echo $view->getTemplateResult();
	}

	public function delall() {
		$termek=store::getEm()->getRepository('Entities\Termek')->find($this->params->getIntRequestParam('termekid'));
		$valtozatok=$termek->getValtozatok();
		foreach($valtozatok as $valt) {
			//$termek->removeValtozat($valt);
			$this->getEm()->remove($valt);
		}
		$this->getEm()->flush();
	}

	public function generate() {
		$termek=store::getEm()->getRepository('Entities\Termek')->find($this->params->getIntRequestParam('termekid'));

		$adattipus1=$this->params->getIntRequestParam('valtozatadattipus1');
		$ertek1=$this->params->getStringRequestParam('valtozatertek1');
		$adattipus2=$this->params->getIntRequestParam('valtozatadattipus2');
		$ertek2=$this->params->getStringRequestParam('valtozatertek2');
		$netto=$this->params->getNumRequestParam('valtozatnettogen');
		$brutto=$this->params->getNumRequestParam('valtozatbruttogen');
		$elerheto=$this->params->getBoolRequestParam('valtozatelerheto',false);
		$lathato=$this->params->getBoolRequestParam('valtozatlathato',false);
		$termekfokep=$this->params->getBoolRequestParam('valtozattermekfokep',false);
		$kepid=$this->params->getIntRequestParam('valtozatkepid');

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