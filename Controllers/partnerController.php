<?php

namespace Controllers;

use mkw\store;

class partnerController extends \mkwhelpers\MattableController {

	public function __construct($params) {
		$this->setEntityName('Entities\Partner');
		$this->setKarbFormTplName('partnerkarbform.tpl');
		$this->setKarbTplName('partnerkarb.tpl');
		$this->setListBodyRowTplName('partnerlista_tbody_tr.tpl');
		$this->setListBodyRowVarName('_partner');
		parent::__construct($params);
	}

	protected function loadVars($t) {
		$x = array();
		if (!$t) {
			$t = new \Entities\Partner();
			$this->getEm()->detach($t);
		}
		$x['id'] = $t->getId();
		$x['nev'] = $t->getNev();
		$x['vezeteknev'] = $t->getVezeteknev();
		$x['keresztnev'] = $t->getKeresztnev();
		$x['inaktiv'] = $t->getInaktiv();
		$x['idegenkod'] = $t->getIdegenkod();
		$x['adoszam'] = $t->getAdoszam();
		$x['euadoszam'] = $t->getEuadoszam();
		$x['mukengszam'] = $t->getMukengszam();
		$x['jovengszam'] = $t->getJovengszam();
		$x['ostermszam'] = $t->getOstermszam();
		$x['valligszam'] = $t->getValligszam();
		$x['fvmszam'] = $t->getFvmszam();
		$x['cjszam'] = $t->getCjszam();
		$x['cim'] = $t->getCim();
		$x['irszam'] = $t->getIrszam();
		$x['varos'] = $t->getVaros();
		$x['utca'] = $t->getUtca();
		$x['lcim'] = $t->getLCim();
		$x['lirszam'] = $t->getLirszam();
		$x['lvaros'] = $t->getLvaros();
		$x['lutca'] = $t->getLutca();
		$x['telefon'] = $t->getTelefon();
		$x['mobil'] = $t->getMobil();
		$x['fax'] = $t->getFax();
		$x['email'] = $t->getEmail();
		$x['honlap'] = $t->getHonlap();
		$x['megjegyzes'] = $t->getMegjegyzes();
		$x['syncid'] = $t->getSyncid();
		$x['cimkek'] = $t->getCimkenevek();
		$x['fizmodnev'] = $t->getFizmodNev();
		$x['uzletkotonev'] = $t->getUzletkotoNev();
		$x['fizhatido'] = $t->getFizhatido();
		$x['szallnev'] = $t->getSzallnev();
		$x['szallirszam'] = $t->getSzallirszam();
		$x['szallvaros'] = $t->getSzallvaros();
		$x['szallutca'] = $t->getSzallutca();
		$x['nem'] = $t->getNem();
		$x['szuletesiido'] = $t->getSzuletesiido();
		$x['szuletesiidostr'] = $t->getSzuletesiidostr();
		$x['akcioshirlevelkell'] = $t->getAkcioshirlevelkell();
		$x['ujdonsaghirlevelkell'] = $t->getUjdonsaghirlevelkell();
		$x['loggedin'] = $this->checkloggedin();
		$x['vendeg'] = $t->getVendeg();
		return $x;
	}

