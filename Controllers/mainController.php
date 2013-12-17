<?php

namespace Controllers;

use mkw\store,
	mkw\consts;

class mainController extends \mkwhelpers\Controller {

	private $view;

	protected function createTermekfaParams() {
		return array(
			'elemperpage' => $this->params->getIntRequestParam('elemperpage', 20),
			'pageno' => $this->params->getIntRequestParam('pageno', 1),
			'order' => $this->params->getStringRequestParam('order', 'ardesc'),
			'filter' => $this->params->getStringRequestParam('filter', ''),
			'klikkeltcimkekatid' => $this->params->getIntRequestParam('cimkekatid'),
			'arfilter' => $this->params->getStringRequestParam('arfilter', ''),
			'keresett' => $this->params->getStringRequestParam('keresett', ''),
			'vt' => $this->params->getIntRequestParam('vt', 1)
		);
	}

	public function show404() {
		$this->view = $this->getTemplateFactory()->createMainView('404.tpl');
		store::fillTemplate($this->view);
		$tc = new termekController($this->params);
		$this->view->setVar('ajanlotttermekek', $tc->getAjanlottLista());
		$this->view->setVar('seodescription', t('Sajnos nem találjuk.'));
		$this->view->setVar('pagetitle', t('Sajnos nem találjuk.'));
		$this->view->printTemplateResult(false);
	}

	public function view() {
		$this->view = $this->getTemplateFactory()->createMainView('main.tpl');
		store::fillTemplate($this->view);
		$hc = new hirController($this->params);
		$tc = new termekController($this->params);
		$khc = new korhintaController($this->params);
		$tfc = new termekfaController($this->params);
        $tcc = new termekcimkeController($this->params);
		$this->view->setVar('pagetitle', store::getParameter(consts::Oldalcim));
		$this->view->setVar('seodescription', store::getParameter(consts::Seodescription));
		$this->view->setVar('hirek', $hc->gethirlist());
		$this->view->setVar('ajanlotttermekek', $tc->getAjanlottLista());
		$this->view->setVar('legnepszerubbtermekek', $tc->getLegnepszerubbLista());
		$this->view->setVar('korhintalista', $khc->getLista());
		$this->view->setVar('topkategorialista', $tfc->getformenu(store::getSetupValue('topkategoriamenunum', 3), 0));
        $this->view->setVar('kiemeltmarkalista', $tcc->getKiemeltList());
		$this->view->printTemplateResult(true);
	}

	public function termekfa() {
		$com = $this->params->getStringParam('slug');
		$tf = new termekfaController($this->params);
		$ag = $tf->getRepo()->findOneBySlug($com);
		if ($ag && !$ag->getInaktiv()) {
			if (count($ag->getChildren()) > 0) {
				$this->view = $this->getTemplateFactory()->createMainView('katlista.tpl');
				$t = $tf->getkatlista($ag);
			}
			else {
				$this->view = $this->getTemplateFactory()->createMainView('termeklista.tpl');
				$t = $tf->gettermeklistaforparent($ag, 'termekfa');
			}
			foreach ($t as $k => $v) {
				$this->view->setVar($k, $v);
			}
			store::fillTemplate($this->view);
			$this->view->setVar('pagetitle', $ag->getShowOldalcim());
			$this->view->setVar('seodescription', $ag->getShowSeodescription());
			$this->view->printTemplateResult(true);
		}
		else {
			store::redirectTo404($com, $this->params);
		}
	}

    public function szuro() {
		$tf = new termekfaController($this->params);
        $this->view = $this->getTemplateFactory()->createMainView('termeklista.tpl');
        $t = $tf->gettermeklistaforparent(null, 'szuro');
        foreach ($t as $k => $v) {
            $this->view->setVar($k, $v);
        }
        store::fillTemplate($this->view);
        $this->view->printTemplateResult(true);
    }

