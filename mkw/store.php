<?php
namespace mkw;
use Doctrine\ORM\EntityManager;

class Store {

	private static $em;
	private static $config;
	private static $setup;
	private static $mainsession;
	private static $adminsession;
	private static $templateFactory;
	private static $router;
	private static $gdl;
	private static $sanitizer;
	public static $DateFormat='Y.m.d';

	public static function writelog($text) {
		$handle = fopen("log.txt", "a");
		$log = "";
		$separator = " ## ";
		$log.=date('Y.m.d. H:i:s') . $separator;
		$log.=$text;
		$log.="\n";
		fwrite($handle, $log);
		fclose($handle);
	}

	/**
	 * @return \Doctrine\ORM\EntityManager
	 */
	public static function getEm() {
		return self::$em;
	}

	public static function setEm($em) {
		self::$em=$em;
	}

	public static function getConfig() {
		return self::$config;
	}

	public static function getConfigValue($key) {
		return self::$config[$key];
	}

	public static function setConfig($config) {
		self::$config=$config;
	}

	public static function getSetup() {
		return self::$setup;
	}

	public static function getSetupValue($key) {
		return self::$setup[$key];
	}

	public static function setSetup($setup) {
		self::$setup=$setup;
	}

	public static function getParameter($par,$default=null) {
		$p=self::getEm()->getRepository('Entities\Parameterek')->find($par);
		if ($p) {
			return $p->getErtek();
		}
		else {
			return $default;
		}
	}

	public static function setParameter($par,$ertek) {
		$p=self::getEm()->getRepository('Entities\Parameterek')->find($par);
		if ($p) {
			$p->setErtek($ertek);
		}
		else {
			$p=new \Entities\Parameterek();
			$p->setId($par);
			$p->setErtek($ertek);
		}
		self::getEm()->persist($p);
		self::getEm()->flush();
	}

	/* TODO ezen lehet h. csiszolni kell meg */
	public static function convDate($DateString) {
		return str_replace('.','-',$DateString);
	}

	public static function createUID() {
		return str_replace('.','',microtime(true));
	}

	public static function createSmallImageUrl($kepurl,$pre='/') {
		$t=explode('.',$kepurl);
		$ext=array_pop($t);
		$result=implode('.',$t).store::getParameter('smallimgpost','').'.'.$ext;
		if ($kepurl&&$kepurl[0]!=$pre) {
			return $pre.$result;
		}
		return $result;
	}

	public static function createMediumImageUrl($kepurl,$pre='/') {
		$t=explode('.',$kepurl);
		$ext=array_pop($t);
		$result=implode('.',$t).store::getParameter('mediumimgpost','').'.'.$ext;
		if ($kepurl&&$kepurl[0]!=$pre) {
			return $pre.$result;
		}
		return $result;
	}

	public static function createBigImageUrl($kepurl,$pre='/') {
		$t=explode('.',$kepurl);
		$ext=array_pop($t);
		$result=implode('.',$t).store::getParameter('bigimgpost','').'.'.$ext;
		if ($kepurl&&$kepurl[0]!=$pre) {
			return $pre.$result;
		}
		return $result;
	}

	/**
	 *
	 * @return Zend_Session_Namespace
	 */
	public static function getMainSession() {
		if (!isset(self::$mainsession)) {
			self::$mainsession=new \Zend_Session_Namespace();
		}
		if (!isset(self::$mainsession->initialized)) {
			\Zend_Session::regenerateId();
			self::$mainsession->initialized=true;
		}
		return self::$mainsession;
	}

	public static function destroyMainSession() {
		if (isset(self::$mainsession)) {
			\Zend_Session::namespaceUnset('');
			\Zend_Session::destroy(true);
			self::$mainsession=null;
		}
	}

	/**
	 *
	 * @return \Zend_Session_Namespace
	 */
	public static function getAdminSession() {
		if (!isset(self::$adminsession)) {
			self::$adminsession=new \Zend_Session_Namespace('a');
		}
		if (!isset(self::$adminsession->initialized)) {
			\Zend_Session::regenerateId();
			self::$adminsession->initialized=true;
		}
		return self::$adminsession;
	}

