<?php

namespace Controllers;

use Entities\Arsav;
use Entities\Dolgozo;
use Entities\Fizmod;
use Entities\JogaBerlet;
use Entities\Jogaoratipus;
use Entities\JogaReszvetel;
use Entities\Jogaterem;
use Entities\Partner;
use Entities\Penztar;
use Entities\Termek;
use Entities\TermekValtozat;
use Entities\Valutanem;

class jogareszvetelController extends \mkwhelpers\MattableController
{

    public function __construct($params)
    {
        $this->setEntityName(JogaReszvetel::class);
        $this->setKarbFormTplName('jogareszvetelkarbform.tpl');
        $this->setKarbTplName('jogareszvetelkarb.tpl');
        $this->setListBodyRowTplName('jogareszvetellista_tbody_tr.tpl');
        $this->setListBodyRowVarName('_egyed');
        parent::__construct($params);
    }

    protected function loadVars($t, $forKarb = false)
    {
        $x = [];
        if (!$t) {
            $t = new \Entities\JogaReszvetel();
            $this->getEm()->detach($t);
            $x['oper'] = 'add';
            $x['id'] = \mkw\store::createUID();
        } else {
            $x['oper'] = 'edit';
            $x['id'] = $t->getId();
        }
        $x['datum'] = $t->getDatumStr();
        $x['napnev'] = $t->getDatumNapnev();

        $x['partner'] = $t->getPartnerId();
        $x['partnernev'] = $t->getPartnernev();
        $x['partneremail'] = $t->getPartneremail();
        $x['partnertelefon'] = ($t->getPartner() ? $t->getPartner()->getTelefon() : '');

        $x['tanar'] = $t->getTanarId();
        $x['tanarnev'] = $t->getTanarnev();

        $x['jogaterem'] = $t->getJogateremId();
        $x['jogateremnev'] = $t->getJogateremNev();

        $x['jogaoratipus'] = $t->getJogaoratipusId();
        $x['jogaoratipusnev'] = $t->getJogaoratipusNev();

        $x['fizmod'] = $t->getFizmodId();
        $x['fizmodnev'] = $t->getFizmodNev();

        $x['penztar'] = $t->getPenztarId();
        $x['penztarnev'] = $t->getPenztarnev();

        $x['termek'] = $t->getTermekId();
        $x['termeknev'] = $t->getTermeknev();
        $x['nettoegysar'] = $t->getNettoegysar();
        $x['bruttoegysar'] = $t->getBruttoegysar();
        $x['jutalek'] = $t->getJutalek();

        $x['jogaberlet'] = $t->getJogaberletId();
        $x['jogaberletnev'] = $t->getJogaberlet() ? $t->getJogaberlet()->getFullNev() : '';

        $x['tisztaznikell'] = $t->isTisztaznikell();
        $x['online'] = $t->getOnline();

        if ($forKarb) {
            $fizmod = new fizmodController($this->params);
            $x['fizmodlist'] = $fizmod->getSelectList();
            $termek = new termekController($this->params);
            $x['termeklist'] = $termek->getEladhatoSelectList();
            $penztar = new penztarController($this->params);
            $x['penztarlist'] = $penztar->getSelectList();
            $tanar = new dolgozoController($this->params);
            $x['tanarlist'] = $tanar->getSelectList();
            $terem = new jogateremController($this->params);
            $x['jogateremlist'] = $terem->getSelectList();
            $ot = new jogaoratipusController($this->params);
            $x['jogaoratipuslist'] = $ot->getSelectList();
            $partner = new partnerController($this->params);
            $x['partnerlist'] = $partner->getSelectList();
        }

        return $x;
    }

