<?php

namespace Controllers;

use Entities\Emailtemplate;
use Entities\Partner;
use Entities\Termek;
use Entities\TermekErtesito;

class termekertesitoController extends \mkwhelpers\MattableController
{

    public function __construct()
    {
        $this->setEntityName(TermekErtesito::class);
        parent::__construct();
    }

    protected function loadVars($t)
    {
        if (!$t) {
            $t = new \Entities\TermekErtesito();
            $this->getEm()->detach($t);
        }
        $x = $this->getEntityFieldsArray($t);
        $x['createdstr'] = $t->getCreatedStr();
        $x['sentstr'] = $t->getSentStr();
        $x['termek'] = $t->getTermek()?->toKosar(null);
        return $x;
    }

    protected function saveData()
    {
        $tid = $this->params->getIntRequestParam('termekid');
        $email = $this->params->getStringRequestParam('email');
        if ($tid && $email) {
            parent::saveData();
        }
    }

    protected function setFields($obj)
    {
        $termek = $this->getRepo(Termek::class)->find($this->params->getIntRequestParam('termekid'));
        if ($termek) {
            $obj->setTermek($termek);
            $partner = $this->getRepo(Partner::class)->getLoggedInUser();
            if ($partner) {
                $obj->setPartner($partner);
            }
            $obj->setEmail($this->params->getStringRequestParam('email'));
        }
        return $obj;
    }

    public function getAllByPartner($partner)
    {
        $list = $this->getRepo()->getByPartner($partner);
        $ret = [];
        foreach ($list as $l) {
            $ret[] = $this->loadVars($l);
        }
        return $ret;
    }

    public function sendErtesito($termek)
    {
        $mailer = \mkw\store::getMailer();
        $emailtpl = $this->getEm()->getRepository(Emailtemplate::class)->findOneByNev('termekertesito');
        if ($emailtpl) {
            $ertesitok = $this->getRepo()->getByTermek($termek);
            foreach ($ertesitok as $ert) {
                if ($ert->getEmail()) {
                    $p = $this->getEm()->getRepository(Partner::class)->findNemVendegByEmail($ert->getEmail());
                    if (count($p)) {
                        $p = $p[0];
                        $knev = $p->getKeresztnev();
                        $vnev = $p->getVezeteknev();
                    } else {
                        $knev = null;
                        $vnev = null;
                    }
                    $user = [
                        'keresztnev' => $knev,
                        'vezeteknev' => $vnev,
                        'fiokurl' => \mkw\store::getRouter()->generate('showaccount', \mkw\store::getConfigValue('mainurl', true)),
                        'url' => \mkw\store::getFullUrl(null, \mkw\store::getConfigValue('mainurl'))
                    ];
                    $term = [
                        'nev' => $termek->getNev(),
                        'url' => \mkw\store::getRouter()->generate('showtermek', \mkw\store::getConfigValue('mainurl', true), ['slug' => $termek->getSlug()]),
                        'kepurl' => \mkw\store::getFullUrl($termek->getKepurlSmall(), \mkw\store::getConfigValue('mainurl'))
                    ];
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
                    $mailer->clear();
                    $ert->setSent('');
                    $this->getEm()->persist($ert);
                    $this->getEm()->flush();
                }
            }
        }
    }
}
