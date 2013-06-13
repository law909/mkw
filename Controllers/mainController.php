<?php
namespace Controllers;
use matt, matt\Exceptions, mkw\store;

class mainController extends matt\Controller {

	private $view;

	public function __construct($generalDataLoader,$actionName=null,$commandString=null) {
		$this->setTemplateFactory(store::getTemplateFactory());
		parent::__construct($generalDataLoader,$actionName,$commandString);
	}

	public function handleRequest() {
		store::getMainSession();
		$pc=new partnerController($this->generalDataLoader);
		if ($pc->checkloggedin()) {
			$prevuri=$_SERVER['REQUEST_URI'];
			if (!$prevuri) {
				$prevuri='/';
			}
			if ($pc->autoLogout()) {
				header('Location: '.$prevuri);
			}
			else {
				$pc->setUtolsoKlikk();
			}
		}
		$methodname=$this->getActionName();
		if ($methodname=='') {
			$methodname='view';
		}
		if ($this->mainMethodExists(__CLASS__,$methodname)) {
			$this->$methodname($this->getCommandString());
		}
		else {
			$this->setControllerName($this->getActionName());
			if ($this->controllerExists($this->getControllerName())) {
				try {
//					write_log('<Request>='.$_SERVER['REQUEST_URI']);
					$pieces=explode(matt\URLCommandSeparator,$this->getCommandString());
					$this->setActionName(array_shift($pieces));
					$this->setCommandString(implode(matt\URLCommandSeparator,$pieces));
					$c=$this->loadController($this->getControllerName());
					$c->handleRequest();
					$this->storePrevUri();
				}
				catch (matt\Exceptions\UnknownMethodException $e) {
					$this->redirectTo404($methodname);
				}
			}
			else {
				$this->redirectTo404($methodname);
			}
		}
	}

	private function storePrevUri() {
		store::getMainSession()->prevuri=$_SERVER['REQUEST_URI'];
	}

	protected function redirectTo404($keresendo) {
		$this->view=$this->getTemplateFactory()->createMainView('404.tpl');
		$tc=new termekController($this->generalDataLoader);
		$this->view->setVar('ajanlotttermekek',$tc->getAjanlottLista());
		$this->fillTemplate();
		$this->view->setVar('seodescription',t('Sajnos nem találjuk: ').$keresendo);
		$this->view->setVar('pagetitle',t('Sajnos nem találjuk: ').$keresendo);
		$this->view->printTemplateResult();
	}

	protected function fillTemplate($v=null) {
		if (!$v) {
			$v=$this->view;
		}
		store::fillTemplate($v);
	}

	protected function createTermekfaParams() {
		return array(
			'elemperpage'=>$this->getIntParam('elemperpage',20),
			'pageno'=>$this->getIntParam('pageno',1),
			'order'=>$this->getStringParam('order','ardesc'),
			'filter'=>$this->getStringParam('filter',''),
			'klikkeltcimkekatid'=>$this->getIntParam('cimkekatid'),
			'arfilter'=>$this->getStringParam('arfilter',''),
			'keresett'=>$this->getStringParam('keresett',''),
			'vt'=>$this->getIntParam('vt',1)
		);
	}

	public function view() {
		$this->view=$this->getTemplateFactory()->createMainView('main.tpl');
		$this->fillTemplate();
		$hc=new hirController($this->generalDataLoader);
		$tc=new termekController($this->generalDataLoader);
		$khc=new korhintaController($this->generalDataLoader);
		$tfc=new termekfaController($this->generalDataLoader);
		$this->view->setVar('hirek',$hc->gethirlist());
		$this->view->setVar('ajanlotttermekek',$tc->getAjanlottLista());
		$this->view->setVar('legnepszerubbtermekek',$tc->getLegnepszerubbLista());
		$this->view->setVar('korhintalista',$khc->getLista());
		$this->view->setVar('topkategorialista',$tfc->getformenu(store::getSetupValue('topkategoriamenunum',3),0));
		$this->storePrevUri();
		$this->view->printTemplateResult();
	}

	public function termekfa($com) {
		$tf=new termekfaController($this->generalDataLoader);
		$ag=$tf->getRepo()->findOneBySlug($com);
		if ($ag) {
			if (count($ag->getChildren())>0) {
				$this->view=$this->getTemplateFactory()->createMainView('katlista.tpl');
				$t=$tf->getkatlista($ag);
			}
			else {
				$this->view=$this->getTemplateFactory()->createMainView('termeklista.tpl');
				$t=$tf->gettermeklistaforparent($ag,$this->createTermekfaParams());
			}
			foreach($t as $k=>$v) {
				$this->view->setVar($k,$v);
			}
			$this->fillTemplate();
			$this->view->setVar('pagetitle',$ag->getOldalcim());
			$this->view->setVar('seodescription',$ag->getSeodescription());
			$this->view->setVar('seokeywords',$ag->getSeokeywords());
			$this->storePrevUri();
			$this->view->printTemplateResult();
		}
		else {
			$this->redirectTo404($com);
		}
	}

