<?php

namespace Traits;

use Controllers\partnertermekcsoportkedvezmenyController;
use Controllers\partnertermekkedvezmenyController;
use Entities\Arsav;
use Entities\Fizmod;
use Entities\MPTNGYSzerepkor;
use Entities\MPTSzekcio;
use Entities\MPTTagozat;
use Entities\MPTTagsagforma;
use Entities\Orszag;
use Entities\Partner;
use Entities\Partnercimketorzs;
use Entities\PartnerDok;
use Entities\PartnerTermekcsoportKedvezmeny;
use Entities\PartnerTermekKedvezmeny;
use Entities\Partnertipus;
use Entities\Szallitasimod;
use Entities\Termek;
use Entities\Termekcsoport;
use Entities\Uzletkoto;
use Entities\Valutanem;
use Controllers\partnerdokController;
use Controllers\mptfolyoszamlaController;
use Controllers\partnercimkekatController;
use Controllers\orszagController;
use Controllers\partnertipusController;
use Controllers\arsavController;
use Controllers\termekcsoportController;
use Controllers\emailtemplateController;
use Controllers\mptngyegyetemController;
use Controllers\mptngykarController;
use Controllers\fizmodController;
use Controllers\uzletkotoController;
use Controllers\valutanemController;
use Controllers\szallitasimodController;
use Controllers\mpttagsagformaController;
use Controllers\mptszekcioController;
use Controllers\mpttagozatController;
use Controllers\korzetszamController;
use Controllers\mptngyszerepkorController;

