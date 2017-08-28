<?php

namespace Controllers;

class setupController extends \mkwhelpers\Controller {

    public function __construct($params) {
        $this->setEntityName('Entities\Parameterek');
        parent::__construct($params);
    }

    public function view() {
        $repo = \mkw\store::getEm()->getRepository($this->getEntityName());
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
        $p = $repo->find(\mkw\consts::Tulajkisadozo);
        $view->setVar(\mkw\consts::Tulajkisadozo, ($p ? $p->getErtek() : 0));
        $p = $repo->find(\mkw\consts::Tulajegyenivallalkozo);
        $view->setVar(\mkw\consts::Tulajegyenivallalkozo, ($p ? $p->getErtek() : 0));
        $p = $repo->find(\mkw\consts::Tulajevnyilvszam);
        $view->setVar(\mkw\consts::Tulajevnyilvszam, ($p ? $p->getErtek() : ''));
        $p = $repo->find(\mkw\consts::Tulajevnev);
        $view->setVar(\mkw\consts::Tulajevnev, ($p ? $p->getErtek() : ''));

//        $p = $repo->find(\mkw\consts::Tulajcrc);
//        $view->setVar(\mkw\consts::Tulajcrc, ($p ? $p->getErtek() : ''));

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

        $p = $repo->find(\mkw\consts::SzallitasiFeltetelSablon);
        $szallstatlap = new statlapController($this->params);
        $view->setVar('szallitasifeltetelstatlaplist', $szallstatlap->getSelectList(($p ? $p->getErtek() : 0)));


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
        $termek = new termekController($this->params);
        $view->setVar('szallitasiktgtermeklist', $termek->getSelectList(($p ? $p->getErtek() : 0)));

        $p = $repo->find(\mkw\consts::FoxpostSzallitasiMod);
        $szallmod = new szallitasimodController($this->params);
        $view->setVar('foxpostszallmodlist', $szallmod->getSelectList(($p ? $p->getErtek() : 0), true));
        $p = $repo->find(\mkw\consts::TOFSzallitasiMod);
        $view->setVar('tofszallmodlist', $szallmod->getSelectList(($p ? $p->getErtek() : 0), true));

        $p = $repo->find(\mkw\consts::NullasAfa);
        $fizmod = new afaController($this->params);
        $view->setVar('nullasafalist', $fizmod->getSelectList(($p ? $p->getErtek() : 0)));

        $p = $repo->find(\mkw\consts::BelsoUzletkoto);
        $uk = new uzletkotoController($this->params);
        $fofilter = new \mkwhelpers\FilterDescriptor();
        $fofilter->addFilter('belso', '=', true);
        $view->setVar('belsouklist', $uk->getSelectList(($p ? $p->getErtek() : 0), $fofilter));

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
        $p = $repo->find(\mkw\consts::Fooldalakciostermekdb);
        $view->setVar(\mkw\consts::Fooldalakciostermekdb, ($p ? $p->getErtek() : 6));
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
        $p = $repo->find(\mkw\consts::FoxpostApiURL);
        $view->setVar(\mkw\consts::FoxpostApiURL, ($p ? $p->getErtek() : ''));
        $p = $repo->find(\mkw\consts::FoxpostUsername);
        $view->setVar(\mkw\consts::FoxpostUsername, ($p ? $p->getErtek() : ''));
        $p = $repo->find(\mkw\consts::FoxpostPassword);
        $view->setVar(\mkw\consts::FoxpostPassword, ($p ? $p->getErtek() : ''));
        $p = $repo->find(\mkw\consts::KuponElotag);
        $view->setVar(\mkw\consts::KuponElotag, ($p ? $p->getErtek() : 'MKW'));
        $p = $repo->find(\mkw\consts::Off);
        $view->setVar(\mkw\consts::Off, ($p ? $p->getErtek() : 0));

        $p = $repo->find(\mkw\consts::VasarlasiUtalvanyTermek);
//        $termek = new termekController($this->params);
        $view->setVar('vasarlasiutalvanytermeklist', $termek->getSelectList(($p ? $p->getErtek() : 0)));

        // alapertelmezes
        $p = $repo->find(\mkw\consts::Fizmod);
        $fizmod = new fizmodController($this->params);
        $view->setVar('fizmodlist', $fizmod->getSelectList(($p ? $p->getErtek() : 0)));

        $p = $repo->find(\mkw\consts::UtanvetFizmod);
        $view->setVar('utanvetfizmodlist', $fizmod->getSelectList(($p ? $p->getErtek() : 0)));
        $p = $repo->find(\mkw\consts::SZEPFizmod);
        $view->setVar('szepkartyafizmodlist', $fizmod->getSelectList(($p ? $p->getErtek() : 0)));
        $p = $repo->find(\mkw\consts::SportkartyaFizmod);
        $view->setVar('sportkartyafizmodlist', $fizmod->getSelectList(($p ? $p->getErtek() : 0)));
        $p = $repo->find(\mkw\consts::AYCMFizmod);
        $view->setVar('aycmfizmodlist', $fizmod->getSelectList(($p ? $p->getErtek() : 0)));

        $p = $repo->find(\mkw\consts::Szallitasimod);
        $szallmod = new szallitasimodController($this->params);
        $view->setVar('szallitasimodlist', $szallmod->getSelectList(($p ? $p->getErtek() : 0)));

        if (\mkw\store::isOTPay()) {
            $p = $repo->find(\mkw\consts::OTPayFizmod);
            $view->setVar('otpayfizmodlist', $fizmod->getSelectList(($p ? $p->getErtek() : 0)));
        }

        if (\mkw\store::isMasterPass()) {
            $p = $repo->find(\mkw\consts::MasterPassFizmod);
            $view->setVar('masterpassfizmodlist', $fizmod->getSelectList(($p ? $p->getErtek() : 0)));
        }

        $p = $repo->find(\mkw\consts::Raktar);
        $raktar = new raktarController($this->params);
        $view->setVar('raktarlist', $raktar->getSelectList(($p ? $p->getErtek() : 0)));

        $p = $repo->find(\mkw\consts::Valutanem);
        $valutanem = new valutanemController($this->params);
        $view->setVar('valutanemlist', $valutanem->getSelectList(($p ? $p->getErtek() : 0)));
        $p = $repo->find(\mkw\consts::ShowTermekArsavValutanem);
        $view->setVar('showtermekarsavvalutanemlist', $valutanem->getSelectList(($p ? $p->getErtek() : 0)));

        $p = $repo->find(\mkw\consts::Arsav);
        $arsav = new termekarController($this->params);
        $view->setVar('arsavlist', $arsav->getSelectList(($p ? $p->getErtek() : '')));
        $p = $repo->find(\mkw\consts::ShowTermekArsav);
        $view->setVar('showtermekarsavlist', $arsav->getSelectList(($p ? $p->getErtek() : '')));

        $p = $repo->find(\mkw\consts::Webshop2Price);
        $view->setVar('arsav2list', $arsav->getSelectList(($p ? $p->getErtek() : '')));
        $p = $repo->find(\mkw\consts::Webshop2Discount);
        $view->setVar('akciosarsav2list', $arsav->getSelectList(($p ? $p->getErtek() : '')));
        $p = $repo->find(\mkw\consts::Webshop3Price);
        $view->setVar('arsav3list', $arsav->getSelectList(($p ? $p->getErtek() : '')));
        $p = $repo->find(\mkw\consts::Webshop3Discount);
        $view->setVar('akciosarsav3list', $arsav->getSelectList(($p ? $p->getErtek() : '')));

        $p = $repo->find(\mkw\consts::MarkaCs);
        $markacs = new termekcimkekatController($this->params);
        $view->setVar('markacslist', $markacs->getSelectList(($p ? $p->getErtek() : 0)));

        $p = $repo->find(\mkw\consts::KiskerCimke);
        $partnercimkec = new partnercimkeController($this->params);
        $view->setVar('kiskercimkelist', $partnercimkec->getSelectList(($p ? $p->getErtek() : 0)));
        $p = $repo->find(\mkw\consts::SpanyolCimke);
        $view->setVar('spanyolcimkelist', $partnercimkec->getSelectList(($p ? $p->getErtek() : 0)));
        $orszagc = new orszagController($this->params);
        $p = $repo->find(\mkw\consts::Spanyolorszag);
        $view->setVar('spanyolorszaglist', $orszagc->getSelectList(($p ? $p->getErtek() : 0)));

        $mkcs = new munkakorController($this->params);
        $p = $repo->find(\mkw\consts::AdminRole);
        $view->setVar('adminrolelist', $mkcs->getSelectList(($p ? $p->getErtek() : 0)));
        $p = $repo->find(\mkw\consts::TermekfeltoltoRole);
        $view->setVar('termekfeltoltorolelist', $mkcs->getSelectList(($p ? $p->getErtek() : 0)));

        $bsf = new bizonylatstatuszController($this->params);
        $p = $repo->find(\mkw\consts::BizonylatStatuszFuggoben);
        $view->setVar('bizonylatstatuszfuggobenlist', $bsf->getSelectList(($p ? $p->getErtek() : 0)));
        $p = $repo->find(\mkw\consts::BizonylatStatuszTeljesitheto);
        $view->setVar('bizonylatstatuszteljesithetolist', $bsf->getSelectList(($p ? $p->getErtek() : 0)));
        $p = $repo->find(\mkw\consts::BizonylatStatuszBackorder);
        $view->setVar('bizonylatstatuszbackorderlist', $bsf->getSelectList(($p ? $p->getErtek() : 0)));
        $p = $repo->find(\mkw\consts::MegrendelesFilterStatuszCsoport);
        $view->setVar('megrendelesfilterstatuszcsoportlist', $bsf->getCsoportSelectList(($p ? $p->getErtek() : '')));

        $p = $repo->find(\mkw\consts::Esedekessegalap);
        $view->setVar(\mkw\consts::Esedekessegalap, ($p ? $p->getErtek() : '1'));

        $p = $repo->find(\mkw\consts::Locale);
        $view->setVar('localelist', \mkw\store::getLocaleSelectList(($p ? $p->getErtek() : '')));

        $p = $repo->find(\mkw\consts::ImportNewKatId);
        $inkid = $p ? $p->getErtek() : 0;
        $importnewkat = \mkw\store::getEm()->getRepository('Entities\TermekFa')->find($inkid);
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

        $p = $repo->find(\mkw\consts::MugenraceKatId);
        $inkid = $p ? $p->getErtek() : 0;
        $mugenracekat = \mkw\store::getEm()->getRepository('Entities\TermekFa')->find($inkid);
        if ($mugenracekat) {
            $view->setVar('mugenracekat', array(
                'caption' => $mugenracekat->getNev(),
                'id' => $mugenracekat->getId()
            ));
        }
        else {
            $view->setVar('mugenracekat', array(
                'caption' => '',
                'id' => ''
            ));
        }

        $p = $repo->find(\mkw\consts::ValtozatTipusMeret);
        $meretcs = new termekvaltozatadattipusController($this->params);
        $view->setVar('valtozattipusmeretlist', $meretcs->getSelectList(($p ? $p->getErtek() : 0)));

        $p = $repo->find(\mkw\consts::ValtozatTipusSzin);
//        $szincs = new termekvaltozatadattipusController($this->params);
        $view->setVar('valtozattipusszinlist', $meretcs->getSelectList(($p ? $p->getErtek() : 0)));

        $p = $repo->find(\mkw\consts::RendezendoValtozat);
//        $meretcs = new termekvaltozatadattipusController($this->params);
        $view->setVar('rendezendovaltozatlist', $meretcs->getSelectList(($p ? $p->getErtek() : 0)));

        $p = $repo->find(\mkw\consts::ValtozatSorrend);
        $view->setVar(\mkw\consts::ValtozatSorrend, ($p ? $p->getErtek() : ''));
        $p = $repo->find(\mkw\consts::BizonylatMennyiseg);
        $view->setVar(\mkw\consts::BizonylatMennyiseg, ($p ? $p->getErtek() : 0));
        $p = $repo->find(\mkw\consts::TeljesitmenyKezdoEv);
        $view->setVar(\mkw\consts::TeljesitmenyKezdoEv, ($p ? $p->getErtek() : 0));


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

        $gyarto = new partnerController($this->params);
        $p = $repo->find(\mkw\consts::GyartoBtech);
        $view->setVar('gyartobtechlist', $gyarto->getSzallitoSelectList(($p ? $p->getErtek() : '')));
        $p = $repo->find(\mkw\consts::GyartoDelton);
        $view->setVar('gyartodeltonlist', $gyarto->getSzallitoSelectList(($p ? $p->getErtek() : '')));
        $p = $repo->find(\mkw\consts::GyartoKreativ);
        $view->setVar('gyartokreativlist', $gyarto->getSzallitoSelectList(($p ? $p->getErtek() : '')));
        $p = $repo->find(\mkw\consts::GyartoMaxutov);
        $view->setVar('gyartomaxutovlist', $gyarto->getSzallitoSelectList(($p ? $p->getErtek() : '')));
        $p = $repo->find(\mkw\consts::GyartoReintex);
        $view->setVar('gyartoreintexlist', $gyarto->getSzallitoSelectList(($p ? $p->getErtek() : '')));
        $p = $repo->find(\mkw\consts::GyartoSilko);
        $view->setVar('gyartosilkolist', $gyarto->getSzallitoSelectList(($p ? $p->getErtek() : '')));
        $p = $repo->find(\mkw\consts::GyartoTutisport);
        $view->setVar('gyartotutisportlist', $gyarto->getSzallitoSelectList(($p ? $p->getErtek() : '')));
        $p = $repo->find(\mkw\consts::GyartoKress);
        $view->setVar('gyartokresslist', $gyarto->getSzallitoSelectList(($p ? $p->getErtek() : '')));
        $p = $repo->find(\mkw\consts::GyartoLegavenue);
        $view->setVar('gyartolegavenuelist', $gyarto->getSzallitoSelectList(($p ? $p->getErtek() : '')));
        $p = $repo->find(\mkw\consts::GyartoNomad);
        $view->setVar('gyartonomadlist', $gyarto->getSzallitoSelectList(($p ? $p->getErtek() : '')));

        $p = $repo->find(\mkw\consts::PathBtech);
        $view->setVar(\mkw\consts::PathBtech, ($p ? $p->getErtek() : ''));
        $p = $repo->find(\mkw\consts::PathTutisport);
        $view->setVar(\mkw\consts::PathTutisport, ($p ? $p->getErtek() : ''));
        $p = $repo->find(\mkw\consts::PathSilko);
        $view->setVar(\mkw\consts::PathSilko, ($p ? $p->getErtek() : ''));
        $p = $repo->find(\mkw\consts::PathDelton);
        $view->setVar(\mkw\consts::PathDelton, ($p ? $p->getErtek() : ''));
        $p = $repo->find(\mkw\consts::PathKreativ);
        $view->setVar(\mkw\consts::PathKreativ, ($p ? $p->getErtek() : ''));
        $p = $repo->find(\mkw\consts::PathMaxutov);
        $view->setVar(\mkw\consts::PathMaxutov, ($p ? $p->getErtek() : ''));
        $p = $repo->find(\mkw\consts::PathReintex);
        $view->setVar(\mkw\consts::PathReintex, ($p ? $p->getErtek() : ''));
        $p = $repo->find(\mkw\consts::PathKress);
        $view->setVar(\mkw\consts::PathKress, ($p ? $p->getErtek() : ''));
        $p = $repo->find(\mkw\consts::PathLegavenue);
        $view->setVar(\mkw\consts::PathLegavenue, ($p ? $p->getErtek() : ''));
        $p = $repo->find(\mkw\consts::PathNomad);
        $view->setVar(\mkw\consts::PathNomad, ($p ? $p->getErtek() : ''));

        $p = $repo->find(\mkw\consts::MiniCRMHasznalatban);
        $view->setVar(\mkw\consts::MiniCRMHasznalatban, ($p ? $p->getErtek() : ''));
        $p = $repo->find(\mkw\consts::MiniCRMSystemId);
        $view->setVar(\mkw\consts::MiniCRMSystemId, ($p ? $p->getErtek() : ''));
        $p = $repo->find(\mkw\consts::MiniCRMAPIKey);
        $view->setVar(\mkw\consts::MiniCRMAPIKey, ($p ? $p->getErtek() : ''));
        $p = $repo->find(\mkw\consts::MiniCRMPartnertorzs);
        $view->setVar(\mkw\consts::MiniCRMPartnertorzs, ($p ? $p->getErtek() : ''));
        $p = $repo->find(\mkw\consts::MiniCRMRendezvenyJelentkezes);
        $view->setVar(\mkw\consts::MiniCRMRendezvenyJelentkezes, ($p ? $p->getErtek() : ''));


        $p = $repo->find(\mkw\consts::MugenraceLogo);
        $view->setVar(\mkw\consts::MugenraceLogo, ($p ? $p->getErtek() : ''));
        $p = $repo->find(\mkw\consts::MugenraceFooldalKep);
        $view->setVar(\mkw\consts::MugenraceFooldalKep, ($p ? $p->getErtek() : ''));
        $p = $repo->find(\mkw\consts::MugenraceFooldalSzoveg);
        $view->setVar(\mkw\consts::MugenraceFooldalSzoveg, ($p ? $p->getErtek() : ''));

        $view->setVar('stopkreativimporturl', \mkw\store::getRouter()->generate('adminimportstop', false, array('impname' => 'kreativ')));
        $view->setVar('stopdeltonimporturl', \mkw\store::getRouter()->generate('adminimportstop', false, array('impname' => 'delton')));
        $view->setVar('stopreinteximporturl', \mkw\store::getRouter()->generate('adminimportstop', false, array('impname' => 'reintex')));
        $view->setVar('stoptutisportimporturl', \mkw\store::getRouter()->generate('adminimportstop', false, array('impname' => 'tutisport')));
        $view->setVar('stopmaxutovimporturl', \mkw\store::getRouter()->generate('adminimportstop', false, array('impname' => 'maxutov')));
        $view->setVar('stopsilkoimporturl', \mkw\store::getRouter()->generate('adminimportstop', false, array('impname' => 'silko')));
        $view->setVar('stopbtechimporturl', \mkw\store::getRouter()->generate('adminimportstop', false, array('impname' => 'btech')));
        $view->setVar('stopkressgepimporturl', \mkw\store::getRouter()->generate('adminimportstop', false, array('impname' => 'kressgep')));
        $view->setVar('stopkresstartozekimporturl', \mkw\store::getRouter()->generate('adminimportstop', false, array('impname' => 'kresstartozek')));
        $view->setVar('stoplegavenueimporturl', \mkw\store::getRouter()->generate('adminimportstop', false, array('impname' => 'legavenue')));
        $view->setVar('stopnomadimporturl', \mkw\store::getRouter()->generate('adminimportstop', false, array('impname' => 'nomad')));

        $view->setVar('repairkreativimporturl', \mkw\store::getRouter()->generate('adminimportrepair', false, array('impname' => 'kreativ')));
        $view->setVar('repairdeltonimporturl', \mkw\store::getRouter()->generate('adminimportrepair', false, array('impname' => 'delton')));
        $view->setVar('repairreinteximporturl', \mkw\store::getRouter()->generate('adminimportrepair', false, array('impname' => 'reintex')));
        $view->setVar('repairtutisportimporturl', \mkw\store::getRouter()->generate('adminimportrepair', false, array('impname' => 'tutisport')));
        $view->setVar('repairmaxutovimporturl', \mkw\store::getRouter()->generate('adminimportrepair', false, array('impname' => 'maxutov')));
        $view->setVar('repairsilkoimporturl', \mkw\store::getRouter()->generate('adminimportrepair', false, array('impname' => 'silko')));
        $view->setVar('repairbtechimporturl', \mkw\store::getRouter()->generate('adminimportrepair', false, array('impname' => 'btech')));
        $view->setVar('repairkressimporturl', \mkw\store::getRouter()->generate('adminimportrepair', false, array('impname' => 'kress')));
        $view->setVar('repairlegavenueimporturl', \mkw\store::getRouter()->generate('adminimportrepair', false, array('impname' => 'legavenue')));
        $view->setVar('repairnomadimporturl', \mkw\store::getRouter()->generate('adminimportrepair', false, array('impname' => 'nomad')));
        $view->printTemplateResult();
    }

