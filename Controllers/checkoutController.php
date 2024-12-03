<?php

namespace Controllers;

use Entities\Bizonylatfej;
use Entities\Bizonylatstatusz;
use Entities\Fizmod;
use Entities\Kosar;
use Entities\Kupon;
use Entities\SzallitasimodFizmodNovelo;
use mkw\store;

class checkoutController extends \mkwhelpers\MattableController
{

    public function __construct($params)
    {
        $this->setEntityName(Kosar::class);
        parent::__construct($params);
    }

    private function vv($a, $b)
    {
        if ($a) {
            return $a;
        }
        return $b;
    }

    public function getCheckout()
    {
        $p = \mkw\store::getMainSession()->params;
        if (!$p) {
            $p = new \mkwhelpers\ParameterHandler([]);
        }
        \mkw\store::getMainSession()->params = false;

        $view = \mkw\store::getTemplateFactory()->createMainView('checkout.tpl');
        \mkw\store::fillTemplate($view, false);
        $view->setVar('checkout', true);

        $partner = \mkw\store::getLoggedInUser();
        if ($partner) {
            $valu = $partner->getValutanemId();
        } else {
            $valu = \mkw\store::getMainValutanemId();
        }
        if (!$valu) {
            $valu = \mkw\store::getSetupValue(\mkw\consts::Valutanem);
        }

        $kr = $this->getRepo(Kosar::class);
        $sorok = $kr->getDataBySessionId(\Zend_Session::getId());
        $sum = 0;
        /** @var \Entities\Kosar $sor */
        foreach ($sorok as $sor) {
            $sum = $sum + $sor->getBruttoegysar() * $sor->getMennyiseg();
        }

        $szm = new szallitasimodController($this->params);
        $szlist = $szm->getSelectList(null, false, $valu, $sum);

        $u = \mkw\store::getLoggedInUser();
        if ($u) {
            $user['nev'] = $u->getNev();
            $user['email'] = $u->getEmail();
            $user['vezeteknev'] = $u->getVezeteknev();
            $user['keresztnev'] = $u->getKeresztnev();
            $user['telefon'] = $u->getTelefon();
            $user['telszam'] = $u->getTelszam();
            $user['irszam'] = $u->getIrszam();
            $user['varos'] = $u->getVaros();
            $user['utca'] = $u->getUtca();
            $user['adoszam'] = $u->getAdoszam();
            $user['orszag'] = $u->getOrszagId();
            $user['szallnev'] = $u->getSzallnev();
            $user['szallirszam'] = $u->getSzallirszam();
            $user['szallvaros'] = $u->getSzallvaros();
            $user['szallutca'] = $u->getSzallutca();
            $user['szallorszag'] = $u->getSzallorszagId();
            $user['szalladategyezik'] = !$u->getNev() &&
                !$u->getIrszam() &&
                !$u->getVaros() &&
                !$u->getUtca() &&
                !$u->getNev();
            $user['akcioshirlevelkell'] = $u->getAkcioshirlevelkell();
            $user['ujdonsaghirlevelkell'] = $u->getUjdonsaghirlevelkell();
            $view->setVar('partnerszallitasimod', $u->getSzallitasimodNev());
            $view->setVar('partnerszallitasimodid', $u->getSzallitasimodId());
            $view->setVar('partnerfizetesimod', $u->getFizmodNev());
        } else {
            $user['nev'] = '';
            $user['email'] = '';
            $user['vezeteknev'] = '';
            $user['keresztnev'] = '';
            $user['telefon'] = '';
            $user['telszam'] = '';
            $user['orszag'] = \mkw\store::getMainSession()->orszag;
            $user['irszam'] = '';
            $user['varos'] = '';
            $user['utca'] = '';
            $user['adoszam'] = '';
            $user['szallnev'] = '';
            $user['szallirszam'] = '';
            $user['szallvaros'] = '';
            $user['szallutca'] = '';
            $user['szallorszag'] = \mkw\store::getMainSession()->orszag;
            $user['szalladategyezik'] = true;
            $user['akcioshirlevelkell'] = false;
            $user['ujdonsaghirlevelkell'] = false;
            $view->setVar('partnerszallitasimod', '');
            $view->setVar('partnerfizetesimod', '');
        }

        $view->setVar('szallitasimodlist', $szlist);
        $view->setVar('showerror', \mkw\store::getMainSession()->loginerror);
        $view->setVar('checkouterrors', \mkw\store::getMainSession()->checkoutErrors);
        $view->setVar('showaszflink', \mkw\store::getRouter()->generate('showstatlappopup', false, ['lap' => 'aszf']));
        $view->setVar('regkell', $p->getIntRequestParam('regkell', 2));
        $view->setVar('vezeteknev', $this->vv($p->getStringRequestParam('vezeteknev'), $user['vezeteknev']));
        $view->setVar('keresztnev', $this->vv($p->getStringRequestParam('keresztnev'), $user['keresztnev']));
        $view->setVar('telefon', $this->vv($p->getStringRequestParam('telefon'), $user['telefon']));
        $view->setVar('telszam', $this->vv($p->getStringRequestParam('telszam'), $user['telszam']));
        $view->setVar('jelszo1', $p->getStringRequestParam('jelszo1'));
        $view->setVar('jelszo2', $p->getStringRequestParam('jelszo2'));
        $view->setVar('email', $this->vv($p->getStringRequestParam('kapcsemail'), $user['email']));
        $view->setVar('szamlanev', $this->vv($p->getStringRequestParam('szamlanev'), $user['nev']));
        $view->setVar('szamlairszam', $this->vv($p->getStringRequestParam('szamlairszam'), $user['irszam']));
        $view->setVar('szamlavaros', $this->vv($p->getStringRequestParam('szamlavaros'), $user['varos']));
        $view->setVar('szamlautca', $this->vv($p->getStringRequestParam('szamlautca'), $user['utca']));
        $view->setVar('adoszam', $this->vv($p->getStringRequestParam('adoszam'), $user['adoszam']));
        $view->setVar('szamlaeqszall', $this->vv($p->getBoolRequestParam('szamlaeqszall'), $user['szalladategyezik']));
        $view->setVar('orszag', $this->vv($p->getIntRequestParam('orszag'), $user['orszag']));
        $view->setVar('szallnev', $this->vv($p->getStringRequestParam('szallnev'), $user['szallnev']));
        $view->setVar('szallirszam', $this->vv($p->getStringRequestParam('szallirszam'), $user['szallirszam']));
        $view->setVar('szallvaros', $this->vv($p->getStringRequestParam('szallvaros'), $user['szallvaros']));
        $view->setVar('szallutca', $this->vv($p->getStringRequestParam('szallutca'), $user['szallutca']));
        $view->setVar('szallorszag', $this->vv($p->getIntRequestParam('szallorszag'), $user['szallorszag']));
        $view->setVar('szallitasimod', $p->getIntRequestParam('szallitasimod'));
        $view->setVar('fizetesimod', $p->getIntRequestParam('fizetesimod'));
        $view->setVar('webshopmessage', $p->getStringRequestParam('webshopmessage'));
        $view->setVar('couriermessage', $p->getStringRequestParam('couriermessage'));
        $view->setVar('aszfready', $p->getBoolRequestParam('aszfready'));
        $view->setVar('akciohirlevel', $this->vv($p->getBoolRequestParam('akciohirlevel'), $user['akcioshirlevelkell']));
        $view->setVar('ujdonsaghirlevel', $this->vv($p->getBoolRequestParam('ujdonsaghirlevel'), $user['ujdonsaghirlevelkell']));
        $oc = new orszagController($p);
        $view->setVar('orszaglist', $oc->getSelectList($this->vv($p->getIntRequestParam('orszag'), $user['orszag'])));
        $view->setVar('szallorszaglist', $oc->getSelectList($this->vv($p->getIntRequestParam('szallorszag'), $user['szallorszag'])));
        $telkorzetc = new korzetszamController($this->params);
        $view->setVar('telkorzetlist', $telkorzetc->getSelectList($this->vv($p->getStringRequestParam('telkorzet'), $user['telkorzet'])));
        \mkw\store::getMainSession()->loginerror = false;
        \mkw\store::getMainSession()->checkoutErrors = false;
        $view->printTemplateResult(false);
    }