	protected function setFields($obj) {
		$obj->setNev($this->params->getStringRequestParam('nev'));
		$obj->setVezeteknev($this->params->getStringRequestParam('vezeteknev'));
		$obj->setKeresztnev($this->params->getStringRequestParam('keresztnev'));
		$obj->setInaktiv($this->params->getBoolRequestParam('inaktiv'));
		$obj->setIdegenkod($this->params->getStringRequestParam('idegenkod'));
		$obj->setAdoszam($this->params->getStringRequestParam('adoszam'));
		$obj->setEuadoszam($this->params->getStringRequestParam('euadoszam'));
		$obj->setMukengszam($this->params->getStringRequestParam('mukengszam'));
		$obj->setJovengszam($this->params->getStringRequestParam('jovengszam'));
		$obj->setOstermszam($this->params->getStringRequestParam('ostermszam'));
		$obj->setValligszam($this->params->getStringRequestParam('valligszam'));
		$obj->setFvmszam($this->params->getStringRequestParam('fvmszam'));
		$obj->setCjszam($this->params->getStringRequestParam('cjszam'));
		$obj->setIrszam($this->params->getStringRequestParam('irszam'));
		$obj->setVaros($this->params->getStringRequestParam('varos'));
		$obj->setUtca($this->params->getStringRequestParam('utca'));
		$obj->setLirszam($this->params->getStringRequestParam('lirszam'));
		$obj->setLvaros($this->params->getStringRequestParam('lvaros'));
		$obj->setLutca($this->params->getStringRequestParam('lutca'));
		$obj->setTelefon($this->params->getStringRequestParam('telefon'));
		$obj->setMobil($this->params->getStringRequestParam('mobil'));
		$obj->setFax($this->params->getStringRequestParam('fax'));
		$obj->setEmail($this->params->getStringRequestParam('email'));
		$obj->setHonlap($this->params->getStringRequestParam('honlap'));
		$obj->setMegjegyzes($this->params->getStringRequestParam('megjegyzes'));
		$obj->setSyncid($this->params->getStringRequestParam('syncid'));
		$obj->setFizhatido($this->params->getIntRequestParam('fizhatido'));
		$obj->setSzallnev($this->params->getStringRequestParam('szallnev'));
		$obj->setSzallirszam($this->params->getStringRequestParam('szallirszam'));
		$obj->setSzallvaros($this->params->getStringRequestParam('szallvaros'));
		$obj->setSzallutca($this->params->getStringRequestParam('szallutca'));
		$obj->setNem($this->params->getIntRequestParam('nem'));
		$obj->setSzuletesiido($this->params->getStringRequestParam('szuletesiido'));
		$obj->setAkcioshirlevelkell($this->params->getBoolRequestParam('akcioshirlevelkell'));
		$obj->setUjdonsaghirlevelkell($this->params->getBoolRequestParam('ujdonsaghirlevelkell'));
		$fizmod = store::getEm()->getRepository('Entities\Fizmod')->find($this->params->getIntRequestParam('fizmod', 0));
		if ($fizmod) {
			$obj->setFizmod($fizmod);
		}
		$uk = store::getEm()->getRepository('Entities\Uzletkoto')->find($this->params->getIntRequestParam('uzletkoto', 0));
		if ($uk) {
			$obj->setUzletkoto($uk);
		}
		$obj->removeAllCimke();
		$cimkekpar = $this->params->getArrayRequestParam('cimkek');
		foreach ($cimkekpar as $cimkekod) {
			$cimke = $this->getEm()->getRepository('Entities\Partnercimketorzs')->find($cimkekod);
			if ($cimke) {
				$obj->addCimke($cimke);
			}
		}
		return $obj;
	}

	public function getlistbody() {
		$view = $this->createView('partnerlista_tbody.tpl');

		$filter = array();
		if (!is_null($this->params->getRequestParam('nevfilter', NULL))) {
			$fv = $this->params->getStringRequestParam('nevfilter');
			$filter['fields'][] = 'nev';
			$filter['values'][] = $fv;
		}
		if (!is_null($this->params->getRequestParam('cimkefilter', NULL))) {
			$fv = $this->params->getArrayRequestParam('cimkefilter');
			$cimkekodok = implode(',', $fv);
			if ($cimkekodok) {
				$q = $this->getEm()->createQuery('SELECT p.id FROM Entities\Partnercimketorzs pc JOIN pc.partnerek p WHERE pc.id IN (' . $cimkekodok . ')');
				$res = $q->getScalarResult();
				$cimkefilter = array();
				foreach ($res as $sor) {
					$cimkefilter[] = $sor['id'];
				}
				$filter['fields'][] = 'id';
				$filter['values'][] = $cimkefilter;
			}
		}

		$this->initPager($this->getRepo()->getCount($filter));

		$egyedek = $this->getRepo()->getWithJoins(
				$filter, $this->getOrderArray(), $this->getPager()->getOffset(), $this->getPager()->getElemPerPage());

		echo json_encode($this->loadDataToView($egyedek, 'partnerlista', $view));
	}

	public function viewlist() {
		$view = $this->createView('partnerlista.tpl');

		$view->setVar('pagetitle', t('Partnerek'));
		$view->setVar('orderselect', $this->getRepo()->getOrdersForTpl());
		$view->setVar('batchesselect', $this->getRepo()->getBatchesForTpl());
		$tcc = new partnercimkekatController($this->params);
		$view->setVar('cimkekat', $tcc->getWithCimkek(null));
		$view->printTemplateResult();
	}

