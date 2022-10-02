<?php

namespace Controllers;

use Entities\Termek;
use Entities\TermekValtozat;
use mkw\store;

class termekvaltozatController extends \mkwhelpers\MattableController {

    public function __construct($params) {
        $this->setEntityName('Entities\TermekValtozat');
//		$this->setKarbFormTplName('?howto?karbform.tpl');
//		$this->setKarbTplName('?howto?karb.tpl');
//		$this->setListBodyRowTplName('?howto?lista_tbody_tr.tpl');
//		$this->setListBodyRowVarName('_egyed');
        parent::__construct($params);
    }

    public function loadVars($t, $termek, $forKarb = false) {
        $tvatc = new termekvaltozatadattipusController($this->params);
        $tkepc = new termekkepController($this->params);
        $x = array();
        if (!$t) {
            $t = new \Entities\TermekValtozat();
            $this->getEm()->detach($t);
            $x['oper'] = 'add';
            $x['id'] = store::createUID();
            $x['termek']['id'] = $termek ? $termek->getId() : null;
            $x['keplista'] = $termek ? $tkepc->getSelectList($termek, NULL) : array();
        }
        else {
            $x['oper'] = 'edit';
            $x['id'] = $t->getId();
            $x['keplista'] = $tkepc->getSelectList($t->getTermek(), $t->getKepid());
        }
        $x['adattipus1id'] = $t->getAdatTipus1Id();
        $x['adattipus1nev'] = $t->getAdatTipus1Nev();
        $x['adattipus1lista'] = $tvatc->getSelectList($t->getAdatTipus1Id());
        $x['ertek1'] = $t->getErtek1();
        $x['adattipus2id'] = $t->getAdatTipus2Id();
        $x['adattipus2nev'] = $t->getAdatTipus2Nev();
        $x['adattipus2lista'] = $tvatc->getSelectList($t->getAdatTipus2Id());
        $x['ertek2'] = $t->getErtek2();
        $x['lathato'] = $t->getLathato();
        $x['lathato2'] = $t->getLathato2();
        $x['lathato3'] = $t->getLathato3();
        $x['lathato4'] = $t->getLathato4();
        $x['elerheto'] = $t->getElerheto();
        $x['elerheto2'] = $t->getElerheto2();
        $x['elerheto3'] = $t->getElerheto3();
        $x['elerheto4'] = $t->getElerheto4();
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
        return $x;
    }

    protected function setFields($obj) {
        $obj->setLathato($this->params->getBoolRequestParam('lathato', false));
        /* MINTA ha nem kell, dobd ki
          $ck=store::getEm()->getRepository('Entities\Termekcimkekat')->find($this->getIntRequestParam('cimkecsoport'));
          if ($ck) {
          $obj->setKategoria($ck);
          }
         */
        return $obj;
    }

    public function getemptyrow() {
        $termek = store::getEm()->getRepository('Entities\Termek')->find($this->params->getIntRequestParam('termekid'));
        $view = $this->createView('termektermekvaltozatkarb.tpl');
        $view->setVar('valtozat', $this->loadVars(null, $termek, true));
        echo $view->getTemplateResult();
    }

    public function delall() {
        $termek = store::getEm()->getRepository('Entities\Termek')->find($this->params->getIntRequestParam('termekid'));
        $valtozatok = $termek->getValtozatok();
        foreach ($valtozatok as $valt) {
            //$termek->removeValtozat($valt);
            $this->getEm()->remove($valt);
        }
        $this->getEm()->flush();
    }