trait PartnerAdminCrud
{
    public function loadVars($t, $forKarb = false)
    {
        $kedvCtrl = new partnertermekcsoportkedvezmenyController();
        $termekkedvCtrl = new partnertermekkedvezmenyController();
        $dokCtrl = new partnerdokController();
        $mptfolyoszamlaCtrl = new mptfolyoszamlaController();
        $x = [];
        if (!$t) {
            $t = new \Entities\Partner();
            $this->getEm()->detach($t);
        }
        $x['id'] = $t->getId();
        $x['nev'] = $t->getNev();
        $x['vezeteknev'] = $t->getVezeteknev();
        $x['keresztnev'] = $t->getKeresztnev();
        $x['nevelotag'] = $t->getNevelotag();
        $x['inaktiv'] = $t->getInaktiv();
        $x['idegenkod'] = $t->getIdegenkod();
        $x['adoszam'] = $t->getAdoszam();
        $x['csoportosadoszam'] = $t->getCsoportosadoszam();
        $x['euadoszam'] = $t->getEuadoszam();
        $x['thirdadoszam'] = $t->getThirdadoszam();
        $x['mukengszam'] = $t->getMukengszam();
        $x['jovengszam'] = $t->getJovengszam();
        $x['ostermszam'] = $t->getOstermszam();
        $x['valligszam'] = $t->getValligszam();
        $x['fvmszam'] = $t->getFvmszam();
        $x['cjszam'] = $t->getCjszam();
        $x['cim'] = $t->getCim();
        $x['irszam'] = $t->getIrszam();
        $x['varos'] = $t->getVaros();
        $x['utca'] = $t->getUtca();
        $x['hazszam'] = $t->getHazszam();
        $x['orszag'] = $t->getOrszag();
        $x['orszagnev'] = $t->getOrszagNev();
        $x['szallorszag'] = $t->getSzallorszag();
        $x['szallorszagnev'] = $t->getSzallorszagNev();
        $x['lcim'] = $t->getLCim();
        $x['lirszam'] = $t->getLirszam();
        $x['lvaros'] = $t->getLvaros();
        $x['lutca'] = $t->getLutca();
        $x['lhazszam'] = $t->getLhazszam();
        $x['telefon'] = $t->getTelefon();
        $x['telszam'] = $t->getTelszam();
        $x['telkorzet'] = $t->getTelkorzet();
        $x['mobil'] = $t->getMobil();
        $x['fax'] = $t->getFax();
        $x['email'] = $t->getEmail();
        $x['honlap'] = $t->getHonlap();
        $x['megjegyzes'] = $t->getMegjegyzes();
        $x['syncid'] = $t->getSyncid();
        $x['cimkek'] = $t->getCimkeNevek();
        $x['fizmodnev'] = $t->getFizmodNev();
        $x['uzletkotonev'] = $t->getUzletkotoNev();
        $x['fizhatido'] = $t->getFizhatido();
        $x['szallnev'] = $t->getSzallnev();
        $x['szallirszam'] = $t->getSzallirszam();
        $x['szallvaros'] = $t->getSzallvaros();
        $x['szallutca'] = $t->getSzallutca();
        $x['szallhazszam'] = $t->getSzallhazszam();
        $x['szallcim'] = $t->getSzallcim();
        $x['nem'] = $t->getNem();
        $x['szuletesiido'] = $t->getSzuletesiido();
        $x['szuletesiidostr'] = $t->getSzuletesiidoStr();
        $x['akcioshirlevelkell'] = $t->getAkcioshirlevelkell();
        $x['ujdonsaghirlevelkell'] = $t->getUjdonsaghirlevelkell();
        $x['loggedin'] = $this->checkloggedin();
        $x['vendeg'] = $t->getVendeg();
        $x['ip'] = $t->getIp();
        $x['referrer'] = $t->getReferrer();
        $x['szallito'] = $t->getSzallito();
        $x['exportbacsakkeszlet'] = $t->isExportbacsakkeszlet();
        $x['szallitasiido'] = $t->getSzallitasiido();
        $x['szamlatipus'] = $t->getSzamlatipus();
        $x['banknev'] = $t->getBanknev();
        $x['bankcim'] = $t->getBankcim();
        $x['iban'] = $t->getIban();
        $x['swift'] = $t->getSwift();
        $x['valutanem'] = $t->getValutanem();
        $x['valutanemnev'] = $t->getValutanemnev();
        $x['arsav'] = $t->getArsav();
        $x['arsavnev'] = $t->getArsav()?->getNev();
        $x['szallitasimod'] = $t->getSzallitasimod();
        $x['szallitasimodnev'] = $t->getSzallitasimodNev();
        $x['partnertipus'] = $t->getPartnertipus();
        $x['partnertipusnev'] = $t->getPartnertipusNev();
        $x['bizonylatnyelv'] = $t->getBizonylatnyelv();
        $x['ezuzletkoto'] = $t->getEzuzletkoto();
        $x['ktdatvallal'] = $t->getKtdatvallal();
        $x['ktdatalany'] = $t->getKtdatalany();
        $x['ktdszerzszam'] = $t->getKtdszerzszam();
        $x['munkahelyneve'] = $t->getMunkahelyneve();
        $x['foglalkozas'] = $t->getFoglalkozas();
        $x['anonymizalnikell'] = $t->getAnonymizalnikell();
        $x['anonym'] = $t->getAnonym();
        $x['apinev'] = $t->getApiconsumernev();
        $x['szamlalevelmegszolitas'] = $t->getSzamlalevelmegszolitas();
        $x['kulsos'] = $t->getKulsos();
        $x['mennyisegetlathat'] = $t->isMennyisegetlathat();
        $x['vatstatus'] = $t->getVatstatus();
        $x['szamlaegyeb'] = $t->getSzamlaegyeb();
        $x['lastmodstr'] = $t->getLastmodStr();
        $x['createdstr'] = $t->getCreatedStr();
        $x['updatedby'] = $t->getUpdatedbyNev();
        $x['createdby'] = $t->getCreatedbyNev();
        $x['mpt_username'] = $t->getMptUsername();
        $x['mpt_registerdatestr'] = $t->getMptRegisterdateStr();
        $x['mpt_lastvisitstr'] = $t->getMptLastvisitStr();
        $x['mpt_lastupdatestr'] = $t->getMptLastupdateStr();
        $x['mpt_userid'] = $t->getMptUserid();
        $x['mpt_munkahelynev'] = $t->getMptMunkahelynev();
        $x['mpt_munkahelyirszam'] = $t->getMptMunkahelyirszam();
        $x['mpt_munkahelyvaros'] = $t->getMptMunkahelyvaros();
        $x['mpt_munkahelyutca'] = $t->getMptMunkahelyutca();
        $x['mpt_munkahelyhazszam'] = $t->getMptMunkahelyhazszam();
        $x['mpt_lakcimirszam'] = $t->getMptLakcimirszam();
        $x['mpt_lakcimvaros'] = $t->getMptLakcimvaros();
        $x['mpt_lakcimutca'] = $t->getMptLakcimutca();
        $x['mpt_lakcimhazszam'] = $t->getMptLakcimhazszam();
        $x['mpt_tagkartya'] = $t->getMptTagkartya();
        $x['mpt_szekcio1'] = $t->getMptSzekcio1Id();
        $x['mpt_szekcio1nev'] = $t->getMptSzekcio1Nev();
        $x['mpt_szekcio2'] = $t->getMptSzekcio2Id();
        $x['mpt_szekcio2nev'] = $t->getMptSzekcio2Nev();
        $x['mpt_szekcio3'] = $t->getMptSzekcio3Id();
        $x['mpt_szekcio3nev'] = $t->getMptSzekcio3Nev();
        $x['mpt_tagozat'] = $t->getMptTagozatId();
        $x['mpt_tagozatnev'] = $t->getMptTagozatNev();
        $x['mpt_tagsagforma'] = $t->getMptTagsagformaId();
        $x['mpt_tagsagformanev'] = $t->getMptTagsagformaNev();
        $x['mpt_megszolitas'] = $t->getMptMegszolitas();
        $x['mpt_fokozat'] = $t->getMptFokozat();
        $x['mpt_vegzettseg'] = $t->getMptVegzettseg();
        $x['mpt_szuleteseve'] = $t->getMptSzuleteseve();
        $x['mpt_diplomahely'] = $t->getMptDiplomahely();
        $x['mpt_diplomaeve'] = $t->getMptDiplomaeve();
        $x['mpt_egyebdiploma'] = $t->getMptEgyebdiploma();
        $x['mpt_privatemail'] = $t->getMptPrivatemail();
        $x['mpt_tagsagdatestr'] = $t->getMptTagsagdateStr();
        $x['mpt_szamlazasinev'] = $t->getMptSzamlazasinev();
        $x['mptngynapreszvetel1'] = $t->isMptngynapreszvetel1();
        $x['mptngynapreszvetel2'] = $t->isMptngynapreszvetel2();
        $x['mptngynapreszvetel3'] = $t->isMptngynapreszvetel3();
        $x['mptngycsoportosfizetes'] = $t->getMptngycsoportosfizetes();
        $x['mptngyvipvacsora'] = $t->isMptngyvipvacsora();
        $x['mptngybankett'] = $t->isMptngybankett();
        $x['mptngykapcsolatnev'] = $t->getMptngykapcsolatnev();
        $x['mptngybankszamlaszam'] = $t->getMptngybankszamlaszam();
        $x['szlanev'] = $t->getSzlanev();
        $x['mptngydiak'] = $t->isMptngydiak();
        $x['mptngynyugdijas'] = $t->isMptngynyugdijas();
        $x['mptngympttag'] = $t->isMptngympttag();
        $x['mptngybefizetes'] = $t->getMptngybefizetes();
        $x['mptngybefizetesdatum'] = $t->getMptngybefizetesdatumStr();
        $x['mptngybefizetesmodnev'] = $t->getMptngybefizetesmod()?->getNev();
        $x['mptngynemveszreszt'] = $t->isMptngynemveszreszt();
        $x['mptngyphd'] = $t->isMptngyphd();
        $x['mptngyegyetem'] = $t->getMPTNGYEgyetemId();
        $x['mptngyegyetemnev'] = $t->getMPTNGYEgyetemNev();
        $x['mptngykar'] = $t->getMPTNGYKarId();
        $x['mptngykarnev'] = $t->getMPTNGYKarNev();
        $x['mptngyegyetemegyeb'] = $t->getMPTNGYEgyetemegyeb();
        $x['xnemrendelhet'] = $t->isXNemrendelhet();
        $x['nemrendelhet'] = $t->isNemrendelhet();
        $x['nemrendelhet2'] = $t->isNemrendelhet2();
        $x['nemrendelhet3'] = $t->isNemrendelhet3();
        $x['nemrendelhet4'] = $t->isNemrendelhet4();
        $x['nemrendelhet5'] = $t->isNemrendelhet5();
        $afaoverride = $t->getAFAOverride();
        if ($afaoverride) {
            $x['afa'] = $afaoverride->getId();
            $x['afakulcs'] = $afaoverride->getErtek();
        }
        if ($forKarb) {
            $kedv = [];
            foreach ($t->getTermekcsoportkedvezmenyek() as $tar) {
                $kedv[] = $kedvCtrl->loadVars($tar, true);
            }
            $x['termekcsoportkedvezmenyek'] = $kedv;
            $kedv = [];
            foreach ($t->getTermekkedvezmenyek() as $tar) {
                $kedv[] = $termekkedvCtrl->loadVars($tar, true);
            }
            $x['termekkedvezmenyek'] = $kedv;

            $dok = [];
            foreach ($t->getPartnerDokok() as $kepje) {
                $dok[] = $dokCtrl->loadVars($kepje);
            }
            $x['dokok'] = $dok;
        }

        if (\mkw\store::isMPT()) {
            $fsz = [];
            foreach ($t->getMPTFolyoszamlak() as $item) {
                $fsz[] = $mptfolyoszamlaCtrl->loadVars($item, true);
            }
            $x['mptfolyoszamla'] = $fsz;
        }
        return $x;
    }

