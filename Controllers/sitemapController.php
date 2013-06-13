<?php
namespace Controllers;
use matt, mkw\store;

class sitemapController extends matt\Controller {

	public function __construct($generalDataLoader,$actionName=null,$commandString=null) {
		$this->setTemplateFactory(store::getTemplateFactory());
		$this->entityName='Entities\Parameterek';
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

	public function view() {
		$gd=new \mkw\generalDataLoader();
		$view=$this->createView('sitemap.tpl');
		$gd->loadData($view);
		$view->printTemplateResult();
	}

	public function create() {
		$smview=$this->createView('sitemapxml.tpl');
		$urls[]=array(
			'url'=>'http://'.$_SERVER['HTTP_HOST'],
			'lastmod'=>date('Y-m-d'),
			'changefreq'=>store::getParameter('fooldalchangefreq','daily'),
			'priority'=>store::getParameter('fooldalprior','1.0')
		);
		$c=store::getParameter('kategoriachangefreq','daily');
		$p=store::getParameter('kategoriaprior','0.7');
		$tr=store::getEm()->getRepository('\Entities\TermekFa');
		$rec=$tr->getForSitemapXml();
		foreach($rec as $sor) {
			$d=new \DateTime($sor['lastmod']);
			$urls[]=array(
				'url'=>htmlentities('http://'.$_SERVER['HTTP_HOST'].'/termekfa/'.$sor['slug']),
				'lastmod'=>$d->format('Y-m-d'),
				'changefreq'=>$c,
				'priority'=>$p
			);
		}
		$c=store::getParameter('termekchangefreq','daily');
		$p=store::getParameter('termekprior','0.5');
		$tr=store::getEm()->getRepository('\Entities\Termek');
		$rec=$tr->getForSitemapXml();
		foreach($rec as $sor) {
			$d=new \DateTime($sor['lastmod']);
			$urls[]=array(
				'url'=>htmlentities('http://'.$_SERVER['HTTP_HOST'].'/termek/'.$sor['slug']),
				'lastmod'=>$d->format('Y-m-d'),
				'changefreq'=>$c,
				'priority'=>$p
			);
		}
		$c=store::getParameter('statlapchangefreq','monthly');
		$p=store::getParameter('statlapprior','0.4');
		$tr=store::getEm()->getRepository('\Entities\Statlap');
		$rec=$tr->getForSitemapXml();
		foreach($rec as $sor) {
			$d=new \DateTime($sor['lastmod']);
			$urls[]=array(
				'url'=>htmlentities('http://'.$_SERVER['HTTP_HOST'].'/statlap/'.$sor['slug']),
				'lastmod'=>$d->format('Y-m-d'),
				'changefreq'=>$c,
				'priority'=>$p
			);
		}
		$smview->setVar('urls',$urls);
		file_put_contents('sitemap.xml',$smview->getTemplateResult());

		$gd=new \mkw\generalDataLoader();
		$view=$this->createView('sitemap.tpl');
		$gd->loadData($view);
		$view->setVar('szoveg',t('A sitemap kész.'));
		$view->printTemplateResult();
	}
}