    /**
     * @param \Entities\JogaReszvetel $obj
     * @param $oper
     *
     * @return mixed
     */
    protected function setFields($obj, $oper)
    {
        $partnerkod = $this->params->getIntRequestParam('partner');

        if ($partnerkod == -1) {
            $partneremail = $this->params->getStringRequestParam('partneremail');
            if ($partneremail) {
                $partnerobj = $this->getRepo(Partner::class)->findOneBy(['email' => $partneremail]);
                if (!$partnerobj) {
                    $partnerobj = new \Entities\Partner();
                }
            } else {
                $partnerobj = new \Entities\Partner();
            }
            $partnerobj->setAdoszam($this->params->getStringRequestParam('partneradoszam'));
            $partnerobj->setEuadoszam($this->params->getStringRequestParam('partnereuadoszam'));
            $partnerobj->setEmail($this->params->getStringRequestParam('partneremail'));
            $partnerobj->setTelefon($this->params->getStringRequestParam('partnertelefon'));
            $partnerobj->setNev($this->params->getStringRequestParam('partnernev'));
            $partnerobj->setVezeteknev($this->params->getStringRequestParam('partnervezeteknev'));
            $partnerobj->setKeresztnev($this->params->getStringRequestParam('partnerkeresztnev'));
            $partnerobj->setIrszam($this->params->getStringRequestParam('partnerirszam'));
            $partnerobj->setVaros($this->params->getStringRequestParam('partnervaros'));
            $partnerobj->setUtca($this->params->getStringRequestParam('partnerutca'));
            $this->getEm()->persist($partnerobj);
        }
        if ($partnerkod > 0) {
            $ck = \mkw\store::getEm()->getRepository(Partner::class)->find($partnerkod);
            if ($ck) {
                $obj->setPartner($ck);
            }
        } else {
            $obj->setPartner($partnerobj);
        }

        $obj->setDatum($this->params->getStringRequestParam('datum'));
        $ck = \mkw\store::getEm()->getRepository(Partner::class)->find($this->params->getIntRequestParam('tanar', 0));
        if ($ck) {
            $obj->setTanar($ck);
        }
        $ck = \mkw\store::getEm()->getRepository(Partner::class)->find($this->params->getIntRequestParam('partner', 0));
        if ($ck) {
            $obj->setPartner($ck);
        }
        $ck = \mkw\store::getEm()->getRepository(Fizmod::class)->find($this->params->getIntRequestParam('fizmod', 0));
        if ($ck) {
            $obj->setFizmod($ck);
        }
        $ck = \mkw\store::getEm()->getRepository(Jogaterem::class)->find($this->params->getIntRequestParam('jogaterem', 0));
        if ($ck) {
            $obj->setJogaterem($ck);
        }
        $ck = \mkw\store::getEm()->getRepository(Jogaoratipus::class)->find($this->params->getIntRequestParam('jogaoratipus', 0));
        if ($ck) {
            $obj->setJogaoratipus($ck);
        }
        $ck = \mkw\store::getEm()->getRepository(Penztar::class)->find($this->params->getIntRequestParam('penztar', 0));
        if ($ck) {
            $obj->setPenztar($ck);
        }
        $ck = \mkw\store::getEm()->getRepository(Termek::class)->find($this->params->getIntRequestParam('termek', 0));
        if ($ck) {
            $obj->setTermek($ck);
        }
        $ck = \mkw\store::getEm()->getRepository(JogaBerlet::class)->find($this->params->getIntRequestParam('jogaberlet', 0));
        if ($ck) {
            $obj->setJogaberlet($ck);
        } else {
            $obj->removeJogaberlet();
        }
        $obj->setTisztaznikell($this->params->getBoolRequestParam('tisztaznikell'));
        $obj->setOnline($this->params->getIntRequestParam('online'));
        return $obj;
    }

