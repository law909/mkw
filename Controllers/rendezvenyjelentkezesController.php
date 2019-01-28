<?php

namespace Controllers;

class rendezvenyjelentkezesController extends \mkwhelpers\MattableController {

    public function __construct($params) {
        $this->setEntityName('Entities\RendezvenyJelentkezes');
        $this->setKarbFormTplName('rendezvenyjelentkezeskarbform.tpl');
        $this->setKarbTplName('rendezvenyjelentkezeskarb.tpl');
        $this->setListBodyRowTplName('rendezvenyjelentkezeslista_tbody_tr.tpl');
        $this->setListBodyRowVarName('_egyed');
        parent::__construct($params);
    }

    protected function loadVars($t) {
        $x = array();
        if (!$t) {
            $t = new \Entities\RendezvenyJelentkezes();
            $this->getEm()->detach($t);
        }
        $x['id'] = $t->getId();
        $x['megjegyzes'] = $t->getMegjegyzes();
        $x['datum'] = $t->getDatumStr();
        $x['rendezvenynev'] = $t->getRendezvenyNev();
        $x['rendezvenykezdodatum'] = $t->getRendezvenyDatumStr();
        $x['rendezvenytanarnev'] = $t->getRendezvenyTanarNev();
        $x['partnernev'] = $t->getPartnernev();
        $x['partnercim'] = $t->getPartnerCim();
        $x['partneremail'] = $t->getPartneremail();
        $x['partnertelefon'] = $t->getPartnertelefon();
        $x['partnervezeteknev'] = $t->getPartnerVezeteknev();
        $x['partnerkeresztnev'] = $t->getPartnerKeresztnev();
        $x['partnerirszam'] = $t->getPartnerIrszam();
        $x['partnervaros'] = $t->getPartnerVaros();
        $x['partnerutca'] = $t->getPartnerUtca();
        $x['partnerhazszam'] = $t->getPartnerHazszam();

        $x['fizetve'] = $t->getFizetve();
        $x['fizetesdatum'] = $t->getFizetesdatumStr();
        $x['fizetvepenztarnev'] = $t->getFizetvepenztarNev();
        $x['fizetvepenztarbizonylatszam'] = $t->getFizetvepenztarbizonylatszam();
        $x['fizetvebankszamlaszam'] = $t->getFizetvebankszamlaSzam();
        $x['fizetvebankbizonylatszam'] = $t->getFizetvebankbizonylatszam();
        $x['fizetveosszeghuf'] = $t->getFizetveosszeghuf();
        $x['fizmodnev'] = $t->getFizmodNev();

        $x['szamlazva'] = $t->getSzamlazva();
        $x['szamlazasdatum'] = $t->getSzamlazasdatumStr();
        $x['szamlaszam'] = $t->getSzamlaszam();
        $x['szamlazvakelt'] = $t->getSzamlazvakeltStr();
        $x['szamlazvateljesites'] = $t->getSzamlazvateljesitesStr();
        $x['szamlazvaosszeghuf'] = $t->getSzamlazvaosszeghuf();

        $x['lemondva'] = $t->getLemondva();
        $x['lemondasdatum'] = $t->getLemondasdatumStr();
        $x['lemondasoka'] = $t->getLemondasoka();

        $x['visszautalva'] = $t->getVisszautalva();
        $x['visszautalasdatum'] = $t->getVisszautalasdatumStr();
        $x['visszautalaspenztarnev'] = $t->getVisszautalaspenztarNev();
        $x['visszautalaspenztarbizonylatszam'] = $t->getVisszautalaspenztarbizonylatszam();
        $x['visszautalasbankszamlaszam'] = $t->getVisszautalasbankszamlaSzam();
        $x['visszautalasbankbizonylatszam'] = $t->getVisszautalasbankbizonylatszam();
        $x['visszautalasosszeghuf'] = $t->getVisszautalasosszeghuf();
        $x['visszautalasfizmodnev'] = $t->getVisszautalasfizmodNev();

        $x['emailregkoszono'] = $t->getEmailregkoszono();
        $x['emaildijbekero'] = $t->getEmaildijbekero();
        $x['emaildijbekerodatum'] = $t->getEmaildijbekerodatumStr();
        $x['emailrendezvenykezdes'] = $t->getEmailrendezvenykezdes();
        return $x;
    }

