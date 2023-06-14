<?php

namespace Controllers;

class mugenrace2021CheckoutController extends checkoutController
{

    public function save()
    {
        $partnerid = $this->params->getIntRequestParam('id');
        $regkell = $this->params->getIntRequestParam('regkell');
        $vezeteknev = $this->params->getStringRequestParam('vezeteknev');
        $keresztnev = $this->params->getStringRequestParam('keresztnev');
        $telefon = preg_replace('/[^0-9+]/', '', $this->params->getStringRequestParam('telefon'));
        if (substr_count($telefon, '+') > 1) {
            $firstPlus = strpos($telefon, '+') + 1;
            $telefon = substr($telefon, 0, $firstPlus) . str_replace('+', '', substr($telefon, $firstPlus));
        }
        $jelszo1 = $this->params->getStringRequestParam('jelszo1');
        $jelszo2 = $this->params->getStringRequestParam('jelszo2');
        $kapcsemail = $this->params->getStringRequestParam('kapcsemail');
        $szamlanev = $this->params->getStringRequestParam('szlanev');
        $szamlairszam = $this->params->getStringRequestParam('irszam');
        $szamlavaros = $this->params->getStringRequestParam('varos');
        $szamlautca = $this->params->getStringRequestParam('utca');
        $szamlahazszam = $this->params->getStringRequestParam('hazszam');
        $szallnev = $this->params->getStringRequestParam('szallnev');
        $szallirszam = $this->params->getStringRequestParam('szallirszam');
        $szallvaros = $this->params->getStringRequestParam('szallvaros');
        $szallutca = $this->params->getStringRequestParam('szallutca');
        $szallhazszam = $this->params->getStringRequestParam('szallhazszam');
        $szamlaeqszall = $this->params->getBoolRequestParam('szamlaeqszall');
        $orszag = $this->params->getIntRequestParam('orszag');
        $szallitasimod = $this->params->getIntRequestParam('szallitasimod');
        $fizetesimod = $this->params->getIntRequestParam('fizetesimod');
        $webshopmessage = $this->params->getStringRequestParam('webshopmessage');
        $couriermessage = $this->params->getStringRequestParam('couriermessage');
        $aszfready = $this->params->getBoolRequestParam('aszfready');
        $akciohirlevel = $this->params->getBoolRequestParam('akciohirlevel');
        $ujdonsaghirlevel = $this->params->getBoolRequestParam('ujdonsaghirlevel');

        if ($szamlaeqszall) {
            $szamlanev = $szallnev;
            $szamlairszam = $szallirszam;
            $szamlavaros = $szallvaros;
            $szamlautca = $szallutca;
            $szamlahazszam = $szallhazszam;
        }

        $ret = ['success' => true];

        if (!$aszfready) {
            $ret['success'] = false;
            $ret['fields']['aszfready'] = [
                'valid' => false,
                'error' => t('Kötelező kitölteni')
            ];
        }
        if (!$szallnev) {
            $ret['success'] = false;
            $ret['fields']['szallnev'] = [
                'valid' => false,
                'error' => t('Kötelező kitölteni')
            ];
        }
        if (!$szallirszam) {
            $ret['success'] = false;
            $ret['fields']['szallirszam'] = [
                'valid' => false,
                'error' => t('Kötelező kitölteni')
            ];
        }
        if (!$szallvaros) {
            $ret['success'] = false;
            $ret['fields']['szallvaros'] = [
                'valid' => false,
                'error' => t('Kötelező kitölteni')
            ];
        }
        if (!$szallutca) {
            $ret['success'] = false;
            $ret['fields']['szallutca'] = [
                'valid' => false,
                'error' => t('Kötelező kitölteni')
            ];
        }
        if (!$szamlanev) {
            $ret['success'] = false;
            $ret['fields']['szlanev'] = [
                'valid' => false,
                'error' => t('Kötelező kitölteni')
            ];
        }
        if (!$szamlairszam) {
            $ret['success'] = false;
            $ret['fields']['irszam'] = [
                'valid' => false,
                'error' => t('Kötelező kitölteni')
            ];
        }
        if (!$szamlavaros) {
            $ret['success'] = false;
            $ret['fields']['varos'] = [
                'valid' => false,
                'error' => t('Kötelező kitölteni')
            ];
        }
        if (!$szamlautca) {
            $ret['success'] = false;
            $ret['fields']['utca'] = [
                'valid' => false,
                'error' => t('Kötelező kitölteni')
            ];
        }

        $kosartetelek = $this->getRepo('Entities\Kosar')->getDataBySessionId(\Zend_Session::getId());
        if (!count($kosartetelek)) {
            $ret['success'] = false;
            $ret['fields'][''] = 'Üres a kosara.';
        }

        if ($ret['success']) {
            if ($partnerid) {
                $partner = $this->getRepo('Entities\Partner')->getLoggedInUser();
            } else {
                switch ($regkell) {
                    case 1: // vendég
                        $pc = new \Controllers\partnerController($this->params);
                        $partner = $pc->saveRegistrationData(true);
                        $szamlasave = true;
                        $szallsave = true;
                        break;
                    case 2: // regisztráció
                        $pc = new \Controllers\partnerController($this->params);
                        $partner = $pc->saveRegistrationData(false);
                        $pc->login($kapcsemail, $jelszo1);
                        break;
                    default: // be van jelentkezve
                        break;
                }
            }
            $partner->setSzallnev($szallnev);
            $partner->setSzallirszam($szallirszam);
            $partner->setSzallvaros($szallvaros);
            $partner->setSzallutca($szallutca);
            $orszag = \mkw\store::getEm()->getRepository('Entities\Orszag')->find($this->params->getIntRequestParam('orszag', 0));
            if ($orszag) {
                $partner->setOrszag($orszag);
            }
            if ($szamlaeqszall) {
                $partner->setNev($szallnev);
                $partner->setIrszam($szallirszam);
                $partner->setVaros($szallvaros);
                $partner->setUtca($szallutca);
            } else {
                $partner->setNev($szamlanev);
                $partner->setIrszam($szamlairszam);
                $partner->setVaros($szamlavaros);
                $partner->setUtca($szamlautca);
            }
            $partner->setTelefon($telefon);
            $partner->setAkcioshirlevelkell($akciohirlevel);
            $partner->setUjdonsaghirlevelkell($ujdonsaghirlevel);
            $this->getEm()->persist($partner);

            $nullasafa = $this->getRepo('Entities\Afa')->find(\mkw\store::getParameter(\mkw\consts::NullasAfa));
            $biztetelcontroller = new bizonylattetelController($this->params);
            //$valutanem =

            $biztipus = $this->getRepo('Entities\Bizonylattipus')->find('megrendeles');
            $megrendfej = new \Entities\Bizonylatfej();
            $megrendfej->setPersistentData();
            switch ($regkell) {
                case 1:
                    $megrendfej->setRegmode(1);
                    $regmodenev = 'vendég';
                    break;
                case 2:
                    $megrendfej->setRegmode(2);
                    $regmodenev = 'regisztrált';
                    break;
                default:
                    $megrendfej->setRegmode(3);
                    $regmodenev = 'bejelentkezett';
                    break;
            }

            $megrendfej->setIp($_SERVER['REMOTE_ADDR']);
            $megrendfej->setReferrer(\mkw\store::getMainSession()->referrer);
            $megrendfej->setBizonylattipus($biztipus);
            $megrendfej->setKelt('');
            $megrendfej->setTeljesites('');
            $megrendfej->setEsedekesseg('');

            $megrendfej->setPartner($partner);

            $megrendfej->setPartnernev($szamlanev);
            $megrendfej->setPartnerirszam($szamlairszam);
            $megrendfej->setPartnervaros($szamlavaros);
            $megrendfej->setPartnerutca($szamlautca);
            $orszagobj = $this->getRepo('Entities\Orszag')->find($orszag);
            if ($orszagobj) {
                $megrendfej->setPartnerorszag($orszagobj);
            }
            $megrendfej->setSzallnev($szallnev);
            $megrendfej->setSzallirszam($szallirszam);
            $megrendfej->setSzallvaros($szallvaros);
            $megrendfej->setSzallutca($szallutca);

            $megrendfej->setFizmod($this->getEm()->getRepository('Entities\Fizmod')->find($fizetesimod));
            $megrendfej->setSzallitasimod($this->getEm()->getRepository('Entities\Szallitasimod')->find($szallitasimod));
            $valutanemid = \mkw\store::getMainSession()->valutanem;
            $valutanem = $this->getRepo('Entities\Valutanem')->find($valutanemid);
            $megrendfej->setValutanem($valutanem);
            $megrendfej->setWebshopmessage($webshopmessage);
            $arf = $this->getEm()->getRepository('Entities\Arfolyam')->getActualArfolyam($valutanem, $megrendfej->getTeljesites());
            $megrendfej->setArfolyam($arf->getArfolyam());
            $raktarid = \mkw\store::getParameter(\mkw\consts::Raktar);
            $megrendfej->setRaktar($this->getRepo('Entities\Raktar')->find($raktarid));
            if ($valutanem) {
                $megrendfej->setBankszamla($valutanem->getBankszamla());
            }
            if (\mkw\store::isBarionFizmod($fizetesimod)) {
                $bizstatusz = $this->getRepo('Entities\Bizonylatstatusz')->find(\mkw\store::getParameter(\mkw\consts::BarionFizetesrevarStatusz));
            } else {
                $bizstatusz = $this->getRepo('Entities\Bizonylatstatusz')->find(\mkw\store::getParameter(\mkw\consts::BizonylatStatuszFuggoben));
            }
            $megrendfej->setBizonylatstatusz($bizstatusz);

            $lasttermeknevek = [];
            $lasttermekids = [];
            foreach ($kosartetelek as $kt) {
                $t = new \Entities\Bizonylattetel();
                $t->setBizonylatfej($megrendfej);
                $t->setPersistentData();
                $t->setTermek($kt->getTermek());
                $t->setTermekvaltozat($kt->getTermekvaltozat());
                $t->setMennyiseg($kt->getMennyiseg());
                if ($partner->getSzamlatipus()) {
                    if ($nullasafa) {
                        $t->setAfa($nullasafa);
                    }
                    $t->setNettoegysar($kt->getNettoegysar());
                    $t->setEnettoegysar($kt->getEnettoegysar());
                    $t->setEbruttoegysar($kt->getEnettoegysar());
                } else {
                    $t->setNettoegysar($kt->getNettoegysar());
                    $t->setBruttoegysar($kt->getBruttoegysar());
                    $t->setEnettoegysar($kt->getEnettoegysar());
                    $t->setEbruttoegysar($kt->getEbruttoegysar());
                }
                $t->setKedvezmeny($kt->getKedvezmeny());
                $arak = $biztetelcontroller->calcAr(
                    $t->getAfaId(),
                    $t->getArfolyam(),
                    $t->getNettoegysar(),
                    $t->getEnettoegysar(),
                    $t->getMennyiseg()
                );
                $t->setNettoegysarhuf($arak['nettoegysarhuf']);
                $t->setBruttoegysarhuf($arak['bruttoegysarhuf']);
                $t->calc();
                $lasttermeknevek[] = $t->getTermeknev();
                $lasttermekids[] = $t->getTermekId();
                $this->getEm()->persist($t);
            }
            $this->getEm()->persist($megrendfej);
            $this->getEm()->flush();

            \mkw\store::getMainSession()->lastmegrendeles = $megrendfej->getId();
            \mkw\store::getMainSession()->lasttermeknevek = $lasttermeknevek;
            \mkw\store::getMainSession()->lasttermekids = $lasttermekids;
            $kc = new kosarController($this->params);
            $kc->clear();

            if (\mkw\store::isBarionFizmod($fizetesimod)) {
                $bc = new barionController($this->params);
                $paymentres = $bc->startPayment($megrendfej);
                if ($paymentres['result']) {
                    // Header('Location: ' . $paymentres['redirecturl']);
                    $ret['url'] = $paymentres['redirecturl'];
                } else {
                    // Header('Location: ' . \mkw\store::getRouter()->generate('checkoutbarionerror', false, [], ['mr' => $megrendfej->getId()]));
                    $ret['url'] = \mkw\store::getRouter()->generate('checkoutbarionerror', false, [], ['mr' => $megrendfej->getId()]);
                }
            } else {
                if ($bizstatusz) {
                    $megrendfej->sendStatuszEmail($bizstatusz->getEmailtemplate());
                }
                // Header('Location: ' . \mkw\store::getRouter()->generate('checkoutkoszonjuk'));
                $ret['url'] = \mkw\store::getRouter()->generate('checkoutkoszonjuk');
            }
        }
        echo json_encode($ret);
    }

}