    public function getlistbody()
    {
        $view = $this->createView('jogareszvetellista_tbody.tpl');

        $filter = new \mkwhelpers\FilterDescriptor();

        $f = $this->params->getStringRequestParam('partnernevfilter');
        if ($f) {
            $filter->addFilter('partnernev', 'LIKE', '%' . $f . '%');
        }

        $f = $this->params->getStringRequestParam('partneremailfilter');
        if ($f) {
            $filter->addFilter('partneremail', 'LIKE', '%' . $f . '%');
        }

        $f = $this->params->getIntRequestParam('fizmodfilter');
        if ($f) {
            $bs = $this->getRepo(Fizmod::class)->findOneById($f);
            if ($bs) {
                $filter->addFilter('fizmod', '=', $bs);
            }
        }

        $tol = $this->params->getStringRequestParam('datumtolfilter');
        $ig = $this->params->getStringRequestParam('datumigfilter');
        if ($tol) {
            $filter->addFilter('datum', '>=', $tol);
        }
        if ($ig) {
            $filter->addFilter('datum', '<=', $ig);
        }

        $tisztaznikell = $this->params->getBoolRequestParam('tisztaznikellfilter');
        switch ($tisztaznikell) {
            case 0:
                $filter->addFilter('tisztaznikell', '=', false);
                break;
            case 1:
                $filter->addFilter('tisztaznikell', '=', true);
                break;
        }

        $online = $this->params->getIntRequestParam('onlinefilter');
        switch ($online) {
            case 1:
                $filter->addFilter('online', '=', 1);
                break;
            case 2:
                $filter->addFilter('online', '=', 2);
                break;
        }

        $tanar = $this->params->getIntRequestParam('tanarfilter');
        if ($tanar) {
            $ts = $this->getRepo(Dolgozo::class)->find($tanar);
            if ($ts) {
                $filter->addFilter('tanar', '=', $ts);
            }
        }

        $this->initPager($this->getRepo()->getCount($filter));

        $egyedek = $this->getRepo()->getWithJoins(
            $filter,
            $this->getOrderArray(),
            $this->getPager()->getOffset(),
            $this->getPager()->getElemPerPage()
        );

        echo json_encode($this->loadDataToView($egyedek, 'egyedlista', $view));
    }

    public function getSelectList($selid = null)
    {
        $rec = $this->getRepo()->getAll([], ['nev' => 'ASC']);
        $res = [];
        foreach ($rec as $sor) {
            $res[] = ['id' => $sor['id'], 'caption' => $sor['nev'], 'selected' => ($sor['id'] == $selid)];
        }
        return $res;
    }

    public function viewselect()
    {
        $view = $this->createView('jogareszvetellista.tpl');

        $view->setVar('pagetitle', t('Óra látogatások'));
        if (!\mkw\store::isPartnerAutocomplete()) {
            $partner = new partnerController($this->params);
            $view->setVar('partnerlist', $partner->getSelectList());
        }
        $fizmod = new fizmodController($this->params);
        $view->setVar('fizmodlist', $fizmod->getSelectList());
        $penztar = new penztarController($this->params);
        $view->setVar('penztarlist', $penztar->getSelectList());
        $termek = new termekController($this->params);
        $view->setVar('termeklist', $termek->getSelectList());
        $tanarc = new dolgozoController($this->params);
        $view->setVar('tanarlist', $tanarc->getSelectList());
        $view->printTemplateResult(false);
    }

    public function viewlist()
    {
        $view = $this->createView('jogareszvetellista.tpl');

        $view->setVar('pagetitle', t('Óra látogatások'));
        $view->setVar('orderselect', $this->getRepo()->getOrdersForTpl());
        $view->setVar('batchesselect', $this->getRepo()->getBatchesForTpl());
        if (!\mkw\store::isPartnerAutocomplete()) {
            $partner = new partnerController($this->params);
            $view->setVar('partnerlist', $partner->getSelectList(($record ? $record->getPartnerId() : 0)));
        }
        $fizmod = new fizmodController($this->params);
        $view->setVar('fizmodlist', $fizmod->getSelectList(($record ? $record->getFizmodId() : 0)));
        $penztar = new penztarController($this->params);
        $view->setVar('penztarlist', $penztar->getSelectList());
        $tanarc = new dolgozoController($this->params);
        $view->setVar('tanarlist', $tanarc->getSelectList());
        $view->printTemplateResult(false);
    }

