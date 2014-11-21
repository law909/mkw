<?php

namespace Controllers;

use mkw\Store;

class checkoutController extends \mkwhelpers\MattableController {

	public function __construct($params) {
		$this->setEntityName('Entities\Kosar');
		parent::__construct($params);
	}

	public function getCheckout() {
        Store::writelog(\Zend_Session::getId() . ' getCheckout: checkout kezdődik', 'checkoutlog.log');
		$view = Store::getTemplateFactory()->createMainView('checkout.tpl');
		Store::fillTemplate($view);
		$szm = new szallitasimodController($this->params);
		$szlist = $szm->getSelectList(null);
		$view->setVar('szallitasimodlist', $szlist);
		$view->setVar('showerror', Store::getMainSession()->loginerror);
		$view->setVar('showaszflink', Store::getRouter()->generate('showstatlappopup', false, array('lap' => 'aszf')));
		Store::getMainSession()->loginerror = false;

/*        $this->getRepo('Entities\Kosar')->createSzallitasiKtg($this->params->getIntRequestParam('szallitasimod'));
		$sorok = $this->getRepo('Entities\Kosar')->getDataBySessionId(\Zend_Session::getId());
		$s = array();
		foreach ($sorok as $sor) {
			$s[] = $sor->toLista();
		}
		$view->setVar('tetellista', $s);
*/		$view->printTemplateResult(false);
        Store::writelog(\Zend_Session::getId() . ' getCheckout: checkout kiküldve', 'checkoutlog.log');
	}

	public function getFizmodList() {
		$view = Store::getTemplateFactory()->createMainView('checkoutfizmodlist.tpl');
		$szm = new fizmodController($this->params);
		$szlist = $szm->getSelectList(null, $this->params->getIntRequestParam('szallitasimod'));
		$view->setVar('fizmodlist', $szlist);
		echo json_encode(array(
            'html' => $view->getTemplateResult()
        ));
	}

	public function getTetelList() {
        $this->getRepo('Entities\Kosar')->createSzallitasiKtg($this->params->getIntRequestParam('szallitasimod'));
		$view = Store::getTemplateFactory()->createMainView('checkouttetellist.tpl');

        $kr = $this->getRepo('Entities\Kosar');
		$sorok = $kr->getDataBySessionId(\Zend_Session::getId());
		$s = array();
		foreach ($sorok as $sor) {
			$s[] = $sor->toLista();
		}
		$view->setVar('tetellista', $s);
		echo json_encode(array(
            'html' => $view->getTemplateResult(),
            'hash' => $kr->getHash()
        ));
	}

