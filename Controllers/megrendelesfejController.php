<?php

namespace Controllers;

use mkw\store;

class megrendelesfejController extends bizonylatfejController {

    public function __construct($params) {
        $this->biztipus = 'megrendeles';
        $this->setPageTitle('Megrendelés');
        $this->setPluralPageTitle('Megrendelések');
        parent::__construct($params);
        $this->getRepo()->addToBatches(array('foxpostsend' => 'Küldés Foxpostnak'));
    }

    public function setVars($view) {
        parent::setVars($view);
        $view->setVar('datumtolfilter', null);
        $bsc = new bizonylatstatuszController($this->params);
        if (\mkw\Store::getTheme() == 'mkwcansas') {
            $view->setVar('bizonylatstatuszlist', $bsc->getSelectList(\mkw\Store::getParameter(\mkw\consts::BizonylatStatuszFuggoben)));
            $view->setVar('bizonylatstatuszcsoportlist', $bsc->getCsoportSelectList());
        }
        else {
            $view->setVar('bizonylatstatuszlist', $bsc->getSelectList());
            $view->setVar('bizonylatstatuszcsoportlist', $bsc->getCsoportSelectList(\mkw\Store::getParameter(\mkw\consts::MegrendelesFilterStatuszCsoport)));
        }
    }

    protected function loadVars($t, $forKarb = false) {
		if (!$t) {
			$t = new \Entities\Bizonylatfej();
			$this->getEm()->detach($t);
		}
        $x = parent::loadVars($t, $forKarb);
        $bsc = new bizonylatstatuszController($this->params);
        $x['bizonylatstatuszlist'] = $bsc->getSelectList($t->getBizonylatstatuszId());
        return $x;
    }

    protected function afterSave($o) {
        parent::afterSave($o);
        if ($this->params->getBoolRequestParam('bizonylatstatuszertesito')) {
            $statusz = $o->getBizonylatstatusz();
            if ($statusz) {
                $emailtpl = $statusz->getEmailtemplate();
                $o->sendStatuszEmail($emailtpl);
            }
        }
    }

    public function setStatusz() {
        $bf = $this->getRepo()->find($this->params->getStringRequestParam('id'));
        if ($bf) {
            $statusz = $this->getRepo('Entities\Bizonylatstatusz')->find($this->params->getIntRequestParam('statusz'));
            if ($statusz) {
                $bf->setBizonylatstatusz($statusz);
                $this->getEm()->persist($bf);
                $this->getEm()->flush();
                if ($this->params->getBoolRequestParam('bizonylatstatuszertesito')) {
                    $emailtpl = $statusz->getEmailtemplate();
                    $bf->sendStatuszEmail($emailtpl);
                }
            }
        }
    }

    public function getszamlakarb() {
        $megrendszam = $this->params->getStringRequestParams('id');
        $szamlac = new SzamlafejController($this->params);
        echo $szamlac->getkarb('bizonylatfejkarb.tpl', $megrendszam, 'add');
    }

    public function doPrintelolegbekero() {
        $o = $this->getRepo()->findForPrint($this->params->getStringRequestParam('id'));
        if ($o) {
            $biztip = $this->getRepo('Entities\Bizonylattipus')->find($this->biztipus);
            if ($biztip) {
                if (\mkw\Store::getTheme() == 'superzone') {
                    $view = $this->createView('biz_elolegbekero_eng.tpl');
                }
                else {
                    $view = $this->createView('biz_elolegbekero.tpl');
                }
                $this->setVars($view);
                $view->setVar('egyed', $o->toLista());
                $view->setVar('afaosszesito',$this->getRepo()->getAFAOsszesito($o));
                echo $view->getTemplateResult();
            }
        }
    }