    public function getemptyrow()
    {
        $view = $this->createView('jogareszvetelquickkarb.tpl');
        $v = $this->loadVars(null, true);
        $view->setVar('egyed', $v);
        echo json_encode([
            'id' => $v['id'],
            'html' => $view->getTemplateResult()
        ]);
    }

    protected function _getkarb($tplname)
    {
        $id = $this->params->getRequestParam('id', 0);
        $oper = $this->params->getRequestParam('oper', '');
        $view = $this->createView($tplname);

        $view->setVar('pagetitle', t('Óra látogatás'));
        $view->setVar('formaction', '/admin/jogareszvetel/save');
        $view->setVar('oper', $oper);
        /** @var \Entities\JogaReszvetel $record */
        $record = $this->getRepo()->findWithJoins($id);
        $view->setVar('egyed', $this->loadVars($record));
        if (!\mkw\store::isPartnerAutocomplete()) {
            $partner = new partnerController($this->params);
            $view->setVar('partnerlist', $partner->getSelectList(($record ? $record->getPartnerId() : 0)));
        }
        $fizmod = new fizmodController($this->params);
        $view->setVar('fizmodlist', $fizmod->getSelectList(($record ? $record->getFizmodId() : 0)));

        return $view->getTemplateResult();
    }

    public function getar()
    {
        // Nincsenek ársávok
        if (!\mkw\store::isArsavok()) {
            $termek = $this->getEm()->getRepository(Termek::class)->find($this->params->getIntRequestParam('termek'));
            $partner = $this->getEm()->getRepository(Partner::class)->find($this->params->getIntRequestParam('partner'));
            $valutanem = $this->getEm()->getRepository(Valutanem::class)->find($this->params->getIntRequestParam('valutanem'));
            $valtozat = null;
            if ($this->params->getIntRequestParam('valtozat')) {
                $valtozat = $this->getEm()->getRepository(TermekValtozat::class)->find($this->params->getIntRequestParam('valtozat'));
            }
            if ($termek) {
                $o = $termek->getJogaalkalom();
                if (!$o) {
                    $o = 1;
                }
                $r = [
                    'netto' => $termek->getNettoAr($valtozat) / $o,
                    'brutto' => $termek->getBruttoAr($valtozat) / $o,
                    'nettofull' => $termek->getNettoAr($valtozat),
                    'bruttofull' => $termek->getBruttoAr($valtozat),
                    'kedvezmeny' => $termek->getKedvezmeny($partner) / $o,
                    'enetto' => $termek->getKedvezmenynelkuliNettoAr($valtozat, $partner, $valutanem) / $o,
                    'ebrutto' => $termek->getKedvezmenynelkuliBruttoAr($valtozat, $partner, $valutanem) / $o
                ];
                echo json_encode($r);
            } else {
                echo json_encode([
                    'netto' => 0,
                    'brutto' => 0,
                    'nettofull' => 0,
                    'bruttofull' => 0,
                    'kedvezmeny' => 0,
                    'enetto' => 0,
                    'ebrutto' => 0
                ]);
            }
        } // Vannak ársávok
        else {
            $arsav = $this->getEm()->getRepository(Arsav::class)->findOneBy(['nev' => 'folyamatos']);
            /** @var \Entities\Termek $termek */
            $termek = $this->getEm()->getRepository(Termek::class)->find($this->params->getIntRequestParam('termek'));
            $partner = $this->getEm()->getRepository(Partner::class)->find($this->params->getIntRequestParam('partner'));
            $valutanem = $this->getEm()->getRepository(Valutanem::class)->find($this->params->getIntRequestParam('valutanem'));
            $valtozat = null;
            if ($this->params->getIntRequestParam('valtozat')) {
                $valtozat = $this->getEm()->getRepository(TermekValtozat::class)->find($this->params->getIntRequestParam('valtozat'));
            }
            if ($termek) {
                $o = $termek->getJogaalkalom();
                if (!$o) {
                    $o = 1;
                }
                $r = [
                    'netto' => $termek->getNettoAr($valtozat, $partner, $valutanem, $arsav) / $o,
                    'brutto' => $termek->getBruttoAr($valtozat, $partner, $valutanem, $arsav) / $o,
                    'nettofull' => $termek->getNettoAr($valtozat, $partner, $valutanem, $arsav),
                    'bruttofull' => $termek->getBruttoAr($valtozat, $partner, $valutanem, $arsav),
                    'kedvezmeny' => $termek->getKedvezmeny($partner) / $o,
                    'enetto' => $termek->getKedvezmenynelkuliNettoAr($valtozat, $partner, $valutanem, $arsav) / $o,
                    'ebrutto' => $termek->getKedvezmenynelkuliBruttoAr($valtozat, $partner, $valutanem, $arsav) / $o
                ];
                echo json_encode($r);
            } else {
                echo json_encode([
                    'netto' => 0,
                    'brutto' => 0,
                    'nettofull' => 0,
                    'bruttofull' => 0,
                    'kedvezmeny' => 0,
                    'enetto' => 0,
                    'ebrutto' => 0
                ]);
            }
        }
    }

