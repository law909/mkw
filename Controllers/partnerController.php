<?php

namespace Controllers;

use Entities\Emailtemplate;
use Entities\MPTNGYSzerepkor;
use Entities\MPTSzekcio;
use Entities\MPTTagozat;
use Entities\MPTTagsagforma;
use Entities\Partner;
use Entities\PartnerTermekcsoportKedvezmeny;
use mkwhelpers\FilterDescriptor;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Spreadsheet;

class partnerController extends \mkwhelpers\MattableController
{

    public function __construct($params)
    {
        $this->setEntityName('Entities\Partner');
        $this->setKarbFormTplName('partnerkarbform.tpl');
        $this->setKarbTplName('partnerkarb.tpl');
        $this->setListBodyRowTplName('partnerlista_tbody_tr.tpl');
        $this->setListBodyRowVarName('_partner');
        parent::__construct($params);
    }

    protected function loadVars($t, $forKarb = false)
    {
        $kedvCtrl = new \Controllers\partnertermekcsoportkedvezmenyController($this->params);
        $termekkedvCtrl = new \Controllers\partnertermekkedvezmenyController($this->params);
        $mijszokCtrl = new \Controllers\partnermijszoklevelController($this->params);
        $mijszpuneCtrl = new \Controllers\partnermijszpuneController($this->params);
        $mijszoralatogatasCtrl = new \Controllers\partnermijszoralatogatasController($this->params);
        $mijsztanitasCtrl = new \Controllers\partnermijsztanitasController($this->params);
        $dokCtrl = new partnerdokController($this->params);
        $mptfolyoszamlaCtrl = new mptfolyoszamlaController($this->params);
        $x = [];
        if (!$t) {
            $t = new \Entities\Partner();
            $this->getEm()->detach($t);
        }
        $x['id'] = $t->getId();
        $x['nev'] = $t->getNev();
        $x['vezeteknev'] = $t->getVezeteknev();
        $x['keresztnev'] = $t->getKeresztnev();
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
        $x['termekarazonosito'] = $t->getTermekarazonosito();
        $x['szallitasimod'] = $t->getSzallitasimod();
        $x['szallitasimodnev'] = $t->getSzallitasimodNev();
        $x['partnertipus'] = $t->getPartnertipus();
        $x['partnertipusnev'] = $t->getPartnertipusNev();
        $x['bizonylatnyelv'] = $t->getBizonylatnyelv();
        $x['ezuzletkoto'] = $t->getEzuzletkoto();
        $x['mijszmiotajogazik'] = $t->getMijszmiotajogazik();
        $x['mijszmiotatanit'] = $t->getMijszmiotatanit();
        $x['mijszmembershipbesideshu'] = $t->getMijszmembershipbesideshu();
        $x['mijszbusiness'] = $t->getMijszbusiness();
        $x['mijszexporttiltva'] = $t->getMijszexporttiltva();
        $x['ktdatvallal'] = $t->getKtdatvallal();
        $x['ktdatalany'] = $t->getKtdatalany();
        $x['ktdszerzszam'] = $t->getKtdszerzszam();
        $x['munkahelyneve'] = $t->getMunkahelyneve();
        $x['foglalkozas'] = $t->getFoglalkozas();
        $x['minicrmprojectid'] = $t->getMinicrmprojectid();
        $x['minicrmcontactid'] = $t->getMinicrmcontactid();
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
        $x['xnemrendelhet'] = $t->isXNemrendelhet();
        $x['nemrendelhet'] = $t->isNemrendelhet();
        $x['nemrendelhet2'] = $t->isNemrendelhet2();
        $x['nemrendelhet3'] = $t->isNemrendelhet3();
        $x['nemrendelhet4'] = $t->isNemrendelhet4();
        $x['nemrendelhet5'] = $t->isNemrendelhet5();
        if ($t->getSzamlatipus() > 0) {
            $afa = $this->getRepo('Entities\Afa')->find(\mkw\store::getParameter(\mkw\consts::NullasAfa));
            if ($afa) {
                $x['afa'] = $afa->getId();
                $x['afakulcs'] = $afa->getErtek();
            }
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

        if (\mkw\store::isMIJSZ()) {
            $okl = [];
            foreach ($t->getMijszoklevelek() as $tar) {
                $okl[] = $mijszokCtrl->loadVars($tar, true);
            }
            $x['mijszoklevelek'] = $okl;
            $pune = [];
            foreach ($t->getMijszpune() as $tar) {
                $pune[] = $mijszpuneCtrl->loadVars($tar, true);
            }
            $x['mijszpune'] = $pune;
            $oralatogatas = [];
            foreach ($t->getMijszoralatogatas() as $tar) {
                $oralatogatas[] = $mijszoralatogatasCtrl->loadVars($tar, true);
            }
            $x['mijszoralatogatas'] = $oralatogatas;
            $tanitas = [];
            foreach ($t->getMijsztanitas() as $tar) {
                $tanitas[] = $mijsztanitasCtrl->loadVars($tar, true);
            }
            $x['mijsztanitas'] = $tanitas;
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
            $obj->setTermekarazonosito($this->params->getStringRequestParam('termekarazonosito'));
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
            if ($this->params->getIntRequestParam('minicrmprojectid')) {
                $obj->setMinicrmprojectid($this->params->getIntRequestParam('minicrmprojectid'));
            }
            if ($this->params->getIntRequestParam('minicrmcontactid')) {
                $obj->setMinicrmcontactid($this->params->getIntRequestParam('minicrmcontactid'));
            }
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
            $obj->setNemrendelhet($this->params->getBoolRequestParam('nemrendelhet'));
            $obj->setNemrendelhet2($this->params->getBoolRequestParam('nemrendelhet2'));
            $obj->setNemrendelhet3($this->params->getBoolRequestParam('nemrendelhet3'));
            $obj->setNemrendelhet4($this->params->getBoolRequestParam('nemrendelhet4'));
            $obj->setNemrendelhet5($this->params->getBoolRequestParam('nemrendelhet5'));
            $fizmod = \mkw\store::getEm()->getRepository('Entities\Fizmod')->find($this->params->getIntRequestParam('mptngybefizetesmod', 0));
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

            $fizmod = \mkw\store::getEm()->getRepository('Entities\Fizmod')->find($this->params->getIntRequestParam('fizmod', 0));
            if ($fizmod) {
                $obj->setFizmod($fizmod);
            } else {
                $obj->setFizmod(null);
            }
            $uk = \mkw\store::getEm()->getRepository('Entities\Uzletkoto')->find($this->params->getIntRequestParam('uzletkoto', 0));
            if ($uk) {
                $obj->setUzletkoto($uk);
            } else {
                $obj->removeUzletkoto();
            }
            $valutanem = \mkw\store::getEm()->getRepository('Entities\Valutanem')->find($this->params->getIntRequestParam('valutanem', 0));
            if ($valutanem) {
                $obj->setValutanem($valutanem);
            }
            $szallmod = \mkw\store::getEm()->getRepository('Entities\Szallitasimod')->find($this->params->getIntRequestParam('szallitasimod', 0));
            if ($szallmod) {
                $obj->setSzallitasimod($szallmod);
            } else {
                $obj->setSzallitasimod(null);
            }
            $orszag = \mkw\store::getEm()->getRepository('Entities\Orszag')->find($this->params->getIntRequestParam('orszag', 0));
            if ($orszag) {
                $obj->setOrszag($orszag);
            } else {
                $obj->setOrszag(null);
            }
            $partnertipus = \mkw\store::getEm()->getRepository('Entities\Partnertipus')->find($this->params->getIntRequestParam('partnertipus', 0));
            if ($partnertipus) {
                $obj->setPartnertipus($partnertipus);
            } else {
                $obj->setPartnertipus(null);
            }

            if (\mkw\store::isMIJSZ()) {
                $obj->setMijszmiotajogazik($this->params->getIntRequestParam('mijszmiotajogazik'));
                $obj->setMijszmiotatanit($this->params->getIntRequestParam('mijszmiotatanit'));
                $obj->setMijszmembershipbesideshu($this->params->getStringRequestParam('mijszmembershipbesideshu'));
                $obj->setMijszbusiness($this->params->getStringRequestParam('mijszbusiness'));
                $obj->setMijszexporttiltva($this->params->getBoolRequestParam('mijszexporttiltva'));
            }

            $obj->removeAllCimke();
            $cimkekpar = $this->params->getArrayRequestParam('cimkek');
            foreach ($cimkekpar as $cimkekod) {
                $cimke = $this->getEm()->getRepository('Entities\Partnercimketorzs')->find($cimkekod);
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
                        $dok = \mkw\store::getEm()->getRepository('Entities\PartnerDok')->find($dokid);
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
            if (\mkw\store::isMIJSZ()) {
                $obj->setMijszmiotajogazik($this->params->getIntRequestParam('mijszmiotajogazik'));
                $obj->setMijszmiotatanit($this->params->getIntRequestParam('mijszmiotatanit'));
                $obj->setMijszmembershipbesideshu($this->params->getStringRequestParam('mijszmembershipbesideshu'));
                $obj->setMijszbusiness($this->params->getStringRequestParam('mijszbusiness'));
                $obj->setHonlap($this->params->getStringRequestParam('honlap'));
            }
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
            $orszag = \mkw\store::getEm()->getRepository('Entities\Orszag')->find($this->params->getIntRequestParam('orszag', 0));
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
        }

        if ($subject === 'jelszo') {
            $obj->setJelszo($this->params->getStringRequestParam('jelszo1'));
        }

        if ($subject === 'discounts' || $subject === 'minden') {
            $kdids = $this->params->getArrayRequestParam('kedvezmenyid');
            foreach ($kdids as $kdid) {
                $oper = $this->params->getStringRequestParam('kedvezmenyoper_' . $kdid);
                $termekcsoport = $this->getEm()->getRepository('Entities\Termekcsoport')->find(
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
                        $kedv = $this->getEm()->getRepository('Entities\PartnerTermekcsoportKedvezmeny')->find($kdid);
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
                $termek = $this->getEm()->getRepository('Entities\Termek')->find($this->params->getIntRequestParam('termekkedvezmenytermek_' . $kdid));
                if ($termek) {
                    if ($oper === 'add') {
                        $kedv = new \Entities\PartnerTermekKedvezmeny();
                        $kedv->setPartner($obj);
                        $kedv->setTermek($termek);
                        $kedv->setKedvezmeny($this->params->getNumRequestParam('termekkedvezmeny_' . $kdid));
                        $this->getEm()->persist($kedv);
                    } elseif ($oper === 'edit') {
                        $kedv = $this->getEm()->getRepository('Entities\PartnerTermekKedvezmeny')->find($kdid);
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
            $obj->setSessionid(\Zend_Session::getId());
            $obj->setIp($_SERVER['REMOTE_ADDR']);
            $obj->setReferrer(\mkw\store::getMainSession()->referrer);
            $obj->setAkcioshirlevelkell($this->params->getBoolRequestParam('akcioshirlevelkell'));
            $obj->setUjdonsaghirlevelkell($this->params->getBoolRequestParam('ujdonsaghirlevelkell'));
            $fizmod = \mkw\store::getEm()->getRepository('Entities\Fizmod')->find($this->params->getIntRequestParam('fizetesimod', 0));
            if ($fizmod) {
                $obj->setFizmod($fizmod);
            }
            $szallmod = \mkw\store::getEm()->getRepository('Entities\Szallitasimod')->find($this->params->getIntRequestParam('szallitasimod', 0));
            if ($szallmod) {
                $obj->setSzallitasimod($szallmod);
            }
        }

        if (!$obj->getVatstatus()) {
            $obj->calcVatstatus();
        }
        return $obj;
    }

    public function saveRegistrationData($vendeg = false)
    {
        $email = $this->params->getStringRequestParam('kapcsemail');
        if (!$email) {
            $email = $this->params->getStringRequestParam('email');
        }
        $ps = $this->getRepo()->findVendegByEmail($email);
        if (count($ps) > 0) {
            $t = $ps[0];
        } else {
            $t = new \Entities\Partner();
        }
        $t = $this->setFields($t, null, 'registration');
        $t->setVendeg($vendeg);
        $this->getEm()->persist($t);
        $this->getEm()->flush();
        return $t;
    }

    public function checkApiRegData($data)
    {
        $ret = [];
        if (!$data['email']) {
            $ret[] = 'Empty field: email';
        }
        if (!$data['password']) {
            $ret[] = 'Empty field: password';
        }
        if (!$data['nev']) {
            $ret[] = 'Empty field: nev';
        }
        if (!$data['irszam']) {
            $ret[] = 'Empty field: irszam';
        }
        if (!$data['varos']) {
            $ret[] = 'Empty field: varos';
        }
        if (!$data['utca']) {
            $ret[] = 'Empty field: utca';
        }
        return $ret;
    }

    public function saveApiRegData($data, $consumer)
    {
        $p = $this->getRepo()->findOneBy(['email' => $data['email']]);
        if (!$p) {
            $p = new \Entities\Partner();
        }
        $p->setEmail($data['email']);
        $p->setJelszo($data['password']);
        $p->setVezeteknev($data['vezeteknev']);
        $p->setKeresztnev($data['keresztnev']);
        $p->setNev($data['nev']);
        $p->setIrszam($data['irszam']);
        $p->setVaros($data['varos']);
        $p->setUtca($data['utca']);
        $p->setHazszam($data['hazszam']);
        $p->setTelefon($data['telefon']);
        $p->setEmail($data['email']);
        $p->setAdoszam($data['adoszam']);
        $p->setEuadoszam($data['euadoszam']);
        $p->setSzallnev($data['szallnev']);
        $p->setSzallirszam($data['szallirszam']);
        $p->setSzallvaros($data['szallvaros']);
        $p->setSzallutca($data['szallutca']);
        $p->setSzallhazszam($data['szallhazszam']);
        $p->setVendeg((bool)$data['vendeg']);

        $p->setApireg(true);
        $p->setApiconsumer($consumer);

        $this->getEm()->persist($p);
        $this->getEm()->flush();
        return $p;
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
                $filter->addSql('_xx.mptngydiak=1 OR _xx.mptngynyugdíjas=1');
                break;
            case 4:
                $filter->addFilter('mptngydiak', '=', false);
                $filter->addFilter('mptngynyudijas', '=', false);
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
        $tcc = new partnercimkekatController($this->params);
        $view->setVar('cimkekat', $tcc->getWithCimkek(null));
        $orszag = new orszagController($this->params);
        $view->setVar('orszaglist', $orszag->getSelectList(0, true));
        $partnertipus = new partnertipusController($this->params);
        $view->setVar('partnertipuslist', $partnertipus->getSelectList(0));
        $arsav = new termekarController($this->params);
        $view->setVar('arsavlist', $arsav->getSelectList());
        $tcs = new termekcsoportController($this->params);
        $view->setVar('tcsktermekcsoportlist', $tcs->getSelectList());
        $emailtpl = new emailtemplateController($this->params);
        $view->setVar('emailsablonlist', $emailtpl->getSelectList());
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
        $tcc = new partnercimkekatController($this->params);
        $cimkek = $partner ? $partner->getCimkek() : null;
        $view->setVar('cimkekat', $tcc->getWithCimkek($cimkek));
        $fizmod = new fizmodController($this->params);
        $view->setVar('fizmodlist', $fizmod->getSelectList($partner?->getFizmodId()));
        $uk = new uzletkotoController($this->params);
        $view->setVar('uzletkotolist', $uk->getSelectList($partner?->getUzletkotoId()));
        $valutanem = new valutanemController($this->params);
        $view->setVar('valutanemlist', $valutanem->getSelectList($partner?->getValutanemId()));
        $termekar = new termekarController($this->params);
        $view->setVar('termekarazonositolist', $termekar->getSelectList($partner?->getTermekarazonosito()));
        $szallmod = new szallitasimodController($this->params);
        $view->setVar('szallitasimodlist', $szallmod->getSelectList($partner?->getSzallitasimodId()));
        $orszag = new orszagController($this->params);
        $view->setVar('orszaglist', $orszag->getSelectList($partner?->getOrszagId(), true));
        $partnertipus = new partnertipusController($this->params);
        $view->setVar('partnertipuslist', $partnertipus->getSelectList($partner?->getPartnertipusId()));
        $mpttagsagforma = new mpttagsagformaController($this->params);
        $view->setVar('mpttagsagformalist', $mpttagsagforma->getSelectList($partner?->getMptTagsagformaId()));
        $mptszekcio = new mptszekcioController($this->params);
        $view->setVar('mptszekcio1list', $mptszekcio->getSelectList($partner?->getMptSzekcio1Id()));
        $view->setVar('mptszekcio2list', $mptszekcio->getSelectList($partner?->getMptSzekcio2Id()));
        $view->setVar('mptszekcio3list', $mptszekcio->getSelectList($partner?->getMptSzekcio3Id()));
        $mpttagozat = new mpttagozatController($this->params);
        $view->setVar('mpttagozatlist', $mpttagozat->getSelectList($partner?->getMptTagozatId()));

        $view->setVar('bizonylatnyelvlist', \mkw\store::getLocaleSelectList($partner?->getBizonylatnyelv()));

        $telkorzetc = new korzetszamController($this->params);
        $view->setVar('telkorzetlist', $telkorzetc->getSelectList($partner?->getTelkorzet()));

        $mptngyszkc = new mptngyszerepkorController($this->params);
        $view->setVar('mptngyszerepkorlist', $mptngyszkc->getSelectList($partner?->getMptngyszerepkorId()));
        $fizmod = new fizmodController($this->params);
        $view->setVar('mptngybefizetesmodlist', $fizmod->getSelectList($partner?->getMptngybefizetesmod()?->getId()));

        $view->setVar('partner', $this->loadVars($partner, true));
        $view->printTemplateResult();
    }

    /**
     * @param $selid
     * @param FilterDescriptor | array $filter
     *
     * @return array
     */
    public function getSelectList($selid = null, $filter = [])
    {
        $f = new FilterDescriptor();
        $f->addFilter('inaktiv', '=', false);
        if ($filter) {
            $f->merge($filter);
        }
        $rec = $this->getRepo()->getAllForSelectList($f, ['nev' => 'ASC']);
        $res = [];
        foreach ($rec as $sor) {
            $res[] = [
                'id' => $sor['id'],
                'caption' => $sor['nev'] . ' ' . $sor['irszam'] . ' ' . $sor['varos'] . ' ' . $sor['utca'] . ' ' . $sor['hazszam'],
                'nevvaros' => $sor['nev'] . ' ' . $sor['varos'],
                'nev' => $sor['nev'],
                'cim' => $sor['irszam'] . ' ' . $sor['varos'] . ' ' . $sor['utca'] . ' ' . $sor['hazszam'],
                'email' => $sor['email'],
                'selected' => ($sor['id'] == $selid)
            ];
        }
        return $res;
    }

    public function getBizonylatfejSelectList()
    {
        $term = trim($this->params->getStringRequestParam('term'));
        $ret = [];
        if ($term) {
            $r = \mkw\store::getEm()->getRepository('Entities\Partner');
            $res = $r->getBizonylatfejLista($term);

            $afa = $this->getRepo('Entities\Afa')->find(\mkw\store::getParameter(\mkw\consts::NullasAfa));
            $partnerafa = $afa->getId();
            $partnerafakulcs = $afa->getErtek();

            foreach ($res as $r) {
                $x = [
                    'id' => $r['id'],
                    'value' => $r['nev'] . ' ' . \mkw\store::implodeCim($r['irszam'], $r['varos'], $r['utca'], $r['hazszam']) . ' (' . $r['email'] . ')'
                ];
                if ($r['szamlatipus'] > 0) {
                    $x['afa'] = $partnerafa;
                    $x['afakulcs'] = $partnerafakulcs;
                }
                $ret[] = $x;
            }
        }
        echo json_encode($ret);
    }

    public function getPartnerData()
    {
        $pid = $this->params->getIntRequestParam('partnerid');
        $email = $this->params->getStringRequestParam('email');
        if ($pid) {
            /** @var Partner $partner */
            $partner = $this->getRepo()->find($pid);
        } elseif ($email) {
            /** @var Partner $partner */
            $partner = $this->getRepo()->findOneBy(['email' => $email]);
        } else {
            /** @var Partner $partner */
            $partner = $this->getRepo()->getLoggedInUser();
        }

        $ret = [];
        if ($partner) {
            $ret = [
                'id' => $partner->getId(),
                'fizmod' => $partner->getFizmodId(),
                'fizhatido' => $partner->getFizhatido(),
                'nev' => $partner->getNev(),
                'vezeteknev' => $partner->getVezeteknev(),
                'keresztnev' => $partner->getKeresztnev(),
                'szlanev' => $partner->getSzlanev(),
                'irszam' => $partner->getIrszam(),
                'varos' => $partner->getVaros(),
                'utca' => $partner->getUtca(),
                'hazszam' => $partner->getHazszam(),
                'szallnev' => $partner->getSzallnev(),
                'szallirszam' => $partner->getSzallirszam(),
                'szallvaros' => $partner->getSzallvaros(),
                'szallutca' => $partner->getSzallutca(),
                'szallhazszam' => $partner->getSzallhazszam(),
                'adoszam' => $partner->getAdoszam(),
                'euadoszam' => $partner->getEuadoszam(),
                'thirdadoszam' => $partner->getThirdadoszam(),
                'telefon' => $partner->getTelefon(),
                'email' => $partner->getEmail(),
                'szallitasimod' => $partner->getSzallitasimodId(),
                'valutanem' => $partner->getValutanemId(),
                'uzletkoto' => $partner->getUzletkotoId(),
                'bizonylatnyelv' => $partner->getBizonylatnyelv(),
                'orszag' => $partner->getOrszagId(),
                'vatstatus' => $partner->getVatstatus(),
                'szamlatipus' => $partner->getSzamlatipus(),
                'szamlaegyeb' => $partner->getSzamlaegyeb(),
                'mptngybankszamlaszam' => $partner->getMptngybankszamlaszam(),
                'mptngycsoportosfizetes' => $partner->getMptngycsoportosfizetes(),
                'mptngykapcsolatnev' => $partner->getMptngykapcsolatnev(),
                'mpt_munkahelynev' => $partner->getMptMunkahelynev(),
                'mptngynemveszreszt' => $partner->isMptngynemveszreszt(),
                'mptngynapreszvetel1' => $partner->isMptngynapreszvetel1(),
                'mptngynapreszvetel2' => $partner->isMptngynapreszvetel2(),
                'mptngynapreszvetel3' => $partner->isMptngynapreszvetel3(),
                'mptngyvipvacsora' => $partner->isMptngyvipvacsora(),
                'mptngybankett' => $partner->isMptngybankett(),
                'mptnyugdijasdiak' => $partner->isMptngynyugdijas() ? t('Nyugdíjas') : ($partner->isMptngydiak() ? t('Diák') : ''),
                'mptngydiak' => $partner->isMptngydiak(),
                'mptngynyugdijas' => $partner->isMptngynyugdijas(),
                'mptngympttag' => $partner->isMptngympttag(),
                'mptngyszerepkor' => $partner->getMptngyszerepkorId(),
                'mpttag' => $partner->isMptngympttag() ? t('MPT tag') : t('nem MPT tag'),
            ];
            if ($partner->getSzamlatipus() > 0) {
                $afa = $this->getRepo('Entities\Afa')->find(\mkw\store::getParameter(\mkw\consts::NullasAfa));
                $ret['afa'] = $afa->getId();
                $ret['afakulcs'] = $afa->getErtek();
            }
        }
        echo json_encode($ret);
    }

    public function getSzallitoSelectList($selid)
    {
        $filter = new \mkwhelpers\FilterDescriptor();
        $filter->addFilter('szallito', '=', true);
        $rec = $this->getRepo()->getAll($filter, ['nev' => 'ASC']);
        $res = [];
        foreach ($rec as $sor) {
            $res[] = [
                'id' => $sor->getId(),
                'caption' => $sor->getNev(),
                'selected' => ($sor->getId() == $selid),
                'fizmod' => $sor->getFizmodId(),
                'fizhatido' => $sor->getFizhatido(),
                'irszam' => $sor->getIrszam(),
                'varos' => $sor->getVaros(),
                'utca' => $sor->getUtca(),
                'hazszam' => $sor->getHazszam()
            ];
        }
        return $res;
    }

    public function checkemail()
    {
        $email = $this->params->getStringRequestParam('email');

        $ret = [];
        $ret['hibas'] = !\Zend_Validate::is($email, 'EmailAddress');
        if (!$ret['hibas']) {
            if (!$this->params->getBoolRequestParam('dce')) {
                $ret['hibas'] = $this->getRepo()->countByEmail($email) > 0;
                if ($ret['hibas']) {
                    $ret['uzenet'] = t('Már létezik ez az emailcím.');
                }
            }
        } else {
            $ret['uzenet'] = t('Kérjük emailcímet adjon meg.');
        }
        echo json_encode($ret);
    }

    public function showPubRegistration()
    {
        $view = $this->getTemplateFactory()->createMainView('pubregistration.tpl');
        \mkw\store::fillTemplate($view);
        $view->printTemplateResult(true);
    }

    public function showPubRegistrationThx()
    {
        $view = $this->getTemplateFactory()->createMainView('pubregistrationthx.tpl');
        \mkw\store::fillTemplate($view);
        $view->printTemplateResult(true);
    }

    public function apiLogin($puser, $pass)
    {
        $ok = false;
        if ($puser instanceof \Entities\Partner) {
            $user = $puser;
            $ok = true;
        } else {
            $users = $this->getRepo()->findByUserPass($puser, $pass);
            if (count($users) > 0) {
                $user = $users[0];
                $ok = true;
            }
        }
        if ($ok && $user && !$user->getVendeg()) {
            return $user;
        }
        return false;
    }

    public function login($puser, $pass = null)
    {
        $ok = false;
        if ($puser instanceof \Entities\Partner) {
            $user = $puser;
            $ok = true;
        } else {
            /** @var \Entities\Partner $users */
            $users = $this->getRepo()->findByUserPass($puser, $pass);
            if (count($users) > 0) {
                $user = $users[0];
                $ok = true;
            }
        }
        if ($ok && $user) {
            if ($user->getVendeg()) {
                return false;
            }
            if (\mkw\store::isMultiShop()) {
                if ($user->getPartnertipus()) {
                    if (!$user->getPartnertipus()->getXBelephet()) {
                        return false;
                    }
                }
            }
            $kc = new kosarController($this->params);
            $kc->clear($user->getId()); // csak partner alapján
            $oldid = \Zend_Session::getId();
            \Zend_Session::regenerateId();
            \mkw\store::clearLoggedInUser();
            $user->setSessionid(\Zend_Session::getId());
            $user->setUtolsoklikk();
            $user->clearPasswordreminder();
            $this->getEm()->persist($user);
            \mkw\store::getMainSession()->pk = $user->getId();
            $mc = new mainController($this->params);
            $mc->setOrszag($user->getOrszagId());
            if (\mkw\store::isB2B()) {
                if ($user->getEzuzletkoto()) {
                    $uk = $this->getRepo('Entities\Uzletkoto')->find($user->getUzletkotoId());
                    if ($uk) {
                        $uk->setSessionid(\Zend_Session::getId());
                        $this->getEm()->persist($uk);
                        \mkw\store::getMainSession()->uk = $user->getUzletkotoId();
                        \mkw\store::getMainSession()->ukpartner = $user->getId();
                    }
                }
            }
            $this->getEm()->flush();
            $kc->replaceSessionIdAndAddPartner($oldid, $user);
            $kc->addSessionIdByPartner($user);
            return true;
        }
        return false;
    }

    public function logout()
    {
        $user = \mkw\store::getLoggedInUser();
        if ($user) {
            \mkw\store::clearLoggedInUser();
            $user->setSessionid('');
            $this->getEm()->persist($user);
            $this->getEm()->flush();
            $kc = new kosarController($this->params);
            $kc->removeSessionId(\Zend_Session::getId());
            \mkw\store::getMainSession()->pk = null;
            \mkw\store::getMainSession()->uk = null;
            \mkw\store::getMainSession()->ukpartner = null;
            \mkw\store::destroyMainSession();
        }
    }

    public function autologout()
    {
        $user = \mkw\store::getLoggedInUser();
        if ($user) {
            $ma = new \DateTime();
            $kul = $ma->diff($user->getUtolsoklikk());
            $kulonbseg = floor(
                ($kul->y * 365 * 24 * 60 * 60 + $kul->m * 30 * 24 * 60 * 60 + $kul->d * 24 * 60 * 60 + $kul->h * 60 * 60 + $kul->i * 60 + $kul->s) / 60
            );
            $perc = \mkw\store::getParameter(\mkw\consts::Autologoutmin, 10);
            if ($perc <= 0) {
                $perc = 10;
            }
            if ($kulonbseg >= $perc) {
                $this->logout();
                return true;
            }
        }
        return false;
    }

    public function setUtolsoKlikk()
    {
        $user = \mkw\store::getLoggedInUser();
        if ($user) {
            $user->setUtolsoKlikk();
            $this->getEm()->persist($user);
            $this->getEm()->flush();
        }
    }

    public function checkloggedin()
    {
        return $this->getRepo()->checkloggedin();
    }

    public function saveRegistration()
    {
        $hibas = false;
        $hibak = [];

        $vezeteknev = $this->params->getStringRequestParam('vezeteknev');
        $keresztnev = $this->params->getStringRequestParam('keresztnev');
        $email = $this->params->getStringRequestParam('email');
        $jelszo1 = $this->params->getStringRequestParam('jelszo1');

        $r = $this->checkPartnerData('adataim');
        $hibas = $hibas || $r['hibas'];
        $hibak = array_merge($hibak, $r['hibak']);

        $r = $this->checkPartnerData('jelszo');
        $hibas = $hibas || $r['hibas'];
        $hibak = array_merge($hibak, $r['hibak']);

        if (!$hibas) {
            $ps = $this->getRepo()->findVendegByEmail($email);
            if (count($ps) > 0) {
                $t = $ps[0];
            } else {
                $t = new \Entities\Partner();
            }
            $t = $this->setFields($t, 'add', 'registration');
            $this->getEm()->persist($t);
            $this->getEm()->flush();
            $this->login($email, $jelszo1);
            $emailtpl = $this->getEm()->getRepository('Entities\Emailtemplate')->findOneByNev('regisztracio');
            if ($emailtpl) {
                $tpldata = [
                    'keresztnev' => $keresztnev,
                    'vezeteknev' => $vezeteknev,
                    'fiokurl' => \mkw\store::getRouter()->generate('showaccount', true),
                    'url' => \mkw\store::getFullUrl()
                ];
                $subject = $this->getTemplateFactory()->createMainView('string:' . $emailtpl->getTargy());
                $subject->setVar('user', $tpldata);
                $body = $this->getTemplateFactory()->createMainView('string:' . $emailtpl->getHTMLSzoveg());
                $body->setVar('user', $tpldata);
                /*
                $mailer = \mkw\store::getMailer();
                $mailer->setTo($email);
                $mailer->setSubject($subject->getTemplateResult());
                $mailer->setMessage($body->getTemplateResult());
                $mailer->send();
                */
            }
            \Zend_Session::writeClose();
            Header('Location: ' . \mkw\store::getRouter()->generate('showaccount'));
        } else {
            $this->showRegistrationForm($vezeteknev, $keresztnev, $email, $hibak);
        }
    }

    public function showRegistrationForm($vezeteknev = '', $keresztnev = '', $email = '', $hibak = [])
    {
        $view = $this->getTemplateFactory()->createMainView('regisztracio.tpl');
        $view->setVar('pagetitle', t('Regisztráció') . ' - ' . \mkw\store::getParameter(\mkw\consts::Oldalcim));
        $view->setVar('vezeteknev', $vezeteknev);
        $view->setVar('keresztnev', $keresztnev);
        $view->setVar('email', $email);
        $view->setVar('hibak', $hibak);
        \mkw\store::fillTemplate($view);
        $view->printTemplateResult(true);
    }

    public function showLoginForm()
    {
        if ($this->checkloggedin()) {
            \Zend_Session::writeClose();
            if (\mkw\store::isMPTNGY()) {
                header('Location: ' . \mkw\store::getRouter()->generate('mptngyszakmaianyagok'));
            } else {
                header('Location: ' . \mkw\store::getRouter()->generate('showaccount'));
            }
        } else {
            $view = $this->getTemplateFactory()->createMainView('login.tpl');
            \mkw\store::fillTemplate($view, (!\mkw\store::isSuperzoneB2B()));
            $view->setVar('pagetitle', t('Bejelentkezés') . ' - ' . \mkw\store::getParameter(\mkw\consts::Oldalcim));
            $view->setVar('sikertelen', \mkw\store::getMainSession()->loginerror);
            \mkw\store::getMainSession()->loginerror = false;
            $view->printTemplateResult(true);
        }
    }

    public function doLogin()
    {
        $checkout = $this->params->getStringRequestParam('c') === 'c';
        if ($checkout) {
            $route = \mkw\store::getRouter()->generate('showcheckout');
        } else {
            if (\mkw\store::mustLogin() && \mkw\store::getMainSession()->redirafterlogin) {
                $route = \mkw\store::getMainSession()->redirafterlogin;
                unset(\mkw\store::getMainSession()->redirafterlogin);
            } else {
                $route = \mkw\store::getRouter()->generate('showaccount');
            }
        }
        if ($this->checkloggedin()) {
//			\Zend_Session::writeClose();
            if (\mkw\store::isMugenrace2021()) {
                echo json_encode(['url' => $route]);
            } else {
                header('Location: ' . $route);
            }
        } else {
            if ($this->login($this->params->getStringRequestParam('email'), $this->params->getStringRequestParam('jelszo'))) {
//				\Zend_Session::writeClose();
                if (!$checkout) {
                    $kc = new kosarController($this->params);
                    $kc->clear();
                }
                /** @var \Entities\Partner $partnerobj */
                $partnerobj = \mkw\store::getEm()->getRepository('Entities\Partner')->find(\mkw\store::getMainSession()->pk);
                if ($partnerobj) {
                    $mc = new mainController($this->params);
                    $mc->setOrszag($partnerobj->getOrszagId());
                }
                if (\mkw\store::isMugenrace2021()) {
                    echo json_encode(['url' => $route]);
                } else {
                    header('Location: ' . $route);
                }
            } else {
                \mkw\store::clearLoggedInUser();
                $mc = new mainController($this->params);
                $mc->clearOrszag();
                if ($checkout) {
                    \mkw\store::getMainSession()->loginerror = true;
                    if (\mkw\store::isMugenrace2021()) {
                        echo json_encode([
                            'loginerror' => true,
                            'errormsg' => t('A bejelentkezés nem sikerült'),
                        ]);
                    } else {
                        header('Location: ' . \mkw\store::getRouter()->generate('showcheckout'));
                    }
                } else {
                    \mkw\store::getMainSession()->loginerror = true;
                    header('Location: ' . \mkw\store::getRouter()->generate('showlogin'));
                }
            }
        }
    }

    public function doLogout($uri = null)
    {
        if (!$uri) {
            $prevuri = \mkw\store::getMainSession()->prevuri;
            if (!$prevuri) {
                $prevuri = '/';
            }
        } else {
            $prevuri = $uri;
        }
        if ($this->checkloggedin()) {
            $this->logout();
            $mc = new mainController($this->params);
            $mc->clearOrszag();
        }
        Header('Location: ' . $prevuri);
    }

    public function showAccount()
    {
        /** @var \Entities\Partner $user */
        $user = $this->getRepo()->getLoggedInUser();
        if ($user) {
            $view = $this->getTemplateFactory()->createMainView('fiok.tpl');
            \mkw\store::fillTemplate($view);

            $view->setVar('pagetitle', t('Fiók') . ' - ' . \mkw\store::getParameter(\mkw\consts::Oldalcim));
            $view->setVar('user', $this->loadVars($user)); // fillTemplate-ben megtortenik

            $tec = new termekertesitoController($this->params);
            $view->setVar('ertesitok', $tec->getAllByPartner($user));

            $megrc = new megrendelesfejController($this->params);
            $megrlist = $megrc->getFiokList();
            $view->setVar('megrendeleslist', $megrlist);

            $megrlist = $megrc->getFiokList(true);
            $view->setVar('mindenmegrendeleslist', $megrlist);

            $szamlac = new szamlafejController($this->params);
            $szamlalist = $szamlac->getFiokList();
            $view->setVar('szamlalist', $szamlalist);

            $garugyc = new garanciaugyfejController($this->params);
            $garugylist = $garugyc->getFiokList();
            $view->setVar('garanciaugylist', $garugylist);

            $orszagc = new orszagController($this->params);
            $view->setVar('orszaglist', $orszagc->getSelectList($user->getOrszagId()));

            $telkorzetc = new korzetszamController($this->params);
            $view->setVar('telkorzetlist', $telkorzetc->getSelectList($user->getTelkorzet()));

            $ptcsk = new partnertermekcsoportkedvezmenyController($this->params);
            $ptcsklist = $ptcsk->getFiokList();
            $view->setVar('discountlist', $ptcsklist);
            $view->printTemplateResult(true);
        } else {
            header('Location: ' . \mkw\store::getRouter()->generate('showlogin'));
        }
    }

    /**
     * @param $subject
     * @param \Entities\Partner|null $user
     *
     * @return array
     */
    public function checkPartnerData($subject)
    {
        $ret = [];
        $hibas = false;
        $hibak = [];
        switch ($subject) {
            case 'adataim':
                $vezeteknev = $this->params->getStringRequestParam('vezeteknev');
                $keresztnev = $this->params->getStringRequestParam('keresztnev');
                $email = $this->params->getStringRequestParam('email');
                if (!\Zend_Validate::is($email, 'EmailAddress')) {
                    $hibas = true;
                    $hibak['email'] = t('Rossz az email');
                }
                if ($vezeteknev == '' || $keresztnev == '') {
                    $hibas = true;
                    $hibak['nev'] = t('Üres a név');
                }
                break;
            case 'szamlaadatok':
            case 'szallitasiadatok':
                break;
            case 'jelszo':
                $hibak['hibas'] = 0;
                $checkregijelszo = $this->params->getBoolRequestParam('checkregijelszo', false);
                if ($checkregijelszo) {
                    $regijelszo = $this->params->getStringRequestParam('regijelszo');
                    $user = \mkw\store::getLoggedInUser();
                    if ($user) {
                        $hibas = !$user->checkJelszo($regijelszo);
                    }
                }
                if (!$hibas) {
                    $j1 = $this->params->getStringRequestParam('jelszo1');
                    $j2 = $this->params->getStringRequestParam('jelszo2');
                    if (($j1 === '') && ($j2 === '')) {
                        $hibas = true;
                        $hibak['ures'] = t('Üres jelszót adott meg');
                        $hibak['hibas'] = 3;
                    }
                    if ($j1 !== $j2) {
                        $hibas = true;
                        $hibak['jelszo1'] = t('A két jelszó nem egyezik');
                        $hibak['hibas'] = 1;
                    }
                } else {
                    $hibak['regijelszo'] = t('Rossz régi jelszót adott meg');
                    $hibak['hibas'] = 2;
                }
                break;
            case 'b2bregistration':
                break;
        }
        $ret = [
            'hibas' => $hibas,
            'hibak' => $hibak
        ];
        return $ret;
    }

    public function saveAccount()
    {
        $user = $this->getRepo()->getLoggedInUser();
        $jax = $this->params->getIntRequestParam('jax', 0);
        $subject = $this->params->getStringParam('subject');

        if ($user) {
            $hiba = $this->checkPartnerData($subject);
            if (!$hiba['hibas']) {
                $user = $this->setFields($user, 'edit', $subject);
                $this->getEm()->persist($user);
                $this->getEm()->flush();
                if (!$jax) {
                    Header('Location: ' . \mkw\store::getRouter()->generate('showaccount'));
                } else {
                    echo json_encode($hiba['hibak']);
                }
            } else {
                if ($jax) {
                    echo json_encode($hiba['hibak']);
                } else {
                    echo $hiba['hibak'];
                }
            }
        } else {
            header('Location: ' . \mkw\store::getRouter()->generate('showlogin'));
        }
    }

    public function savePubRegistration()
    {
        $user = new \Entities\Partner();
        $subject = 'pubreg';

        $hiba = $this->checkPartnerData($subject);
        if (!$hiba['hibas']) {
            $user = $this->setFields($user, 'edit', $subject);
            $this->getEm()->persist($user);
            $this->getEm()->flush();
            Header('Location: ' . \mkw\store::getRouter()->generate('pubregistrationthx'));
        } else {
            echo $hiba['hibak'];
        }
    }

    public function createPassReminder()
    {
        $email = $this->params->getStringRequestParam('email');
        if ($email) {
            $p = $this->getRepo()->findNemVendegByEmail($email);
            if (count($p)) {
                $p = $p[0];
                $pr = $p->setPasswordreminder();
                $this->getEm()->persist($p);
                $this->getEm()->flush();
                $emailtpl = $this->getEm()->getRepository('Entities\Emailtemplate')->findOneByNev('jelszoemlekezteto');
                if ($emailtpl) {
                    $tpldata = [
                        'keresztnev' => $p->getKeresztnev(),
                        'vezeteknev' => $p->getVezeteknev(),
                        'fiokurl' => \mkw\store::getRouter()->generate('showaccount', true),
                        'url' => \mkw\store::getFullUrl(),
                        'reminder' => \mkw\store::getRouter()->generate('showpassreminder', true, [
                            'id' => $pr
                        ])
                    ];
                    $subject = $this->getTemplateFactory()->createMainView('string:' . $emailtpl->getTargy());
                    $subject->setVar('user', $tpldata);
                    $body = $this->getTemplateFactory()->createMainView('string:' . $emailtpl->getHTMLSzoveg());
                    $body->setVar('user', $tpldata);
                    $mailer = \mkw\store::getMailer();
                    $mailer->setTo($email);
                    $mailer->setSubject($subject->getTemplateResult());
                    $mailer->setMessage($body->getTemplateResult());
                    $mailer->send();
                }
            }
        }
    }

    public function showPassReminder()
    {
        $route = \mkw\store::getRouter()->generate('show404');
        $pr = $this->params->getStringParam('id');
        if ($pr) {
            $partner = $this->getRepo()->findOneByPasswordreminder($pr);
            if ($partner) {
                $tpl = $this->getTemplateFactory()->createMainView('passreminder.tpl');
                \mkw\store::fillTemplate($tpl);
                $tpl->setVar('reminder', $pr);
                $tpl->printTemplateResult(false);
                return;
            }
        }
        header('Location: ' . $route);
    }

    public function savePassReminder()
    {
        $route = \mkw\store::getRouter()->generate('show404');
        $pr = $this->params->getStringRequestParam('id');
        if ($pr) {
            $user = $this->getRepo()->findOneByPasswordreminder($pr);
            if ($user) {
                $j1 = $this->params->getStringRequestParam('jelszo1');
                $j2 = $this->params->getStringRequestParam('jelszo2');
                if ($j1 === $j2) {
                    $user->setJelszo($j1);
                    $user->clearPasswordreminder();
                    $this->getEm()->persist($user);
                    $this->getEm()->flush();
                    if ($this->login($user)) {
                        $kc = new kosarController($this->params);
                        $kc->clear();
                        $route = \mkw\store::getRouter()->generate('showaccount');
                    }
                }
            }
        }
        header('Location: ' . $route);
    }

    public function getKiegyenlitetlenBiz()
    {
        $partnerid = $this->params->getIntRequestParam('partner');
        $irany = $this->params->getIntRequestParam('irany', 1);
        $br = $this->getRepo('Entities\Bizonylatfej');
        $bizs = $this->getRepo('Entities\Folyoszamla')->getSumByPartner($partnerid, $irany);
        $adat = [];
        foreach ($bizs as $biz) {
            if ($biz['hivatkozottdatum']) {
                $datum = $biz['hivatkozottdatum']->format(\mkw\store::$DateFormat);
            } else {
                $datum = '';
            }
            /** @var \Entities\Bizonylatfej $hbiz */
            $hbiz = $br->find($biz['hivatkozottbizonylat']);
            if ($hbiz) {
                $erbizszam = $hbiz->getErbizonylatszam();
            } else {
                $erbizszam = '';
            }
            $adat[] = [
                'bizszam' => $biz['hivatkozottbizonylat'],
                'erbizszam' => $erbizszam,
                'datum' => $datum,
                'egyenleg' => $biz['egyenleg'] * 1 * $irany,
                'fizmod' => $biz['fizmodnev']
            ];
        }
        $view = $this->createView('kiegyenlitetlenselect.tpl');
        $view->setVar('bizonylatok', $adat);
        $ret = [
            'html' => $view->getTemplateResult()
        ];
        echo json_encode($ret);
    }

    public function mijszExport()
    {
        function x($o, $sor)
        {
            return \mkw\store::getExcelCoordinate($o, $sor);
        }

        $ids = $this->params->getStringRequestParam('ids');
        $country = $this->params->getStringRequestParam('country');

        $filter = new \mkwhelpers\FilterDescriptor();
        if ($ids) {
            $filter->addFilter('id', 'IN', explode(',', $ids));
        }
        if ($country === 'in') {
            $filter->addFilter('mijszexporttiltva', '=', false);
        }

        $partnerek = $this->getRepo()->getAll($filter, ['keresztnev' => 'ASC', 'vezeteknev' => 'ASC']);

        $o = 0;
        $excel = new Spreadsheet();
        $excel->setActiveSheetIndex(0)
            ->setCellValue(x($o++, 1), 'First name')
            ->setCellValue(x($o++, 1), 'Last name')
            ->setCellValue(x($o++, 1), 'Email')
            ->setCellValue(x($o++, 1), 'Issuer of certificate')
            ->setCellValue(x($o++, 1), 'Certificate level')
            ->setCellValue(x($o++, 1), 'Certification year')
            ->setCellValue(x($o++, 1), 'Membership besides HU')
            ->setCellValue(x($o++, 1), 'Country of residency')
            ->setCellValue(x($o++, 1), 'Post code')
            ->setCellValue(x($o++, 1), 'City')
            ->setCellValue(x($o++, 1), 'Address')
            ->setCellValue(x($o++, 1), 'Phone')
            ->setCellValue(x($o++, 1), 'Business')
            ->setCellValue(x($o++, 1), 'Web page');

        if ($partnerek) {
            $sor = 2;
            /** @var \Entities\Partner $partner */
            foreach ($partnerek as $partner) {
                /** @var \Entities\PartnerMIJSZOklevel $oklevel */
                $oklevel = $this->getRepo('Entities\PartnerMIJSZOklevel')->getLastByPartner($partner);
                $o = 0;
                $excel->setActiveSheetIndex(0)
                    ->setCellValue(x($o++, $sor), $partner->getKeresztnev())
                    ->setCellValue(x($o++, $sor), $partner->getVezeteknev())
                    ->setCellValue(x($o++, $sor), $partner->getEmail());
                if ($oklevel) {
                    $excel->setActiveSheetIndex(0)
                        ->setCellValue(x($o++, $sor), $oklevel->getMIJSZOklevelkibocsajtoNev())
                        ->setCellValue(x($o++, $sor), $oklevel->getMIJSZOklevelszintNev())
                        ->setCellValue(x($o++, $sor), $oklevel->getOklevelev());
                } else {
                    $excel->setActiveSheetIndex(0)
                        ->setCellValue(x($o++, $sor), '')
                        ->setCellValue(x($o++, $sor), '')
                        ->setCellValue(x($o++, $sor), '');
                }
                $excel->setActiveSheetIndex(0)
                    ->setCellValue(x($o++, $sor), $partner->getMijszmembershipbesideshu())
                    ->setCellValue(x($o++, $sor), $partner->getOrszagNev())
                    ->setCellValue(x($o++, $sor), $partner->getIrszam())
                    ->setCellValue(x($o++, $sor), $partner->getVaros())
                    ->setCellValue(x($o++, $sor), $partner->getUtca())
                    ->setCellValue(x($o++, $sor), $partner->getTelefon())
                    ->setCellValue(x($o++, $sor), $partner->getMijszbusiness())
                    ->setCellValue(x($o++, $sor), $partner->getHonlap());

                $sor++;
            }
        }
        $writer = IOFactory::createWriter($excel, 'Xlsx');

        switch ($country) {
            case 'in':
                $filepath = \mkw\store::storagePath(uniqid('mijszpartner_in') . '.xlsx');
                break;
            case 'us':
                $filepath = \mkw\store::storagePath(uniqid('mijszpartner_us') . '.xlsx');
                break;
        }
        $writer->save($filepath);

        $fileSize = filesize($filepath);

        // Output headers.
        header("Cache-Control: private");
        header("Content-Type: application/stream");
        header("Content-Length: " . $fileSize);
        header("Content-Disposition: attachment; filename=" . $filepath);

        readfile($filepath);

        \unlink($filepath);
    }

    public function megjegyzesExport()
    {
        function x($o, $sor)
        {
            return \mkw\store::getExcelCoordinate($o, $sor);
        }

        $ids = $this->params->getStringRequestParam('ids');

        $filter = new \mkwhelpers\FilterDescriptor();
        if ($ids) {
            $filter->addFilter('id', 'IN', explode(',', $ids));
        }

        $partnerek = $this->getRepo()->getAll($filter, ['vezeteknev' => 'ASC', 'keresztnev' => 'ASC']);

        $o = 0;
        $excel = new Spreadsheet();
        $excel->setActiveSheetIndex(0)
            ->setCellValue(x($o++, 1), 'Vezetéknév')
            ->setCellValue(x($o++, 1), 'Keresztnév')
            ->setCellValue(x($o++, 1), 'Nyelv')
            ->setCellValue(x($o++, 1), 'Email')
            ->setCellValue(x($o++, 1), 'Telefon')
            ->setCellValue(x($o++, 1), 'Megjegyzés');

        if ($partnerek) {
            $sor = 2;
            /** @var \Entities\Partner $partner */
            foreach ($partnerek as $partner) {
                $o = 0;
                $excel->setActiveSheetIndex(0)
                    ->setCellValue(x($o++, $sor), $partner->getVezeteknev())
                    ->setCellValue(x($o++, $sor), $partner->getKeresztnev())
                    ->setCellValue(x($o++, $sor), $partner->getBizonylatnyelv())
                    ->setCellValue(x($o++, $sor), $partner->getEmail())
                    ->setCellValue(x($o++, $sor), $partner->getTelefon())
                    ->setCellValue(x($o++, $sor), $partner->getMegjegyzes());

                $sor++;
            }
        }
        $writer = IOFactory::createWriter($excel, 'Xlsx');

        $filepath = \mkw\store::storagePath(uniqid('partnermegjegyzes') . '.xlsx');
        $writer->save($filepath);

        $fileSize = filesize($filepath);

        // Output headers.
        header("Cache-Control: private");
        header("Content-Type: application/stream");
        header("Content-Length: " . $fileSize);
        header("Content-Disposition: attachment; filename=" . $filepath);

        readfile($filepath);

        \unlink($filepath);
    }

    public function roadrecordExport()
    {
        $filepath = uniqid('roadrecordpartner') . '.csv';
        $csv = fopen($filepath, 'w');

        $fej = [
            'partner',
            'iranyitoszam',
            'helyseg',
            'Utca',
            'hazszam',
            'megjegyzes',
            'magan',
            'utazas celja',
            'latitude',
            'longitude'
        ];
        fwrite($csv, implode(';', $fej) . "\n");

        $ids = $this->params->getStringRequestParam('ids');

        $filter = new \mkwhelpers\FilterDescriptor();
        if ($ids) {
            $filter->addFilter('id', 'IN', explode(',', $ids));
        }

        $partnerek = $this->getRepo()->getAll($filter, ['nev' => 'ASC']);
        if ($partnerek) {
            /** @var \Entities\Partner $partner */
            foreach ($partnerek as $partner) {
                $sor = [
                    \mkw\store::toiso($partner->getNev()),
                    \mkw\store::toiso($partner->getIrszam()),
                    \mkw\store::toiso($partner->getVaros()),
                    \mkw\store::toiso($partner->getUtca()),
                    \mkw\store::toiso($partner->getHazszam()),
                    '',
                    '',
                    '',
                    '',
                    ''
                ];
                fwrite($csv, implode(';', $sor) . "\n");
            }
        }

        fclose($csv);

        $filesize = filesize($filepath);
        header("Cache-Control: private");
        header("Content-Type: application/stream");
        header("Content-Length: " . $filesize);
        header("Content-Disposition: attachment; filename=" . $filepath);

        readfile($filepath);

        \unlink($filepath);
    }

    public function doAnonym()
    {
        $partnerid = $this->params->getIntRequestParam('id');
        $this->getRepo()->doAnonym($partnerid);
    }

    public function hirlevelExport()
    {
        function x($o, $sor)
        {
            return \mkw\store::getExcelCoordinate($o, $sor);
        }

        $filter = new \mkwhelpers\FilterDescriptor();
        $filter->addFilter('akcioshirlevelkell', '=', true);
        $filter->addFilter('ujdonsaghirlevelkell', '=', true);

        $partnerek = $this->getRepo()->getAll($filter, ['vezeteknev' => 'ASC', 'keresztnev' => 'ASC']);

        $o = 0;
        $excel = new Spreadsheet();
        $excel->setActiveSheetIndex(0)
            ->setCellValue(x($o++, 1), 'Vezetéknév')
            ->setCellValue(x($o++, 1), 'Keresztnév')
            ->setCellValue(x($o++, 1), 'Név')
            ->setCellValue(x($o++, 1), 'Email')
            ->setCellValue(x($o++, 1), 'Akciós hírlevél')
            ->setCellValue(x($o++, 1), 'Újdonság hírlevél');

        if ($partnerek) {
            $sor = 2;
            /** @var \Entities\Partner $partner */
            foreach ($partnerek as $partner) {
                $o = 0;
                $excel->setActiveSheetIndex(0)
                    ->setCellValue(x($o++, $sor), $partner->getVezeteknev())
                    ->setCellValue(x($o++, $sor), $partner->getKeresztnev())
                    ->setCellValue(x($o++, $sor), $partner->getNev())
                    ->setCellValue(x($o++, $sor), $partner->getEmail())
                    ->setCellValue(x($o++, $sor), $partner->getAkcioshirlevelkell())
                    ->setCellValue(x($o++, $sor), $partner->getUjdonsaghirlevelkell());

                $sor++;
            }
        }
        $writer = IOFactory::createWriter($excel, 'Xlsx');

        $filepath = \mkw\store::storagePath(uniqid('partnerhirlevel') . '.xlsx');
        $writer->save($filepath);

        $fileSize = filesize($filepath);

        // Output headers.
        header("Cache-Control: private");
        header("Content-Type: application/stream");
        header("Content-Length: " . $fileSize);
        header("Content-Disposition: attachment; filename=" . $filepath);

        readfile($filepath);

        \unlink($filepath);
    }

    public function arsavcsere()
    {
        $ids = $this->params->getArrayRequestParam('ids');
        $arsav = $this->params->getStringRequestParam('arsav');
        $filter = new \mkwhelpers\FilterDescriptor();
        if ($ids) {
            $filter->addFilter('id', 'IN', $ids);
        }

        $partnerek = $this->getRepo()->getAll($filter);

        /** @var Partner $partner */
        foreach ($partnerek as $partner) {
            $partner->setTermekarazonosito($arsav);
            $this->getEm()->persist($partner);
            $this->getEm()->flush();
        }
    }

    public function tcskedit()
    {
        $ids = $this->params->getArrayRequestParam('ids');
        $tcs = $this->params->getStringRequestParam('tcs');
        $kedvvalt = $this->params->getNumRequestParam('kedv');

        $filter = new \mkwhelpers\FilterDescriptor();
        if ($ids) {
            $filter->addFilter('id', 'IN', $ids);
        }
        $partnerek = $this->getRepo()->getAll($filter);
        /** @var Partner $partner */
        foreach ($partnerek as $partner) {
            /** @var PartnerTermekcsoportKedvezmeny $tcsk */
            foreach ($partner->getTermekcsoportkedvezmenyek() as $tcsk) {
                if ($tcsk->getTermekcsoportId() == $tcs) {
                    $tcsk->setKedvezmeny($kedvvalt);
                    $this->getEm()->persist($tcsk);
                    $this->getEm()->flush();
                }
            }
        }
    }

    public function setflag()
    {
        $id = $this->params->getIntRequestParam('id');
        $kibe = $this->params->getBoolRequestParam('kibe');
        $flag = $this->params->getStringRequestParam('flag');
        /** @var \Entities\Partner $obj */
        $obj = $this->getRepo()->find($id);
        if ($obj) {
            switch ($flag) {
                case 'inaktiv':
                    $obj->setInaktiv($kibe);
                    break;
            }
            $this->getEm()->persist($obj);
            $this->getEm()->flush();
        }
    }

    public function sendEmailSablonok()
    {
        $ids = $this->params->getArrayRequestParam('ids');
        $sablon = $this->getRepo(Emailtemplate::class)->find($this->params->getIntRequestParam('sablon'));
        if ($sablon) {
            foreach ($ids as $id) {
                /** @var \Entities\Partner $partner */
                $partner = $this->getRepo()->find($id);
                if ($partner) {
                    $partner->sendEmailSablon($sablon);
                }
            }
        }
    }

    public function mptngyszamlazasExport()
    {
        function x($o, $sor)
        {
            return \mkw\store::getExcelCoordinate($o, $sor);
        }

        $ids = $this->params->getStringRequestParam('ids');

        $filter = new \mkwhelpers\FilterDescriptor();
        if ($ids) {
            $filter->addFilter('id', 'IN', explode(',', $ids));
        }

        $partnerek = $this->getRepo()->getAll($filter, ['nev' => 'ASC']);

        $o = 0;
        $excel = new Spreadsheet();
        $excel->setActiveSheetIndex(0)
            ->setCellValue(x($o++, 1), 'Név')
            ->setCellValue(x($o++, 1), 'Telefon')
            ->setCellValue(x($o++, 1), 'Email')
            ->setCellValue(x($o++, 1), 'Számlázási név')
            ->setCellValue(x($o++, 1), 'Számlázási cím')
            ->setCellValue(x($o++, 1), 'Munkahely')
            ->setCellValue(x($o++, 1), 'Csoportos fizetés')
            ->setCellValue(x($o++, 1), 'Kapcsolat név')
            ->setCellValue(x($o++, 1), 'Nem vesz részt, csak szerző')
            ->setCellValue(x($o++, 1), 'MPT tag')
            ->setCellValue(x($o++, 1), 'Diák')
            ->setCellValue(x($o++, 1), 'Nyugdíjas');

        if ($partnerek) {
            $sor = 2;
            /** @var \Entities\Partner $partner */
            foreach ($partnerek as $partner) {
                $o = 0;
                $excel->setActiveSheetIndex(0)
                    ->setCellValue(x($o++, $sor), $partner->getNev())
                    ->setCellValue(x($o++, $sor), $partner->getTelefon())
                    ->setCellValue(x($o++, $sor), $partner->getEmail())
                    ->setCellValue(x($o++, $sor), $partner->getSzlanev())
                    ->setCellValue(x($o++, $sor), $partner->getCim())
                    ->setCellValue(x($o++, $sor), $partner->getMptMunkahelynev())
                    ->setCellValue(x($o++, $sor), $partner->getMptngycsoportosfizetes())
                    ->setCellValue(x($o++, $sor), $partner->getMptngykapcsolatnev())
                    ->setCellValue(x($o++, $sor), $partner->isMptngynemveszreszt())
                    ->setCellValue(x($o++, $sor), $partner->isMptngympttag())
                    ->setCellValue(x($o++, $sor), $partner->isMptngydiak())
                    ->setCellValue(x($o++, $sor), $partner->isMptngynyugdijas());

                $sor++;
            }
        }
        $writer = IOFactory::createWriter($excel, 'Xlsx');

        $filepath = \mkw\store::storagePath(uniqid('mptngypartnerszamlazas') . '.xlsx');
        $writer->save($filepath);

        $fileSize = filesize($filepath);

        // Output headers.
        header("Cache-Control: private");
        header("Content-Type: application/stream");
        header("Content-Length: " . $fileSize);
        header("Content-Disposition: attachment; filename=" . $filepath);

        readfile($filepath);

        \unlink($filepath);
    }

    public function queryTaxpayer()
    {
        $payernum = $this->params->getStringRequestParam('adoszam');
        $payernum = substr($payernum, 0, 8);

        $no = new \mkwhelpers\NAVOnline(\mkw\store::getTulajAdoszam(), \mkw\store::getNAVOnlineEnv());
        if ($no->querytaxpayer($payernum)) {
            echo $no->getResult();
        } else {
            $noerrors = $no->getErrors();
            \mkw\store::writelog($payernum . ' querytaxpayer', 'navonline.log');
            \mkw\store::writelog(print_r($noerrors, true), 'navonline.log');
        }
    }
}