    public function otpayrefund() {
        require_once('busvendor/OTPay/MerchTerm_umg_client.php');

        $szam = $this->params->getStringRequestParam('id');
        $mr = $this->getRepo()->find($szam);

        $error = '';
        if ($mr) {
            $timeout = new \TimeoutCategory();
            $timeout->value = "mediumPeriod";
            if ($mr->getOTPayMSISDN()) {
                $clientId = new \ClientMsisdn();
                $clientId->value = $mr->getOTPayMSISDN();
            }
            else {
                if ($mr->getOTPayMPID()) {
                    $clientId = new \ClientMpid();
                    $clientId->value = $mr->getOTPayMPID();
                }
                else {
                    $error = 'Hiányzik a mobil szám vagy a fizetési azonosító';
                }
            }
            if (!$error) {
                $request = array(
                    'merchTermId' => \MerchTerm_config::getConfig("merchTermId"),
                    'merchTrxId' => $mr->getTrxId(),
                    'clientId' => $clientId,
                    'timeout' => $timeout,
                    'amount' => $mr->getFizetendo(),
                    'description' => 'refund',
                    'isRepeated' => false,
                    'origBankTrxId' => $mr->getOTPayId()
                );

                $client = null;

                try {
                    $client = new \MerchTerm_umg_client();
                    $response = $client->PostImCreditInit($request);
                    \mkw\Store::writelog(print_r($response, true), 'otpay.log');
                    /*
                    if ($response->result == 0) {
                        $mr->setFizetve(false);
                        $this->getEm()->persist($mr);
                        $this->getEm()->flush();
                    }
                    else {
                        $error = Store::getOTPayErrorMessage($response->result);
                    }
                     *
                     */

                } catch (Exception $e) {
                    $exception = $e;
                    $error = $exception->getMessage();
                }
            }
        }
        echo json_encode($error);
    }

    public function otpaystorno() {
        require_once('busvendor/OTPay/MerchTerm_umg_client.php');

        $mr = $this->getRepo()->find($this->params->getStringRequestParam('id'));

        $error = '';
        if ($mr) {
            $request = array(
                'merchTermId' => \MerchTerm_config::getConfig("merchTermId"),
                'bankTrxId' => $mr->getOTPayId(),
                'reasonCode' => 1
            );

            $client = null;

            try {
                $client = new \MerchTerm_umg_client();
                $response = $client->PostImCreditInit($request);
                \mkw\Store::writelog(print_r($response, true), 'otpay.log');
                /*
                if ($response->result == 0) {
                    $mr->setFizetve(false);
                    $this->getEm()->persist($mr);
                    $this->getEm()->flush();
                }
                else {
                    $error = Store::getOTPayErrorMessage($response->result);
                }
                 *
                 */

            } catch (Exception $e) {
                $error = $e->getMessage();
                echo json_encode($error);
            }
        }
        echo json_encode($error);
    }

    public function sendToFoxPost() {
        $ids = $this->params->getArrayRequestParam('ids');
        foreach($ids as $id) {
            $megrendfej = $this->getRepo()->find($id);
            if ($megrendfej && Store::isFoxpostSzallitasimod($megrendfej->getSzallitasimodId()) && !$megrendfej->getFoxpostBarcode()) {
                $fpc = new \Controllers\foxpostController($this->params);
                $fpres = $fpc->sendMegrendeles($megrendfej);
                if ($fpres) {
                    $megrendfej->setFoxpostBarcode($fpres['barcode']);
                    $megrendfej->setFuvarlevelszam($fpres['barcode']);
                    if (array_key_exists('trace', $fpres)) {
                        $megrendfej->setTraceurl($fpres['trace']['href']);
                    }
                    $this->getEm()->persist($megrendfej);
                    $this->getEm()->flush();
                }
            }
        }
    }

