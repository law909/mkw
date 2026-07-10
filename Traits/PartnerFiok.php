<?php

namespace Traits;

use Controllers\termekertesitoController;
use Controllers\megrendelesfejController;
use Controllers\szamlafejController;
use Controllers\garanciaugyfejController;
use Controllers\orszagController;
use Controllers\korzetszamController;
use Controllers\partnertermekcsoportkedvezmenyController;

trait PartnerFiok
{
    public function showAccount()
    {
        /** @var \Entities\Partner $user */
        $user = $this->getRepo()->getLoggedInUser();
        if ($user) {
            $view = $this->getTemplateFactory()->createMainView('fiok.tpl');
            \mkw\store::fillTemplate($view);

            $view->setVar('pagetitle', t('Fiók') . ' - ' . \mkw\store::getParameter(\mkw\consts::Oldalcim));
            $view->setVar('user', $this->loadVars($user)); // fillTemplate-ben megtortenik

            $tec = new termekertesitoController();
            $view->setVar('ertesitok', $tec->getAllByPartner($user));

            $megrc = new megrendelesfejController();
            $megrlist = $megrc->getFiokList();
            $view->setVar('megrendeleslist', $megrlist);

            $megrlist = $megrc->getFiokList(true);
            $view->setVar('mindenmegrendeleslist', $megrlist);

            $szamlac = new szamlafejController();
            $szamlalist = $szamlac->getFiokList();
            $view->setVar('szamlalist', $szamlalist);

            $garugyc = new garanciaugyfejController();
            $garugylist = $garugyc->getFiokList();
            $view->setVar('garanciaugylist', $garugylist);

            $orszagc = new orszagController();
            $view->setVar('orszaglist', $orszagc->getSelectList($user->getOrszagId()));
            $view->setVar('szallorszaglist', $orszagc->getSelectList($user->getSzallorszagId()));

            $telkorzetc = new korzetszamController();
            $view->setVar('telkorzetlist', $telkorzetc->getSelectList($user->getTelkorzet()));

            $ptcsk = new partnertermekcsoportkedvezmenyController();
            $ptcsklist = $ptcsk->getFiokList();
            $view->setVar('discountlist', $ptcsklist);
            $view->printTemplateResult(true);
        } else {
            header('Location: ' . \mkw\store::getRouter()->generate('showlogin'));
        }
    }

    /**
     * @param $subject
     * @param \Entities\Partner|null $user
     *
     * @return array
     */
    public function checkPartnerData($subject)
    {
        $ret = [];
        $hibas = false;
        $hibak = [];
        switch ($subject) {
            case 'adataim':
                $vezeteknev = $this->params->getStringRequestParam('vezeteknev');
                $keresztnev = $this->params->getStringRequestParam('keresztnev');
                $email = $this->params->getStringRequestParam('email');
                if (!\mkw\validate::is($email, 'EmailAddress')) {
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
                } else {
                    $hibak['regijelszo'] = t('Rossz régi jelszót adott meg');
                    $hibak['hibas'] = 2;
                }
                break;
            case 'b2bregistration':
                break;
        }
        $ret = [
            'hibas' => $hibas,
            'hibak' => $hibak
        ];
        return $ret;
    }

    public function saveAccount()
    {
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
                } else {
                    echo json_encode($hiba['hibak']);
                }
            } elseif ($jax) {
                echo json_encode($hiba['hibak']);
            } else {
                echo $hiba['hibak'];
            }
        } else {
            header('Location: ' . \mkw\store::getRouter()->generate('showlogin'));
        }
    }

}
