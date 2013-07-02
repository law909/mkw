<?php
namespace Controllers;
use mkw\store;

class setupController extends \mkwhelpers\Controller {

	public function __construct($params) {
		$this->entityName='Entities\Parameterek';
		parent::__construct($params);
	}

	public function view() {
		$repo=store::getEm()->getRepository($this->entityName);
		$view=$this->createView('setup.tpl');
		$view->setVar('pagetitle',t('Beállítások'));

		// tulaj
		$p=$repo->find('tulajnev');
		$view->setVar('tulajnev',($p?$p->getErtek():''));
		$p=$repo->find('tulajirszam');
		$view->setVar('tulajirszam',($p?$p->getErtek():''));
		$p=$repo->find('tulajvaros');
		$view->setVar('tulajvaros',($p?$p->getErtek():''));
		$p=$repo->find('tulajutca');
		$view->setVar('tulajutca',($p?$p->getErtek():''));
		$p=$repo->find('tulajadoszam');
		$view->setVar('tulajadoszam',($p?$p->getErtek():''));
		$p=$repo->find('tulajeuadoszam');
		$view->setVar('tulajeuadoszam',($p?$p->getErtek():''));

		// web
		$p=$repo->find('oldalcim');
		$view->setVar('oldalcim',($p?$p->getErtek():''));
		$p=$repo->find('seodescription');
		$view->setVar('seodescription',($p?$p->getErtek():''));

		$p=$repo->find('termekoldalcim');
		$view->setVar('termekoldalcim',($p?$p->getErtek():''));
		$p=$repo->find('termekseodescription');
		$view->setVar('termekseodescription',($p?$p->getErtek():''));

		$p=$repo->find('katoldalcim');
		$view->setVar('katoldalcim',($p?$p->getErtek():''));
		$p=$repo->find('katseodescription');
		$view->setVar('katseodescription',($p?$p->getErtek():''));

		$p=$repo->find('smallimagesize');
		$view->setVar('smallimagesize',($p?$p->getErtek():80));
		$p=$repo->find('mediumimagesize');
		$view->setVar('mediumimagesize',($p?$p->getErtek():200));
		$p=$repo->find('bigimagesize');
		$view->setVar('bigimagesize',($p?$p->getErtek():800));
		$p=$repo->find('korhintaimagesize');
		$view->setVar('korhintaimagesize',($p?$p->getErtek():480));
		$p=$repo->find('jpgquality');
		$view->setVar('jpgquality',($p?$p->getErtek():90));
		$p=$repo->find('pngquality');
		$view->setVar('pngquality',($p?$p->getErtek():9));
		$p=$repo->find('smallimgpost');
		$view->setVar('smallimgpost',($p?$p->getErtek():'_s'));
		$p=$repo->find('mediumimgpost');
		$view->setVar('mediumimgpost',($p?$p->getErtek():'_m'));
		$p=$repo->find('bigimgpost');
		$view->setVar('bigimgpost',($p?$p->getErtek():'_b'));
		$p=$repo->find('fooldalajanlotttermekdb');
		$view->setVar('fooldalajanlotttermekdb',($p?$p->getErtek():6));
		$p=$repo->find('fooldalhirdb');
		$view->setVar('fooldalhirdb',($p?$p->getErtek():5));
		$p=$repo->find('fooldalnepszerutermekdb');
		$view->setVar('fooldalnepszerutermekdb',($p?$p->getErtek():6));
		$p=$repo->find('arfilterstep');
		$view->setVar('arfilterstep',($p?$p->getErtek():500));
		$p=$repo->find('kiemelttermekdb');
		$view->setVar('kiemelttermekdb',($p?$p->getErtek():3));
		$p=$repo->find('autologoutmin');
		$view->setVar('autologoutmin',($p?$p->getErtek():10));

		// alapertelmezes
		$p=$repo->find('fizmod');
		$fizmod=new fizmodController($this->params);
		$view->setVar('fizmodlist',$fizmod->getSelectList(($p?$p->getErtek():0)));

		$p=$repo->find('raktar');
		$raktar=new raktarController($this->params);
		$view->setVar('raktarlist',$raktar->getSelectList(($p?$p->getErtek():0)));

		$p=$repo->find('valutanem');
		$valutanem=new valutanemController($this->params);
		$view->setVar('valutanemlist',$valutanem->getSelectList(($p?$p->getErtek():0)));

		$p=$repo->find('esedekessegalap');
		$view->setVar('esedekessegalap',($p?$p->getErtek():'1'));

		// feed
		$p=$repo->find('feedhirdb');
		$view->setVar('feedhirdb',($p?$p->getErtek():20));
		$p=$repo->find('feedhirtitle');
		$view->setVar('feedhirtitle',($p?$p->getErtek():t('Híreink')));
		$p=$repo->find('feedhirDescription');
		$view->setVar('feedhirdescription',($p?$p->getErtek():t('Híreink')));
		$p=$repo->find('feedtermekdb');
		$view->setVar('feedtermekdb',($p?$p->getErtek():30));
		$p=$repo->find('feedtermektitle');
		$view->setVar('feedtermektitle',($p?$p->getErtek():t('Termékeink')));
		$p=$repo->find('feedtermekdescription');
		$view->setVar('feedtermekdescription',($p?$p->getErtek():t('Termékeink')));

		// sitemap
		$p=$repo->find('statlapprior');
		$view->setVar('statlapprior',($p?$p->getErtek():0.4));
		$p=$repo->find('termekprior');
		$view->setVar('termekprior',($p?$p->getErtek():0.5));
		$p=$repo->find('kategoriaprior');
		$view->setVar('kategoriaprior',($p?$p->getErtek():0.7));
		$p=$repo->find('fooldalprior');
		$view->setVar('fooldalprior',($p?$p->getErtek():1));
		$p=$repo->find('statlapchangefreq');
		$view->setVar('statlapchangefreq',($p?$p->getErtek():'monthly'));
		$p=$repo->find('termekchangefreq');
		$view->setVar('termekchangefreq',($p?$p->getErtek():'monthly'));
		$p=$repo->find('kategoriachangefreq');
		$view->setVar('kategoriachangefreq',($p?$p->getErtek():'daily'));
		$p=$repo->find('fooldalchangefreq');
		$view->setVar('fooldalchangefreq',($p?$p->getErtek():'daily'));

		$view->printTemplateResult();
	}