	public function kereses() {
		$term = trim($this->params->getStringRequestParam('term'));
		if ($term) {
			$r = store::getEm()->getRepository('\Entities\Termek');
			$res = $r->getNevek($term);
			echo json_encode($res);
		}
		else {
			$keresoszo = trim($this->params->getStringRequestParam('keresett'));
			if ($keresoszo != '') {
				$log = new \Entities\Keresoszolog($keresoszo);
				store::getEm()->persist($log);
				store::getEm()->flush();

				$tf = new termekfaController($this->params);
				$t = $tf->gettermeklistaforparent(null, 'kereses');

                $this->view = $this->getTemplateFactory()->createMainView('termeklista.tpl');
                foreach ($t as $k => $v) {
                    $this->view->setVar($k, $v);
                }
				store::fillTemplate($this->view);
				$this->view->setVar('seodescription', t('A keresett kifejezés: ') . $keresoszo);
				$this->view->setVar('pagetitle', t('A keresett kifejezés: ') . $keresoszo);
				$this->view->printTemplateResult(true);
			}
			else {
				$this->view = $this->getTemplateFactory()->createMainView('nincstalalat.tpl');
				$tc = new termekController($this->params);
				$this->view->setVar('ajanlotttermekek', $tc->getAjanlottLista());
				store::fillTemplate($this->view);
				$this->view->setVar('seodescription', t('Keressen valamit.'));
				$this->view->setVar('pagetitle', t('Keressen valamit.'));
				$this->view->printTemplateResult(true);
			}
		}
	}

	public function termek() {
		$com = $this->params->getStringParam('slug');
		$tc = new termekController($this->params);
		$termek = $tc->getRepo()->findOneBySlug($com);
		if ($termek && !$termek->getInaktiv() && $termek->getLathato()) {
			$this->view = $this->getTemplateFactory()->createMainView('termeklap.tpl');
			store::fillTemplate($this->view);
			$this->view->setVar('pagetitle', $termek->getShowOldalcim());
			$this->view->setVar('seodescription', $termek->getShowSeodescription());
			$t = $tc->getTermekLap($termek);
			foreach ($t as $k => $v) {
				$this->view->setVar($k, $v);
			}
			$this->view->printTemplateResult(true);
		}
		else {
			store::redirectTo404($com, $this->params);
		}
	}

	public function valtozatar() {
		$termekid = $this->params->getIntRequestParam('t');
		$valtozatid = $this->params->getIntRequestParam('vid');
		$termek = store::getEm()->getRepository('Entities\Termek')->find($termekid);
		$valtozat = store::getEm()->getRepository('Entities\TermekValtozat')->find($valtozatid);
		$ret = array();
		$ret['price'] = number_format($termek->getBruttoAr($valtozat), 0, ',', ' ') . ' Ft';
		echo json_encode($ret);
	}

	public function valtozat() {
		$termekkod = $this->params->getIntRequestParam('t');
		$tipusid = $this->params->getIntRequestParam('ti');
		$valtozatertek = $this->params->getRequestParam('v');
		$masiktipusid = $this->params->getIntRequestParam('mti');
		$masikselected = $this->params->getRequestParam('sel');
		$ret = array();

		$tc = new termekController($this->params);
		$termek = $tc->getRepo()->find($termekkod);

		if ($masiktipusid) {
			$t = array($tipusid, $masiktipusid);
			$e = array($valtozatertek, $masikselected);
		}
		else {
			$t = array($tipusid);
			$e = array($valtozatertek);
		}
		$termekvaltozat = store::getEm()->getRepository('Entities\TermekValtozat')->getByProperties($termek->getId(), $t, $e);
		$ret['price'] = number_format($termek->getBruttoAr($termekvaltozat), 0, ',', ' ') . ' Ft';

		$valtozatok = $termek->getValtozatok();
		foreach ($valtozatok as $valtozat) {
			if ($valtozat->getElerheto()) {
				if ($valtozat->getAdatTipus1Id() == $tipusid && ($valtozat->getErtek1() == $valtozatertek || !$valtozatertek)) {
					$ret['adat'][$valtozat->getErtek2()] = array('value' => $valtozat->getErtek2(), 'sel' => $masikselected == $valtozat->getErtek2());
				}
				elseif ($valtozat->getAdatTipus2Id() == $tipusid && ($valtozat->getErtek2() == $valtozatertek || !$valtozatertek)) {
					$ret['adat'][$valtozat->getErtek1()] = array('value' => $valtozat->getErtek1(), 'sel' => $masikselected == $valtozat->getErtek1());
				}
			}
		}
		echo json_encode($ret);
	}

