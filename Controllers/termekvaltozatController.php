<?php

namespace Controllers;

use Automattic\WooCommerce\HttpClient\HttpClientException;
use Entities\Meretsor;
use Entities\Raktar;
use Entities\Szin;
use Entities\Termek;
use Entities\TermekKep;
use Entities\TermekValtozat;
use Entities\TermekValtozatAdatTipus;
use Entities\TermekValtozatErtek;
use mkw\store;

class termekvaltozatController extends \mkwhelpers\MattableController
{

    public function __construct($params)
    {
        $this->setEntityName(TermekValtozat::class);
//		$this->setKarbFormTplName('?howto?karbform.tpl');
//		$this->setKarbTplName('?howto?karb.tpl');
//		$this->setListBodyRowTplName('?howto?lista_tbody_tr.tpl');
//		$this->setListBodyRowVarName('_egyed');
        parent::__construct($params);
    }

    public function loadVars($t, $termek, $forKarb = false)
    {
        $tvatc = new termekvaltozatadattipusController($this->params);
        $tkepc = new termekkepController($this->params);
        $szinc = new szinController($this->params);
        $meretc = new meretController($this->params);
        $x = [];
        if (!$t) {
            $t = new \Entities\TermekValtozat();
            $this->getEm()->detach($t);
            $x['oper'] = 'add';
            $x['id'] = store::createUID();
            $x['termek']['id'] = $termek ? $termek->getId() : null;
            $x['keplista'] = $termek ? $tkepc->getSelectList($termek, null) : [];
        } else {
            $x['oper'] = 'edit';
            $x['id'] = $t->getId();
            $x['keplista'] = $tkepc->getSelectList($t->getTermek(), $t->getKepid());
        }
        $x['adattipus1id'] = $t->getAdatTipus1Id();
        $x['adattipus1nev'] = $t->getAdatTipus1Nev();
        $x['adattipus1lista'] = $tvatc->getSelectList(
            \mkw\store::isFixSzinMode() && $x['oper'] == 'add' ? \mkw\store::getParameter(\mkw\consts::ValtozatTipusSzin) : $t->getAdatTipus1Id()
        );
        $x['ertek1'] = $t->getErtek1();
        $x['adattipus2id'] = $t->getAdatTipus2Id();
        $x['adattipus2nev'] = $t->getAdatTipus2Nev();
        $x['adattipus2lista'] = $tvatc->getSelectList(
            \mkw\store::isFixSzinMode() && $x['oper'] == 'add' ? \mkw\store::getParameter(\mkw\consts::ValtozatTipusMeret) : $t->getAdatTipus2Id()
        );
        $x['ertek2'] = $t->getErtek2();
        $x['lathato'] = $t->getLathato();
        $x['lathato2'] = $t->getLathato2();
        $x['lathato3'] = $t->getLathato3();
        $x['lathato4'] = $t->getLathato4();
        $x['lathato5'] = $t->getLathato5();
        $x['lathato6'] = $t->getLathato6();
        $x['lathato7'] = $t->getLathato7();
        $x['lathato8'] = $t->getLathato8();
        $x['lathato9'] = $t->getLathato9();
        $x['lathato10'] = $t->getLathato10();
        $x['lathato11'] = $t->getLathato11();
        $x['lathato12'] = $t->getLathato12();
        $x['lathato13'] = $t->getLathato13();
        $x['lathato14'] = $t->getLathato14();
        $x['lathato15'] = $t->getLathato15();
        $x['elerheto'] = $t->getElerheto();
        $x['elerheto2'] = $t->getElerheto2();
        $x['elerheto3'] = $t->getElerheto3();
        $x['elerheto4'] = $t->getElerheto4();
        $x['elerheto5'] = $t->getElerheto5();
        $x['elerheto6'] = $t->getElerheto6();
        $x['elerheto7'] = $t->getElerheto7();
        $x['elerheto8'] = $t->getElerheto8();
        $x['elerheto9'] = $t->getElerheto9();
        $x['elerheto10'] = $t->getElerheto10();
        $x['elerheto11'] = $t->getElerheto11();
        $x['elerheto12'] = $t->getElerheto12();
        $x['elerheto13'] = $t->getElerheto13();
        $x['elerheto14'] = $t->getElerheto14();
        $x['elerheto15'] = $t->getElerheto15();
        $x['termekfokep'] = $t->getTermekfokep();
        $x['kepurl'] = $t->getKepurl();
        $x['kepleiras'] = $t->getKepleiras();
        $x['kepid'] = $t->getKepId();
        $x['netto'] = $t->getNetto();
        $x['brutto'] = $t->getBrutto();
        $x['cikkszam'] = $t->getCikkszam();
        $x['idegencikkszam'] = $t->getIdegencikkszam();
        $x['vonalkod'] = $t->getVonalkod();
        $x['keszlet'] = $t->getKeszlet();
        $x['foglaltmennyiseg'] = $t->getFoglaltMennyiseg();
        $x['beerkezesdatum'] = $t->getBeerkezesdatum();
        $x['beerkezesdatumstr'] = $t->getBeerkezesdatumStr();
        $x['minboltikeszlet'] = $t->getMinboltikeszlet();
        $x['wcid'] = $t->getWcid();
        if (\mkw\store::isFixSzinMode()) {
            $x['szinid'] = $t->getSzinId();
            $x['meretid'] = $t->getMeretId();
            if ($forKarb) {
                $x['szinlista'] = $szinc->getSelectList($t->getSzinId());
                $x['meretlista'] = $meretc->getSelectList($t->getMeretId());
            }
        }
        return $x;
    }