    public function quickSave()
    {
        $terem = $this->getEm()->getRepository(Jogaterem::class)->find($this->params->getIntRequestParam('jogaterem'));
        $oratipus = $this->getEm()->getRepository(Jogaoratipus::class)->find($this->params->getIntRequestParam('jogaoratipus', 0));
        $tanar = $this->getEm()->getRepository(Dolgozo::class)->find($this->params->getIntRequestParam('tanar', 0));
        $jrids = $this->params->getArrayRequestParam('jrid');
        if ($terem && $oratipus && $tanar) {
            foreach ($jrids as $jrid) {
                $uresterem = $this->params->getBoolRequestParam('uresterem_' . $jrid);
                $termek = $this->getEm()->getRepository(Termek::class)->find($this->params->getIntRequestParam('termek_' . $jrid, 0));
                /** @var \Entities\Fizmod $fizmod */
                $fizmod = $this->getEm()->getRepository(Fizmod::class)->find($this->params->getIntRequestParam('fizmod_' . $jrid, 0));
                $penztar = $this->getEm()->getRepository(Penztar::class)->find($this->params->getIntRequestParam('penztar_' . $jrid, 0));
                $berlet = $this->getEm()->getRepository(JogaBerlet::class)->find($this->params->getIntRequestParam('jogaberlet_' . $jrid, 0));
                if (
                    (!$uresterem && $termek && ($this->params->getNumRequestParam('ar_' . $jrid, 0) !== 0)) ||
                    $uresterem
                ) {
                    $jroper = $this->params->getStringRequestParam('jroper_' . $jrid);
                    if ($jroper === 'add') {
                        $jr = new \Entities\JogaReszvetel();
                        $partnerkod = $this->params->getIntRequestParam('partner_' . $jrid, 0);
                        if ($partnerkod > 0) {
                            /** @var \Entities\Partner $partnerobj */
                            $partnerobj = \mkw\store::getEm()->getRepository(Partner::class)->find($partnerkod);
                            if ($partnerobj) {
                                $partnerobj->setEmail($this->params->getStringRequestParam('partneremail_' . $jrid));
                                $partnerobj->setTelefon($this->params->getStringRequestParam('partnertelefon_' . $jrid));
                                $partnerobj->setVezeteknev($this->params->getStringRequestParam('partnervezeteknev_' . $jrid));
                                $partnerobj->setKeresztnev($this->params->getStringRequestParam('partnerkeresztnev_' . $jrid));
                                if ($partnerobj->getVezeteknev() || $partnerobj->getKeresztnev()) {
                                    $partnerobj->setNev($partnerobj->getVezeteknev() . ' ' . $partnerobj->getKeresztnev());
                                }
                                $partnerobj->setIrszam($this->params->getStringRequestParam('partnerirszam_' . $jrid));
                                $partnerobj->setVaros($this->params->getStringRequestParam('partnervaros_' . $jrid));
                                $partnerobj->setUtca($this->params->getStringRequestParam('partnerutca_' . $jrid));
                                $partnerobj->setHazszam($this->params->getStringRequestParam('partnerhazszam_' . $jrid));
                                $this->getEm()->persist($partnerobj);

                                $jr->setPartner($partnerobj);
                            }
                        } elseif ($partnerkod === -1) {
                            $partnerobj = null;
                            $partneremail = $this->params->getStringRequestParam('partneremail_' . $jrid);
                            if ($partneremail) {
                                /** @var \Entities\Partner $partnerobj */
                                $partnerobj = $this->getRepo(Partner::class)->findOneBy(['email' => $partneremail]);
                            }
                            if (!$partnerobj) {
                                $partnerobj = new \Entities\Partner();
                            }
                            $partnerobj->setEmail($this->params->getStringRequestParam('partneremail_' . $jrid));
                            $partnerobj->setTelefon($this->params->getStringRequestParam('partnertelefon_' . $jrid));
                            $partnerobj->setVezeteknev($this->params->getStringRequestParam('partnervezeteknev_' . $jrid));
                            $partnerobj->setKeresztnev($this->params->getStringRequestParam('partnerkeresztnev_' . $jrid));
                            if ($partnerobj->getVezeteknev() || $partnerobj->getKeresztnev()) {
                                $partnerobj->setNev($partnerobj->getVezeteknev() . ' ' . $partnerobj->getKeresztnev());
                            }
                            $partnerobj->setIrszam($this->params->getStringRequestParam('partnerirszam_' . $jrid));
                            $partnerobj->setVaros($this->params->getStringRequestParam('partnervaros_' . $jrid));
                            $partnerobj->setUtca($this->params->getStringRequestParam('partnerutca_' . $jrid));
                            $partnerobj->setHazszam($this->params->getStringRequestParam('partnerhazszam_' . $jrid));

                            if (!$partnerobj->getEmail()) {
                                $partnerobj->setEmail(
                                    uniqid(\Behat\Transliterator\Transliterator::transliterate($partnerobj->getNev(), '_'), true) . '@mail.local'
                                );
                            }
                            if (!$partnerobj->getIrszam()) {
                                $partnerobj->setIrszam('1011');
                            }
                            if (!$partnerobj->getVaros()) {
                                $partnerobj->setVaros('Budapest');
                            }
                            $this->getEm()->persist($partnerobj);

                            $jr->setPartner($partnerobj);
                        }
                        $jr->setUresterem($uresterem);
                        if ($uresterem) {
                            $jr->setPartnernev('Üres terem');
                        }
                        $jr->setJogaoratipus($oratipus);
                        $jr->setJogaterem($terem);
                        $jr->setTermek($termek);
                        $jr->setBruttoegysar($this->params->getNumRequestParam('ar_' . $jrid));
                        $jr->setTanar($tanar);
                        $jr->setDatum($this->params->getStringRequestParam('datum'));
                        $jr->setFizmod($fizmod);
                        $jr->setPenztar($penztar);
                        $jr->setJogaberlet($berlet);
                        $jr->setTisztaznikell($this->params->getBoolRequestParam('tisztaznikell_' . $jrid));
                        $jr->calcJutalek();
                        $this->getEm()->persist($jr);
                        $this->getEm()->flush();
                    }
                }
            }
        }
    }