    private function setObj($par, $value) {
        $en = $this->getEntityName();
        $p = \mkw\store::getEm()->getRepository($en)->find($par);
        if ($value === false) {
            $value = '0';
        }
        if ($value === true) {
            $value = '1';
        }
        if ($p) {
            $p->setErtek($value);
        }
        else {
            $p = new $en();
            $p->setId($par);
            $p->setErtek($value);
        }
        \mkw\store::getEm()->persist($p);
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
        $this->setObj(\mkw\consts::Tulajkisadozo, $this->params->getBoolRequestParam(\mkw\consts::Tulajkisadozo));
        $this->setObj(\mkw\consts::Tulajegyenivallalkozo, $this->params->getBoolRequestParam(\mkw\consts::Tulajegyenivallalkozo));
        $this->setObj(\mkw\consts::Tulajevnev, $this->params->getStringRequestParam(\mkw\consts::Tulajevnev));
        $this->setObj(\mkw\consts::Tulajevnyilvszam, $this->params->getStringRequestParam(\mkw\consts::Tulajevnyilvszam));

        if ($this->params->getStringRequestParam('tulajcrc')) {
            $this->setObj(\mkw\consts::Tulajcrc, md5($this->params->getStringRequestParam('tulajcrc') . \mkw\store::getAdminSalt()));
        }

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

        $szkt = \mkw\store::getEm()->getRepository('Entities\Termek')->find($this->params->getIntRequestParam('szallitasiktgtermek', 0));
        if ($szkt) {
            $this->setObj(\mkw\consts::SzallitasiKtgTermek, $szkt->getId());
        }

        $szm = \mkw\store::getEm()->getRepository('Entities\Szallitasimod')->find($this->params->getIntRequestParam('foxpostszallmod', 0));
        if ($szm) {
            $this->setObj(\mkw\consts::FoxpostSzallitasiMod, $szm->getId());
        }
        else {
            $this->setObj(\mkw\consts::FoxpostSzallitasiMod, '');
        }

        $szm = \mkw\store::getEm()->getRepository('Entities\Szallitasimod')->find($this->params->getIntRequestParam('tofszallmod', 0));
        if ($szm) {
            $this->setObj(\mkw\consts::TOFSzallitasiMod, $szm->getId());
        }
        else {
            $this->setObj(\mkw\consts::TOFSzallitasiMod, '');
        }

        $belsouk = \mkw\store::getEm()->getRepository('Entities\Uzletkoto')->find($this->params->getIntRequestParam('belsouk', 0));
        if ($belsouk) {
            $this->setObj(\mkw\consts::BelsoUzletkoto, $belsouk->getId());
        }
        else {
            $this->setObj(\mkw\consts::BelsoUzletkoto, '');
        }

        $szallfeltsablon = \mkw\store::getEm()->getRepository('Entities\Statlap')->find($this->params->getIntRequestParam('szallitasifeltetelsablon', 0));
        if ($szallfeltsablon) {
            $this->setObj(\mkw\consts::SzallitasiFeltetelSablon, $szallfeltsablon->getId());
        }
        else {
            $this->setObj(\mkw\consts::SzallitasiFeltetelSablon, '');
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
        $this->setObj(\mkw\consts::Fooldalakciostermekdb, $this->params->getIntRequestParam('fooldalakciostermekdb', 1));
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
        $this->setObj(\mkw\consts::FoxpostApiURL, $this->params->getStringRequestParam('foxpostapiurl'));
        $this->setObj(\mkw\consts::FoxpostUsername, $this->params->getStringRequestParam('foxpostusername'));
        $this->setObj(\mkw\consts::FoxpostPassword, $this->params->getStringRequestParam('foxpostpassword'));
        $this->setObj(\mkw\consts::MiniCRMHasznalatban, $this->params->getBoolRequestParam('minicrmhasznalatban'));
        $this->setObj(\mkw\consts::MiniCRMSystemId, $this->params->getStringRequestParam('minicrmsystemid'));
        $this->setObj(\mkw\consts::MiniCRMAPIKey, $this->params->getStringRequestParam('minicrmapikey'));
        $this->setObj(\mkw\consts::MiniCRMPartnertorzs, $this->params->getIntRequestParam('minicrmpartnertorzs'));
        $this->setObj(\mkw\consts::MiniCRMRendezvenyJelentkezes, $this->params->getIntRequestParam('minicrmrendezvenyjelentkezes'));
        $this->setObj(\mkw\consts::KuponElotag, $this->params->getStringRequestParam('kuponelotag'));
        $this->setObj(\mkw\consts::Off, $this->params->getBoolRequestParam(\mkw\consts::Off));

        $vut = \mkw\store::getEm()->getRepository('Entities\Termek')->find($this->params->getIntRequestParam('vasarlasiutalvanytermek', 0));
        if ($vut) {
            $this->setObj(\mkw\consts::VasarlasiUtalvanyTermek, $vut->getId());
        }

        // alapertelmezes
        $fizmod = \mkw\store::getEm()->getRepository('Entities\Fizmod')->find($this->params->getIntRequestParam('fizmod', 0));
        if ($fizmod) {
            $this->setObj(\mkw\consts::Fizmod, $fizmod->getId());
        }
        $fizmod = \mkw\store::getEm()->getRepository('Entities\Fizmod')->find($this->params->getIntRequestParam('utanvetfizmod', 0));
        if ($fizmod) {
            $this->setObj(\mkw\consts::UtanvetFizmod, $fizmod->getId());
        }
        else {
            $this->setObj(\mkw\consts::UtanvetFizmod, '');
        }
        $fizmod = \mkw\store::getEm()->getRepository('Entities\Fizmod')->find($this->params->getIntRequestParam('szepkartyafizmod', 0));
        if ($fizmod) {
            $this->setObj(\mkw\consts::SZEPFizmod, $fizmod->getId());
        }
        else {
            $this->setObj(\mkw\consts::SZEPFizmod, '');
        }
        $fizmod = \mkw\store::getEm()->getRepository('Entities\Fizmod')->find($this->params->getIntRequestParam('sportkartyafizmod', 0));
        if ($fizmod) {
            $this->setObj(\mkw\consts::SportkartyaFizmod, $fizmod->getId());
        }
        else {
            $this->setObj(\mkw\consts::SportkartyaFizmod, '');
        }
        $fizmod = \mkw\store::getEm()->getRepository('Entities\Fizmod')->find($this->params->getIntRequestParam('aycmfizmod', 0));
        if ($fizmod) {
            $this->setObj(\mkw\consts::AYCMFizmod, $fizmod->getId());
        }
        else {
            $this->setObj(\mkw\consts::AYCMFizmod, '');
        }

        $szallmod = \mkw\store::getEm()->getRepository('Entities\Szallitasimod')->find($this->params->getIntRequestParam('szallitasimod', 0));
        if ($szallmod) {
            $this->setObj(\mkw\consts::Szallitasimod, $szallmod->getId());
        }
        if (\mkw\store::isOTPay()) {
            $fizmod = \mkw\store::getEm()->getRepository('Entities\Fizmod')->find($this->params->getIntRequestParam('otpayfizmod', 0));
            if ($fizmod) {
                $this->setObj(\mkw\consts::OTPayFizmod, $fizmod->getId());
            }
            else {
                $this->setObj(\mkw\consts::OTPayFizmod, '');
            }
        }
        if (\mkw\store::isMasterPass()) {
            $fizmod = \mkw\store::getEm()->getRepository('Entities\Fizmod')->find($this->params->getIntRequestParam('masterpassfizmod', 0));
            if ($fizmod) {
                $this->setObj(\mkw\consts::MasterPassFizmod, $fizmod->getId());
            }
            else {
                $this->setObj(\mkw\consts::MasterPassFizmod, '');
            }
        }
        $afa = \mkw\store::getEm()->getRepository('Entities\Afa')->find($this->params->getIntRequestParam('nullasafa', 0));
        if ($afa) {
            $this->setObj(\mkw\consts::NullasAfa, $afa->getId());
        }
        $raktar = \mkw\store::getEm()->getRepository('Entities\Raktar')->find($this->params->getIntRequestParam('raktar', 0));
        if ($raktar) {
            $this->setObj(\mkw\consts::Raktar, $raktar->getId());
        }
        $valutanem = \mkw\store::getEm()->getRepository('Entities\Valutanem')->find($this->params->getIntRequestParam('valutanem', 0));
        if ($valutanem) {
            $this->setObj(\mkw\consts::Valutanem, $valutanem->getId());
        }

        $valutanem = \mkw\store::getEm()->getRepository('Entities\Valutanem')->find($this->params->getIntRequestParam('showtermekarsavvalutanem', 0));
        if ($valutanem) {
            $this->setObj(\mkw\consts::ShowTermekArsavValutanem, $valutanem->getId());
        }

        $this->setObj(\mkw\consts::Arsav, $this->params->getStringRequestParam('arsav'));
        $this->setObj(\mkw\consts::ShowTermekArsav, $this->params->getStringRequestParam('showtermekarsav'));
        $this->setObj(\mkw\consts::Webshop2Price, $this->params->getStringRequestParam('arsav2'));
        $this->setObj(\mkw\consts::Webshop2Discount, $this->params->getStringRequestParam('akciosarsav2'));
        $this->setObj(\mkw\consts::Webshop3Price, $this->params->getStringRequestParam('arsav3'));
        $this->setObj(\mkw\consts::Webshop3Discount, $this->params->getStringRequestParam('akciosarsav3'));

        $markacs = \mkw\store::getEm()->getRepository('Entities\Termekcimkekat')->find($this->params->getIntRequestParam('markacs', 0));
        if ($markacs) {
            $this->setObj(\mkw\consts::MarkaCs, $markacs->getId());
        }
        else {
            $this->setObj(\mkw\consts::MarkaCs, '');
        }

        $kiskercimke = \mkw\store::getEm()->getRepository('Entities\Partnercimketorzs')->find($this->params->getIntRequestParam('kiskercimke', 0));
        if ($kiskercimke) {
            $this->setObj(\mkw\consts::KiskerCimke, $kiskercimke->getId());
        }
        else {
            $this->setObj(\mkw\consts::KiskerCimke, '');
        }

        $spanyolcimke = \mkw\store::getEm()->getRepository('Entities\Partnercimketorzs')->find($this->params->getIntRequestParam('spanyolcimke', 0));
        if ($spanyolcimke) {
            $this->setObj(\mkw\consts::SpanyolCimke, $spanyolcimke->getId());
        }
        else {
            $this->setObj(\mkw\consts::SpanyolCimke, '');
        }

        $spanyolorszag = \mkw\store::getEm()->getRepository('Entities\Orszag')->find($this->params->getIntRequestParam('spanyolorszag', 0));
        if ($spanyolorszag) {
            $this->setObj(\mkw\consts::Spanyolorszag, $spanyolorszag->getId());
        }
        else {
            $this->setObj(\mkw\consts::Spanyolorszag, '');
        }

        $sz = \mkw\store::getEm()->getRepository('Entities\TermekValtozatAdatTipus')->find($this->params->getIntRequestParam('valtozattipusszin', 0));
        if ($sz) {
            $this->setObj(\mkw\consts::ValtozatTipusSzin, $sz->getId());
        }
        else {
            $this->setObj(\mkw\consts::ValtozatTipusSzin, '');
        }

        $sz = \mkw\store::getEm()->getRepository('Entities\TermekValtozatAdatTipus')->find($this->params->getIntRequestParam('valtozattipusmeret', 0));
        if ($sz) {
            $this->setObj(\mkw\consts::ValtozatTipusMeret, $sz->getId());
        }
        else {
            $this->setObj(\mkw\consts::ValtozatTipusMeret, '');
        }

        $sz = \mkw\store::getEm()->getRepository('Entities\TermekValtozatAdatTipus')->find($this->params->getIntRequestParam('rendezendovaltozat', 0));
        if ($sz) {
            $this->setObj(\mkw\consts::RendezendoValtozat, $sz->getId());
        }
        else {
            $this->setObj(\mkw\consts::RendezendoValtozat, '');
        }

        $this->setObj(\mkw\consts::ValtozatSorrend, $this->params->getStringRequestParam('valtozatsorrend'));
        $this->setObj(\mkw\consts::BizonylatMennyiseg, $this->params->getStringRequestParam(\mkw\consts::BizonylatMennyiseg));
        $this->setObj(\mkw\consts::TeljesitmenyKezdoEv, $this->params->getStringRequestParam(\mkw\consts::TeljesitmenyKezdoEv));

        $rolerep = \mkw\store::getEm()->getRepository('Entities\Munkakor');
        $role = $rolerep->find($this->params->getIntRequestParam('adminrole', 0));
        if ($role) {
            $this->setObj(\mkw\consts::AdminRole, $role->getId());
        }
        $role = $rolerep->find($this->params->getIntRequestParam('termekfeltoltorole', 0));
        if ($role) {
            $this->setObj(\mkw\consts::TermekfeltoltoRole, $role->getId());
        }

        $bsf = \mkw\store::getEm()->getRepository('Entities\Bizonylatstatusz')->find($this->params->getIntRequestParam('bizonylatstatuszfuggoben', 0));
        if ($bsf) {
            $this->setObj(\mkw\consts::BizonylatStatuszFuggoben, $bsf->getId());
        }
        else {
            $this->setObj(\mkw\consts::BizonylatStatuszFuggoben, '');
        }
        $bsf = \mkw\store::getEm()->getRepository('Entities\Bizonylatstatusz')->find($this->params->getIntRequestParam('bizonylatstatuszteljesitheto', 0));
        if ($bsf) {
            $this->setObj(\mkw\consts::BizonylatStatuszTeljesitheto, $bsf->getId());
        }
        else {
            $this->setObj(\mkw\consts::BizonylatStatuszTeljesitheto, '');
        }
        $bsf = \mkw\store::getEm()->getRepository('Entities\Bizonylatstatusz')->find($this->params->getIntRequestParam('bizonylatstatuszbackorder', 0));
        if ($bsf) {
            $this->setObj(\mkw\consts::BizonylatStatuszBackorder, $bsf->getId());
        }
        else {
            $this->setObj(\mkw\consts::BizonylatStatuszBackorder, '');
        }
        $this->setObj(\mkw\consts::MegrendelesFilterStatuszCsoport, $this->params->getStringRequestParam('megrendelesfilterstatuszcsoport'));

        $this->setObj(\mkw\consts::Esedekessegalap, $this->params->getIntRequestParam('esedekessegalap', 1));
        $this->setObj(\mkw\consts::Locale, $this->params->getStringRequestParam('locale'));

        $inkid = $this->params->getIntRequestParam('importnewkatid');
        if ($inkid) {
            $importnewkat = \mkw\store::getEm()->getRepository('Entities\TermekFa')->find($inkid);
            $this->setObj(\mkw\consts::ImportNewKatId, $importnewkat->getId());
        }
        else {
            $this->setObj(\mkw\consts::ImportNewKatId, 0);
        }
        $inkid = $this->params->getIntRequestParam('mugenracekatid');
        if ($inkid) {
            $mugenracekat = \mkw\store::getEm()->getRepository('Entities\TermekFa')->find($inkid);
            $this->setObj(\mkw\consts::MugenraceKatId, $mugenracekat->getId());
        }
        else {
            $this->setObj(\mkw\consts::MugenraceKatId, 0);
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

        $gyarto = \mkw\store::getEm()->getRepository('Entities\Partner');

        $x = $this->params->getIntRequestParam('gyartobtech', 0);
        $partner = $gyarto->find($x);
        if ($partner) {
            $this->setObj(\mkw\consts::GyartoBtech, $partner->getId());
        }
        else {
            $this->setObj(\mkw\consts::GyartoBtech, '');
        }
        $x = $this->params->getIntRequestParam('gyartodelton', 0);
        $partner = $gyarto->find($x);
        if ($partner) {
            $this->setObj(\mkw\consts::GyartoDelton, $partner->getId());
        }
        else {
            $this->setObj(\mkw\consts::GyartoDelton, '');
        }
        $x = $this->params->getIntRequestParam('gyartokreativ', 0);
        $partner = $gyarto->find($x);
        if ($partner) {
            $this->setObj(\mkw\consts::GyartoKreativ, $partner->getId());
        }
        else {
            $this->setObj(\mkw\consts::GyartoKreativ, '');
        }
        $x = $this->params->getIntRequestParam('gyartomaxutov', 0);
        $partner = $gyarto->find($x);
        if ($partner) {
            $this->setObj(\mkw\consts::GyartoMaxutov, $partner->getId());
        }
        else {
            $this->setObj(\mkw\consts::GyartoMaxutov, '');
        }
        $x = $this->params->getIntRequestParam('gyartoreintex', 0);
        $partner = $gyarto->find($x);
        if ($partner) {
            $this->setObj(\mkw\consts::GyartoReintex, $partner->getId());
        }
        else {
            $this->setObj(\mkw\consts::GyartoReintex, '');
        }
        $x = $this->params->getIntRequestParam('gyartotutisport', 0);
        $partner = $gyarto->find($x);
        if ($partner) {
            $this->setObj(\mkw\consts::GyartoTutisport, $partner->getId());
        }
        else {
            $this->setObj(\mkw\consts::GyartoTutisport, '');
        }
        $x = $this->params->getIntRequestParam('gyartosilko', 0);
        $partner = $gyarto->find($x);
        if ($partner) {
            $this->setObj(\mkw\consts::GyartoSilko, $partner->getId());
        }
        else {
            $this->setObj(\mkw\consts::GyartoSilko, '');
        }
        $x = $this->params->getIntRequestParam('gyartokress', 0);
        $partner = $gyarto->find($x);
        if ($partner) {
            $this->setObj(\mkw\consts::GyartoKress, $partner->getId());
        }
        else {
            $this->setObj(\mkw\consts::GyartoKress, '');
        }
        $x = $this->params->getIntRequestParam('gyartolegavenue', 0);
        $partner = $gyarto->find($x);
        if ($partner) {
            $this->setObj(\mkw\consts::GyartoLegavenue, $partner->getId());
        }
        else {
            $this->setObj(\mkw\consts::GyartoLegavenue, '');
        }
        $x = $this->params->getIntRequestParam('gyartonomad', 0);
        $partner = $gyarto->find($x);
        if ($partner) {
            $this->setObj(\mkw\consts::GyartoNomad, $partner->getId());
        }
        else {
            $this->setObj(\mkw\consts::GyartoNomad, '');
        }

        $this->setObj(\mkw\consts::PathBtech, $this->params->getStringRequestParam('pathbtech', ''));
        $this->setObj(\mkw\consts::PathDelton, $this->params->getStringRequestParam('pathdelton', ''));
        $this->setObj(\mkw\consts::PathKreativ, $this->params->getStringRequestParam('pathkreativ', ''));
        $this->setObj(\mkw\consts::PathMaxutov, $this->params->getStringRequestParam('pathmaxutov', ''));
        $this->setObj(\mkw\consts::PathReintex, $this->params->getStringRequestParam('pathreintex', ''));
        $this->setObj(\mkw\consts::PathSilko, $this->params->getStringRequestParam('pathsilko', ''));
        $this->setObj(\mkw\consts::PathTutisport, $this->params->getStringRequestParam('pathtutisport', ''));
        $this->setObj(\mkw\consts::PathKress, $this->params->getStringRequestParam('pathkress', ''));
        $this->setObj(\mkw\consts::PathLegavenue, $this->params->getStringRequestParam('pathlegavenue', ''));
        $this->setObj(\mkw\consts::PathNomad, $this->params->getStringRequestParam('pathnomad', ''));

        $this->setObj(\mkw\consts::MugenraceLogo, $this->params->getStringRequestParam('mugenracelogo'));
        $this->setObj(\mkw\consts::MugenraceFooldalKep, $this->params->getStringRequestParam('mugenracefooldalkep'));
        $this->setObj(\mkw\consts::MugenraceFooldalSzoveg, $this->params->getStringRequestParam('mugenracefooldalszoveg'));

        \mkw\store::getEm()->flush();

    }

}
