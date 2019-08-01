<?php

namespace Controllers;

use mkw\store;

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
        $p = \mkw\store::getMainSession()->params;
        if (!$p) {
            $p = new \mkwhelpers\ParameterHandler(array());
        }
        \mkw\store::getMainSession()->params = false;

		$view = \mkw\store::getTemplateFactory()->createMainView('checkout.tpl');
        \mkw\store::fillTemplate($view, false);

		$szm = new szallitasimodController($this->params);
		$szlist = $szm->getSelectList(null);

        $u = \mkw\store::getLoggedInUser();
        if ($u) {
            $user['nev'] = $u->getNev();
            $user['email'] = $u->getEmail();
            $user['vezeteknev'] = $u->getVezeteknev();
            $user['keresztnev'] = $u->getKeresztnev();
            $user['telefon'] = $u->getTelefon();
            $user['telszam'] = $u->getTelszam();
            $user['irszam'] = $u->getIrszam();
            $user['varos'] = $u->getVaros();
            $user['utca'] = $u->getUtca();
            $user['adoszam'] = $u->getAdoszam();
            $user['orszag'] = $u->getOrszagId();
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
            $user['telszam'] = '';
            $user['orszag'] = \mkw\store::getMainSession()->orszag;
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
		$view->setVar('showerror', \mkw\store::getMainSession()->loginerror);
        $view->setVar('checkouterrors', \mkw\store::getMainSession()->checkoutErrors);
		$view->setVar('showaszflink', \mkw\store::getRouter()->generate('showstatlappopup', false, array('lap' => 'aszf')));
        $view->setVar('regkell', $p->getIntRequestParam('regkell', 2));
        $view->setVar('vezeteknev', $this->vv($p->getStringRequestParam('vezeteknev'), $user['vezeteknev']));
        $view->setVar('keresztnev', $this->vv($p->getStringRequestParam('keresztnev'), $user['keresztnev']));
        $view->setVar('telefon', $this->vv($p->getStringRequestParam('telefon'), $user['telefon']));
        $view->setVar('telszam', $this->vv($p->getStringRequestParam('telszam'), $user['telszam']));
        $view->setVar('jelszo1', $p->getStringRequestParam('jelszo1'));
        $view->setVar('jelszo2', $p->getStringRequestParam('jelszo2'));
        $view->setVar('email', $this->vv($p->getStringRequestParam('kapcsemail'), $user['email']));
        $view->setVar('szamlanev', $this->vv($p->getStringRequestParam('szamlanev'), $user['nev']));
        $view->setVar('szamlairszam', $this->vv($p->getStringRequestParam('szamlairszam'), $user['irszam']));
        $view->setVar('szamlavaros', $this->vv($p->getStringRequestParam('szamlavaros'), $user['varos']));
        $view->setVar('szamlautca', $this->vv($p->getStringRequestParam('szamlautca'), $user['utca']));
        $view->setVar('adoszam', $this->vv($p->getStringRequestParam('adoszam'), $user['adoszam']));
        $view->setVar('szamlaeqszall', $this->vv($p->getBoolRequestParam('szamlaeqszall'), $user['szalladategyezik']));
        $view->setVar('orszag', $this->vv($p->getIntRequestParam('orszag'), $user['orszag']));
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
        $oc = new orszagController($p);
        $view->setVar('szallorszaglist', $oc->getSelectList($this->vv($p->getIntRequestParam('orszag'), $user['orszag'])));
        $telkorzetc = new korzetszamController($this->params);
        $view->setVar('telkorzetlist', $telkorzetc->getSelectList($this->vv($p->getStringRequestParam('telkorzet'), $user['telkorzet'])));
        \mkw\store::getMainSession()->loginerror = false;
        \mkw\store::getMainSession()->checkoutErrors = false;
		$view->printTemplateResult(false);
	}

	public function getFizmodList() {
	    $krepo = $this->getRepo('Entities\SzallitasimodFizmodNovelo');
		$view = \mkw\store::getTemplateFactory()->createMainView('checkoutfizmodlist.tpl');
		$fm = new fizmodController($this->params);
		$szm = $this->params->getIntRequestParam('szallitasimod');
		$szlist = $fm->getSelectList(null, $szm);
		$adat = array();
		foreach ($szlist as $szl) {
		    $x = $krepo->getBySzallitasimodFizmod($szm, $szl['id']);
		    if ($x) {
		        if (is_array($x)) {
		            $x = $x[0];
                }
                $szl['novelo'] = $x->getOsszeg();
            }
		    $adat[] = $szl;
        }
		$view->setVar('fizmodlist', $adat);
		echo json_encode(array(
            'html' => $view->getTemplateResult()
        ));
	}

	public function getTetelList() {
        $kuponkod = $this->params->getStringRequestParam('kupon');
        $kuponszoveg = '';
        if ($kuponkod) {
            /** @var \Entities\Kupon $kupon */
            $kupon = $this->getRepo('Entities\Kupon')->find($kuponkod);
            if ($kupon) {
                if ($kupon->isErvenyes()) {
                    if ($kupon->isIngyenSzallitas()) {
                        $kuponszoveg = $kupon->getTipusStr();
                    }
                }
                else {
                    $kuponszoveg = $kupon->getLejartStr();
                }
            }
            else {
                $kuponszoveg = 'ismeretlen kupon';
            }
        }
        $this->getRepo('Entities\Kosar')->createSzallitasiKtg(
            $this->params->getIntRequestParam('szallitasimod'),
            $this->params->getIntRequestParam('fizmod'),
            $kuponkod);
		$view = \mkw\store::getTemplateFactory()->createMainView('checkouttetellist.tpl');

        $kr = $this->getRepo('Entities\Kosar');
		$sorok = $kr->getDataBySessionId(\Zend_Session::getId());
		$s = array();
        $partner = \mkw\store::getLoggedInUser();
        if ($partner) {
            $view->setVar('valutanemnev', $partner->getValutanemnev());
        }
        else {
            $view->setVar('valutanemnev', \mkw\store::getMainSession()->valutanemnev);
        }
		foreach ($sorok as $sor) {
			$s[] = $sor->toLista($partner);
		}
		$view->setVar('tetellista', $s);
		echo json_encode(array(
            'html' => $view->getTemplateResult(),
            'hash' => $kr->getHash(),
            'kuponszoveg' => $kuponszoveg
        ));
	}

	public function save() {

        switch (true) {
            case \mkw\store::isMindentkapni():
                $errorlogtext = array();
                $errors = array();

                $regkell = $this->params->getIntRequestParam('regkell');
                $vezeteknev = $this->params->getStringRequestParam('vezeteknev');
                $keresztnev = $this->params->getStringRequestParam('keresztnev');
                $telkorzet = $this->params->getStringRequestParam('telkorzet');
                $telszam = preg_replace('/[^0-9+]/', '', $this->params->getStringRequestParam('telszam'));
                $telefon = '+36' . $telkorzet . $telszam;
                $jelszo1 = $this->params->getStringRequestParam('jelszo1');
                $jelszo2 = $this->params->getStringRequestParam('jelszo2');
                $kapcsemail = trim($this->params->getStringRequestParam('kapcsemail'));
                $validkapcsemail = \mkw\store::isValidEmail($kapcsemail);
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
                $csomagterminalid = $this->params->getIntRequestParam('foxpostterminal');
                if (!$csomagterminalid) {
                    $csomagterminalid = $this->params->getIntRequestParam('glsterminal');
                }
                $tofterminalid = $this->params->getIntRequestParam('tofid');
                $kuponkod = $this->params->getStringRequestParam('kupon');

                $ok = ($vezeteknev && $keresztnev && $telkorzet && $telszam &&
                        $szallirszam && $szallvaros && $szallutca && $szallnev &&
                        (!$szamlaeqszall ? $szamlanev : true) &&
                        (!$szamlaeqszall ? $szamlairszam : true) &&
                        (!$szamlaeqszall ? $szamlavaros : true) &&
                        (!$szamlaeqszall ? $szamlautca : true) &&
                        $szallitasimod > 0 &&
                        $fizetesimod > 0 &&
                        $aszfready
                        );

                if (\mkw\store::isFoxpostSzallitasimod($szallitasimod) || \mkw\store::isGLSSzallitasimod($szallitasimod)) {
                    $ok = $ok && $csomagterminalid;
                }

                if (\mkw\store::isTOFSzallitasimod($szallitasimod)) {
                    $ok = $ok && $tofterminalid;
                }

                if (!$ok) {
                    $errorlogtext[] = '1alapadat';
                    if (!$vezeteknev) {
                        $errors[] = 'Nem adta meg a vezetéknevét.';
                    }
                    if (!$keresztnev) {
                        $errors[] = 'Nem adta meg a keresztnevét.';
                    }
                    if (!$telkorzet || !$telszam) {
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
                        $ok = $ok && $kapcsemail && $validkapcsemail;
                        if (!$kapcsemail) {
                            $errorlogtext[] = '2vendegemail';
                            $errors[] = 'Nem adott meg emailcímet.';
                        }
                        else {
                            if (!$validkapcsemail) {
                                $errorlogtext[] = '2vendegemailhiba';
                                $errors[] = 'A megadott emailcím hibás.';
                            }
                        }
                        break;
                    case 2: // regisztráció
                        $ok = $ok && $jelszo1 && $jelszo2 && ($jelszo1 === $jelszo2) && $kapcsemail && $validkapcsemail;
                        if (!$jelszo1 || !$jelszo2 || ($jelszo1 !== $jelszo2)) {
                            $errorlogtext[] = '3regjelszo';
                            $errors[] = 'Nem adott meg jelszót, vagy a két jelszó nem egyezik.';
                        }
                        if (!$kapcsemail) {
                            $errorlogtext[] = '3regemail';
                            $errors[] = 'Nem adott meg emailcímet.';
                        }
                        else {
                            if (!$validkapcsemail) {
                                $errorlogtext[] = '3vendegemailhiba';
                                $errors[] = 'A megadott emailcím hibás.';
                            }
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
                            $pc->login($kapcsemail, $jelszo1);
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
                    $partner->setTelkorzet($telkorzet);
                    $partner->setTelszam($telszam);
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
                    $megrendfej->setReferrer(\mkw\store::getMainSession()->referrer);
                    $megrendfej->setBizonylattipus($biztipus);
                    $megrendfej->setKelt('');
                    $megrendfej->setTeljesites('');
                    $megrendfej->setEsedekesseg('');
                    $megrendfej->setHatarido('');
                    $megrendfej->setArfolyam(1);
                    $megrendfej->setPartner($partner);
                    $megrendfej->setKupon($kuponkod);
                    $megrendfej->setFizmod($this->getEm()->getRepository('Entities\Fizmod')->find($fizetesimod));
                    $megrendfej->setSzallitasimod($this->getEm()->getRepository('Entities\Szallitasimod')->find($szallitasimod));
                    $valutanemid = \mkw\store::getParameter(\mkw\consts::Valutanem);
                    $valutanem = $this->getRepo('Entities\Valutanem')->find($valutanemid);
                    $megrendfej->setValutanem($valutanem);
                    $raktarid = \mkw\store::getParameter(\mkw\consts::Raktar);
                    $megrendfej->setRaktar($this->getRepo('Entities\Raktar')->find($raktarid));
                    $megrendfej->setBankszamla($valutanem->getBankszamla());
                    $megrendfej->setWebshopmessage($webshopmessage);
                    $megrendfej->setCouriermessage($couriermessage);
                    if (\mkw\store::isBarionFizmod($fizetesimod)) {
                        $bizstatusz = $this->getRepo('Entities\Bizonylatstatusz')->find(\mkw\store::getParameter(\mkw\consts::BarionFizetesrevarStatusz));
                    }
                    else {
                        $bizstatusz = $this->getRepo('Entities\Bizonylatstatusz')->find(\mkw\store::getParameter(\mkw\consts::BizonylatStatuszFuggoben));
                    }
                    $megrendfej->setBizonylatstatusz($bizstatusz);
                    if (\mkw\store::isFoxpostSzallitasimod($szallitasimod) || \mkw\store::isGLSSzallitasimod($szallitasimod)) {
                        $fpc = $this->getRepo('Entities\CsomagTerminal')->find($csomagterminalid);
                        if ($fpc) {
                            $megrendfej->setCsomagterminal($fpc);
                        }
                    }
                    if (\mkw\store::isTOFSzallitasimod($szallitasimod)) {
                        $fpc = $this->getRepo('Entities\CsomagTerminal')->find($tofterminalid);
                        if ($fpc) {
                            $megrendfej->setCsomagterminal($fpc);
                        }
                    }

                    $lasttermeknevek = array();
                    $lasttermekids = array();
                    $lasttermekadat = array();
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
                        $lasttermekadat[] = array(
                            'id' => $t->getTermekId(),
                            'unitprice' => $t->getBruttoegysar(),
                            'qty' => $t->getMennyiseg()
                        );
                        $this->getEm()->persist($t);
                    }
                    $this->getEm()->persist($megrendfej);
                    $this->getEm()->flush();

                    \mkw\store::getMainSession()->lastmegrendeles = $megrendfej->getId();
                    \mkw\store::getMainSession()->lastemail = $kapcsemail;
                    \mkw\store::getMainSession()->lasttermeknevek = $lasttermeknevek;
                    \mkw\store::getMainSession()->lasttermekids = $lasttermekids;
                    \mkw\store::getMainSession()->lastszallmod = $szallitasimod;
                    \mkw\store::getMainSession()->lastfizmod = $fizetesimod;
                    \mkw\store::getMainSession()->lasttermekadat = $lasttermekadat;
                    $kc = new kosarController($this->params);
                    $kc->clear();

                    if ($fizetesimod == \mkw\store::getParameter(\mkw\consts::OTPayFizmod)) {
                        Header('Location: ' . \mkw\store::getRouter()->generate('showcheckoutfizetes'));
                    }
                    else {
                        if ($bizstatusz) {
                            $megrendfej->sendStatuszEmail($bizstatusz->getEmailtemplate());
                        }
                        if (\mkw\store::isBarionFizmod($fizetesimod)) {
                            $bc = new barionController($this->params);
                            $paymentres = $bc->startPayment($megrendfej);
                            if ($paymentres['result']) {
                                Header('Location: ' . $paymentres['redirecturl']);
                            }
                            else {
                                Header('Location: ' . \mkw\store::getRouter()->generate('checkoutbarionerror', false, array(), array('mr' => $megrendfej->getId())));
                            }
                        }
                        else {
                            Header('Location: ' . \mkw\store::getRouter()->generate('checkoutkoszonjuk'));
                        }
                    }
                }
                else {
                    \mkw\store::getMainSession()->params = $this->params;
                    \mkw\store::getMainSession()->checkoutErrors = $errors;
                    Header('Location: ' . \mkw\store::getRouter()->generate('showcheckout'));
                }
                break;
            case \mkw\store::isSuperzoneB2B():
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
                $hatarido = $this->params->getDateRequestParam('hatarido');

                if ($szalleqszamla) {
                    $szallnev = $szamlanev;
                    $szallirszam = $szamlairszam;
                    $szallvaros = $szamlavaros;
                    $szallutca = $szamlautca;
                }

                $ok = ($szallnev && $szallirszam && $szallvaros && $szallutca &&
                        $szamlanev && $szamlairszam && $szamlavaros && $szamlautca && $hatarido);

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

                    $partner = \mkw\store::getLoggedInUser();
                    $nullasafa = $this->getRepo('Entities\Afa')->find(\mkw\store::getParameter(\mkw\consts::NullasAfa));
                    $biztetelcontroller = new bizonylattetelController($this->params);
                    $valutanem = $partner->getValutanem();

                    $biztipus = $this->getRepo('Entities\Bizonylattipus')->find('megrendeles');
                    $megrendfej = new \Entities\Bizonylatfej();
                    $megrendfej->setPersistentData();
                    $megrendfej->setIp($_SERVER['REMOTE_ADDR']);
                    $megrendfej->setReferrer(\mkw\store::getMainSession()->referrer);
                    $megrendfej->setBizonylattipus($biztipus);
                    $megrendfej->setKelt('');
                    $megrendfej->setTeljesites('');
                    $megrendfej->setEsedekesseg('');
                    $megrendfej->setHatarido(new \DateTime(\mkw\store::convDate($hatarido)));

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
                    $raktarid = \mkw\store::getParameter(\mkw\consts::Raktar);
                    $megrendfej->setRaktar($this->getRepo('Entities\Raktar')->find($raktarid));
                    if ($valutanem) {
                        $megrendfej->setBankszamla($valutanem->getBankszamla());
                    }
                    $bizstatusz = $this->getRepo('Entities\Bizonylatstatusz')->find(\mkw\store::getParameter(\mkw\consts::BizonylatStatuszFuggoben));
                    $megrendfej->setBizonylatstatusz($bizstatusz);

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

                    \mkw\store::getMainSession()->lastmegrendeles = $megrendfej->getId();
                    //\mkw\store::getMainSession()->lastemail = $kapcsemail;
                    \mkw\store::getMainSession()->lasttermeknevek = $lasttermeknevek;
                    \mkw\store::getMainSession()->lasttermekids = $lasttermekids;
                    //\mkw\store::getMainSession()->lastszallmod = $szallitasimod;
                    //\mkw\store::getMainSession()->lastfizmod = $fizetesimod;
                    $kc = new kosarController($this->params);
                    $kc->clear();

                    if ($bizstatusz) {
                        $megrendfej->sendStatuszEmail($bizstatusz->getEmailtemplate());
                    }
                    Header('Location: ' . \mkw\store::getRouter()->generate('checkoutkoszonjuk'));
                }
                else {
                    \mkw\store::getMainSession()->params = $this->params;
                    \mkw\store::getMainSession()->checkoutErrors = $errors;
                    Header('Location: ' . \mkw\store::getRouter()->generate('showcheckout'));
                }
                break;
            case \mkw\store::isMugenrace():
                $errorlogtext = array();
                $errors = array();

                $regkell = $this->params->getIntRequestParam('regkell');
                $vezeteknev = $this->params->getStringRequestParam('vezeteknev');
                $keresztnev = $this->params->getStringRequestParam('keresztnev');
                $telefon = preg_replace('/[^0-9+]/', '', $this->params->getStringRequestParam('telefon'));
                if (substr_count($telefon, '+') > 1) {
                    $firstPlus = strpos($telefon, '+') + 1;
                    $telefon = substr($telefon, 0, $firstPlus) . str_replace('+', '', substr($telefon, $firstPlus));
                }
                $jelszo1 = $this->params->getStringRequestParam('jelszo1');
                $jelszo2 = $this->params->getStringRequestParam('jelszo2');
                $kapcsemail = $this->params->getStringRequestParam('kapcsemail');
                $szamlanev = $this->params->getStringRequestParam('szamlanev');
                $szamlairszam = $this->params->getStringRequestParam('szamlairszam');
                $szamlavaros = $this->params->getStringRequestParam('szamlavaros');
                $szamlautca = $this->params->getStringRequestParam('szamlautca');
                $szallnev = $this->params->getStringRequestParam('szallnev');
                $szallirszam = $this->params->getStringRequestParam('szallirszam');
                $szallvaros = $this->params->getStringRequestParam('szallvaros');
                $szallutca = $this->params->getStringRequestParam('szallutca');
                $szamlaeqszall = $this->params->getBoolRequestParam('szamlaeqszall');
                $orszag = $this->params->getIntRequestParam('orszag');
                $szallitasimod = $this->params->getIntRequestParam('szallitasimod');
                $fizetesimod = $this->params->getIntRequestParam('fizetesimod');
                $webshopmessage = $this->params->getStringRequestParam('webshopmessage');
                $couriermessage = $this->params->getStringRequestParam('couriermessage');
                $aszfready = $this->params->getBoolRequestParam('aszfready');
                $akciohirlevel = $this->params->getBoolRequestParam('akciohirlevel');
                $ujdonsaghirlevel = $this->params->getBoolRequestParam('ujdonsaghirlevel');

                if ($szamlaeqszall) {
                    $szamlanev = $szallnev;
                    $szamlairszam = $szallirszam;
                    $szamlavaros = $szallvaros;
                    $szamlautca = $szallutca;
                }

                $ok = ($szallnev && $szallirszam && $szallvaros && $szallutca &&
                    $szamlanev && $szamlairszam && $szamlavaros && $szamlautca && $orszag);

                if (!$ok) {
                    $errorlogtext[] = '1alapadat';
                    $errors[] = 'Nem adott meg egy kötelező adatot.';
                }

                $kosartetelek = $this->getRepo('Entities\Kosar')->getDataBySessionId(\Zend_Session::getId());
                $ok = $ok && count($kosartetelek) > 0;
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
                            $pc->login($kapcsemail, $jelszo1);
                            break;
                        default: // be van jelentkezve
                            $partner = $this->getRepo('Entities\Partner')->getLoggedInUser();
                            break;
                    }
                    $partner->setSzallnev($szallnev);
                    $partner->setSzallirszam($szallirszam);
                    $partner->setSzallvaros($szallvaros);
                    $partner->setSzallutca($szallutca);
                    $orszag = \mkw\store::getEm()->getRepository('Entities\Orszag')->find($this->params->getIntRequestParam('orszag', 0));
                    if ($orszag) {
                        $partner->setOrszag($orszag);
                    }
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
                    $partner->setAkcioshirlevelkell($akciohirlevel);
                    $partner->setUjdonsaghirlevelkell($ujdonsaghirlevel);
                    $this->getEm()->persist($partner);

                    $nullasafa = $this->getRepo('Entities\Afa')->find(\mkw\store::getParameter(\mkw\consts::NullasAfa));
                    $biztetelcontroller = new bizonylattetelController($this->params);
                    //$valutanem =

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
                    $megrendfej->setReferrer(\mkw\store::getMainSession()->referrer);
                    $megrendfej->setBizonylattipus($biztipus);
                    $megrendfej->setKelt('');
                    $megrendfej->setTeljesites('');
                    $megrendfej->setEsedekesseg('');

                    $megrendfej->setPartner($partner);

                    $megrendfej->setPartnernev($szamlanev);
                    $megrendfej->setPartnerirszam($szamlairszam);
                    $megrendfej->setPartnervaros($szamlavaros);
                    $megrendfej->setPartnerutca($szamlautca);
                    $orszagobj = $this->getRepo('Entities\Orszag')->find($orszag);
                    if ($orszagobj) {
                        $megrendfej->setPartnerorszag($orszagobj);
                    }
                    $megrendfej->setSzallnev($szallnev);
                    $megrendfej->setSzallirszam($szallirszam);
                    $megrendfej->setSzallvaros($szallvaros);
                    $megrendfej->setSzallutca($szallutca);

                    $megrendfej->setFizmod($this->getEm()->getRepository('Entities\Fizmod')->find($fizetesimod));
                    $megrendfej->setSzallitasimod($this->getEm()->getRepository('Entities\Szallitasimod')->find($szallitasimod));
                    $valutanemid = \mkw\store::getMainSession()->valutanem;
                    $valutanem = $this->getRepo('Entities\Valutanem')->find($valutanemid);
                    $megrendfej->setValutanem($valutanem);
                    $megrendfej->setWebshopmessage($webshopmessage);
                    $arf = $this->getEm()->getRepository('Entities\Arfolyam')->getActualArfolyam($valutanem, $megrendfej->getTeljesites());
                    $megrendfej->setArfolyam($arf->getArfolyam());
                    $raktarid = \mkw\store::getParameter(\mkw\consts::Raktar);
                    $megrendfej->setRaktar($this->getRepo('Entities\Raktar')->find($raktarid));
                    if ($valutanem) {
                        $megrendfej->setBankszamla($valutanem->getBankszamla());
                    }
                    if (\mkw\store::isBarionFizmod($fizetesimod)) {
                        $bizstatusz = $this->getRepo('Entities\Bizonylatstatusz')->find(\mkw\store::getParameter(\mkw\consts::BarionFizetesrevarStatusz));
                    }
                    else {
                        $bizstatusz = $this->getRepo('Entities\Bizonylatstatusz')->find(\mkw\store::getParameter(\mkw\consts::BizonylatStatuszFuggoben));
                    }
                    $megrendfej->setBizonylatstatusz($bizstatusz);

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

                    \mkw\store::getMainSession()->lastmegrendeles = $megrendfej->getId();
                    //\mkw\store::getMainSession()->lastemail = $kapcsemail;
                    \mkw\store::getMainSession()->lasttermeknevek = $lasttermeknevek;
                    \mkw\store::getMainSession()->lasttermekids = $lasttermekids;
                    //\mkw\store::getMainSession()->lastszallmod = $szallitasimod;
                    //\mkw\store::getMainSession()->lastfizmod = $fizetesimod;
                    $kc = new kosarController($this->params);
                    $kc->clear();

                    if (\mkw\store::isBarionFizmod($fizetesimod)) {
                        $bc = new barionController($this->params);
                        $paymentres = $bc->startPayment($megrendfej);
                        if ($paymentres['result']) {
                            Header('Location: ' . $paymentres['redirecturl']);
                        }
                        else {
                            Header('Location: ' . \mkw\store::getRouter()->generate('checkoutbarionerror', false, array(), array('mr' => $megrendfej->getId())));
                        }
                    }
                    else {
                        if ($bizstatusz) {
                            $megrendfej->sendStatuszEmail($bizstatusz->getEmailtemplate());
                        }
                        Header('Location: ' . \mkw\store::getRouter()->generate('checkoutkoszonjuk'));
                    }
                }
                else {
                    \mkw\store::getMainSession()->params = $this->params;
                    \mkw\store::getMainSession()->checkoutErrors = $errors;
                    Header('Location: ' . \mkw\store::getRouter()->generate('showcheckout'));
                }
                break;
        }
	}

	public function showCheckoutFizetes() {
        $mrszam = \mkw\store::getMainSession()->lastmegrendeles;
        $szallmod = \mkw\store::getMainSession()->lastszallmod;
        $fizmod = \mkw\store::getMainSession()->lastfizmod;
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
        $ooo = \mkw\store::getParameter(\mkw\consts::OTPayFizmod);
        if ($ooo) {
            $excfm[] = $ooo;
        }
        $ooo = \mkw\store::getParameter(\mkw\consts::MasterPassFizmod);
        if ($ooo) {
            $excfm[] = $ooo;
        }

		$view = \mkw\store::getTemplateFactory()->createMainView('checkoutfizmodlist.tpl');
		$fm = new fizmodController($this->params);
		$szlist = $fm->getSelectList($fizmod, $szallmod, $excfm);
		$view->setVar('fizmodlist', $szlist);
        $fml = $view->getTemplateResult();

        $view = \mkw\store::getTemplateFactory()->createMainView('checkoutfizetes.tpl');
        \mkw\store::fillTemplate($view);
        $view->setVar('fizetendo', $fizetendo);
		$view->setVar('megrendelesszam', $mrszam);
        $view->setVar('fizmodlist', $fml);
        $view->setVar('fizmodnev', $fizmodnev);
        $view->setVar('checkouterrors',\mkw\store::getMainSession()->checkoutfizeteserrors);
		$view->printTemplateResult(false);
        \mkw\store::getMainSession()->checkoutfizeteserrors = false;
	}

    public function doCheckoutFizetes() {
        require_once('busvendor/OTPay/MerchTerm_umg_client.php');

        $error = false;
        \mkw\store::getMainSession()->fizetesdb = \mkw\store::getMainSession()->fizetesdb * 1 + 1;

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
                            'isRepeated' => (\mkw\store::getMainSession()->fizetesdb > 1)
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
            \mkw\store::getMainSession()->checkoutfizeteserrors = $error;
            Header('Location: ' . \mkw\store::getRouter()->generate('showcheckoutfizetes'));
        }
        else {
            Header('Location: ' . \mkw\store::getRouter()->generate('checkoutkoszonjuk'));
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
            $bizstatusz = $this->getRepo('Entities\Bizonylatstatusz')->find(\mkw\store::getParameter(\mkw\consts::BizonylatStatuszFuggoben));
            if ($bizstatusz) {
                $mf->sendStatuszEmail($bizstatusz->getEmailtemplate());
            }
        }

        Header('Location: ' . \mkw\store::getRouter()->generate('checkoutkoszonjuk'));

    }

	public function thanks() {
		$view = \mkw\store::getTemplateFactory()->createMainView('checkoutkoszonjuk.tpl');
        \mkw\store::fillTemplate($view);
        $mrszam = \mkw\store::getMainSession()->lastmegrendeles;
		$view->setVar('megrendelesszam', $mrszam);
		$view->setVar('megrendelesadat', \mkw\store::getMainSession()->lasttermekadat);
//itt kell hozza vasarolt termeket keresni session->lasttermekids-re

        $aktsapikey = \mkw\store::getParameter(\mkw\consts::AKTrustedShopApiKey);
        $email = \mkw\store::getMainSession()->lastemail;

        if ($aktsapikey && $email) {
            require_once 'busvendor/AKTrustedShop/TrustedShop.php';

            $ts = new \TrustedShop($aktsapikey);
            $ts->SetEmail($email);

            $ltn = \mkw\store::getMainSession()->lasttermeknevek;
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
        \mkw\store::getMainSession()->lastmegrendeles = '';
        \mkw\store::getMainSession()->lastemail = '';
        \mkw\store::getMainSession()->lasttermeknevek = array();
        \mkw\store::getMainSession()->lasttermekids = array();
        \mkw\store::getMainSession()->lastszallmod = 0;
        \mkw\store::getMainSession()->lastfizmod = 0;
        \mkw\store::getMainSession()->lasttermekadat = array();

		$view->printTemplateResult(false);
	}

	public function barionError() {
	    $mrszam = $this->params->getStringRequestParam('mr');
        $view = \mkw\store::getTemplateFactory()->createMainView('checkoutbarionerror.tpl');
        \mkw\store::fillTemplate($view);
        $view->setVar('megrendelesszam', $mrszam);

        $view->printTemplateResult(false);
    }
}