	public function kereses($com) {
		$term=trim($this->getStringParam('term'));
		if ($term) {
			$r=store::getEm()->getRepository('\Entities\Termek');
			$res=$r->getNevek($term);
			echo json_encode($res);
		}
		else {
			$this->storePrevUri();
			$keresoszo=trim($this->getStringParam('keresett'));
			if ($keresoszo!='') {
				$log=new \Entities\Keresoszolog($keresoszo);
				store::getEm()->persist($log);
				store::getEm()->flush();

				$tf=new termekfaController($this->generalDataLoader);
				$t=$tf->gettermeklistaforparent(null,$this->createTermekfaParams());

				if ($t['lapozo']['elemcount']>0) {
					$this->view=$this->getTemplateFactory()->createMainView('termeklista.tpl');
					foreach($t as $k=>$v) {
						$this->view->setVar($k,$v);
					}
				}
				else {
					$this->view=$this->getTemplateFactory()->createMainView('nincstalalat.tpl');
					$tc=new termekController($this->generalDataLoader);
					$this->view->setVar('ajanlotttermekek',$tc->getAjanlottLista());
				}
				$this->fillTemplate();
				$this->view->setVar('seodescription',t('A keresett kifejezés: ').$keresoszo);
				$this->view->setVar('pagetitle',t('A keresett kifejezés: ').$keresoszo);
				$this->view->printTemplateResult();
			}
			else {
				$this->view=$this->getTemplateFactory()->createMainView('nincstalalat.tpl');
				$tc=new termekController($this->generalDataLoader);
				$this->view->setVar('ajanlotttermekek',$tc->getAjanlottLista());
				$this->fillTemplate();
				$this->view->setVar('seodescription',t('Keressen valamit.'));
				$this->view->setVar('pagetitle',t('Keressen valamit.'));
				$this->view->printTemplateResult();
			}
		}
	}

	public function termek($com) {
		$tc=new termekController($this->generalDataLoader);
		$termek=$tc->getRepo()->findOneBySlug($com);
		if ($termek) {
			$this->view=$this->getTemplateFactory()->createMainView('termeklap.tpl');
			$this->fillTemplate();
			$this->view->setVar('pagetitle',$termek->getOldalcim());
			$this->view->setVar('seodescription',$termek->getSeodescription());
			$this->view->setVar('seokeywords',$termek->getSeokeywords());
			$t=$tc->getTermekLap($termek);
			foreach($t as $k=>$v) {
				$this->view->setVar($k,$v);
			}
			$this->storePrevUri();
			$this->view->printTemplateResult();
		}
		else {
			$this->redirectTo404($com);
		}
	}

	public function regisztracio($com) {
		$pc=new partnerController($this->generalDataLoader);
		switch ($com) {
			case 'ment':
				$view=$pc->regisztral();
				$this->fillTemplate($view);
				$view->printTemplateResult();
				break;
			default :
				$this->view=$this->getTemplateFactory()->createMainView('regisztracio.tpl');
				$this->fillTemplate();
				$this->storePrevUri();
				$this->view->printTemplateResult();
				break;
		}
	}

	public function fiok($com) {
		$pc=new partnerController($this->generalDataLoader);
		if ($pc->checkloggedin()) {
			switch ($com) {
				case 'ment':
					break;
				default :
					$view=$pc->getFiokTpl();
					$this->fillTemplate($view);
					$this->storePrevUri();
					$view->printTemplateResult();
					break;
			}
		}
		else {
			header('Location: /login');
		}
	}

	public function login($com) {
		$pc=new partnerController($this->generalDataLoader);
		if ($pc->checkloggedin()) {
			header('Location: /fiok');
		}
		else {
			switch ($com) {
				case 'ment':
					if ($pc->login($this->getStringParam('email'),$this->getStringParam('jelszo'))) {
						header('Location: /fiok');
					}
					else {
						$view=$pc->getLoginTpl();
						$this->fillTemplate($view);
						$view->setVar('sikertelen',true);
						$view->printTemplateResult();
					}
					break;
				default :
					$view=$pc->getLoginTpl();
					$this->fillTemplate($view);
					$this->storePrevUri();
					$view->printTemplateResult();
					break;
			}
		}
	}