    /**
     * @param \Entities\Partner $obj
     *
     * @return \Entities\Partner
     */
    public function setFields($obj, $parancs, $subject = 'minden')
    {
        if ($subject === 'minden') {
            $j1 = $this->params->getStringRequestParam('jelszo1');
            $j2 = $this->params->getStringRequestParam('jelszo2');
            if ($j1 && $j2 && $j1 === $j2) {
                $obj->setJelszo($j1);
            }
            $obj->setInaktiv($this->params->getBoolRequestParam('inaktiv'));
            $obj->setIdegenkod($this->params->getStringRequestParam('idegenkod'));
            $obj->setEuadoszam($this->params->getStringRequestParam('euadoszam'));
            $obj->setThirdadoszam($this->params->getStringRequestParam('thirdadoszam'));
            $obj->setMukengszam($this->params->getStringRequestParam('mukengszam'));
            $obj->setJovengszam($this->params->getStringRequestParam('jovengszam'));
            $obj->setOstermszam($this->params->getStringRequestParam('ostermszam'));
            $obj->setValligszam($this->params->getStringRequestParam('valligszam'));
            $obj->setFvmszam($this->params->getStringRequestParam('fvmszam'));
            $obj->setCjszam($this->params->getStringRequestParam('cjszam'));
            $obj->setLirszam($this->params->getStringRequestParam('lirszam'));
            $obj->setLvaros($this->params->getStringRequestParam('lvaros'));
            $obj->setLutca($this->params->getStringRequestParam('lutca'));
            $obj->setLhazszam($this->params->getStringRequestParam('lhazszam'));
            $obj->setMobil($this->params->getStringRequestParam('mobil'));
            $obj->setFax($this->params->getStringRequestParam('fax'));
            $obj->setHonlap($this->params->getStringRequestParam('honlap'));
            $obj->setMegjegyzes($this->params->getStringRequestParam('megjegyzes'));
            $obj->setSyncid($this->params->getStringRequestParam('syncid'));
            $obj->setFizhatido($this->params->getIntRequestParam('fizhatido'));
            $obj->setNem($this->params->getIntRequestParam('nem'));
            $obj->setSzuletesiido($this->params->getStringRequestParam('szuletesiido'));
            $obj->setSzallito($this->params->getBoolRequestParam('szallito'));
            $obj->setExportbacsakkeszlet($this->params->getBoolRequestParam('exportbacsakkeszlet'));
            $obj->setSzallitasiido($this->params->getIntRequestParam('szallitasiido'));
            $obj->setSzamlatipus($this->params->getIntRequestParam('szamlatipus'));
            $obj->setBizonylatnyelv($this->params->getStringRequestParam('bizonylatnyelv'));
            $obj->setEzuzletkoto($this->params->getBoolRequestParam('ezuzletkoto'));
            $obj->setKtdatalany($this->params->getBoolRequestParam('ktdatalany'));
            $obj->setKtdatvallal($this->params->getBoolRequestParam('ktdatvallal'));
            $obj->setKtdszerzszam($this->params->getStringRequestParam('ktdszerzszam'));
            $obj->setMunkahelyneve($this->params->getStringRequestParam('munkahelyneve'));
            $obj->setFoglalkozas($this->params->getStringRequestParam('foglalkozas'));
            $obj->setSzamlalevelmegszolitas($this->params->getStringRequestParam('szamlalevelmegszolitas'));
            $obj->setKulsos($this->params->getBoolRequestParam('kulsos'));
            $obj->setMennyisegetlathat($this->params->getBoolRequestParam('mennyisegetlathat'));
            $obj->setVatstatus($this->params->getIntRequestParam('vatstatus'));
            $obj->setSzamlaegyeb($this->params->getStringRequestParam('szamlaegyeb'));
            $obj->setSzlanev($this->params->getStringRequestParam('szlanev'));
            $obj->setNevelotag($this->params->getStringRequestParam('nevelotag'));
            $obj->setMptUsername($this->params->getStringRequestParam('mpt_username'));
            $obj->setMptPassword($this->params->getStringRequestParam('mpt_password'));
            $obj->setMptUserid($this->params->getIntRequestParam('mpt_userid'));
            $obj->setMptMunkahelynev($this->params->getStringRequestParam('mpt_munkahelynev'));
            $obj->setMptMunkahelyirszam($this->params->getStringRequestParam('mpt_munkahelyirszam'));
            $obj->setMptMunkahelyvaros($this->params->getStringRequestParam('mpt_munkahelyvaros'));
            $obj->setMptMunkahelyutca($this->params->getStringRequestParam('mpt_munkahelyutca'));
            $obj->setMptMunkahelyhazszam($this->params->getStringRequestParam('mpt_munkahelyhazszam'));
            $obj->setMptLakcimirszam($this->params->getStringRequestParam('mpt_lakcimirszam'));
            $obj->setMptLakcimvaros($this->params->getStringRequestParam('mpt_lakcimvaros'));
            $obj->setMptLakcimutca($this->params->getStringRequestParam('mpt_lakcimutca'));
            $obj->setMptLakcimhazszam($this->params->getStringRequestParam('mpt_lakcimhazszam'));
            $obj->setMptTagkartya($this->params->getStringRequestParam('mpt_tagkartya'));
            $obj->setMptMegszolitas($this->params->getStringRequestParam('mpt_megszolitas'));
            $obj->setMptFokozat($this->params->getStringRequestParam('mpt_fokozat'));
            $obj->setMptVegzettseg($this->params->getStringRequestParam('mpt_vegzettseg'));
            $obj->setMptSzuleteseve($this->params->getIntRequestParam('mpt_szuleteseve'));
            $obj->setMptDiplomaeve($this->params->getIntRequestParam('mpt_diplomaeve'));
            $obj->setMptDiplomahely($this->params->getStringRequestParam('mpt_diplomahely'));
            $obj->setMptEgyebdiploma($this->params->getStringRequestParam('mpt_egyebdiploma'));
            $obj->setMptPrivatemail($this->params->getStringRequestParam('mpt_privatemail'));
            $obj->setMptTagsagdate($this->params->getStringRequestParam('mpt_tagsagdate'));
            $obj->setMptSzamlazasinev($this->params->getStringRequestParam('mpt_szamlazasinev'));
            $obj->setMptngycsoportosfizetes($this->params->getStringRequestParam('mptngycsoportosfizetes'));
            $obj->setMptngykapcsolatnev($this->params->getStringRequestParam('mptngykapcsolatnev'));
            $obj->setMptngybankszamlaszam($this->params->getStringRequestParam('mptngybankszamlaszam'));
            $obj->setMptngyvipvacsora($this->params->getBoolRequestParam('mptngyvipvacsora'));
            $obj->setMptngybankett($this->params->getBoolRequestParam('mptngybankett'));
            $obj->setMptngynapreszvetel1($this->params->getBoolRequestParam('mptngynapreszvetel1'));
            $obj->setMptngynapreszvetel2($this->params->getBoolRequestParam('mptngynapreszvetel2'));
            $obj->setMptngynapreszvetel3($this->params->getBoolRequestParam('mptngynapreszvetel3'));
            $obj->setMptngydiak($this->params->getBoolRequestParam('mptngydiak'));
            $obj->setMptngynyugdijas($this->params->getBoolRequestParam('mptngynyugdijas'));
            $obj->setMptngympttag($this->params->getBoolRequestParam('mptngympttag'));
            $obj->setMptngybefizetes($this->params->getNumRequestParam('mptngybefizetes'));
            $obj->setMptngybefizetesdatum($this->params->getStringRequestParam('mptngybefizetesdatum'));
            $obj->setMptngynemveszreszt($this->params->getBoolRequestParam('mptngynemveszreszt'));
            $obj->setMptngyphd($this->params->getBoolRequestParam('mptngyphd'));
            /*
            $egyetem = \mkw\store::getEm()->getRepository(MPTNGYEgyetem::class)->find($this->params->getIntRequestParam('mptngyegyetem'));
            if ($egyetem) {
                $obj->setMPTNGYEgyetem($egyetem);
            } else {
                $obj->removeMPTNGYEgyetem();
            }

            $kar = \mkw\store::getEm()->getRepository(MPTNGYKar::class)->find($this->params->getIntRequestParam('mptngykar'));
            if ($kar) {
                $obj->setMPTNGYKar($kar);
            } else {
                $obj->removeMPTNGYKar();
            }

            $obj->setMPTNGYEgyetemegyeb($this->params->getStringRequestParam('mptngyegyetemegyeb'));
            */
            $obj->setNemrendelhet($this->params->getBoolRequestParam('nemrendelhet'));
            $obj->setNemrendelhet2($this->params->getBoolRequestParam('nemrendelhet2'));
            $obj->setNemrendelhet3($this->params->getBoolRequestParam('nemrendelhet3'));
            $obj->setNemrendelhet4($this->params->getBoolRequestParam('nemrendelhet4'));
            $obj->setNemrendelhet5($this->params->getBoolRequestParam('nemrendelhet5'));
            $arsav = \mkw\store::getEm()->getRepository(Arsav::class)->find($this->params->getIntRequestParam('arsav'));
            if ($arsav) {
                $obj->setArsav($arsav);
            } else {
                $obj->removeArsav();
            }
            $fizmod = \mkw\store::getEm()->getRepository(Fizmod::class)->find($this->params->getIntRequestParam('mptngybefizetesmod', 0));
            if ($fizmod) {
                $obj->setMptngybefizetesmod($fizmod);
            } else {
                $obj->setMptngybefizetesmod(null);
            }
            $mptngyszerepkor = \mkw\store::getEm()->getRepository(MPTNGYSzerepkor::class)->find($this->params->getIntRequestParam('mptngyszerepkor', 0));
            if ($mptngyszerepkor) {
                $obj->setMptngyszerepkor($mptngyszerepkor);
            } else {
                $obj->setMptngyszerepkor(null);
            }
            $mptszekcio = \mkw\store::getEm()->getRepository(MPTSzekcio::class)->find($this->params->getIntRequestParam('mpt_szekcio1', 0));
            if ($mptszekcio) {
                $obj->setMptSzekcio1($mptszekcio);
            } else {
                $obj->setMptSzekcio1(null);
            }
            $mptszekcio = \mkw\store::getEm()->getRepository(MPTSzekcio::class)->find($this->params->getIntRequestParam('mpt_szekcio2', 0));
            if ($mptszekcio) {
                $obj->setMptSzekcio2($mptszekcio);
            } else {
                $obj->setMptSzekcio2(null);
            }
            $mptszekcio = \mkw\store::getEm()->getRepository(MPTSzekcio::class)->find($this->params->getIntRequestParam('mpt_szekcio3', 0));
            if ($mptszekcio) {
                $obj->setMptSzekcio3($mptszekcio);
            } else {
                $obj->setMptSzekcio3(null);
            }
            $mpttagozat = \mkw\store::getEm()->getRepository(MPTTagozat::class)->find($this->params->getIntRequestParam('mpt_tagozat', 0));
            if ($mpttagozat) {
                $obj->setMptTagozat($mpttagozat);
            } else {
                $obj->setMptTagozat(null);
            }
            $mpttagsagforma = \mkw\store::getEm()->getRepository(MPTTagsagforma::class)->find($this->params->getIntRequestParam('mpt_tagsagforma', 0));
            if ($mpttagsagforma) {
                $obj->setMptTagsagforma($mpttagsagforma);
            } else {
                $obj->setMptTagsagforma(null);
            }

            $fizmod = \mkw\store::getEm()->getRepository(Fizmod::class)->find($this->params->getIntRequestParam('fizmod', 0));
            if ($fizmod) {
                $obj->setFizmod($fizmod);
            } else {
                $obj->setFizmod(null);
            }
            $uk = \mkw\store::getEm()->getRepository(Uzletkoto::class)->find($this->params->getIntRequestParam('uzletkoto', 0));
            if ($uk) {
                $obj->setUzletkoto($uk);
            } else {
                $obj->removeUzletkoto();
            }
            $valutanem = \mkw\store::getEm()->getRepository(Valutanem::class)->find($this->params->getIntRequestParam('valutanem', 0));
            if ($valutanem) {
                $obj->setValutanem($valutanem);
            }
            $szallmod = \mkw\store::getEm()->getRepository(Szallitasimod::class)->find($this->params->getIntRequestParam('szallitasimod', 0));
            if ($szallmod) {
                $obj->setSzallitasimod($szallmod);
            } else {
                $obj->setSzallitasimod(null);
            }
            $orszag = \mkw\store::getEm()->getRepository(Orszag::class)->find($this->params->getIntRequestParam('orszag', 0));
            if ($orszag) {
                $obj->setOrszag($orszag);
            } else {
                $obj->setOrszag(null);
            }
            $szallorszag = \mkw\store::getEm()->getRepository(Orszag::class)->find($this->params->getIntRequestParam('szallorszag', 0));
            if ($szallorszag) {
                $obj->setSzallorszag($szallorszag);
            } else {
                $obj->setSzallorszag(null);
            }
            $partnertipus = \mkw\store::getEm()->getRepository(Partnertipus::class)->find($this->params->getIntRequestParam('partnertipus', 0));
            if ($partnertipus) {
                $obj->setPartnertipus($partnertipus);
            } else {
                $obj->setPartnertipus(null);
            }

            $obj->removeAllCimke();
            $cimkekpar = $this->params->getArrayRequestParam('cimkek');
            foreach ($cimkekpar as $cimkekod) {
                $cimke = $this->getEm()->getRepository(Partnercimketorzs::class)->find($cimkekod);
                if ($cimke) {
                    $obj->addCimke($cimke);
                }
            }

            $dokids = $this->params->getArrayRequestParam('dokid');
            foreach ($dokids as $dokid) {
                if (($this->params->getStringRequestParam('dokurl_' . $dokid, '') !== '') ||
                    ($this->params->getStringRequestParam('dokpath_' . $dokid, '') !== '')) {
                    $dokoper = $this->params->getStringRequestParam('dokoper_' . $dokid);
                    if ($dokoper === 'add') {
                        $dok = new \Entities\PartnerDok();
                        $obj->addPartnerDok($dok);
                        $dok->setUrl($this->params->getStringRequestParam('dokurl_' . $dokid));
                        $dok->setPath($this->params->getStringRequestParam('dokpath_' . $dokid));
                        $dok->setLeiras($this->params->getStringRequestParam('dokleiras_' . $dokid));
                        $this->getEm()->persist($dok);
                    } elseif ($dokoper === 'edit') {
                        $dok = \mkw\store::getEm()->getRepository(PartnerDok::class)->find($dokid);
                        if ($dok) {
                            $dok->setUrl($this->params->getStringRequestParam('dokurl_' . $dokid));
                            $dok->setPath($this->params->getStringRequestParam('dokpath_' . $dokid));
                            $dok->setLeiras($this->params->getStringRequestParam('dokleiras_' . $dokid));
                            $this->getEm()->persist($dok);
                        }
                    }
                }
            }
        }

        if ($subject === 'adataim' || $subject === 'pubreg' || $subject === 'minden') {
            $obj->setVezeteknev($this->params->getStringRequestParam('vezeteknev'));
            $obj->setKeresztnev($this->params->getStringRequestParam('keresztnev'));
            if (\mkw\store::isMindentkapni() && $subject !== 'minden') {
                $obj->setNev($this->params->getStringRequestParam('vezeteknev') . ' ' . $this->params->getStringRequestParam('keresztnev'));
            }
            $obj->setEmail($this->params->getStringRequestParam('email'));
            if (\mkw\store::isMindentkapni()) {
                $telkorzet = $this->params->getStringRequestParam('telkorzet');
                $telszam = preg_replace('/[^0-9+]/', '', $this->params->getStringRequestParam('telszam'));
                $obj->setTelkorzet($telkorzet);
                $obj->setTelszam($telszam);
                $obj->setTelefon('+36' . $telkorzet . $telszam);
            } else {
                $obj->setTelefon($this->params->getStringRequestParam('telefon'));
            }
            $obj->setAkcioshirlevelkell($this->params->getBoolRequestParam('akcioshirlevelkell'));
            $obj->setUjdonsaghirlevelkell($this->params->getBoolRequestParam('ujdonsaghirlevelkell'));
            $obj->setMunkahelyneve($this->params->getStringRequestParam('munkahelyneve'));
            $obj->setFoglalkozas($this->params->getStringRequestParam('foglalkozas'));
        }

        if ($subject === 'bankiadatok' || $subject === 'minden') {
            $obj->setBanknev($this->params->getStringRequestParam('banknev'));
            $obj->setBankcim($this->params->getStringRequestParam('bankcim'));
            $obj->setIban($this->params->getStringRequestParam('iban'));
            $obj->setSwift($this->params->getStringRequestParam('swift'));
        }

        if ($subject === 'szamlaadatok' || $subject === 'pubreg' || $subject === 'minden') {
            $obj->setNev($this->params->getStringRequestParam('nev'));
            $obj->setAdoszam(substr($this->params->getStringRequestParam('adoszam'), 0, 13));
            $obj->setCsoportosadoszam($this->params->getStringRequestParam('csoportosadoszam'));
            $obj->setIrszam($this->params->getStringRequestParam('irszam'));
            $obj->setVaros($this->params->getStringRequestParam('varos'));
            $obj->setUtca($this->params->getStringRequestParam('utca'));
            $obj->setHazszam($this->params->getStringRequestParam('hazszam'));
            $orszag = \mkw\store::getEm()->getRepository(Orszag::class)->find($this->params->getIntRequestParam('orszag', 0));
            if ($orszag) {
                $obj->setOrszag($orszag);
            }
        }

        if ($subject === 'szallitasiadatok' || $subject === 'minden') {
            $obj->setSzallnev($this->params->getStringRequestParam('szallnev'));
            $obj->setSzallirszam($this->params->getStringRequestParam('szallirszam'));
            $obj->setSzallvaros($this->params->getStringRequestParam('szallvaros'));
            $obj->setSzallutca($this->params->getStringRequestParam('szallutca'));
            $obj->setSzallhazszam($this->params->getStringRequestParam('szallhazszam'));
            $orszag = \mkw\store::getEm()->getRepository(Orszag::class)->find($this->params->getIntRequestParam('szallorszag', 0));
            if ($orszag) {
                $obj->setSzallorszag($orszag);
            }
        }

        if ($subject === 'jelszo') {
            $obj->setJelszo($this->params->getStringRequestParam('jelszo1'));
        }

        if ($subject === 'discounts' || $subject === 'minden') {
            $kdids = $this->params->getArrayRequestParam('kedvezmenyid');
            foreach ($kdids as $kdid) {
                $oper = $this->params->getStringRequestParam('kedvezmenyoper_' . $kdid);
                $termekcsoport = $this->getEm()->getRepository(Termekcsoport::class)->find(
                    $this->params->getIntRequestParam('kedvezmenytermekcsoport_' . $kdid)
                );
                if ($termekcsoport) {
                    if ($oper === 'add') {
                        $kedv = new \Entities\PartnerTermekcsoportKedvezmeny();
                        $kedv->setPartner($obj);
                        $kedv->setTermekcsoport($termekcsoport);
                        $kedv->setKedvezmeny($this->params->getNumRequestParam('kedvezmeny_' . $kdid));
                        $this->getEm()->persist($kedv);
                    } elseif ($oper === 'edit') {
                        $kedv = $this->getEm()->getRepository(PartnerTermekcsoportKedvezmeny::class)->find($kdid);
                        if ($kedv) {
                            $kedv->setPartner($obj);
                            $kedv->setTermekcsoport($termekcsoport);
                            $kedv->setKedvezmeny($this->params->getNumRequestParam('kedvezmeny_' . $kdid));
                            $this->getEm()->persist($kedv);
                        }
                    }
                }
            }
            $kdids = $this->params->getArrayRequestParam('termekkedvezmenyid');
            foreach ($kdids as $kdid) {
                $oper = $this->params->getStringRequestParam('termekkedvezmenyoper_' . $kdid);
                $termek = $this->getEm()->getRepository(Termek::class)->find($this->params->getIntRequestParam('termekkedvezmenytermek_' . $kdid));
                if ($termek) {
                    if ($oper === 'add') {
                        $kedv = new \Entities\PartnerTermekKedvezmeny();
                        $kedv->setPartner($obj);
                        $kedv->setTermek($termek);
                        $kedv->setKedvezmeny($this->params->getNumRequestParam('termekkedvezmeny_' . $kdid));
                        $this->getEm()->persist($kedv);
                    } elseif ($oper === 'edit') {
                        $kedv = $this->getEm()->getRepository(PartnerTermekKedvezmeny::class)->find($kdid);
                        if ($kedv) {
                            $kedv->setPartner($obj);
                            $kedv->setTermek($termek);
                            $kedv->setKedvezmeny($this->params->getNumRequestParam('termekkedvezmeny_' . $kdid));
                            $this->getEm()->persist($kedv);
                        }
                    }
                }
            }
        }

        if ($subject === 'registration') {
            $obj->setVezeteknev($this->params->getStringRequestParam('vezeteknev'));
            $obj->setKeresztnev($this->params->getStringRequestParam('keresztnev'));
            if (\mkw\store::isMindentkapni()) {
                $obj->setNev($this->params->getStringRequestParam('vezeteknev') . ' ' . $this->params->getStringRequestParam('keresztnev'));
            }
            $email = $this->params->getStringRequestParam('kapcsemail');
            if ($email) {
                $obj->setEmail($email);
            } else {
                $obj->setEmail($this->params->getStringRequestParam('email'));
            }
            $obj->setJelszo($this->params->getStringRequestParam('jelszo1'));
            $obj->setVendeg(false);
            $obj->setSessionid(\mkw\session::getId());
            $obj->setIp($_SERVER['REMOTE_ADDR']);
            $obj->setReferrer(\mkw\store::getMainSession()->referrer);
            $obj->setAkcioshirlevelkell($this->params->getBoolRequestParam('akcioshirlevelkell'));
            $obj->setUjdonsaghirlevelkell($this->params->getBoolRequestParam('ujdonsaghirlevelkell'));
            $obj->setBizonylatnyelv(\mkw\store::getWebshopLongLocale());
            $fizmod = \mkw\store::getEm()->getRepository(Fizmod::class)->find($this->params->getIntRequestParam('fizetesimod', 0));
            if ($fizmod) {
                $obj->setFizmod($fizmod);
            }
            $szallmod = \mkw\store::getEm()->getRepository(Szallitasimod::class)->find($this->params->getIntRequestParam('szallitasimod', 0));
            if ($szallmod) {
                $obj->setSzallitasimod($szallmod);
            }
        }

        if (!$obj->getVatstatus()) {
            $obj->calcVatstatus();
        }
        return $obj;
    }