    protected function setFields($obj)
    {
        $obj->setLathato($this->params->getBoolRequestParam('lathato', false));
        /* MINTA ha nem kell, dobd ki
          $ck=store::getEm()->getRepository('Entities\Termekcimkekat')->find($this->getIntRequestParam('cimkecsoport'));
          if ($ck) {
          $obj->setKategoria($ck);
          }
         */
        return $obj;
    }

    public function getemptyrow()
    {
        $termek = store::getEm()->getRepository(Termek::class)->find($this->params->getIntRequestParam('termekid'));
        $view = $this->createView('termektermekvaltozatkarb.tpl');
        $view->setVar('valtozat', $this->loadVars(null, $termek, true));
        echo $view->getTemplateResult();
    }

    /**
     * @param TermekValtozat $o
     * @param $parancs
     *
     * @return void
     */
    protected function afterSave($o, $parancs = null)
    {
        switch ($parancs) {
            case $this->delOperation:
                $termek = $o->getTermek();
                $szinId = $o->getSzinId();
                if ($termek && $szinId) {
                    foreach ($termek->getTermekSzinKepek() as $szinkep) {
                        if ($szinkep->getSzinId() === $szinId) {
                            $termek->removeTermekSzinKep($szinkep);
                            $this->getEm()->remove($szinkep);
                        }
                    }
                    $this->getEm()->flush();
                }
        }
    }

    public function delall()
    {
        $termek = store::getEm()->getRepository(Termek::class)->find($this->params->getIntRequestParam('termekid'));
        $valtozatok = $termek->getValtozatok();
        $ids = [];
        foreach ($valtozatok as $valt) {
            //$termek->removeValtozat($valt);
            if ($valt->getWcid()) {
                $ids[] = $valt->getId();
            }
            $this->getEm()->remove($valt);
        }
        $this->getEm()->flush();
        if ($ids && \mkw\store::isWoocommerceOn()) {
            $wc = store::getWcClient();
            try {
                \mkw\store::writelog('BATCH DELETE TermekValtozat: ' . json_encode($ids));
                $result = $wc->post('products/' . $termek->getWcid() . '/variations/batch', ['delete' => $ids]);
                $termek->clearWcdate();
                $termek->uploadToWC();
            } catch (HttpClientException $e) {
                \mkw\store::writelog('BATCH DELETE TermekValtozat:HIBA: ' . $e->getResponse()->getBody());
            }
        }
    }

