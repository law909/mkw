<?php

namespace Controllers;

use Carbon\Carbon;
use Entities\JogaBejelentkezes;
use Entities\Orarend;
use mkw\store;
use mkwhelpers\FilterDescriptor;

class cronController extends \mkwhelpers\Controller {

    public function run() {
        if (\mkw\store::isDarshan()) {
            $this->checkJogaBejelentkezes();
        }
    }

    public function checkJogaBejelentkezes() {
        /*
        $realma = Carbon::now();
        $tesztma = Carbon::create($realma->year, $realma->month, $realma->day, 12, 31);
        Carbon::setTestNow($tesztma);
        */

        $ma = Carbon::now();
        $nap = $ma->format('N');
        $filter = new FilterDescriptor();
        $filter->addFilter('nap', '=', $nap);
        $filter->addFilter('inaktiv', '=', false);
        $filter->addFilter('bejelentkezeskell', '=', true);

        /** @var Orarend $ora */
        $maiorak = $this->getRepo(Orarend::class)->getAll($filter);
        foreach ($maiorak as $ora) {
            $oradatetime = Carbon::createFromTimeString($ora->getKezdetStr());
            $kul = $ma->floatDiffInMinutes($oradatetime, false);

            \mkw\store::writelog('---------------------------------');
            \mkw\store::writelog($ora->getId());
            \mkw\store::writelog($ora->getNev());
            \mkw\store::writelog('most:      ' . $ma->format(\mkw\store::$DateTimeFormat));
            \mkw\store::writelog('ora ekkor: ' . $oradatetime->format(\mkw\store::$DateTimeFormat));
            \mkw\store::writelog('elteres:   ' . $kul);

            if ($kul > 115 && $kul < 125) {
                $bejfilter = new FilterDescriptor();
                $bejfilter->addFilter('orarend', '=', $ora);
                $bejfilter->addFilter('datum', '=', $ma);
                $bejcnt = $this->getRepo(JogaBejelentkezes::class)->getCount($bejfilter);
                if (($ora->getMinbejelentkezes() > 0 && $bejcnt < $ora->getMinbejelentkezes()) || ($bejcnt == 0)) {
                    if ($bejcnt == 0) {
                        $emailtpl = $this->getRepo('\Entities\Emailtemplate')->find(\mkw\store::getParameter(\mkw\consts::JogaNemjonsenkiSablon));
                        if ($emailtpl) {
                            $subject = \mkw\store::getTemplateFactory()->createMainView('string:' . $emailtpl->getTargy());
                            $body = \mkw\store::getTemplateFactory()->createMainView('string:' . str_replace('&#39;', '\'', html_entity_decode($emailtpl->getHTMLSzoveg())));
                            $body->setVar('megszolitas', $ora->getDolgozoNev());
                            $body->setVar('oranev', $ora->getNev());
                            $body->setVar('orakezdet', $ora->getKezdetStr());
                            $body->setVar('oradatum', $ma->format(\mkw\store::$DateFormat));

                            $tanaremail = $ora->getDolgozoEmail();
                            $mailer = \mkw\store::getMailer();

                            if ($tanaremail) {
                                $mailer->addTo($tanaremail);
                                $mailer->setSubject($subject->getTemplateResult());
                                $mailer->setMessage($body->getTemplateResult());

                                $mailer->send();
                            }
                        }
                        \mkw\store::writelog('nem jon senki, tanart ertesiteni h ne jojjon');
                    }
                    else {
                        $emailtpl = $this->getRepo('\Entities\Emailtemplate')->find(\mkw\store::getParameter(\mkw\consts::JogaNemjelenteztekelegenTanarnakSablon));
                        if ($emailtpl) {
                            $subject = \mkw\store::getTemplateFactory()->createMainView('string:' . $emailtpl->getTargy());
                            $body = \mkw\store::getTemplateFactory()->createMainView('string:' . str_replace('&#39;', '\'', html_entity_decode($emailtpl->getHTMLSzoveg())));
                            $body->setVar('megszolitas', $ora->getDolgozoNev());
                            $body->setVar('oranev', $ora->getNev());
                            $body->setVar('orakezdet', $ora->getKezdetStr());
                            $body->setVar('oradatum', $ma->format(\mkw\store::$DateFormat));

                            $tanaremail = $ora->getDolgozoEmail();
                            $mailer = \mkw\store::getMailer();

                            if ($tanaremail) {
                                $mailer->addTo($tanaremail);
                                $mailer->setSubject($subject->getTemplateResult());
                                $mailer->setMessage($body->getTemplateResult());

                                $mailer->send();
                            }
                        }

                        $emailtpl = $this->getRepo('\Entities\Emailtemplate')->find(\mkw\store::getParameter(\mkw\consts::JogaNemjelentkeztekelegenGyakorlonakSablon));
                        if ($emailtpl) {
                            $jelentkezesek = $this->getRepo(JogaBejelentkezes::class)->getAll($bejfilter);
                            /** @var JogaBejelentkezes $jelentkezes */
                            foreach ($jelentkezesek as $jelentkezes) {
                                $subject = \mkw\store::getTemplateFactory()->createMainView('string:' . $emailtpl->getTargy());
                                $body = \mkw\store::getTemplateFactory()->createMainView('string:' . str_replace('&#39;', '\'', html_entity_decode($emailtpl->getHTMLSzoveg())));
                                $body->setVar('megszolitas', $jelentkezes->getPartnernev());
                                $body->setVar('oranev', $ora->getNev());
                                $body->setVar('orakezdet', $ora->getKezdetStr());
                                $body->setVar('oradatum', $ma->format(\mkw\store::$DateFormat));

                                $email = $jelentkezes->getPartneremail();
                                $mailer = \mkw\store::getMailer();

                                if ($email) {
                                    $mailer->addTo($email);
                                    $mailer->setSubject($subject->getTemplateResult());
                                    $mailer->setMessage($body->getTemplateResult());

                                    $mailer->send();
                                }
                            }
                        }
                        \mkw\store::writelog('jajajj, nincsenek elegen, lemondjuk a tanarnal es a jelentkezoknel');
                    }
                }
                else {
                    $emailtpl = $this->getRepo('\Entities\Emailtemplate')->find(\mkw\store::getParameter(\mkw\consts::JogaElegenjelentkeztekTanarnakSablon));
                    if ($emailtpl) {
                        $subject = \mkw\store::getTemplateFactory()->createMainView('string:' . $emailtpl->getTargy());
                        $body = \mkw\store::getTemplateFactory()->createMainView('string:' . str_replace('&#39;', '\'', html_entity_decode($emailtpl->getHTMLSzoveg())));
                        $body->setVar('megszolitas', $ora->getDolgozoNev());
                        $body->setVar('oranev', $ora->getNev());
                        $body->setVar('orakezdet', $ora->getKezdetStr());
                        $body->setVar('oradatum', $ma->format(\mkw\store::$DateFormat));
                        $jelz = [];
                        $jelentkezesek = $this->getRepo(JogaBejelentkezes::class)->getAll($bejfilter);
                        /** @var JogaBejelentkezes $jelentkezes */
                        foreach ($jelentkezesek as $jelentkezes) {
                            $jelz[] = [
                                'nev' => $jelentkezes->getPartnernev()
                            ];
                        }
                        $body->setVar('jelentkezesek', $jelz);
                        $tanaremail = $ora->getDolgozoEmail();
                        $mailer = \mkw\store::getMailer();

                        if ($tanaremail) {
                            $mailer->addTo($tanaremail);
                            $mailer->setSubject($subject->getTemplateResult());
                            $mailer->setMessage($body->getTemplateResult());

                            $mailer->send();
                        }
                    }
                    \mkw\store::writelog('okenak tunik');
                }
                \mkw\store::writelog($bejcnt . ' < ' . $ora->getMinbejelentkezes());
            }
            else {
                \mkw\store::writelog('meg messze van az ora, varunk tovabb: ' . $kul);
            }
        }
    }
}