    public function getSzallmodFizmodList()
    {
        $partner = \mkw\store::getLoggedInUser();
        if ($partner) {
            $valu = $partner->getValutanemId();
        } else {
            $valu = \mkw\store::getMainSession()->valutanem;
        }

        $kr = $this->getRepo(Kosar::class);
        $sorok = $kr->getDataBySessionId(\Zend_Session::getId());
        $sum = 0;
        /** @var \Entities\Kosar $sor */
        foreach ($sorok as $sor) {
            $sum = $sum + $sor->getBruttoegysar() * $sor->getMennyiseg();
        }

        $ret = [];
        $fr = $this->getRepo(Fizmod::class);
        $fc = new fizmodController($this->params);
        $szm = new szallitasimodController($this->params);
        $szlist = $szm->getSelectList(null, false, $valu, $sum);
        foreach ($szlist as $szallmod) {
            $fmlist = explode(',', $szallmod['fizmodok']);
            $fmarr = [];
            foreach ($fmlist as $fmid) {
                $fizmod = $fr->find($fmid);
                if ($fizmod) {
                    $fmarr[] = $fc->loadVars($fizmod, true);
                }
            }
            $szallmod['fizmodlist'] = $fmarr;
            $ret[] = $szallmod;
        }
        echo json_encode($ret);
    }