	public function logout($com) {
		$pc=new partnerController($this->generalDataLoader);
		$prevuri=store::getMainSession()->prevuri;
		if (!$prevuri) {
			$prevuri='/';
		}
		if ($pc->checkloggedin()) {
			$pc->logout();
		}
		Header('Location: '.$prevuri);
	}

	public function checkemail($com) {
		$email=$this->getStringParam('email');
		$ret['hibas']=!\Zend_Validate::is($email,'EmailAddress');
		if ($ret['hibas']) {
			$ret['uzenet']=t('Kérjük emailcímet adjon meg.');
		}
		else {
			$ret['uzenet']='';
		}
		echo json_encode($ret);
	}

	public function statlap($com) {
		$sc=new statlapController($this->generalDataLoader);
		$statlap=$sc->getRepo()->findOneBySlug($com);
		if ($statlap) {
			$this->view=$this->getTemplateFactory()->createMainView('statlap.tpl');
			$this->fillTemplate();
			$this->view->setVar('pagetitle',$statlap->getOldalcim());
			$this->view->setVar('seodescription',$statlap->getSeodescription());
			$this->view->setVar('seokeywords',$statlap->getSeokeywords());
			$this->view->setVar('statlap',$sc->getstatlap($statlap));
			$this->storePrevUri();
			$this->view->printTemplateResult();
		}
		else {
			$this->redirectTo404($com);
		}
	}

	public function hir($com) {
		$sc=new hirController($this->generalDataLoader);
		$hir=$sc->getRepo()->findOneBySlug($com);
		if ($hir) {
			$this->view=$this->getTemplateFactory()->createMainView('hir.tpl');
			$this->fillTemplate();
			$this->view->setVar('pagetitle',$hir->getCim());
			$this->view->setVar('seodescription',$hir->getSeodescription());
			$this->view->setVar('seokeywords',$hir->getSeokeywords());
			$this->view->setVar('hir',$hir->convertToArray());
			$this->storePrevUri();
			$this->view->printTemplateResult();
		}
		else {
			$this->redirectTo404($com);
		}
	}

	public function feed($com) {
		$feedview=$this->getTemplateFactory()->createMainView('feed.tpl');
		switch ($com) {
			case 'hir':
				$sc=new hirController($this->generalDataLoader);
				$feedview->setVar('title',store::getParameter('feedhirtitle',t('Híreink')));
				$feedview->setVar('link','http://'.$_SERVER['SERVER_NAME'].'/feed/hir');
				$d=new \DateTime();
				$feedview->setVar('pubdate',$d->format('D, d M Y H:i:s'));
				$feedview->setVar('lastbuilddate',$d->format('D, d M Y H:i:s'));
				$feedview->setVar('description',store::getParameter('feedhirdescription',''));
				$entries=array();
				$hirek=$sc->getfeedhirlist();
				foreach($hirek as $hir) {
					$entries[]=array(
						'title'=>$hir->getCim(),
						'link'=>'http://'.$_SERVER['SERVER_NAME'].'/hir/'.$hir->getSlug(),
						'guid'=>'http://'.$_SERVER['SERVER_NAME'].'/hir/'.$hir->getSlug(),
						'description'=>$hir->getSzoveg(),
						'pubdate'=>$hir->getDatum()->format('D, d M Y H:i:s')
					);
				}
				$feedview->setVar('entries',$entries);
				header('Content-type: text/xml');
				$feedview->printTemplateResult();
				break;
			case 'termek':
				$tc=new termekController($this->generalDataLoader);
				$view=$this->getTemplateFactory()->createMainView('termekfeed.tpl');
				$feedview->setVar('title',store::getParameter('feedtermektitle',t('Termékeink')));
				$feedview->setVar('link','http://'.$_SERVER['HTTP_HOST'].'/feed/termek');
				$d=new \DateTime();
				$feedview->setVar('pubdate',$d->format('D, d M Y H:i:s'));
				$feedview->setVar('lastbuilddate',$d->format('D, d M Y H:i:s'));
				$feedview->setVar('description',store::getParameter('feedtermekdescription',''));
				$entries=array();
				$termekek=$tc->getfeedtermeklist();
				foreach($termekek as $termek) {
					$view->setVar('kepurl',$termek->getKepUrlSmall());
					$view->setVar('szoveg',$termek->getRovidLeiras());
					$view->setVar('url','http://'.$_SERVER['HTTP_HOST'].'/termek/'.$termek->getSlug());
					$entries[]=array(
						'title'=>$termek->getNev(),
						'link'=>'http://'.$_SERVER['HTTP_HOST'].'/termek/'.$termek->getSlug(),
						'guid'=>'http://'.$_SERVER['HTTP_HOST'].'/termek/'.$termek->getSlug(),
						'description'=>$view->getTemplateResult(),
						'pubdate'=>$d->format('D, d M Y H:i:s')
					);
				}
				$feedview->setVar('entries',$entries);
				header('Content-type: text/xml');
				$feedview->printTemplateResult();
				break;
		}
	}