	public static function getSalt() {
		return self::getConfigValue('so');
	}

	/**
	 *
	 * @return \mkwhelpers\TemplateFactory
	 */
	public static function getTemplateFactory() {
		if (!isset(self::$templateFactory)) {
			self::$templateFactory=new \mkwhelpers\TemplateFactory(self::$config);
		}
		return self::$templateFactory;
	}

	public static function fillTemplate($v) {
		$tf=new \Controllers\termekfaController(null);
		$v->setVar('seodescription',self::getParameter('seodescription'));
		$v->setVar('feedtermektitle',self::getParameter('feedtermektitle',t('Termékeink')));
		$v->setVar('feedhirtitle',self::getParameter('feedhirtitle',t('Híreink')));
		$v->setVar('menu1',$tf->getformenu(1,self::getSetupValue('almenunum')));
		$kc=new \Controllers\kosarController(null);
		$v->setVar('kosar',$kc->getMiniData());
		$pc=new \Controllers\partnerController(null);
		$user=array();
		$user['loggedin']=$pc->checkloggedin();
		if ($user['loggedin']) {
			$u=$pc->getLoggedInUser();
			$user['nev']=$u->getNev();
			$user['email']=$u->getEmail();
			$user['vezeteknev']=$u->getVezeteknev();
			$user['keresztnev']=$u->getKeresztnev();
			$user['telefon']=$u->getTelefon();
			$user['szamlanev']=$u->getSzamlanev();
			$user['szamlairszam']=$u->getSzamlairszam();
			$user['szamlavaros']=$u->getSzamlavaros();
			$user['szamlautca']=$u->getSzamlautca();
			$user['szamlaadoszam']=$u->getSzamlaadoszam();
			$user['szallnev']=$u->getSzallnev();
			$user['szallirszam']=$u->getSzallirszam();
			$user['szallvaros']=$u->getSzallvaros();
			$user['szallutca']=$u->getSzallutca();
			$user['szalladategyezik']=!$u->getSzallnev() &&
					!$u->getSzallirszam() &&
					!$u->getSzallvaros() &&
					!$u->getSzallutca() &&
					!$u->getSzallnev();
		}
		else {
			$user['szalladategyezik']=true;
		}
		$v->setVar('user',$user);
		$rut = self::getRouter();
		$v->setVar('showloginlink',$rut->generate('showlogin'));
		$v->setVar('showregisztraciolink',$rut->generate('showregistration'));
		$v->setVar('showaccountlink',$rut->generate('showaccount'));
		$v->setVar('dologoutlink',$rut->generate('dologout'));
		$v->setVar('kosargetlink',$rut->generate('kosarget'));
		$v->setVar('showcheckoutlink',$rut->generate('showcheckout'));
		$v->setVar('prevuri',self::getMainSession()->prevuri ? self::getMainSession()->prevuri : '/');
	}

	/**
	 *
	 * @return \AltoRouter
	 */
	public static function getRouter() {
		if (!isset(self::$router)) {
			self::$router=new \AltoRouter();
		}
		return self::$router;
	}

	public static function getGdl() {
		if (!isset(self::$gdl)) {
			self::$gdl=new generalDataLoader();
		}
		return self::$gdl;
	}

	/**
	 *
	 * @return \mkwhelpers\HtmlPurifierSanitizer
	 */
	public static function getSanitizer() {
		if (!isset(self::$sanitizer)) {
			self::$sanitizer=new \mkwhelpers\HtmlPurifierSanitizer();
		}
		return self::$sanitizer;
	}

	public static function storePrevUri() {
		store::getMainSession()->prevuri=$_SERVER['REQUEST_URI'];
	}

	public static function redirectTo404($keresendo,$params) {
		$view=self::getTemplateFactory()->createMainView('404.tpl');
		$tc=new \Controllers\termekController($params);
		$view->setVar('ajanlotttermekek',$tc->getAjanlottLista());
		store::fillTemplate($view);
		$view->setVar('seodescription',t('Sajnos nem találjuk: ').$keresendo);
		$view->setVar('pagetitle',t('Sajnos nem találjuk: ').$keresendo);
		$view->printTemplateResult();
	}

}