	public function kapcsolat() {
		$com = $this->params->getStringParam('todo');
		switch ($com) {
			case 'ment':
				$hibas = false;
				$hibak = array();
				$nev = $this->params->getStringRequestParam('nev');
				$email1 = $this->params->getStringRequestParam('email1');
				$email2 = $this->params->getStringRequestParam('email2');
				$telefon = $this->params->getStringRequestParam('telefon');
				$rendelesszam = $this->params->getStringRequestParam('rendelesszam');
                $tema = \mkw\Store::getEm()->getRepository('Entities\Kapcsolatfelveteltema')->find($this->params->getStringRequestParam('tema'));
                if ($tema) {
                    $temanev = $tema->getNev();
                }
                else {
                    $temanev = 'Imseretlen';
                }
				$szoveg = $this->params->getStringRequestParam('szoveg');
				if (!\Zend_Validate::is($email1, 'EmailAddress') || !\Zend_Validate::is($email2, 'EmailAddress')) {
					$hibas = true;
					$hibak['email'] = t('Rossz az email');
				}
				if ($email1 !== $email2) {
					$hibas = true;
					$hibak['email'] = t('Nem egyezik a két emailcím');
				}
				if ($nev == '') {
					$hibas = true;
					$hibak['nev'] = t('Üres a név');
				}
				if ($tema == '') {
					$hibas = true;
					$hibak['tema'] = t('Nincs megadva téma');
				}
				if (!$hibas) {
                    $mailer = new \mkw\mkwmailer();
                    $mailer->setTo('info@mindentkapni.hu');
                    $mailer->setSubject('Kapcsolatfelvétel, ' . $rendelesszam . ' ' . $nev);
                    $mailer->setMessage(
                        'Rendelésszám: ' . $rendelesszam . '<br>' .
                        'Név: ' . $nev . '<br>' .
                        'Email: ' . $email . '<br>' .
                        'Telefon: ' . $telefon . '<br>' .
                        'Téma: ' . $temanev . '<br>' .
                        'Szöveg: ' . $szoveg . '<br>'
                    );
                    $mailer->send("From: " . $email . "\r\n"
                        . "Reply-to: " . $email . "\r\n"
                        . "MIME-version: 1.0\r\n"
                        . "Content-Type: text/html; charset=utf-8\r\n");
					$view = $this->getTemplateFactory()->createMainView('kapcsolatkosz.tpl');
					store::fillTemplate($view);
				}
				else {
					$kftc = new kapcsolatfelveteltemaController($this->params);
					$view = $this->getTemplateFactory()->createMainView('kapcsolat.tpl');
					$view->setVar('nev', $nev);
					$view->setVar('email1', $email1);
					$view->setVar('email2', $email2);
					$view->setVar('telefon', $telefon);
					$view->setVar('rendelesszam', $rendelesszam);
					$view->setVar('temalista', $kftc->getSelectList($tema));
					$view->setVar('szoveg', $szoveg);
					$view->setVar('hibak', $hibak);
				}
				$view->printTemplateResult(false);
				break;
			default :
				$kftc = new kapcsolatfelveteltemaController($this->params);
				$this->view = $this->getTemplateFactory()->createMainView('kapcsolat.tpl');
				store::fillTemplate($this->view);
				$this->view->setVar('temalista', $kftc->getSelectList(0));
				$this->view->printTemplateResult(true);
				break;
		}
	}

}