    public function getlistbody()
    {
        $view = $this->createView('partnerlista_tbody.tpl');

        $filter = new \mkwhelpers\FilterDescriptor();
        if (!is_null($this->params->getRequestParam('nevfilter', null))) {
            $fv = $this->params->getStringRequestParam('nevfilter');
            $filter->addFilter(['nev', 'keresztnev', 'vezeteknev', 'szallnev'], 'LIKE', '%' . $fv . '%');
        }
        $f = $this->params->getStringRequestParam('emailfilter');
        if ($f) {
            $filter->addFilter('email', 'LIKE', '%' . $f . '%');
        }
        $f = $this->params->getStringRequestParam('szallitasiirszamfilter');
        if ($f) {
            $filter->addFilter('szallirszam', 'LIKE', '%' . $f . '%');
        }
        $f = $this->params->getStringRequestParam('szallitasivarosfilter');
        if ($f) {
            $filter->addFilter('szallvaros', 'LIKE', '%' . $f . '%');
        }
        $f = $this->params->getStringRequestParam('szallitasiutcafilter');
        if ($f) {
            $filter->addFilter('szallutca', 'LIKE', '%' . $f . '%');
        }
        $f = $this->params->getStringRequestParam('szamlazasiirszamfilter');
        if ($f) {
            $filter->addFilter('irszam', 'LIKE', '%' . $f . '%');
        }
        $f = $this->params->getStringRequestParam('szamlazasivarosfilter');
        if ($f) {
            $filter->addFilter('varos', 'LIKE', '%' . $f . '%');
        }
        $f = $this->params->getStringRequestParam('szamlazasiutcafilter');
        if ($f) {
            $filter->addFilter('utca', 'LIKE', '%' . $f . '%');
        }
        $f = $this->params->getNumRequestParam('beszallitofilter', 9);
        if ($f != 9) {
            $filter->addFilter('szallito', '=', $f);
        }
        if (!is_null($this->params->getRequestParam('partnertipusfilter', null))) {
            $filter->addFilter('partnertipus', '=', $this->params->getIntRequestParam('partnertipusfilter'));
        }
        if (!is_null($this->params->getRequestParam('orszagfilter', null))) {
            $filter->addFilter('orszag', '=', $this->params->getIntRequestParam('orszagfilter'));
        }
        if (!is_null($this->params->getRequestParam('szallorszagfilter', null))) {
            $filter->addFilter('szallorszag', '=', $this->params->getIntRequestParam('szallorszagfilter'));
        }
        $f = $this->params->getNumRequestParam('inaktivfilter', 9);
        if ($f != 9) {
            $filter->addFilter('inaktiv', '=', $f);
        }
        if (!is_null($this->params->getRequestParam('cimkefilter', null))) {
            $fv = $this->params->getArrayRequestParam('cimkefilter');
            $cimkekodok = implode(',', $fv);
            if ($cimkekodok) {
                $filter->addJoin('INNER JOIN _xx.cimkek c WITH (c.id IN (' . $cimkekodok . '))');
            }
        }
        switch ($this->params->getNumRequestParam('mptngyreszvetelfilter', 9)) {
            case 1:
                $filter->addFilter('mptngynapreszvetel1', '=', true);
                break;
            case 2:
                $filter->addFilter('mptngyvipvacsora', '=', true);
                break;
            case 3:
                $filter->addFilter('mptngynapreszvetel2', '=', true);
                break;
            case 4:
                $filter->addFilter('mptngybankett', '=', true);
                break;
            case 5:
                $filter->addFilter('mptngynapreszvetel3', '=', true);
                break;
            case 6:
                $filter->addFilter('mptngynemveszreszt', '=', true);
                break;
            case 7:
                $filter->addFilter('mptngynapreszvetel1', '=', false);
                $filter->addFilter('mptngyvipvacsora', '=', false);
                $filter->addFilter('mptngynapreszvetel2', '=', false);
                $filter->addFilter('mptngybankett', '=', false);
                $filter->addFilter('mptngynapreszvetel3', '=', false);
                $filter->addFilter('mptngynemveszreszt', '=', false);
                break;
        }
        switch ($this->params->getNumRequestParam('mptngydiakfilter', 9)) {
            case 1:
                $filter->addFilter('mptngydiak', '=', true);
                break;
            case 2:
                $filter->addFilter('mptngynyugdijas', '=', true);
                break;
            case 3:
                $filter->addSql('_xx.mptngydiak=1 OR _xx.mptngynyugdijas=1');
                break;
            case 4:
                $filter->addFilter('mptngydiak', '=', false);
                $filter->addFilter('mptngynyugdijas', '=', false);
                break;
        }
        $f = $this->params->getStringRequestParam('munkahelynevfilter');
        if ($f) {
            $filter->addFilter('mpt_munkahelynev', 'LIKE', '%' . $f . '%');
        }
        $f = $this->params->getStringRequestParam('szlanevfilter');
        if ($f) {
            $filter->addFilter('szlanev', 'LIKE', '%' . $f . '%');
        }
        $this->initPager($this->getRepo()->getCount($filter));

        $egyedek = $this->getRepo()->getWithJoins(
            $filter,
            $this->getOrderArray(),
            $this->getPager()->getOffset(),
            $this->getPager()->getElemPerPage()
        );

        echo json_encode($this->loadDataToView($egyedek, 'partnerlista', $view));
    }

