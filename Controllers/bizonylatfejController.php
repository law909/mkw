<?php

namespace Controllers;

use Entities\Bizonylattetel;
use mkw\store;

class bizonylatfejController extends \mkwhelpers\MattableController {

	public function __construct($params) {
		$this->setEntityName('Entities\Bizonylatfej');
//		$this->setKarbFormTplName('megrendelesfejkarbform.tpl');
//		$this->setKarbTplName('megrendelesfejkarb.tpl');
//		$this->setListBodyRowTplName('megrendelesfejlista_tbody_tr.tpl');
		$this->setListBodyRowVarName('_egyed');
		parent::__construct($params);
	}

	protected function loadVars($t, $forKarb = false) {
		$tetelCtrl = new bizonylattetelController($this->params);
		$tetel = array();
		$x = array();
		if (!$t) {
			$t = new \Entities\Bizonylatfej();
			$this->getEm()->detach($t);
		}
		$x['id'] = $t->getId();
		$x['bizonylatnev'] = $t->getBizonylatnev();
		$x['erbizonylatszam'] = $t->getErbizonylatszam();
		$x['keltstr'] = $t->getKeltStr();
		$x['teljesitesstr'] = $t->getTeljesitesStr();
		$x['esedekessegstr'] = $t->getEsedekessegStr();
		$x['hataridostr'] = $t->getHataridoStr();
		$x['partner'] = $t->getPartnerId();
		$x['partnernev'] = $t->getPartnernev();
		$x['partnerirszam'] = $t->getPartnerirszam();
		$x['partnervaros'] = $t->getPartnervaros();
		$x['partnerutca'] = $t->getPartnerutca();
		$x['partnertelefon'] = $t->getPartnertelefon();
		$x['partneremail'] = $t->getPartneremail();
		$x['partneradoszam'] = $t->getPartneradoszam();
		$x['raktar'] = $t->getRaktarId();
		$x['raktarnev'] = $t->getRaktarnev();
		$x['fizmod'] = $t->getFizmodId();
		$x['fizmodnev'] = $t->getFizmodnev();
		$x['szallitasimod'] = $t->getSzallitasimodId();
		$x['szallitasimodnev'] = $t->getSzallitasimodnev();
		$x['valutanem'] = $t->getValutanemId();
		$x['valutanemnev'] = $t->getValutanemNev();
		$x['arfolyam'] = $t->getArfolyam();
		$x['bankszamla'] = $t->getBankszamlaId();
		$x['bankszamlanev'] = $t->getBankszamlaNev();
		$x['netto'] = $t->getNetto();
		$x['afa'] = $t->getAfa();
		$x['brutto'] = $t->getBrutto();
		$x['nettohuf'] = $t->getNettohuf();
		$x['afahuf'] = $t->getAfahuf();
		$x['bruttohuf'] = $t->getBruttohuf();
		$x['megjegyzes'] = $t->getMegjegyzes();
		$x['szallnev'] = $t->getSzallnev();
		$x['szallirszam'] = $t->getSzallirszam();
		$x['szallvaros'] = $t->getSzallvaros();
		$x['szallutca'] = $t->getSzallutca();
		$x['webshopmessage'] = $t->getWebshopmessage();
		$x['couriermessage'] = $t->getCouriermessage();
		$x['ip'] = $t->getIp();
		$x['referrer'] = $t->getReferrer();
		if ($forKarb) {
			foreach ($t->getBizonylattetelek() as $ttetel) {
				$tetel[] = $tetelCtrl->loadVars($ttetel, true);
			}
//				$tetel[]=$tetelCtrl->loadVars(null,true);
			$x['tetelek'] = $tetel;
		}
		return $x;
	}