	public function valtozatar($com) {
		$termekid=$this->getIntParam('t');
		$valtozatid=$this->getIntParam('vid');
		$termek=store::getEm()->getRepository('Entities\Termek')->find($termekid);
		$valtozat=store::getEm()->getRepository('Entities\TermekValtozat')->find($valtozatid);
		$ret=array();
		$ret['price']=number_format($termek->getBruttoAr($valtozat),0,',',' ').' Ft';
		echo json_encode($ret);
	}

	public function valtozat($com) {
		$termekkod=$this->getIntParam('t');
		$tipusid=$this->getIntParam('ti');
		$valtozatertek=$this->getParam('v');
		$masiktipusid=$this->getIntParam('mti');
		$masikselected=$this->getParam('sel');
		$ret=array();

		$tc=new termekController($this->generalDataLoader);
		$termek=$tc->getRepo()->find($termekkod);

		if ($masiktipusid) {
			$t=array($tipusid,$masiktipusid);
			$e=array($valtozatertek,$masikselected);
		}
		else {
			$t=array($tipusid);
			$e=array($valtozatertek);
		}
		$termekvaltozat=store::getEm()->getRepository('Entities\TermekValtozat')->getByProperties($termek->getId(),$t,$e);
		$ret['price']=number_format($termek->getBruttoAr($termekvaltozat),0,',',' ').' Ft';

		$valtozatok=$termek->getValtozatok();
		foreach($valtozatok as $valtozat) {
			if ($valtozat->getAdatTipus1Id()==$tipusid&&($valtozat->getErtek1()==$valtozatertek||!$valtozatertek)) {
				$ret['adat'][$valtozat->getErtek2()]=array('value'=>$valtozat->getErtek2(),'sel'=>$masikselected==$valtozat->getErtek2());
			}
			elseif ($valtozat->getAdatTipus2Id()==$tipusid&&($valtozat->getErtek2()==$valtozatertek||!$valtozatertek)) {
				$ret['adat'][$valtozat->getErtek1()]=array('value'=>$valtozat->getErtek1(),'sel'=>$masikselected==$valtozat->getErtek1());
			}
		}
		echo json_encode($ret);
	}

	public function kapcsolat($com) {
		switch ($com) {
			case 'ment':
				$hibas=false;
				$hibak=array();
				$nev=$this->getStringParam('nev');
				$email1=$this->getStringParam('email1');
				$email2=$this->getStringParam('email2');
				$telefon=$this->getStringParam('telefon');
				$rendelesszam=$this->getStringParam('rendelesszam');
				$tema=$this->getStringParam('tema');
				$szoveg=$this->getStringParam('szoveg');
				if (!\Zend_Validate::is($email1,'EmailAddress')||!\Zend_Validate::is($email2,'EmailAddress')) {
					$hibas=true;
					$hibak['email']=t('Rossz az email');
				}
				if ($email1!==$email2) {
					$hibas=true;
					$hibak['email']=t('Nem egyezik a két emailcím');
				}
				if ($nev=='') {
					$hibas=true;
					$hibak['nev']=t('Üres a név');
				}
				if ($tema=='') {
					$hibas=true;
					$hibak['tema']=t('Nincs megadva téma');
				}
				if (!$hibas) {
					$view=$this->getTemplateFactory()->createMainView('kapcsolatkosz.tpl');
					$this->fillTemplate($view);
					$this->storePrevUri();
				}
				else {
					$kftc=new kapcsolatfelveteltemaController($this->generalDataLoader);
					$view=$this->getTemplateFactory()->createMainView('kapcsolat.tpl');
					$view->setVar('nev',$nev);
					$view->setVar('email1',$email1);
					$view->setVar('email2',$email2);
					$view->setVar('telefon',$telefon);
					$view->setVar('rendelesszam',$rendelesszam);
					$view->setVar('temalista',$kftc->getSelectList($tema));
					$view->setVar('szoveg',$szoveg);
					$view->setVar('hibak',$hibak);
				}
				$view->printTemplateResult();
				break;
			default :
				$kftc=new kapcsolatfelveteltemaController($this->generalDataLoader);
				$this->view=$this->getTemplateFactory()->createMainView('kapcsolat.tpl');
				$this->fillTemplate();
				$this->view->setVar('temalista',$kftc->getSelectList(0));
				$this->storePrevUri();
				$this->view->printTemplateResult();
				break;
		}
	}
}