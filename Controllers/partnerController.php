<?php

namespace Controllers;

use Entities\Arsav;
use Entities\Fizmod;
use Entities\MPTNGYEgyetem;
use Entities\MPTNGYKar;
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
use Traits\PartnerAuth;
use Traits\PartnerRegistration;
use Traits\PartnerPassReminder;
use Traits\PartnerFiok;
use Traits\PartnerDataProvider;
use Traits\PartnerBulkOps;
use Traits\PartnerExport;

/**
 * @see \Traits\PartnerAuth          storefront belépés/kilépés/session (login, logout, autologout, doLogin, doLogout, showLoginForm)
 * @see \Traits\PartnerRegistration  regisztráció + API regisztráció + email-ellenőrzés
 * @see \Traits\PartnerPassReminder        jelszó-emlékeztető (elfelejtett jelszó) folyamat
 * @see \Traits\PartnerFiok          "fiókom" oldal (showAccount, saveAccount, checkPartnerData)
 * @see \Traits\PartnerDataProvider  AJAX/select-list adatszolgáltatók más kontrollereknek
 * @see \Traits\PartnerBulkOps       tömeges admin műveletek (ársávcsere, kedvezmény, flag, email, anonimizálás)
 * @see \Traits\PartnerExport        XLSX exportok (megjegyzés, hírlevél, MPTNGY számlázás)
 */
class partnerController extends \mkwhelpers\MattableController
{
    use PartnerAuth;
    use PartnerRegistration;
    use PartnerPassReminder;
    use PartnerFiok;
    use PartnerDataProvider;
    use PartnerBulkOps;
    use PartnerExport;

    public function __construct()
    {
        $this->setEntityName(Partner::class);
        $this->setKarbFormTplName('partnerkarbform.tpl');
        $this->setKarbTplName('partnerkarb.tpl');
        $this->setListBodyRowTplName('partnerlista_tbody_tr.tpl');
        $this->setListBodyRowVarName('_partner');
        parent::__construct();
    }

    public function loadVars($t, $forKarb = false)
    {
        $kedvCtrl = new partnertermekcsoportkedvezmenyController();
        $termekkedvCtrl = new partnertermekkedvezmenyController();
        $dokCtrl = new partnerdokController();
        $mptfolyoszamlaCtrl = new mptfolyoszamlaController();
        if (!$t) {
            $t = new \Entities\Partner();
            $this->getEm()->detach($t);
        }
        $x = $this->getEntityFieldsArray($t);
        $x['orszagnev'] = $t->getOrszagNev();
        $x['szallorszagnev'] = $t->getSzallorszagNev();
        $x['lcim'] = $t->getLCim();
        $x['cimkek'] = $t->getCimkeNevek();
        $x['fizmodnev'] = $t->getFizmodNev();
        $x['uzletkotonev'] = $t->getUzletkotoNev();
        $x['szallcim'] = $t->getSzallcim();
        $x['szuletesiidostr'] = $t->getSzuletesiidoStr();
        $x['loggedin'] = $this->checkloggedin();
        $x['valutanemnev'] = $t->getValutanemnev();
        $x['arsavnev'] = $t->getArsav()?->getNev();
        $x['szallitasimodnev'] = $t->getSzallitasimodNev();
        $x['partnertipusnev'] = $t->getPartnertipusNev();
        $x['apinev'] = $t->getApiconsumernev();
        $x['lastmodstr'] = $t->getLastmodStr();
        $x['createdstr'] = $t->getCreatedStr();
        $x['updatedby'] = $t->getUpdatedbyNev();
        $x['createdby'] = $t->getCreatedbyNev();
        $x['mpt_registerdatestr'] = $t->getMptRegisterdateStr();
        $x['mpt_lastvisitstr'] = $t->getMptLastvisitStr();
        $x['mpt_lastupdatestr'] = $t->getMptLastupdateStr();
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
        $x['mpt_tagsagdatestr'] = $t->getMptTagsagdateStr();
        $x['mptngybefizetesdatum'] = $t->getMptngybefizetesdatumStr();
        $x['mptngybefizetesmodnev'] = $t->getMptngybefizetesmod()?->getNev();
        $x['mptngyegyetem'] = $t->getMPTNGYEgyetemId();
        $x['mptngyegyetemnev'] = $t->getMPTNGYEgyetemNev();
        $x['mptngykar'] = $t->getMPTNGYKarId();
        $x['mptngykarnev'] = $t->getMPTNGYKarNev();
        $x['xnemrendelhet'] = $t->isXNemrendelhet();
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
            foreach ($t->getMptfolyoszamlak() as $item) {
                $fsz[] = $mptfolyoszamlaCtrl->loadVars($item, true);
            }
            $x['mptfolyoszamla'] = $fsz;
        }
        return $x;
    }

    /**
     * @param \Entities\Partner $obj
     * @param string|null $parancs add/edit/… – nem használt, a hívók adják át
     * @param string $subject melyik űrlap/fül mentődik
     *
     * @return \Entities\Partner
     */
    public function setFields($obj, $parancs)
    {
        $obj = $this->setEntityFieldsFromRequest($obj, [
            'skip' => ['jelszo', 'vendeg', 'apireg', 'anonymizalnikell', 'anonym']
        ]);

        $j1 = $this->params->getStringRequestParam('jelszo1');
        $j2 = $this->params->getStringRequestParam('jelszo2');
        if ($j1 && $j2 && $j1 === $j2) {
            $obj->setJelszo($j1);
        }

        $arsav = \mkw\store::getEm()->getRepository(Arsav::class)->find($this->params->getIntRequestParam('arsav'));
        if ($arsav) {
            $obj->setArsav($arsav);
        } else {
            $obj->removeArsav();
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
        $partnertipus = \mkw\store::getEm()->getRepository(Partnertipus::class)->find($this->params->getIntRequestParam('partnertipus', 0));
        if ($partnertipus) {
            $obj->setPartnertipus($partnertipus);
        } else {
            $obj->setPartnertipus(null);
        }
        $orszag = \mkw\store::getEm()->getRepository(Orszag::class)->find($this->params->getIntRequestParam('orszag', 0));
        if ($orszag) {
            $obj->setOrszag($orszag);
        } else {
            $obj->setOrszag(null);
        }
        $orszag = \mkw\store::getEm()->getRepository(Orszag::class)->find($this->params->getIntRequestParam('szallorszag', 0));
        if ($orszag) {
            $obj->setSzallorszag($orszag);
        } else {
            $obj->setSzallorszag(null);
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
        $egyetem = \mkw\store::getEm()->getRepository(MPTNGYEgyetem::class)->find($this->params->getIntRequestParam('mptngyegyetem'));
        if ($egyetem) {
            $obj->setMptngyegyetem($egyetem);
        } else {
            $obj->removeMptngyegyetem();
        }
        $kar = \mkw\store::getEm()->getRepository(MPTNGYKar::class)->find($this->params->getIntRequestParam('mptngykar'));
        if ($kar) {
            $obj->setMptngykar($kar);
        } else {
            $obj->removeMptngykar();
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