    public function getFizmodList()
    {
        $kosarrepo = $this->getRepo(Kosar::class);
        $krepo = $this->getRepo(SzallitasimodFizmodNovelo::class);
        $view = \mkw\store::getTemplateFactory()->createMainView('checkoutfizmodlist.tpl');
        $fm = new fizmodController($this->params);
        $szm = $this->params->getIntRequestParam('szallitasimod');
        $szlist = $fm->getSelectList(null, $szm);
        $adat = [];
        foreach ($szlist as $szl) {
            $szl['biztonsagikerdeskell'] = $szl['bankkartyas'];
            /** @var SzallitasimodFizmodNovelo $x */
            $x = $krepo->getBySzallitasimodFizmod($szm, $szl['id']);
            if ($x) {
                if (is_array($x)) {
                    $x = $x[0];
                }
                $szl['maxhatar'] = $x->getMaxhatar();
                $szl['ertekszazalek'] = $x->getErtekszazalek();
                $e = $kosarrepo->calcSumBySessionId(\Zend_Session::getId());
                if ($e) {
                    if ($x->getErtekszazalek()) {
                        $szl['novelo'] = round($e['sum'] * $x->getErtekszazalek() / 100);
                    } else {
                        $szl['novelo'] = $x->getOsszeg();
                    }
                }
                if ($e && (($x->getMaxhatar() > 0 && $x->getMaxhatar() >= $e['sum']) || $x->getMaxhatar() == 0)) {
                    $adat[] = $szl;
                }
            } else {
                $adat[] = $szl;
            }
        }
        $view->setVar('fizmodlist', $adat);
        echo json_encode([
            'html' => $view->getTemplateResult()
        ]);
    }

    public function getTetelList()
    {
        $data = $this->_getTetelListData();

        $view = \mkw\store::getTemplateFactory()->createMainView('checkouttetellist.tpl');

        $view->setVar('valutanemnev', $data['valutanemnev']);
        $view->setVar('szallitasiido', $data['szallitasiido']);
        $view->setVar('tetellista', $data['tetellista']);
        echo json_encode([
            'html' => $view->getTemplateResult(),
            'hash' => $data['hash'],
            'kuponszoveg' => $data['kuponszoveg']
        ]);
    }

    public function getTetelListData()
    {
        $data = $this->_getTetelListData();
        echo json_encode($data);
    }