	public function _getkarb($tplname) {
		$id = $this->params->getRequestParam('id', 0);
		$oper = $this->params->getRequestParam('oper', '');
		$view = $this->createView($tplname);

		$view->setVar('pagetitle', t('Partner'));
		$view->setVar('oper', $oper);

		$partner = $this->getRepo()->findWithJoins($id);
		// loadVars utan nem abc sorrendben adja vissza
		$tcc = new partnercimkekatController($this->params);
		$cimkek = $partner ? $partner->getCimkek() : null;
		$view->setVar('cimkekat', $tcc->getWithCimkek($cimkek));
		$fizmod = new fizmodController($this->params);
		$view->setVar('fizmodlist', $fizmod->getSelectList(($partner ? $partner->getFizmodId() : 0)));
		$uk = new uzletkotoController($this->params);
		$view->setVar('uzletkotolist', $uk->getSelectList(($partner ? $partner->getUzletkotoId() : 0)));

		$view->setVar('partner', $this->loadVars($partner));
		$view->printTemplateResult();
	}

	public function getSelectList($selid) {
		$rec = $this->getRepo()->getAll(array(), array('nev' => 'ASC'));
		$res = array();
		foreach ($rec as $sor) {
			$res[] = array(
				'id' => $sor->getId(),
				'caption' => $sor->getNev(),
				'selected' => ($sor->getId() == $selid),
				'fizmod' => $sor->getFizmodId(),
				'fizhatido' => $sor->getFizhatido(),
				'irszam' => $sor->getIrszam(),
				'varos' => $sor->getVaros(),
				'utca' => $sor->getUtca()
			);
		}
		return $res;
	}

	public function checkemail() {
		$email = $this->params->getStringRequestParam('email');
		$ret = array();
		$ret['hibas'] = !\Zend_Validate::is($email, 'EmailAddress');
		if (!$ret['hibas']) {
			if (!$this->params->getBoolRequestParam('dce')) {
				$ret['hibas'] = $this->getRepo()->countByEmail($email) > 0;
				if ($ret['hibas']) {
					$ret['uzenet'] = t('Már létezik ez az emailcím.');
				}
			}
		}
		else {
			$ret['uzenet'] = t('Kérjük emailcímet adjon meg.');
		}
		echo json_encode($ret);
	}

	public function getFiokTpl() {
		$view = $this->getTemplateFactory()->createMainView('fiok.tpl');
		return $view;
	}

	public function getLoginTpl() {
		$view = $this->getTemplateFactory()->createMainView('login.tpl');
		return $view;
	}

	public function login($user, $pass) {
		$users = $this->getRepo()->findByUserPass($user, $pass);
		if (count($users) > 0) {
			$user = $users[0];
			$oldid = \Zend_Session::getId();
			\Zend_Session::regenerateId();
			$user->setSessionid(\Zend_Session::getId());
			$user->setUtolsoklikk();
			$this->getEm()->persist($user);
			$this->getEm()->flush();
			store::getMainSession()->pk = $user->getId();
			$kc = new kosarController($this->params);
			$kc->replaceSessionIdAndAddPartner($oldid, $user);
			$kc->addSessionIdByPartner($user);
			return true;
		}
		return false;
	}

	public function logout() {
		$user = $this->getLoggedInUser();
		if ($user) {
			$user->setSessionid('');
			$this->getEm()->persist($user);
			$this->getEm()->flush();
			$kc = new kosarController($this->params);
			$kc->removeSessionId(\Zend_Session::getId());
			store::destroyMainSession();
		}
	}

	public function autologout() {
		$user = $this->getLoggedInUser();
		if ($user) {
			$ma = new \DateTime();
			$kul = $ma->diff($user->getUtolsoklikk());
			$kulonbseg = floor(($kul->y * 365 * 24 * 60 * 60 + $kul->m * 30 * 24 * 60 * 60 + $kul->d * 24 * 60 * 60 + $kul->h * 60 * 60 + $kul->i * 60 + $kul->s) / 60);
			$perc = store::getParameter(\mkw\consts::Autologoutmin, 10);
			if ($perc <= 0) {
				$perc = 10;
			}
			if ($kulonbseg >= $perc) {
				$this->logout();
				return true;
			}
		}
		return false;
	}

