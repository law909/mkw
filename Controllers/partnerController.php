<?php

namespace Controllers;

class partnerController extends \mkwhelpers\MattableController {

    public function __construct($params) {
        $this->setEntityName('Entities\Partner');
        $this->setKarbFormTplName('partnerkarbform.tpl');
        $this->setKarbTplName('partnerkarb.tpl');
        $this->setListBodyRowTplName('partnerlista_tbody_tr.tpl');
        $this->setListBodyRowVarName('_partner');
        parent::__construct($params);
    }

    protected function loadVars($t) {
        $kedvCtrl = new \Controllers\partnertermekcsoportkedvezmenyController($this->params);
        $termekkedvCtrl = new \Controllers\partnertermekkedvezmenyController($this->params);
        $mijszokCtrl = new \Controllers\partnermijszoklevelController($this->params);
        $mijszpuneCtrl = new \Controllers\partnermijszpuneController($this->params);
        $mijszoralatogatasCtrl = new \Controllers\partnermijszoralatogatasController($this->params);
        $mijsztanitasCtrl = new \Controllers\partnermijsztanitasController($this->params);
        $x = array();
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
        $x['euadoszam'] = $t->getEuadoszam();
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
        if ($t->getSzamlatipus() > 0) {
            $afa = $this->getRepo('Entities\Afa')->find(\mkw\store::getParameter(\mkw\consts::NullasAfa));
            if ($afa) {
                $x['afa'] = $afa->getId();
                $x['afakulcs'] = $afa->getErtek();
            }
        }
        $kedv = array();
        foreach ($t->getTermekcsoportkedvezmenyek() as $tar) {
            $kedv[] = $kedvCtrl->loadVars($tar, true);
        }
        $x['termekcsoportkedvezmenyek'] = $kedv;
        $kedv = array();
        foreach ($t->getTermekkedvezmenyek() as $tar) {
            $kedv[] = $termekkedvCtrl->loadVars($tar, true);
        }
        $x['termekkedvezmenyek'] = $kedv;

        if (\mkw\store::isMIJSZ()) {
            $okl = array();
            foreach ($t->getMijszoklevelek() as $tar) {
                $okl[] = $mijszokCtrl->loadVars($tar, true);
            }
            $x['mijszoklevelek'] = $okl;
            $pune = array();
            foreach ($t->getMijszpune() as $tar) {
                $pune[] = $mijszpuneCtrl->loadVars($tar, true);
            }
            $x['mijszpune'] = $pune;
            $oralatogatas = array();
            foreach ($t->getMijszoralatogatas() as $tar) {
                $oralatogatas[] = $mijszoralatogatasCtrl->loadVars($tar, true);
            }
            $x['mijszoralatogatas'] = $oralatogatas;
            $tanitas = array();
            foreach ($t->getMijsztanitas() as $tar) {
                $tanitas[] = $mijsztanitasCtrl->loadVars($tar, true);
            }
            $x['mijsztanitas'] = $tanitas;
        }
        return $x;
    }

