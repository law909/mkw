<?php

namespace Controllers;

use mkw\store;

class setupController extends \mkwhelpers\Controller {

    public function __construct($params) {
        $this->entityName = 'Entities\Parameterek';
        parent::__construct($params);
    }

    public function view() {
        $repo = store::getEm()->getRepository($this->entityName);
        $view = $this->createView('setup.tpl');
        $view->setVar('pagetitle', t('Beállítások'));

        // tulaj
        $p = $repo->find(\mkw\consts::Tulajnev);
        $view->setVar(\mkw\consts::Tulajnev, ($p ? $p->getErtek() : ''));
        $p = $repo->find(\mkw\consts::Tulajirszam);
        $view->setVar(\mkw\consts::Tulajirszam, ($p ? $p->getErtek() : ''));
        $p = $repo->find(\mkw\consts::Tulajvaros);
        $view->setVar(\mkw\consts::Tulajvaros, ($p ? $p->getErtek() : ''));
        $p = $repo->find(\mkw\consts::Tulajutca);
        $view->setVar(\mkw\consts::Tulajutca, ($p ? $p->getErtek() : ''));
        $p = $repo->find(\mkw\consts::Tulajadoszam);
        $view->setVar(\mkw\consts::Tulajadoszam, ($p ? $p->getErtek() : ''));
        $p = $repo->find(\mkw\consts::Tulajeuadoszam);
        $view->setVar(\mkw\consts::Tulajeuadoszam, ($p ? $p->getErtek() : ''));
        $p = $repo->find(\mkw\consts::Tulajeorinr);
        $view->setVar(\mkw\consts::Tulajeorinr, ($p ? $p->getErtek() : ''));

        $p = $repo->find(\mkw\consts::EmailFrom);
        $view->setVar(\mkw\consts::EmailFrom, ($p ? $p->getErtek() : ''));
        $p = $repo->find(\mkw\consts::EmailReplyTo);
        $view->setVar(\mkw\consts::EmailReplyTo, ($p ? $p->getErtek() : ''));
        $p = $repo->find(\mkw\consts::EmailBcc);
        $view->setVar(\mkw\consts::EmailBcc, ($p ? $p->getErtek() : ''));

        // web
        $p = $repo->find(\mkw\consts::Oldalcim);
        $view->setVar(\mkw\consts::Oldalcim, ($p ? $p->getErtek() : ''));
        $p = $repo->find(\mkw\consts::Seodescription);
        $view->setVar(\mkw\consts::Seodescription, ($p ? $p->getErtek() : ''));

        $p = $repo->find(\mkw\consts::Termekoldalcim);
        $view->setVar(\mkw\consts::Termekoldalcim, ($p ? $p->getErtek() : ''));
        $p = $repo->find(\mkw\consts::Termekseodescription);
        $view->setVar(\mkw\consts::Termekseodescription, ($p ? $p->getErtek() : ''));

        $p = $repo->find(\mkw\consts::Katoldalcim);
        $view->setVar(\mkw\consts::Katoldalcim, ($p ? $p->getErtek() : ''));
        $p = $repo->find(\mkw\consts::Katseodescription);
        $view->setVar(\mkw\consts::Katseodescription, ($p ? $p->getErtek() : ''));

        $p = $repo->find(\mkw\consts::Markaoldalcim);
        $view->setVar(\mkw\consts::Markaoldalcim, ($p ? $p->getErtek() : ''));
        $p = $repo->find(\mkw\consts::Markaseodescription);
        $view->setVar(\mkw\consts::Markaseodescription, ($p ? $p->getErtek() : ''));

        $p = $repo->find(\mkw\consts::Hirekoldalcim);
        $view->setVar(\mkw\consts::Hirekoldalcim, ($p ? $p->getErtek() : ''));
        $p = $repo->find(\mkw\consts::Hirekseodescription);
        $view->setVar(\mkw\consts::Hirekseodescription, ($p ? $p->getErtek() : ''));

        $p = $repo->find(\mkw\consts::Logo);
        $view->setVar(\mkw\consts::Logo, ($p ? $p->getErtek() : ''));
        $p = $repo->find(\mkw\consts::UjtermekJelolo);
        $view->setVar(\mkw\consts::UjtermekJelolo, ($p ? $p->getErtek() : ''));
        $p = $repo->find(\mkw\consts::Top10Jelolo);
        $view->setVar(\mkw\consts::Top10Jelolo, ($p ? $p->getErtek() : ''));
        $p = $repo->find(\mkw\consts::AkcioJelolo);
        $view->setVar(\mkw\consts::AkcioJelolo, ($p ? $p->getErtek() : ''));
        $p = $repo->find(\mkw\consts::IngyenszallitasJelolo);
        $view->setVar(\mkw\consts::IngyenszallitasJelolo, ($p ? $p->getErtek() : ''));

        $p = $repo->find(\mkw\consts::SzallitasiKtg1Tol);
        $view->setVar(\mkw\consts::SzallitasiKtg1Tol, ($p ? $p->getErtek() : ''));
        $p = $repo->find(\mkw\consts::SzallitasiKtg1Ig);
        $view->setVar(\mkw\consts::SzallitasiKtg1Ig, ($p ? $p->getErtek() : ''));
        $p = $repo->find(\mkw\consts::SzallitasiKtg1Ertek);
        $view->setVar(\mkw\consts::SzallitasiKtg1Ertek, ($p ? $p->getErtek() : ''));
        $p = $repo->find(\mkw\consts::SzallitasiKtg2Tol);
        $view->setVar(\mkw\consts::SzallitasiKtg2Tol, ($p ? $p->getErtek() : ''));
        $p = $repo->find(\mkw\consts::SzallitasiKtg2Ig);
        $view->setVar(\mkw\consts::SzallitasiKtg2Ig, ($p ? $p->getErtek() : ''));
        $p = $repo->find(\mkw\consts::SzallitasiKtg2Ertek);
        $view->setVar(\mkw\consts::SzallitasiKtg2Ertek, ($p ? $p->getErtek() : ''));
        $p = $repo->find(\mkw\consts::SzallitasiKtg3Tol);
        $view->setVar(\mkw\consts::SzallitasiKtg3Tol, ($p ? $p->getErtek() : ''));
        $p = $repo->find(\mkw\consts::SzallitasiKtg3Ig);
        $view->setVar(\mkw\consts::SzallitasiKtg3Ig, ($p ? $p->getErtek() : ''));
        $p = $repo->find(\mkw\consts::SzallitasiKtg3Ertek);
        $view->setVar(\mkw\consts::SzallitasiKtg3Ertek, ($p ? $p->getErtek() : ''));
        $p = $repo->find(\mkw\consts::SzallitasiKtgTermek);
        $fizmod = new termekController($this->params);
        $view->setVar('szallitasiktgtermeklist', $fizmod->getSelectList(($p ? $p->getErtek() : 0)));

        $p = $repo->find(\mkw\consts::Miniimagesize);
        $view->setVar(\mkw\consts::Miniimagesize, ($p ? $p->getErtek() : 80));
        $p = $repo->find(\mkw\consts::Smallimagesize);
        $view->setVar(\mkw\consts::Smallimagesize, ($p ? $p->getErtek() : 80));
        $p = $repo->find(\mkw\consts::Mediumimagesize);
        $view->setVar(\mkw\consts::Mediumimagesize, ($p ? $p->getErtek() : 200));
        $p = $repo->find(\mkw\consts::Bigimagesize);
        $view->setVar(\mkw\consts::Bigimagesize, ($p ? $p->getErtek() : 800));
        $p = $repo->find(\mkw\consts::Korhintaimagesize);
        $view->setVar(\mkw\consts::Korhintaimagesize, ($p ? $p->getErtek() : 480));
        $p = $repo->find(\mkw\consts::Jpgquality);
        $view->setVar(\mkw\consts::Jpgquality, ($p ? $p->getErtek() : 90));
        $p = $repo->find(\mkw\consts::Pngquality);
        $view->setVar(\mkw\consts::Pngquality, ($p ? $p->getErtek() : 9));
        $p = $repo->find(\mkw\consts::Miniimgpost);
        $view->setVar(\mkw\consts::Miniimgpost, ($p ? $p->getErtek() : '_i'));
        $p = $repo->find(\mkw\consts::Smallimgpost);
        $view->setVar(\mkw\consts::Smallimgpost, ($p ? $p->getErtek() : '_s'));
        $p = $repo->find(\mkw\consts::Mediumimgpost);
        $view->setVar(\mkw\consts::Mediumimgpost, ($p ? $p->getErtek() : '_m'));
        $p = $repo->find(\mkw\consts::Bigimgpost);
        $view->setVar(\mkw\consts::Bigimgpost, ($p ? $p->getErtek() : '_b'));
        $p = $repo->find(\mkw\consts::Fooldalajanlotttermekdb);
        $view->setVar(\mkw\consts::Fooldalajanlotttermekdb, ($p ? $p->getErtek() : 6));
        $p = $repo->find(\mkw\consts::Fooldalhirdb);
        $view->setVar(\mkw\consts::Fooldalhirdb, ($p ? $p->getErtek() : 5));
        $p = $repo->find(\mkw\consts::Fooldalnepszerutermekdb);
        $view->setVar(\mkw\consts::Fooldalnepszerutermekdb, ($p ? $p->getErtek() : 6));
        $p = $repo->find(\mkw\consts::Termeklistatermekdb);
        $view->setVar(\mkw\consts::Termeklistatermekdb, ($p ? $p->getErtek() : 30));
        $p = $repo->find(\mkw\consts::Termeklapnepszerutermekdb);
        $view->setVar(\mkw\consts::Termeklapnepszerutermekdb, ($p ? $p->getErtek() : 6));

        $p = $repo->find(\mkw\consts::Arfilterstep);
        $view->setVar(\mkw\consts::Arfilterstep, ($p ? $p->getErtek() : 500));
        $p = $repo->find(\mkw\consts::Kiemelttermekdb);
        $view->setVar(\mkw\consts::Kiemelttermekdb, ($p ? $p->getErtek() : 3));
        $p = $repo->find(\mkw\consts::Hasonlotermekdb);
        $view->setVar(\mkw\consts::Hasonlotermekdb, ($p ? $p->getErtek() : 3));
        $p = $repo->find(\mkw\consts::Hasonlotermekarkulonbseg);
        $view->setVar(\mkw\consts::Hasonlotermekarkulonbseg, ($p ? $p->getErtek() : 10));
        $p = $repo->find(\mkw\consts::Autologoutmin);
        $view->setVar(\mkw\consts::Autologoutmin, ($p ? $p->getErtek() : 10));
        $p = $repo->find(\mkw\consts::GAFollow);
        $view->setVar(\mkw\consts::GAFollow, ($p ? $p->getErtek() : ''));
        $p = $repo->find(\mkw\consts::FBAppId);
        $view->setVar(\mkw\consts::FBAppId, ($p ? $p->getErtek() : ''));

        // alapertelmezes
        $p = $repo->find(\mkw\consts::Fizmod);
        $fizmod = new fizmodController($this->params);
        $view->setVar('fizmodlist', $fizmod->getSelectList(($p ? $p->getErtek() : 0)));

        $p = $repo->find(\mkw\consts::OTPayFizmod);
//        $fizmod = new fizmodController($this->params);
        $view->setVar('otpayfizmodlist', $fizmod->getSelectList(($p ? $p->getErtek() : 0)));

        $p = $repo->find(\mkw\consts::MasterPassFizmod);
//        $fizmod = new fizmodController($this->params);
        $view->setVar('masterpassfizmodlist', $fizmod->getSelectList(($p ? $p->getErtek() : 0)));

        $p = $repo->find(\mkw\consts::Raktar);
        $raktar = new raktarController($this->params);
        $view->setVar('raktarlist', $raktar->getSelectList(($p ? $p->getErtek() : 0)));

        $p = $repo->find(\mkw\consts::Valutanem);
        $valutanem = new valutanemController($this->params);
        $view->setVar('valutanemlist', $valutanem->getSelectList(($p ? $p->getErtek() : 0)));

        $p = $repo->find(\mkw\consts::Arsav);
        $arsav = new termekarController($this->params);
        $view->setVar('arsavlist', $arsav->getSelectList(($p ? $p->getErtek() : 0)));

        $p = $repo->find(\mkw\consts::MarkaCs);
        $markacs = new termekcimkekatController($this->params);
        $view->setVar('markacslist', $markacs->getSelectList(($p ? $p->getErtek() : 0)));

        $mkcs = new munkakorController($this->params);
        $p = $repo->find(\mkw\consts::AdminRole);
        $view->setVar('adminrolelist', $mkcs->getSelectList(($p ? $p->getErtek() : 0)));
        $p = $repo->find(\mkw\consts::TermekfeltoltoRole);
        $view->setVar('termekfeltoltorolelist', $mkcs->getSelectList(($p ? $p->getErtek() : 0)));

        $bsf = new bizonylatstatuszController($this->params);
        $p = $repo->find(\mkw\consts::BizonylatStatuszFuggoben);
        $view->setVar('bizonylatstatuszfuggobenlist', $bsf->getSelectList(($p ? $p->getErtek() : 0)));

        $p = $repo->find(\mkw\consts::Esedekessegalap);
        $view->setVar(\mkw\consts::Esedekessegalap, ($p ? $p->getErtek() : '1'));

        $p = $repo->find(\mkw\consts::ImportNewKatId);
        $inkid = $p ? $p->getErtek() : 0;
        $importnewkat = store::getEm()->getRepository('Entities\TermekFa')->find($inkid);
        if ($importnewkat) {
            $view->setVar('importnewkat', array(
                'caption' => $importnewkat->getNev(),
                'id' => $importnewkat->getId()
            ));
        }
        else {
            $view->setVar('importnewkat', array(
                'caption' => '',
                'id' => ''
            ));
        }

        // feed
        $p = $repo->find(\mkw\consts::Feedhirdb);
        $view->setVar(\mkw\consts::Feedhirdb, ($p ? $p->getErtek() : 20));
        $p = $repo->find(\mkw\consts::Feedhirtitle);
        $view->setVar(\mkw\consts::Feedhirtitle, ($p ? $p->getErtek() : t('Híreink')));
        $p = $repo->find(\mkw\consts::Feedhirdescription);
        $view->setVar(\mkw\consts::Feedhirdescription, ($p ? $p->getErtek() : t('Híreink')));
        $p = $repo->find(\mkw\consts::Feedtermekdb);
        $view->setVar(\mkw\consts::Feedtermekdb, ($p ? $p->getErtek() : 30));
        $p = $repo->find(\mkw\consts::Feedtermektitle);
        $view->setVar(\mkw\consts::Feedtermektitle, ($p ? $p->getErtek() : t('Termékeink')));
        $p = $repo->find(\mkw\consts::Feedtermekdescription);
        $view->setVar(\mkw\consts::Feedtermekdescription, ($p ? $p->getErtek() : t('Termékeink')));

        // sitemap
        $p = $repo->find(\mkw\consts::Statlapprior);
        $view->setVar(\mkw\consts::Statlapprior, ($p ? $p->getErtek() : 0.4));
        $p = $repo->find(\mkw\consts::Termekprior);
        $view->setVar(\mkw\consts::Termekprior, ($p ? $p->getErtek() : 0.5));
        $p = $repo->find(\mkw\consts::Kategoriaprior);
        $view->setVar(\mkw\consts::Kategoriaprior, ($p ? $p->getErtek() : 0.7));
        $p = $repo->find(\mkw\consts::Fooldalprior);
        $view->setVar(\mkw\consts::Fooldalprior, ($p ? $p->getErtek() : 1));
        $p = $repo->find(\mkw\consts::Statlapchangefreq);
        $view->setVar(\mkw\consts::Statlapchangefreq, ($p ? $p->getErtek() : 'monthly'));
        $p = $repo->find(\mkw\consts::Termekchangefreq);
        $view->setVar(\mkw\consts::Termekchangefreq, ($p ? $p->getErtek() : 'monthly'));
        $p = $repo->find(\mkw\consts::Kategoriachangefreq);
        $view->setVar(\mkw\consts::Kategoriachangefreq, ($p ? $p->getErtek() : 'daily'));
        $p = $repo->find(\mkw\consts::Fooldalchangefreq);
        $view->setVar(\mkw\consts::Fooldalchangefreq, ($p ? $p->getErtek() : 'daily'));

        $p = $repo->find(\mkw\consts::AKTrustedShopApiKey);
        $view->setVar(\mkw\consts::AKTrustedShopApiKey, ($p ? $p->getErtek() : ''));

        $view->printTemplateResult();
    }

