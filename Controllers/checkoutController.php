<?php

namespace Controllers;

use mkw\Store;

class checkoutController extends \mkwhelpers\MattableController {

	public function __construct($params) {
		$this->setEntityName('Entities\Kosar');
		parent::__construct($params);
	}

	public function getCheckout() {
		$view = Store::getTemplateFactory()->createMainView('checkout.tpl');
		Store::fillTemplate($view);
		$szm = new szallitasimodController($this->params);
		$szlist = $szm->getSelectList(null);
		$view->setVar('szallitasimodlist', $szlist);
		$view->setVar('showerror', Store::getMainSession()->loginerror);
		$view->setVar('showaszflink', Store::getRouter()->generate('showstatlappopup', false, array('lap' => 'aszf')));
		Store::getMainSession()->loginerror = false;

		$sorok = $this->getEm()->getRepository('Entities\Kosar')->getDataBySessionId(\Zend_Session::getId());
		$s = array();
		foreach ($sorok as $sor) {
			$s[] = $sor->toLista();
		}
		$view->setVar('tetellista', $s);
		$view->printTemplateResult();
	}

	public function getFizmodList() {
		$view = Store::getTemplateFactory()->createMainView('checkoutfizmodlist.tpl');
		$szm = new fizmodController($this->params);
		$szlist = $szm->getSelectList(null, $this->params->getIntRequestParam('szallitasimod'));
		$view->setVar('fizmodlist', $szlist);
		echo $view->getTemplateResult();
	}

	public function getTetelList() {
		$view = Store::getTemplateFactory()->createMainView('checkouttetellist.tpl');

		$sorok = $this->getEm()->getRepository('Entities\Kosar')->getDataBySessionId(\Zend_Session::getId());
		$s = array();
		foreach ($sorok as $sor) {
			$s[] = $sor->toLista();
		}
		$view->setVar('tetellista', $s);
		echo $view->getTemplateResult();
	}

