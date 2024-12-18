<?php

namespace Controllers;

use Entities\Bizonylatstatusz;
use Entities\Bizonylattipus;
use Entities\CsomagTerminal;
use Entities\Fizmod;
use Entities\Kosar;
use Entities\Partner;
use Entities\Raktar;
use Entities\Szallitasimod;
use Entities\Valutanem;
use mkw\store;

class mindentkapniCheckoutController extends checkoutController
{

    public function save()
    {
        $errorlogtext = [];
        $errors = [];

        $regkell = $this->params->getIntRequestParam('regkell');
        $vezeteknev = $this->params->getStringRequestParam('vezeteknev');
        $keresztnev = $this->params->getStringRequestParam('keresztnev');
        $telkorzet = $this->params->getStringRequestParam('telkorzet');
        $telszam = preg_replace('/[^0-9+]/', '', $this->params->getStringRequestParam('telszam'));
        $telefon = '+36' . $telkorzet . $telszam;
        $jelszo1 = $this->params->getStringRequestParam('jelszo1');
        $jelszo2 = $this->params->getStringRequestParam('jelszo2');
        $kapcsemail = trim($this->params->getStringRequestParam('kapcsemail'));
        $validkapcsemail = \mkw\store::isValidEmail($kapcsemail);
        $szamlanev = $this->params->getStringRequestParam('szamlanev');
        $szamlairszam = $this->params->getStringRequestParam('szamlairszam');
        $szamlavaros = $this->params->getStringRequestParam('szamlavaros');
        $szamlautca = $this->params->getStringRequestParam('szamlautca');
        $adoszam = $this->params->getStringRequestParam('adoszam');
        $szamlaeqszall = $this->params->getBoolRequestParam('szamlaeqszall');
        $szallnev = $this->params->getStringRequestParam('szallnev');
        $szallirszam = $this->params->getStringRequestParam('szallirszam');
        $szallvaros = $this->params->getStringRequestParam('szallvaros');
        $szallutca = $this->params->getStringRequestParam('szallutca');
        $szallitasimod = $this->params->getIntRequestParam('szallitasimod');
        $fizetesimod = $this->params->getIntRequestParam('fizetesimod');
        $webshopmessage = $this->params->getStringRequestParam('webshopmessage');
        $couriermessage = $this->params->getStringRequestParam('couriermessage');
        $aszfready = $this->params->getBoolRequestParam('aszfready');
        $szamlasave = $this->params->getBoolRequestParam('szamlasave');
        $szallsave = $this->params->getBoolRequestParam('szallsave');
        $akciohirlevel = $this->params->getBoolRequestParam('akciohirlevel');
        $ujdonsaghirlevel = $this->params->getBoolRequestParam('ujdonsaghirlevel');
        $csomagterminalid = $this->params->getIntRequestParam('foxpostterminal');
        if (!$csomagterminalid) {
            $csomagterminalid = $this->params->getIntRequestParam('glsterminal');
        }
        $tofterminalid = $this->params->getIntRequestParam('tofid');
        $kuponkod = $this->params->getStringRequestParam('kupon');

        $ok = ($vezeteknev && $keresztnev && $telkorzet && $telszam &&
            $szallirszam && $szallvaros && $szallutca && $szallnev &&
            (!$szamlaeqszall ? $szamlanev : true) &&
            (!$szamlaeqszall ? $szamlairszam : true) &&
            (!$szamlaeqszall ? $szamlavaros : true) &&
            (!$szamlaeqszall ? $szamlautca : true) &&
            $szallitasimod > 0 &&
            $fizetesimod > 0 &&
            $aszfready
        );

        if (\mkw\store::isFoxpostSzallitasimod($szallitasimod) || \mkw\store::isGLSSzallitasimod($szallitasimod)) {
            $ok = $ok && $csomagterminalid;
        }

        if (\mkw\store::isTOFSzallitasimod($szallitasimod)) {
            $ok = $ok && $tofterminalid;
        }

        if (!$ok) {
            $errorlogtext[] = '1alapadat';
            if (!$vezeteknev) {
                $errors[] = 'Nem adta meg a vezetéknevét.';
            }
            if (!$keresztnev) {
                $errors[] = 'Nem adta meg a keresztnevét.';
            }
            if (!$telkorzet || !$telszam) {
                $errors[] = 'Nem adta meg a telefonszámát.';
            }
            if (!$szallirszam) {
                $errors[] = 'Nem adta meg a szállítási ir.számot.';
            }
            if (!$szallvaros) {
                $errors[] = 'Nem adta meg a szállítási várost.';
            }
            if (!$szallutca) {
                $errors[] = 'Nem adta meg a szállítási utcát.';
            }
            if (!$szallnev) {
                $errors[] = 'Nem adta meg a szállítási nevet.';
            }
            if (!$szamlaeqszall) {
                if (!$szamlanev) {
                    $errors[] = 'Nem adta meg a számlázási nevet.';
                }
                if (!$szamlairszam) {
                    $errors[] = 'Nem adta meg a számlázási ir.számot.';
                }
                if (!$szamlavaros) {
                    $errors[] = 'Nem adta meg a számlázási várost.';
                }
                if (!$szamlautca) {
                    $errors[] = 'Nem adta meg a számlázási utcát.';
                }
            }
            if (!$szallitasimod) {
                $errors[] = 'Nem adta meg a szállítási módot.';
            }
            if ((\mkw\store::isFoxpostSzallitasimod($szallitasimod) || \mkw\store::isGLSSzallitasimod($szallitasimod)) && (!$csomagterminalid)) {
                $errors[] = 'Nem adta meg a csomagterminált.';
            }
            if (!$fizetesimod) {
                $errors[] = 'Nem adta meg a fizetési módot.';
            }
            if (!$aszfready) {
                $errors[] = 'Nem fogadta el az ált.szerződési feltételeket.';
            }
        }
        switch ($regkell) {
            case 1: // vendég
                $ok = $ok && $kapcsemail && $validkapcsemail;
                if (!$kapcsemail) {
                    $errorlogtext[] = '2vendegemail';
                    $errors[] = 'Nem adott meg emailcímet.';
                } else {
                    if (!$validkapcsemail) {
                        $errorlogtext[] = '2vendegemailhiba';
                        $errors[] = 'A megadott emailcím hibás.';
                    }
                }
                break;
            case 2: // regisztráció
                $ok = $ok && $jelszo1 && $jelszo2 && ($jelszo1 === $jelszo2) && $kapcsemail && $validkapcsemail;
                if (!$jelszo1 || !$jelszo2 || ($jelszo1 !== $jelszo2)) {
                    $errorlogtext[] = '3regjelszo';
                    $errors[] = 'Nem adott meg jelszót, vagy a két jelszó nem egyezik.';
                }
                if (!$kapcsemail) {
                    $errorlogtext[] = '3regemail';
                    $errors[] = 'Nem adott meg emailcímet.';
                } else {
                    if (!$validkapcsemail) {
                        $errorlogtext[] = '3vendegemailhiba';
                        $errors[] = 'A megadott emailcím hibás.';
                    }
                }
                break;
            default: // be van jelentkezve elvileg
                break;
        }

        $kosartetelek = $this->getRepo(Kosar::class)->getDataBySessionId(\Zend_Session::getId());
        $ok = $ok && count($kosartetelek) > 0;
        if (!count($kosartetelek)) {
            $errorlogtext[] = '4ureskosar';
            $errors[] = 'Üres a kosara.';
        }

        if ($ok) {
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
                    $partner = $this->getRepo(Partner::class)->getLoggedInUser();
                    break;
            }
            $partner->setSzallnev($szallnev);
            $partner->setSzallirszam($szallirszam);
            $partner->setSzallvaros($szallvaros);
            $partner->setSzallutca($szallutca);
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
            $partner->setTelkorzet($telkorzet);
            $partner->setTelszam($telszam);
            $partner->setTelefon($telefon);
            $partner->setAdoszam($adoszam);
            $partner->setAkcioshirlevelkell($akciohirlevel);
            $partner->setUjdonsaghirlevelkell($ujdonsaghirlevel);
            $this->getEm()->persist($partner);

            $biztipus = $this->getRepo(Bizonylattipus::class)->find('megrendeles');
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
            $megrendfej->setHatarido('');
            $megrendfej->setArfolyam(1);
            $megrendfej->setPartner($partner);
            $megrendfej->setKupon($kuponkod);
            $megrendfej->setFizmod($this->getEm()->getRepository(Fizmod::class)->find($fizetesimod));
            $megrendfej->setSzallitasimod($this->getEm()->getRepository(Szallitasimod::class)->find($szallitasimod));
            $valutanemid = \mkw\store::getParameter(\mkw\consts::Valutanem);
            $valutanem = $this->getRepo(Valutanem::class)->find($valutanemid);
            $megrendfej->setValutanem($valutanem);
            $raktarid = \mkw\store::getParameter(\mkw\consts::Raktar);
            $megrendfej->setRaktar($this->getRepo(Raktar::class)->find($raktarid));
            $megrendfej->setBankszamla($valutanem->getBankszamla());
            $megrendfej->setWebshopmessage($webshopmessage);
            $megrendfej->setCouriermessage($couriermessage);
            if (\mkw\store::isBarionFizmod($fizetesimod)) {
                $bizstatusz = $this->getRepo(Bizonylatstatusz::class)->find(\mkw\store::getParameter(\mkw\consts::BarionFizetesrevarStatusz));
            } else {
                $bizstatusz = $this->getRepo(Bizonylatstatusz::class)->find(\mkw\store::getParameter(\mkw\consts::BizonylatStatuszFuggoben));
            }
            $megrendfej->setBizonylatstatusz($bizstatusz);
            if (\mkw\store::isFoxpostSzallitasimod($szallitasimod) || \mkw\store::isGLSSzallitasimod($szallitasimod)) {
                $fpc = $this->getRepo(CsomagTerminal::class)->find($csomagterminalid);
                if ($fpc) {
                    $megrendfej->setCsomagterminal($fpc);
                }
            }
            if (\mkw\store::isTOFSzallitasimod($szallitasimod)) {
                $fpc = $this->getRepo(CsomagTerminal::class)->find($tofterminalid);
                if ($fpc) {
                    $megrendfej->setCsomagterminal($fpc);
                }
            }

            $lasttermeknevek = [];
            $lasttermekids = [];
            $lasttermekadat = [];
            //			$kosartetelek = $this->getRepo('Entities\Kosar')->getDataBySessionId(\Zend_Session::getId());
            foreach ($kosartetelek as $kt) {
                $t = new \Entities\Bizonylattetel();
                $t->setBizonylatfej($megrendfej);
                $t->setPersistentData();
                $t->setTermek($kt->getTermek());
                $t->setTermekvaltozat($kt->getTermekvaltozat());
                $t->setMennyiseg($kt->getMennyiseg());
                $t->setNettoegysar($kt->getNettoegysar());
                $t->setBruttoegysar($kt->getBruttoegysar());
                $t->setNettoegysarhuf($kt->getNettoegysar());
                $t->setBruttoegysarhuf($kt->getBruttoegysar());
                $t->setEnettoegysarhuf($kt->getEnettoegysar());
                $t->setEbruttoegysarhuf($kt->getEbruttoegysar());
                $t->setKedvezmeny($kt->getKedvezmeny());
                $t->calc();
                $lasttermeknevek[] = $t->getTermeknev();
                $lasttermekids[] = $t->getTermekId();
                $lasttermekadat[] = [
                    'id' => $t->getTermekId(),
                    'unitprice' => $t->getBruttoegysar(),
                    'qty' => $t->getMennyiseg()
                ];
                $this->getEm()->persist($t);
            }
            $this->getEm()->persist($megrendfej);
            $this->getEm()->flush();

            \mkw\store::getMainSession()->lastmegrendeles = $megrendfej->getId();
            \mkw\store::getMainSession()->lastemail = $kapcsemail;
            \mkw\store::getMainSession()->lasttermeknevek = $lasttermeknevek;
            \mkw\store::getMainSession()->lasttermekids = $lasttermekids;
            \mkw\store::getMainSession()->lastszallmod = $szallitasimod;
            \mkw\store::getMainSession()->lastfizmod = $fizetesimod;
            \mkw\store::getMainSession()->lasttermekadat = $lasttermekadat;
            $kc = new kosarController($this->params);
            $kc->clear();

            if ($fizetesimod == \mkw\store::getParameter(\mkw\consts::OTPayFizmod)) {
                Header('Location: ' . \mkw\store::getRouter()->generate('showcheckoutfizetes'));
            } else {
                if ($bizstatusz) {
                    $megrendfej->sendStatuszEmail($bizstatusz->getEmailtemplate());
                }
                if (\mkw\store::isBarionFizmod($fizetesimod)) {
                    $bc = new barionController($this->params);
                    $paymentres = $bc->startPayment($megrendfej);
                    if ($paymentres['result']) {
                        Header('Location: ' . $paymentres['redirecturl']);
                    } else {
                        Header('Location: ' . \mkw\store::getRouter()->generate('checkoutbarionerror', false, [], ['mr' => $megrendfej->getId()]));
                    }
                } else {
                    Header('Location: ' . \mkw\store::getRouter()->generate('checkoutkoszonjuk'));
                }
            }
        } else {
            \mkw\store::getMainSession()->params = $this->params;
            \mkw\store::getMainSession()->checkoutErrors = $errors;
            Header('Location: ' . \mkw\store::getRouter()->generate('showcheckout'));
        }
    }

}