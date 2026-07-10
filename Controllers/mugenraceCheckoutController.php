<?php

namespace Controllers;

use Entities\Afa;
use Entities\Arfolyam;
use Entities\Bizonylatstatusz;
use Entities\Bizonylattipus;
use Entities\Fizmod;
use Entities\Kosar;
use Entities\Orszag;
use Entities\Partner;
use Entities\Raktar;
use Entities\Szallitasimod;
use Entities\Valutanem;
use mkw\store;


/******************************************************
 *
 * mugenraceshop
 *
 */
class mugenraceCheckoutController extends checkoutController
{

    public function save()
    {
        $errorlogtext = [];
        $errors = [];

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
        $szamlanev = $this->params->getStringRequestParam('szamlanev');
        $szamlairszam = $this->params->getStringRequestParam('szamlairszam');
        $szamlavaros = $this->params->getStringRequestParam('szamlavaros');
        $szamlautca = $this->params->getStringRequestParam('szamlautca');
        $szallnev = $this->params->getStringRequestParam('szallnev');
        $szallirszam = $this->params->getStringRequestParam('szallirszam');
        $szallvaros = $this->params->getStringRequestParam('szallvaros');
        $szallutca = $this->params->getStringRequestParam('szallutca');
        $szamlaeqszall = $this->params->getBoolRequestParam('szamlaeqszall');
        $orszag = $this->params->getIntRequestParam('orszag');
        $szallorszag = $this->params->getIntRequestParam('szallorszag');
        $szallitasimod = $this->params->getIntRequestParam('szallitasimod');
        $fizetesimod = $this->params->getIntRequestParam('fizetesimod');
        $webshopmessage = $this->params->getStringRequestParam('webshopmessage');
        $couriermessage = $this->params->getStringRequestParam('couriermessage');
        $aszfready = $this->params->getBoolRequestParam('aszfready');
        $akciohirlevel = $this->params->getBoolRequestParam('akciohirlevel');
        $ujdonsaghirlevel = $this->params->getBoolRequestParam('ujdonsaghirlevel');
        $vasarlotipus = $this->params->getStringRequestParam('vasarlo_tipus');
        $adoszam = $this->params->getStringRequestParam('adoszam');

        if ($szamlaeqszall) {
            $szamlanev = $szallnev;
            $szamlairszam = $szallirszam;
            $szamlavaros = $szallvaros;
            $szamlautca = $szallutca;
            $orszag = $szallorszag;
        }

        $ok = ($szallnev && $szallirszam && $szallvaros && $szallutca && $szallorszag &&
            $szamlanev && $szamlairszam && $szamlavaros && $szamlautca && $orszag);

        if ($vasarlotipus == 'ceg') {
            $ok = $ok && (!empty($adoszam));
        }

        if (!$ok) {
            $errorlogtext[] = '1alapadat';
            $errors[] = 'Nem adott meg egy kötelező adatot.';
        }

        $kosartetelek = $this->getRepo(Kosar::class)->getDataBySessionId(\mkw\session::getId());
        $ok = $ok && count($kosartetelek) > 0;
        if (!count($kosartetelek)) {
            $errorlogtext[] = '4ureskosar';
            $errors[] = 'Üres a kosara.';
        }

        $res = \mkw\store::checkMinKosarErtek();
        if (!$res['success']) {
            $ok = false;
            $errorlogtext[] = '5minkosarertek';
            $errors[] = 'A rendelés összege nem éri el a minimális vásárlási limitet (' . \mkw\store::bizformat($res['minvalue']) . ' Ft).';
        }

        if ($ok) {
            $orszagobj = $this->getRepo(Orszag::class)->find($orszag);
            $szallorszagobj = $this->getRepo(Orszag::class)->find($szallorszag);

            switch ($regkell) {
                case 1: // vendég
                    $pc = new \Controllers\partnerController();
                    $partner = $pc->saveRegistrationData(true);
                    $szamlasave = true;
                    $szallsave = true;
                    break;
                case 2: // regisztráció
                    $pc = new \Controllers\partnerController();
                    $partner = $pc->saveRegistrationData(false);
                    $pc->login($kapcsemail, $jelszo1);
                    break;
                default: // be van jelentkezve
                    $partner = $this->getRepo(Partner::class)->getLoggedInUser();
                    break;
            }
            if (\mkw\store::isMagyarorszag($orszag)) {
                switch ($vasarlotipus) {
                    case 'ceg':
                        $partner->setVatstatus(1); // belfőldi adóalany
                        break;
                    case 'maganszemely':
                        $partner->setVatstatus(2); // magánszemély
                        break;
                }
            } else {
                switch ($vasarlotipus) {
                    case 'ceg':
                        $partner->setVatstatus(3); // egyéb
                        break;
                    case 'maganszemely':
                        $partner->setVatstatus(2); // magánszemély
                        break;
                }
            }
            if (\mkw\store::isMagyarorszag($orszag)) {
                $partner->setAdoszam($adoszam);
            } elseif ($orszagobj->getEu()) {
                $partner->setEuadoszam($adoszam);
            } else {
                $partner->setThirdadoszam($adoszam);
            }
            $partner->setSzallnev($szallnev);
            $partner->setSzallirszam($szallirszam);
            $partner->setSzallvaros($szallvaros);
            $partner->setSzallutca($szallutca);
            if ($szallorszagobj) {
                $partner->setSzallorszag($szallorszagobj);
            }

            $partner->setNev($szamlanev);
            $partner->setIrszam($szamlairszam);
            $partner->setVaros($szamlavaros);
            $partner->setUtca($szamlautca);
            if ($orszagobj) {
                $partner->setOrszag($orszagobj);
            }

            $partner->setTelefon($telefon);
            $partner->setAkcioshirlevelkell($akciohirlevel);
            $partner->setUjdonsaghirlevelkell($ujdonsaghirlevel);
            $this->getEm()->persist($partner);

            $biztetelcontroller = new bizonylattetelController();

            $biztipus = $this->getRepo(Bizonylattipus::class)->find('megrendeles');
            $megrendfej = new \Entities\Bizonylatfej();
            $megrendfej->setPersistentData();
            switch ($regkell) {
                case 1:
                    $megrendfej->setRegmode(1);
                    break;
                case 2:
                    $megrendfej->setRegmode(2);
                    break;
                default:
                    $megrendfej->setRegmode(3);
                    break;
            }

            $megrendfej->setIp($_SERVER['REMOTE_ADDR']);
            $megrendfej->setReferrer(\mkw\store::getMainSession()->referrer);
            $megrendfej->setBizonylattipus($biztipus);
            $megrendfej->setKelt('');
            $megrendfej->setTeljesites('');
            $megrendfej->setEsedekesseg('');

            $megrendfej->setPartner($partner);

            $megrendfej->setFizmod($this->getEm()->getRepository(Fizmod::class)->find($fizetesimod));
            $megrendfej->setSzallitasimod($this->getEm()->getRepository(Szallitasimod::class)->find($szallitasimod));
            $megrendfej->setRaktar($this->getRepo(Raktar::class)->find(\mkw\store::getParameter(\mkw\consts::Raktar)));

            $megrendfej->setWebshopmessage($webshopmessage);
            $megrendfej->setCouriermessage($couriermessage);

            $valutanem = \mkw\store::getWebshopValutanem();
            $megrendfej->setValutanem($valutanem);

            $arf = $this->getEm()->getRepository(Arfolyam::class)->getActualArfolyam($valutanem, $megrendfej->getTeljesites());
            $megrendfej->setArfolyam($arf->getArfolyam());

            if ($valutanem) {
                $megrendfej->setBankszamla($valutanem->getBankszamla());
            }

            if (\mkw\store::isBarionFizmod($fizetesimod)) {
                $bizstatusz = $this->getRepo(Bizonylatstatusz::class)->find(\mkw\store::getParameter(\mkw\consts::BarionFizetesrevarStatusz));
            } elseif (\mkw\store::isStripeFizmod($fizetesimod)) {
                $bizstatusz = $this->getRepo(Bizonylatstatusz::class)->find(\mkw\store::getParameter(\mkw\consts::StripeFizetesrevarStatusz));
            } else {
                $bizstatusz = $this->getRepo(Bizonylatstatusz::class)->find(\mkw\store::getParameter(\mkw\consts::BizonylatStatuszFuggoben));
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
                $t->setAfa($kt->getAfa());
                $t->setMennyiseg($kt->getMennyiseg());
                $t->setNettoegysar($kt->getNettoegysar());
                $t->setBruttoegysar($kt->getBruttoegysar());
                $t->setEnettoegysar($kt->getEbruttoegysar());
                $t->setEbruttoegysar($kt->getEbruttoegysar());
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
                $t->kerekitBruttoegysar();
                $t->calc();
                $lasttermeknevek[] = $t->getTermeknev();
                $lasttermekids[] = $t->getTermekId();
                $this->getEm()->persist($t);
            }
            $this->getEm()->persist($megrendfej);
            $this->getEm()->flush();

            \mkw\store::getMainSession()->lastmegrendeles = $megrendfej->getId();
            //\mkw\store::getMainSession()->lastemail = $kapcsemail;
            \mkw\store::getMainSession()->lasttermeknevek = $lasttermeknevek;
            \mkw\store::getMainSession()->lasttermekids = $lasttermekids;
            \mkw\store::getMainSession()->lastszallmod = $szallitasimod;
            \mkw\store::getMainSession()->lastfizmod = $fizetesimod;
            $kc = new kosarController();
            $kc->clear();

            if (\mkw\store::isBarionFizmod($fizetesimod)) {
                $bc = new barionController();
                $paymentres = $bc->startPayment($megrendfej);
                if ($paymentres['result']) {
                    Header('Location: ' . $paymentres['redirecturl']);
                } else {
                    Header('Location: ' . \mkw\store::getRouter()->generate('checkoutbarionerror', false, [], ['mr' => $megrendfej->getId()]));
                }
            } elseif (\mkw\store::isStripeFizmod($fizetesimod)) {
                Header('Location: ' . \mkw\store::getRouter()->generate('showcheckoutfizetes'));
            } else {
                if ($bizstatusz) {
                    $megrendfej->sendStatuszEmail($bizstatusz->getEmailtemplate());
                }
                Header('Location: ' . \mkw\store::getRouter()->generate('checkoutkoszonjuk'));
            }
        } else {
            \mkw\store::getMainSession()->params = $this->params;
            \mkw\store::getMainSession()->checkoutErrors = $errors;
            Header('Location: ' . \mkw\store::getRouter()->generate('showcheckout'));
        }
    }

}