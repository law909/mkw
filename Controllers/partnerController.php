<?php

namespace Controllers;

use mkw\store;

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
        $x['lcim'] = $t->getLCim();
        $x['lirszam'] = $t->getLirszam();
        $x['lvaros'] = $t->getLvaros();
        $x['lutca'] = $t->getLutca();
        $x['telefon'] = $t->getTelefon();
        $x['mobil'] = $t->getMobil();
        $x['fax'] = $t->getFax();
        $x['email'] = $t->getEmail();
        $x['honlap'] = $t->getHonlap();
        $x['megjegyzes'] = $t->getMegjegyzes();
        $x['syncid'] = $t->getSyncid();
        $x['cimkek'] = $t->getCimkenevek();
        $x['fizmodnev'] = $t->getFizmodNev();
        $x['uzletkotonev'] = $t->getUzletkotoNev();
        $x['fizhatido'] = $t->getFizhatido();
        $x['szallnev'] = $t->getSzallnev();
        $x['szallirszam'] = $t->getSzallirszam();
        $x['szallvaros'] = $t->getSzallvaros();
        $x['szallutca'] = $t->getSzallutca();
        $x['nem'] = $t->getNem();
        $x['szuletesiido'] = $t->getSzuletesiido();
        $x['szuletesiidostr'] = $t->getSzuletesiidostr();
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
        $x['bizonylatnyelv'] = $t->getBizonylatnyelv();
        $x['ezuzletkoto'] = $t->getEzuzletkoto();
        if ($t->getSzamlatipus() > 0) {
            $afa = $this->getRepo('Entities\Afa')->find(\mkw\Store::getParameter(\mkw\consts::NullasAfa));
            $x['afa'] = $afa->getId();
            $x['afakulcs'] = $afa->getErtek();
        }
        $kedv = array();
        foreach ($t->getTermekcsoportkedvezmenyek() as $tar) {
            $kedv[] = $kedvCtrl->loadVars($tar, true);
        }
        $x['termekcsoportkedvezmenyek'] = $kedv;

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

            $fizmod = store::getEm()->getRepository('Entities\Fizmod')->find($this->params->getIntRequestParam('fizmod', 0));
            if ($fizmod) {
                $obj->setFizmod($fizmod);
            }
            $uk = store::getEm()->getRepository('Entities\Uzletkoto')->find($this->params->getIntRequestParam('uzletkoto', 0));
            if ($uk) {
                $obj->setUzletkoto($uk);
            }
            else {
                $obj->removeUzletkoto();
            }
            $valutanem = store::getEm()->getRepository('Entities\Valutanem')->find($this->params->getIntRequestParam('valutanem', 0));
            if ($valutanem) {
                $obj->setValutanem($valutanem);
            }
            $szallmod = store::getEm()->getRepository('Entities\Szallitasimod')->find($this->params->getIntRequestParam('szallitasimod', 0));
            if ($szallmod) {
                $obj->setSzallitasimod($szallmod);
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

        if ($subject === 'adataim' || $subject === 'minden') {
            $obj->setVezeteknev($this->params->getStringRequestParam('vezeteknev'));
            $obj->setKeresztnev($this->params->getStringRequestParam('keresztnev'));
            if (\mkw\Store::getTheme() === 'mkwcansas' && $subject !== 'minden') {
                $obj->setNev($this->params->getStringRequestParam('vezeteknev') . ' ' . $this->params->getStringRequestParam('keresztnev'));
            }
            $obj->setEmail($this->params->getStringRequestParam('email'));
            $obj->setTelefon($this->params->getStringRequestParam('telefon'));
            $obj->setAkcioshirlevelkell($this->params->getBoolRequestParam('akcioshirlevelkell'));
            $obj->setUjdonsaghirlevelkell($this->params->getBoolRequestParam('ujdonsaghirlevelkell'));

        }

        if ($subject === 'bankiadatok' || $subject === 'minden') {
            $obj->setBanknev($this->params->getStringRequestParam('banknev'));
            $obj->setBankcim($this->params->getStringRequestParam('bankcim'));
            $obj->setIban($this->params->getStringRequestParam('iban'));
            $obj->setSwift($this->params->getStringRequestParam('swift'));
        }

        if ($subject === 'szamlaadatok' || $subject === 'minden') {
            $obj->setNev($this->params->getStringRequestParam('nev'));
            $obj->setAdoszam($this->params->getStringRequestParam('adoszam'));
            $obj->setIrszam($this->params->getStringRequestParam('irszam'));
            $obj->setVaros($this->params->getStringRequestParam('varos'));
            $obj->setUtca($this->params->getStringRequestParam('utca'));
        }

        if ($subject === 'szallitasiadatok' || $subject === 'minden') {
            $obj->setSzallnev($this->params->getStringRequestParam('szallnev'));
            $obj->setSzallirszam($this->params->getStringRequestParam('szallirszam'));
            $obj->setSzallvaros($this->params->getStringRequestParam('szallvaros'));
            $obj->setSzallutca($this->params->getStringRequestParam('szallutca'));
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
        }

        if ($subject === 'registration') {
            $obj->setVezeteknev($this->params->getStringRequestParam('vezeteknev'));
            $obj->setKeresztnev($this->params->getStringRequestParam('keresztnev'));
            if (\mkw\Store::getTheme() === 'mkwcansas') {
                $obj->setNev($this->params->getStringRequestParam('vezeteknev') . ' ' . $this->params->getStringRequestParam('keresztnev'));
            }
            if ($this->params->existsRequestParam('email')) {
                $obj->setEmail($this->params->getStringRequestParam('email'));
            }
            else {
                $obj->setEmail($this->params->getStringRequestParam('kapcsemail'));
            }
            $obj->setJelszo($this->params->getStringRequestParam('jelszo1'));
            $obj->setVendeg(false);
            $obj->setSessionid(\Zend_Session::getId());
            $obj->setIp($_SERVER['REMOTE_ADDR']);
            $obj->setReferrer(Store::getMainSession()->referrer);
        }
        return $obj;
    }

    public function saveRegistrationData($vendeg = false) {
        $ps = $this->getRepo()->findVendegByEmail($this->params->getStringRequestParam('email'));
        if (count($ps) > 0) {
            $t = $ps[0];
        }
        else {
            $t = new \Entities\Partner();
        }
        $this->setFields($t, null, 'registration');
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

        $view->setVar('bizonylatnyelvlist', \mkw\Store::getLocaleSelectList($partner ? $partner->getBizonylatnyelv() : ''));

        $view->setVar('partner', $this->loadVars($partner));
        $view->printTemplateResult();
    }

    public function getSelectList($selid = null, $filter = array()) {
        $rec = $this->getRepo()->getAllForSelectList($filter, array('nev' => 'ASC'));
        $res = array();
        foreach ($rec as $sor) {
            $res[] = array(
                'id' => $sor['id'],
                'caption' => $sor['nev'] . ' ' . $sor['irszam'] . ' ' . $sor['varos'] . ' ' . $sor['utca'],
                'selected' => ($sor['id'] == $selid)
            );
        }
        return $res;
    }

    public function getPartnerData() {
        $partner = $this->getRepo()->find($this->params->getIntRequestParam('partnerid'));
        $ret = array();
        if ($partner) {
            $ret = array(
                'fizmod' => $partner->getFizmodId(),
                'fizhatido' => $partner->getFizhatido(),
                'nev' => $partner->getNev(),
                'vezeteknev' => $partner->getVezeteknev(),
                'keresztnev' => $partner->getKeresztnev(),
                'irszam' => $partner->getIrszam(),
                'varos' => $partner->getVaros(),
                'utca' => $partner->getUtca(),
                'szallnev' => $partner->getSzallnev(),
                'szallirszam' => $partner->getSzallirszam(),
                'szallvaros' => $partner->getSzallvaros(),
                'szallutca' => $partner->getSzallutca(),
                'adoszam' => $partner->getAdoszam(),
                'telefon' => $partner->getTelefon(),
                'email' => $partner->getEmail(),
                'szallitasimod' => $partner->getSzallitasimodId(),
                'valutanem' => $partner->getValutanemId(),
                'uzletkoto' => $partner->getUzletkotoId(),
                'bizonylatnyelv' => $partner->getBizonylatnyelv()
            );
            if ($partner->getSzamlatipus() > 0) {
                $afa = $this->getRepo('Entities\Afa')->find(\mkw\Store::getParameter(\mkw\consts::NullasAfa));
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
                'utca' => $sor->getUtca()
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

    public function login($user, $pass = null) {
        $ok = false;
        if ($user instanceof \Entities\Partner) {
            $ok = true;
        }
        else {
            $users = $this->getRepo()->findByUserPass($user, $pass);
            if (count($users) > 0) {
                $user = $users[0];
                $ok = true;
            }
        }
        if ($ok) {
            if ($user->getVendeg()) {
                return false;
            }
            $kc = new kosarController($this->params);
            $kc->clear($user->getId()); // csak partner alapján
            $oldid = \Zend_Session::getId();
            \Zend_Session::regenerateId();
            \mkw\Store::clearLoggedInUser();
            $user->setSessionid(\Zend_Session::getId());
            $user->setUtolsoklikk();
            $user->clearPasswordreminder();
            $this->getEm()->persist($user);
            store::getMainSession()->pk = $user->getId();
            if (store::isB2B()) {
                if ($user->getEzuzletkoto()) {
                    $uk = $this->getRepo('Entities\Uzletkoto')->find($user->getUzletkotoId());
                    if ($uk) {
                        $uk->setSessionid(\Zend_Session::getId());
                        $this->getEm()->persist($uk);
                        store::getMainSession()->uk = $user->getUzletkotoId();
                        store::getMainSession()->ukpartner = $user->getId();
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
        $user = \mkw\Store::getLoggedInUser();
        if ($user) {
            store::clearLoggedInUser();
            $user->setSessionid('');
            $this->getEm()->persist($user);
            $this->getEm()->flush();
            $kc = new kosarController($this->params);
            $kc->removeSessionId(\Zend_Session::getId());
            store::getMainSession()->pk = null;
            store::getMainSession()->uk = null;
            store::getMainSession()->ukpartner = null;
            store::destroyMainSession();
        }
    }

    public function autologout() {
        $user = \mkw\Store::getLoggedInUser();
        if ($user) {
            $ma = new \DateTime();
            $kul = $ma->diff($user->getUtolsoklikk());
            $kulonbseg = floor(($kul->y * 365 * 24 * 60 * 60 + $kul->m * 30 * 24 * 60 * 60 + $kul->d * 24 * 60 * 60 + $kul->h * 60 * 60 + $kul->i * 60 + $kul->s) / 60);
            $perc = store::getParameter(\mkw\consts::Autologoutmin, 10);
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
        $user = \mkw\Store::getLoggedInUser();
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
                    'fiokurl' => \mkw\Store::getRouter()->generate('showaccount', true),
                    'url' => \mkw\Store::getFullUrl()
                );
                $subject = $this->getTemplateFactory()->createMainView('string:' . $emailtpl->getTargy());
                $subject->setVar('user', $tpldata);
                $body = $this->getTemplateFactory()->createMainView('string:' . $emailtpl->getHTMLSzoveg());
                $body->setVar('user', $tpldata);
                $mailer = \mkw\Store::getMailer();
                $mailer->setTo($email);
                $mailer->setSubject($subject->getTemplateResult());
                $mailer->setMessage($body->getTemplateResult());
                $mailer->send();
            }
            \Zend_Session::writeClose();
            Header('Location: ' . store::getRouter()->generate('showaccount'));
        }
        else {
            $this->showRegistrationForm($vezeteknev, $keresztnev, $email, $hibak);
        }
    }

    public function showRegistrationForm($vezeteknev = '', $keresztnev = '', $email = '', $hibak = array()) {
        $view = $this->getTemplateFactory()->createMainView('regisztracio.tpl');
        $view->setVar('pagetitle', t('Regisztráció') . ' - ' . \mkw\Store::getParameter(\mkw\consts::Oldalcim));
        $view->setVar('vezeteknev', $vezeteknev);
        $view->setVar('keresztnev', $keresztnev);
        $view->setVar('email', $email);
        $view->setVar('hibak', $hibak);
        store::fillTemplate($view);
        $view->printTemplateResult(true);
    }

    public function showLoginForm() {
        if ($this->checkloggedin()) {
            \Zend_Session::writeClose();
            header('Location: ' . store::getRouter()->generate('showaccount'));
        }
        else {
            $view = $this->getLoginTpl();
            store::fillTemplate($view, (\mkw\Store::getTheme() !== 'superzone'));
            $view->setVar('pagetitle', t('Bejelentkezés') . ' - ' . \mkw\Store::getParameter(\mkw\consts::Oldalcim));
            $view->setVar('sikertelen', \mkw\Store::getMainSession()->loginerror);
            \mkw\Store::getMainSession()->loginerror = false;
            $view->printTemplateResult(true);
        }
    }

    public function doLogin() {
        $checkout = $this->params->getStringRequestParam('c') === 'c';
        if ($checkout) {
            $route = store::getRouter()->generate('showcheckout');
        }
        else {
            if (\mkw\Store::mustLogin() && \mkw\Store::getMainSession()->redirafterlogin) {
                $route = \mkw\Store::getMainSession()->redirafterlogin;
                unset(\mkw\Store::getMainSession()->redirafterlogin);
            }
            else {
                $route = store::getRouter()->generate('showaccount');
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
                header('Location: ' . $route);
            }
            else {
                \mkw\Store::clearLoggedInUser();
                if ($checkout) {
                    \mkw\Store::getMainSession()->loginerror = true;
                    header('Location: ' . store::getRouter()->generate('showcheckout'));
                }
                else {
                    \mkw\Store::getMainSession()->loginerror = true;
                    header('Location: ' . store::getRouter()->generate('showlogin'));
                }
            }
        }
    }

    public function doLogout($uri = null) {
        if (!$uri) {
            $prevuri = store::getMainSession()->prevuri;
            if (!$prevuri) {
                $prevuri = '/';
            }
        }
        else {
            $prevuri = $uri;
        }
        if ($this->checkloggedin()) {
            $this->logout();
        }
        Header('Location: ' . $prevuri);
    }

    public function showAccount() {
        $user = $this->getRepo()->getLoggedInUser();
        if ($user) {
            $view = $this->getFiokTpl();
            store::fillTemplate($view);

            $view->setVar('pagetitle', t('Fiók') . ' - ' . \mkw\Store::getParameter(\mkw\consts::Oldalcim));
            $view->setVar('user', $this->loadVars($user)); // fillTemplate-ben megtortenik

            $tec = new termekertesitoController($this->params);
            $view->setVar('ertesitok', $tec->getAllByPartner($user));

            $megrc = new megrendelesfejController($this->params);
            $megrlist = $megrc->getFiokList();
            $view->setVar('megrendeleslist', $megrlist);

            $ptcsk = new partnertermekcsoportkedvezmenyController($this->params);
            $ptcsklist = $ptcsk->getFiokList();
            $view->setVar('discountlist', $ptcsklist);

            $view->printTemplateResult(true);
        }
        else {
            header('Location: ' . store::getRouter()->generate('showlogin'));
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
                    $user = \mkw\Store::getLoggedInUser();
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
                    Header('Location: ' . store::getRouter()->generate('showaccount'));
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
            header('Location: ' . store::getRouter()->generate('showlogin'));
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
                        'fiokurl' => \mkw\Store::getRouter()->generate('showaccount', true),
                        'url' => \mkw\Store::getFullUrl(),
                        'reminder' => \mkw\Store::getRouter()->generate('showpassreminder', true, array(
                            'id' => $pr))
                    );
                    $subject = $this->getTemplateFactory()->createMainView('string:' . $emailtpl->getTargy());
                    $subject->setVar('user', $tpldata);
                    $body = $this->getTemplateFactory()->createMainView('string:' . $emailtpl->getHTMLSzoveg());
                    $body->setVar('user', $tpldata);
                    $mailer = \mkw\Store::getMailer();
                    $mailer->setTo($email);
                    $mailer->setSubject($subject->getTemplateResult());
                    $mailer->setMessage($body->getTemplateResult());
                    $mailer->send();
                }
            }
        }
    }

    public function showPassReminder() {
        $route = store::getRouter()->generate('show404');
        $pr = $this->params->getStringParam('id');
        if ($pr) {
            $partner = $this->getRepo()->findOneByPasswordreminder($pr);
            if ($partner) {
                $tpl = $this->getTemplateFactory()->createMainView('passreminder.tpl');
                \mkw\Store::fillTemplate($tpl);
                $tpl->setVar('reminder', $pr);
                $tpl->printTemplateResult(false);
                return;
            }
        }
        header('Location: ' . $route);
    }

    public function savePassReminder() {
        $route = store::getRouter()->generate('show404');
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
                        $route = \mkw\Store::getRouter()->generate('showaccount');
                    }
                }
            }
        }
        header('Location: ' . $route);
    }

    public function getKiegyenlitetlenBiz() {
        $partnerid = $this->params->getIntRequestParam('partner');
        $bizs = $this->getRepo('Entities\Folyoszamla')->getSumByPartner($partnerid);
        $adat = array();
        foreach($bizs as $biz) {
            if ($biz['hivatkozottdatum']) {
                $datum = $biz['hivatkozottdatum']->format(\mkw\Store::$DateFormat);
            }
            else {
                $datum = '';
            }
            $adat[] = array(
                'bizszam' => $biz['hivatkozottbizonylat'],
                'datum' => $datum,
                'egyenleg' => $biz['egyenleg']
            );
        }
        $view = $this->createView('kiegyenlitetlenselect.tpl');
        $view->setVar('bizonylatok', $adat);
        $ret = array(
            'html' => $view->getTemplateResult()
        );
        echo json_encode($ret);
    }
}
