<?php

namespace Controllers;

class b2bpartnerController extends partnerController {

    public function saveRegistration() {
        $hibas = false;
        $hibak = array();

        $vezeteknev = $this->params->getStringRequestParam('vezeteknev');
        $keresztnev = $this->params->getStringRequestParam('keresztnev');
        $email = $this->params->getStringRequestParam('email');

        $r = $this->checkPartnerData('adataim');
        $hibas = $hibas || $r['hibas'];
        $hibak = array_merge($hibak, $r['hibak']);

        $r = $this->checkPartnerData('jelszo');
        $hibas = $hibas || $r['hibas'];
        $hibak = array_merge($hibak, $r['hibak']);

        if (!$hibas) {
            /** @var \Entities\Uzletkoto $uk */
            $uk = \mkw\Store::getLoggedInUK();
            if ($uk) {
                $partner = new \Entities\Partner();
                // emailt az uzletkotonek kell kuldeni es egy kozponti cimre
                $partner = $this->setFields($partner, 'add', 'adataim');
                $partner = $this->setFields($partner, 'add', 'bankiadatok');
                $partner = $this->setFields($partner, 'add', 'szamlaadatok');
                $partner = $this->setFields($partner, 'add', 'szallitasiadatok');
                $partner = $this->setFields($partner, 'add', 'discounts');
                $partner = $this->setFields($partner, 'add', 'jelszo');
                $partner = $this->setFields($partner, 'add', 'registration');
                $partner->setUzletkoto($uk);
                $partner->setBizonylatnyelv($uk->getPartnerbizonylatnyelv());
                $partner->setValutanem($uk->getPartnervalutanem());
                $partner->setFizmod($uk->getPartnerfizmod());
                $partner->setSzallitasimod($uk->getPartnerszallitasimod());
                $partner->setSzamlatipus($uk->getPartnerszamlatipus());
                $partner->setTermekarazonosito($uk->getPartnertermekarazonosito());
                if (\mkw\Store::getTheme() === 'superzone') {
                    $spanyol = $this->getRepo('Entities\Partnercimketorzs')->find(\mkw\Store::getParameter(\mkw\consts::SpanyolCimke));
                    if ($spanyol) {
                        $partner->addCimke($spanyol);
                    }
                }
                $this->getEm()->persist($partner);
                $this->getEm()->flush();

                $this->changePartner($partner->getId());

                if ($uk->getEmail()) {
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
                        $mailer->setTo($uk->getEmail());
                        $mailer->setSubject($subject->getTemplateResult());
                        $mailer->setMessage($body->getTemplateResult());
                        $mailer->send();
                    }
                }
                //\Zend_Session::writeClose();
                Header('Location: ' . \mkw\Store::getRouter()->generate('showaccount'));
            }
            else {
                Header('Location: ' . \mkw\Store::getRouter()->generate('showlogin'));
            }
        }
        else {
            $this->showRegistrationForm($vezeteknev, $keresztnev, $email, $hibak);
        }
    }

    public function showRegistrationForm($hibak = array()) {
        $view = $this->getTemplateFactory()->createMainView('regisztracio.tpl');
        $view->setVar('pagetitle', t('Regisztráció') . ' - ' . \mkw\Store::getParameter(\mkw\consts::Oldalcim));
        $view->setVar('hibak', $hibak);
        \mkw\Store::fillTemplate($view);
        $ptcsk = new partnertermekcsoportkedvezmenyController($this->params);
        $ptcsklist = $ptcsk->getFiokList(true);
        $view->setVar('discountlist', $ptcsklist);
        $view->printTemplateResult(true);
    }

    public function changePartner($ujpartnerid = null) {
        if (!$ujpartnerid) {
            $ujpartnerid = $this->params->getIntRequestParam('partner');
        }
        $user = $this->getRepo()->find($ujpartnerid);
        $regiuser = \mkw\Store::getLoggedInUser();
        if ($user) {

            // pseudo logout old user
            \mkw\Store::clearLoggedInUser();
            if ($regiuser) {
                $regiuser->setSessionid('');
                $this->getEm()->persist($regiuser);
                $this->getEm()->flush();
            }
            $kc = new kosarController($this->params);
            $kc->removeSessionId(\Zend_Session::getId());
            \mkw\Store::getMainSession()->pk = null;

            // pseudo login new user
            $user->setSessionid(\Zend_Session::getId());
            $user->setUtolsoklikk();
            $user->clearPasswordreminder();
            $this->getEm()->persist($user);
            $this->getEm()->flush();
            \mkw\Store::getMainSession()->pk = $user->getId();
        }
    }

}