	public function setUtolsoKlikk() {
		$user = $this->getLoggedInUser();
		if ($user) {
			$user->setUtolsoKlikk();
			$this->getEm()->persist($user);
			$this->getEm()->flush();
		}
	}

	public function checkloggedin() {
		if (isset(store::getMainSession()->pk)) {
			$users = $this->getRepo()->findByIdSessionid(store::getMainSession()->pk, \Zend_Session::getId());
			return count($users) == 1;
		}
		return false;
	}

	public function getLoggedInUser() {
		if ($this->checkloggedin()) {
			return $this->getRepo()->find(store::getMainSession()->pk);
		}
		return null;
	}

	public function saveRegistrationData($vezeteknev, $keresztnev, $email, $jelszo, $vendeg = false) {
		$ps = $this->getRepo()->findVendegByEmail($email);
		if (count($ps)>0) {
			$t = $ps[0];
		}
		else {
			$t = new \Entities\Partner();
		}
		$t->setVezeteknev($vezeteknev);
		$t->setKeresztnev($keresztnev);
		$t->setNev($vezeteknev . ' ' . $keresztnev);
		$t->setEmail($email);
		$t->setJelszo($jelszo);
		$t->setVendeg($vendeg);
		$t->setSessionid(\Zend_Session::getId());
		$this->getEm()->persist($t);
		$this->getEm()->flush();
		return $t;
	}

	public function saveRegistration() {
		$hibas = false;
		$hibak = array();
		$vezeteknev = $this->params->getStringRequestParam('vezeteknev');
		$keresztnev = $this->params->getStringRequestParam('keresztnev');
		$email = $this->params->getStringRequestParam('email');
		$jelszo1 = $this->params->getStringRequestParam('jelszo1');
		$jelszo2 = $this->params->getStringRequestParam('jelszo2');
		if (!\Zend_Validate::is($email, 'EmailAddress')) {
			$hibas = true;
			$hibak['email'] = t('Rossz az email');
		}
		if ($jelszo1 !== $jelszo2) {
			$hibas = true;
			$hibak['jelszo'] = t('Rossz a jelszó');
		}
		if ($vezeteknev == '' || $keresztnev == '') {
			$hibas = true;
			$hibak['nev'] = t('Üres a név');
		}
		if (!$hibas) {
			$this->saveRegistrationData($vezeteknev, $keresztnev, $email, $jelszo1);
			$this->login($email, $jelszo1);
			\Zend_Session::writeClose();
			Header('Location: ' . store::getRouter()->generate('showaccount'));
		}
		else {
			$this->showRegistrationForm($vezeteknev, $keresztnev, $email, $hibak);
		}
	}

	public function showRegistrationForm($vezeteknev = '', $keresztnev = '', $email = '', $hibak = array()) {
		$view = $this->getTemplateFactory()->createMainView('regisztracio.tpl');
		$view->setVar('pagetitle', t('Regisztráció') . ' - ' . \mkw\Store::getParameter(\mkw\consts::Oldalcim));
		$view->setVar('vezeteknev', $vezeteknev);
		$view->setVar('keresztnev', $keresztnev);
		$view->setVar('email', $email);
		$view->setVar('hibak', $hibak);
		store::fillTemplate($view);
		$view->printTemplateResult();
	}

	public function showLoginForm() {
		if ($this->checkloggedin()) {
			\Zend_Session::writeClose();
			header('Location: ' . store::getRouter()->generate('showaccount'));
		}
		else {
			$view = $this->getLoginTpl();
			store::fillTemplate($view);
			$view->setVar('pagetitle', t('Bejelentkezés') . ' - ' . \mkw\Store::getParameter(\mkw\consts::Oldalcim));
			$view->setVar('sikertelen', \mkw\Store::getMainSession()->loginerror);
			\mkw\Store::getMainSession()->loginerror = false;
			$view->printTemplateResult();
		}
	}

	public function doLogin() {
		$checkout = $this->params->getStringRequestParam('c') === 'c';
		if ($checkout) {
			$route = store::getRouter()->generate('showcheckout');
		}
		else {
			$route = store::getRouter()->generate('showaccount');
		}
		if ($this->checkloggedin()) {
			\Zend_Session::writeClose();
			header('Location: ' . $route);
		}
		else {
			if ($this->login($this->params->getStringRequestParam('email'), $this->params->getStringRequestParam('jelszo'))) {
				\Zend_Session::writeClose();
				if (!$checkout) {
					$kc = new kosarController($this->params);
					$kc->clear();
				}
				header('Location: ' . $route);
			}
			else {
				if ($checkout) {
					\mkw\Store::getMainSession()->loginerror = true;
					header('Location: ' . store::getRouter()->generate('showcheckout'));
				}
				else {
					\mkw\Store::getMainSession()->loginerror = true;
					header('Location: ' . store::getRouter()->generate('showlogin'));
				}
			}
		}
	}

