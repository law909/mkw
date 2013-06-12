<?php
namespace Controllers;
use Entities\Bizonylattetel;

use matt, matt\Exceptions, SIIKerES\store;

class bizonylatfejController extends matt\MattableController {

	public function __construct($generalDataLoader,$actionName=null,$commandString=null) {
		$this->setEntityName('Entities\Megrendelesfej');
		$this->setEm(store::getEm());
		$this->setTemplateFactory(store::getTemplateFactory());
//		$this->setKarbFormTplName('megrendelesfejkarbform.tpl');
//		$this->setKarbTplName('megrendelesfejkarb.tpl');
//		$this->setListBodyRowTplName('megrendelesfejlista_tbody_tr.tpl');
		$this->setListBodyRowVarName('_egyed');
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

	protected function loadVars($t,$forKarb=false) {
		$tetelCtrl=new bizonylattetelController($this->generalDataLoader);
		$tetel=array();
		$x=array();
		if ($t) {
			$x['id']=$t->getId();
			$x['bizonylatnev']=$t->getBizonylatnev();
			$x['erbizonylatszam']=$t->getErbizonylatszam();
			$x['keltstr']=$t->getKeltStr();
			$x['teljesitesstr']=$t->getTeljesitesStr();
			$x['esedekessegstr']=$t->getEsedekessegStr();
			$x['hataridostr']=$t->getHataridoStr();
			$x['partner']=$t->getPartnerId();
			$x['partnernev']=$t->getPartnernev();
			$x['partnerirszam']=$t->getPartnerirszam();
			$x['partnervaros']=$t->getPartnervaros();
			$x['partnerutca']=$t->getPartnerutca();
			$x['raktar']=$t->getRaktarId();
			$x['raktarnev']=$t->getRaktarnev();
			$x['fizmod']=$t->getFizmodId();
			$x['fizmodnev']=$t->getFizmodnev();
			$x['valutanem']=$t->getValutanemId();
			$x['valutanemnev']=$t->getValutanemNev();
			$x['arfolyam']=$t->getArfolyam();
			$x['bankszamla']=$t->getBankszamlaId();
			$x['bankszamlanev']=$t->getBankszamlaNev();
			$x['netto']=$t->getNetto();
			$x['afa']=$t->getAfa();
			$x['brutto']=$t->getBrutto();
			$x['nettohuf']=$t->getNettohuf();
			$x['afahuf']=$t->getAfahuf();
			$x['bruttohuf']=$t->getBruttohuf();
			$x['megjegyzes']=$t->getMegjegyzes();
			if ($forKarb) {
				foreach($t->getBizonylattetelek() as $ttetel) {
					$tetel[]=$tetelCtrl->loadVars($ttetel,true);
				}
//				$tetel[]=$tetelCtrl->loadVars(null,true);
				$x['tetelek']=$tetel;
			}
		}
		else {
			$x['id']='';
			$x['bizonylatnev']='';
			$x['erbizonylatszam']='';
			$ma=new \DateTime();
			$x['keltstr']=$ma->format(store::$DateFormat);
			$x['teljesitesstr']=$ma->format(store::$DateFormat);
			$x['esedekessegstr']=$ma->format(store::$DateFormat);
			$x['hataridostr']=$ma->format(store::$DateFormat);
			$x['partner']=0;
			$x['partnernev']='';
			$x['partnerirszam']='';
			$x['partnervaros']='';
			$x['partnerutca']='';
			$x['raktar']=0;
			$x['raktarnev']='';
			$x['fizmod']=0;
			$x['fizmodnev']='';
			$x['valutanem']=0;
			$x['valutanemnev']='';
			$x['arfolyam']=0;
			$x['bankszamla']=0;
			$x['bankszamlanev']='';
			$x['netto']=0;
			$x['afa']=0;
			$x['brutto']=0;
			$x['nettohuf']=0;
			$x['afahuf']=0;
			$x['bruttohuf']=0;
			$x['megjegyzes']='';
			if ($forKarb) {
//				$tetel[]=$tetelCtrl->loadVars(null,true);
				$x['tetelek']=$tetel;
			}
		}
		return $x;
	}

	protected function setFields($obj) {
		try {
			$obj->setPersistentData(); // a biz. állandó adatait tölti fel (biz.tip-ból, tulaj adatok)

			$obj->setErbizonylatszam($this->getStringParam('erbizonylatszam'));
			$ck=store::getEm()->getRepository('Entities\Partner')->find($this->getIntParam('partner'));
			if ($ck) {
				$obj->setPartner($ck);
			}
			$ck=store::getEm()->getRepository('Entities\Raktar')->find($this->getIntParam('raktar'));
			if ($ck) {
				$obj->setRaktar($ck);
			}
			$ck=store::getEm()->getRepository('Entities\Fizmod')->find($this->getIntParam('fizmod'));
			if ($ck) {
				$obj->setFizmod($ck);
			}
			$obj->setKelt($this->getStringParam('kelt'));
			$obj->setTeljesites($this->getStringParam('teljesites'));
			$obj->setEsedekesseg($this->getStringParam('esedekesseg'));
			$obj->setHatarido($this->getStringParam('hatarido'));

			$ck=store::getEm()->getRepository('Entities\Valutanem')->find($this->getIntParam('valutanem'));
			if ($ck) {
				$obj->setValutanem($ck);
			}

			$obj->setArfolyam($this->getNumParam('arfolyam'));

			$ck=store::getEm()->getRepository('Entities\Bankszamla')->find($this->getIntParam('bankszamla'));
			if ($ck) {
				$obj->setBankszamla($ck);
			}

			$obj->setMegjegyzes($this->getStringParam('megjegyzes'));

			$obj->generateId(); // az üres kelt miatt került a végére

			$tetelids=$this->getArrayParam('tetelid');
			foreach($tetelids as $tetelid) {
				if (($this->getIntParam('teteltermek_'.$tetelid)>0)) {
					$oper=$this->getStringParam('teteloper_'.$tetelid);
					$termek=$this->getEm()->getRepository('Entities\Termek')->find($this->getIntParam('teteltermek_'.$tetelid));
					if ($oper=='add') {
						$tetel=new Bizonylattetel();
						$obj->addBizonylattetel($tetel);
						$tetel->setPersistentData();
						$tetel->setTetelsorszam(1);
						$tetel->setArvaltoztat(0);
						if ($termek) {
							$tetel->setTermek($termek);
						}
						$tetel->setMozgat();
						$tetel->setMennyiseg($this->getFloatParam('tetelmennyiseg_'.$tetelid));
						$tetel->setNettoegysar($this->getFloatParam('tetelnettoegysar_'.$tetelid));
						$tetel->setBruttoegysar($this->getFloatParam('tetelbruttoegysar_'.$tetelid));
						$tetel->setNetto($this->getFloatParam('tetelnetto_'.$tetelid));
						$tetel->setBrutto($this->getFloatParam('tetelbrutto_'.$tetelid));
						$tetel->setAfaertek($tetel->getBrutto()-$tetel->getNetto());
						$tetel->setNettoegysarhuf($this->getFloatParam('tetelnettoegysarhuf_'.$tetelid));
						$tetel->setBruttoegysarhuf($this->getFloatParam('tetelbruttoegysarhuf_'.$tetelid));
						$tetel->setNettohuf($this->getFloatParam('tetelnettohuf_'.$tetelid));
						$tetel->setBruttohuf($this->getFloatParam('tetelbruttohuf_'.$tetelid));
						$tetel->setAfaertekhuf($tetel->getBruttohuf()-$tetel->getNettohuf());
						$tetel->setHatarido($this->getStringParam('tetelhatarido_'.$tetelid));
//						$tetel->setArfolyam($this->getFloatParam('arfolyam'));
						$this->getEm()->persist($tetel);
					}
					elseif ($oper=='edit') {
						$tetel=$this->getEm()->getRepository('Entities\Bizonylattetel')->find($tetelid);
						if ($tetel) {
							$tetel->setPersistentData();
							$tetel->setMozgat();
							if ($termek) {
								$tetel->setTermek($termek);
							}
							$tetel->setMozgat();
							$tetel->setMennyiseg($this->getFloatParam('tetelmennyiseg_'.$tetelid));
							$tetel->setNettoegysar($this->getFloatParam('tetelnettoegysar_'.$tetelid));
							$tetel->setBruttoegysar($this->getFloatParam('tetelbruttoegysar_'.$tetelid));
							$tetel->setNetto($this->getFloatParam('tetelnetto_'.$tetelid));
							$tetel->setBrutto($this->getFloatParam('tetelbrutto_'.$tetelid));
							$tetel->setAfaertek($tetel->getBrutto()-$tetel->getNetto());
							$tetel->setNettoegysarhuf($this->getFloatParam('tetelnettoegysarhuf_'.$tetelid));
							$tetel->setBruttoegysarhuf($this->getFloatParam('tetelbruttoegysarhuf_'.$tetelid));
							$tetel->setNettohuf($this->getFloatParam('tetelnettohuf_'.$tetelid));
							$tetel->setBruttohuf($this->getFloatParam('tetelbruttohuf_'.$tetelid));
							$tetel->setAfaertekhuf($tetel->getBruttohuf()-$tetel->getNettohuf());
							$tetel->setHatarido($this->getStringParam('tetelhatarido_'.$tetelid));
//							$tetel->setArfolyam($this->getFloatParam('arfolyam'));
							$this->getEm()->persist($tetel);
						}
					}
				}
			}
		}
		catch (matt\Exceptions\WrongValueTypeException $e){
		}
		return $obj;
	}
}