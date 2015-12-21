<?php

namespace Controllers;

use mkw\Store;

class checkoutController extends \mkwhelpers\MattableController {

	public function __construct($params) {
		$this->setEntityName('Entities\Kosar');
		parent::__construct($params);
	}

    private function vv($a, $b) {
        if ($a) {
            return $a;
        }
        return $b;
    }

	public function getCheckout() {
        $p = Store::getMainSession()->params;
        if (!$p) {
            $p = new \mkwhelpers\ParameterHandler(array());
        }
        Store::getMainSession()->params = false;

		$view = Store::getTemplateFactory()->createMainView('checkout.tpl');
		Store::fillTemplate($view, false);

		$szm = new szallitasimodController($this->params);
		$szlist = $szm->getSelectList(null);

        $u = Store::getLoggedInUser();
        if ($u) {
            $user['nev'] = $u->getNev();
            $user['email'] = $u->getEmail();
            $user['vezeteknev'] = $u->getVezeteknev();
            $user['keresztnev'] = $u->getKeresztnev();
            $user['telefon'] = $u->getTelefon();
            $user['irszam'] = $u->getIrszam();
            $user['varos'] = $u->getVaros();
            $user['utca'] = $u->getUtca();
            $user['adoszam'] = $u->getAdoszam();
            $user['szallnev'] = $u->getSzallnev();
            $user['szallirszam'] = $u->getSzallirszam();
            $user['szallvaros'] = $u->getSzallvaros();
            $user['szallutca'] = $u->getSzallutca();
            $user['szalladategyezik'] = !$u->getNev() &&
                    !$u->getIrszam() &&
                    !$u->getVaros() &&
                    !$u->getUtca() &&
                    !$u->getNev();
            $user['akcioshirlevelkell'] = $u->getAkcioshirlevelkell();
            $user['ujdonsaghirlevelkell'] = $u->getUjdonsaghirlevelkell();
            $view->setVar('partnerszallitasimod', $u->getSzallitasimodNev());
            $view->setVar('partnerszallitasimodid', $u->getSzallitasimodId());
            $view->setVar('partnerfizetesimod', $u->getFizmodNev());
        }
        else {
            $user['nev'] = '';
            $user['email'] = '';
            $user['vezeteknev'] = '';
            $user['keresztnev'] = '';
            $user['telefon'] = '';
            $user['irszam'] = '';
            $user['varos'] = '';
            $user['utca'] = '';
            $user['adoszam'] = '';
            $user['szallnev'] = '';
            $user['szallirszam'] = '';
            $user['szallvaros'] = '';
            $user['szallutca'] = '';
            $user['szalladategyezik'] = true;
            $user['akcioshirlevelkell'] = false;
            $user['ujdonsaghirlevelkell'] = false;
            $view->setVar('partnerszallitasimod', '');
            $view->setVar('partnerfizetesimod', '');
        }

		$view->setVar('szallitasimodlist', $szlist);
		$view->setVar('showerror', Store::getMainSession()->loginerror);
        $view->setVar('checkouterrors', Store::getMainSession()->checkoutErrors);
		$view->setVar('showaszflink', Store::getRouter()->generate('showstatlappopup', false, array('lap' => 'aszf')));
        $view->setVar('regkell', $p->getIntRequestParam('regkell', 2));
        $view->setVar('vezeteknev', $this->vv($p->getStringRequestParam('vezeteknev'), $user['vezeteknev']));
        $view->setVar('keresztnev', $this->vv($p->getStringRequestParam('keresztnev'), $user['keresztnev']));
        $view->setVar('telefon', $this->vv($p->getStringRequestParam('telefon'), $user['telefon']));
        $view->setVar('jelszo1', $p->getStringRequestParam('jelszo1'));
        $view->setVar('jelszo2', $p->getStringRequestParam('jelszo2'));
        $view->setVar('email', $this->vv($p->getStringRequestParam('kapcsemail'), $user['email']));
        $view->setVar('szamlanev', $this->vv($p->getStringRequestParam('szamlanev'), $user['nev']));
        $view->setVar('szamlairszam', $this->vv($p->getStringRequestParam('szamlairszam'), $user['irszam']));
        $view->setVar('szamlavaros', $this->vv($p->getStringRequestParam('szamlavaros'), $user['varos']));
        $view->setVar('szamlautca', $this->vv($p->getStringRequestParam('szamlautca'), $user['utca']));
        $view->setVar('adoszam', $this->vv($p->getStringRequestParam('adoszam'), $user['adoszam']));
        $view->setVar('szamlaeqszall', $this->vv($p->getBoolRequestParam('szamlaeqszall'), $user['szalladategyezik']));
        $view->setVar('szallnev', $this->vv($p->getStringRequestParam('szallnev'), $user['szallnev']));
        $view->setVar('szallirszam', $this->vv($p->getStringRequestParam('szallirszam'), $user['szallirszam']));
        $view->setVar('szallvaros', $this->vv($p->getStringRequestParam('szallvaros'), $user['szallvaros']));
        $view->setVar('szallutca', $this->vv($p->getStringRequestParam('szallutca'), $user['szallutca']));
        $view->setVar('szallitasimod', $p->getIntRequestParam('szallitasimod'));
        $view->setVar('fizetesimod', $p->getIntRequestParam('fizetesimod'));
        $view->setVar('webshopmessage', $p->getStringRequestParam('webshopmessage'));
        $view->setVar('couriermessage', $p->getStringRequestParam('couriermessage'));
        $view->setVar('aszfready', $p->getBoolRequestParam('aszfready'));
        $view->setVar('akciohirlevel', $this->vv($p->getBoolRequestParam('akciohirlevel'), $user['akcioshirlevelkell']));
        $view->setVar('ujdonsaghirlevel', $this->vv($p->getBoolRequestParam('ujdonsaghirlevel'), $user['ujdonsaghirlevelkell']));
		Store::getMainSession()->loginerror = false;
        Store::getMainSession()->checkoutErrors = false;
		$view->printTemplateResult(false);
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
        $partner = Store::getLoggedInUser();
        if ($partner) {
            $view->setVar('valutanem', $partner->getValutanemnev());
        }
		foreach ($sorok as $sor) {
			$s[] = $sor->toLista($partner);
		}
		$view->setVar('tetellista', $s);
		echo json_encode(array(
            'html' => $view->getTemplateResult(),
            'hash' => $kr->getHash()
        ));
	}