	public function doLogout() {
		$prevuri = store::getMainSession()->prevuri;
		if (!$prevuri) {
			$prevuri = '/';
		}
		if ($this->checkloggedin()) {
			$this->logout();
		}
		Header('Location: ' . $prevuri);
	}

	public function showAccount() {
		$user = $this->getLoggedInUser();
		if ($user) {
			$view = $this->getFiokTpl();
			store::fillTemplate($view);
			$view->setVar('pagetitle', t('Fiók') . ' - ' . \mkw\Store::getParameter(\mkw\consts::Oldalcim));
			$view->setVar('user', $this->loadVars($user)); // fillTemplate-ben megtortenik
			$tec = new termekertesitoController($this->params);
			$view->setVar('ertesitok', $tec->getAllByPartner($user));
			$view->printTemplateResult();
		}
		else {
			header('Location: ' . store::getRouter()->generate('showlogin'));
		}
	}

	public function saveAccount() {
		$user = $this->getLoggedInUser();
		if ($user) {
			switch ($this->params->getStringParam('subject')) {
				case 'adataim':
					$vezeteknev = $this->params->getStringRequestParam('vezeteknev');
					$keresztnev = $this->params->getStringRequestParam('keresztnev');
					$email = $this->params->getStringRequestParam('email');
					$telefon = $this->params->getStringRequestParam('telefon');
					$akcioshirlevelkell = $this->params->getBoolRequestParam('akcioshirlevelkell');
					$ujdonsaghirlevelkell = $this->params->getBoolRequestParam('ujdonsaghirlevelkell');
					$jax = $this->params->getIntRequestParam('jax', 0);
					if (!\Zend_Validate::is($email, 'EmailAddress')) {
						$hibas = true;
						$hibak['email'] = t('Rossz az email');
					}
					if ($vezeteknev == '' || $keresztnev == '') {
						$hibas = true;
						$hibak['nev'] = t('Üres a név');
					}
					if (!$hibas) {
						$user->setVezeteknev($vezeteknev);
						$user->setKeresztnev($keresztnev);
						$user->setNev($vezeteknev . ' ' . $keresztnev);
						$user->setEmail($email);
						$user->setTelefon($telefon);
						$user->setAkcioshirlevelkell($akcioshirlevelkell);
						$user->setUjdonsaghirlevelkell($ujdonsaghirlevelkell);
						$this->getEm()->persist($user);
						$this->getEm()->flush();
						if (!$jax) {
							Header('Location: ' . store::getRouter()->generate('showaccount'));
						}
					}
					else {
						if ($jax) {
							echo json_encode($hibak);
						}
					}
					break;
				case 'szamlaadatok':
					$user->setNev($this->params->getStringRequestParam('szamlanev'));
					$user->setAdoszam($this->params->getStringRequestParam('adoszam'));
					$user->setIrszam($this->params->getStringRequestParam('szamlairszam'));
					$user->setVaros($this->params->getStringRequestParam('szamlavaros'));
					$user->setUtca($this->params->getStringRequestParam('szamlautca'));
					$this->getEm()->persist($user);
					$this->getEm()->flush();
					if (!$jax) {
						Header('Location: ' . store::getRouter()->generate('showaccount'));
					}
					break;
				case 'szallitasiadatok':
					$user->setSzallnev($this->params->getStringRequestParam('szallnev'));
					$user->setSzallirszam($this->params->getStringRequestParam('szallirszam'));
					$user->setSzallvaros($this->params->getStringRequestParam('szallvaros'));
					$user->setSzallutca($this->params->getStringRequestParam('szallutca'));
					$this->getEm()->persist($user);
					$this->getEm()->flush();
					if (!$jax) {
						Header('Location: ' . store::getRouter()->generate('showaccount'));
					}
					break;
			}
		}
		else {
			header('Location: ' . store::getRouter()->generate('showlogin'));
		}
	}

}