	protected function setFields($obj) {
		$obj->setPersistentData(); // a biz. állandó adatait tölti fel (biz.tip-ból, tulaj adatok)

		$obj->setErbizonylatszam($this->params->getStringRequestParam('erbizonylatszam'));
		$ck = store::getEm()->getRepository('Entities\Partner')->find($this->params->getIntRequestParam('partner'));
		if ($ck) {
			$obj->setPartner($ck);
		}
		$ck = store::getEm()->getRepository('Entities\Raktar')->find($this->params->getIntRequestParam('raktar'));
		if ($ck) {
			$obj->setRaktar($ck);
		}
		$ck = store::getEm()->getRepository('Entities\Fizmod')->find($this->params->getIntRequestParam('fizmod'));
		if ($ck) {
			$obj->setFizmod($ck);
		}
		$ck = store::getEm()->getRepository('Entities\Szallitasimod')->find($this->params->getIntRequestParam('szallitasimod'));
		if ($ck) {
			$obj->setSzallitasimod($ck);
		}
		$obj->setKelt($this->params->getStringRequestParam('kelt'));
		$obj->setTeljesites($this->params->getStringRequestParam('teljesites'));
		$obj->setEsedekesseg($this->params->getStringRequestParam('esedekesseg'));
		$obj->setHatarido($this->params->getStringRequestParam('hatarido'));

		$obj->setPartneradoszam($this->params->getStringRequestParam('partneradoszam'));
		$obj->setPartnerirszam($this->params->getStringRequestParam('partnerirszam'));
		$obj->setPartnervaros($this->params->getStringRequestParam('partnervaros'));
		$obj->setPartnerutca($this->params->getStringRequestParam('partnerutca'));
		$obj->setPartnertelefon($this->params->getStringRequestParam('partnertelefon'));
		$obj->setPartneremail($this->params->getStringRequestParam('partneremail'));

		$obj->setSzallnev($this->params->getStringRequestParam('szallnev'));
		$obj->setSzallirszam($this->params->getStringRequestParam('szallirszam'));
		$obj->setSzallvaros($this->params->getStringRequestParam('szallvaros'));
		$obj->setSzallutca($this->params->getStringRequestParam('szallutca'));

		$ck = store::getEm()->getRepository('Entities\Valutanem')->find($this->params->getIntRequestParam('valutanem'));
		if ($ck) {
			$obj->setValutanem($ck);
		}

		$obj->setArfolyam($this->params->getNumRequestParam('arfolyam'));

		$ck = store::getEm()->getRepository('Entities\Bankszamla')->find($this->params->getIntRequestParam('bankszamla'));
		if ($ck) {
			$obj->setBankszamla($ck);
		}

		$obj->setMegjegyzes($this->params->getStringRequestParam('megjegyzes'));
		$obj->setWebshopmessage($this->params->getStringRequestParam('webshopmessage'));
		$obj->setCouriermessage($this->params->getStringRequestParam('couriermessage'));

		$obj->generateId(); // az üres kelt miatt került a végére

		$tetelids = $this->params->getArrayRequestParam('tetelid');
		foreach ($tetelids as $tetelid) {
			if (($this->params->getIntRequestParam('teteltermek_' . $tetelid) > 0)) {
				$oper = $this->params->getStringRequestParam('teteloper_' . $tetelid);
				$termek = $this->getEm()->getRepository('Entities\Termek')->find($this->params->getIntRequestParam('teteltermek_' . $tetelid));
				$termekvaltozat = $this->getEm()->getRepository('Entities\TermekValtozat')->find($this->params->getIntRequestParam('tetelvaltozat_' . $tetelid));
				if ($oper == 'add') {
					$tetel = new Bizonylattetel();
					$obj->addBizonylattetel($tetel);
					$tetel->setPersistentData();
					$tetel->setArvaltoztat(0);
					if ($termek) {
						$tetel->setTermek($termek);
					}
					if ($termekvaltozat) {
						$tetel->setTermekvaltozat($termekvaltozat);
					}
					$tetel->setMozgat();
					$tetel->setMennyiseg($this->params->getFloatRequestParam('tetelmennyiseg_' . $tetelid));
					$tetel->setNettoegysar($this->params->getFloatRequestParam('tetelnettoegysar_' . $tetelid));
					$tetel->setBruttoegysar($this->params->getFloatRequestParam('tetelbruttoegysar_' . $tetelid));
					$tetel->setNetto($this->params->getFloatRequestParam('tetelnetto_' . $tetelid));
					$tetel->setBrutto($this->params->getFloatRequestParam('tetelbrutto_' . $tetelid));
					$tetel->setAfaertek($tetel->getBrutto() - $tetel->getNetto());
					$tetel->setNettoegysarhuf($this->params->getFloatRequestParam('tetelnettoegysarhuf_' . $tetelid));
					$tetel->setBruttoegysarhuf($this->params->getFloatRequestParam('tetelbruttoegysarhuf_' . $tetelid));
					$tetel->setNettohuf($this->params->getFloatRequestParam('tetelnettohuf_' . $tetelid));
					$tetel->setBruttohuf($this->params->getFloatRequestParam('tetelbruttohuf_' . $tetelid));
					$tetel->setAfaertekhuf($tetel->getBruttohuf() - $tetel->getNettohuf());
					$tetel->setHatarido($this->params->getStringRequestParam('tetelhatarido_' . $tetelid));
//						$tetel->setArfolyam($this->params->getFloatRequestParam('arfolyam'));
					$this->getEm()->persist($tetel);
				}
				elseif ($oper == 'edit') {
					$tetel = $this->getEm()->getRepository('Entities\Bizonylattetel')->find($tetelid);
					if ($tetel) {
						$tetel->setPersistentData();
						$tetel->setMozgat();
						if ($termek) {
							$tetel->setTermek($termek);
						}
						if ($termekvaltozat) {
							$tetel->setTermekvaltozat($termekvaltozat);
						}
						$tetel->setMozgat();
						$tetel->setMennyiseg($this->params->getFloatRequestParam('tetelmennyiseg_' . $tetelid));
						$tetel->setNettoegysar($this->params->getFloatRequestParam('tetelnettoegysar_' . $tetelid));
						$tetel->setBruttoegysar($this->params->getFloatRequestParam('tetelbruttoegysar_' . $tetelid));
						$tetel->setNetto($this->params->getFloatRequestParam('tetelnetto_' . $tetelid));
						$tetel->setBrutto($this->params->getFloatRequestParam('tetelbrutto_' . $tetelid));
						$tetel->setAfaertek($tetel->getBrutto() - $tetel->getNetto());
						$tetel->setNettoegysarhuf($this->params->getFloatRequestParam('tetelnettoegysarhuf_' . $tetelid));
						$tetel->setBruttoegysarhuf($this->params->getFloatRequestParam('tetelbruttoegysarhuf_' . $tetelid));
						$tetel->setNettohuf($this->params->getFloatRequestParam('tetelnettohuf_' . $tetelid));
						$tetel->setBruttohuf($this->params->getFloatRequestParam('tetelbruttohuf_' . $tetelid));
						$tetel->setAfaertekhuf($tetel->getBruttohuf() - $tetel->getNettohuf());
						$tetel->setHatarido($this->params->getStringRequestParam('tetelhatarido_' . $tetelid));
//							$tetel->setArfolyam($this->params->getFloatRequestParam('arfolyam'));
						$this->getEm()->persist($tetel);
					}
				}
			}
		}
		return $obj;
	}

}