    private function _getTetelListData()
    {
        $kr = $this->getRepo(Kosar::class);
        $kuponkod = $this->params->getStringRequestParam('kupon');
        $kuponszoveg = '';
        if ($kuponkod) {
            $e = $kr->calcSumBySessionId(\Zend_Session::getId());
            $ertek = $e['sum'];
            /** @var \Entities\Kupon $kupon */
            $kupon = $this->getRepo(Kupon::class)->find($kuponkod);
            if ($kupon) {
                if ($kupon->isErvenyes()) {
                    if ($kupon->isIngyenSzallitas()) {
                        if ($kupon->isMinimumosszegMegvan($ertek)) {
                            $kuponszoveg = $kupon->getTipusStr();
                        } else {
                            $kuponszoveg = 'Rendeljen még ' . bizformat($kupon->getMinimumosszeg() - $ertek) . ' Ft értékben a kupon használatához!';
                        }
                    }
                } else {
                    $kuponszoveg = $kupon->getLejartStr();
                }
            } else {
                $kuponszoveg = 'ismeretlen kupon';
            }
        }
        $this->getRepo(Kosar::class)->createSzallitasiKtg(
            $this->params->getIntRequestParam('szallitasimod'),
            $this->params->getIntRequestParam('fizmod'),
            $kuponkod
        );
        $this->getRepo(Kosar::class)->createUtanvetKtg(
            $this->params->getIntRequestParam('szallitasimod'),
            $this->params->getIntRequestParam('fizmod'),
            $kuponkod
        );
        $this->getRepo(Kosar::class)->createKezelesiKtg(
            $this->params->getIntRequestParam('szallitasimod')
        );

        $ret = [];

        $sorok = $kr->getDataBySessionId(\Zend_Session::getId());
        $s = [];
        $partner = \mkw\store::getLoggedInUser();
        if ($partner) {
            $ret['valutanemnev'] = $partner->getValutanemnev();
        } else {
            $ret['valutanemnev'] = \mkw\store::getMainSession()->valutanemnev;
        }
        $szallido = 1;
        /** @var \Entities\Kosar $sor */
        foreach ($sorok as $sor) {
            $sorszallido = $sor->getTermek()->calcSzallitasiido($sor->getTermekvaltozat(), $sor->getMennyiseg());
            if ($szallido < $sorszallido) {
                $szallido = $sorszallido;
            }
            $s[] = $sor->toLista($partner);
        }
        $szallido = $szallido + \mkw\store::calcSzallitasiidoAddition(date_create());

        $ret['hash'] = $kr->getHash();
        $ret['szallitasiido'] = $szallido;
        $ret['tetellista'] = $s;
        $ret['kuponszoveg'] = $kuponszoveg;

        return $ret;
    }

    public function showCheckoutFizetes()
    {
        $mrszam = \mkw\store::getMainSession()->lastmegrendeles;
        $szallmod = \mkw\store::getMainSession()->lastszallmod;
        $fizmod = \mkw\store::getMainSession()->lastfizmod;
        $fizmodnev = '';
        $f = $this->getRepo(Fizmod::class)->find($fizmod);
        if ($f) {
            $fizmodnev = $f->getNev();
        }

        $fizetendo = 0;
        $mr = $this->getRepo(Bizonylatfej::class)->find($mrszam);
        if ($mr) {
            $fizetendo = $mr->getFizetendo();
        }

        $excfm = [];
        $ooo = \mkw\store::getParameter(\mkw\consts::OTPayFizmod);
        if ($ooo) {
            $excfm[] = $ooo;
        }
        $ooo = \mkw\store::getParameter(\mkw\consts::MasterPassFizmod);
        if ($ooo) {
            $excfm[] = $ooo;
        }

        $view = \mkw\store::getTemplateFactory()->createMainView('checkoutfizmodlist.tpl');
        $fm = new fizmodController($this->params);
        $szlist = $fm->getSelectList($fizmod, $szallmod, $excfm);
        $view->setVar('fizmodlist', $szlist);
        $fml = $view->getTemplateResult();

        $view = \mkw\store::getTemplateFactory()->createMainView('checkoutfizetes.tpl');
        \mkw\store::fillTemplate($view);
        $view->setVar('fizetendo', $fizetendo);
        $view->setVar('megrendelesszam', $mrszam);
        $view->setVar('fizmodlist', $fml);
        $view->setVar('fizmodnev', $fizmodnev);
        $view->setVar('checkouterrors', \mkw\store::getMainSession()->checkoutfizeteserrors);
        $view->printTemplateResult(false);
        \mkw\store::getMainSession()->checkoutfizeteserrors = false;
    }