    /**
     * @param \Entities\Partner $obj
     * @return \Entities\Partner
     */
    protected function setFields($obj, $parancs, $subject = 'minden') {

        if ($subject === 'minden') {
            $j1 = $this->params->getStringRequestParam('jelszo1');
            $j2 = $this->params->getStringRequestParam('jelszo2');
            if ($j1 && $j2 && $j1 === $j2) {
                $obj->setJelszo($j1);
            }
            $obj->setInaktiv($this->params->getBoolRequestParam('inaktiv'));
            $obj->setIdegenkod($this->params->getStringRequestParam('idegenkod'));
            $obj->setEuadoszam($this->params->getStringRequestParam('euadoszam'));
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

            $fizmod = \mkw\store::getEm()->getRepository('Entities\Fizmod')->find($this->params->getIntRequestParam('fizmod', 0));
            if ($fizmod) {
                $obj->setFizmod($fizmod);
            }
            $uk = \mkw\store::getEm()->getRepository('Entities\Uzletkoto')->find($this->params->getIntRequestParam('uzletkoto', 0));
            if ($uk) {
                $obj->setUzletkoto($uk);
            }
            else {
                $obj->removeUzletkoto();
            }
            $valutanem = \mkw\store::getEm()->getRepository('Entities\Valutanem')->find($this->params->getIntRequestParam('valutanem', 0));
            if ($valutanem) {
                $obj->setValutanem($valutanem);
            }
            $szallmod = \mkw\store::getEm()->getRepository('Entities\Szallitasimod')->find($this->params->getIntRequestParam('szallitasimod', 0));
            if ($szallmod) {
                $obj->setSzallitasimod($szallmod);
            }
            $orszag = \mkw\store::getEm()->getRepository('Entities\Orszag')->find($this->params->getIntRequestParam('orszag', 0));
            if ($orszag) {
                $obj->setOrszag($orszag);
            }
            $partnertipus = \mkw\store::getEm()->getRepository('Entities\Partnertipus')->find($this->params->getIntRequestParam('partnertipus', 0));
            if ($partnertipus) {
                $obj->setPartnertipus($partnertipus);
            }

            if (\mkw\store::isMIJSZ()) {
                $obj->setMijszmiotajogazik($this->params->getIntRequestParam('mijszmiotajogazik'));
                $obj->setMijszmiotatanit($this->params->getIntRequestParam('mijszmiotatanit'));
                $obj->setMijszmembershipbesideshu($this->params->getStringRequestParam('mijszmembershipbesideshu'));
                $obj->setMijszbusiness($this->params->getStringRequestParam('mijszbusiness'));
                $obj->setMijszexporttiltva($this->params->getBoolRequestParam('mijszexporttiltva'));
                $okids = $this->params->getArrayRequestParam('mijszoklevelid');
                foreach ($okids as $okid) {
                    $oper = $this->params->getStringRequestParam('mijszokleveloper_' . $okid);
                    $kibo = $this->getEm()->getRepository('Entities\MIJSZOklevelkibocsajto')->find($this->params->getIntRequestParam('mijszokleveloklevelkibocsajto_' . $okid));
                    $szint = $this->getEm()->getRepository('Entities\MIJSZOklevelszint')->find($this->params->getIntRequestParam('mijszokleveloklevelszint_' . $okid));
                    if ($kibo && $szint) {
                        if ($oper === 'add') {
                            $kedv = new \Entities\PartnerMIJSZOklevel();
                            $kedv->setPartner($obj);
                            $kedv->setMIJSZOklevelkibocsajto($kibo);
                            $kedv->setMIJSZOklevelszint($szint);
                            $kedv->setOklevelev($this->params->getIntRequestParam('mijszokleveloklevelev_' . $okid));
                            $this->getEm()->persist($kedv);
                        }
                        elseif ($oper === 'edit') {
                            $kedv = $this->getEm()->getRepository('Entities\PartnerMIJSZOklevel')->find($okid);
                            if ($kedv) {
                                $kedv->setPartner($obj);
                                $kedv->setMIJSZOklevelkibocsajto($kibo);
                                $kedv->setMIJSZOklevelszint($szint);
                                $kedv->setOklevelev($this->params->getIntRequestParam('mijszokleveloklevelev_' . $okid));
                                $this->getEm()->persist($kedv);
                            }
                        }
                    }
                }
            }

            $obj->removeAllCimke();
            $cimkekpar = $this->params->getArrayRequestParam('cimkek');
            foreach ($cimkekpar as $cimkekod) {
                $cimke = $this->getEm()->getRepository('Entities\Partnercimketorzs')->find($cimkekod);
                if ($cimke) {
                    $obj->addCimke($cimke);
                }
            }
        }

        if ($subject === 'adataim' || $subject === 'pubreg' || $subject === 'minden') {
            $obj->setVezeteknev($this->params->getStringRequestParam('vezeteknev'));
            $obj->setKeresztnev($this->params->getStringRequestParam('keresztnev'));
            if (\mkw\store::getTheme() === 'mkwcansas' && $subject !== 'minden') {
                $obj->setNev($this->params->getStringRequestParam('vezeteknev') . ' ' . $this->params->getStringRequestParam('keresztnev'));
            }
            $obj->setEmail($this->params->getStringRequestParam('email'));
            $obj->setTelefon($this->params->getStringRequestParam('telefon'));
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

        if ($subject === 'pubreg') {
            $cimke = $this->getEm()->getRepository('Entities\Partnercimketorzs')->find(\mkw\store::getParameter(\mkw\consts::FelvetelAlattCimke));
            if ($cimke) {
                $obj->addCimke($cimke);
            }
            $ptipus = $this->getEm()->getRepository('Entities\Partnertipus')->find(\mkw\store::getParameter(\mkw\consts::FelvetelAlattTipus));
            if ($ptipus) {
                $obj->setPartnertipus($ptipus);
            }
        }

        if (\mkw\store::isMIJSZ() && ($subject === 'oklevelek' || $subject === 'minden')) {
            $okids = $this->params->getArrayRequestParam('mijszoklevelid');
            foreach ($okids as $okid) {
                $oper = $this->params->getStringRequestParam('mijszokleveloper_' . $okid);
                $kibo = $this->getEm()->getRepository('Entities\MIJSZOklevelkibocsajto')->find($this->params->getIntRequestParam('mijszokleveloklevelkibocsajto_' . $okid));
                $szint = $this->getEm()->getRepository('Entities\MIJSZOklevelszint')->find($this->params->getIntRequestParam('mijszokleveloklevelszint_' . $okid));
                if ($kibo && $szint) {
                    if ($oper === 'add') {
                        $kedv = new \Entities\PartnerMIJSZOklevel();
                        $kedv->setPartner($obj);
                        $kedv->setMIJSZOklevelkibocsajto($kibo);
                        $kedv->setMIJSZOklevelszint($szint);
                        $kedv->setOklevelev($this->params->getIntRequestParam('mijszokleveloklevelev_' . $okid));
                        $this->getEm()->persist($kedv);
                    }
                    elseif ($oper === 'edit') {
                        $kedv = $this->getEm()->getRepository('Entities\PartnerMIJSZOklevel')->find($okid);
                        if ($kedv) {
                            $kedv->setPartner($obj);
                            $kedv->setMIJSZOklevelkibocsajto($kibo);
                            $kedv->setMIJSZOklevelszint($szint);
                            $kedv->setOklevelev($this->params->getIntRequestParam('mijszokleveloklevelev_' . $okid));
                            $this->getEm()->persist($kedv);
                        }
                    }
                }
            }
        }

        if (\mkw\store::isMIJSZ() && ($subject === 'pune' || $subject === 'minden')) {
            $okids = $this->params->getArrayRequestParam('mijszpuneid');
            foreach ($okids as $okid) {
                $oper = $this->params->getStringRequestParam('mijszpuneoper_' . $okid);
                if ($oper === 'add') {
                    $kedv = new \Entities\PartnerMIJSZPune();
                    $kedv->setPartner($obj);
                    $kedv->setEv($this->params->getIntRequestParam('mijszpuneev_' . $okid));
                    $kedv->setHonap($this->params->getIntRequestParam('mijszpunehonap_' . $okid));
                    $this->getEm()->persist($kedv);
                }
                elseif ($oper === 'edit') {
                    $kedv = $this->getEm()->getRepository('Entities\PartnerMIJSZPune')->find($okid);
                    if ($kedv) {
                        $kedv->setPartner($obj);
                        $kedv->setEv($this->params->getIntRequestParam('mijszpuneev_' . $okid));
                        $kedv->setHonap($this->params->getIntRequestParam('mijszpunehonap_' . $okid));
                        $this->getEm()->persist($kedv);
                    }
                }
            }
        }

        if (\mkw\store::isMIJSZ() && ($subject === 'oralatogatasok' || $subject === 'minden')) {
            $okids = $this->params->getArrayRequestParam('mijszoralatogatasid');
            foreach ($okids as $okid) {
                $oper = $this->params->getStringRequestParam('mijszoralatogatasoper_' . $okid);
                $tanar = $this->getEm()->getRepository('Entities\Partner')->find($this->params->getIntRequestParam('mijszoralatogatastanar_' . $okid));
                if ($tanar || $this->params->getStringRequestParam('mijszoralatogatastanaregyeb_' . $okid)) {
                    if ($oper === 'add') {
                        $kedv = new \Entities\PartnerMIJSZOralatogatas();
                        $kedv->setPartner($obj);
                        $kedv->setTanar($tanar);
                        $kedv->setTanaregyeb($this->params->getStringRequestParam('mijszoralatogatastanaregyeb_' . $okid));
                        $kedv->setHelyszin($this->params->getStringRequestParam('mijszoralatogatashelyszin_' . $okid));
                        $kedv->setMikor($this->params->getStringRequestParam('mijszoralatogatasmikor_' . $okid));
                        $kedv->setOraszam($this->params->getIntRequestParam('mijszoralatogatasoraszam_' . $okid));
                        $kedv->setEv($this->params->getIntRequestParam('mijszoralatogatasev_' . $okid));
                        $this->getEm()->persist($kedv);
                    }
                    elseif ($oper === 'edit') {
                        $kedv = $this->getEm()->getRepository('Entities\PartnerMIJSZOralatogatas')->find($okid);
                        if ($kedv) {
                            $kedv->setPartner($obj);
                            $kedv->setTanar($tanar);
                            $kedv->setTanaregyeb($this->params->getStringRequestParam('mijszoralatogatastanaregyeb_' . $okid));
                            $kedv->setHelyszin($this->params->getStringRequestParam('mijszoralatogatashelyszin_' . $okid));
                            $kedv->setMikor($this->params->getStringRequestParam('mijszoralatogatasmikor_' . $okid));
                            $kedv->setOraszam($this->params->getIntRequestParam('mijszoralatogatasoraszam_' . $okid));
                            $kedv->setEv($this->params->getIntRequestParam('mijszoralatogatasev_' . $okid));
                            $this->getEm()->persist($kedv);
                        }
                    }
                }
            }
        }

        if (\mkw\store::isMIJSZ() && ($subject === 'tanitas' || $subject === 'minden')) {
            $okids = $this->params->getArrayRequestParam('mijsztanitasid');
            foreach ($okids as $okid) {
                $oper = $this->params->getStringRequestParam('mijsztanitasoper_' . $okid);
                $szint = $this->getEm()->getRepository('Entities\MIJSZGyakorlasszint')->find($this->params->getIntRequestParam('mijsztanitasszint_' . $okid));
                if ($szint || $this->params->getStringRequestParam('mijsztanitasszintegyeb_' . $okid)) {
                    if ($oper === 'add') {
                        $kedv = new \Entities\PartnerMIJSZTanitas();
                        $kedv->setPartner($obj);
                        $kedv->setSzint($szint);
                        $kedv->setSzintegyeb($this->params->getStringRequestParam('mijsztanitasszintegyeb_' . $okid));
                        $kedv->setHelyszin($this->params->getStringRequestParam('mijsztanitashelyszin_' . $okid));
                        $kedv->setMikor($this->params->getStringRequestParam('mijsztanitasmikor_' . $okid));
                        $kedv->setNap($this->params->getIntRequestParam('mijsztanitasnap_' . $okid));
                        $this->getEm()->persist($kedv);
                    }
                    elseif ($oper === 'edit') {
                        $kedv = $this->getEm()->getRepository('Entities\PartnerMIJSZTanitas')->find($okid);
                        if ($kedv) {
                            $kedv->setPartner($obj);
                            $kedv->setSzint($szint);
                            $kedv->setSzintegyeb($this->params->getStringRequestParam('mijsztanitasszintegyeb_' . $okid));
                            $kedv->setHelyszin($this->params->getStringRequestParam('mijsztanitashelyszin_' . $okid));
                            $kedv->setMikor($this->params->getStringRequestParam('mijsztanitasmikor_' . $okid));
                            $kedv->setNap($this->params->getIntRequestParam('mijsztanitasnap_' . $okid));
                            $this->getEm()->persist($kedv);
                        }
                    }
                }
            }
        }

        if ($subject === 'bankiadatok' || $subject === 'minden') {
            $obj->setBanknev($this->params->getStringRequestParam('banknev'));
            $obj->setBankcim($this->params->getStringRequestParam('bankcim'));
            $obj->setIban($this->params->getStringRequestParam('iban'));
            $obj->setSwift($this->params->getStringRequestParam('swift'));
        }

        if ($subject === 'szamlaadatok' || $subject === 'pubreg' || $subject === 'minden') {
            $obj->setNev($this->params->getStringRequestParam('nev'));
            $obj->setAdoszam($this->params->getStringRequestParam('adoszam'));
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
                $termekcsoport = $this->getEm()->getRepository('Entities\Termekcsoport')->find($this->params->getIntRequestParam('kedvezmenytermekcsoport_' . $kdid));
                if ($termekcsoport) {
                    if ($oper === 'add') {
                        $kedv = new \Entities\PartnerTermekcsoportKedvezmeny();
                        $kedv->setPartner($obj);
                        $kedv->setTermekcsoport($termekcsoport);
                        $kedv->setKedvezmeny($this->params->getNumRequestParam('kedvezmeny_' . $kdid));
                        $this->getEm()->persist($kedv);
                    }
                    elseif ($oper === 'edit') {
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
                    }
                    elseif ($oper === 'edit') {
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
            if (\mkw\store::getTheme() === 'mkwcansas') {
                $obj->setNev($this->params->getStringRequestParam('vezeteknev') . ' ' . $this->params->getStringRequestParam('keresztnev'));
            }
            $email = $this->params->getStringRequestParam('kapcsemail');
            if ($email) {
                $obj->setEmail($email);
            }
            else {
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
        return $obj;
    }

    public function saveRegistrationData($vendeg = false) {
        $email = $this->params->getStringRequestParam('kapcsemail');
        if (!$email) {
            $email = $this->params->getStringRequestParam('email');
        }
        $ps = $this->getRepo()->findVendegByEmail($email);
        if (count($ps) > 0) {
            $t = $ps[0];
        }
        else {
            $t = new \Entities\Partner();
        }
        $t = $this->setFields($t, null, 'registration');
        $t->setVendeg($vendeg);
        $this->getEm()->persist($t);
        $this->getEm()->flush();
        return $t;
    }

    public function getlistbody() {
        $view = $this->createView('partnerlista_tbody.tpl');

        $filter = new \mkwhelpers\FilterDescriptor();
        if (!is_null($this->params->getRequestParam('nevfilter', NULL))) {
            $fv = $this->params->getStringRequestParam('nevfilter');
            $filter->addFilter(array('nev','keresztnev','vezeteknev','szallnev'), 'LIKE', '%' . $fv . '%');
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
        $f = $this->params->getNumRequestParam('beszallitofilter',9);
        if ($f != 9) {
            $filter->addFilter('szallito', '=', $f);
        }
        if (!is_null($this->params->getRequestParam('partnertipusfilter', null))) {
            $filter->addFilter('partnertipus' , '=', $this->params->getIntRequestParam('partnertipusfilter'));
        }
        if (!is_null($this->params->getRequestParam('orszagfilter', null))) {
            $filter->addFilter('orszag' , '=', $this->params->getIntRequestParam('orszagfilter'));
        }
        if (!is_null($this->params->getRequestParam('cimkefilter', NULL))) {
            $fv = $this->params->getArrayRequestParam('cimkefilter');
            $cimkekodok = implode(',', $fv);
            if ($cimkekodok) {
                $q = $this->getEm()->createQuery('SELECT p.id FROM Entities\Partnercimketorzs pc JOIN pc.partnerek p WHERE pc.id IN (' . $cimkekodok . ')');
                $res = $q->getScalarResult();
                $cimkefilter = array();
                foreach ($res as $sor) {
                    $cimkefilter[] = $sor['id'];
                }
                $filter->addFilter('id', 'IN', $cimkefilter);
            }
        }

        $this->initPager($this->getRepo()->getCount($filter));

        $egyedek = $this->getRepo()->getWithJoins(
                $filter, $this->getOrderArray(), $this->getPager()->getOffset(), $this->getPager()->getElemPerPage());

        echo json_encode($this->loadDataToView($egyedek, 'partnerlista', $view));
    }

    public function viewlist() {
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
        $view->printTemplateResult();
    }

    public function _getkarb($tplname) {
        $id = $this->params->getRequestParam('id', 0);
        $oper = $this->params->getRequestParam('oper', '');
        $view = $this->createView($tplname);

        $view->setVar('pagetitle', t('Partner'));
        $view->setVar('oper', $oper);

        $partner = $this->getRepo()->findWithJoins($id);
        $view->setVar('szamlatipuslist', $this->getRepo()->getSzamlatipusList(($partner ? $partner->getSzamlatipus() : 0)));
        // loadVars utan nem abc sorrendben adja vissza
        $tcc = new partnercimkekatController($this->params);
        $cimkek = $partner ? $partner->getCimkek() : null;
        $view->setVar('cimkekat', $tcc->getWithCimkek($cimkek));
        $fizmod = new fizmodController($this->params);
        $view->setVar('fizmodlist', $fizmod->getSelectList(($partner ? $partner->getFizmodId() : 0)));
        $uk = new uzletkotoController($this->params);
        $view->setVar('uzletkotolist', $uk->getSelectList(($partner ? $partner->getUzletkotoId() : 0)));
        $valutanem = new valutanemController($this->params);
        $view->setVar('valutanemlist', $valutanem->getSelectList(($partner ? $partner->getValutanemId() : 0)));
        $termekar = new termekarController($this->params);
        $view->setVar('termekarazonositolist', $termekar->getSelectList(($partner ? $partner->getTermekarazonosito() : '')));
        $szallmod = new szallitasimodController($this->params);
        $view->setVar('szallitasimodlist', $szallmod->getSelectList(($partner ? $partner->getSzallitasimodId() : 0)));
        $orszag = new orszagController($this->params);
        $view->setVar('orszaglist', $orszag->getSelectList(($partner ? $partner->getOrszagId() : 0), true));
        $partnertipus = new partnertipusController($this->params);
        $view->setVar('partnertipuslist', $partnertipus->getSelectList(($partner ? $partner->getPartnertipusId() : 0)));

        $view->setVar('bizonylatnyelvlist', \mkw\store::getLocaleSelectList($partner ? $partner->getBizonylatnyelv() : ''));

        $view->setVar('partner', $this->loadVars($partner));
        $view->printTemplateResult();
    }

    public function getSelectList($selid = null, $filter = array()) {
        $rec = $this->getRepo()->getAllForSelectList($filter, array('nev' => 'ASC'));
        $res = array();
        foreach ($rec as $sor) {
            $res[] = array(
                'id' => $sor['id'],
                'caption' => $sor['nev'] . ' ' . $sor['irszam'] . ' ' . $sor['varos'] . ' ' . $sor['utca'] . ' ' . $sor['hazszam'],
                'nev' => $sor['nev'],
                'email' => $sor['email'],
                'selected' => ($sor['id'] == $selid)
            );
        }
        return $res;
    }

    public function getBizonylatfejSelectList() {
        $term = trim($this->params->getStringRequestParam('term'));
        $ret = array();
        if ($term) {
            $r = \mkw\store::getEm()->getRepository('Entities\Partner');
            $res = $r->getBizonylatfejLista($term);

            $afa = $this->getRepo('Entities\Afa')->find(\mkw\store::getParameter(\mkw\consts::NullasAfa));
            $partnerafa = $afa->getId();
            $partnerafakulcs = $afa->getErtek();

            foreach ($res as $r) {
                $x = array(
                    'id' => $r['id'],
                    'value' => $r['nev'] . ' ' . \mkw\store::implodeCim($r['irszam'], $r['varos'], $r['utca'], $r['hazszam']) . ' (' . $r['email'] . ')'
                );
                if ($r['szamlatipus'] > 0) {
                    $x['afa'] = $partnerafa;
                    $x['afakulcs'] = $partnerafakulcs;
                }
                $ret[] = $x;
            }
        }
        echo json_encode($ret);
    }

    public function getPartnerData() {
        $pid = $this->params->getIntRequestParam('partnerid');
        $email = $this->params->getStringRequestParam('email');
        if ($pid) {
            $partner = $this->getRepo()->find($pid);
        }
        elseif ($email) {
            $partner = $this->getRepo()->findOneBy(array('email' => $email));
        }
        $ret = array();
        if ($partner) {
            $ret = array(
                'id' => $partner->getId(),
                'fizmod' => $partner->getFizmodId(),
                'fizhatido' => $partner->getFizhatido(),
                'nev' => $partner->getNev(),
                'vezeteknev' => $partner->getVezeteknev(),
                'keresztnev' => $partner->getKeresztnev(),
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
                'telefon' => $partner->getTelefon(),
                'email' => $partner->getEmail(),
                'szallitasimod' => $partner->getSzallitasimodId(),
                'valutanem' => $partner->getValutanemId(),
                'uzletkoto' => $partner->getUzletkotoId(),
                'bizonylatnyelv' => $partner->getBizonylatnyelv()
            );
            if ($partner->getSzamlatipus() > 0) {
                $afa = $this->getRepo('Entities\Afa')->find(\mkw\store::getParameter(\mkw\consts::NullasAfa));
                $ret['afa'] = $afa->getId();
                $ret['afakulcs'] = $afa->getErtek();
            }
        }
        echo json_encode($ret);
    }

    public function getSzallitoSelectList($selid) {
        $filter = new \mkwhelpers\FilterDescriptor();
        $filter->addFilter('szallito', '=', true);
        $rec = $this->getRepo()->getAll($filter, array('nev' => 'ASC'));
        $res = array();
        foreach ($rec as $sor) {
            $res[] = array(
                'id' => $sor->getId(),
                'caption' => $sor->getNev(),
                'selected' => ($sor->getId() == $selid),
                'fizmod' => $sor->getFizmodId(),
                'fizhatido' => $sor->getFizhatido(),
                'irszam' => $sor->getIrszam(),
                'varos' => $sor->getVaros(),
                'utca' => $sor->getUtca(),
                'hazszam' => $sor->getHazszam()
            );
        }
        return $res;
    }

    public function checkemail() {
        $email = $this->params->getStringRequestParam('email');
        $ret = array();
        $ret['hibas'] = !\Zend_Validate::is($email, 'EmailAddress');
        if (!$ret['hibas']) {
            if (!$this->params->getBoolRequestParam('dce')) {
                $ret['hibas'] = $this->getRepo()->countByEmail($email) > 0;
                if ($ret['hibas']) {
                    $ret['uzenet'] = t('Már létezik ez az emailcím.');
                }
            }
        }
        else {
            $ret['uzenet'] = t('Kérjük emailcímet adjon meg.');
        }
        echo json_encode($ret);
    }

    public function getFiokTpl() {
        $view = $this->getTemplateFactory()->createMainView('fiok.tpl');
        return $view;
    }

    public function getLoginTpl() {
        $view = $this->getTemplateFactory()->createMainView('login.tpl');
        return $view;
    }

    public function showPubRegistration() {
        $view = $this->getTemplateFactory()->createMainView('pubregistration.tpl');
        \mkw\store::fillTemplate($view);
        $view->printTemplateResult(true);
    }

    public function showPubRegistrationThx() {
        $view = $this->getTemplateFactory()->createMainView('pubregistrationthx.tpl');
        \mkw\store::fillTemplate($view);
        $view->printTemplateResult(true);
    }

    public function login($puser, $pass = null) {
        $ok = false;
        if ($puser instanceof \Entities\Partner) {
            $user = $puser;
            $ok = true;
        }
        else {
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

    public function logout() {
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

    public function autologout() {
        $user = \mkw\store::getLoggedInUser();
        if ($user) {
            $ma = new \DateTime();
            $kul = $ma->diff($user->getUtolsoklikk());
            $kulonbseg = floor(($kul->y * 365 * 24 * 60 * 60 + $kul->m * 30 * 24 * 60 * 60 + $kul->d * 24 * 60 * 60 + $kul->h * 60 * 60 + $kul->i * 60 + $kul->s) / 60);
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

    public function setUtolsoKlikk() {
        $user = \mkw\store::getLoggedInUser();
        if ($user) {
            $user->setUtolsoKlikk();
            $this->getEm()->persist($user);
            $this->getEm()->flush();
        }
    }

    public function checkloggedin() {
        return $this->getRepo()->checkloggedin();
    }

    public function saveRegistration() {
        $hibas = false;
        $hibak = array();

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
            }
            else {
                $t = new \Entities\Partner();
            }
            $t = $this->setFields($t, 'add', 'registration');
            $this->getEm()->persist($t);
            $this->getEm()->flush();
            $this->login($email, $jelszo1);
            $emailtpl = $this->getEm()->getRepository('Entities\Emailtemplate')->findOneByNev('regisztracio');
            if ($emailtpl) {
                $tpldata = array(
                    'keresztnev' => $keresztnev,
                    'vezeteknev' => $vezeteknev,
                    'fiokurl' => \mkw\store::getRouter()->generate('showaccount', true),
                    'url' => \mkw\store::getFullUrl()
                );
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
            \Zend_Session::writeClose();
            Header('Location: ' . \mkw\store::getRouter()->generate('showaccount'));
        }
        else {
            $this->showRegistrationForm($vezeteknev, $keresztnev, $email, $hibak);
        }
    }

    public function showRegistrationForm($vezeteknev = '', $keresztnev = '', $email = '', $hibak = array()) {
        $view = $this->getTemplateFactory()->createMainView('regisztracio.tpl');
        $view->setVar('pagetitle', t('Regisztráció') . ' - ' . \mkw\store::getParameter(\mkw\consts::Oldalcim));
        $view->setVar('vezeteknev', $vezeteknev);
        $view->setVar('keresztnev', $keresztnev);
        $view->setVar('email', $email);
        $view->setVar('hibak', $hibak);
        \mkw\store::fillTemplate($view);
        $view->printTemplateResult(true);
    }

    public function showLoginForm() {
        if ($this->checkloggedin()) {
            \Zend_Session::writeClose();
            header('Location: ' . \mkw\store::getRouter()->generate('showaccount'));
        }
        else {
            $view = $this->getLoginTpl();
            \mkw\store::fillTemplate($view, (!\mkw\store::isSuperzoneB2B()));
            $view->setVar('pagetitle', t('Bejelentkezés') . ' - ' . \mkw\store::getParameter(\mkw\consts::Oldalcim));
            $view->setVar('sikertelen', \mkw\store::getMainSession()->loginerror);
            \mkw\store::getMainSession()->loginerror = false;
            $view->printTemplateResult(true);
        }
    }

    public function doLogin() {
        $checkout = $this->params->getStringRequestParam('c') === 'c';
        if ($checkout) {
            $route = \mkw\store::getRouter()->generate('showcheckout');
        }
        else {
            if (\mkw\store::mustLogin() && \mkw\store::getMainSession()->redirafterlogin) {
                $route = \mkw\store::getMainSession()->redirafterlogin;
                unset(\mkw\store::getMainSession()->redirafterlogin);
            }
            else {
                $route = \mkw\store::getRouter()->generate('showaccount');
            }
        }
        if ($this->checkloggedin()) {
//			\Zend_Session::writeClose();
            header('Location: ' . $route);
        }
        else {
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
                header('Location: ' . $route);
            }
            else {
                \mkw\store::clearLoggedInUser();
                $mc = new mainController($this->params);
                $mc->clearOrszag();
                if ($checkout) {
                    \mkw\store::getMainSession()->loginerror = true;
                    header('Location: ' . \mkw\store::getRouter()->generate('showcheckout'));
                }
                else {
                    \mkw\store::getMainSession()->loginerror = true;
                    header('Location: ' . \mkw\store::getRouter()->generate('showlogin'));
                }
            }
        }
    }

    public function doLogout($uri = null) {
        if (!$uri) {
            $prevuri = \mkw\store::getMainSession()->prevuri;
            if (!$prevuri) {
                $prevuri = '/';
            }
        }
        else {
            $prevuri = $uri;
        }
        if ($this->checkloggedin()) {
            $this->logout();
            $mc = new mainController($this->params);
            $mc->clearOrszag();
        }
        Header('Location: ' . $prevuri);
    }

    public function showAccount() {
        $user = $this->getRepo()->getLoggedInUser();
        if ($user) {
            $view = $this->getFiokTpl();
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

            $ptcsk = new partnertermekcsoportkedvezmenyController($this->params);
            $ptcsklist = $ptcsk->getFiokList();
            $view->setVar('discountlist', $ptcsklist);
            $view->printTemplateResult(true);
        }
        else {
            header('Location: ' . \mkw\store::getRouter()->generate('showlogin'));
        }
    }

    /**
     * @param $subject
     * @param \Entities\Partner|null $user
     * @return array
     */
    public function checkPartnerData($subject) {
        $ret = array();
        $hibas = false;
        $hibak = array();
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
                }
                else {
                    $hibak['regijelszo'] = t('Rossz régi jelszót adott meg');
                    $hibak['hibas'] = 2;
                }
                break;
            case 'b2bregistration':
                break;
        }
        $ret = array(
            'hibas' => $hibas,
            'hibak' => $hibak
        );
        return $ret;
    }

    public function saveAccount() {
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
                }
                else {
                    echo json_encode($hiba['hibak']);
                }
            }
            else {
                if ($jax) {
                    echo json_encode($hiba['hibak']);
                }
                else {
                    echo $hiba['hibak'];
                }
            }
        }
        else {
            header('Location: ' . \mkw\store::getRouter()->generate('showlogin'));
        }
    }

    public function savePubRegistration() {
        $user = new \Entities\Partner();
        $subject = 'pubreg';

        $hiba = $this->checkPartnerData($subject);
        if (!$hiba['hibas']) {
            $user = $this->setFields($user, 'edit', $subject);
            $this->getEm()->persist($user);
            $this->getEm()->flush();
            Header('Location: ' . \mkw\store::getRouter()->generate('pubregistrationthx'));
        }
        else {
            echo $hiba['hibak'];
        }
    }

    public function createPassReminder() {
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
                    $tpldata = array(
                        'keresztnev' => $p->getKeresztnev(),
                        'vezeteknev' => $p->getVezeteknev(),
                        'fiokurl' => \mkw\store::getRouter()->generate('showaccount', true),
                        'url' => \mkw\store::getFullUrl(),
                        'reminder' => \mkw\store::getRouter()->generate('showpassreminder', true, array(
                            'id' => $pr))
                    );
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

    public function showPassReminder() {
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

    public function savePassReminder() {
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

    public function getKiegyenlitetlenBiz() {
        $partnerid = $this->params->getIntRequestParam('partner');
        $irany = $this->params->getIntRequestParam('irany', 1);
        $bizs = $this->getRepo('Entities\Folyoszamla')->getSumByPartner($partnerid, $irany);
        $adat = array();
        foreach($bizs as $biz) {
            if ($biz['hivatkozottdatum']) {
                $datum = $biz['hivatkozottdatum']->format(\mkw\store::$DateFormat);
            }
            else {
                $datum = '';
            }
            $adat[] = array(
                'bizszam' => $biz['hivatkozottbizonylat'],
                'datum' => $datum,
                'egyenleg' => $biz['egyenleg'] * 1 * $irany,
                'fizmod' => $biz['fizmodnev']
            );
        }
        $view = $this->createView('kiegyenlitetlenselect.tpl');
        $view->setVar('bizonylatok', $adat);
        $ret = array(
            'html' => $view->getTemplateResult()
        );
        echo json_encode($ret);
    }

    public function mijszExport() {
        function x($o, $sor) {
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

        $partnerek = $this->getRepo()->getAll($filter, array('keresztnev' => 'ASC', 'vezeteknev' => 'ASC'));

        $o = 0;
        $excel = new \PHPExcel();
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
                }
                else {
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
        $writer = \PHPExcel_IOFactory::createWriter($excel, 'Excel2007');

        switch ($country) {
            case 'in':
                $filepath = uniqid('mijszpartner_in') . '.xlsx';
                break;
            case 'us':
                $filepath = uniqid('mijszpartner_us') . '.xlsx';
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

    public function megjegyzesExport() {
        function x($o, $sor) {
            return \mkw\store::getExcelCoordinate($o, $sor);
        }

        $ids = $this->params->getStringRequestParam('ids');

        $filter = new \mkwhelpers\FilterDescriptor();
        if ($ids) {
            $filter->addFilter('id', 'IN', explode(',', $ids));
        }

        $partnerek = $this->getRepo()->getAll($filter, array('vezeteknev' => 'ASC', 'keresztnev' => 'ASC'));

        $o = 0;
        $excel = new \PHPExcel();
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
        $writer = \PHPExcel_IOFactory::createWriter($excel, 'Excel2007');

        $filepath = uniqid('partnermegjegyzes') . '.xlsx';
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

    public function roadrecordExport() {
        $filepath = uniqid('roadrecordpartner') . '.csv';
        $csv = fopen($filepath, 'w');

        $fej = array(
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
        );
        fwrite($csv, implode(';', $fej) . "\n");

        $ids = $this->params->getStringRequestParam('ids');

        $filter = new \mkwhelpers\FilterDescriptor();
        if ($ids) {
            $filter->addFilter('id', 'IN', explode(',', $ids));
        }

        $partnerek = $this->getRepo()->getAll($filter, array('nev' => 'ASC'));
        if ($partnerek) {

            /** @var \Entities\Partner $partner */
            foreach ($partnerek as $partner) {
                $sor = array(
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
                );
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

}
