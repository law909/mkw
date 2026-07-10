<?php

namespace Traits;

trait PartnerRegistration
{
    public function saveRegistrationData($vendeg = false)
    {
        $email = $this->params->getStringRequestParam('kapcsemail');
        if (!$email) {
            $email = $this->params->getStringRequestParam('email');
        }
        $ps = $this->getRepo()->findByEmail($email);
        if (count($ps) > 0) {
            $t = $ps[0];
        } else {
            $t = new \Entities\Partner();
        }
        $t = $this->setFields($t, null, 'registration');
        $t->setVendeg($vendeg);
        $this->getEm()->persist($t);
        $this->getEm()->flush();
        return $t;
    }

    public function checkApiRegData($data)
    {
        $ret = [];
        if (!$data['email']) {
            $ret[] = 'Empty field: email';
        }
        if (!$data['password']) {
            $ret[] = 'Empty field: password';
        }
        if (!$data['nev']) {
            $ret[] = 'Empty field: nev';
        }
        if (!$data['irszam']) {
            $ret[] = 'Empty field: irszam';
        }
        if (!$data['varos']) {
            $ret[] = 'Empty field: varos';
        }
        if (!$data['utca']) {
            $ret[] = 'Empty field: utca';
        }
        return $ret;
    }

    public function saveApiRegData($data, $consumer)
    {
        $p = $this->getRepo()->findOneBy(['email' => $data['email']]);
        if (!$p) {
            $p = new \Entities\Partner();
        }
        $p->setEmail($data['email']);
        $p->setJelszo($data['password']);
        $p->setVezeteknev($data['vezeteknev']);
        $p->setKeresztnev($data['keresztnev']);
        $p->setNev($data['nev']);
        $p->setIrszam($data['irszam']);
        $p->setVaros($data['varos']);
        $p->setUtca($data['utca']);
        $p->setHazszam($data['hazszam']);
        $p->setTelefon($data['telefon']);
        $p->setEmail($data['email']);
        $p->setAdoszam($data['adoszam']);
        $p->setEuadoszam($data['euadoszam']);
        $p->setSzallnev($data['szallnev']);
        $p->setSzallirszam($data['szallirszam']);
        $p->setSzallvaros($data['szallvaros']);
        $p->setSzallutca($data['szallutca']);
        $p->setSzallhazszam($data['szallhazszam']);
        $p->setVendeg((bool)$data['vendeg']);

        $p->setApireg(true);
        $p->setApiconsumer($consumer);

        $this->getEm()->persist($p);
        $this->getEm()->flush();
        return $p;
    }

    public function checkemail()
    {
        $email = $this->params->getStringRequestParam('email');

        $ret = [];
        $ret['hibas'] = !\mkw\validate::is($email, 'EmailAddress');
        if (!$ret['hibas']) {
            if (!$this->params->getBoolRequestParam('dce')) {
                $ret['hibas'] = $this->getRepo()->countByEmail($email) > 0;
                if ($ret['hibas']) {
                    $ret['uzenet'] = t('Már létezik ez az emailcím.');
                }
            }
        } else {
            $ret['uzenet'] = t('Kérjük emailcímet adjon meg.');
        }
        echo json_encode($ret);
    }

    public function showPubRegistration()
    {
        $view = $this->getTemplateFactory()->createMainView('pubregistration.tpl');
        \mkw\store::fillTemplate($view);
        $view->printTemplateResult(true);
    }

    public function showPubRegistrationThx()
    {
        $view = $this->getTemplateFactory()->createMainView('pubregistrationthx.tpl');
        \mkw\store::fillTemplate($view);
        $view->printTemplateResult(true);
    }

    public function saveRegistration()
    {
        $hibas = false;
        $hibak = [];

        $vezeteknev = $this->params->getStringRequestParam('vezeteknev');
        $keresztnev = $this->params->getStringRequestParam('keresztnev');
        $email = $this->params->getStringRequestParam('email');
        $jelszo1 = $this->params->getStringRequestParam('jelszo1');

        $r = $this->checkPartnerData('adataim');
        $hibas = $hibas || $r['hibas'];
        $hibak = array_merge($hibak, $r['hibak']);

        $r = $this->checkPartnerData('jelszo');
        $hibas = $hibas || $r['hibas'];
        $hibak = array_merge($hibak, $r['hibak']);

        if (!$hibas) {
            $ps = $this->getRepo()->findVendegByEmail($email);
            if (count($ps) > 0) {
                $t = $ps[0];
            } else {
                $t = new \Entities\Partner();
            }
            $t = $this->setFields($t, 'add', 'registration');
            $this->getEm()->persist($t);
            $this->getEm()->flush();
            $this->login($email, $jelszo1);
            $emailtpl = $this->getEm()->getRepository('Entities\Emailtemplate')->findOneByNev('regisztracio');
            if ($emailtpl) {
                $tpldata = [
                    'keresztnev' => $keresztnev,
                    'vezeteknev' => $vezeteknev,
                    'fiokurl' => \mkw\store::getRouter()->generate('showaccount', true),
                    'url' => \mkw\store::getFullUrl()
                ];
                $subject = $this->getTemplateFactory()->createMainView('string:' . $emailtpl->getTargy());
                $subject->setVar('user', $tpldata);
                $body = $this->getTemplateFactory()->createMainView('string:' . $emailtpl->getHTMLSzoveg());
                $body->setVar('user', $tpldata);
                /*
                $mailer = \mkw\store::getMailer();
                $mailer->setTo($email);
                $mailer->setSubject($subject->getTemplateResult());
                $mailer->setMessage($body->getTemplateResult());
                $mailer->send();
                */
            }
            \mkw\session::writeClose();
            Header('Location: ' . \mkw\store::getRouter()->generate('showaccount'));
        } else {
            $this->showRegistrationForm($vezeteknev, $keresztnev, $email, $hibak);
        }
    }

    public function showRegistrationForm($vezeteknev = '', $keresztnev = '', $email = '', $hibak = [])
    {
        $view = $this->getTemplateFactory()->createMainView('regisztracio.tpl');
        $view->setVar('pagetitle', t('Regisztráció') . ' - ' . \mkw\store::getParameter(\mkw\consts::Oldalcim));
        $view->setVar('vezeteknev', $vezeteknev);
        $view->setVar('keresztnev', $keresztnev);
        $view->setVar('email', $email);
        $view->setVar('hibak', $hibak);
        \mkw\store::fillTemplate($view);
        $view->printTemplateResult(true);
    }

    public function savePubRegistration()
    {
        $user = new \Entities\Partner();
        $subject = 'pubreg';

        $hiba = $this->checkPartnerData($subject);
        if (!$hiba['hibas']) {
            $user = $this->setFields($user, 'edit', $subject);
            $this->getEm()->persist($user);
            $this->getEm()->flush();
            Header('Location: ' . \mkw\store::getRouter()->generate('pubregistrationthx'));
        } else {
            echo $hiba['hibak'];
        }
    }

}