	public function save() {
		$regkell = $this->params->getIntRequestParam('regkell');
		$vezeteknev = $this->params->getStringRequestParam('vezeteknev');
		$keresztnev = $this->params->getStringRequestParam('keresztnev');
		$telefon = $this->params->getStringRequestParam('telefon');
		$jelszo1 = $this->params->getStringRequestParam('jelszo1');
		$jelszo2 = $this->params->getStringRequestParam('jelszo2');
		$email = $this->params->getStringRequestParam('kapcsemail');
		$szamlanev = $this->params->getStringRequestParam('szamlanev');
		$szamlairszam = $this->params->getStringRequestParam('szamlairszam');
		$szamlavaros = $this->params->getStringRequestParam('szamlavaros');
		$szamlautca = $this->params->getStringRequestParam('szamlautca');
		$adoszam = $this->params->getStringRequestParam('adoszam');
		$szamlaeqszall = $this->params->getBoolRequestParam('szamlaeqszall');
		$szallnev = $this->params->getStringRequestParam('szallnev');
		$szallirszam = $this->params->getStringRequestParam('szallirszam');
		$szallvaros = $this->params->getStringRequestParam('szallvaros');
		$szallutca = $this->params->getStringRequestParam('szallutca');
		$szallitasimod = $this->params->getIntRequestParam('szallitasimod');
		$fizetesimod = $this->params->getIntRequestParam('fizetesimod');
		$webshopmessage = $this->params->getStringRequestParam('webshopmessage');
		$couriermessage = $this->params->getStringRequestParam('couriermessage');
		$aszfready = $this->params->getBoolRequestParam('aszfready');
		$szamlasave = $this->params->getBoolRequestParam('szamlasave');
		$szallsave = $this->params->getBoolRequestParam('szallsave');

		$ok = ($vezeteknev && $keresztnev && $telefon &&
				$szamlairszam && $szamlavaros && $szamlautca &&
				(!$szamlaeqszall ? $szallirszam : true) &&
				(!$szamlaeqszall ? $szallvaros : true) &&
				(!$szamlaeqszall ? $szallutca : true) &&
				$szallitasimod > 0 &&
				$fizetesimod > 0 &&
				$aszfready
				);
		switch ($regkell) {
			case 1: // vendég
				$ok = $ok && $email;
				break;
			case 2: // regisztráció
				$ok = $ok && $jelszo1 && $jelszo2 && ($jelszo1 === $jelszo2) && $email;
				break;
			default: // be van jelentkezve elvileg
				break;
		}

		$kosartetelek = $this->getEm()->getRepository('Entities\Kosar')->getDataBySessionId(\Zend_Session::getId());
		$ok = $ok && count($kosartetelek)>0;

		if ($ok) {
			$pc = new \Controllers\partnerController($this->params);
			switch ($regkell) {
				case 1: // vendég
					$partner = $pc->saveRegistrationData($vezeteknev, $keresztnev, $email, $jelszo1, true);
					$szamlasave = true;
					$szallsave = true;
					break;
				case 2: // regisztráció
					$partner = $pc->saveRegistrationData($vezeteknev, $keresztnev, $email, $jelszo1);
					$pc->login($email, $jelszo1);
					break;
				default: // be van jelentkezve
					$partner = $pc->getLoggedInUser();
					break;
			}
			if ($szamlasave) {
				$partner->setNev($szamlanev);
				$partner->setIrszam($szamlairszam);
				$partner->setVaros($szamlavaros);
				$partner->setUtca($szamlautca);
			}
			if ($szallsave) {
				if ($szamlaeqszall) {
					$partner->setSzallnev($szamlanev);
					$partner->setSzallirszam($szamlairszam);
					$partner->setSzallvaros($szamlavaros);
					$partner->setSzallutca($szamlautca);
				}
				else {
					$partner->setSzallnev($szallnev);
					$partner->setSzallirszam($szallirszam);
					$partner->setSzallvaros($szallvaros);
					$partner->setSzallutca($szallutca);
				}
			}
			if ($szallsave || $szamlasave) {
				$this->getEm()->persist($partner);
			}
			$biztipus = $this->getEm()->getRepository('Entities\Bizonylattipus')->find('megrendeles');
			$megrendfej = new \Entities\Bizonylatfej();
			$megrendfej->setBizonylattipus($biztipus);
			$megrendfej->setKelt('');
			$megrendfej->setTeljesites('');
			$megrendfej->setEsedekesseg('');
			$megrendfej->setHatarido('');
			$megrendfej->setArfolyam(1);
			$megrendfej->setPartner($partner);
			$megrendfej->setPartnernev($szamlanev);
			$megrendfej->setPartnerkeresztnev($keresztnev);
			$megrendfej->setPartnervezeteknev($vezeteknev);
			$megrendfej->setPartneradoszam($adoszam);
			$megrendfej->setPartnerirszam($szamlairszam);
			$megrendfej->setPartnervaros($szamlavaros);
			$megrendfej->setPartnerutca($szamlautca);
			$megrendfej->setFizmod($this->getEm()->getRepository('Entities\Fizmod')->find($fizetesimod));
			$megrendfej->setSzallitasimod($this->getEm()->getRepository('Entities\Szallitasimod')->find($szallitasimod));
			$valutanemid = store::getParameter(\mkw\consts::Valutanem);
			$valutanem = $this->getEm()->getRepository('Entities\Valutanem')->find($valutanemid);
			$megrendfej->setValutanem($valutanem);
			$raktarid = store::getParameter(\mkw\consts::Raktar);
			$megrendfej->setRaktar($this->getEm()->getRepository('Entities\Raktar')->find($raktarid));
			$megrendfej->setBankszamla($this->getEm()->getRepository('Entities\Bankszamla')->getByValutanem($valutanem));
			if ($szamlaeqszall) {
				$megrendfej->setSzallnev($szamlanev);
				$megrendfej->setSzallirszam($szamlairszam);
				$megrendfej->setSzallvaros($szamlavaros);
				$megrendfej->setSzallutca($szamlautca);
			}
			else {
				$megrendfej->setSzallnev($szallnev);
				$megrendfej->setSzallirszam($szallirszam);
				$megrendfej->setSzallvaros($szallvaros);
				$megrendfej->setSzallutca($szallutca);
			}
			$megrendfej->setWebshopmessage($webshopmessage);
			$megrendfej->setCouriermessage($couriermessage);

			$megrendfej->generateId();

			$kosartetelek = $this->getEm()->getRepository('Entities\Kosar')->getDataBySessionId(\Zend_Session::getId());
			foreach ($kosartetelek as $kt) {
				$t = new \Entities\Bizonylattetel();
				$t->setBizonylatfej($megrendfej);
				$t->setPersistentData();
				$t->setTermek($kt->getTermek());
				$t->setMennyiseg($kt->getMennyiseg());
				$t->setNettoegysar($kt->getNettoegysar());
				$t->setBruttoegysar($kt->getBruttoegysar());
				$t->setNettoegysarhuf($kt->getNettoegysar());
				$t->setBruttoegysarhuf($kt->getBruttoegysar());
				$t->calc();
				$this->getEm()->persist($t);
			}
			$this->getEm()->persist($megrendfej);
			$this->getEm()->flush();
			Store::getMainSession()->lastmegrendeles = $megrendfej->getId();
			Header('Location: ' . Store::getRouter()->generate('checkoutkoszonjuk'));
		}
		else {
			echo 'error van';
		}
	}

	public function thanks() {
		$view = Store::getTemplateFactory()->createMainView('checkoutkoszonjuk.tpl');
		Store::fillTemplate($view);
		$view->setVar('megrendelesszam', Store::getMainSession()->lastmegrendeles);
		Store::getMainSession()->lastmegrendeles = '';

		$view->printTemplateResult();
	}
}