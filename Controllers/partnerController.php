<?php
namespace Controllers;
use Entities\Kontakt;

use matt, matt\Exceptions, SIIKerES\store;

class partnerController extends matt\MattableController {

	public function __construct($generalDataLoader,$actionName=null,$commandString=null) {
		$this->setEntityName('Entities\Partner');
		$this->setEm(store::getEm());
		$this->setTemplateFactory(store::getTemplateFactory());
		$this->setKarbFormTplName('partnerkarbform.tpl');
		$this->setKarbTplName('partnerkarb.tpl');
		$this->setListBodyRowTplName('partnerlista_tbody_tr.tpl');
		$this->setListBodyRowVarName('_partner');
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

	protected function loadVars($t) {
		$kontaktCtrl=new kontaktController($this->generalDataLoader);
		$kont=array();
		$x=array();
		if (!$t) {
			$t=new \Entities\Partner();
			$this->getEm()->detach($t);
		}
		$x['id']=$t->getId();
		$x['nev']=$t->getNev();
		$x['vezeteknev']=$t->getVezeteknev();
		$x['keresztnev']=$t->getKeresztnev();
		$x['inaktiv']=$t->getInaktiv();
		$x['idegenkod']=$t->getIdegenkod();
		$x['adoszam']=$t->getAdoszam();
		$x['euadoszam']=$t->getEuadoszam();
		$x['mukengszam']=$t->getMukengszam();
		$x['jovengszam']=$t->getJovengszam();
		$x['ostermszam']=$t->getOstermszam();
		$x['valligszam']=$t->getValligszam();
		$x['fvmszam']=$t->getFvmszam();
		$x['cjszam']=$t->getCjszam();
		$x['cim']=$t->getCim();
		$x['irszam']=$t->getIrszam();
		$x['varos']=$t->getVaros();
		$x['utca']=$t->getUtca();
		$x['lcim']=$t->getLCim();
		$x['lirszam']=$t->getLirszam();
		$x['lvaros']=$t->getLvaros();
		$x['lutca']=$t->getLutca();
		$x['telefon']=$t->getTelefon();
		$x['mobil']=$t->getMobil();
		$x['fax']=$t->getFax();
		$x['email']=$t->getEmail();
		$x['honlap']=$t->getHonlap();
		$x['megjegyzes']=$t->getMegjegyzes();
		$x['syncid']=$t->getSyncid();
		$x['cimkek']=$t->getCimkenevek();
		$x['fizmodnev']=$t->getFizmodNev();
		$x['uzletkotonev']=$t->getUzletkotoNev();
		foreach($t->getKontaktok() as $kontakt) {
			$kont[]=$kontaktCtrl->loadVars($kontakt);
		}
		//$kont[]=$kontaktCtrl->loadVars(null);
		$x['kontaktok']=$kont;
		$x['fizhatido']=$t->getFizhatido();
		$x['szamlanev']=$t->getSzamlanev();
		$x['szamlairszam']=$t->getSzamlairszam();
		$x['szamlavaros']=$t->getSzamlavaros();
		$x['szamlautca']=$t->getSzamlautca();
		$x['szallnev']=$t->getSzallnev();
		$x['szallirszam']=$t->getSzallirszam();
		$x['szallvaros']=$t->getSzallvaros();
		$x['szallutca']=$t->getSzallutca();
		$x['nem']=$t->getNem();
		$x['szuletesiido']=$t->getSzuletesiido();
		$x['szuletesiidostr']=$t->getSzuletesiidostr();
		$x['akcioshirlevelkell']=$t->getAkcioshirlevelkell();
		$x['ujdonsaghirlevelkell']=$t->getUjdonsaghirlevelkell();
		return $x;
	}

	protected function setFields($obj) {
		try {
			$obj->setNev($this->getStringParam('nev'));
			$obj->setVezeteknev($this->getStringParam('vezeteknev'));
			$obj->setKeresztnev($this->getStringParam('keresztnev'));
			$obj->setInaktiv($this->getBoolParam('inaktiv'));
			$obj->setIdegenkod($this->getStringParam('idegenkod'));
			$obj->setAdoszam($this->getStringParam('adoszam'));
			$obj->setEuadoszam($this->getStringParam('euadoszam'));
			$obj->setMukengszam($this->getStringParam('mukengszam'));
			$obj->setJovengszam($this->getStringParam('jovengszam'));
			$obj->setOstermszam($this->getStringParam('ostermszam'));
			$obj->setValligszam($this->getStringParam('valligszam'));
			$obj->setFvmszam($this->getStringParam('fvmszam'));
			$obj->setCjszam($this->getStringParam('cjszam'));
			$obj->setIrszam($this->getStringParam('irszam'));
			$obj->setVaros($this->getStringParam('varos'));
			$obj->setUtca($this->getStringParam('utca'));
			$obj->setLirszam($this->getStringParam('lirszam'));
			$obj->setLvaros($this->getStringParam('lvaros'));
			$obj->setLutca($this->getStringParam('lutca'));
			$obj->setTelefon($this->getStringParam('telefon'));
			$obj->setMobil($this->getStringParam('mobil'));
			$obj->setFax($this->getStringParam('fax'));
			$obj->setEmail($this->getStringParam('email'));
			$obj->setHonlap($this->getStringParam('honlap'));
			$obj->setMegjegyzes($this->getStringParam('megjegyzes'));
			$obj->setSyncid($this->getStringParam('syncid'));
			$obj->setFizhatido($this->getIntParam('fizhatido'));
			$obj->setSzamlanev($this->getStringParam('szamlanev'));
			$obj->setSzamlairszam($this->getStringParam('szamlairszam'));
			$obj->setSzamlavaros($this->getStringParam('szamlavaros'));
			$obj->setSzamlautca($this->getStringParam('szamlautca'));
			$obj->setSzallnev($this->getStringParam('szallnev'));
			$obj->setSzallirszam($this->getStringParam('szallirszam'));
			$obj->setSzallvaros($this->getStringParam('szallvaros'));
			$obj->setSzallutca($this->getStringParam('szallutca'));
			$obj->setNem($this->getIntParam('nem'));
			$obj->setSzuletesiido($this->getStringParam('szuletesiido'));
			$obj->setAkcioshirlevelkell($this->getBoolParam('akcioshirlevelkell'));
			$obj->setUjdonsaghirlevelkell($this->getBoolParam('ujdonsaghirlevelkell'));
			$fizmod=store::getEm()->getRepository('Entities\Fizmod')->find($this->getIntParam('fizmod',0));
			if ($fizmod) {
				$obj->setFizmod($fizmod);
			}
			$uk=store::getEm()->getRepository('Entities\Uzletkoto')->find($this->getIntParam('uzletkoto',0));
			if ($uk) {
				$obj->setUzletkoto($uk);
			}
			$obj->removeAllCimke();
			$cimkekpar=$this->getArrayParam('cimkek');
			foreach($cimkekpar as $cimkekod) {
				$cimke=$this->getEm()->getRepository('Entities\Partnercimketorzs')->find($cimkekod);
				if ($cimke) {
					$obj->addCimke($cimke);
				}
			}
			$kontaktids=$this->getArrayParam('kontaktid');
			foreach($kontaktids as $kontaktid) {
				if ($this->getStringParam('kontaktnev_'.$kontaktid)!=='') {
					$oper=$this->getStringParam('kontaktoper_'.$kontaktid);
					if ($oper=='add') {
						$k=new Kontakt();
						$k->setNev($this->getStringParam('kontaktnev_'.$kontaktid));
						$k->setTelefon($this->getStringParam('kontakttelefon_'.$kontaktid));
						$k->setMobil($this->getStringParam('kontaktmobil_'.$kontaktid));
						$k->setFax($this->getStringParam('kontaktfax_'.$kontaktid));
						$k->setEmail($this->getStringParam('kontaktemail_'.$kontaktid));
						$k->setHonlap($this->getStringParam('kontakthonlap_'.$kontaktid));
						$k->setMegjegyzes($this->getStringParam('kontaktmegjegyzes_'.$kontaktid));
						$obj->addKontakt($k);
						$this->getEm()->persist($k);
					}
					elseif ($oper=='edit') {
						$k=$this->getEm()->getRepository('Entities\Kontakt')->find($kontaktid);
						if ($k) {
							$k->setNev($this->getStringParam('kontaktnev_'.$kontaktid));
							$k->setTelefon($this->getStringParam('kontakttelefon_'.$kontaktid));
							$k->setMobil($this->getStringParam('kontaktmobil_'.$kontaktid));
							$k->setFax($this->getStringParam('kontaktfax_'.$kontaktid));
							$k->setEmail($this->getStringParam('kontaktemail_'.$kontaktid));
							$k->setHonlap($this->getStringParam('kontakthonlap_'.$kontaktid));
							$k->setMegjegyzes($this->getStringParam('kontaktmegjegyzes_'.$kontaktid));
							$this->getEm()->persist($k);
						}
					}
				}
			}
		}
		catch (matt\Exceptions\WrongValueTypeException $e){
		}
		return $obj;
	}

	protected function getlistbody() {
		$view=$this->createView('partnerlista_tbody.tpl');

		$filter=array();
		if (!is_null($this->getParam('nevfilter',NULL))) {
			$fv=$this->getStringParam('nevfilter');
			$filter['fields'][]=array('nev','nev2');
			$filter['values'][]=$fv;
		}
		if (!is_null($this->getParam('cimkefilter',NULL))) {
			$fv=$this->getArrayParam('cimkefilter');
			$cimkekodok=implode(',',$fv);
			if ($cimkekodok) {
				$q=$this->getEm()->createQuery('SELECT p.id FROM Entities\Partnercimketorzs pc JOIN pc.partnerek p WHERE pc.id IN ('.$cimkekodok.')');
				$res=$q->getScalarResult();
				$cimkefilter=array();
				foreach($res as $sor) {
					$cimkefilter[]=$sor['id'];
				}
				$filter['fields'][]='id';
				$filter['values'][]=$cimkefilter;
			}
		}

		$this->initPager(
			$this->getRepo()->getCount($filter),
			$this->getIntParam('elemperpage',30),
			$this->getIntParam('pageno',1));

		$egyedek=$this->getRepo()->getWithJoins(
			$filter,
			$this->getOrderArray(),
			$this->getPager()->getOffset(),
			$this->getPager()->getElemPerPage());

		echo json_encode($this->loadDataToView($egyedek,'partnerlista',$view));
	}

	protected function viewlist() {
		$view=$this->createView('partnerlista.tpl');

		$view->setVar('pagetitle',t('Partnerek'));
		$view->setVar('orderselect',$this->getRepo()->getOrdersForTpl());
		$view->setVar('batchesselect',$this->getRepo()->getBatchesForTpl());
		$tcc=new partnercimkekatController($this->generalDataLoader);
		$view->setVar('cimkekat',$tcc->getWithCimkek(null));
		$view->printTemplateResult();
	}

	protected function _getkarb($id,$oper,$tplname) {
		$view=$this->createView($tplname);

		$view->setVar('pagetitle',t('Partner'));
		$view->setVar('oper',$oper);

		$partner=$this->getRepo()->findWithJoins($id);
		// loadVars utan nem abc sorrendben adja vissza
		$tcc=new partnercimkekatController($this->generalDataLoader);
		$cimkek=$partner?$partner->getCimkek():null;
		$view->setVar('cimkekat',$tcc->getWithCimkek($cimkek));
		$fizmod=new fizmodController($this->generalDataLoader);
		$view->setVar('fizmodlist',$fizmod->getSelectList(($partner?$partner->getFizmodId():0)));
		$uk=new uzletkotoController($this->generalDataLoader);
		$view->setVar('uzletkotolist',$uk->getSelectList(($partner?$partner->getUzletkotoId():0)));

		$view->setVar('partner',$this->loadVars($partner));
		$view->printTemplateResult();
	}

	public function getSelectList($selid) {
		$rec=$this->getRepo()->getAll(array(),array('nev'=>'ASC'));
		$res=array();
		foreach($rec as $sor) {
			$res[]=array(
				'id'=>$sor->getId(),
				'caption'=>$sor->getNev(),
				'selected'=>($sor->getId()==$selid),
				'fizmod'=>$sor->getFizmodId(),
				'fizhatido'=>$sor->getFizhatido(),
				'irszam'=>$sor->getIrszam(),
				'varos'=>$sor->getVaros(),
				'utca'=>$sor->getUtca()
			);
		}
		return $res;
	}

	public function regisztral() {
		$hibas=false;
		$hibak=array();
		$vezeteknev=$this->getStringParam('vezeteknev');
		$keresztnev=$this->getStringParam('keresztnev');
		$email=$this->getStringParam('email');
		$jelszo1=$this->getStringParam('jelszo1');
		$jelszo2=$this->getStringParam('jelszo2');
		if (!\Zend_Validate::is($email,'EmailAddress')) {
			$hibas=true;
			$hibak['email']=t('Rossz az email');
		}
		if ($jelszo1!==$jelszo2) {
			$hibas=true;
			$hibak['jelszo']=t('Rossz a jelszó');
		}
		if ($vezeteknev==''||$keresztnev=='') {
			$hibas=true;
			$hibak['nev']=t('Üres a név');
		}
		if (!$hibas) {
			$t=new \Entities\Partner();
			$t->setVezeteknev($vezeteknev);
			$t->setKeresztnev($keresztnev);
			$t->setNev($vezeteknev.' '.$keresztnev);
			$t->setEmail($email);
			$t->setJelszo($jelszo1);
			$t->setSessionid(\Zend_Session::getId());
			$this->getEm()->persist($t);
			$this->getEm()->flush();
			$this->login($email,$jelszo1);
			Header('Location: /fiok');
		}
		else {
			$view=$this->getTemplateFactory()->createMainView('regisztracio.tpl');
			$view->setVar('vezeteknev',$vezeteknev);
			$view->setVar('keresztnev',$keresztnev);
			$view->setVar('email',$email);
			$view->setVar('hibak',$hibak);
		}
		return $view;
	}

	public function checkemail() {
		$email=$this->getStringParam('email');
		$ret=array();
		$ret['hibas']=!\Zend_Validate::is($email,'EmailAddress');
		if (!$ret['hibas']) {
			$ret['hibas']=$this->getRepo()->countByEmail($email)>0;
			$ret['uzenet']=t('Már létezik ez az emailcím.');
		}
		else {
			$ret['uzenet']=t('Kérjük emailcímet adjon meg.');
		}
		echo json_encode($ret);
	}

	public function getFiokTpl() {
		$view=$this->getTemplateFactory()->createMainView('fiok.tpl');
		return $view;
	}

	public function getLoginTpl() {
		$view=$this->getTemplateFactory()->createMainView('login.tpl');
		return $view;
	}

	public function login($user,$pass) {
		$users=$this->getRepo()->findByUserPass($user,$pass);
		if (count($users)>0) {
			$user=$users[0];
			$oldid=\Zend_Session::getId();
			\Zend_Session::regenerateId();
			$user->setSessionid(\Zend_Session::getId());
			$user->setUtolsoklikk();
			$this->getEm()->persist($user);
			$this->getEm()->flush();
			store::getMainSession()->pk=$user->getId();
			$kc=new kosarController($this->generalDataLoader);
			$kc->replaceSessionIdAndAddPartner($oldid,$user);
			$kc->addSessionIdByPartner($user);
			return true;
		}
		return false;
	}

	public function logout() {
		$user=$this->getLoggedInUser();
		if ($user) {
			$user->setSessionid('');
			$this->getEm()->persist($user);
			$this->getEm()->flush();
			$kc=new kosarController($this->generalDataLoader);
			$kc->removeSessionId(\Zend_Session::getId());
			store::destroyMainSession();
		}
	}

	public function autologout() {
		$user=$this->getLoggedInUser();
		if ($user) {
			$ma=new \DateTime();
			$kul=$ma->diff($user->getUtolsoklikk());
			$kulonbseg=floor(($kul->y*365*24*60*60+$kul->m*30*24*60*60+$kul->d*24*60*60+$kul->h*60*60+$kul->i*60+$kul->s)/60);
			if ($kulonbseg>=store::getParameter('autologoutmin',10)) {
				$this->logout();
				return true;
			}
		}
		return false;
	}

	public function setUtolsoKlikk() {
		$user=$this->getLoggedInUser();
		if ($user) {
			$user->setUtolsoKlikk();
			$this->getEm()->persist($user);
			$this->getEm()->flush();
		}
	}

	public function checkloggedin() {
		if (isset(store::getMainSession()->pk)) {
			$users=$this->getRepo()->findByIdSessionid(store::getMainSession()->pk,\Zend_Session::getId());
			return count($users)==1;
		}
		return false;
	}

	public function getLoggedInUser() {
		if ($this->checkloggedin()) {
			return $this->getRepo()->find(store::getMainSession()->pk);
		}
		return null;
	}
}