    public function viewlist()
    {
        $view = $this->createView('partnerlista.tpl');

        $view->setVar('pagetitle', t('Partnerek'));
        $view->setVar('orderselect', $this->getRepo()->getOrdersForTpl());
        $view->setVar('batchesselect', $this->getRepo()->getBatchesForTpl());
        $tcc = new partnercimkekatController();
        $view->setVar('cimkekat', $tcc->getWithCimkek(null));
        $orszag = new orszagController();
        $view->setVar('orszaglist', $orszag->getSelectList(0, true));
        $view->setVar('szallorszaglist', $orszag->getSelectList(0, true));
        $partnertipus = new partnertipusController();
        $view->setVar('partnertipuslist', $partnertipus->getSelectList(0));
        $arsav = new arsavController();
        $view->setVar('arsavlist', $arsav->getSelectList());
        $tcs = new termekcsoportController();
        $view->setVar('tcsktermekcsoportlist', $tcs->getSelectList());
        $emailtpl = new emailtemplateController();
        $view->setVar('emailsablonlist', $emailtpl->getSelectList());
        $ec = new mptngyegyetemController();
        $view->setVar('egyetemlist', $ec->getSelectList());
        $kc = new mptngykarController();
        $view->setVar('karlist', $kc->getSelectList());

        $view->printTemplateResult();
    }

