<?php

namespace Controllers;

class sitemapController extends \mkwhelpers\Controller {

    public function __construct($params) {
        $this->setEntityName('Entities\Parameterek');
        parent::__construct($params);
    }

    public function view() {
        $gd = new \mkw\generalDataLoader();
        $view = $this->createView('sitemap.tpl');
        $gd->loadData($view);
        $view->printTemplateResult(false);
    }

    protected function generate() {
        $router = \mkw\store::getRouter();
        $smview = $this->createView('sitemapxml.tpl');
        $urls[] = array(
            'url' => $router->generate('home', \mkw\store::getConfigValue('mainurl')),
            'lastmod' => date('Y-m-d'),
            'changefreq' => \mkw\store::getParameter(\mkw\consts::Fooldalchangefreq, 'daily'),
            'priority' => \mkw\store::getParameter(\mkw\consts::Fooldalprior, '1.0')
        );

        $c = \mkw\store::getParameter(\mkw\consts::Kategoriachangefreq, 'daily');
        $p = \mkw\store::getParameter(\mkw\consts::Kategoriaprior, '0.7');
        $tr = \mkw\store::getEm()->getRepository('\Entities\TermekFa');
        $rec = $tr->getForSitemapXml();
        foreach ($rec as $sor) {
            $d = new \DateTime($sor['lastmod']);
            $kep = false;
            if ($sor['kepurl']) {
                $kep = array(
                    array(
                        'url' => htmlentities(\mkw\store::getFullUrl($sor['kepurl'], \mkw\store::getConfigValue('mainurl'))),
                        'title' => $sor['kepleiras']
                    )
                );
            }
            $u = array(
                'url' => htmlentities($router->generate('showtermekfa', \mkw\store::getConfigValue('mainurl'), array('slug' => $sor['slug']))),
                'lastmod' => $d->format('Y-m-d'),
                'changefreq' => $c,
                'priority' => $p
            );
            if ($kep) {
                $u['images'] = $kep;
            }
            $urls[] = $u;
        }

        $c = \mkw\store::getParameter(\mkw\consts::Termekchangefreq, 'daily');
        $p = \mkw\store::getParameter(\mkw\consts::Termekprior, '0.5');
        $tkr = \mkw\store::getEm()->getRepository('\Entities\TermekKep');
        $tr = \mkw\store::getEm()->getRepository('\Entities\Termek');
        $rec = $tr->getForSitemapXml();
        foreach ($rec as $sor) {
            $d = new \DateTime($sor['lastmod']);
            $kep = array();
            if ($sor['kepurl']) {
                $kep[] = array(
                    'url' => htmlentities(\mkw\store::getFullUrl($sor['kepurl'], \mkw\store::getConfigValue('mainurl'))),
                    'title' => $sor['kepleiras']
                );
            }
            $kepek = $tkr->getByTermekForSitemapXml($sor['id']);
            foreach($kepek as $k) {
                $kep[] = array(
                    'url' => htmlentities(\mkw\store::getFullUrl($k['url'], \mkw\store::getConfigValue('mainurl'))),
                    'title' => $k['leiras']
                );
            }
            $u = array(
                'url' => htmlentities($router->generate('showtermek', \mkw\store::getConfigValue('mainurl'), array('slug' => $sor['slug']))),
                'lastmod' => $d->format('Y-m-d'),
                'changefreq' => $c,
                'priority' => $p
            );
            if ($kep) {
                $u['images'] = $kep;
            }
            $urls[] = $u;
        }

        $c = \mkw\store::getParameter(\mkw\consts::Statlapchangefreq, 'monthly');
        $p = \mkw\store::getParameter(\mkw\consts::Statlapprior, '0.4');
        $tr = \mkw\store::getEm()->getRepository('\Entities\Statlap');
        $rec = $tr->getForSitemapXml();
        foreach ($rec as $sor) {
            $d = new \DateTime($sor['lastmod']);
            $urls[] = array(
                'url' => htmlentities($router->generate('showstatlap', \mkw\store::getConfigValue('mainurl'), array('lap' => $sor['slug']))),
                'lastmod' => $d->format('Y-m-d'),
                'changefreq' => $c,
                'priority' => $p
            );
        }

        $urls[] = array(
            'url' => htmlentities($router->generate('markak', \mkw\store::getConfigValue('mainurl'), array())),
            'lastmod' => date('Y-m-d'),
            'changefreq' => $c,
            'priority' => $p
        );

        $c = \mkw\store::getParameter(\mkw\consts::Blogposztchangefreq, 'monthly');
        $p = \mkw\store::getParameter(\mkw\consts::Blogposztprior, '0.4');
        $tr = \mkw\store::getEm()->getRepository('\Entities\Blogposzt');
        $rec = $tr->getForSitemapXml();
        foreach ($rec as $sor) {
            $d = new \DateTime($sor['lastmod']);
            $urls[] = array(
                'url' => htmlentities($router->generate('showblogposzt', \mkw\store::getConfigValue('mainurl'), array('blogposzt' => $sor['slug']))),
                'lastmod' => $d->format('Y-m-d'),
                'changefreq' => $c,
                'priority' => $p
            );
        }
        $smview->setVar('urls', $urls);
        return $smview->getTemplateResult();
    }

    public function toBot() {
        $r = $this->generate();
        header("Content-type: application/xml");
        header("Pragma: no-cache");
        header("Expires: 0");
        echo $r;
    }

    public function create() {
        $r = file_put_contents(\mkw\store::getConfigValue('mainpath') . 'sitemap.xml', $this->generate());

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
