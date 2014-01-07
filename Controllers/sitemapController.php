<?php

namespace Controllers;

use mkw\store;

class sitemapController extends \mkwhelpers\Controller {

    public function __construct($params) {
        $this->entityName = 'Entities\Parameterek';
        parent::__construct($params);
    }

    public function view() {
        $gd = new \mkw\generalDataLoader();
        $view = $this->createView('sitemap.tpl');
        $gd->loadData($view);
        $view->printTemplateResult(false);
    }

    public function create() {
        $router = \mkw\Store::getRouter();
        $smview = $this->createView('sitemapxml.tpl');
        $urls[] = array(
            'url' => $router->generate('home', \mkw\Store::getConfigValue('mainurl')),
            'lastmod' => date('Y-m-d'),
            'changefreq' => store::getParameter(\mkw\consts::Fooldalchangefreq, 'daily'),
            'priority' => store::getParameter(\mkw\consts::Fooldalprior, '1.0')
        );
        $c = store::getParameter(\mkw\consts::Kategoriachangefreq, 'daily');
        $p = store::getParameter(\mkw\consts::Kategoriaprior, '0.7');
        $tr = store::getEm()->getRepository('\Entities\TermekFa');
        $rec = $tr->getForSitemapXml();
        foreach ($rec as $sor) {
            $d = new \DateTime($sor['lastmod']);
            $kep = false;
            if ($sor['kepurl']) {
                $kep = array(
                    array(
                        'url' => htmlentities(\mkw\Store::getFullUrl($sor['kepurl'], \mkw\Store::getConfigValue('mainurl'))),
                        'title' => $sor['kepleiras']
                    )
                );
            }
            $u = array(
                'url' => htmlentities($router->generate('showtermekfa', \mkw\Store::getConfigValue('mainurl'), array('slug' => $sor['slug']))),
                'lastmod' => $d->format('Y-m-d'),
                'changefreq' => $c,
                'priority' => $p
            );
            if ($kep) {
                $u['images'] = $kep;
            }
            $urls[] = $u;
        }
        $c = store::getParameter(\mkw\consts::Termekchangefreq, 'daily');
        $p = store::getParameter(\mkw\consts::Termekprior, '0.5');
        $tkr = store::getEm()->getRepository('\Entities\TermekKep');
        $tr = store::getEm()->getRepository('\Entities\Termek');
        $rec = $tr->getForSitemapXml();
        foreach ($rec as $sor) {
            $d = new \DateTime($sor['lastmod']);
            $kep = array();
            if ($sor['kepurl']) {
                $kep[] = array(
                    'url' => htmlentities(\mkw\Store::getFullUrl($sor['kepurl'], \mkw\Store::getConfigValue('mainurl'))),
                    'title' => $sor['kepleiras']
                );
            }
            $kepek = $tkr->getByTermekForSitemapXml($sor['id']);
            foreach($kepek as $k) {
                $kep[] = array(
                    'url' => htmlentities(\mkw\Store::getFullUrl($k['url'], \mkw\Store::getConfigValue('mainurl'))),
                    'title' => $k['leiras']
                );
            }
            $u = array(
                'url' => htmlentities($router->generate('showtermek', \mkw\Store::getConfigValue('mainurl'), array('slug' => $sor['slug']))),
                'lastmod' => $d->format('Y-m-d'),
                'changefreq' => $c,
                'priority' => $p
            );
            if ($kep) {
                $u['images'] = $kep;
            }
            $urls[] = $u;
        }
        $c = store::getParameter(\mkw\consts::Statlapchangefreq, 'monthly');
        $p = store::getParameter(\mkw\consts::Statlapprior, '0.4');
        $tr = store::getEm()->getRepository('\Entities\Statlap');
        $rec = $tr->getForSitemapXml();
        foreach ($rec as $sor) {
            $d = new \DateTime($sor['lastmod']);
            $urls[] = array(
                'url' => htmlentities($router->generate('showstatlap', \mkw\Store::getConfigValue('mainurl'), array('lap' => $sor['slug']))),
                'lastmod' => $d->format('Y-m-d'),
                'changefreq' => $c,
                'priority' => $p
            );
        }
        $smview->setVar('urls', $urls);
        $r = file_put_contents(\mkw\Store::getConfigValue('mainpath') . 'sitemap.xml', $smview->getTemplateResult());

        $gd = new \mkw\generalDataLoader();
        $view = $this->createView('sitemap.tpl');
        $gd->loadData($view);
        if ($r) {
            $view->setVar('szoveg', t('A sitemap kész.'));
        }
        else {
            $view->setVar('szoveg', 'Nem sikerült file-ba írni.');
        }
        $view->printTemplateResult(false);
    }
}