    public function doCheckoutFizetes()
    {
        require_once('busvendor/OTPay/MerchTerm_umg_client.php');

        $error = false;
        \mkw\store::getMainSession()->fizetesdb = (int)\mkw\store::getMainSession()->fizetesdb + 1;

        $mrszam = $this->params->getStringRequestParam('megrendelesszam');
        $mobilszam = preg_replace('/[^0-9]/', '', $this->params->getStringRequestParam('mobilszam'));
        $fizazon = preg_replace('/[^0-9]/', '', $this->params->getStringRequestParam('fizazon'));

        if ($mrszam) {
            $mr = $this->getRepo(Bizonylatfej::class)->find($mrszam);
            if ($mr) {
                $fizetendo = $mr->getFizetendo();
                if ($fizetendo != 0) {
                    if ($mobilszam) {
                        $clientId = new \ClientMsisdn();
                        $clientId->value = $mobilszam;
                        $mr->setOTPayMSISDN($mobilszam);
                    } else {
                        if ($fizazon) {
                            $clientId = new \ClientMpid();
                            $clientId->value = $fizazon;
                            $mr->setOTPayMPID($fizazon);
                        } else {
                            $error = 'Hiányzik a mobil szám vagy a fizetési azonosító';
                        }
                    }
                    if (!$error) {
                        $this->getEm()->persist($mr);
                        $this->getEm()->flush();

                        $timeout = new \TimeoutCategory();
                        $timeout->value = "mediumPeriod";
                        // Paraméterek
                        $mytrxid = $mr->getTrxId();
                        $request = [
                            'merchTermId' => \MerchTerm_config::getConfig("merchTermId"),
                            'merchTrxId' => $mytrxid,
                            'clientId' => $clientId,
                            'timeout' => $timeout,
                            'amount' => $fizetendo,
                            'description' => 'Mindentkapni.hu vásárlás',
                            'isRepeated' => (\mkw\store::getMainSession()->fizetesdb > 1)
                        ];

                        $client = null;

                        try {
                            $client = new \MerchTerm_umg_client();
                            $response = $client->PostImCreditInit($request);
                            if ($response->result == 0) {
                                $trxid = $response->bankTrxId;
                                $mr->setOTPayId($trxid);
                                $this->getEm()->persist($mr);
                                $this->getEm()->flush();

                                $imnotiffilter = new \ImNotifFilterBankTrxId();
                                $imnotiffilter->bankTrxId = $trxid;
                                $request = [
                                    'merchTermId' => \MerchTerm_config::getConfig("merchTermId"),
                                    'imNotifFilter' => $imnotiffilter
                                ];
                                $response = $client->GetImNotif($request);
                                if ($response->result == 0) {
                                    if (isset($response->ImNotifList)) {
                                        if (isset($response->ImNotifList->ImNotifReq)) {
                                            if (is_array($response->ImNotifList->ImNotifReq)) {
                                                $r = $response->ImNotifList->ImNotifReq;
                                                $c = 0;
                                                $response = -1;
                                                while ($c < count($r)) {
                                                    if (($r[$c]->message->bankTrxId == $trxid) && ($r[$c]->message->merchTrxId == $mytrxid)) {
                                                        $response = $r[$c]->message->bankTrxResult;
                                                    }
                                                    $c++;
                                                }
                                            } else {
                                                $response = $response->ImNotifList->ImNotifReq->message->bankTrxResult;
                                            }
                                            if ($response == 0) {
                                                $mr->setFizetve(true);
                                                $mr->setOTPayResult($response);
                                                $mr->setOTPayResultText($client->getErrorText($response));
                                                $this->getEm()->persist($mr);
                                                $this->getEm()->flush();
                                            } else {
                                                $error = $client->getErrorText($response);
                                                $mr->setOTPayResult($response);
                                                $mr->setOTPayResultText($error);
                                                $this->getEm()->persist($mr);
                                                $this->getEm()->flush();
                                            }
                                        } else {
                                            $error = 'Ismeretlen UMG válasz.';
                                            $mr->setOTPayResult(-1);
                                            $mr->setOTPayResultText($error . ' => ' . print_r($response, true));
                                            $this->getEm()->persist($mr);
                                            $this->getEm()->flush();
                                        }
                                    } else {
                                        $error = 'Ismeretlen UMG válasz.';
                                        $mr->setOTPayResult(-1);
                                        $mr->setOTPayResultText($error . ' => ' . print_r($response, true));
                                        $this->getEm()->persist($mr);
                                        $this->getEm()->flush();
                                    }
                                } else {
                                    $error = $client->getRCErrorText($response->result);
                                    $mr->setOTPayResult($response->result);
                                    $mr->setOTPayResultText($error);
                                    $this->getEm()->persist($mr);
                                    $this->getEm()->flush();
                                }
                            } else {
                                $error = $client->getRCErrorText($response->result);
                                $mr->setOTPayResult($response->result);
                                $mr->setOTPayResultText($error);
                                $this->getEm()->persist($mr);
                                $this->getEm()->flush();
                            }
                        } catch (Exception $e) {
                            $exception = $e;
                            $error = $exception->getMessage();
                        }
                    }
                } else {
                    $error = 'A fizetendő összeg nem lehet nulla';
                }
            } else {
                $error = 'A megrendelés nem található';
            }
        } else {
            $error = 'Hiányzik a megrendelés azonosító';
        }

        if ($error) {
            \mkw\store::getMainSession()->checkoutfizeteserrors = $error;
            Header('Location: ' . \mkw\store::getRouter()->generate('showcheckoutfizetes'));
        } else {
            Header('Location: ' . \mkw\store::getRouter()->generate('checkoutkoszonjuk'));
        }
    }