	public function save() {

        switch (Store::getTheme()) {
            case 'mkwcansas':
                $errorlogtext = array();
                $errors = array();

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
                $foxpostterminalid = $this->params->getIntRequestParam('foxpostterminal');

                $ok = ($vezeteknev && $keresztnev && $telefon &&
                        $szallirszam && $szallvaros && $szallutca && $szallnev &&
                        (!$szamlaeqszall ? $szamlanev : true) &&
                        (!$szamlaeqszall ? $szamlairszam : true) &&
                        (!$szamlaeqszall ? $szamlavaros : true) &&
                        (!$szamlaeqszall ? $szamlautca : true) &&
                        $szallitasimod > 0 &&
                        $fizetesimod > 0 &&
                        $aszfready
                        );

                if (Store::isFoxpostSzallitasimod($szallitasimod)) {
                    $ok = $ok && $foxpostterminalid;
                }

                if (!$ok) {
                    $errorlogtext[] = '1alapadat';
                    if (!$vezeteknev) {
                        $errors[] = 'Nem adta meg a vezetéknevét.';
                    }
                    if (!$keresztnev) {
                        $errors[] = 'Nem adta meg a keresztnevét.';
                    }
                    if (!$telefon) {
                        $errors[] = 'Nem adta meg a telefonszámát.';
                    }
                    if (!$szallirszam) {
                        $errors[] = 'Nem adta meg a szállítási ir.számot.';
                    }
                    if (!$szallvaros) {
                        $errors[] = 'Nem adta meg a szállítási várost.';
                    }
                    if (!$szallutca) {
                        $errors[] = 'Nem adta meg a szállítási utcát.';
                    }
                    if (!$szallnev) {
                        $errors[] = 'Nem adta meg a szállítási nevet.';
                    }
                    if (!$szamlaeqszall) {
                        if (!$szamlanev) {
                            $errors[] = 'Nem adta meg a számlázási nevet.';
                        }
                        if (!$szamlairszam) {
                            $errors[] = 'Nem adta meg a számlázási ir.számot.';
                        }
                        if (!$szamlavaros) {
                            $errors[] = 'Nem adta meg a számlázási várost.';
                        }
                        if (!$szamlautca) {
                            $errors[] = 'Nem adta meg a számlázási utcát.';
                        }
                    }
                    if (!$szallitasimod) {
                        $errors[] = 'Nem adta meg a szállítási módot.';
                    }
                    if (!$fizetesimod) {
                        $errors[] = 'Nem adta meg a fizetési módot.';
                    }
                    if (!$aszfready) {
                        $errors[] = 'Nem fogadta el az ált.szerződési feltételeket.';
                    }
                }
                switch ($regkell) {
                    case 1: // vendég
                        $ok = $ok && $email;
                        if (!$email) {
                            $errorlogtext[] = '2vendegemail';
                            $errors[] = 'Nem adott meg emailcímet.';
                        }
                        break;
                    case 2: // regisztráció
                        $ok = $ok && $jelszo1 && $jelszo2 && ($jelszo1 === $jelszo2) && $email;
                        if (!$jelszo1 || !$jelszo2 || ($jelszo1 !== $jelszo2)) {
                            $errorlogtext[] = '3regjelszo';
                            $errors[] = 'Nem adott meg jelszót, vagy a két jelszó nem egyezik.';
                        }
                        if (!$email) {
                            $errorlogtext[] = '3regemail';
                            $errors[] = 'Nem adott meg emailcímet.';
                        }
                        break;
                    default: // be van jelentkezve elvileg
                        break;
                }

                $kosartetelek = $this->getRepo('Entities\Kosar')->getDataBySessionId(\Zend_Session::getId());
                $ok = $ok && count($kosartetelek)>0;
                if (!count($kosartetelek)) {
                    $errorlogtext[] = '4ureskosar';
                    $errors[] = 'Üres a kosara.';
                }

                if ($ok) {

                    switch ($regkell) {
                        case 1: // vendég
                            $pc = new \Controllers\partnerController($this->params);
                            $partner = $pc->saveRegistrationData(true);
                            $szamlasave = true;
                            $szallsave = true;
                            break;
                        case 2: // regisztráció
                            $pc = new \Controllers\partnerController($this->params);
                            $partner = $pc->saveRegistrationData(false);
                            $pc->login($email, $jelszo1);
                            break;
                        default: // be van jelentkezve
                            $partner = $this->getRepo('Entities\Partner')->getLoggedInUser();
                            break;
                    }
                    $partner->setSzallnev($szallnev);
                    $partner->setSzallirszam($szallirszam);
                    $partner->setSzallvaros($szallvaros);
                    $partner->setSzallutca($szallutca);
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
                    $partner->setTelefon($telefon);
                    $partner->setAdoszam($adoszam);
                    $partner->setAkcioshirlevelkell($akciohirlevel);
                    $partner->setUjdonsaghirlevelkell($ujdonsaghirlevel);
                    $this->getEm()->persist($partner);

                    $biztipus = $this->getRepo('Entities\Bizonylattipus')->find('megrendeles');
                    $megrendfej = new \Entities\Bizonylatfej();
                    $megrendfej->setPersistentData();
                    switch ($regkell) {
                        case 1:
                            $megrendfej->setRegmode(1);
                            $regmodenev = 'vendég';
                            break;
                        case 2:
                            $megrendfej->setRegmode(2);
                            $regmodenev = 'regisztrált';
                            break;
                        default:
                            $megrendfej->setRegmode(3);
                            $regmodenev = 'bejelentkezett';
                            break;
                    }
                    $megrendfej->setIp($_SERVER['REMOTE_ADDR']);
                    $megrendfej->setReferrer(\mkw\Store::getMainSession()->referrer);
                    $megrendfej->setBizonylattipus($biztipus);
                    $megrendfej->setKelt('');
                    $megrendfej->setTeljesites('');
                    $megrendfej->setEsedekesseg('');
                    $megrendfej->setHatarido('');
                    $megrendfej->setArfolyam(1);
                    $megrendfej->setPartner($partner);
                    $megrendfej->setFizmod($this->getEm()->getRepository('Entities\Fizmod')->find($fizetesimod));
                    $megrendfej->setSzallitasimod($this->getEm()->getRepository('Entities\Szallitasimod')->find($szallitasimod));
                    $valutanemid = store::getParameter(\mkw\consts::Valutanem);
                    $valutanem = $this->getRepo('Entities\Valutanem')->find($valutanemid);
                    $megrendfej->setValutanem($valutanem);
                    $raktarid = store::getParameter(\mkw\consts::Raktar);
                    $megrendfej->setRaktar($this->getRepo('Entities\Raktar')->find($raktarid));
                    $megrendfej->setBankszamla($valutanem->getBankszamla());
                    $megrendfej->setWebshopmessage($webshopmessage);
                    $megrendfej->setCouriermessage($couriermessage);
                    $bizstatusz = $this->getRepo('Entities\Bizonylatstatusz')->find(Store::getParameter(\mkw\consts::BizonylatStatuszFuggoben));
                    $megrendfej->setBizonylatstatusz($bizstatusz);
                    if (Store::isFoxpostSzallitasimod($szallitasimod)) {
                        $fpc = $this->getRepo('Entities\FoxpostTerminal')->find($foxpostterminalid);
                        if ($fpc) {
                            $megrendfej->setFoxpostterminal($fpc);
                        }
                    }

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
                        $t->setEnettoegysarhuf($kt->getEnettoegysar());
                        $t->setEbruttoegysarhuf($kt->getEbruttoegysar());
                        $t->setKedvezmeny($kt->getKedvezmeny());
                        $t->calc();
                        $lasttermeknevek[] = $t->getTermeknev();
                        $lasttermekids[] = $t->getTermekId();
                        $this->getEm()->persist($t);
                    }
                    $this->getEm()->persist($megrendfej);
                    $this->getEm()->flush();

                    \mkw\Store::writelog($megrendfej->getId() . ' : ' . $regmodenev . ' : ' . $partner->getNev() . ' : ' . $email . ' : ' . $partner->getId(), 'checkout.log');

                    Store::getMainSession()->lastmegrendeles = $megrendfej->getId();
                    Store::getMainSession()->lastemail = $email;
                    Store::getMainSession()->lasttermeknevek = $lasttermeknevek;
                    Store::getMainSession()->lasttermekids = $lasttermekids;
                    Store::getMainSession()->lastszallmod = $szallitasimod;
                    Store::getMainSession()->lastfizmod = $fizetesimod;
                    $kc = new kosarController($this->params);
                    $kc->clear();

                    if ($bizstatusz) {
                        $megrendfej->sendStatuszEmail($bizstatusz->getEmailtemplate());
                    }
                    if ($fizetesimod == Store::getParameter(\mkw\consts::OTPayFizmod)) {
                        Header('Location: ' . Store::getRouter()->generate('showcheckoutfizetes'));
                    }
                    else {
                        Header('Location: ' . Store::getRouter()->generate('checkoutkoszonjuk'));
                    }
                }
                else {
                    Store::getMainSession()->params = $this->params;
                    Store::getMainSession()->checkoutErrors = $errors;
                    Header('Location: ' . Store::getRouter()->generate('showcheckout'));
                }
                break;
            case 'superzone':
                $errorlogtext = array();
                $errors = array();

                $szamlanev = $this->params->getStringRequestParam('szamlanev');
                $szamlairszam = $this->params->getStringRequestParam('szamlairszam');
                $szamlavaros = $this->params->getStringRequestParam('szamlavaros');
                $szamlautca = $this->params->getStringRequestParam('szamlautca');
                $szallnev = $this->params->getStringRequestParam('szallnev');
                $szallirszam = $this->params->getStringRequestParam('szallirszam');
                $szallvaros = $this->params->getStringRequestParam('szallvaros');
                $szallutca = $this->params->getStringRequestParam('szallutca');
                $szalleqszamla = $this->params->getBoolRequestParam('szalleqszamla');
                $webshopmessage = $this->params->getStringRequestParam('webshopmessage');

                if ($szalleqszamla) {
                    $szallnev = $szamlanev;
                    $szallirszam = $szamlairszam;
                    $szallvaros = $szamlavaros;
                    $szallutca = $szamlautca;
                }

                $ok = ($szallnev && $szallirszam && $szallvaros && $szallutca &&
                        $szamlanev && $szamlairszam && $szamlavaros && $szamlautca);

                if (!$ok) {
                    $errorlogtext[] = '1alapadat';
                    $errors[] = 'Nem adott meg egy kötelező adatot.';
                }

                $kosartetelek = $this->getRepo('Entities\Kosar')->getDataBySessionId(\Zend_Session::getId());
                $ok = $ok && count($kosartetelek)>0;
                if (!count($kosartetelek)) {
                    $errorlogtext[] = '4ureskosar';
                    $errors[] = 'Üres a kosara.';
                }

                if ($ok) {

                    $partner = Store::getLoggedInUser();
                    $nullasafa = $this->getRepo('Entities\Afa')->find(Store::getParameter(\mkw\consts::NullasAfa));
                    $biztetelcontroller = new bizonylattetelController($this->params);
                    $valutanem = $partner->getValutanem();

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

                    $megrendfej->setPartner($partner);

                    $megrendfej->setPartnernev($szamlanev);
                    $megrendfej->setPartnerirszam($szamlairszam);
                    $megrendfej->setPartnervaros($szamlavaros);
                    $megrendfej->setPartnerutca($szamlautca);
                    $megrendfej->setSzallnev($szallnev);
                    $megrendfej->setSzallirszam($szallirszam);
                    $megrendfej->setSzallvaros($szallvaros);
                    $megrendfej->setSzallutca($szallutca);

                    $megrendfej->setFizmod($partner->getFizmod());
                    $megrendfej->setSzallitasimod($partner->getSzallitasimod());
                    $megrendfej->setValutanem($valutanem);
                    $megrendfej->setWebshopmessage($webshopmessage);
                    $arf = $this->getEm()->getRepository('Entities\Arfolyam')->getActualArfolyam($valutanem, $megrendfej->getTeljesites());
                    $megrendfej->setArfolyam($arf->getArfolyam());
                    $raktarid = store::getParameter(\mkw\consts::Raktar);
                    $megrendfej->setRaktar($this->getRepo('Entities\Raktar')->find($raktarid));
                    if ($valutanem) {
                        $megrendfej->setBankszamla($valutanem->getBankszamla());
                    }
                    $bizstatusz = $this->getRepo('Entities\Bizonylatstatusz')->find(Store::getParameter(\mkw\consts::BizonylatStatuszFuggoben));
                    $megrendfej->setBizonylatstatusz($bizstatusz);

                    $megrendfej->generateId();

                    $lasttermeknevek = array();
                    $lasttermekids = array();
                    foreach ($kosartetelek as $kt) {
                        $t = new \Entities\Bizonylattetel();
                        $t->setBizonylatfej($megrendfej);
                        $t->setPersistentData();
                        $t->setTermek($kt->getTermek());
                        $t->setTermekvaltozat($kt->getTermekvaltozat());
                        $t->setMennyiseg($kt->getMennyiseg());
                        if ($partner->getSzamlatipus()) {
                            if ($nullasafa) {
                                $t->setAfa($nullasafa);
                            }
                            $t->setNettoegysar($kt->getNettoegysar());
                            $t->setEnettoegysar($kt->getEnettoegysar());
                            $t->setEbruttoegysar($kt->getEnettoegysar());
                        }
                        else {
                            $t->setNettoegysar($kt->getNettoegysar());
                            $t->setBruttoegysar($kt->getBruttoegysar());
                            $t->setEnettoegysar($kt->getEnettoegysar());
                            $t->setEbruttoegysar($kt->getEbruttoegysar());
                        }
                        $t->setKedvezmeny($kt->getKedvezmeny());
                        $arak = $biztetelcontroller->calcAr(
                            $t->getAfaId(), $t->getArfolyam(), $t->getNettoegysar(), $t->getEnettoegysar(), $t->getMennyiseg()
                        );
                        $t->setNettoegysarhuf($arak['nettoegysarhuf']);
                        $t->setBruttoegysarhuf($arak['bruttoegysarhuf']);
                        $t->calc();
                        $lasttermeknevek[] = $t->getTermeknev();
                        $lasttermekids[] = $t->getTermekId();
                        $this->getEm()->persist($t);
                    }
                    $this->getEm()->persist($megrendfej);
                    $this->getEm()->flush();

                    Store::getMainSession()->lastmegrendeles = $megrendfej->getId();
                    Store::getMainSession()->lastemail = $email;
                    Store::getMainSession()->lasttermeknevek = $lasttermeknevek;
                    Store::getMainSession()->lasttermekids = $lasttermekids;
                    Store::getMainSession()->lastszallmod = $szallitasimod;
                    Store::getMainSession()->lastfizmod = $fizetesimod;
                    $kc = new kosarController($this->params);
                    $kc->clear();

                    if ($bizstatusz) {
                        $megrendfej->sendStatuszEmail($bizstatusz->getEmailtemplate());
                    }
                    Header('Location: ' . Store::getRouter()->generate('checkoutkoszonjuk'));
                }
                else {
                    Store::getMainSession()->params = $this->params;
                    Store::getMainSession()->checkoutErrors = $errors;
                    Header('Location: ' . Store::getRouter()->generate('showcheckout'));
                }

        }
	}

	public function showCheckoutFizetes() {
        $mrszam = Store::getMainSession()->lastmegrendeles;
        $szallmod = Store::getMainSession()->lastszallmod;
        $fizmod = Store::getMainSession()->lastfizmod;
        $fizmodnev = '';
        $f = $this->getRepo('Entities\Fizmod')->find($fizmod);
        if ($f) {
            $fizmodnev = $f->getNev();
        }

        $fizetendo = 0;
        $mr = $this->getRepo('Entities\Bizonylatfej')->find($mrszam);
        if ($mr) {
            $fizetendo = $mr->getFizetendo();
        }

        $excfm = array();
        $ooo = Store::getParameter(\mkw\consts::OTPayFizmod);
        if ($ooo) {
            $excfm[] = $ooo;
        }
        $ooo = Store::getParameter(\mkw\consts::MasterPassFizmod);
        if ($ooo) {
            $excfm[] = $ooo;
        }

		$view = Store::getTemplateFactory()->createMainView('checkoutfizmodlist.tpl');
		$fm = new fizmodController($this->params);
		$szlist = $fm->getSelectList($fizmod, $szallmod, $excfm);
		$view->setVar('fizmodlist', $szlist);
        $fml = $view->getTemplateResult();

        $view = Store::getTemplateFactory()->createMainView('checkoutfizetes.tpl');
		Store::fillTemplate($view);
        $view->setVar('fizetendo', $fizetendo);
		$view->setVar('megrendelesszam', $mrszam);
        $view->setVar('fizmodlist', $fml);
        $view->setVar('fizmodnev', $fizmodnev);
        $view->setVar('checkouterrors',Store::getMainSession()->checkoutfizeteserrors);
		$view->printTemplateResult(false);
        Store::getMainSession()->checkoutfizeteserrors = false;
	}

    public function doCheckoutFizetes() {
        require_once('busvendor/OTPay/MerchTerm_umg_client.php');

        $error = false;
        Store::getMainSession()->fizetesdb = Store::getMainSession()->fizetesdb * 1 + 1;

        $mrszam = $this->params->getStringRequestParam('megrendelesszam');
        $mobilszam = preg_replace('/[^0-9]/','',$this->params->getStringRequestParam('mobilszam'));
        $fizazon = preg_replace('/[^0-9]/','',$this->params->getStringRequestParam('fizazon'));

        if ($mrszam) {
            $mr = $this->getRepo('Entities\Bizonylatfej')->find($mrszam);
            if ($mr) {
                $fizetendo = $mr->getFizetendo();
                if ($fizetendo != 0) {
                    if ($mobilszam) {
                        $clientId = new \ClientMsisdn();
                        $clientId->value = $mobilszam;
                        $mr->setOTPayMSISDN($mobilszam);
                    }
                    else {
                        if ($fizazon) {
                            $clientId = new \ClientMpid();
                            $clientId->value = $fizazon;
                            $mr->setOTPayMPID($fizazon);
                        }
                        else {
                            $error = 'Hiányzik a mobil szám vagy a fizetési azonosító';
                        }
                    }
                    if (!$error) {
                        $this->getEm()->persist($mr);
                        $this->getEm()->flush();

                        $timeout = new \TimeoutCategory();
                        $timeout->value = "mediumPeriod";
                        // Paraméterek
                        $mytrxid = $mr->getTrxId();
                        $request = array(
                            'merchTermId' => \MerchTerm_config::getConfig("merchTermId"),
                            'merchTrxId' => $mytrxid,
                            'clientId' => $clientId,
                            'timeout' => $timeout,
                            'amount' => $fizetendo,
                            'description' => 'Mindentkapni.hu vásárlás',
                            'isRepeated' => (Store::getMainSession()->fizetesdb > 1)
                        );

                        $client = null;

                        try {
                            $client = new \MerchTerm_umg_client();
                            $response = $client->PostImCreditInit($request);
                            if ($response->result == 0) {
                                $trxid = $response->bankTrxId;
                                $mr->setOTPayId($trxid);
                                $this->getEm()->persist($mr);
                                $this->getEm()->flush();

                                $imnotiffilter = new \ImNotifFilterBankTrxId();
                                $imnotiffilter->bankTrxId = $trxid;
                                $request = array(
                                    'merchTermId' => \MerchTerm_config::getConfig("merchTermId"),
                                    'imNotifFilter' => $imnotiffilter
                                );
                                $response = $client->GetImNotif($request);
                                if ($response->result == 0) {
                                    if (isset($response->ImNotifList)) {
                                        if (isset($response->ImNotifList->ImNotifReq)) {
                                            if (is_array($response->ImNotifList->ImNotifReq)) {
                                                $r = $response->ImNotifList->ImNotifReq;
                                                $c = 0;
                                                $response = -1;
                                                while ($c < count($r)) {
                                                    if (($r[$c]->message->bankTrxId == $trxid)&&($r[$c]->message->merchTrxId == $mytrxid)) {
                                                        $response = $r[$c]->message->bankTrxResult;
                                                    }
                                                    $c++;
                                                }
                                            }
                                            else {
                                                $response = $response->ImNotifList->ImNotifReq->message->bankTrxResult;
                                            }
                                            if ($response == 0) {
                                                $mr->setFizetve(true);
                                                $mr->setOTPayResult($response);
                                                $mr->setOTPayResultText($client->getErrorText($response));
                                                $this->getEm()->persist($mr);
                                                $this->getEm()->flush();
                                            }
                                            else {
                                                $error = $client->getErrorText($response);
                                                $mr->setOTPayResult($response);
                                                $mr->setOTPayResultText($error);
                                                $this->getEm()->persist($mr);
                                                $this->getEm()->flush();
                                            }

                                        }
                                        else {
                                            $error = 'Ismeretlen UMG válasz.';
                                            $mr->setOTPayResult(-1);
                                            $mr->setOTPayResultText($error . ' => ' . print_r($response, true));
                                            $this->getEm()->persist($mr);
                                            $this->getEm()->flush();
                                        }
                                    }
                                    else {
                                        $error = 'Ismeretlen UMG válasz.';
                                        $mr->setOTPayResult(-1);
                                        $mr->setOTPayResultText($error . ' => ' . print_r($response, true));
                                        $this->getEm()->persist($mr);
                                        $this->getEm()->flush();
                                    }
                                }
                                else {
                                    $error = $client->getRCErrorText($response->result);
                                    $mr->setOTPayResult($response->result);
                                    $mr->setOTPayResultText($error);
                                    $this->getEm()->persist($mr);
                                    $this->getEm()->flush();
                                }

                            }
                            else {
                                $error = $client->getRCErrorText($response->result);
                                $mr->setOTPayResult($response->result);
                                $mr->setOTPayResultText($error);
                                $this->getEm()->persist($mr);
                                $this->getEm()->flush();
                            }
                        } catch (Exception $e) {
                            $exception = $e;
                            $error = $exception->getMessage();
                        }
                    }
                }
                else {
                    $error = 'A fizetendő összeg nem lehet nulla';
                }
            }
            else {
                $error = 'A megrendelés nem található';
            }
        }
        else {
            $error = 'Hiányzik a megrendelés azonosító';
        }

        if ($error) {
            Store::getMainSession()->checkoutfizeteserrors = $error;
            Header('Location: ' . Store::getRouter()->generate('showcheckoutfizetes'));
        }
        else {
            Header('Location: ' . Store::getRouter()->generate('checkoutkoszonjuk'));
        }
    }

    public function saveCheckoutFizmod() {
        $megrendelesszam = $this->params->getStringRequestParam('megrendelesszam');
        $f = $this->getRepo('Entities\Fizmod')->find($this->params->getIntRequestParam('fizetesimod'));

        $mf = $this->getRepo('Entities\Bizonylatfej')->find($megrendelesszam);
        if ($mf && $f) {
            $mf->setFizmod($f);
			$this->getEm()->persist($mf);
			$this->getEm()->flush();
            $bizstatusz = $this->getRepo('Entities\Bizonylatstatusz')->find(Store::getParameter(\mkw\consts::BizonylatStatuszFuggoben));
            if ($bizstatusz) {
                $mf->sendStatuszEmail($bizstatusz->getEmailtemplate());
            }
        }

        Header('Location: ' . Store::getRouter()->generate('checkoutkoszonjuk'));

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
        Store::getMainSession()->lastszallmod = 0;
        Store::getMainSession()->lastfizmod = 0;

		$view->printTemplateResult(false);
	}
}