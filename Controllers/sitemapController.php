<?php
namespace Controllers;
use mkw\store;

class sitemapController extends \mkwhelpers\Controller {

	public function __construct($params) {
		$this->entityName='Entities\Parameterek';
		parent::__construct($params);
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
			'changefreq'=>store::getParameter(\mkw\consts::Fooldalchangefreq,'daily'),
			'priority'=>store::getParameter(\mkw\consts::Fooldalprior,'1.0')
		);
		$c=store::getParameter(\mkw\consts::Kategoriachangefreq,'daily');
		$p=store::getParameter(\mkw\consts::Kategoriaprior,'0.7');
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
		$c=store::getParameter(\mkw\consts::Termekchangefreq,'daily');
		$p=store::getParameter(\mkw\consts::Termekprior,'0.5');
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
		$c=store::getParameter(\mkw\consts::Statlapchangefreq,'monthly');
		$p=store::getParameter(\mkw\consts::Statlapprior,'0.4');
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