    public function saveCheckoutFizmod()
    {
        $megrendelesszam = $this->params->getStringRequestParam('megrendelesszam');
        $f = $this->getRepo(Fizmod::class)->find($this->params->getIntRequestParam('fizetesimod'));

        $mf = $this->getRepo(Bizonylatfej::class)->find($megrendelesszam);
        if ($mf && $f) {
            $mf->setFizmod($f);
            $this->getEm()->persist($mf);
            $this->getEm()->flush();
            $bizstatusz = $this->getRepo(Bizonylatstatusz::class)->find(\mkw\store::getParameter(\mkw\consts::BizonylatStatuszFuggoben));
            if ($bizstatusz) {
                $mf->sendStatuszEmail($bizstatusz->getEmailtemplate());
            }
        }

        Header('Location: ' . \mkw\store::getRouter()->generate('checkoutkoszonjuk'));
    }

    public function thanks()
    {
        $view = \mkw\store::getTemplateFactory()->createMainView('checkoutkoszonjuk.tpl');
        \mkw\store::fillTemplate($view);
        $mrszam = \mkw\store::getMainSession()->lastmegrendeles;
        $view->setVar('megrendelesszam', $mrszam);
        $view->setVar('megrendelesadat', \mkw\store::getMainSession()->lasttermekadat);
//itt kell hozza vasarolt termeket keresni session->lasttermekids-re

        $aktsapikey = \mkw\store::getParameter(\mkw\consts::AKTrustedShopApiKey);
        $email = \mkw\store::getMainSession()->lastemail;

        if ($aktsapikey && $email) {
            require_once 'busvendor/AKTrustedShop/TrustedShop.php';

            $ts = new \TrustedShop($aktsapikey);
            $ts->SetEmail($email);

            $ltn = \mkw\store::getMainSession()->lasttermeknevek;
            if ($ltn) {
                foreach ($ltn as $l) {
                    $ts->AddProduct($l);
                }
            }

            ob_start();
            $ts->Send();
            $tsret = ob_get_clean();

            if ($tsret) {
                $view->setVar('AKTrustedShopScript', $tsret);
            }
        }
        \mkw\store::getMainSession()->lastmegrendeles = '';
        \mkw\store::getMainSession()->lastemail = '';
        \mkw\store::getMainSession()->lasttermeknevek = [];
        \mkw\store::getMainSession()->lasttermekids = [];
        \mkw\store::getMainSession()->lastszallmod = 0;
        \mkw\store::getMainSession()->lastfizmod = 0;
        \mkw\store::getMainSession()->lasttermekadat = [];

        $view->printTemplateResult(false);
    }

    public function barionError()
    {
        $mrszam = $this->params->getStringRequestParam('mr');
        $view = \mkw\store::getTemplateFactory()->createMainView('checkoutbarionerror.tpl');
        \mkw\store::fillTemplate($view);
        $view->setVar('megrendelesszam', $mrszam);

        $view->printTemplateResult(false);
    }

    public function saveTerminalSelection()
    {
        $szmid = $this->params->getIntRequestParam('szmid');
        $cs = $this->params->getStringRequestParam('cs');
        $t = $this->params->getIntRequestParam('t');
        \mkw\store::getMainSession()->lsszallmod = $szmid;
        $key = 'lscsoport' . $szmid;
        \mkw\store::getMainSession()->$key = $cs;
        $key = 'lsterminal' . $cs;
        \mkw\store::getMainSession()->$key = $t;
    }
}