    public function _getkarb($tplname)
    {
        $id = $this->params->getRequestParam('id', 0);
        $oper = $this->params->getRequestParam('oper', '');
        $view = $this->createView($tplname);

        $view->setVar('pagetitle', t('Partner'));
        $view->setVar('oper', $oper);

        /** @var Partner $partner */
        $partner = $this->getRepo()->findWithJoins($id);
        $view->setVar('szamlatipuslist', $this->getRepo()->getSzamlatipusList(($partner ? $partner->getSzamlatipus() : 0)));
        $view->setVar('vatstatuslist', $this->getRepo()->getVatstatusList(($partner ? $partner->getVatstatus() : 0)));
        // loadVars utan nem abc sorrendben adja vissza
        $tcc = new partnercimkekatController();
        $cimkek = $partner ? $partner->getCimkek() : null;
        $view->setVar('cimkekat', $tcc->getWithCimkek($cimkek));
        $fizmod = new fizmodController();
        $view->setVar('fizmodlist', $fizmod->getSelectList($partner?->getFizmodId()));
        $uk = new uzletkotoController();
        $view->setVar('uzletkotolist', $uk->getSelectList($partner?->getUzletkotoId()));
        $valutanem = new valutanemController();
        $view->setVar('valutanemlist', $valutanem->getSelectList($partner?->getValutanemId()));
        $arsav = new arsavController();
        $view->setVar('arsavlist', $arsav->getSelectList($partner?->getArsav()?->getId()));

        $szallmod = new szallitasimodController();
        $view->setVar('szallitasimodlist', $szallmod->getSelectList($partner?->getSzallitasimodId()));
        $orszag = new orszagController();
        $view->setVar('orszaglist', $orszag->getSelectList($partner?->getOrszagId(), true));
        $view->setVar('szallorszaglist', $orszag->getSelectList($partner?->getSzallorszagId(), true));
        $partnertipus = new partnertipusController();
        $view->setVar('partnertipuslist', $partnertipus->getSelectList($partner?->getPartnertipusId()));
        $mpttagsagforma = new mpttagsagformaController();
        $view->setVar('mpttagsagformalist', $mpttagsagforma->getSelectList($partner?->getMptTagsagformaId()));
        $mptszekcio = new mptszekcioController();
        $view->setVar('mptszekcio1list', $mptszekcio->getSelectList($partner?->getMptSzekcio1Id()));
        $view->setVar('mptszekcio2list', $mptszekcio->getSelectList($partner?->getMptSzekcio2Id()));
        $view->setVar('mptszekcio3list', $mptszekcio->getSelectList($partner?->getMptSzekcio3Id()));
        $mpttagozat = new mpttagozatController();
        $view->setVar('mpttagozatlist', $mpttagozat->getSelectList($partner?->getMptTagozatId()));

        $view->setVar('bizonylatnyelvlist', \mkw\store::getLocaleSelectList($partner?->getBizonylatnyelv()));

        $telkorzetc = new korzetszamController();
        $view->setVar('telkorzetlist', $telkorzetc->getSelectList($partner?->getTelkorzet()));

        $mptngyszkc = new mptngyszerepkorController();
        $view->setVar('mptngyszerepkorlist', $mptngyszkc->getSelectList($partner?->getMptngyszerepkorId()));
        $fizmod = new fizmodController();
        $view->setVar('mptngybefizetesmodlist', $fizmod->getSelectList($partner?->getMptngybefizetesmod()?->getId()));

        $ec = new mptngyegyetemController();
        $view->setVar('egyetemlist', $ec->getSelectList($partner?->getMPTNGYEgyetemId()));
        $kc = new mptngykarController();
        $view->setVar('karlist', $kc->getSelectList($partner?->getMPTNGYKarId()));

        $view->setVar('partner', $this->loadVars($partner, true));
        $view->printTemplateResult();
    }

}