    public function generate()
    {
        $termek = store::getEm()->getRepository(Termek::class)->find($this->params->getIntRequestParam('termekid'));
        if (!$termek) {
            return;
        }

        $adattipus1 = $this->params->getIntRequestParam('valtozatadattipus1');
        $ertek1 = $this->params->getStringRequestParam('valtozatertek1');
        $adattipus2 = $this->params->getIntRequestParam('valtozatadattipus2');
        $ertek2 = $this->params->getStringRequestParam('valtozatertek2');
        $netto = $this->params->getNumRequestParam('valtozatnettogen');
        $brutto = $this->params->getNumRequestParam('valtozatbruttogen');
        $cikkszam = $this->params->getStringRequestParam('valtozatcikkszamgen');
        $idegencikkszam = $this->params->getStringRequestParam('valtozatidegencikkszamgen');
        $elerheto = $this->params->getBoolRequestParam('valtozatelerheto', false);
        $elerheto2 = $this->params->getBoolRequestParam('valtozatelerheto2', false);
        $elerheto3 = $this->params->getBoolRequestParam('valtozatelerheto3', false);
        $elerheto4 = $this->params->getBoolRequestParam('valtozatelerheto4', false);
        $elerheto5 = $this->params->getBoolRequestParam('valtozatelerheto5', false);
        $elerheto6 = $this->params->getBoolRequestParam('valtozatelerheto6', false);
        $elerheto7 = $this->params->getBoolRequestParam('valtozatelerheto7', false);
        $elerheto8 = $this->params->getBoolRequestParam('valtozatelerheto8', false);
        $elerheto9 = $this->params->getBoolRequestParam('valtozatelerheto9', false);
        $elerheto10 = $this->params->getBoolRequestParam('valtozatelerheto10', false);
        $elerheto11 = $this->params->getBoolRequestParam('valtozatelerheto11', false);
        $elerheto12 = $this->params->getBoolRequestParam('valtozatelerheto12', false);
        $elerheto13 = $this->params->getBoolRequestParam('valtozatelerheto13', false);
        $elerheto14 = $this->params->getBoolRequestParam('valtozatelerheto14', false);
        $elerheto15 = $this->params->getBoolRequestParam('valtozatelerheto15', false);
        $lathato = $this->params->getBoolRequestParam('valtozatlathato', false);
        $lathato2 = $this->params->getBoolRequestParam('valtozatlathato2', false);
        $lathato3 = $this->params->getBoolRequestParam('valtozatlathato3', false);
        $lathato4 = $this->params->getBoolRequestParam('valtozatlathato4', false);
        $lathato5 = $this->params->getBoolRequestParam('valtozatlathato5', false);
        $lathato6 = $this->params->getBoolRequestParam('valtozatlathato6', false);
        $lathato7 = $this->params->getBoolRequestParam('valtozatlathato7', false);
        $lathato8 = $this->params->getBoolRequestParam('valtozatlathato8', false);
        $lathato9 = $this->params->getBoolRequestParam('valtozatlathato9', false);
        $lathato10 = $this->params->getBoolRequestParam('valtozatlathato10', false);
        $lathato11 = $this->params->getBoolRequestParam('valtozatlathato11', false);
        $lathato12 = $this->params->getBoolRequestParam('valtozatlathato12', false);
        $lathato13 = $this->params->getBoolRequestParam('valtozatlathato13', false);
        $lathato14 = $this->params->getBoolRequestParam('valtozatlathato14', false);
        $lathato15 = $this->params->getBoolRequestParam('valtozatlathato15', false);
        $termekfokep = $this->params->getBoolRequestParam('valtozattermekfokep', false);
        $kepid = $this->params->getIntRequestParam('valtozatkepid');

        if (store::isFixSzinMode()) {
            $szinid = $this->params->getIntRequestParam('valtozatszinid');
            $meretsorid = $this->params->getIntRequestParam('valtozatmeretsorid');
            if ($szinid && $meretsorid) {
                $szin = $this->getEm()->getRepository(Szin::class)->find($szinid);
                $meretsor = $this->getEm()->getRepository(Meretsor::class)->find($meretsorid);
                if ($szin && $meretsor) {
                    $meretek = $meretsor->getMeretek();
                    $cikkszamok = explode(';', $cikkszam);
                    $idegencikkszamok = explode(';', $idegencikkszam);
                    $cikl = 0;
                    $atSzin = $this->getEm()->getRepository(TermekValtozatAdatTipus::class)->find(
                        \mkw\store::getParameter(\mkw\consts::ValtozatTipusSzin)
                    );
                    $atMeret = $this->getEm()->getRepository(TermekValtozatAdatTipus::class)->find(
                        \mkw\store::getParameter(\mkw\consts::ValtozatTipusMeret)
                    );
                    foreach ($meretek as $meret) {
                        $valtdb = 0;
                        $valtozat = new \Entities\TermekValtozat();
                        $termek->addValtozat($valtozat);
                        $valtozat->setTermek($termek);
                        $valtozat->setLathato($lathato);
                        $valtozat->setLathato2($lathato2);
                        $valtozat->setLathato3($lathato3);
                        $valtozat->setLathato4($lathato4);
                        $valtozat->setLathato5($lathato5);
                        $valtozat->setLathato6($lathato6);
                        $valtozat->setLathato7($lathato7);
                        $valtozat->setLathato8($lathato8);
                        $valtozat->setLathato9($lathato9);
                        $valtozat->setLathato10($lathato10);
                        $valtozat->setLathato11($lathato11);
                        $valtozat->setLathato12($lathato12);
                        $valtozat->setLathato13($lathato13);
                        $valtozat->setLathato14($lathato14);
                        $valtozat->setLathato15($lathato15);
                        if ($termek->getNemkaphato()) {
                            $valtozat->setElerheto(false);
                            $valtozat->setElerheto2(false);
                            $valtozat->setElerheto3(false);
                            $valtozat->setElerheto4(false);
                            $valtozat->setElerheto5(false);
                            $valtozat->setElerheto6(false);
                            $valtozat->setElerheto7(false);
                            $valtozat->setElerheto8(false);
                            $valtozat->setElerheto9(false);
                            $valtozat->setElerheto10(false);
                            $valtozat->setElerheto11(false);
                            $valtozat->setElerheto12(false);
                            $valtozat->setElerheto13(false);
                            $valtozat->setElerheto14(false);
                            $valtozat->setElerheto15(false);
                        } else {
                            $valtozat->setElerheto($elerheto);
                            $valtozat->setElerheto2($elerheto2);
                            $valtozat->setElerheto3($elerheto3);
                            $valtozat->setElerheto4($elerheto4);
                            $valtozat->setElerheto5($elerheto5);
                            $valtozat->setElerheto6($elerheto6);
                            $valtozat->setElerheto7($elerheto7);
                            $valtozat->setElerheto8($elerheto8);
                            $valtozat->setElerheto9($elerheto9);
                            $valtozat->setElerheto10($elerheto10);
                            $valtozat->setElerheto11($elerheto11);
                            $valtozat->setElerheto12($elerheto12);
                            $valtozat->setElerheto13($elerheto13);
                            $valtozat->setElerheto14($elerheto14);
                            $valtozat->setElerheto15($elerheto15);
                        }
                        $valtozat->setNetto($netto);
                        $valtozat->setTermekfokep($termekfokep);
                        if (count($cikkszamok) > 0) {
                            if (count($cikkszamok) == 1) {
                                $valtozat->setCikkszam($cikkszamok[0]);
                            } elseif (array_key_exists($cikl, $cikkszamok)) {
                                $valtozat->setCikkszam($cikkszamok[$cikl]);
                            }
                        }
                        if (count($idegencikkszamok) > 0) {
                            if (count($idegencikkszamok) == 1) {
                                $valtozat->setIdegencikkszam($idegencikkszamok[0]);
                            } elseif (array_key_exists($cikl, $idegencikkszamok)) {
                                $valtozat->setIdegencikkszam($idegencikkszamok[$cikl]);
                            }
                        }
                        if ($szin) {
                            $valtozat->setSzin($szin);
                            if ($atSzin) {
                                $valtozat->setAdatTipus1($atSzin);
                                $valtozat->setErtek1($szin->getNev());
                            }
                            $valtdb++;
                        }
                        if ($meret) {
                            $valtozat->setMeret($meret);
                            if ($atMeret) {
                                $valtozat->setAdatTipus2($atMeret);
                                $valtozat->setErtek2($meret->getNev());
                            }
                            $valtdb++;
                        }

                        $kep = $this->getEm()->getRepository(TermekKep::class)->find($kepid);
                        if ($kep) {
                            $valtozat->setKep($kep);
                        }

                        if ($valtdb > 0) {
                            $this->getEm()->persist($valtozat);
                        } else {
                            $termek->removeValtozat($valtozat);
                        }
                        $cikl++;
                    }
                    $this->getEm()->flush();
                }
            }
        } elseif (($adattipus1 && $ertek1) || ($adattipus2 && $ertek2)) {
            $ertekek1 = explode(';', $ertek1);
            $ertekek2 = explode(';', $ertek2);
            $cikkszamok = explode(';', $cikkszam);
            $idegencikkszamok = explode(';', $idegencikkszam);
            $cikl = 0;
            $at1 = $this->getEm()->getRepository(TermekValtozatAdatTipus::class)->find($adattipus1);
            $at2 = $this->getEm()->getRepository(TermekValtozatAdatTipus::class)->find($adattipus2);
            foreach ($ertekek1 as $ertek1) {
                foreach ($ertekek2 as $ertek2) {
                    $valtdb = 0;
                    $valtozat = new \Entities\TermekValtozat();
                    $termek->addValtozat($valtozat);
                    $valtozat->setTermek($termek);
                    $valtozat->setLathato($lathato);
                    $valtozat->setLathato2($lathato2);
                    $valtozat->setLathato3($lathato3);
                    $valtozat->setLathato4($lathato4);
                    $valtozat->setLathato5($lathato5);
                    $valtozat->setLathato6($lathato6);
                    $valtozat->setLathato7($lathato7);
                    $valtozat->setLathato8($lathato8);
                    $valtozat->setLathato9($lathato9);
                    $valtozat->setLathato10($lathato10);
                    $valtozat->setLathato11($lathato11);
                    $valtozat->setLathato12($lathato12);
                    $valtozat->setLathato13($lathato13);
                    $valtozat->setLathato14($lathato14);
                    $valtozat->setLathato15($lathato15);
                    if ($termek->getNemkaphato()) {
                        $valtozat->setElerheto(false);
                        $valtozat->setElerheto2(false);
                        $valtozat->setElerheto3(false);
                        $valtozat->setElerheto4(false);
                        $valtozat->setElerheto5(false);
                        $valtozat->setElerheto6(false);
                        $valtozat->setElerheto7(false);
                        $valtozat->setElerheto8(false);
                        $valtozat->setElerheto9(false);
                        $valtozat->setElerheto10(false);
                        $valtozat->setElerheto11(false);
                        $valtozat->setElerheto12(false);
                        $valtozat->setElerheto13(false);
                        $valtozat->setElerheto14(false);
                        $valtozat->setElerheto15(false);
                    } else {
                        $valtozat->setElerheto($elerheto);
                        $valtozat->setElerheto2($elerheto2);
                        $valtozat->setElerheto3($elerheto3);
                        $valtozat->setElerheto4($elerheto4);
                        $valtozat->setElerheto5($elerheto5);
                        $valtozat->setElerheto6($elerheto6);
                        $valtozat->setElerheto7($elerheto7);
                        $valtozat->setElerheto8($elerheto8);
                        $valtozat->setElerheto9($elerheto9);
                        $valtozat->setElerheto10($elerheto10);
                        $valtozat->setElerheto11($elerheto11);
                        $valtozat->setElerheto12($elerheto12);
                        $valtozat->setElerheto13($elerheto13);
                        $valtozat->setElerheto14($elerheto14);
                        $valtozat->setElerheto15($elerheto15);
                    }
                    //					$valtozat->setBrutto($brutto);
                    $valtozat->setNetto($netto);
                    $valtozat->setTermekfokep($termekfokep);
                    if (count($cikkszamok) > 0) {
                        if (count($cikkszamok) == 1) {
                            $valtozat->setCikkszam($cikkszamok[0]);
                        } elseif (array_key_exists($cikl, $cikkszamok)) {
                            $valtozat->setCikkszam($cikkszamok[$cikl]);
                        }
                    }
                    if (count($idegencikkszamok) > 0) {
                        if (count($idegencikkszamok) == 1) {
                            $valtozat->setIdegencikkszam($idegencikkszamok[0]);
                        } elseif (array_key_exists($cikl, $idegencikkszamok)) {
                            $valtozat->setIdegencikkszam($idegencikkszamok[$cikl]);
                        }
                    }

                    if ($at1 && $ertek1) {
                        $valtozat->setAdatTipus1($at1);
                        $valtozat->setErtek1($ertek1);
                        $valtdb++;
                    }

                    if ($at2 && $ertek2) {
                        $valtozat->setAdatTipus2($at2);
                        $valtozat->setErtek2($ertek2);
                        $valtdb++;
                    }

                    $kep = $this->getEm()->getRepository(TermekKep::class)->find($kepid);
                    if ($kep) {
                        $valtozat->setKep($kep);
                    }

                    if ($valtdb > 0) {
                        $this->getEm()->persist($valtozat);
                    } else {
                        $termek->removeValtozat($valtozat);
                    }
                    $cikl++;
                }
            }
            $this->getEm()->flush();
        }

        $view = $this->createView('termektermekvaltozatkarb.tpl');
        $valtozatok = $termek->getValtozatok();
        $result = '';
        foreach ($valtozatok as $valt) {
            $view->setVar('valtozat', $this->loadVars($valt, $termek, true));
            $result .= $view->getTemplateResult();
        }
        echo $result;
    }

