<?php

namespace Controllers;

use Carbon\Carbon;
use Entities\Partner;
use mkw\store;

class adategyeztetoController extends \mkwhelpers\Controller {

    public function view() {
        $view = $this->getTemplateFactory()->createMainView('adategyezteto.tpl');
        $view->printTemplateResult();
    }

    public function check() {
        $view = $this->getTemplateFactory()->createMainView('adategyeztetoadatlap.tpl');
        $email = $this->params->getStringRequestParam('email');
        if ($email) {
            /** @var Partner $partner */
            $partner = $this->getRepo('Entities\Partner')->findOneBy(array('email' => $email));
            if ($partner) {
                $view->setVar('vezeteknev', $partner->getVezeteknev());
                $view->setVar('keresztnev', $partner->getKeresztnev());
                $view->setVar('irszam', $partner->getIrszam());
                $view->setVar('varos', $partner->getVaros());
                $view->setVar('utca', $partner->getUtca());
                $view->setVar('hazszam', $partner->getHazszam());
                $view->setVar('hirlevelkell', $partner->getUjdonsaghirlevelkell());
                $view->setVar('msg', 'Kérjük ellenőrizd az adataidat és nyomd meg a "Mentés" gombot!');
            }
            else {
                $view->setVar('msg', 'Sajnos nem ismerünk az emailcímed alapján. Kérjük add meg az adataidat és nyomd meg a "Mentés" gombot!');
            }
            $view->printTemplateResult();
        }
    }

    public function save() {
        $email = $this->params->getStringRequestParam('email');
        $vezeteknev = $this->params->getStringRequestParam('vezeteknev');
        $keresztnev = $this->params->getStringRequestParam('keresztnev');
        $irszam = $this->params->getStringRequestParam('irszam');
        $varos = $this->params->getStringRequestParam('varos');
        $utca = $this->params->getStringRequestParam('utca');
        $hazszam = $this->params->getStringRequestParam('hazszam');
        $hirlevelkell = $this->params->getBoolRequestParam('hirlevelkell');
        if ($email) {
            /** @var Partner $partner */
            $partner = $this->getRepo('Entities\Partner')->findOneBy(array('email' => $email));
            if (!$partner) {
                $partner = new Partner();
                $partner->setEmail($email);
            }
            $partner->setVezeteknev($vezeteknev);
            $partner->setKeresztnev($keresztnev);
            $partner->setNev(implode(' ', array($vezeteknev, $keresztnev)));
            $partner->setIrszam($irszam);
            $partner->setVaros($varos);
            $partner->setUtca($utca);
            $partner->setHazszam($hazszam);
            $partner->setUjdonsaghirlevelkell($hirlevelkell);

            $this->getEm()->persist($partner);
            $this->getEm()->flush();

            \mkw\store::writelog($email . ':' . $vezeteknev . ':' . $keresztnev, 'adategy.log');
        }
    }

}