    private function setObj($par, $value) {
        $en = $this->entityName;
        $p = store::getEm()->getRepository($en)->find($par);
        if ($p) {
            $p->setErtek($value);
        }
        else {
            $p = new $en();
            $p->setId($par);
            $p->setErtek($value);
        }
        store::getEm()->persist($p);
    }

    public function save() {
        // tulaj
        $this->setObj(\mkw\consts::Tulajnev, $this->params->getStringRequestParam('tulajnev'));
        $this->setObj(\mkw\consts::Tulajirszam, $this->params->getStringRequestParam('tulajirszam'));
        $this->setObj(\mkw\consts::Tulajvaros, $this->params->getStringRequestParam('tulajvaros'));
        $this->setObj(\mkw\consts::Tulajutca, $this->params->getStringRequestParam('tulajutca'));
        $this->setObj(\mkw\consts::Tulajadoszam, $this->params->getStringRequestParam('tulajadoszam'));
        $this->setObj(\mkw\consts::Tulajeuadoszam, $this->params->getStringRequestParam('tulajeuadoszam'));
        $this->setObj(\mkw\consts::Tulajeorinr, $this->params->getStringRequestParam('tulajeorinr'));

        $this->setObj(\mkw\consts::EmailFrom, $this->params->getOriginalStringRequestParam('emailfrom'));
        $this->setObj(\mkw\consts::EmailReplyTo, $this->params->getOriginalStringRequestParam('emailreplyto'));
        $this->setObj(\mkw\consts::EmailBcc, $this->params->getStringRequestParam('emailbcc'));

        // web
        $this->setObj(\mkw\consts::Logo, $this->params->getStringRequestParam('logo'));
        $this->setObj(\mkw\consts::UjtermekJelolo, $this->params->getStringRequestParam('ujtermekjelolo'));
        $this->setObj(\mkw\consts::Top10Jelolo, $this->params->getStringRequestParam('top10jelolo'));
        $this->setObj(\mkw\consts::AkcioJelolo, $this->params->getStringRequestParam('akciojelolo'));
        $this->setObj(\mkw\consts::IngyenszallitasJelolo, $this->params->getStringRequestParam('ingyenszallitasjelolo'));

        $this->setObj(\mkw\consts::SzallitasiKtg1Tol, $this->params->getStringRequestParam('szallitasiktg1tol'));
        $this->setObj(\mkw\consts::SzallitasiKtg1Ig, $this->params->getStringRequestParam('szallitasiktg1ig'));
        $this->setObj(\mkw\consts::SzallitasiKtg1Ertek, $this->params->getStringRequestParam('szallitasiktg1ertek'));

        $this->setObj(\mkw\consts::SzallitasiKtg2Tol, $this->params->getStringRequestParam('szallitasiktg2tol'));
        $this->setObj(\mkw\consts::SzallitasiKtg2Ig, $this->params->getStringRequestParam('szallitasiktg2ig'));
        $this->setObj(\mkw\consts::SzallitasiKtg2Ertek, $this->params->getStringRequestParam('szallitasiktg2ertek'));

        $this->setObj(\mkw\consts::SzallitasiKtg3Tol, $this->params->getStringRequestParam('szallitasiktg3tol'));
        $this->setObj(\mkw\consts::SzallitasiKtg3Ig, $this->params->getStringRequestParam('szallitasiktg3ig'));
        $this->setObj(\mkw\consts::SzallitasiKtg3Ertek, $this->params->getStringRequestParam('szallitasiktg3ertek'));
        $szkt = store::getEm()->getRepository('Entities\Termek')->find($this->params->getIntRequestParam('szallitasiktgtermek', 0));
        if ($szkt) {
            $this->setObj(\mkw\consts::SzallitasiKtgTermek, $szkt->getId());
        }

        $this->setObj(\mkw\consts::Miniimagesize, $this->params->getIntRequestParam('miniimagesize'));
        $this->setObj(\mkw\consts::Smallimagesize, $this->params->getIntRequestParam('smallimagesize'));
        $this->setObj(\mkw\consts::Mediumimagesize, $this->params->getIntRequestParam('mediumimagesize'));
        $this->setObj(\mkw\consts::Bigimagesize, $this->params->getIntRequestParam('bigimagesize'));
        $this->setObj(\mkw\consts::Korhintaimagesize, $this->params->getIntRequestParam('korhintaimagesize'));
        $this->setObj(\mkw\consts::Jpgquality, $this->params->getIntRequestParam('jpgquality'));
        $this->setObj(\mkw\consts::Pngquality, $this->params->getIntRequestParam('pngquality'));
        $this->setObj(\mkw\consts::Miniimgpost, $this->params->getStringRequestParam('miniimgpost'));
        $this->setObj(\mkw\consts::Smallimgpost, $this->params->getStringRequestParam('smallimgpost'));
        $this->setObj(\mkw\consts::Mediumimgpost, $this->params->getStringRequestParam('mediumimgpost'));
        $this->setObj(\mkw\consts::Bigimgpost, $this->params->getStringRequestParam('bigimgpost'));
        $this->setObj(\mkw\consts::Oldalcim, $this->params->getStringRequestParam('oldalcim'));
        $this->setObj(\mkw\consts::Seodescription, $this->params->getStringRequestParam('seodescription'));
        $this->setObj(\mkw\consts::Fooldalajanlotttermekdb, $this->params->getIntRequestParam('fooldalajanlotttermekdb', 6));
        $this->setObj(\mkw\consts::Fooldalhirdb, $this->params->getIntRequestParam('fooldalhirdb', 1));
        $this->setObj(\mkw\consts::Fooldalnepszerutermekdb, $this->params->getIntRequestParam('fooldalnepszerutermekdb', 1));
        $this->setObj(\mkw\consts::Termeklapnepszerutermekdb, $this->params->getIntRequestParam('termeklapnepszerutermekdb', 1));
        $this->setObj(\mkw\consts::Termeklistatermekdb, $this->params->getIntRequestParam('termeklistatermekdb', 30));

        $this->setObj(\mkw\consts::Arfilterstep, $this->params->getIntRequestParam('arfilterstep', 500));
        $this->setObj(\mkw\consts::Kiemelttermekdb, $this->params->getIntRequestParam('kiemelttermekdb', 3));
        $this->setObj(\mkw\consts::Hasonlotermekdb, $this->params->getIntRequestParam('hasonlotermekdb', 3));
        $this->setObj(\mkw\consts::Hasonlotermekarkulonbseg, $this->params->getIntRequestParam('hasonlotermekarkulonbseg', 10));
        $this->setObj(\mkw\consts::Autologoutmin, $this->params->getIntRequestParam('autologoutmin', 10));
        $this->setObj(\mkw\consts::Katoldalcim, $this->params->getStringRequestParam('katoldalcim'));
        $this->setObj(\mkw\consts::Katseodescription, $this->params->getStringRequestParam('katseodescription'));
        $this->setObj(\mkw\consts::Termekoldalcim, $this->params->getStringRequestParam('termekoldalcim'));
        $this->setObj(\mkw\consts::Termekseodescription, $this->params->getStringRequestParam('termekseodescription'));
        $this->setObj(\mkw\consts::Markaoldalcim, $this->params->getStringRequestParam('markaoldalcim'));
        $this->setObj(\mkw\consts::Markaseodescription, $this->params->getStringRequestParam('markaseodescription'));
        $this->setObj(\mkw\consts::Hirekoldalcim, $this->params->getStringRequestParam('hirekoldalcim'));
        $this->setObj(\mkw\consts::Hirekseodescription, $this->params->getStringRequestParam('hirekseodescription'));
        $this->setObj(\mkw\consts::GAFollow, $this->params->getStringRequestParam('gafollow'));
        $this->setObj(\mkw\consts::FBAppId, $this->params->getStringRequestParam('fbappid'));
        // alapertelmezes
        $fizmod = store::getEm()->getRepository('Entities\Fizmod')->find($this->params->getIntRequestParam('fizmod', 0));
        if ($fizmod) {
            $this->setObj(\mkw\consts::Fizmod, $fizmod->getId());
        }
        $fizmod = store::getEm()->getRepository('Entities\Fizmod')->find($this->params->getIntRequestParam('otpayfizmod', 0));
        if ($fizmod) {
            $this->setObj(\mkw\consts::OTPayFizmod, $fizmod->getId());
        }
        else {
            $this->setObj(\mkw\consts::OTPayFizmod, '');
        }
        $fizmod = store::getEm()->getRepository('Entities\Fizmod')->find($this->params->getIntRequestParam('masterpassfizmod', 0));
        if ($fizmod) {
            $this->setObj(\mkw\consts::MasterPassFizmod, $fizmod->getId());
        }
        else {
            $this->setObj(\mkw\consts::MasterPassFizmod, '');
        }
        $raktar = store::getEm()->getRepository('Entities\Raktar')->find($this->params->getIntRequestParam('raktar', 0));
        if ($raktar) {
            $this->setObj(\mkw\consts::Raktar, $raktar->getId());
        }
        $valutanem = store::getEm()->getRepository('Entities\Valutanem')->find($this->params->getIntRequestParam('valutanem', 0));
        if ($valutanem) {
            $this->setObj(\mkw\consts::Valutanem, $valutanem->getId());
        }
        $this->setObj(\mkw\consts::Arsav, $this->params->getStringRequestParam('arsav'));
        $markacs = store::getEm()->getRepository('Entities\Termekcimkekat')->find($this->params->getIntRequestParam('markacs', 0));
        if ($markacs) {
            $this->setObj(\mkw\consts::MarkaCs, $markacs->getId());
        }
        else {
            $this->setObj(\mkw\consts::MarkaCs, '');
        }

        $rolerep = store::getEm()->getRepository('Entities\Munkakor');
        $role = $rolerep->find($this->params->getIntRequestParam('adminrole', 0));
        if ($role) {
            $this->setObj(\mkw\consts::AdminRole, $role->getId());
        }
        $role = $rolerep->find($this->params->getIntRequestParam('termekfeltoltorole', 0));
        if ($role) {
            $this->setObj(\mkw\consts::TermekfeltoltoRole, $role->getId());
        }

        $bsf = store::getEm()->getRepository('Entities\Bizonylatstatusz')->find($this->params->getIntRequestParam('bizonylatstatuszfuggoben', 0));
        if ($bsf) {
            $this->setObj(\mkw\consts::BizonylatStatuszFuggoben, $bsf->getId());
        }
        else {
            $this->setObj(\mkw\consts::BizonylatStatuszFuggoben, '');
        }

        $this->setObj(\mkw\consts::Esedekessegalap, $this->params->getIntRequestParam('esedekessegalap', 1));
        $inkid = $this->params->getIntRequestParam('importnewkatid');
        if ($inkid) {
            $importnewkat = store::getEm()->getRepository('Entities\TermekFa')->find($inkid);
            $this->setObj(\mkw\consts::ImportNewKatId, $importnewkat->getId());
        }
        else {
            $this->setObj(\mkw\consts::ImportNewKatId, 0);
        }
        //feed
        $this->setObj(\mkw\consts::Feedhirdb, $this->params->getIntRequestParam('feedhirdb', 20));
        $this->setObj(\mkw\consts::Feedhirtitle, $this->params->getStringRequestParam('feedhirtitle', t('Híreink')));
        $this->setObj(\mkw\consts::Feedhirdescription, $this->params->getStringRequestParam('feedhirdescription', t('Híreink')));
        $this->setObj(\mkw\consts::Feedtermekdb, $this->params->getIntRequestParam('feedtermekdb', 30));
        $this->setObj(\mkw\consts::Feedtermektitle, $this->params->getStringRequestParam('feedtermektitle', t('Termékeink')));
        $this->setObj(\mkw\consts::Feedtermekdescription, $this->params->getStringRequestParam('feedtermekdescription', t('Termékeink')));
        // sitemap
        $this->setObj(\mkw\consts::Statlapprior, $this->params->getNumRequestParam('statlapprior', 0.4));
        $this->setObj(\mkw\consts::Termekprior, $this->params->getNumRequestParam('termekprior', 0.5));
        $this->setObj(\mkw\consts::Kategoriaprior, $this->params->getNumRequestParam('kategoriaprior', 0.7));
        $this->setObj(\mkw\consts::Fooldalprior, $this->params->getNumRequestParam('fooldalprior', 1));
        $this->setObj(\mkw\consts::Statlapchangefreq, $this->params->getStringRequestParam('statlapchangefreq', 'monthly'));
        $this->setObj(\mkw\consts::Termekchangefreq, $this->params->getStringRequestParam('termekchangefreq', 'monthly'));
        $this->setObj(\mkw\consts::Kategoriachangefreq, $this->params->getStringRequestParam('kategoriachangefreq', 'daily'));
        $this->setObj(\mkw\consts::Fooldalchangefreq, $this->params->getStringRequestParam('fooldalchangefreq', 'daily'));

        $this->setObj(\mkw\consts::AKTrustedShopApiKey, $this->params->getStringRequestParam('aktrustedshopapikey', ''));
        store::getEm()->flush();
    }

}