	public function save() {

        $errorlogtext = array();

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
		$akciohirlevel = $this->params->getBoolRequestParam('akciohirlevel');
		$ujdonsaghirlevel = $this->params->getBoolRequestParam('ujdonsaghirlevel');

		$ok = ($vezeteknev && $keresztnev && $telefon &&
				$szallirszam && $szallvaros && $szallutca &&
				(!$szamlaeqszall ? $szamlairszam : true) &&
				(!$szamlaeqszall ? $szamlavaros : true) &&
				(!$szamlaeqszall ? $szamlautca : true) &&
				$szallitasimod > 0 &&
				$fizetesimod > 0 &&
				$aszfready
				);

        if (!$ok) {
            $errorlogtext[] = '1alapadat';
        }
        switch ($regkell) {
			case 1: // vendég
				$ok = $ok && $email;
                if (!$email) {
                    $errorlogtext[] = '2vendegemail';
                }
				break;
			case 2: // regisztráció
				$ok = $ok && $jelszo1 && $jelszo2 && ($jelszo1 === $jelszo2) && $email;
                if (!$jelszo1 || !$jelszo2 || ($jelszo1 !== $jelszo2) || !$email) {
                    $errorlogtext[] = '3regemailjelszo';
                }
				break;
			default: // be van jelentkezve elvileg
				break;
		}

		$kosartetelek = $this->getRepo('Entities\Kosar')->getDataBySessionId(\Zend_Session::getId());
		$ok = $ok && count($kosartetelek)>0;
        if (!count($kosartetelek)) {
            $errorlogtext[] = '4ureskosar';
        }

		if ($ok) {
			switch ($regkell) {
				case 1: // vendég
        			$pc = new \Controllers\partnerController($this->params);
					$partner = $pc->saveRegistrationData($vezeteknev, $keresztnev, $email, $jelszo1, true);
					$szamlasave = true;
					$szallsave = true;
					break;
				case 2: // regisztráció
        			$pc = new \Controllers\partnerController($this->params);
					$partner = $pc->saveRegistrationData($vezeteknev, $keresztnev, $email, $jelszo1);
					$pc->login($email, $jelszo1);
					break;
				default: // be van jelentkezve
					$partner = $this->getRepo('Entities\Partner')->getLoggedInUser();
					break;
			}
            if ($szallsave) {
                $partner->setSzallnev($szallnev);
                $partner->setSzallirszam($szallirszam);
                $partner->setSzallvaros($szallvaros);
                $partner->setSzallutca($szallutca);
            }
			if ($szamlasave) {
				if ($szamlaeqszall) {
                    $partner->setNev($szallnev);
                    $partner->setIrszam($szallirszam);
                    $partner->setVaros($szallvaros);
                    $partner->setUtca($szallutca);
				}
				else {
                    $partner->setNev($szamlanev);
                    $partner->setIrszam($szamlairszam);
                    $partner->setVaros($szamlavaros);
                    $partner->setUtca($szamlautca);
				}
			}
			$partner->setTelefon($telefon);
			$partner->setAdoszam($adoszam);
			$partner->setAkcioshirlevelkell($akciohirlevel);
			$partner->setUjdonsaghirlevelkell($ujdonsaghirlevel);
			$this->getEm()->persist($partner);

			$biztipus = $this->getRepo('Entities\Bizonylattipus')->find('megrendeles');
			$megrendfej = new \Entities\Bizonylatfej();
            $megrendfej->setPersistentData();
            $megrendfej->setIp($_SERVER['REMOTE_ADDR']);
            $megrendfej->setReferrer(\mkw\Store::getMainSession()->referrer);
			$megrendfej->setBizonylattipus($biztipus);
			$megrendfej->setKelt('');
			$megrendfej->setTeljesites('');
			$megrendfej->setEsedekesseg('');
			$megrendfej->setHatarido('');
			$megrendfej->setArfolyam(1);
			$megrendfej->setPartner($partner);
            /*
			$megrendfej->setPartnernev($szamlanev);
			$megrendfej->setPartnerkeresztnev($keresztnev);
			$megrendfej->setPartnervezeteknev($vezeteknev);
			$megrendfej->setPartneradoszam($adoszam);
			$megrendfej->setPartnerirszam($szamlairszam);
			$megrendfej->setPartnervaros($szamlavaros);
			$megrendfej->setPartnerutca($szamlautca);
			$megrendfej->setPartnertelefon($telefon);
			$megrendfej->setPartneremail($email);
             */
			$megrendfej->setFizmod($this->getEm()->getRepository('Entities\Fizmod')->find($fizetesimod));
			$megrendfej->setSzallitasimod($this->getEm()->getRepository('Entities\Szallitasimod')->find($szallitasimod));
			$valutanemid = store::getParameter(\mkw\consts::Valutanem);
			$valutanem = $this->getRepo('Entities\Valutanem')->find($valutanemid);
			$megrendfej->setValutanem($valutanem);
			$raktarid = store::getParameter(\mkw\consts::Raktar);
			$megrendfej->setRaktar($this->getRepo('Entities\Raktar')->find($raktarid));
			$megrendfej->setBankszamla($valutanem->getBankszamla());
            /*
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
             */
			$megrendfej->setWebshopmessage($webshopmessage);
			$megrendfej->setCouriermessage($couriermessage);
            $bizstatusz = $this->getRepo('Entities\Bizonylatstatusz')->find(Store::getParameter(\mkw\consts::BizonylatStatuszFuggoben));
            $megrendfej->setBizonylatstatusz($bizstatusz);

			$megrendfej->generateId();

            $lasttermeknevek = array();
            $lasttermekids = array();
//			$kosartetelek = $this->getRepo('Entities\Kosar')->getDataBySessionId(\Zend_Session::getId());
			foreach ($kosartetelek as $kt) {
				$t = new \Entities\Bizonylattetel();
				$t->setBizonylatfej($megrendfej);
				$t->setPersistentData();
				$t->setTermek($kt->getTermek());
                $t->setTermekvaltozat($kt->getTermekvaltozat());
				$t->setMennyiseg($kt->getMennyiseg());
				$t->setNettoegysar($kt->getNettoegysar());
				$t->setBruttoegysar($kt->getBruttoegysar());
				$t->setNettoegysarhuf($kt->getNettoegysar());
				$t->setBruttoegysarhuf($kt->getBruttoegysar());
				$t->calc();
                $lasttermeknevek[] = $t->getTermeknev();
                $lasttermekids[] = $t->getTermekId();
				$this->getEm()->persist($t);
			}
			$this->getEm()->persist($megrendfej);
			$this->getEm()->flush();


            if ($bizstatusz) {
                $megrendfej->sendStatuszEmail($bizstatusz->getEmailtemplate());
            }

			Store::getMainSession()->lastmegrendeles = $megrendfej->getId();
            Store::getMainSession()->lastemail = $email;
            Store::getMainSession()->lasttermeknevek = $lasttermeknevek;
            Store::getMainSession()->lasttermekids = $lasttermekids;
			$kc = new kosarController($this->params);
			$kc->clear();
			Header('Location: ' . Store::getRouter()->generate('checkoutkoszonjuk'));
		}
		else {
			echo 'error';
            Store::writelog(\Zend_Session::getId() . ' ERROR ' . implode(' ', $errorlogtext), 'checkoutlog.log');
		}
	}

	public function thanks() {
        $mrszam = Store::getMainSession()->lastmegrendeles;
		$view = Store::getTemplateFactory()->createMainView('checkoutkoszonjuk.tpl');
		Store::fillTemplate($view);
        $mrszam = Store::getMainSession()->lastmegrendeles;
		$view->setVar('megrendelesszam', $mrszam);
//itt kell hozza vasarolt termeket keresni session->lasttermekids-re

        $aktsapikey = Store::getParameter(\mkw\consts::AKTrustedShopApiKey);
        $email = Store::getMainSession()->lastemail;

        if ($aktsapikey && $email) {
            require_once 'busvendor/AKTrustedShop/TrustedShop.php';

            $ts = new \TrustedShop($aktsapikey);
            $ts->SetEmail($email);

            $ltn = Store::getMainSession()->lasttermeknevek;
            if ($ltn) {
                foreach($ltn as $l) {
                    $ts->AddProduct($l);
                }
            }

            ob_start();
            $ts->Send();
            $tsret = ob_get_clean();

            if ($tsret) {
                $view->setVar('AKTrustedShopScript', $tsret);
            }
        }
		Store::getMainSession()->lastmegrendeles = '';
        Store::getMainSession()->lastemail = '';
        Store::getMainSession()->lasttermeknevek = array();
        Store::getMainSession()->lasttermekids = array();

		$view->printTemplateResult(false);
	}
}