    /**
     * @param \Entities\RendezvenyJelentkezes $obj
     * @param $oper
     * @return mixed
     */
    protected function setFields($obj, $oper) {
        $partnerkod = $this->params->getIntRequestParam('partner');

        if ($partnerkod == -1) {
            $partneremail = $this->params->getStringRequestParam('partneremail');
            if ($partneremail) {
                $partnerobj = $this->getRepo('Entities\Partner')->findOneBy(array('email' => $partneremail));
                if (!$partnerobj) {
                    $partnerobj = new \Entities\Partner();
                }
            }
            else {
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
            $partnerobj->setHazszam($this->params->getStringRequestParam('partnerhazszam'));
            $this->getEm()->persist($partnerobj);
        }
        if ($partnerkod > 0) {
            $ck = \mkw\store::getEm()->getRepository('Entities\Partner')->find($partnerkod);
            if ($ck) {
                $obj->setPartner($ck);
            }
        }
        else {
            $obj->setPartner($partnerobj);
        }

        $obj->setMegjegyzes($this->params->getStringRequestParam('megjegyzes'));
        $obj->setDatum($this->params->getStringRequestParam('datum'));
        $ck = \mkw\store::getEm()->getRepository('Entities\Fizmod')->find($this->params->getIntRequestParam('fizmod', 0));
        if ($ck) {
            $obj->setFizmod($ck);
        }
        $ck = \mkw\store::getEm()->getRepository('Entities\Rendezveny')->find($this->params->getIntRequestParam('rendezveny', 0));
        if ($ck) {
            $obj->setRendezveny($ck);
        }
        return $obj;
    }

    public function getlistbody() {
        $view = $this->createView('rendezvenyjelentkezeslista_tbody.tpl');

        $filter = new \mkwhelpers\FilterDescriptor();

        $f = $this->params->getIntRequestParam('idfilter');
        if ($f) {
            $filter->addFilter('id', '=', $f);
        }

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
            $bs = $this->getRepo('Entities\Fizmod')->findOneById($f);
            if ($bs) {
                $filter->addFilter('fizmod', '=', $bs);
            }
        }

        $f = $this->params->getIntRequestParam('rendezvenyfilter');
        if ($f) {
            $bs = $this->getRepo('Entities\Rendezveny')->findOneById($f);
            if ($bs) {
                $filter->addFilter('rendezveny', '=', $bs);
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

        $this->initPager($this->getRepo()->getCount($filter));

        $egyedek = $this->getRepo()->getWithJoins(
            $filter, $this->getOrderArray(), $this->getPager()->getOffset(), $this->getPager()->getElemPerPage());

        echo json_encode($this->loadDataToView($egyedek, 'egyedlista', $view));
    }

    public function getSelectList($selid = null) {
        $rec = $this->getRepo()->getAllForSelectList(array(), array('nev' => 'ASC'));
        $res = array();
        foreach ($rec as $sor) {
            $res[] = array('id' => $sor['id'], 'caption' => $sor['nev'], 'selected' => ($sor['id'] == $selid));
        }
        return $res;
    }

    public function viewselect() {
        $view = $this->createView('rendezvenyjelentkezeslista.tpl');

        $view->setVar('pagetitle', t('Rendezvény jelentkezések'));
        if (!\mkw\store::isPartnerAutocomplete()) {
            $partner = new partnerController($this->params);
            $view->setVar('partnerlist', $partner->getSelectList(($record ? $record->getPartnerId() : 0)));
        }
        $fizmod = new fizmodController($this->params);
        $view->setVar('fizmodlist', $fizmod->getSelectList(($record ? $record->getFizmodId() : 0)));
        $penztar = new penztarController($this->params);
        $view->setVar('penztarlist', $penztar->getSelectList());
        $bankszamla = new bankszamlaController($this->params);
        $view->setVar('bankszamlalist', $bankszamla->getSelectList());
        $rendezveny = new rendezvenyController($this->params);
        $view->setVar('rendezvenylist', $rendezveny->getSelectList(($record ? $record->getRendezvenyId() : 0)));
        $view->printTemplateResult(false);
    }

    public function viewlist() {
        $view = $this->createView('rendezvenyjelentkezeslista.tpl');

        $view->setVar('pagetitle', t('Rendezvény jelentkezések'));
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
        $bankszamla = new bankszamlaController($this->params);
        $view->setVar('bankszamlalist', $bankszamla->getSelectList());
        $jogcim = new jogcimController($this->params);
        $view->setVar('jogcimlist', $jogcim->getSelectList());
        $rendezveny = new rendezvenyController($this->params);
        $view->setVar('rendezvenylist', $rendezveny->getSelectList(($record ? $record->getRendezvenyId() : 0)));
        $view->printTemplateResult(false);
    }

    protected function _getkarb($tplname) {
        $id = $this->params->getRequestParam('id', 0);
        $oper = $this->params->getRequestParam('oper', '');
        $view = $this->createView($tplname);

        $view->setVar('pagetitle', t('Rendezvény jelentkezés'));
        $view->setVar('formaction', '/admin/rendezvenyjelentkezes/save');
        $view->setVar('oper', $oper);
        /** @var \Entities\RendezvenyJelentkezes $record */
        $record = $this->getRepo()->findWithJoins($id);
        $view->setVar('egyed', $this->loadVars($record));
        if (!\mkw\store::isPartnerAutocomplete()) {
            $partner = new partnerController($this->params);
            $view->setVar('partnerlist', $partner->getSelectList(($record ? $record->getPartnerId() : 0)));
        }
        $fizmod = new fizmodController($this->params);
        $view->setVar('fizmodlist', $fizmod->getSelectList(($record ? $record->getFizmodId() : 0)));
        $jogcim = new jogcimController($this->params);
        $view->setVar('jogcimlist', $jogcim->getSelectList());
        $rendezveny = new rendezvenyController($this->params);
        $view->setVar('rendezvenylist', $rendezveny->getSelectList(($record ? $record->getRendezvenyId() : 0)));

        return $view->getTemplateResult();
    }

    public function getar() {
        $id = $this->params->getIntRequestParam('id');
        /** @var \Entities\RendezvenyJelentkezes $rj */
        $rj = $this->getRepo()->find($id);
        if ($rj) {
            /** @var \Entities\Rendezveny $r */
            $r = $rj->getRendezveny();
            if ($r->getAr()) {
                echo json_encode(array('result' => 'ok', 'price' => $r->getAr()));
            }
            else {
                if ($r) {
                    $t = $r->getTermek();
                    $p = $rj->getPartner();
                    if ($t && $p) {
                        $price = $t->getBruttoAr(null, $p);
                        echo json_encode(array('result' => 'ok', 'price' => $price));
                    }
                    else {
                        echo json_encode(array('result' => 'error', 'msg' => at('Nincs termék vagy partner!')));
                    }
                }
                else {
                    echo json_encode(array('result' => 'error', 'msg' => at('Nincs rendezvény!')));
                }
            }
        }
        else {
            echo json_encode(array('result' => 'error', 'msg' => at('Nincs ilyen jelentkezés!')));
        }
    }

    public function getfizetettosszeg() {
        $id = $this->params->getIntRequestParam('id');
        /** @var \Entities\RendezvenyJelentkezes $rj */
        $rj = $this->getRepo()->find($id);
        if ($rj) {
            echo json_encode(array('result' => 'ok', 'price' => $rj->getFizetveosszeghuf()));
        }
        else {
            echo json_encode(array('result' => 'error', 'msg' => at('Nincs ilyen jelentkezés!')));
        }
    }

    public function fizet() {
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
            }
            elseif ($tipus === 'P' && $penztar) {
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

            $emailtpl = $this->getRepo('Entities\Emailtemplate')->find(\mkw\store::getParameter(\mkw\consts::RendezvenySablonFizetesKoszono));
            if ($emailtpl) {
                $tpldata = $r->toLista();
                $subject = \mkw\store::getTemplateFactory()->createMainView('string:' . $emailtpl->getTargy());
                $subject->setVar('jelentkezes', $tpldata);
                $body = \mkw\store::getTemplateFactory()->createMainView('string:' . str_replace('&#39;', '\'', html_entity_decode($emailtpl->getHTMLSzoveg())));
                $body->setVar('jelentkezes', $tpldata);
                if (\mkw\store::getConfigValue('developer')) {
                    \mkw\store::writelog($subject->getTemplateResult(), 'rendezvenyfizeteskoszonoemail.html');
                    \mkw\store::writelog($body->getTemplateResult(), 'rendezvenyfizeteskoszonoemail.html');
                }
                else {
                    $mailer = \mkw\store::getMailer();
                    $mailer->addTo($r->getPartneremail());
                    $mailer->setSubject($subject->getTemplateResult());
                    $mailer->setMessage($body->getTemplateResult());
                    $mailer->send();
                }
            }

            echo json_encode(array('result' => 'ok'));
        }
        else {
            echo json_encode(array('result' => 'error', 'msg' => at('Nem adott meg minden adatot!')));
        }
    }

    public function szamlaz() {
        /** @var \Entities\Rendezvenyjelentkezes $rj */
        $rj = $this->getRepo()->find($this->params->getIntRequestParam('id'));

        $kelt = $this->params->getStringRequestParam('kelt');
        $teljesites = $this->params->getStringRequestParam('teljesites');
        $osszeg = $this->params->getNumRequestParam('osszeg');

        $biztipusstr = $this->params->getStringRequestParam('biztipus');
        switch ($biztipusstr) {
            case 'szamla':
                $biztipus = $this->getRepo('Entities\Bizonylattipus')->find('szamla');
                break;
            case 'egyeb':
                $biztipus = $this->getRepo('Entities\Bizonylattipus')->find('egyeb');
                break;
            default:
                $biztipusstr = null;
                $biztipus = null;
        }
        if ($rj && $biztipus && $kelt && $teljesites && $osszeg) {

            $r = $rj->getRendezveny();

            if ($rj->getFizetvebanktetelid()) {
                /** @var \Entities\Bankbizonylatfej $bankfej */
                $bankfej = $this->getRepo('\Entities\Bankbizonylatfej')->find($rj->getFizetvebankbizonylatszam());
                /** @var \Entities\Bankbizonylattetel $banktetel */
                $banktetel = $this->getRepo('\Entities\Bankbizonylattetel')->find($rj->getFizetvebanktetelid());
                $penztartetel = null;
            }
            else {
                $bankfej = null;
                $banktetel = null;
                /** @var \Entities\Penztarbizonylattetel $penztartetel */
                $penztartetel = $this->getRepo('\Entities\Penztarbizonylattetel')->find($rj->getFizetvepenztartetelid());
            }
            $biz = new \Entities\Bizonylatfej();
            $bt = new \Entities\Bizonylattetel();

            $biz->setBizonylattipus($biztipus);
            $biz->setPersistentData(); // a biz. állandó adatait tölti fel (biz.tip-ból, tulaj adatok)

            $biz->addBizonylattetel($bt);

            $biz->setPartner($rj->getPartner());
            $biz->setFizmod($rj->getFizmod());
            $biz->setKelt($kelt);
            $biz->setTeljesites($teljesites);
            $biz->setEsedekesseg(\mkw\store::calcEsedekesseg($kelt, $rj->getFizmod(), $rj->getPartner()));
            $biz->setValutanem(\mkw\store::getParameter(\mkw\consts::Valutanem));
            $biz->setArfolyam(1);
            if ($banktetel && $bankfej) {
                $biz->setBankszamla($bankfej->getBankszamla());
            }
            $biz->setBelsomegjegyzes(at('Automatikus bizonylat'));
            $biz->setRaktar(\mkw\store::getParameter(\mkw\consts::Raktar));
            $biz->setSzallitasimod(\mkw\store::getParameter(\mkw\consts::Szallitasimod));

            $bt->setPersistentData();
            $bt->setTermek($r->getTermek());
            $bt->setBruttoegysarhuf($osszeg);
            $bt->setBruttoegysar($osszeg);
            $bt->setMennyiseg(1);
            $bt->calc();

            $this->getEm()->persist($biz);
            $this->getEm()->flush($biz);

            $rj->setSzamlazva(true);
            $rj->setSzamlazasdatum();
            $rj->setSzamlaszam($biz->getId());
            $rj->setSzamlazvabizonylattipus($biztipusstr);
            $rj->setSzamlazvakelt($kelt);
            $rj->setSzamlazvateljesites($teljesites);
            $rj->setSzamlazvaosszeghuf($osszeg);

            if ($banktetel) {
                $banktetel->setHivatkozottbizonylat($biz->getId());
                $this->getEm()->persist($banktetel);
            }
            elseif ($penztartetel) {
                $penztartetel->setHivatkozottbizonylat($biz->getId());
                $this->getEm()->persist($penztartetel);
            }

            $this->getEm()->persist($rj);
            $this->getEm()->flush();

            echo json_encode(array('result' => 'ok'));
        }
        else {
            echo json_encode(array('result' => 'error', 'msg' => at('Nem adott meg minden adatot!')));
        }
    }

    public function lemond() {
        /** @var \Entities\RendezvenyJelentkezes $rj */
        $rj = $this->getRepo()->find($this->params->getIntRequestParam('id'));
        $datum = $this->params->getStringRequestParam('datum');
        $ok = $this->params->getStringRequestParam('ok');

        if ($rj && $datum) {

            $rj->setLemondva(true);
            $rj->setLemondasdatum($datum);
            $rj->setLemondasoka($ok);

            $this->getEm()->persist($rj);
            $this->getEm()->flush();

            echo json_encode(array('result' => 'ok'));
        }
        else {
            echo json_encode(array('result' => 'error', 'msg' => at('Nem adott meg minden adatot!')));
        }

    }

    public function visszautal() {
        /** @var \Entities\RendezvenyJelentkezes $rj */
        $rj = $this->getRepo()->find($this->params->getIntRequestParam('id'));

    }

    public function sendDijbekeroEmail() {
        $ret = array('msg' => at('A díjbekérő levél kiküldve.'));
        /** @var \Entities\RendezvenyJelentkezes $jel */
        $jel = $this->getRepo()->find($this->params->getIntRequestParam('id'));
        if ($jel) {
            $emailtpl = $this->getRepo('Entities\Emailtemplate')->find(\mkw\store::getParameter(\mkw\consts::RendezvenySablonDijbekero));
            if ($emailtpl) {
                $tpldata = $jel->toLista();
                $subject = \mkw\store::getTemplateFactory()->createMainView('string:' . $emailtpl->getTargy());
                $subject->setVar('jelentkezes', $tpldata);
                $body = \mkw\store::getTemplateFactory()->createMainView('string:' . str_replace('&#39;', '\'', html_entity_decode($emailtpl->getHTMLSzoveg())));
                $body->setVar('jelentkezes', $tpldata);
                if (\mkw\store::getConfigValue('developer')) {
                    \mkw\store::writelog($subject->getTemplateResult(), 'rendezvenydijbekeroemail.html');
                    \mkw\store::writelog($body->getTemplateResult(), 'rendezvenydijbekeroemail.html');
                }
                else {
                    $mailer = \mkw\store::getMailer();
                    $mailer->addTo($jel->getPartneremail());
                    $mailer->setSubject($subject->getTemplateResult());
                    $mailer->setMessage($body->getTemplateResult());
                    $mailer->send();
                }
                $jel->setEmaildijbekero(true);
                $jel->setEmaildijbekerodatum('');
                $this->getEm()->persist($jel);
                $this->getEm()->flush();
            }
            else {
                $ret['msg'] = at('Díjbekérő levél sablon nem található.');
            }
        }
        else {
            $ret['msg'] = at('A jelentkezés nem található.');
        }
        echo json_encode($ret);
    }

    public function sendKezdesEmail($id = null) {
        $ret = array('msg' => at('A kezdés emlékeztető levél kiküldve.'));
        $kellecho = false;
        if (!$id) {
            $kellecho = true;
            $id = $this->params->getIntRequestParam('id');
        }
        /** @var \Entities\RendezvenyJelentkezes $jel */
        $jel = $this->getRepo()->find($id);
        if ($jel) {
            $emailtpl = $this->getRepo('Entities\Emailtemplate')->find(\mkw\store::getParameter(\mkw\consts::RendezvenySablonKezdesEmlekezteto));
            if ($emailtpl) {
                $tpldata = $jel->toLista();
                $subject = \mkw\store::getTemplateFactory()->createMainView('string:' . $emailtpl->getTargy());
                $subject->setVar('jelentkezes', $tpldata);
                $body = \mkw\store::getTemplateFactory()->createMainView('string:' . str_replace('&#39;', '\'', html_entity_decode($emailtpl->getHTMLSzoveg())));
                $body->setVar('jelentkezes', $tpldata);
                if (\mkw\store::getConfigValue('developer')) {
                    \mkw\store::writelog($subject->getTemplateResult(), 'rendezvenykezdesemail.html');
                    \mkw\store::writelog($body->getTemplateResult(), 'rendezvenykezdesemail.html');
                }
                else {
                    $mailer = \mkw\store::getMailer();
                    $mailer->addTo($jel->getPartneremail());
                    $mailer->setSubject($subject->getTemplateResult());
                    $mailer->setMessage($body->getTemplateResult());
                    $mailer->send();
                }

                $jel->setEmailrendezvenykezdes(true);
                $this->getEm()->persist($jel);
                $this->getEm()->flush();
            }
            else {
                $ret['msg'] = at('Kezdés emlékeztető levél sablon nem található.');
            }
        }
        else {
            $ret['msg'] = at('A jelentkezés nem található.');
        }
        if ($kellecho) {
            echo json_encode($ret);
        }
    }
}