    public function backOrder() {
        $id = $this->params->getStringRequestParam('id');
        $regibiz = $this->getRepo()->find($id);
        if ($regibiz) {
            $teljesitheto = $this->getRepo('Entities\Bizonylatstatusz')->find(\mkw\Store::getParameter(\mkw\consts::BizonylatStatuszTeljesitheto));
            $backorder = $this->getRepo('Entities\Bizonylatstatusz')->find(\mkw\Store::getParameter(\mkw\consts::BizonylatStatuszBackorder));
            $this->getEm()->beginTransaction();
            try {
                $ujdb = 0;
                $regidb = 0;
                foreach($regibiz->getBizonylattetelek() as $regitetel) {
                    $t = $regitetel->getTermek();
                    if ($t && $t->getMozgat()) {
                        $v = $regitetel->getTermekvaltozat();
                        $keszlet = 0;
                        if ($v) {
                            $keszlet = $v->getKeszlet() - $v->getFoglaltMennyiseg($regibiz->getId());
                        }
                        else {
                            $keszlet = $t->getKeszlet() - $t->getFoglaltMennyiseg($regibiz->getId());
                        }
                    }
                    if ($keszlet < 0) {
                        $keszlet = 0;
                    }
                    if ($keszlet < $regitetel->getMennyiseg()) {
                        $ujdb++;
                        if ($keszlet > 0) {
                            $regidb++;
                        }
                    }
                    else {
                        $regidb++;
                    }
                }
                if ($regidb == 0 || $ujdb == 0) {
                    $result = 0;
                    if ($ujdb == 0) {
                        $regibiz->setBizonylatstatusz($teljesitheto);
                    }
                    elseif ($regidb == 0) {
                        $regibiz->setBizonylatstatusz($backorder);
                        $result = 1;
                    }
                    $this->getEm()->persist($regibiz);
                    $this->getEm()->flush();
                    $this->getEm()->commit();
                    echo json_encode(array('refresh' => $result));
                }
                else {
                    $ujbiz = new \Entities\Bizonylatfej();
                    $ujbiz->duplicate($regibiz);
                    $ujbiz->clearId();
                    $ujbiz->clearCreated();
                    $ujbiz->clearLastmod();
                    $ujbiz->generateId();
                    $ujbiz->setKelt();
                    $ujbiz->setBizonylatstatusz($backorder);
                    foreach($regibiz->getBizonylattetelek() as $regitetel) {
                        $t = $regitetel->getTermek();
                        if ($t && $t->getMozgat()) {
                            $v = $regitetel->getTermekvaltozat();
                            $keszlet = 0;
                            if ($v) {
                                $keszlet = $v->getKeszlet() - $v->getFoglaltMennyiseg($regibiz->getId());
                            }
                            else {
                                $keszlet = $t->getKeszlet() - $t->getFoglaltMennyiseg($regibiz->getId());
                            }
                        }
                        if ($keszlet < 0) {
                            $keszlet = 0;
                        }
                        if ($keszlet < $regitetel->getMennyiseg()) {
                            $ujtetel = new \Entities\Bizonylattetel();
                            $ujtetel->duplicate($regitetel);
                            $ujtetel->clearCreated();
                            $ujtetel->clearLastmod();
                            /**
                            foreach($regitetel->getTranslations() as $trans) {
                                $ujtrans = clone $trans;
                                $ujtetel->addTranslation($ujtrans);
                                $this->getEm()->persist($ujtrans);
                            }
                             */
                            $ujtetel->setMennyiseg($regitetel->getMennyiseg() - $keszlet);
                            $ujtetel->calc();
                            $ujbiz->addBizonylattetel($ujtetel);
                            $this->getEm()->persist($ujtetel);
                            if ($keszlet <= 0) {
                                $regibiz->removeBizonylattetel($regitetel);
                                $this->getEm()->remove($regitetel);
                            }
                            else {
                                $regitetel->setMennyiseg($keszlet);
                                $regitetel->calc();
                                $this->getEm()->persist($regitetel);
                            }
                        }
                    }
                    $regibiz->setBizonylatstatusz($teljesitheto);
                    $this->getEm()->persist($regibiz);
                    $this->getEm()->persist($ujbiz);
                    $this->getEm()->flush();
                    $this->getEm()->commit();
                    echo json_encode(array('refresh' => 1));
                }
            }
            catch (\Exception $e) {
                $this->getEm()->rollback();
                throw $e;
            }
        }
        else {
            echo json_encode(array('refresh' => 0));
        }
    }
}