	private function setObj($par,$value) {
		$en=$this->entityName;
		$p=store::getEm()->getRepository($en)->find($par);
		if ($p) {
			$p->setErtek($value);
		}
		else {
			$p=new $en();
			$p->setId($par);
			$p->setErtek($value);
		}
		store::getEm()->persist($p);
	}

	public function save() {
		// tulaj
		$this->setObj('tulajnev',$this->params->getStringRequestParam('tulajnev'));
		$this->setObj('tulajirszam',$this->params->getStringRequestParam('tulajirszam'));
		$this->setObj('tulajvaros',$this->params->getStringRequestParam('tulajvaros'));
		$this->setObj('tulajutca',$this->params->getStringRequestParam('tulajutca'));
		$this->setObj('tulajadoszam',$this->params->getStringRequestParam('tulajadoszam'));
		$this->setObj('tulajeuadoszam',$this->params->getStringRequestParam('tulajeuadoszam'));
		// web
		$this->setObj('smallimagesize',$this->params->getIntRequestParam('smallimagesize'));
		$this->setObj('mediumimagesize',$this->params->getIntRequestParam('mediumimagesize'));
		$this->setObj('bigimagesize',$this->params->getIntRequestParam('bigimagesize'));
		$this->setObj('korhintaimagesize',$this->params->getIntRequestParam('korhintaimagesize'));
		$this->setObj('jpgquality',$this->params->getIntRequestParam('jpgquality'));
		$this->setObj('pngquality',$this->params->getIntRequestParam('pngquality'));
		$this->setObj('smallimgpost',$this->params->getStringRequestParam('smallimgpost'));
		$this->setObj('mediumimgpost',$this->params->getStringRequestParam('mediumimgpost'));
		$this->setObj('bigimgpost',$this->params->getStringRequestParam('bigimgpost'));
		$this->setObj('oldalcim',$this->params->getStringRequestParam('oldalcim'));
		$this->setObj('seodescription',$this->params->getStringRequestParam('seodescription'));
		$this->setObj('fooldalajanlotttermekdb',$this->params->getIntRequestParam('fooldalajanlotttermekdb',6));
		$this->setObj('fooldalhirdb',$this->params->getIntRequestParam('fooldalhirdb',1));
		$this->setObj('fooldalnepszerutermekdb',$this->params->getIntRequestParam('fooldalnepszerutermekdb',1));
		$this->setObj('arfilterstep',$this->params->getIntRequestParam('arfilterstep',500));
		$this->setObj('kiemelttermekdb',$this->params->getIntRequestParam('kiemelttermekdb',3));
		$this->setObj('autologoutmin',$this->params->getIntRequestParam('autologoutmin',10));
		$this->setObj('katoldalcim',$this->params->getStringRequestParam('katoldalcim'));
		$this->setObj('katseodescription',$this->params->getStringRequestParam('katseodescription'));
		$this->setObj('termekoldalcim',$this->params->getStringRequestParam('termekoldalcim'));
		$this->setObj('termekseodescription',$this->params->getStringRequestParam('termekseodescription'));
		// alapertelmezes
		$fizmod=store::getEm()->getRepository('Entities\Fizmod')->find($this->params->getIntRequestParam('fizmod',0));
		if ($fizmod) {
			$this->setObj('fizmod',$fizmod->getId());
		}
		$raktar=store::getEm()->getRepository('Entities\Raktar')->find($this->params->getIntRequestParam('raktar',0));
		if ($raktar) {
			$this->setObj('raktar',$raktar->getId());
		}
		$valutanem=store::getEm()->getRepository('Entities\Valutanem')->find($this->params->getIntRequestParam('valutanem',0));
		if ($valutanem) {
			$this->setObj('valutanem',$valutanem->getId());
		}
		$this->setObj('esedekessegalap',$this->params->getIntRequestParam('esedekessegalap',1));
		//feed
		$this->setObj('feedhirdb',$this->params->getIntRequestParam('feedhirdb',20));
		$this->setObj('feedhirtitle',$this->params->getStringRequestParam('feedhirtitle',t('Híreink')));
		$this->setObj('feedhirdescription',$this->params->getStringRequestParam('feedhirdescription',t('Híreink')));
		$this->setObj('feedtermekdb',$this->params->getIntRequestParam('feedtermekdb',30));
		$this->setObj('feedtermektitle',$this->params->getStringRequestParam('feedtermektitle',t('Termékeink')));
		$this->setObj('feedtermekdescription',$this->params->getStringRequestParam('feedtermekdescription',t('Termékeink')));
		// sitemap
		$this->setObj('statlapprior',$this->params->getNumRequestParam('statlapprior',0.4));
		$this->setObj('termekprior',$this->params->getNumRequestParam('termekprior',0.5));
		$this->setObj('kategoriaprior',$this->params->getNumRequestParam('kategoriaprior',0.7));
		$this->setObj('fooldalprior',$this->params->getNumRequestParam('fooldalprior',1));
		$this->setObj('statlapchangefreq',$this->params->getStringRequestParam('statlapchangefreq','monthly'));
		$this->setObj('termekchangefreq',$this->params->getStringRequestParam('termekchangefreq','monthly'));
		$this->setObj('kategoriachangefreq',$this->params->getStringRequestParam('kategoriachangefreq','daily'));
		$this->setObj('fooldalchangefreq',$this->params->getStringRequestParam('fooldalchangefreq','daily'));
		store::getEm()->flush();
	}
}