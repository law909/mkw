<?php

namespace Traits;

use Controllers\kosarController;

trait PartnerPassReminder
{
    public function createPassReminder()
    {
        $email = $this->params->getStringRequestParam('email');
        if ($email) {
            $p = $this->getRepo()->findNemVendegByEmail($email);
            if (count($p)) {
                $p = $p[0];
                $pr = $p->setPasswordreminder();
                $this->getEm()->persist($p);
                $this->getEm()->flush();
                $emailtpl = $this->getEm()->getRepository('Entities\Emailtemplate')->findOneByNev('jelszoemlekezteto');
                if ($emailtpl) {
                    $tpldata = [
                        'keresztnev' => $p->getKeresztnev(),
                        'vezeteknev' => $p->getVezeteknev(),
                        'fiokurl' => \mkw\store::getRouter()->generate('showaccount', true),
                        'url' => \mkw\store::getFullUrl(),
                        'reminder' => \mkw\store::getRouter()->generate('showpassreminder', true, [
                            'id' => $pr
                        ])
                    ];
                    $subject = $this->getTemplateFactory()->createMainView('string:' . $emailtpl->getTargy());
                    $subject->setVar('user', $tpldata);
                    $body = $this->getTemplateFactory()->createMainView('string:' . $emailtpl->getHTMLSzoveg());
                    $body->setVar('user', $tpldata);
                    $mailer = \mkw\store::getMailer();
                    $mailer->setTo($email);
                    $mailer->setSubject($subject->getTemplateResult());
                    $mailer->setMessage($body->getTemplateResult());
                    $mailer->send();
                }
            }
        }
    }

    public function showPassReminder()
    {
        $route = \mkw\store::getRouter()->generate('show404');
        $pr = $this->params->getStringParam('id');
        if ($pr) {
            $partner = $this->getRepo()->findOneByPasswordreminder($pr);
            if ($partner) {
                $tpl = $this->getTemplateFactory()->createMainView('passreminder.tpl');
                \mkw\store::fillTemplate($tpl);
                $tpl->setVar('reminder', $pr);
                $tpl->printTemplateResult(false);
                return;
            }
        }
        header('Location: ' . $route);
    }

    public function savePassReminder()
    {
        $route = \mkw\store::getRouter()->generate('show404');
        $pr = $this->params->getStringRequestParam('id');
        if ($pr) {
            $user = $this->getRepo()->findOneByPasswordreminder($pr);
            if ($user) {
                $j1 = $this->params->getStringRequestParam('jelszo1');
                $j2 = $this->params->getStringRequestParam('jelszo2');
                if ($j1 === $j2) {
                    $user->setJelszo($j1);
                    $user->clearPasswordreminder();
                    $this->getEm()->persist($user);
                    $this->getEm()->flush();
                    if ($this->login($user)) {
                        $kc = new kosarController();
                        $kc->clear();
                        $route = \mkw\store::getRouter()->generate('showaccount');
                    }
                }
            }
        }
        header('Location: ' . $route);
    }

}
