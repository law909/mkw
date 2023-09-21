<?php

namespace Controllers;

use Entities\Emailtemplate;
use Entities\Jogaoratipus;
use Entities\MPTNGYSzakmaianyagtipus;
use Entities\Parameterek;
use Entities\Partner;
use Entities\Statlap;
use Entities\Szallitasimod;
use Entities\Termek;
use Entities\Uzletkoto;

class setupController extends \mkwhelpers\Controller
{

    public function __construct($params)
    {
        $this->setEntityName(Parameterek::class);
        parent::__construct($params);
    }

    public function view()
    {
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
        $p = $repo->find(\mkw\consts::Tulajjovengszam);
        $view->setVar(\mkw\consts::Tulajjovengszam, ($p ? $p->getErtek() : ''));
        $p = $repo->find(\mkw\consts::Tulajevnev);
        $view->setVar(\mkw\consts::Tulajevnev, ($p ? $p->getErtek() : ''));

        $p = $repo->find(\mkw\consts::TulajKontaktNev);
        $view->setVar(\mkw\consts::TulajKontaktNev, ($p ? $p->getErtek() : ''));
        $p = $repo->find(\mkw\consts::TulajKontaktEmail);
        $view->setVar(\mkw\consts::TulajKontaktEmail, ($p ? $p->getErtek() : ''));
        $p = $repo->find(\mkw\consts::TulajKontaktTelefon);
        $view->setVar(\mkw\consts::TulajKontaktTelefon, ($p ? $p->getErtek() : ''));

        $p = $repo->find(\mkw\consts::ProgramNev);
        $view->setVar(\mkw\consts::ProgramNev, ($p ? $p->getErtek() : ''));
        $tulajpartner = new partnerController($this->params);
        $p = $repo->find(\mkw\consts::Tulajpartner);
        $view->setVar('tulajpartnerlist', $tulajpartner->getSzallitoSelectList(($p ? $p->getErtek() : '')));

//        $p = $repo->find(\mkw\consts::Tulajcrc);
//        $view->setVar(\mkw\consts::Tulajcrc, ($p ? $p->getErtek() : ''));

        $p = $repo->find(\mkw\consts::EmailFrom);
        $view->setVar(\mkw\consts::EmailFrom, ($p ? $p->getErtek() : ''));
        $p = $repo->find(\mkw\consts::EmailReplyTo);
        $view->setVar(\mkw\consts::EmailReplyTo, ($p ? $p->getErtek() : ''));
        $p = $repo->find(\mkw\consts::EmailBcc);
        $view->setVar(\mkw\consts::EmailBcc, ($p ? $p->getErtek() : ''));
        $p = $repo->find(\mkw\consts::EmailStatuszValtas);
        $view->setVar(\mkw\consts::EmailStatuszValtas, ($p ? $p->getErtek() : ''));
        $p = $repo->find(\mkw\consts::KonyveloEmail);
        $view->setVar(\mkw\consts::KonyveloEmail, ($p ? $p->getErtek() : ''));

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

        $p = $repo->find(\mkw\consts::Blogoldalcim);
        $view->setVar(\mkw\consts::Blogoldalcim, ($p ? $p->getErtek() : ''));
        $p = $repo->find(\mkw\consts::Blogseodescription);
        $view->setVar(\mkw\consts::Blogseodescription, ($p ? $p->getErtek() : ''));
        $p = $repo->find(\mkw\consts::Blogposztdb);
        $view->setVar(\mkw\consts::Blogposztdb, ($p ? $p->getErtek() : 15));
        $p = $repo->find(\mkw\consts::BlogposztTermeklapdb);
        $view->setVar(\mkw\consts::BlogposztTermeklapdb, ($p ? $p->getErtek() : 3));
        $p = $repo->find(\mkw\consts::BlogposztKategoriadb);
        $view->setVar(\mkw\consts::BlogposztKategoriadb, ($p ? $p->getErtek() : 3));

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
        $p = $repo->find(\mkw\consts::Watermark);
        $view->setVar(\mkw\consts::Watermark, ($p ? $p->getErtek() : ''));

        $p = $repo->find(\mkw\consts::MPTNGYSzimpoziumTipus);
        $sza = new mptngyszakmaianyagtipusController($this->params);
        $view->setVar('mptngyszakmaianyagtipuslist', $sza->getSelectList(($p ? $p->getErtek() : 0)));
        $p = $repo->find(\mkw\consts::MPTNGYSzimpoziumEloadasTipus);
        $view->setVar('mptngyszatipuslist', $sza->getSelectList(($p ? $p->getErtek() : 0)));
        $p = $repo->find(\mkw\consts::MPTNGYKonyvbemutatoTipus);
        $view->setVar('mptngykonyvbemutatotipuslist', $sza->getSelectList(($p ? $p->getErtek() : 0)));

        $p = $repo->find(\mkw\consts::MPTNGYDatum1);
        $view->setVar(\mkw\consts::MPTNGYDatum1, ($p ? $p->getErtek() : ''));
        $p = $repo->find(\mkw\consts::MPTNGYDatum2);
        $view->setVar(\mkw\consts::MPTNGYDatum2, ($p ? $p->getErtek() : ''));
        $p = $repo->find(\mkw\consts::MPTNGYDatum3);
        $view->setVar(\mkw\consts::MPTNGYDatum3, ($p ? $p->getErtek() : ''));

        $p = $repo->find(\mkw\consts::MPTNGYSzempont1);
        $view->setVar(\mkw\consts::MPTNGYSzempont1, ($p ? $p->getErtek() : ''));
        $p = $repo->find(\mkw\consts::MPTNGYSzempont2);
        $view->setVar(\mkw\consts::MPTNGYSzempont2, ($p ? $p->getErtek() : ''));
        $p = $repo->find(\mkw\consts::MPTNGYSzempont3);
        $view->setVar(\mkw\consts::MPTNGYSzempont3, ($p ? $p->getErtek() : ''));
        $p = $repo->find(\mkw\consts::MPTNGYSzempont4);
        $view->setVar(\mkw\consts::MPTNGYSzempont4, ($p ? $p->getErtek() : ''));
        $p = $repo->find(\mkw\consts::MPTNGYSzempont5);
        $view->setVar(\mkw\consts::MPTNGYSzempont5, ($p ? $p->getErtek() : ''));

        $p = $repo->find(\mkw\consts::SzallitasiFeltetelSablon);
        $szallstatlap = new statlapController($this->params);
        $view->setVar('szallitasifeltetelstatlaplist', $szallstatlap->getSelectList(($p ? $p->getErtek() : 0)));

        $p = $repo->find(\mkw\consts::SzamlalevelSablon);
        $szamlalevelsablon = new emailtemplateController($this->params);
        $view->setVar('szamlalevelsablonlist', $szamlalevelsablon->getSelectList(($p ? $p->getErtek() : 0)));

        $p = $repo->find(\mkw\consts::KonyvelolevelSablon);
        $konyvelolevelsablon = new emailtemplateController($this->params);
        $view->setVar('konyvelolevelsablonlist', $konyvelolevelsablon->getSelectList(($p ? $p->getErtek() : 0)));

        $p = $repo->find(\mkw\consts::ErtekelesErtesitoSablon);
        $eesablon = new emailtemplateController($this->params);
        $view->setVar('ertekelesertesitosablonlist', $eesablon->getSelectList(($p ? $p->getErtek() : 0)));

        $p = $repo->find(\mkw\consts::ErtekelesKeroSablon);
        $eesablon = new emailtemplateController($this->params);
        $view->setVar('ertekeleskerosablonlist', $eesablon->getSelectList(($p ? $p->getErtek() : 0)));

        $p = $repo->find(\mkw\consts::RendezvenySablonFelszabadultHelyErtesito);
        $rsdsablon = new emailtemplateController($this->params);
        $view->setVar('rendezvenysablonfelszabadulthelyertesitolist', $rsdsablon->getSelectList(($p ? $p->getErtek() : 0)));

        $p = $repo->find(\mkw\consts::RendezvenySablonDijbekero);
        $rsdsablon = new emailtemplateController($this->params);
        $view->setVar('rendezvenysablondijbekerolist', $rsdsablon->getSelectList(($p ? $p->getErtek() : 0)));

        $p = $repo->find(\mkw\consts::RendezvenySablonRegKoszono);
        $rsrksablon = new emailtemplateController($this->params);
        $view->setVar('rendezvenysablonregkoszonolist', $rsrksablon->getSelectList(($p ? $p->getErtek() : 0)));

        $p = $repo->find(\mkw\consts::RendezvenySablonRegErtesito);
        $rsresablon = new emailtemplateController($this->params);
        $view->setVar('rendezvenysablonregertesitolist', $rsresablon->getSelectList(($p ? $p->getErtek() : 0)));

        $p = $repo->find(\mkw\consts::RendezvenyRegErtesitoEmail);
        $view->setVar(\mkw\consts::RendezvenyRegErtesitoEmail, ($p ? $p->getErtek() : 0));

        $p = $repo->find(\mkw\consts::RendezvenySablonFizetesKoszono);
        $rsfksablon = new emailtemplateController($this->params);
        $view->setVar('rendezvenysablonfizeteskoszonolist', $rsfksablon->getSelectList(($p ? $p->getErtek() : 0)));

        $p = $repo->find(\mkw\consts::RendezvenySablonKezdesEmlekezteto);
        $rskesablon = new emailtemplateController($this->params);
        $view->setVar('rendezvenysablonkezdesemlekeztetolist', $rskesablon->getSelectList(($p ? $p->getErtek() : 0)));

        $p = $repo->find(\mkw\consts::JogaBerletFelszolitoSablon);
        $rskesablon = new emailtemplateController($this->params);
        $view->setVar('jogaberletfelszolitosablonlist', $rskesablon->getSelectList(($p ? $p->getErtek() : 0)));

        $p = $repo->find(\mkw\consts::JogaBerletKoszonoSablon);
        $rskesablon = new emailtemplateController($this->params);
        $view->setVar('jogaberletkoszonosablonlist', $rskesablon->getSelectList(($p ? $p->getErtek() : 0)));

        $p = $repo->find(\mkw\consts::JogaBerletLefogjarniSablon);
        $rskesablon = new emailtemplateController($this->params);
        $view->setVar('jogaberletlefogjarnisablonlist', $rskesablon->getSelectList(($p ? $p->getErtek() : 0)));

        $p = $repo->find(\mkw\consts::JogaBerletLejartSablon);
        $rskesablon = new emailtemplateController($this->params);
        $view->setVar('jogaberletlejartsablonlist', $rskesablon->getSelectList(($p ? $p->getErtek() : 0)));

        $p = $repo->find(\mkw\consts::JogaBerletDatumLejartSablon);
        $rskesablon = new emailtemplateController($this->params);
        $view->setVar('jogaberletdatumlejartsablonlist', $rskesablon->getSelectList(($p ? $p->getErtek() : 0)));

        $p = $repo->find(\mkw\consts::JogaLemondasKoszonoSablon);
        $rskesablon = new emailtemplateController($this->params);
        $view->setVar('jogalemondaskoszonosablonlist', $rskesablon->getSelectList(($p ? $p->getErtek() : 0)));

        $p = $repo->find(\mkw\consts::JogaElmaradasErtesitoSablon);
        $rskesablon = new emailtemplateController($this->params);
        $view->setVar('jogaelmaradasertesitosablonlist', $rskesablon->getSelectList(($p ? $p->getErtek() : 0)));

        $p = $repo->find(\mkw\consts::JogaBejelentkezesErtesitoSablon);
        $rskesablon = new emailtemplateController($this->params);
        $view->setVar('jogabejelentkezesertesitosablonlist', $rskesablon->getSelectList(($p ? $p->getErtek() : 0)));

        $p = $repo->find(\mkw\consts::JogaLemondasErtesitoSablon);
        $rskesablon = new emailtemplateController($this->params);
        $view->setVar('jogalemondasertesitosablonlist', $rskesablon->getSelectList(($p ? $p->getErtek() : 0)));

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
        $p = $repo->find(\mkw\consts::GLSSzallitasiMod);
        $view->setVar('glsszallmodlist', $szallmod->getSelectList(($p ? $p->getErtek() : 0), true));
        $p = $repo->find(\mkw\consts::GLSFutarSzallitasmod);
        $view->setVar('glsfutarszallmodlist', $szallmod->getSelectList(($p ? $p->getErtek() : 0), true));
        $p = $repo->find(\mkw\consts::ArukeresoExportSzallmod);
        $view->setVar('arukeresoexportszallmodlist', $szallmod->getSelectList(($p ? $p->getErtek() : 0), true));

        $p = $repo->find(\mkw\consts::NullasAfa);
        $fizmod = new afaController($this->params);
        $view->setVar('nullasafalist', $fizmod->getSelectList(($p ? $p->getErtek() : 0)));

        $p = $repo->find(\mkw\consts::BelsoUzletkoto);
        $uk = new uzletkotoController($this->params);
        $fofilter = new \mkwhelpers\FilterDescriptor();
        $fofilter->addFilter('belso', '=', true);
        $view->setVar('belsouklist', $uk->getSelectList(($p ? $p->getErtek() : 0), $fofilter));

        $p = $repo->find(\mkw\consts::ASZFUrl);
        $view->setVar(\mkw\consts::ASZFUrl, ($p ? $p->getErtek() : ''));

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
        $p = $repo->find(\mkw\consts::GMapsApiKey);
        $view->setVar(\mkw\consts::GMapsApiKey, ($p ? $p->getErtek() : ''));
        $p = $repo->find(\mkw\consts::FBAppId);
        $view->setVar(\mkw\consts::FBAppId, ($p ? $p->getErtek() : ''));

        $p = $repo->find(\mkw\consts::FoxpostApiURL);
        $view->setVar(\mkw\consts::FoxpostApiURL, ($p ? $p->getErtek() : ''));
        $p = $repo->find(\mkw\consts::FoxpostUsername);
        $view->setVar(\mkw\consts::FoxpostUsername, ($p ? $p->getErtek() : ''));
        $p = $repo->find(\mkw\consts::FoxpostPassword);
        $view->setVar(\mkw\consts::FoxpostPassword, ($p ? $p->getErtek() : ''));
        $p = $repo->find(\mkw\consts::FoxpostApiVersion);
        $view->setVar('foxpostapiversionlist', \mkw\store::getFoxpostAPIVersionSelectList($p ? $p->getErtek() : ''));
        $p = $repo->find(\mkw\consts::Foxpostv2ApiURL);
        $view->setVar(\mkw\consts::Foxpostv2ApiURL, ($p ? $p->getErtek() : ''));
        $p = $repo->find(\mkw\consts::Foxpostv2Username);
        $view->setVar(\mkw\consts::Foxpostv2Username, ($p ? $p->getErtek() : ''));
        $p = $repo->find(\mkw\consts::Foxpostv2Password);
        $view->setVar(\mkw\consts::Foxpostv2Password, ($p ? $p->getErtek() : ''));
        $p = $repo->find(\mkw\consts::Foxpostv2ApiKey);
        $view->setVar(\mkw\consts::Foxpostv2ApiKey, ($p ? $p->getErtek() : ''));

        $p = $repo->find(\mkw\consts::GLSApiURL);
        $view->setVar(\mkw\consts::GLSApiURL, ($p ? $p->getErtek() : ''));
        $p = $repo->find(\mkw\consts::GLSParcelLabelDir);
        $view->setVar(\mkw\consts::GLSParcelLabelDir, ($p ? $p->getErtek() : ''));
        $p = $repo->find(\mkw\consts::GLSUsername);
        $view->setVar(\mkw\consts::GLSUsername, ($p ? $p->getErtek() : ''));
        $p = $repo->find(\mkw\consts::GLSClientNumber);
        $view->setVar(\mkw\consts::GLSClientNumber, ($p ? $p->getErtek() : ''));
        $p = $repo->find(\mkw\consts::GLSPassword);
        $view->setVar(\mkw\consts::GLSPassword, ($p ? $p->getErtek() : ''));
        $p = $repo->find(\mkw\consts::EmagAPIUrl);
        $view->setVar(\mkw\consts::EmagAPIUrl, ($p ? $p->getErtek() : ''));
        $p = $repo->find(\mkw\consts::EmagUsername);
        $view->setVar(\mkw\consts::EmagUsername, ($p ? $p->getErtek() : ''));
        $p = $repo->find(\mkw\consts::EmagUsercode);
        $view->setVar(\mkw\consts::EmagUsercode, ($p ? $p->getErtek() : ''));
        $p = $repo->find(\mkw\consts::EmagPassword);
        $view->setVar(\mkw\consts::EmagPassword, ($p ? $p->getErtek() : ''));
        $p = $repo->find(\mkw\consts::GLSTerminalURL);
        $view->setVar(\mkw\consts::GLSTerminalURL, ($p ? $p->getErtek() : ''));
        $p = $repo->find(\mkw\consts::KuponElotag);
        $view->setVar(\mkw\consts::KuponElotag, ($p ? $p->getErtek() : 'MKW'));
        $p = $repo->find(\mkw\consts::Off);
        $view->setVar(\mkw\consts::Off, ($p ? $p->getErtek() : 0));
        $p = $repo->find(\mkw\consts::Off2);
        $view->setVar(\mkw\consts::Off2, ($p ? $p->getErtek() : 0));
        $p = $repo->find(\mkw\consts::Off3);
        $view->setVar(\mkw\consts::Off3, ($p ? $p->getErtek() : 0));
        $p = $repo->find(\mkw\consts::Off4);
        $view->setVar(\mkw\consts::Off4, ($p ? $p->getErtek() : 0));
        $p = $repo->find(\mkw\consts::Off5);
        $view->setVar(\mkw\consts::Off5, ($p ? $p->getErtek() : 0));
        $p = $repo->find(\mkw\consts::JogaUresTeremJutalek);
        $view->setVar(\mkw\consts::JogaUresTeremJutalek, ($p ? $p->getErtek() : 0));
        $p = $repo->find(\mkw\consts::JogaJutalek);
        $view->setVar(\mkw\consts::JogaJutalek, ($p ? $p->getErtek() : 0));
        $p = $repo->find(\mkw\consts::JogaAYCMJutalek);
        $view->setVar(\mkw\consts::JogaAYCMJutalek, ($p ? $p->getErtek() : 0));
        $p = $repo->find(\mkw\consts::JogaTanarelszamolasSablon);
        $tanarelszsablon = new emailtemplateController($this->params);
        $view->setVar('tanarelszamolassablonlist', $tanarelszsablon->getSelectList(($p ? $p->getErtek() : 0)));
        $p = $repo->find(\mkw\consts::JogaNemjonsenkiSablon);
        $view->setVar('nemjonsenkisablonlist', $tanarelszsablon->getSelectList(($p ? $p->getErtek() : 0)));
        $p = $repo->find(\mkw\consts::JogaNemjelenteztekelegenTanarnakSablon);
        $view->setVar('nemjelentkeztekelegentanarnaksablonlist', $tanarelszsablon->getSelectList(($p ? $p->getErtek() : 0)));
        $p = $repo->find(\mkw\consts::JogaNemjelentkeztekelegenGyakorlonakSablon);
        $view->setVar('nemjelentkeztekelegengyakorlonaksablonlist', $tanarelszsablon->getSelectList(($p ? $p->getErtek() : 0)));
        $p = $repo->find(\mkw\consts::JogaElegenjelentkeztekTanarnakSablon);
        $view->setVar('elegenjelentkeztektanarnaksablonlist', $tanarelszsablon->getSelectList(($p ? $p->getErtek() : 0)));
        $p = $repo->find(\mkw\consts::JogaBejelentkezesKoszonoSablon);
        $view->setVar('jogabejelentkezeskoszonosablonlist', $tanarelszsablon->getSelectList(($p ? $p->getErtek() : 0)));
        $p = $repo->find(\mkw\consts::JogaElmaradasKonyvelonekSablon);
        $view->setVar('jogaelmaradaskonyveloneksablonlist', $tanarelszsablon->getSelectList(($p ? $p->getErtek() : 0)));

        $p = $repo->find(\mkw\consts::BarionAPIVersion);
        $view->setVar(\mkw\consts::BarionAPIVersion, ($p ? $p->getErtek() : 0));
        $p = $repo->find(\mkw\consts::BarionPOSKey);
        $view->setVar(\mkw\consts::BarionPOSKey, ($p ? $p->getErtek() : 0));
        $p = $repo->find(\mkw\consts::BarionPayeeEmail);
        $view->setVar(\mkw\consts::BarionPayeeEmail, ($p ? $p->getErtek() : 0));
        $p = $repo->find(\mkw\consts::BarionEnvironment);
        $view->setVar('barionenvironmentlist', \mkw\store::getBarionEnvironmentSelectList($p ? (int)$p->getErtek() : 0));

        $p = $repo->find(\mkw\consts::SzamlaOrzesAlap);
        $view->setVar(\mkw\consts::SzamlaOrzesAlap, ($p ? $p->getErtek() : 0));
        $p = $repo->find(\mkw\consts::SzamlaOrzesEv);
        $view->setVar(\mkw\consts::SzamlaOrzesEv, ($p ? $p->getErtek() : 0));


        $p = $repo->find(\mkw\consts::VasarlasiUtalvanyTermek);
//        $termek = new termekController($this->params);
        $view->setVar('vasarlasiutalvanytermeklist', $termek->getSelectList(($p ? $p->getErtek() : 0)));
        $p = $repo->find(\mkw\consts::JogaOrajegyTermek);
        $view->setVar('jogaorajegytermeklist', $termek->getSelectList(($p ? $p->getErtek() : 0)));
        $p = $repo->find(\mkw\consts::JogaBerlet4Termek);
        $view->setVar('jogaberlet4termeklist', $termek->getSelectList(($p ? $p->getErtek() : 0)));
        $p = $repo->find(\mkw\consts::JogaBerlet10Termek);
        $view->setVar('jogaberlet10termeklist', $termek->getSelectList(($p ? $p->getErtek() : 0)));

        $jtc = new jogaoratipusController($this->params);
        $p = $repo->find(\mkw\consts::JogaAllapotfelmeresTipus);
        $view->setVar('jogaallapotfelmerestipuslist', $jtc->getSelectList(($p ? $p->getErtek() : 0)));

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
        $p = $repo->find(\mkw\consts::BarionFizmod);
        $view->setVar('barionfizmodlist', $fizmod->getSelectList(($p ? $p->getErtek() : 0)));

        $p = $repo->find(\mkw\consts::MunkaJelenlet);
        $c = new jelenlettipusController($this->params);
        $view->setVar('munkajelenletlist', $c->getSelectList(($p ? $p->getErtek() : 0)));

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

        $p = $repo->find(\mkw\consts::Webshop1Name);
        $view->setVar(\mkw\consts::Webshop1Name, ($p ? $p->getErtek() : ''));
        $p = $repo->find(\mkw\consts::Webshop2Name);
        $view->setVar(\mkw\consts::Webshop2Name, ($p ? $p->getErtek() : ''));
        $p = $repo->find(\mkw\consts::Webshop3Name);
        $view->setVar(\mkw\consts::Webshop3Name, ($p ? $p->getErtek() : ''));
        $p = $repo->find(\mkw\consts::Webshop4Name);
        $view->setVar(\mkw\consts::Webshop4Name, ($p ? $p->getErtek() : ''));
        $p = $repo->find(\mkw\consts::Webshop5Name);
        $view->setVar(\mkw\consts::Webshop5Name, ($p ? $p->getErtek() : ''));
        $p = $repo->find(\mkw\consts::Webshop6Name);
        $view->setVar(\mkw\consts::Webshop6Name, ($p ? $p->getErtek() : ''));
        $p = $repo->find(\mkw\consts::Webshop7Name);
        $view->setVar(\mkw\consts::Webshop7Name, ($p ? $p->getErtek() : ''));
        $p = $repo->find(\mkw\consts::Webshop8Name);
        $view->setVar(\mkw\consts::Webshop8Name, ($p ? $p->getErtek() : ''));
        $p = $repo->find(\mkw\consts::Webshop9Name);
        $view->setVar(\mkw\consts::Webshop9Name, ($p ? $p->getErtek() : ''));
        $p = $repo->find(\mkw\consts::Webshop10Name);
        $view->setVar(\mkw\consts::Webshop10Name, ($p ? $p->getErtek() : ''));
        $p = $repo->find(\mkw\consts::Webshop11Name);
        $view->setVar(\mkw\consts::Webshop11Name, ($p ? $p->getErtek() : ''));
        $p = $repo->find(\mkw\consts::Webshop12Name);
        $view->setVar(\mkw\consts::Webshop12Name, ($p ? $p->getErtek() : ''));
        $p = $repo->find(\mkw\consts::Webshop13Name);
        $view->setVar(\mkw\consts::Webshop13Name, ($p ? $p->getErtek() : ''));
        $p = $repo->find(\mkw\consts::Webshop14Name);
        $view->setVar(\mkw\consts::Webshop14Name, ($p ? $p->getErtek() : ''));
        $p = $repo->find(\mkw\consts::Webshop15Name);
        $view->setVar(\mkw\consts::Webshop15Name, ($p ? $p->getErtek() : ''));

        $p = $repo->find(\mkw\consts::Webshop2Price);
        $view->setVar('arsav2list', $arsav->getSelectList(($p ? $p->getErtek() : '')));
        $p = $repo->find(\mkw\consts::Webshop2Discount);
        $view->setVar('akciosarsav2list', $arsav->getSelectList(($p ? $p->getErtek() : '')));

        $p = $repo->find(\mkw\consts::Webshop3Price);
        $view->setVar('arsav3list', $arsav->getSelectList(($p ? $p->getErtek() : '')));
        $p = $repo->find(\mkw\consts::Webshop3Discount);
        $view->setVar('akciosarsav3list', $arsav->getSelectList(($p ? $p->getErtek() : '')));

        $p = $repo->find(\mkw\consts::Webshop4Price);
        $view->setVar('arsav4list', $arsav->getSelectList(($p ? $p->getErtek() : '')));
        $p = $repo->find(\mkw\consts::Webshop4Discount);
        $view->setVar('akciosarsav4list', $arsav->getSelectList(($p ? $p->getErtek() : '')));

        $p = $repo->find(\mkw\consts::Webshop5Price);
        $view->setVar('arsav5list', $arsav->getSelectList(($p ? $p->getErtek() : '')));
        $p = $repo->find(\mkw\consts::Webshop5Discount);
        $view->setVar('akciosarsav5list', $arsav->getSelectList(($p ? $p->getErtek() : '')));

        $p = $repo->find(\mkw\consts::MarkaCs);
        $markacs = new termekcimkekatController($this->params);
        $view->setVar('markacslist', $markacs->getSelectList(($p ? $p->getErtek() : 0)));
        $p = $repo->find(\mkw\consts::DENCs);
        $dencs = new termekcimkekatController($this->params);
        $view->setVar('dencslist', $dencs->getSelectList(($p ? $p->getErtek() : 0)));
        $p = $repo->find(\mkw\consts::EpitoelemszamCs);
        $dencs = new termekcimkekatController($this->params);
        $view->setVar('epitoelemszamcslist', $dencs->getSelectList(($p ? $p->getErtek() : 0)));
        $p = $repo->find(\mkw\consts::CsomagoltmeretCs);
        $dencs = new termekcimkekatController($this->params);
        $view->setVar('csomagoltmeretcslist', $dencs->getSelectList(($p ? $p->getErtek() : 0)));
        $p = $repo->find(\mkw\consts::AjanlottkorosztalyCs);
        $dencs = new termekcimkekatController($this->params);
        $view->setVar('ajanlottkorosztalycslist', $dencs->getSelectList(($p ? $p->getErtek() : 0)));

        $partnercimkec = new partnercimkeController($this->params);
        $p = $repo->find(\mkw\consts::KiskerCimke);
        $view->setVar('kiskercimkelist', $partnercimkec->getSelectList(($p ? $p->getErtek() : 0)));
        $p = $repo->find(\mkw\consts::NagykerCimke);
        $view->setVar('nagykercimkelist', $partnercimkec->getSelectList(($p ? $p->getErtek() : 0)));
        $p = $repo->find(\mkw\consts::SpanyolCimke);
        $view->setVar('spanyolcimkelist', $partnercimkec->getSelectList(($p ? $p->getErtek() : 0)));
        $p = $repo->find(\mkw\consts::FelvetelAlattCimke);
        $view->setVar('felvetelalattcimkelist', $partnercimkec->getSelectList(($p ? $p->getErtek() : 0)));
        $orszagc = new orszagController($this->params);
        $p = $repo->find(\mkw\consts::Spanyolorszag);
        $view->setVar('spanyolorszaglist', $orszagc->getSelectList(($p ? $p->getErtek() : 0), true));
        $p = $repo->find(\mkw\consts::Orszag);
        $view->setVar('orszaglist', $orszagc->getSelectList(($p ? $p->getErtek() : 0), true));
        $p = $repo->find(\mkw\consts::Magyarorszag);
        $view->setVar('magyarorszaglist', $orszagc->getSelectList(($p ? $p->getErtek() : 0), true));

        $p = $repo->find(\mkw\consts::FelvetelAlattTipus);
        $partnertipusc = new partnertipusController($this->params);
        $view->setVar('felvetelalattpartnertipuslist', $partnertipusc->getSelectList(($p ? $p->getErtek() : 0)));

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
        $p = $repo->find(\mkw\consts::BarionFizetesrevarStatusz);
        $view->setVar('barionfizetesrevarstatuszlist', $bsf->getSelectList(($p ? $p->getErtek() : 0)));
        $p = $repo->find(\mkw\consts::BarionFizetveStatusz);
        $view->setVar('barionfizetvestatuszlist', $bsf->getSelectList(($p ? $p->getErtek() : 0)));
        $p = $repo->find(\mkw\consts::BarionRefundedStatusz);
        $view->setVar('barionrefundedstatuszlist', $bsf->getSelectList(($p ? $p->getErtek() : 0)));

        $p = $repo->find(\mkw\consts::Esedekessegalap);
        $view->setVar(\mkw\consts::Esedekessegalap, ($p ? $p->getErtek() : '1'));

        $p = $repo->find(\mkw\consts::Locale);
        $view->setVar('localelist', \mkw\store::getLocaleSelectList(($p ? $p->getErtek() : '')));

        $p = $repo->find(\mkw\consts::ImportNewKatId);
        $inkid = $p ? $p->getErtek() : 0;
        $importnewkat = \mkw\store::getEm()->getRepository('Entities\TermekFa')->find($inkid);
        if ($importnewkat) {
            $view->setVar('importnewkat', [
                'caption' => $importnewkat->getNev(),
                'id' => $importnewkat->getId()
            ]);
        } else {
            $view->setVar('importnewkat', [
                'caption' => '',
                'id' => ''
            ]);
        }

        $p = $repo->find(\mkw\consts::NoMinKeszletTermekkat);
        $inkid = $p ? $p->getErtek() : 0;
        $importnewkat = \mkw\store::getEm()->getRepository('Entities\TermekFa')->find($inkid);
        if ($importnewkat) {
            $view->setVar(\mkw\consts::NoMinKeszletTermekkat, [
                'caption' => $importnewkat->getNev(),
                'id' => $importnewkat->getId()
            ]);
        } else {
            $view->setVar(\mkw\consts::NoMinKeszletTermekkat, [
                'caption' => '',
                'id' => ''
            ]);
        }
        $p = $repo->find(\mkw\consts::NoMinKeszlet);
        $view->setVar(\mkw\consts::NoMinKeszlet, ($p ? $p->getErtek() : 0));

        $p = $repo->find(\mkw\consts::MugenraceKatId);
        $inkid = $p ? $p->getErtek() : 0;
        $mugenracekat = \mkw\store::getEm()->getRepository('Entities\TermekFa')->find($inkid);
        if ($mugenracekat) {
            $view->setVar('mugenracekat', [
                'caption' => $mugenracekat->getNev(),
                'id' => $mugenracekat->getId()
            ]);
        } else {
            $view->setVar('mugenracekat', [
                'caption' => '',
                'id' => ''
            ]);
        }

        $p = $repo->find(\mkw\consts::Web4DefaKatId);
        $inkid = $p ? $p->getErtek() : 0;
        $web4defakat = \mkw\store::getEm()->getRepository('Entities\TermekFa')->find($inkid);
        if ($web4defakat) {
            $view->setVar('web4defakat', [
                'caption' => $web4defakat->getNev(),
                'id' => $web4defakat->getId()
            ]);
        } else {
            $view->setVar('web4defakat', [
                'caption' => '',
                'id' => ''
            ]);
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
        $p = $repo->find(\mkw\consts::Feedblogdb);
        $view->setVar(\mkw\consts::Feedblogdb, ($p ? $p->getErtek() : 20));
        $p = $repo->find(\mkw\consts::Feedblogtitle);
        $view->setVar(\mkw\consts::Feedblogtitle, ($p ? $p->getErtek() : t('Blog')));
        $p = $repo->find(\mkw\consts::Feedblogdescription);
        $view->setVar(\mkw\consts::Feedblogdescription, ($p ? $p->getErtek() : t('Blog')));

        // sitemap
        $p = $repo->find(\mkw\consts::Statlapprior);
        $view->setVar(\mkw\consts::Statlapprior, ($p ? $p->getErtek() : 0.4));
        $p = $repo->find(\mkw\consts::Blogposztprior);
        $view->setVar(\mkw\consts::Blogposztprior, ($p ? $p->getErtek() : 0.4));
        $p = $repo->find(\mkw\consts::Termekprior);
        $view->setVar(\mkw\consts::Termekprior, ($p ? $p->getErtek() : 0.5));
        $p = $repo->find(\mkw\consts::Kategoriaprior);
        $view->setVar(\mkw\consts::Kategoriaprior, ($p ? $p->getErtek() : 0.7));
        $p = $repo->find(\mkw\consts::Fooldalprior);
        $view->setVar(\mkw\consts::Fooldalprior, ($p ? $p->getErtek() : 1));
        $p = $repo->find(\mkw\consts::Statlapchangefreq);
        $view->setVar(\mkw\consts::Statlapchangefreq, ($p ? $p->getErtek() : 'monthly'));
        $p = $repo->find(\mkw\consts::Blogposztchangefreq);
        $view->setVar(\mkw\consts::Blogposztchangefreq, ($p ? $p->getErtek() : 'monthly'));
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
        $p = $repo->find(\mkw\consts::GyartoNika);
        $view->setVar('gyartonikalist', $gyarto->getSzallitoSelectList(($p ? $p->getErtek() : '')));
        $p = $repo->find(\mkw\consts::GyartoHaffner24);
        $view->setVar('gyartohaffner24list', $gyarto->getSzallitoSelectList(($p ? $p->getErtek() : '')));
        $p = $repo->find(\mkw\consts::GyartoEvona);
        $view->setVar('gyartoevonalist', $gyarto->getSzallitoSelectList(($p ? $p->getErtek() : '')));
        $p = $repo->find(\mkw\consts::GyartoEvonaXML);
        $view->setVar('gyartoevonaxmllist', $gyarto->getSzallitoSelectList(($p ? $p->getErtek() : '')));
        $p = $repo->find(\mkw\consts::GyartoNetpresso);
        $view->setVar('gyartonetpressolist', $gyarto->getSzallitoSelectList(($p ? $p->getErtek() : '')));
        $p = $repo->find(\mkw\consts::GyartoGulf);
        $view->setVar('gyartogulflist', $gyarto->getSzallitoSelectList(($p ? $p->getErtek() : '')));
        $p = $repo->find(\mkw\consts::GyartoQman);
        $view->setVar('gyartoqmanlist', $gyarto->getSzallitoSelectList(($p ? $p->getErtek() : '')));
        $p = $repo->find(\mkw\consts::GyartoSmileebike);
        $view->setVar('gyartosmileebikelist', $gyarto->getSzallitoSelectList(($p ? $p->getErtek() : '')));

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
        $p = $repo->find(\mkw\consts::PathNika);
        $view->setVar(\mkw\consts::PathNika, ($p ? $p->getErtek() : ''));
        $p = $repo->find(\mkw\consts::PathHaffner24);
        $view->setVar(\mkw\consts::PathHaffner24, ($p ? $p->getErtek() : ''));
        $p = $repo->find(\mkw\consts::PathEvona);
        $view->setVar(\mkw\consts::PathEvona, ($p ? $p->getErtek() : ''));
        $p = $repo->find(\mkw\consts::PathNetpresso);
        $view->setVar(\mkw\consts::PathNetpresso, ($p ? $p->getErtek() : ''));
        $p = $repo->find(\mkw\consts::PathSmileebike);
        $view->setVar(\mkw\consts::PathSmileebike, ($p ? $p->getErtek() : ''));

        $p = $repo->find(\mkw\consts::UrlKreativ);
        $view->setVar(\mkw\consts::UrlKreativ, ($p ? $p->getErtek() : ''));
        $p = $repo->find(\mkw\consts::UrlKreativImages);
        $view->setVar(\mkw\consts::UrlKreativImages, ($p ? $p->getErtek() : ''));
        $p = $repo->find(\mkw\consts::UrlDelton);
        $view->setVar(\mkw\consts::UrlDelton, ($p ? $p->getErtek() : ''));
        $p = $repo->find(\mkw\consts::UrlNomad);
        $view->setVar(\mkw\consts::UrlNomad, ($p ? $p->getErtek() : ''));
        $p = $repo->find(\mkw\consts::UrlNika);
        $view->setVar(\mkw\consts::UrlNika, ($p ? $p->getErtek() : ''));
        $p = $repo->find(\mkw\consts::UrlMaxutov);
        $view->setVar(\mkw\consts::UrlMaxutov, ($p ? $p->getErtek() : ''));
        $p = $repo->find(\mkw\consts::UrlLegavenue);
        $view->setVar(\mkw\consts::UrlLegavenue, ($p ? $p->getErtek() : ''));
        $p = $repo->find(\mkw\consts::UrlHaffner24);
        $view->setVar(\mkw\consts::UrlHaffner24, ($p ? $p->getErtek() : ''));
        $p = $repo->find(\mkw\consts::UrlReintex);
        $view->setVar(\mkw\consts::UrlReintex, ($p ? $p->getErtek() : ''));
        $p = $repo->find(\mkw\consts::UrlNetpresso);
        $view->setVar(\mkw\consts::UrlNetpresso, ($p ? $p->getErtek() : ''));
        $p = $repo->find(\mkw\consts::UrlEvonaXML);
        $view->setVar(\mkw\consts::UrlEvonaXML, ($p ? $p->getErtek() : ''));
        $p = $repo->find(\mkw\consts::UrlSmileebike);
        $view->setVar(\mkw\consts::UrlSmileebike, ($p ? $p->getErtek() : ''));

        $p = $repo->find(\mkw\consts::KepUrlEvona);
        $view->setVar(\mkw\consts::KepUrlEvona, ($p ? $p->getErtek() : ''));

        $p = $repo->find(\mkw\consts::ExcludeReintex);
        $view->setVar(\mkw\consts::ExcludeReintex, ($p ? $p->getErtek() : ''));

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
        $p = $repo->find(\mkw\consts::MugenraceFooterLogo);
        $view->setVar(\mkw\consts::MugenraceFooterLogo, ($p ? $p->getErtek() : ''));
        $p = $repo->find(\mkw\consts::MugenraceFooldalKep);
        $view->setVar(\mkw\consts::MugenraceFooldalKep, ($p ? $p->getErtek() : ''));
        $p = $repo->find(\mkw\consts::MugenraceFejlecKep);
        $view->setVar(\mkw\consts::MugenraceFejlecKep, ($p ? $p->getErtek() : ''));
        $p = $repo->find(\mkw\consts::MugenraceFooldalSzoveg);
        $view->setVar(\mkw\consts::MugenraceFooldalSzoveg, ($p ? $p->getErtek() : ''));

        $partner = new partnerController($this->params);
        $p = $repo->find(\mkw\consts::DefaultPartner);
        $view->setVar('defaultpartnerlist', $partner->getSelectList(($p ? $p->getErtek() : '')));

        $tc = $repo->find(\mkw\consts::DefaultTermek);
        $termek = new termekController($this->params);
        $view->setVar('defaulttermeklist', $termek->getSelectList(($tc ? $tc->getErtek() : 0)));

        $view->setVar('stopkreativimporturl', \mkw\store::getRouter()->generate('adminimportstop', false, ['impname' => 'kreativ']));
        $view->setVar('stopdeltonimporturl', \mkw\store::getRouter()->generate('adminimportstop', false, ['impname' => 'delton']));
        $view->setVar('stopreinteximporturl', \mkw\store::getRouter()->generate('adminimportstop', false, ['impname' => 'reintex']));
        $view->setVar('stoptutisportimporturl', \mkw\store::getRouter()->generate('adminimportstop', false, ['impname' => 'tutisport']));
        $view->setVar('stopmaxutovimporturl', \mkw\store::getRouter()->generate('adminimportstop', false, ['impname' => 'maxutov']));
        $view->setVar('stopsilkoimporturl', \mkw\store::getRouter()->generate('adminimportstop', false, ['impname' => 'silko']));
        $view->setVar('stopbtechimporturl', \mkw\store::getRouter()->generate('adminimportstop', false, ['impname' => 'btech']));
        $view->setVar('stopkressgepimporturl', \mkw\store::getRouter()->generate('adminimportstop', false, ['impname' => 'kressgep']));
        $view->setVar('stopkresstartozekimporturl', \mkw\store::getRouter()->generate('adminimportstop', false, ['impname' => 'kresstartozek']));
        $view->setVar('stoplegavenueimporturl', \mkw\store::getRouter()->generate('adminimportstop', false, ['impname' => 'legavenue']));
        $view->setVar('stopnomadimporturl', \mkw\store::getRouter()->generate('adminimportstop', false, ['impname' => 'nomad']));
        $view->setVar('stopnikaimporturl', \mkw\store::getRouter()->generate('adminimportstop', false, ['impname' => 'nika']));
        $view->setVar('stophaffner24importurl', \mkw\store::getRouter()->generate('adminimportstop', false, ['impname' => 'haffner24']));
        $view->setVar('stopevonaimporturl', \mkw\store::getRouter()->generate('adminimportstop', false, ['impname' => 'evona']));
        $view->setVar('stopevonaxmlimporturl', \mkw\store::getRouter()->generate('adminimportstop', false, ['impname' => 'evonaxml']));
        $view->setVar('stopnetpressoimporturl', \mkw\store::getRouter()->generate('adminimportstop', false, ['impname' => 'netpresso']));
        $view->setVar('stopgulfimporturl', \mkw\store::getRouter()->generate('adminimportstop', false, ['impname' => 'gulf']));
        $view->setVar('stopqmanimporturl', \mkw\store::getRouter()->generate('adminimportstop', false, ['impname' => 'qman']));
        $view->setVar('stopsmileebikeimporturl', \mkw\store::getRouter()->generate('adminimportstop', false, ['impname' => 'smileebike']));

        $view->setVar('repairkreativimporturl', \mkw\store::getRouter()->generate('adminimportrepair', false, ['impname' => 'kreativ']));
        $view->setVar('repairdeltonimporturl', \mkw\store::getRouter()->generate('adminimportrepair', false, ['impname' => 'delton']));
        $view->setVar('repairreinteximporturl', \mkw\store::getRouter()->generate('adminimportrepair', false, ['impname' => 'reintex']));
        $view->setVar('repairtutisportimporturl', \mkw\store::getRouter()->generate('adminimportrepair', false, ['impname' => 'tutisport']));
        $view->setVar('repairmaxutovimporturl', \mkw\store::getRouter()->generate('adminimportrepair', false, ['impname' => 'maxutov']));
        $view->setVar('repairsilkoimporturl', \mkw\store::getRouter()->generate('adminimportrepair', false, ['impname' => 'silko']));
        $view->setVar('repairbtechimporturl', \mkw\store::getRouter()->generate('adminimportrepair', false, ['impname' => 'btech']));
        $view->setVar('repairkressimporturl', \mkw\store::getRouter()->generate('adminimportrepair', false, ['impname' => 'kress']));
        $view->setVar('repairlegavenueimporturl', \mkw\store::getRouter()->generate('adminimportrepair', false, ['impname' => 'legavenue']));
        $view->setVar('repairnomadimporturl', \mkw\store::getRouter()->generate('adminimportrepair', false, ['impname' => 'nomad']));
        $view->setVar('repairnikaimporturl', \mkw\store::getRouter()->generate('adminimportrepair', false, ['impname' => 'nika']));
        $view->setVar('repairhaffner24importurl', \mkw\store::getRouter()->generate('adminimportrepair', false, ['impname' => 'haffner24']));
        $view->setVar('repairevonaimporturl', \mkw\store::getRouter()->generate('adminimportrepair', false, ['impname' => 'evona']));
        $view->setVar('repairevonaxmlimporturl', \mkw\store::getRouter()->generate('adminimportrepair', false, ['impname' => 'evonaxml']));
        $view->setVar('repairnetpressoimporturl', \mkw\store::getRouter()->generate('adminimportrepair', false, ['impname' => 'netpresso']));
        $view->setVar('repairgulfimporturl', \mkw\store::getRouter()->generate('adminimportrepair', false, ['impname' => 'gulf']));
        $view->setVar('repairqmanimporturl', \mkw\store::getRouter()->generate('adminimportrepair', false, ['impname' => 'qman']));
        $view->setVar('repairsmileebikeimporturl', \mkw\store::getRouter()->generate('adminimportrepair', false, ['impname' => 'smileebike']));

        $view->printTemplateResult();
    }

    private function setObj($par, $value, $specialchars = false)
    {
        $en = $this->getEntityName();
        /** @var \Entities\Parameterek $p */
        $p = \mkw\store::getEm()->getRepository($en)->find($par);
        if ($value === false) {
            $value = '0';
        }
        if ($value === true) {
            $value = '1';
        }
        if ($p) {
            $p->setErtek($value);
            $p->setSpecialchars($specialchars);
        } else {
            $p = new $en();
            $p->setId($par);
            $p->setErtek($value);
            $p->setSpecialchars($specialchars);
        }
        \mkw\store::getEm()->persist($p);
    }

    public function save()
    {
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
        $this->setObj(\mkw\consts::Tulajjovengszam, $this->params->getStringRequestParam(\mkw\consts::Tulajjovengszam));
        $this->setObj(\mkw\consts::TulajKontaktNev, $this->params->getStringRequestParam(\mkw\consts::TulajKontaktNev));
        $this->setObj(\mkw\consts::TulajKontaktEmail, $this->params->getStringRequestParam(\mkw\consts::TulajKontaktEmail));
        $this->setObj(\mkw\consts::TulajKontaktTelefon, $this->params->getStringRequestParam(\mkw\consts::TulajKontaktTelefon));
        $this->setObj(\mkw\consts::ProgramNev, $this->params->getStringRequestParam(\mkw\consts::ProgramNev));

        $tulajpartner = \mkw\store::getEm()->getRepository(Partner::class);
        $x = $this->params->getIntRequestParam('tulajpartner', 0);
        $partner = $tulajpartner->find($x);
        if ($partner) {
            $this->setObj(\mkw\consts::Tulajpartner, $partner->getId());
        } else {
            $this->setObj(\mkw\consts::Tulajpartner, '');
        }

        $x = $this->params->getIntRequestParam('defaultpartner', 0);
        $partner = $tulajpartner->find($x);
        if ($partner) {
            $this->setObj(\mkw\consts::DefaultPartner, $partner->getId());
        } else {
            $this->setObj(\mkw\consts::DefaultPartner, '');
        }

        if ($this->params->getStringRequestParam('tulajcrc')) {
            $this->setObj(\mkw\consts::Tulajcrc, md5($this->params->getStringRequestParam('tulajcrc') . \mkw\store::getAdminSalt()));
        }

        $this->setObj(\mkw\consts::EmailFrom, $this->params->getOriginalStringRequestParam('emailfrom'));
        $this->setObj(\mkw\consts::EmailReplyTo, $this->params->getOriginalStringRequestParam('emailreplyto'));
        $this->setObj(\mkw\consts::EmailBcc, $this->params->getStringRequestParam('emailbcc'));
        $this->setObj(\mkw\consts::EmailStatuszValtas, $this->params->getStringRequestParam('emailstatuszvaltas'));
        $this->setObj(\mkw\consts::KonyveloEmail, $this->params->getOriginalStringRequestParam('konyveloemail'));

        // web
        $this->setObj(\mkw\consts::Logo, $this->params->getStringRequestParam('logo'));
        $this->setObj(\mkw\consts::UjtermekJelolo, $this->params->getStringRequestParam('ujtermekjelolo'));
        $this->setObj(\mkw\consts::Top10Jelolo, $this->params->getStringRequestParam('top10jelolo'));
        $this->setObj(\mkw\consts::AkcioJelolo, $this->params->getStringRequestParam('akciojelolo'));
        $this->setObj(\mkw\consts::IngyenszallitasJelolo, $this->params->getStringRequestParam('ingyenszallitasjelolo'));
        $this->setObj(\mkw\consts::Watermark, $this->params->getStringRequestParam('watermark'));

        $this->setObj(\mkw\consts::ASZFUrl, $this->params->getStringRequestParam(\mkw\consts::ASZFUrl));

        $this->setObj(\mkw\consts::SzallitasiKtg1Tol, $this->params->getStringRequestParam('szallitasiktg1tol'));
        $this->setObj(\mkw\consts::SzallitasiKtg1Ig, $this->params->getStringRequestParam('szallitasiktg1ig'));
        $this->setObj(\mkw\consts::SzallitasiKtg1Ertek, $this->params->getStringRequestParam('szallitasiktg1ertek'));

        $this->setObj(\mkw\consts::SzallitasiKtg2Tol, $this->params->getStringRequestParam('szallitasiktg2tol'));
        $this->setObj(\mkw\consts::SzallitasiKtg2Ig, $this->params->getStringRequestParam('szallitasiktg2ig'));
        $this->setObj(\mkw\consts::SzallitasiKtg2Ertek, $this->params->getStringRequestParam('szallitasiktg2ertek'));

        $this->setObj(\mkw\consts::SzallitasiKtg3Tol, $this->params->getStringRequestParam('szallitasiktg3tol'));
        $this->setObj(\mkw\consts::SzallitasiKtg3Ig, $this->params->getStringRequestParam('szallitasiktg3ig'));
        $this->setObj(\mkw\consts::SzallitasiKtg3Ertek, $this->params->getStringRequestParam('szallitasiktg3ertek'));

        $szkt = \mkw\store::getEm()->getRepository(Termek::class)->find($this->params->getIntRequestParam('szallitasiktgtermek', 0));
        if ($szkt) {
            $this->setObj(\mkw\consts::SzallitasiKtgTermek, $szkt->getId());
        } else {
            $this->setObj(\mkw\consts::SzallitasiKtgTermek, '');
        }

        $szkt = \mkw\store::getEm()->getRepository(Termek::class)->find($this->params->getIntRequestParam('defaulttermek', 0));
        if ($szkt) {
            $this->setObj(\mkw\consts::DefaultTermek, $szkt->getId());
        } else {
            $this->setObj(\mkw\consts::DefaultTermek, '');
        }

        $szm = \mkw\store::getEm()->getRepository(Szallitasimod::class)->find($this->params->getIntRequestParam('foxpostszallmod', 0));
        if ($szm) {
            $this->setObj(\mkw\consts::FoxpostSzallitasiMod, $szm->getId());
        } else {
            $this->setObj(\mkw\consts::FoxpostSzallitasiMod, '');
        }

        $szm = \mkw\store::getEm()->getRepository(Szallitasimod::class)->find($this->params->getIntRequestParam('tofszallmod', 0));
        if ($szm) {
            $this->setObj(\mkw\consts::TOFSzallitasiMod, $szm->getId());
        } else {
            $this->setObj(\mkw\consts::TOFSzallitasiMod, '');
        }

        $szm = \mkw\store::getEm()->getRepository(Szallitasimod::class)->find($this->params->getIntRequestParam('glsszallmod', 0));
        if ($szm) {
            $this->setObj(\mkw\consts::GLSSzallitasiMod, $szm->getId());
        } else {
            $this->setObj(\mkw\consts::GLSSzallitasiMod, '');
        }

        $szm = \mkw\store::getEm()->getRepository(Szallitasimod::class)->find($this->params->getIntRequestParam('glsfutarszallmod', 0));
        if ($szm) {
            $this->setObj(\mkw\consts::GLSFutarSzallitasmod, $szm->getId());
        } else {
            $this->setObj(\mkw\consts::GLSFutarSzallitasmod, '');
        }

        $szm = \mkw\store::getEm()->getRepository(Szallitasimod::class)->find($this->params->getIntRequestParam('arukeresoexportszallmod', 0));
        if ($szm) {
            $this->setObj(\mkw\consts::ArukeresoExportSzallmod, $szm->getId());
        } else {
            $this->setObj(\mkw\consts::ArukeresoExportSzallmod, '');
        }

        $belsouk = \mkw\store::getEm()->getRepository(Uzletkoto::class)->find($this->params->getIntRequestParam('belsouk', 0));
        if ($belsouk) {
            $this->setObj(\mkw\consts::BelsoUzletkoto, $belsouk->getId());
        } else {
            $this->setObj(\mkw\consts::BelsoUzletkoto, '');
        }

        $szallfeltsablon = \mkw\store::getEm()->getRepository(Statlap::class)->find($this->params->getIntRequestParam('szallitasifeltetelsablon', 0));
        if ($szallfeltsablon) {
            $this->setObj(\mkw\consts::SzallitasiFeltetelSablon, $szallfeltsablon->getId());
        } else {
            $this->setObj(\mkw\consts::SzallitasiFeltetelSablon, '');
        }

        $sza = \mkw\store::getEm()->getRepository(MPTNGYSzakmaianyagtipus::class)->find(
            $this->params->getIntRequestParam(\mkw\consts::MPTNGYSzimpoziumTipus, 0)
        );
        if ($sza) {
            $this->setObj(\mkw\consts::MPTNGYSzimpoziumTipus, $sza->getId());
        } else {
            $this->setObj(\mkw\consts::MPTNGYSzimpoziumTipus, '');
        }
        $sza = \mkw\store::getEm()->getRepository(MPTNGYSzakmaianyagtipus::class)->find(
            $this->params->getIntRequestParam(\mkw\consts::MPTNGYSzimpoziumEloadasTipus, 0)
        );
        if ($sza) {
            $this->setObj(\mkw\consts::MPTNGYSzimpoziumEloadasTipus, $sza->getId());
        } else {
            $this->setObj(\mkw\consts::MPTNGYSzimpoziumEloadasTipus, '');
        }
        $sza = \mkw\store::getEm()->getRepository(MPTNGYSzakmaianyagtipus::class)->find(
            $this->params->getIntRequestParam(\mkw\consts::MPTNGYKonyvbemutatoTipus, 0)
        );
        if ($sza) {
            $this->setObj(\mkw\consts::MPTNGYKonyvbemutatoTipus, $sza->getId());
        } else {
            $this->setObj(\mkw\consts::MPTNGYKonyvbemutatoTipus, '');
        }

        $this->setObj(\mkw\consts::MPTNGYDatum1, $this->params->getStringRequestParam(\mkw\consts::MPTNGYDatum1));
        $this->setObj(\mkw\consts::MPTNGYDatum2, $this->params->getStringRequestParam(\mkw\consts::MPTNGYDatum2));
        $this->setObj(\mkw\consts::MPTNGYDatum3, $this->params->getStringRequestParam(\mkw\consts::MPTNGYDatum3));

        $this->setObj(\mkw\consts::MPTNGYSzempont1, $this->params->getStringRequestParam(\mkw\consts::MPTNGYSzempont1));
        $this->setObj(\mkw\consts::MPTNGYSzempont2, $this->params->getStringRequestParam(\mkw\consts::MPTNGYSzempont2));
        $this->setObj(\mkw\consts::MPTNGYSzempont3, $this->params->getStringRequestParam(\mkw\consts::MPTNGYSzempont3));
        $this->setObj(\mkw\consts::MPTNGYSzempont4, $this->params->getStringRequestParam(\mkw\consts::MPTNGYSzempont4));
        $this->setObj(\mkw\consts::MPTNGYSzempont5, $this->params->getStringRequestParam(\mkw\consts::MPTNGYSzempont5));

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
        $this->setObj(\mkw\consts::Blogoldalcim, $this->params->getStringRequestParam('blogoldalcim'));
        $this->setObj(\mkw\consts::Blogseodescription, $this->params->getStringRequestParam('blogseodescription'));
        $this->setObj(\mkw\consts::Blogposztdb, $this->params->getIntRequestParam('blogposztdb', 15));
        $this->setObj(\mkw\consts::BlogposztTermeklapdb, $this->params->getIntRequestParam('blogposzttermeklapdb', 3));
        $this->setObj(\mkw\consts::BlogposztKategoriadb, $this->params->getIntRequestParam('blogposztkategoriadb', 3));
        $this->setObj(\mkw\consts::GAFollow, $this->params->getStringRequestParam('gafollow'));
        $this->setObj(\mkw\consts::GMapsApiKey, $this->params->getStringRequestParam('gmapsapikey'));
        $this->setObj(\mkw\consts::FBAppId, $this->params->getStringRequestParam('fbappid'));

        $this->setObj(\mkw\consts::FoxpostApiURL, $this->params->getStringRequestParam('foxpostapiurl'), true);
        $this->setObj(\mkw\consts::FoxpostUsername, $this->params->getStringRequestParam('foxpostusername'));
        $this->setObj(\mkw\consts::FoxpostPassword, $this->params->getStringRequestParam('foxpostpassword'));
        $this->setObj(\mkw\consts::FoxpostApiVersion, $this->params->getStringRequestParam(\mkw\consts::FoxpostApiVersion));
        $this->setObj(\mkw\consts::Foxpostv2ApiURL, $this->params->getStringRequestParam('foxpostv2apiurl'), true);
        $this->setObj(\mkw\consts::Foxpostv2Username, $this->params->getStringRequestParam('foxpostv2username'));
        $this->setObj(\mkw\consts::Foxpostv2Password, $this->params->getStringRequestParam('foxpostv2password'));
        $this->setObj(\mkw\consts::Foxpostv2ApiKey, $this->params->getStringRequestParam('foxpostv2apikey'));

        $this->setObj(\mkw\consts::GLSApiURL, $this->params->getStringRequestParam('glsapiurl'), true);
        $this->setObj(\mkw\consts::GLSUsername, $this->params->getStringRequestParam('glsusername'));
        $this->setObj(\mkw\consts::GLSClientNumber, $this->params->getStringRequestParam('glsclientnumber'));
        $this->setObj(\mkw\consts::GLSPassword, $this->params->getStringRequestParam('glspassword'));
        $this->setObj(\mkw\consts::GLSParcelLabelDir, $this->params->getStringRequestParam('glsparcellabeldir'));
        \mkw\store::createDirectoryRecursively($this->params->getStringRequestParam('glsparcellabeldir'));
        $this->setObj(\mkw\consts::EmagAPIUrl, $this->params->getStringRequestParam('emagapiurl'), true);
        $this->setObj(\mkw\consts::EmagUsername, $this->params->getStringRequestParam('emagusername'));
        $this->setObj(\mkw\consts::EmagUsercode, $this->params->getStringRequestParam('emagusercode'));
        $this->setObj(\mkw\consts::EmagPassword, $this->params->getStringRequestParam('emagpassword'));
        $this->setObj(\mkw\consts::GLSTerminalURL, $this->params->getStringRequestParam('glsterminalurl'), true);
        $this->setObj(\mkw\consts::MiniCRMHasznalatban, $this->params->getBoolRequestParam('minicrmhasznalatban'));
        $this->setObj(\mkw\consts::MiniCRMSystemId, $this->params->getStringRequestParam('minicrmsystemid'));
        $this->setObj(\mkw\consts::MiniCRMAPIKey, $this->params->getStringRequestParam('minicrmapikey'));
        $this->setObj(\mkw\consts::MiniCRMPartnertorzs, $this->params->getIntRequestParam('minicrmpartnertorzs'));
        $this->setObj(\mkw\consts::MiniCRMRendezvenyJelentkezes, $this->params->getIntRequestParam('minicrmrendezvenyjelentkezes'));
        $this->setObj(\mkw\consts::KuponElotag, $this->params->getStringRequestParam('kuponelotag'));
        $this->setObj(\mkw\consts::Off, $this->params->getBoolRequestParam(\mkw\consts::Off));
        $this->setObj(\mkw\consts::Off2, $this->params->getBoolRequestParam(\mkw\consts::Off2));
        $this->setObj(\mkw\consts::Off3, $this->params->getBoolRequestParam(\mkw\consts::Off3));
        $this->setObj(\mkw\consts::Off4, $this->params->getBoolRequestParam(\mkw\consts::Off4));
        $this->setObj(\mkw\consts::Off5, $this->params->getBoolRequestParam(\mkw\consts::Off5));
        $this->setObj(\mkw\consts::JogaAYCMJutalek, $this->params->getNumRequestParam(\mkw\consts::JogaAYCMJutalek));
        $this->setObj(\mkw\consts::JogaJutalek, $this->params->getNumRequestParam(\mkw\consts::JogaJutalek));
        $this->setObj(\mkw\consts::JogaUresTeremJutalek, $this->params->getNumRequestParam(\mkw\consts::JogaUresTeremJutalek));
        $tanarelszsablon = \mkw\store::getEm()->getRepository(Emailtemplate::class)->find($this->params->getIntRequestParam('tanarelszamolassablon', 0));
        if ($tanarelszsablon) {
            $this->setObj(\mkw\consts::JogaTanarelszamolasSablon, $tanarelszsablon->getId());
        } else {
            $this->setObj(\mkw\consts::JogaTanarelszamolasSablon, '');
        }
        $tanarelszsablon = \mkw\store::getEm()->getRepository(Emailtemplate::class)->find($this->params->getIntRequestParam('nemjonsenkisablon', 0));
        if ($tanarelszsablon) {
            $this->setObj(\mkw\consts::JogaNemjonsenkiSablon, $tanarelszsablon->getId());
        } else {
            $this->setObj(\mkw\consts::JogaNemjonsenkiSablon, '');
        }
        $tanarelszsablon = \mkw\store::getEm()->getRepository(Emailtemplate::class)->find(
            $this->params->getIntRequestParam('nemjelentkeztekelegentanarnaksablon', 0)
        );
        if ($tanarelszsablon) {
            $this->setObj(\mkw\consts::JogaNemjelenteztekelegenTanarnakSablon, $tanarelszsablon->getId());
        } else {
            $this->setObj(\mkw\consts::JogaNemjelenteztekelegenTanarnakSablon, '');
        }
        $tanarelszsablon = \mkw\store::getEm()->getRepository(Emailtemplate::class)->find(
            $this->params->getIntRequestParam('nemjelentkeztekelegengyakorlonaksablon', 0)
        );
        if ($tanarelszsablon) {
            $this->setObj(\mkw\consts::JogaNemjelentkeztekelegenGyakorlonakSablon, $tanarelszsablon->getId());
        } else {
            $this->setObj(\mkw\consts::JogaNemjelentkeztekelegenGyakorlonakSablon, '');
        }
        $tanarelszsablon = \mkw\store::getEm()->getRepository(Emailtemplate::class)->find(
            $this->params->getIntRequestParam('elegenjelentkeztektanarnaksablon', 0)
        );
        if ($tanarelszsablon) {
            $this->setObj(\mkw\consts::JogaElegenjelentkeztekTanarnakSablon, $tanarelszsablon->getId());
        } else {
            $this->setObj(\mkw\consts::JogaElegenjelentkeztekTanarnakSablon, '');
        }
        $tanarelszsablon = \mkw\store::getEm()->getRepository(Emailtemplate::class)->find(
            $this->params->getIntRequestParam('jogabejelentkezeskoszonosablon', 0)
        );
        if ($tanarelszsablon) {
            $this->setObj(\mkw\consts::JogaBejelentkezesKoszonoSablon, $tanarelszsablon->getId());
        } else {
            $this->setObj(\mkw\consts::JogaBejelentkezesKoszonoSablon, '');
        }
        $tanarelszsablon = \mkw\store::getEm()->getRepository(Emailtemplate::class)->find(
            $this->params->getIntRequestParam('jogaelmaradaskonyveloneksablon', 0)
        );
        if ($tanarelszsablon) {
            $this->setObj(\mkw\consts::JogaElmaradasKonyvelonekSablon, $tanarelszsablon->getId());
        } else {
            $this->setObj(\mkw\consts::JogaElmaradasKonyvelonekSablon, '');
        }
        $szamlalevelsablon = \mkw\store::getEm()->getRepository(Emailtemplate::class)->find($this->params->getIntRequestParam('szamlalevelsablon', 0));
        if ($szamlalevelsablon) {
            $this->setObj(\mkw\consts::SzamlalevelSablon, $szamlalevelsablon->getId());
        } else {
            $this->setObj(\mkw\consts::SzamlalevelSablon, '');
        }

        $eesablon = \mkw\store::getEm()->getRepository(Emailtemplate::class)->find($this->params->getIntRequestParam('ertekelesertesitosablon', 0));
        if ($eesablon) {
            $this->setObj(\mkw\consts::ErtekelesErtesitoSablon, $eesablon->getId());
        } else {
            $this->setObj(\mkw\consts::ErtekelesErtesitoSablon, '');
        }

        $eesablon = \mkw\store::getEm()->getRepository(Emailtemplate::class)->find($this->params->getIntRequestParam('ertekeleskerosablon', 0));
        if ($eesablon) {
            $this->setObj(\mkw\consts::ErtekelesKeroSablon, $eesablon->getId());
        } else {
            $this->setObj(\mkw\consts::ErtekelesKeroSablon, '');
        }

        $konyvelolevelsablon = \mkw\store::getEm()->getRepository(Emailtemplate::class)->find($this->params->getIntRequestParam('konyvelolevelsablon', 0));
        if ($konyvelolevelsablon) {
            $this->setObj(\mkw\consts::KonyvelolevelSablon, $konyvelolevelsablon->getId());
        } else {
            $this->setObj(\mkw\consts::KonyvelolevelSablon, '');
        }

        $levelsablon = \mkw\store::getEm()->getRepository(Emailtemplate::class)->find(
            $this->params->getIntRequestParam('rendezvenysablonfelszabadulthelyertesito', 0)
        );
        if ($levelsablon) {
            $this->setObj(\mkw\consts::RendezvenySablonFelszabadultHelyErtesito, $levelsablon->getId());
        } else {
            $this->setObj(\mkw\consts::RendezvenySablonFelszabadultHelyErtesito, '');
        }

        $levelsablon = \mkw\store::getEm()->getRepository(Emailtemplate::class)->find($this->params->getIntRequestParam('rendezvenysablonregkoszono', 0));
        if ($levelsablon) {
            $this->setObj(\mkw\consts::RendezvenySablonRegKoszono, $levelsablon->getId());
        } else {
            $this->setObj(\mkw\consts::RendezvenySablonRegKoszono, '');
        }

        $levelsablon = \mkw\store::getEm()->getRepository(Emailtemplate::class)->find($this->params->getIntRequestParam('rendezvenysablonregertesito', 0));
        if ($levelsablon) {
            $this->setObj(\mkw\consts::RendezvenySablonRegErtesito, $levelsablon->getId());
        } else {
            $this->setObj(\mkw\consts::RendezvenySablonRegErtesito, '');
        }

        $this->setObj(\mkw\consts::RendezvenyRegErtesitoEmail, $this->params->getStringRequestParam(\mkw\consts::RendezvenyRegErtesitoEmail));

        $levelsablon = \mkw\store::getEm()->getRepository(Emailtemplate::class)->find($this->params->getIntRequestParam('rendezvenysablondijbekero', 0));
        if ($levelsablon) {
            $this->setObj(\mkw\consts::RendezvenySablonDijbekero, $levelsablon->getId());
        } else {
            $this->setObj(\mkw\consts::RendezvenySablonDijbekero, '');
        }

        $levelsablon = \mkw\store::getEm()->getRepository(Emailtemplate::class)->find(
            $this->params->getIntRequestParam('rendezvenysablonkezdesemlekezteto', 0)
        );
        if ($levelsablon) {
            $this->setObj(\mkw\consts::RendezvenySablonKezdesEmlekezteto, $levelsablon->getId());
        } else {
            $this->setObj(\mkw\consts::RendezvenySablonKezdesEmlekezteto, '');
        }

        $levelsablon = \mkw\store::getEm()->getRepository(Emailtemplate::class)->find($this->params->getIntRequestParam('rendezvenysablonfizeteskoszono', 0));
        if ($levelsablon) {
            $this->setObj(\mkw\consts::RendezvenySablonFizetesKoszono, $levelsablon->getId());
        } else {
            $this->setObj(\mkw\consts::RendezvenySablonFizetesKoszono, '');
        }

        $levelsablon = \mkw\store::getEm()->getRepository(Emailtemplate::class)->find(
            $this->params->getIntRequestParam(\mkw\consts::JogaBerletFelszolitoSablon, 0)
        );
        if ($levelsablon) {
            $this->setObj(\mkw\consts::JogaBerletFelszolitoSablon, $levelsablon->getId());
        } else {
            $this->setObj(\mkw\consts::JogaBerletFelszolitoSablon, '');
        }

        $levelsablon = \mkw\store::getEm()->getRepository(Emailtemplate::class)->find(
            $this->params->getIntRequestParam(\mkw\consts::JogaBerletKoszonoSablon, 0)
        );
        if ($levelsablon) {
            $this->setObj(\mkw\consts::JogaBerletKoszonoSablon, $levelsablon->getId());
        } else {
            $this->setObj(\mkw\consts::JogaBerletKoszonoSablon, '');
        }

        $levelsablon = \mkw\store::getEm()->getRepository(Emailtemplate::class)->find(
            $this->params->getIntRequestParam(\mkw\consts::JogaBerletLefogjarniSablon, 0)
        );
        if ($levelsablon) {
            $this->setObj(\mkw\consts::JogaBerletLefogjarniSablon, $levelsablon->getId());
        } else {
            $this->setObj(\mkw\consts::JogaBerletLefogjarniSablon, '');
        }

        $levelsablon = \mkw\store::getEm()->getRepository(Emailtemplate::class)->find(
            $this->params->getIntRequestParam(\mkw\consts::JogaBerletLejartSablon, 0)
        );
        if ($levelsablon) {
            $this->setObj(\mkw\consts::JogaBerletLejartSablon, $levelsablon->getId());
        } else {
            $this->setObj(\mkw\consts::JogaBerletLejartSablon, '');
        }

        $levelsablon = \mkw\store::getEm()->getRepository(Emailtemplate::class)->find(
            $this->params->getIntRequestParam(\mkw\consts::JogaBerletDatumLejartSablon, 0)
        );
        if ($levelsablon) {
            $this->setObj(\mkw\consts::JogaBerletDatumLejartSablon, $levelsablon->getId());
        } else {
            $this->setObj(\mkw\consts::JogaBerletDatumLejartSablon, '');
        }

        $levelsablon = \mkw\store::getEm()->getRepository(Emailtemplate::class)->find(
            $this->params->getIntRequestParam(\mkw\consts::JogaLemondasKoszonoSablon, 0)
        );
        if ($levelsablon) {
            $this->setObj(\mkw\consts::JogaLemondasKoszonoSablon, $levelsablon->getId());
        } else {
            $this->setObj(\mkw\consts::JogaLemondasKoszonoSablon, '');
        }

        $levelsablon = \mkw\store::getEm()->getRepository(Emailtemplate::class)->find(
            $this->params->getIntRequestParam(\mkw\consts::JogaElmaradasErtesitoSablon, 0)
        );
        if ($levelsablon) {
            $this->setObj(\mkw\consts::JogaElmaradasErtesitoSablon, $levelsablon->getId());
        } else {
            $this->setObj(\mkw\consts::JogaElmaradasErtesitoSablon, '');
        }

        $levelsablon = \mkw\store::getEm()->getRepository(Emailtemplate::class)->find(
            $this->params->getIntRequestParam(\mkw\consts::JogaBejelentkezesErtesitoSablon, 0)
        );
        if ($levelsablon) {
            $this->setObj(\mkw\consts::JogaBejelentkezesErtesitoSablon, $levelsablon->getId());
        } else {
            $this->setObj(\mkw\consts::JogaBejelentkezesErtesitoSablon, '');
        }

        $levelsablon = \mkw\store::getEm()->getRepository(Emailtemplate::class)->find(
            $this->params->getIntRequestParam(\mkw\consts::JogaLemondasErtesitoSablon, 0)
        );
        if ($levelsablon) {
            $this->setObj(\mkw\consts::JogaLemondasErtesitoSablon, $levelsablon->getId());
        } else {
            $this->setObj(\mkw\consts::JogaLemondasErtesitoSablon, '');
        }

        $this->setObj(\mkw\consts::BarionEnvironment, $this->params->getNumRequestParam(\mkw\consts::BarionEnvironment));
        $this->setObj(\mkw\consts::BarionPayeeEmail, $this->params->getStringRequestParam(\mkw\consts::BarionPayeeEmail));
        $this->setObj(\mkw\consts::BarionPOSKey, $this->params->getStringRequestParam(\mkw\consts::BarionPOSKey));
        $this->setObj(\mkw\consts::BarionAPIVersion, $this->params->getStringRequestParam(\mkw\consts::BarionAPIVersion));

        $this->setObj(\mkw\consts::SzamlaOrzesAlap, $this->params->getIntRequestParam(\mkw\consts::SzamlaOrzesAlap));
        $this->setObj(\mkw\consts::SzamlaOrzesEv, $this->params->getIntRequestParam(\mkw\consts::SzamlaOrzesEv));

        $vut = \mkw\store::getEm()->getRepository(Termek::class)->find($this->params->getIntRequestParam('vasarlasiutalvanytermek', 0));
        if ($vut) {
            $this->setObj(\mkw\consts::VasarlasiUtalvanyTermek, $vut->getId());
        }
        $vut = \mkw\store::getEm()->getRepository(Termek::class)->find($this->params->getIntRequestParam('jogaorajegytermek', 0));
        if ($vut) {
            $this->setObj(\mkw\consts::JogaOrajegyTermek, $vut->getId());
        } else {
            $this->setObj(\mkw\consts::JogaOrajegyTermek, null);
        }
        $vut = \mkw\store::getEm()->getRepository(Termek::class)->find($this->params->getIntRequestParam('jogaberlet4termek', 0));
        if ($vut) {
            $this->setObj(\mkw\consts::JogaBerlet4Termek, $vut->getId());
        } else {
            $this->setObj(\mkw\consts::JogaBerlet4Termek, null);
        }
        $vut = \mkw\store::getEm()->getRepository(Termek::class)->find($this->params->getIntRequestParam('jogaberlet10termek', 0));
        if ($vut) {
            $this->setObj(\mkw\consts::JogaBerlet10Termek, $vut->getId());
        } else {
            $this->setObj(\mkw\consts::JogaBerlet10Termek, null);
        }

        $vut = \mkw\store::getEm()->getRepository(Jogaoratipus::class)->find($this->params->getIntRequestParam('jogaallapotfelmerestipus', 0));
        if ($vut) {
            $this->setObj(\mkw\consts::JogaAllapotfelmeresTipus, $vut->getId());
        } else {
            $this->setObj(\mkw\consts::JogaAllapotfelmeresTipus, null);
        }

        // alapertelmezes
        $fizmod = \mkw\store::getEm()->getRepository('Entities\Fizmod')->find($this->params->getIntRequestParam('fizmod', 0));
        if ($fizmod) {
            $this->setObj(\mkw\consts::Fizmod, $fizmod->getId());
        }
        $fizmod = \mkw\store::getEm()->getRepository('Entities\Fizmod')->find($this->params->getIntRequestParam('utanvetfizmod', 0));
        if ($fizmod) {
            $this->setObj(\mkw\consts::UtanvetFizmod, $fizmod->getId());
        } else {
            $this->setObj(\mkw\consts::UtanvetFizmod, '');
        }
        $fizmod = \mkw\store::getEm()->getRepository('Entities\Fizmod')->find($this->params->getIntRequestParam('szepkartyafizmod', 0));
        if ($fizmod) {
            $this->setObj(\mkw\consts::SZEPFizmod, $fizmod->getId());
        } else {
            $this->setObj(\mkw\consts::SZEPFizmod, '');
        }
        $fizmod = \mkw\store::getEm()->getRepository('Entities\Fizmod')->find($this->params->getIntRequestParam('sportkartyafizmod', 0));
        if ($fizmod) {
            $this->setObj(\mkw\consts::SportkartyaFizmod, $fizmod->getId());
        } else {
            $this->setObj(\mkw\consts::SportkartyaFizmod, '');
        }
        $fizmod = \mkw\store::getEm()->getRepository('Entities\Fizmod')->find($this->params->getIntRequestParam('aycmfizmod', 0));
        if ($fizmod) {
            $this->setObj(\mkw\consts::AYCMFizmod, $fizmod->getId());
        } else {
            $this->setObj(\mkw\consts::AYCMFizmod, '');
        }
        $fizmod = \mkw\store::getEm()->getRepository('Entities\Fizmod')->find($this->params->getIntRequestParam('barionfizmod', 0));
        if ($fizmod) {
            $this->setObj(\mkw\consts::BarionFizmod, $fizmod->getId());
        } else {
            $this->setObj(\mkw\consts::BarionFizmod, '');
        }

        $j = \mkw\store::getEm()->getRepository('Entities\Jelenlettipus')->find($this->params->getIntRequestParam('munkajelenlet', 0));
        if ($j) {
            $this->setObj(\mkw\consts::MunkaJelenlet, $j->getId());
        } else {
            $this->setObj(\mkw\consts::MunkaJelenlet, '');
        }

        $szallmod = \mkw\store::getEm()->getRepository('Entities\Szallitasimod')->find($this->params->getIntRequestParam('szallitasimod', 0));
        if ($szallmod) {
            $this->setObj(\mkw\consts::Szallitasimod, $szallmod->getId());
        }
        if (\mkw\store::isOTPay()) {
            $fizmod = \mkw\store::getEm()->getRepository('Entities\Fizmod')->find($this->params->getIntRequestParam('otpayfizmod', 0));
            if ($fizmod) {
                $this->setObj(\mkw\consts::OTPayFizmod, $fizmod->getId());
            } else {
                $this->setObj(\mkw\consts::OTPayFizmod, '');
            }
        }
        if (\mkw\store::isMasterPass()) {
            $fizmod = \mkw\store::getEm()->getRepository('Entities\Fizmod')->find($this->params->getIntRequestParam('masterpassfizmod', 0));
            if ($fizmod) {
                $this->setObj(\mkw\consts::MasterPassFizmod, $fizmod->getId());
            } else {
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
        $this->setObj(\mkw\consts::Webshop1Name, $this->params->getStringRequestParam('webshop1name'));
        $this->setObj(\mkw\consts::Webshop2Name, $this->params->getStringRequestParam('webshop2name'));
        $this->setObj(\mkw\consts::Webshop3Name, $this->params->getStringRequestParam('webshop3name'));
        $this->setObj(\mkw\consts::Webshop4Name, $this->params->getStringRequestParam('webshop4name'));
        $this->setObj(\mkw\consts::Webshop5Name, $this->params->getStringRequestParam('webshop5name'));
        $this->setObj(\mkw\consts::Webshop6Name, $this->params->getStringRequestParam('webshop6name'));
        $this->setObj(\mkw\consts::Webshop7Name, $this->params->getStringRequestParam('webshop7name'));
        $this->setObj(\mkw\consts::Webshop8Name, $this->params->getStringRequestParam('webshop8name'));
        $this->setObj(\mkw\consts::Webshop9Name, $this->params->getStringRequestParam('webshop9name'));
        $this->setObj(\mkw\consts::Webshop10Name, $this->params->getStringRequestParam('webshop10name'));
        $this->setObj(\mkw\consts::Webshop11Name, $this->params->getStringRequestParam('webshop11name'));
        $this->setObj(\mkw\consts::Webshop12Name, $this->params->getStringRequestParam('webshop12name'));
        $this->setObj(\mkw\consts::Webshop13Name, $this->params->getStringRequestParam('webshop13name'));
        $this->setObj(\mkw\consts::Webshop14Name, $this->params->getStringRequestParam('webshop14name'));
        $this->setObj(\mkw\consts::Webshop15Name, $this->params->getStringRequestParam('webshop15name'));
        $this->setObj(\mkw\consts::Webshop2Price, $this->params->getStringRequestParam('arsav2'));
        $this->setObj(\mkw\consts::Webshop2Discount, $this->params->getStringRequestParam('akciosarsav2'));
        $this->setObj(\mkw\consts::Webshop3Price, $this->params->getStringRequestParam('arsav3'));
        $this->setObj(\mkw\consts::Webshop3Discount, $this->params->getStringRequestParam('akciosarsav3'));
        $this->setObj(\mkw\consts::Webshop4Price, $this->params->getStringRequestParam('arsav4'));
        $this->setObj(\mkw\consts::Webshop4Discount, $this->params->getStringRequestParam('akciosarsav4'));
        $this->setObj(\mkw\consts::Webshop5Price, $this->params->getStringRequestParam('arsav5'));
        $this->setObj(\mkw\consts::Webshop5Discount, $this->params->getStringRequestParam('akciosarsav5'));

        $markacs = \mkw\store::getEm()->getRepository('Entities\Termekcimkekat')->find($this->params->getIntRequestParam('markacs', 0));
        if ($markacs) {
            $this->setObj(\mkw\consts::MarkaCs, $markacs->getId());
        } else {
            $this->setObj(\mkw\consts::MarkaCs, '');
        }

        $dencs = \mkw\store::getEm()->getRepository('Entities\Termekcimkekat')->find($this->params->getIntRequestParam('dencs', 0));
        if ($dencs) {
            $this->setObj(\mkw\consts::DENCs, $dencs->getId());
        } else {
            $this->setObj(\mkw\consts::DENCs, '');
        }
        $dencs = \mkw\store::getEm()->getRepository('Entities\Termekcimkekat')->find($this->params->getIntRequestParam('epitoelemszamcs', 0));
        if ($dencs) {
            $this->setObj(\mkw\consts::EpitoelemszamCs, $dencs->getId());
        } else {
            $this->setObj(\mkw\consts::EpitoelemszamCs, '');
        }
        $dencs = \mkw\store::getEm()->getRepository('Entities\Termekcimkekat')->find($this->params->getIntRequestParam('csomagoltmeretcs', 0));
        if ($dencs) {
            $this->setObj(\mkw\consts::CsomagoltmeretCs, $dencs->getId());
        } else {
            $this->setObj(\mkw\consts::CsomagoltmeretCs, '');
        }
        $dencs = \mkw\store::getEm()->getRepository('Entities\Termekcimkekat')->find($this->params->getIntRequestParam('ajanlottkorosztalycs', 0));
        if ($dencs) {
            $this->setObj(\mkw\consts::AjanlottkorosztalyCs, $dencs->getId());
        } else {
            $this->setObj(\mkw\consts::AjanlottkorosztalyCs, '');
        }

        $kiskercimke = \mkw\store::getEm()->getRepository('Entities\Partnercimketorzs')->find($this->params->getIntRequestParam('kiskercimke', 0));
        if ($kiskercimke) {
            $this->setObj(\mkw\consts::KiskerCimke, $kiskercimke->getId());
        } else {
            $this->setObj(\mkw\consts::KiskerCimke, '');
        }

        $nagykercimke = \mkw\store::getEm()->getRepository('Entities\Partnercimketorzs')->find($this->params->getIntRequestParam('nagykercimke', 0));
        if ($nagykercimke) {
            $this->setObj(\mkw\consts::NagykerCimke, $nagykercimke->getId());
        } else {
            $this->setObj(\mkw\consts::NagykerCimke, '');
        }

        $felvetelalattcimke = \mkw\store::getEm()->getRepository('Entities\Partnercimketorzs')->find(
            $this->params->getIntRequestParam('felvetelalattcimke', 0)
        );
        if ($felvetelalattcimke) {
            $this->setObj(\mkw\consts::FelvetelAlattCimke, $felvetelalattcimke->getId());
        } else {
            $this->setObj(\mkw\consts::FelvetelAlattCimke, '');
        }

        $felvetelalattpartnertipus = \mkw\store::getEm()->getRepository('Entities\Partnertipus')->find(
            $this->params->getIntRequestParam('felvetelalattpartnertipus', 0)
        );
        if ($felvetelalattpartnertipus) {
            $this->setObj(\mkw\consts::FelvetelAlattTipus, $felvetelalattpartnertipus->getId());
        } else {
            $this->setObj(\mkw\consts::FelvetelAlattTipus, '');
        }

        $spanyolcimke = \mkw\store::getEm()->getRepository('Entities\Partnercimketorzs')->find($this->params->getIntRequestParam('spanyolcimke', 0));
        if ($spanyolcimke) {
            $this->setObj(\mkw\consts::SpanyolCimke, $spanyolcimke->getId());
        } else {
            $this->setObj(\mkw\consts::SpanyolCimke, '');
        }

        $spanyolorszag = \mkw\store::getEm()->getRepository('Entities\Orszag')->find($this->params->getIntRequestParam('spanyolorszag', 0));
        if ($spanyolorszag) {
            $this->setObj(\mkw\consts::Spanyolorszag, $spanyolorszag->getId());
        } else {
            $this->setObj(\mkw\consts::Spanyolorszag, '');
        }
        $orszag = \mkw\store::getEm()->getRepository('Entities\Orszag')->find($this->params->getIntRequestParam('orszag', 0));
        if ($orszag) {
            $this->setObj(\mkw\consts::Orszag, $orszag->getId());
        } else {
            $this->setObj(\mkw\consts::Orszag, '');
        }
        $magyarorszag = \mkw\store::getEm()->getRepository('Entities\Orszag')->find($this->params->getIntRequestParam('magyarorszag', 0));
        if ($magyarorszag) {
            $this->setObj(\mkw\consts::Magyarorszag, $magyarorszag->getId());
        } else {
            $this->setObj(\mkw\consts::Magyarorszag, '');
        }

        $sz = \mkw\store::getEm()->getRepository('Entities\TermekValtozatAdatTipus')->find($this->params->getIntRequestParam('valtozattipusszin', 0));
        if ($sz) {
            $this->setObj(\mkw\consts::ValtozatTipusSzin, $sz->getId());
        } else {
            $this->setObj(\mkw\consts::ValtozatTipusSzin, '');
        }

        $sz = \mkw\store::getEm()->getRepository('Entities\TermekValtozatAdatTipus')->find($this->params->getIntRequestParam('valtozattipusmeret', 0));
        if ($sz) {
            $this->setObj(\mkw\consts::ValtozatTipusMeret, $sz->getId());
        } else {
            $this->setObj(\mkw\consts::ValtozatTipusMeret, '');
        }

        $sz = \mkw\store::getEm()->getRepository('Entities\TermekValtozatAdatTipus')->find($this->params->getIntRequestParam('rendezendovaltozat', 0));
        if ($sz) {
            $this->setObj(\mkw\consts::RendezendoValtozat, $sz->getId());
        } else {
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
        } else {
            $this->setObj(\mkw\consts::BizonylatStatuszFuggoben, '');
        }
        $bsf = \mkw\store::getEm()->getRepository('Entities\Bizonylatstatusz')->find($this->params->getIntRequestParam('bizonylatstatuszteljesitheto', 0));
        if ($bsf) {
            $this->setObj(\mkw\consts::BizonylatStatuszTeljesitheto, $bsf->getId());
        } else {
            $this->setObj(\mkw\consts::BizonylatStatuszTeljesitheto, '');
        }
        $bsf = \mkw\store::getEm()->getRepository('Entities\Bizonylatstatusz')->find($this->params->getIntRequestParam('bizonylatstatuszbackorder', 0));
        if ($bsf) {
            $this->setObj(\mkw\consts::BizonylatStatuszBackorder, $bsf->getId());
        } else {
            $this->setObj(\mkw\consts::BizonylatStatuszBackorder, '');
        }
        $this->setObj(\mkw\consts::MegrendelesFilterStatuszCsoport, $this->params->getStringRequestParam('megrendelesfilterstatuszcsoport'));
        $bsf = \mkw\store::getEm()->getRepository('Entities\Bizonylatstatusz')->find($this->params->getIntRequestParam('barionfizetesrevarstatusz', 0));
        if ($bsf) {
            $this->setObj(\mkw\consts::BarionFizetesrevarStatusz, $bsf->getId());
        } else {
            $this->setObj(\mkw\consts::BarionFizetesrevarStatusz, '');
        }
        $bsf = \mkw\store::getEm()->getRepository('Entities\Bizonylatstatusz')->find($this->params->getIntRequestParam('barionfizetvestatusz', 0));
        if ($bsf) {
            $this->setObj(\mkw\consts::BarionFizetveStatusz, $bsf->getId());
        } else {
            $this->setObj(\mkw\consts::BarionFizetveStatusz, '');
        }
        $bsf = \mkw\store::getEm()->getRepository('Entities\Bizonylatstatusz')->find($this->params->getIntRequestParam('barionrefundedstatusz', 0));
        if ($bsf) {
            $this->setObj(\mkw\consts::BarionRefundedStatusz, $bsf->getId());
        } else {
            $this->setObj(\mkw\consts::BarionRefundedStatusz, '');
        }

        $this->setObj(\mkw\consts::Esedekessegalap, $this->params->getIntRequestParam('esedekessegalap', 1));
        $this->setObj(\mkw\consts::Locale, $this->params->getStringRequestParam('locale'));

        $inkid = $this->params->getIntRequestParam('importnewkatid');
        if ($inkid) {
            $importnewkat = \mkw\store::getEm()->getRepository('Entities\TermekFa')->find($inkid);
            $this->setObj(\mkw\consts::ImportNewKatId, $importnewkat->getId());
        } else {
            $this->setObj(\mkw\consts::ImportNewKatId, 0);
        }
        $inkid = $this->params->getIntRequestParam(\mkw\consts::NoMinKeszletTermekkat);
        if ($inkid) {
            $importnewkat = \mkw\store::getEm()->getRepository('Entities\TermekFa')->find($inkid);
            $this->setObj(\mkw\consts::NoMinKeszletTermekkat, $importnewkat->getId());
        } else {
            $this->setObj(\mkw\consts::NoMinKeszletTermekkat, 0);
        }
        $this->setObj(\mkw\consts::NoMinKeszlet, $this->params->getBoolRequestParam(\mkw\consts::NoMinKeszlet));

        $inkid = $this->params->getIntRequestParam('mugenracekatid');
        if ($inkid) {
            $mugenracekat = \mkw\store::getEm()->getRepository('Entities\TermekFa')->find($inkid);
            $this->setObj(\mkw\consts::MugenraceKatId, $mugenracekat->getId());
        } else {
            $this->setObj(\mkw\consts::MugenraceKatId, 0);
        }
        $inkid = $this->params->getIntRequestParam('web4defakatid');
        if ($inkid) {
            $importnewkat = \mkw\store::getEm()->getRepository('Entities\TermekFa')->find($inkid);
            $this->setObj(\mkw\consts::Web4DefaKatId, $importnewkat->getId());
        } else {
            $this->setObj(\mkw\consts::Web4DefaKatId, 0);
        }
        //feed
        $this->setObj(\mkw\consts::Feedhirdb, $this->params->getIntRequestParam('feedhirdb', 20));
        $this->setObj(\mkw\consts::Feedhirtitle, $this->params->getStringRequestParam('feedhirtitle', t('Híreink')));
        $this->setObj(\mkw\consts::Feedhirdescription, $this->params->getStringRequestParam('feedhirdescription', t('Híreink')));
        $this->setObj(\mkw\consts::Feedtermekdb, $this->params->getIntRequestParam('feedtermekdb', 30));
        $this->setObj(\mkw\consts::Feedtermektitle, $this->params->getStringRequestParam('feedtermektitle', t('Termékeink')));
        $this->setObj(\mkw\consts::Feedtermekdescription, $this->params->getStringRequestParam('feedtermekdescription', t('Termékeink')));
        $this->setObj(\mkw\consts::Feedblogdb, $this->params->getIntRequestParam('feedblogdb', 20));
        $this->setObj(\mkw\consts::Feedblogtitle, $this->params->getStringRequestParam('feedblogtitle', t('Blog')));
        $this->setObj(\mkw\consts::Feedblogdescription, $this->params->getStringRequestParam('feedblogdescription', t('Blog')));
        // sitemap
        $this->setObj(\mkw\consts::Statlapprior, $this->params->getNumRequestParam('statlapprior', 0.4));
        $this->setObj(\mkw\consts::Blogposztprior, $this->params->getNumRequestParam('blogposztprior', 0.4));
        $this->setObj(\mkw\consts::Termekprior, $this->params->getNumRequestParam('termekprior', 0.5));
        $this->setObj(\mkw\consts::Kategoriaprior, $this->params->getNumRequestParam('kategoriaprior', 0.7));
        $this->setObj(\mkw\consts::Fooldalprior, $this->params->getNumRequestParam('fooldalprior', 1));
        $this->setObj(\mkw\consts::Statlapchangefreq, $this->params->getStringRequestParam('statlapchangefreq', 'monthly'));
        $this->setObj(\mkw\consts::Blogposztchangefreq, $this->params->getStringRequestParam('blogposztchangefreq', 'monthly'));
        $this->setObj(\mkw\consts::Termekchangefreq, $this->params->getStringRequestParam('termekchangefreq', 'monthly'));
        $this->setObj(\mkw\consts::Kategoriachangefreq, $this->params->getStringRequestParam('kategoriachangefreq', 'daily'));
        $this->setObj(\mkw\consts::Fooldalchangefreq, $this->params->getStringRequestParam('fooldalchangefreq', 'daily'));

        $this->setObj(\mkw\consts::AKTrustedShopApiKey, $this->params->getStringRequestParam('aktrustedshopapikey', ''));

        $gyarto = \mkw\store::getEm()->getRepository('Entities\Partner');

        $x = $this->params->getIntRequestParam('gyartobtech', 0);
        $partner = $gyarto->find($x);
        if ($partner) {
            $this->setObj(\mkw\consts::GyartoBtech, $partner->getId());
        } else {
            $this->setObj(\mkw\consts::GyartoBtech, '');
        }
        $x = $this->params->getIntRequestParam('gyartodelton', 0);
        $partner = $gyarto->find($x);
        if ($partner) {
            $this->setObj(\mkw\consts::GyartoDelton, $partner->getId());
        } else {
            $this->setObj(\mkw\consts::GyartoDelton, '');
        }
        $x = $this->params->getIntRequestParam('gyartokreativ', 0);
        $partner = $gyarto->find($x);
        if ($partner) {
            $this->setObj(\mkw\consts::GyartoKreativ, $partner->getId());
        } else {
            $this->setObj(\mkw\consts::GyartoKreativ, '');
        }
        $x = $this->params->getIntRequestParam('gyartomaxutov', 0);
        $partner = $gyarto->find($x);
        if ($partner) {
            $this->setObj(\mkw\consts::GyartoMaxutov, $partner->getId());
        } else {
            $this->setObj(\mkw\consts::GyartoMaxutov, '');
        }
        $x = $this->params->getIntRequestParam('gyartoreintex', 0);
        $partner = $gyarto->find($x);
        if ($partner) {
            $this->setObj(\mkw\consts::GyartoReintex, $partner->getId());
        } else {
            $this->setObj(\mkw\consts::GyartoReintex, '');
        }
        $x = $this->params->getIntRequestParam('gyartotutisport', 0);
        $partner = $gyarto->find($x);
        if ($partner) {
            $this->setObj(\mkw\consts::GyartoTutisport, $partner->getId());
        } else {
            $this->setObj(\mkw\consts::GyartoTutisport, '');
        }
        $x = $this->params->getIntRequestParam('gyartosilko', 0);
        $partner = $gyarto->find($x);
        if ($partner) {
            $this->setObj(\mkw\consts::GyartoSilko, $partner->getId());
        } else {
            $this->setObj(\mkw\consts::GyartoSilko, '');
        }
        $x = $this->params->getIntRequestParam('gyartokress', 0);
        $partner = $gyarto->find($x);
        if ($partner) {
            $this->setObj(\mkw\consts::GyartoKress, $partner->getId());
        } else {
            $this->setObj(\mkw\consts::GyartoKress, '');
        }
        $x = $this->params->getIntRequestParam('gyartolegavenue', 0);
        $partner = $gyarto->find($x);
        if ($partner) {
            $this->setObj(\mkw\consts::GyartoLegavenue, $partner->getId());
        } else {
            $this->setObj(\mkw\consts::GyartoLegavenue, '');
        }
        $x = $this->params->getIntRequestParam('gyartonomad', 0);
        $partner = $gyarto->find($x);
        if ($partner) {
            $this->setObj(\mkw\consts::GyartoNomad, $partner->getId());
        } else {
            $this->setObj(\mkw\consts::GyartoNomad, '');
        }
        $x = $this->params->getIntRequestParam('gyartonika', 0);
        $partner = $gyarto->find($x);
        if ($partner) {
            $this->setObj(\mkw\consts::GyartoNika, $partner->getId());
        } else {
            $this->setObj(\mkw\consts::GyartoNika, '');
        }
        $x = $this->params->getIntRequestParam('gyartohaffner24', 0);
        $partner = $gyarto->find($x);
        if ($partner) {
            $this->setObj(\mkw\consts::GyartoHaffner24, $partner->getId());
        } else {
            $this->setObj(\mkw\consts::GyartoHaffner24, '');
        }
        $x = $this->params->getIntRequestParam('gyartoevona', 0);
        $partner = $gyarto->find($x);
        if ($partner) {
            $this->setObj(\mkw\consts::GyartoEvona, $partner->getId());
        } else {
            $this->setObj(\mkw\consts::GyartoEvona, '');
        }
        $x = $this->params->getIntRequestParam('gyartoevonaxml', 0);
        $partner = $gyarto->find($x);
        if ($partner) {
            $this->setObj(\mkw\consts::GyartoEvonaXML, $partner->getId());
        } else {
            $this->setObj(\mkw\consts::GyartoEvonaXML, '');
        }
        $x = $this->params->getIntRequestParam('gyartonetpresso', 0);
        $partner = $gyarto->find($x);
        if ($partner) {
            $this->setObj(\mkw\consts::GyartoNetpresso, $partner->getId());
        } else {
            $this->setObj(\mkw\consts::GyartoNetpresso, '');
        }
        $x = $this->params->getIntRequestParam('gyartogulf', 0);
        $partner = $gyarto->find($x);
        if ($partner) {
            $this->setObj(\mkw\consts::GyartoGulf, $partner->getId());
        } else {
            $this->setObj(\mkw\consts::GyartoGulf, '');
        }
        $x = $this->params->getIntRequestParam('gyartoqman', 0);
        $partner = $gyarto->find($x);
        if ($partner) {
            $this->setObj(\mkw\consts::GyartoQman, $partner->getId());
        } else {
            $this->setObj(\mkw\consts::GyartoQman, '');
        }
        $x = $this->params->getIntRequestParam('gyartosmileebike', 0);
        $partner = $gyarto->find($x);
        if ($partner) {
            $this->setObj(\mkw\consts::GyartoSmileebike, $partner->getId());
        } else {
            $this->setObj(\mkw\consts::GyartoSmileebike, '');
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
        $this->setObj(\mkw\consts::PathNika, $this->params->getStringRequestParam('pathnika', ''));
        $this->setObj(\mkw\consts::PathHaffner24, $this->params->getStringRequestParam('pathhaffner24', ''));
        $this->setObj(\mkw\consts::PathEvona, $this->params->getStringRequestParam('pathevona', ''));
        $this->setObj(\mkw\consts::PathNetpresso, $this->params->getStringRequestParam('pathnetpresso', ''));
        $this->setObj(\mkw\consts::PathSmileebike, $this->params->getStringRequestParam('pathsmileebike', ''));

        $this->setObj(\mkw\consts::UrlKreativ, $this->params->getStringRequestParam('urlkreativ', ''), true);
        $this->setObj(\mkw\consts::UrlKreativImages, $this->params->getStringRequestParam('urlkreativimages', ''), true);
        $this->setObj(\mkw\consts::UrlDelton, $this->params->getStringRequestParam('urldelton', ''), true);
        $this->setObj(\mkw\consts::UrlNomad, $this->params->getStringRequestParam('urlnomad', ''), true);
        $this->setObj(\mkw\consts::UrlNika, $this->params->getStringRequestParam('urlnika', ''), true);
        $this->setObj(\mkw\consts::UrlMaxutov, $this->params->getStringRequestParam('urlmaxutov', ''), true);
        $this->setObj(\mkw\consts::UrlLegavenue, $this->params->getStringRequestParam('urllegavenue', ''), true);
        $this->setObj(\mkw\consts::UrlHaffner24, $this->params->getStringRequestParam('urlhaffner24', ''), true);
        $this->setObj(\mkw\consts::UrlReintex, $this->params->getStringRequestParam('urlreintex', ''), true);
        $this->setObj(\mkw\consts::UrlNetpresso, $this->params->getStringRequestParam('urlnetpresso', ''), true);
        $this->setObj(\mkw\consts::UrlEvonaXML, $this->params->getStringRequestParam('urlevonaxml', ''), true);
        $this->setObj(\mkw\consts::UrlSmileebike, $this->params->getStringRequestParam('urlsmileebike', ''), true);

        $this->setObj(\mkw\consts::KepUrlEvona, $this->params->getStringRequestParam('kepurlevona', ''));

        $this->setObj(\mkw\consts::ExcludeReintex, $this->params->getStringRequestParam('excludereintex', ''), true);

        $this->setObj(\mkw\consts::MugenraceLogo, $this->params->getStringRequestParam('mugenracelogo'));
        $this->setObj(\mkw\consts::MugenraceFooterLogo, $this->params->getStringRequestParam('mugenracefooterlogo'));
        $this->setObj(\mkw\consts::MugenraceFooldalKep, $this->params->getStringRequestParam('mugenracefooldalkep'));
        $this->setObj(\mkw\consts::MugenraceFejlecKep, $this->params->getStringRequestParam('mugenracefejleckep'));
        $this->setObj(\mkw\consts::MugenraceFooldalSzoveg, $this->params->getStringRequestParam('mugenracefooldalszoveg'));

        \mkw\store::getEm()->flush();
    }

    public function getMPTNGYSzempontList()
    {
        $repo = \mkw\store::getEm()->getRepository($this->getEntityName());
        echo json_encode([
            1 => $repo->find(\mkw\consts::MPTNGYSzempont1)->getErtek(),
            2 => $repo->find(\mkw\consts::MPTNGYSzempont2)->getErtek(),
            3 => $repo->find(\mkw\consts::MPTNGYSzempont3)->getErtek(),
            4 => $repo->find(\mkw\consts::MPTNGYSzempont4)->getErtek(),
            5 => $repo->find(\mkw\consts::MPTNGYSzempont5)->getErtek(),
        ]);
    }
}