    public function generate() {
        $termek = store::getEm()->getRepository('Entities\Termek')->find($this->params->getIntRequestParam('termekid'));

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
        $lathato = $this->params->getBoolRequestParam('valtozatlathato', false);
        $lathato2 = $this->params->getBoolRequestParam('valtozatlathato2', false);
        $lathato3 = $this->params->getBoolRequestParam('valtozatlathato3', false);
        $lathato4 = $this->params->getBoolRequestParam('valtozatlathato4', false);
        $termekfokep = $this->params->getBoolRequestParam('valtozattermekfokep', false);
        $kepid = $this->params->getIntRequestParam('valtozatkepid');

        if (($adattipus1 && $ertek1) || ($adattipus2 && $ertek2)) {
            $ertekek1 = explode(';', $ertek1);
            $ertekek2 = explode(';', $ertek2);
            $cikkszamok = explode(';', $cikkszam);
            $idegencikkszamok = explode(';', $idegencikkszam);
            $cikl = 0;
            $at1 = $this->getEm()->getRepository('Entities\TermekValtozatAdatTipus')->find($adattipus1);
            $at2 = $this->getEm()->getRepository('Entities\TermekValtozatAdatTipus')->find($adattipus2);
            foreach ($ertekek1 as $ertek1) {
                foreach ($ertekek2 as $ertek2) {
                    $valtdb = 0;
                    $valtozat = new \Entities\TermekValtozat();
                    $termek->addValtozat($valtozat);
                    $valtozat->setLathato($lathato);
                    $valtozat->setLathato2($lathato2);
                    $valtozat->setLathato3($lathato3);
                    $valtozat->setLathato4($lathato4);
                    if ($termek->getNemkaphato()) {
                        $valtozat->setElerheto(false);
                        $valtozat->setElerheto2(false);
                        $valtozat->setElerheto3(false);
                        $valtozat->setElerheto4(false);
                    }
                    else {
                        $valtozat->setElerheto($elerheto);
                        $valtozat->setElerheto2($elerheto2);
                        $valtozat->setElerheto3($elerheto3);
                        $valtozat->setElerheto4($elerheto3);
                    }
                    //					$valtozat->setBrutto($brutto);
                    $valtozat->setNetto($netto);
                    $valtozat->setTermekfokep($termekfokep);
                    if (count($cikkszamok) > 0) {
                        if (count($cikkszamok) == 1) {
                            $valtozat->setCikkszam($cikkszamok[0]);
                        }
                        elseif (array_key_exists($cikl, $cikkszamok)) {
                            $valtozat->setCikkszam($cikkszamok[$cikl]);
                        }
                    }
                    if (count($idegencikkszamok) > 0) {
                        if (count($idegencikkszamok) == 1) {
                            $valtozat->setIdegencikkszam($idegencikkszamok[0]);
                        }
                        elseif (array_key_exists($cikl, $idegencikkszamok)) {
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

                    $kep = $this->getEm()->getRepository('Entities\TermekKep')->find($kepid);
                    if ($kep) {
                        $valtozat->setKep($kep);
                    }

                    if ($valtdb > 0) {
                        $this->getEm()->persist($valtozat);
                    }
                    else {
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
            $result.=$view->getTemplateResult();
        }
        echo $result;
    }

    public function getKeszletByRaktar() {
        $valtozatid = $this->params->getIntRequestParam('valtozatid');
        $valtozat = $this->getRepo()->find($valtozatid);
        $raktarak = $this->getRepo('Entities\Raktar')->getAllActive();
        if ($valtozat) {
            $klist = array();
            foreach($raktarak as $raktar) {
                $klist[] = array(
                    'raktarnev' => $raktar->getNev(),
                    'keszlet' => $valtozat->getKeszlet(null, $raktar->getId())
                );
            }
    		$view = $this->createView('termekkeszletreszletezo.tpl');
            $view->setVar('lista', $klist);
            $view->printTemplateResult();
        }
    }

    public function getValtozatAdatok() {
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
                                'kepurl' => $valt->getKepurl()
                            ];
                        } elseif ($valt->getAdatTipus2Id() == \mkw\store::getParameter(\mkw\consts::ValtozatTipusSzin)) {
                            $vtt[$valt->getErtek2()] = [
                                'value' => $valt->getErtek2(),
                                'type' => $valt->getAdatTipus2Id(),
                                'kepurl' => $valt->getKepurl()
                            ];
                        }
                    }
                }
                $x['szinek'] = $vtt;
                $vtt = array();
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