    public function fizet()
    {
        /** @var \Entities\RendezvenyJelentkezes $r */
        $r = $this->getRepo()->find($this->params->getIntRequestParam('id'));
        /** @var \Entities\Fizmod $fizmod */
        $fizmod = $this->getRepo('\Entities\Fizmod')->find($this->params->getIntRequestParam('fizmod'));
        $bankszamla = $this->getRepo('\Entities\Bankszamla')->find($this->params->getIntRequestParam('bankszamla'));
        $penztar = $this->getRepo('\Entities\Penztar')->find($this->params->getIntRequestParam('penztar'));
        $jogcim = $this->getRepo('\Entities\Jogcim')->find($this->params->getIntRequestParam('jogcim'));
        $osszeg = $this->params->getNumRequestParam('osszeg');

        if ($r && $fizmod && $jogcim
            && (($this->params->getIntRequestParam('bankszamla') && $bankszamla) || ($this->params->getIntRequestParam('penztar') && $penztar))
            && $osszeg) {
            $tipus = $fizmod->getTipus();
            if ($tipus === 'B' && $bankszamla) {
                $biz = new \Entities\Bankbizonylatfej();
                $bt = new \Entities\Bankbizonylattetel();
                $biz->addBizonylattetel($bt);

                $biz->setBizonylattipus($this->getRepo('\Entities\Bizonylattipus')->find('bank'));
                $biz->setMegjegyzes(at('Automatikus bizonylat'));
                $biz->setBankszamla($bankszamla);
                $biz->setPartner($r->getPartner());
                $biz->setKelt('');
                $biz->setValutanem(\mkw\store::getParameter(\mkw\consts::Valutanem));

                $bt->setPartner($r->getPartner());
                $bt->setValutanem(\mkw\store::getParameter(\mkw\consts::Valutanem));
                $bt->setDatum($this->params->getStringRequestParam('datum'));
                $bt->setHivatkozottdatum($this->params->getStringRequestParam('datum'));
                $bt->setNetto($osszeg);
                $bt->setAfa(0);
                $bt->setBrutto($osszeg);
                $bt->setIrany(1);
                $bt->setJogcim($jogcim);

                $this->getEm()->persist($biz);
                $this->getEm()->flush($biz);

                $r->setFizetvebankszamla($bankszamla);
                $r->setFizetvebankbizonylatszam($biz->getId());
                $r->setFizetvebanktetelid($bt->getId());
            } elseif ($tipus === 'P' && $penztar) {
                $biz = new \Entities\Penztarbizonylatfej();
                $bt = new \Entities\Penztarbizonylattetel();
                $biz->addBizonylattetel($bt);

                $biz->setBizonylattipus($this->getRepo('\Entities\Bizonylattipus')->find('penztar'));
                $biz->setMegjegyzes(at('Automatikus bizonylat'));
                $biz->setIrany(1);
                $biz->setKelt('');
                $biz->setPenztar($penztar);
                $biz->setPartner($r->getPartner());

                $bt->setJogcim($jogcim);
                $bt->setNetto($osszeg);
                $bt->setAfa(0);
                $bt->setBrutto($osszeg);
                $bt->setSzoveg($r->getRendezvenyTeljesNev());
                $bt->setHivatkozottdatum($this->params->getStringRequestParam('datum'));

                $this->getEm()->persist($biz);
                $this->getEm()->flush($biz);

                $r->setFizetvepenztar($penztar);
                $r->setFizetvepenztarbizonylatszam($biz->getId());
                $r->setFizetvepenztartetelid($bt->getId());
            }

            $r->setFizetesdatum($this->params->getStringRequestParam('datum'));
            $r->setFizetveosszeghuf($osszeg);
            $r->setFizmod($fizmod);
            $r->setFizetve(true);

            $this->getEm()->persist($r);
            $this->getEm()->flush();

            echo json_encode(['result' => 'ok']);
        } else {
            echo json_encode(['result' => 'error', 'msg' => at('Nem adott meg minden adatot!')]);
        }
    }

    protected function beforeRemove($o)
    {
        /** @var \Entities\JogaBerlet $berlet */
        $berlet = $o->getJogaberlet();
        if ($berlet) {
            $berlet->calcLejart(-1);
        }
    }

}