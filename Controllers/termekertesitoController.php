<?php

namespace Controllers;

use mkw\store;

class termekertesitoController extends \mkwhelpers\MattableController {

    public function __construct($params) {
        $this->setEntityName('Entities\TermekErtesito');
        parent::__construct($params);
    }

    protected function loadVars($t) {
        $x = array();
        if (!$t) {
            $t = new \Entities\TermekErtesito();
            $this->getEm()->detach($t);
        }
        $x['id'] = $t->getId();
        $x['createdstr'] = $t->getCreatedStr();
        $x['sentstr'] = $t->getSentStr();
        $termek = $t->getTermek();
        if ($termek) {
            $x['termek'] = $termek->toKosar(null);
        }
        return $x;
    }

    protected function saveData() {
        $tid = $this->params->getIntRequestParam('termekid');
        $email = $this->params->getStringRequestParam('email');
        if ($tid && $email) {
            parent::saveData();
        }
    }

    protected function setFields($obj) {
        $termek = $this->getRepo('Entities\Termek')->find($this->params->getIntRequestParam('termekid'));
        if ($termek) {
            $obj->setTermek($termek);
            $partner = $this->getRepo('Entities\Partner')->getLoggedInUser();
            if ($partner) {
                $obj->setPartner($partner);
            }
            $obj->setEmail($this->params->getStringRequestParam('email'));
        }
        return $obj;
    }

    public function getAllByPartner($partner) {
        $list = $this->getRepo()->getByPartner($partner);
        $ret = array();
        foreach ($list as $l) {
            $ret[] = $this->loadVars($l);
        }
        return $ret;
    }

    public function sendErtesito($termek) {
        $mailer = new \mkw\mkwmailer();
        $emailtpl = $this->getEm()->getRepository('Entities\Emailtemplate')->findOneByNev('termekertesito');
        if ($emailtpl) {
            $ertesitok = $this->getRepo()->getByTermek($termek);
            foreach($ertesitok as $ert) {
                if ($ert->getEmail()) {
                    $p = $this->getEm()->getRepository('Entities\Partner')->findNemVendegByEmail($ert->getEmail());
                    if (count($p)) {
                        $p = $p[0];
                        $knev = $p->getKeresztnev();
                        $vnev = $p->getVezeteknev();
                    }
                    else {
                        $knev = null;
                        $vnev = null;
                    }
                    $user = array(
                        'keresztnev' => $knev,
                        'vezeteknev' => $vnev,
                        'fiokurl' => \mkw\Store::getRouter()->generate('showaccount', \mkw\Store::getConfigValue('mainurl', true)),
                        'url' => \mkw\Store::getFullUrl(null, \mkw\Store::getConfigValue('mainurl'))
                    );
                    $term = array(
                        'nev' => $termek->getNev(),
                        'url' => \mkw\Store::getRouter()->generate('showtermek', \mkw\Store::getConfigValue('mainurl', true), array('slug' => $termek->getSlug())),
                        'kepurl' => \mkw\Store::getFullUrl($termek->getKepurlSmall(), \mkw\Store::getConfigValue('mainurl'))
                    );
                    $subject = $this->getTemplateFactory()->createMainView('string:' . $emailtpl->getTargy());
                    $subject->setVar('user', $user);
                    $subject->setVar('termek', $term);
                    $body = $this->getTemplateFactory()->createMainView('string:' . $emailtpl->getHTMLSzoveg());
                    $body->setVar('user', $user);
                    $body->setVar('termek', $term);
                    $mailer->setTo($ert->getEmail());
                    $mailer->setSubject($subject->getTemplateResult());
                    $mailer->setMessage($body->getTemplateResult());
                    $mailer->send();
                    $ert->setSent('');
                    $this->getEm()->persist($ert);
                    $this->getEm()->flush();
                }
            }
        }
    }
}
