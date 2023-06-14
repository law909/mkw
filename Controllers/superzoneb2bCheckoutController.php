<?php

namespace Controllers;

class superzoneb2bCheckoutController extends checkoutController
{

    public function save()
    {
        $errorlogtext = [];
        $errors = [];

        $szamlanev = $this->params->getStringRequestParam('szamlanev');
        $szamlairszam = $this->params->getStringRequestParam('szamlairszam');
        $szamlavaros = $this->params->getStringRequestParam('szamlavaros');
        $szamlautca = $this->params->getStringRequestParam('szamlautca');
        $szallnev = $this->params->getStringRequestParam('szallnev');
        $szallirszam = $this->params->getStringRequestParam('szallirszam');
        $szallvaros = $this->params->getStringRequestParam('szallvaros');
        $szallutca = $this->params->getStringRequestParam('szallutca');
        $szalleqszamla = $this->params->getBoolRequestParam('szalleqszamla');
        $webshopmessage = $this->params->getStringRequestParam('webshopmessage');
        $hatarido = $this->params->getDateRequestParam('hatarido');

        if ($szalleqszamla) {
            $szallnev = $szamlanev;
            $szallirszam = $szamlairszam;
            $szallvaros = $szamlavaros;
            $szallutca = $szamlautca;
        }

        $ok = ($szallnev && $szallirszam && $szallvaros && $szallutca &&
            $szamlanev && $szamlairszam && $szamlavaros && $szamlautca && $hatarido);

        if (!$ok) {
            $errorlogtext[] = '1alapadat';
            $errors[] = 'Nem adott meg egy kötelező adatot.';
        }

        $kosartetelek = $this->getRepo('Entities\Kosar')->getDataBySessionId(\Zend_Session::getId());
        $ok = $ok && count($kosartetelek) > 0;
        if (!count($kosartetelek)) {
            $errorlogtext[] = '4ureskosar';
            $errors[] = 'Üres a kosara.';
        }

        if ($ok) {
            $partner = \mkw\store::getLoggedInUser();
            $nullasafa = $this->getRepo('Entities\Afa')->find(\mkw\store::getParameter(\mkw\consts::NullasAfa));
            $biztetelcontroller = new bizonylattetelController($this->params);
            $valutanem = $partner->getValutanem();

            $biztipus = $this->getRepo('Entities\Bizonylattipus')->find('megrendeles');
            $megrendfej = new \Entities\Bizonylatfej();
            $megrendfej->setPersistentData();
            $megrendfej->setIp($_SERVER['REMOTE_ADDR']);
            $megrendfej->setReferrer(\mkw\store::getMainSession()->referrer);
            $megrendfej->setBizonylattipus($biztipus);
            $megrendfej->setKelt('');
            $megrendfej->setTeljesites('');
            $megrendfej->setEsedekesseg('');
            $megrendfej->setHatarido(new \DateTime(\mkw\store::convDate($hatarido)));

            $megrendfej->setPartner($partner);

            $megrendfej->setPartnernev($szamlanev);
            $megrendfej->setPartnerirszam($szamlairszam);
            $megrendfej->setPartnervaros($szamlavaros);
            $megrendfej->setPartnerutca($szamlautca);
            $megrendfej->setSzallnev($szallnev);
            $megrendfej->setSzallirszam($szallirszam);
            $megrendfej->setSzallvaros($szallvaros);
            $megrendfej->setSzallutca($szallutca);

            $megrendfej->setFizmod($partner->getFizmod());
            $megrendfej->setSzallitasimod($partner->getSzallitasimod());
            $megrendfej->setValutanem($valutanem);
            $megrendfej->setWebshopmessage($webshopmessage);
            $arf = $this->getEm()->getRepository('Entities\Arfolyam')->getActualArfolyam($valutanem, $megrendfej->getTeljesites());
            $megrendfej->setArfolyam($arf->getArfolyam());
            $raktarid = \mkw\store::getParameter(\mkw\consts::Raktar);
            $megrendfej->setRaktar($this->getRepo('Entities\Raktar')->find($raktarid));
            if ($valutanem) {
                $megrendfej->setBankszamla($valutanem->getBankszamla());
            }
            $bizstatusz = $this->getRepo('Entities\Bizonylatstatusz')->find(\mkw\store::getParameter(\mkw\consts::BizonylatStatuszFuggoben));
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
            //\mkw\store::getMainSession()->lastemail = $kapcsemail;
            \mkw\store::getMainSession()->lasttermeknevek = $lasttermeknevek;
            \mkw\store::getMainSession()->lasttermekids = $lasttermekids;
            //\mkw\store::getMainSession()->lastszallmod = $szallitasimod;
            //\mkw\store::getMainSession()->lastfizmod = $fizetesimod;
            $kc = new kosarController($this->params);
            $kc->clear();

            if ($bizstatusz) {
                $megrendfej->sendStatuszEmail($bizstatusz->getEmailtemplate());
            }
            Header('Location: ' . \mkw\store::getRouter()->generate('checkoutkoszonjuk'));
        } else {
            \mkw\store::getMainSession()->params = $this->params;
            \mkw\store::getMainSession()->checkoutErrors = $errors;
            Header('Location: ' . \mkw\store::getRouter()->generate('showcheckout'));
        }
    }

}