    public function getKeszletByRaktar()
    {
        $valtozatid = $this->params->getIntRequestParam('valtozatid');
        /** @var TermekValtozat $valtozat */
        $valtozat = $this->getRepo()->find($valtozatid);
        $raktarak = $this->getRepo(Raktar::class)->getAllActive();
        if ($valtozat) {
            $klist = [];
            foreach ($raktarak as $raktar) {
                $klist[] = [
                    'raktarnev' => $raktar->getNev(),
                    'keszlet' => $valtozat->getKeszlet(null, $raktar->getId())
                ];
            }
            $view = $this->createView('termekkeszletreszletezo.tpl');
            $view->setVar('lista', $klist);
            $tpl = $view->getTemplateResult();
        }
        echo json_encode([
            'title' => $valtozat->getNev(),
            'html' => $tpl,
        ]);
    }

    public function getValtozatAdatok()
    {
        $x = [];
        $tid = $this->params->getIntRequestParam('tid');
        $termek = $this->getRepo(Termek::class)->find($tid);
        if ($termek) {
            if (\mkw\store::isMugenrace2021()) {
                $vtt = [];
                $valtozatok = $termek->getValtozatok();
                /** @var TermekValtozat $valt */
                foreach ($valtozatok as $valt) {
                    if ($valt->getXElerheto()) {
                        if ($valt->getAdatTipus1Id() == \mkw\store::getParameter(\mkw\consts::ValtozatTipusSzin)) {
                            $vtt[$valt->getErtek1()] = [
                                'value' => $valt->getErtek1(),
                                'type' => $valt->getAdatTipus1Id(),
                                'kepurl' => $valt->getKepurlMedium(),
                                'eredetikepurl' => $valt->getKepurl(),
                                'largekepurl' => $valt->getKepurlLarge(),
                            ];
                        } elseif ($valt->getAdatTipus2Id() == \mkw\store::getParameter(\mkw\consts::ValtozatTipusSzin)) {
                            $vtt[$valt->getErtek2()] = [
                                'value' => $valt->getErtek2(),
                                'type' => $valt->getAdatTipus2Id(),
                                'kepurl' => $valt->getKepurlMedium(),
                                'eredetikepurl' => $valt->getKepurl(),
                                'largekepurl' => $valt->getKepurlLarge(),
                            ];
                        }
                    }
                }
                $x['szinek'] = $vtt;
                $vtt = [];
                foreach ($valtozatok as $valt) {
                    if ($valt->getXElerheto()) {
                        if ($valt->getAdatTipus1Id() == \mkw\store::getParameter(\mkw\consts::ValtozatTipusMeret)) {
                            $vtt[$valt->getErtek1()] = [
                                'value' => $valt->getErtek1(),
                                'type' => $valt->getAdatTipus1Id()
                            ];
                        } elseif ($valt->getAdatTipus2Id() == \mkw\store::getParameter(\mkw\consts::ValtozatTipusMeret)) {
                            $vtt[$valt->getErtek2()] = [
                                'value' => $valt->getErtek2(),
                                'type' => $valt->getAdatTipus2Id()
                            ];
                        }
                    }
                }
                $x['meretek'] = $vtt;
                $vtt = [];
                foreach ($valtozatok as $valt) {
                    $keszlet = $valt->getKeszlet() - $valt->getFoglaltMennyiseg();
                    if ($valt->getAdatTipus1Id() == \mkw\store::getParameter(\mkw\consts::ValtozatTipusSzin)) {
                        $vtt[$valt->getErtek1() . $valt->getErtek2()] = $keszlet > 0;
                    } elseif ($valt->getAdatTipus2Id() == \mkw\store::getParameter(\mkw\consts::ValtozatTipusSzin)) {
                        $vtt[$valt->getErtek2() . $valt->getErtek1()] = $keszlet > 0;
                    }
                }
                $x['keszlet'] = $vtt;
            }
        }
        echo json_encode($x);
    }

}
