<?php

namespace Traits;

use Entities\Emailtemplate;
use Entities\Partner;
use Entities\PartnerTermekkategoriaKedvezmeny;

trait PartnerBulkOps
{
    public function doAnonym()
    {
        $partnerid = $this->params->getIntRequestParam('id');
        $this->getRepo()->doAnonym($partnerid);
    }

    public function arsavcsere()
    {
        $ids = $this->params->getArrayRequestParam('ids');
        $arsav = $this->params->getIntRequestParam('arsav');
        $filter = new \mkwhelpers\FilterDescriptor();
        if ($ids) {
            $filter->addFilter('id', 'IN', $ids);
        }

        $partnerek = $this->getRepo()->getAll($filter);

        /** @var Partner $partner */
        foreach ($partnerek as $partner) {
            $partner->setArsav($arsav);
            $this->getEm()->persist($partner);
        }
        $this->getEm()->flush();
    }

    public function setTermekkategoriaKedvezmenyek()
    {
        $ids = $this->params->getArrayRequestParam('ids');
        $termekfaid = $this->params->getIntRequestParam('termekfa');
        $kedvvalt = $this->params->getNumRequestParam('kedv');
        if (!$termekfaid) {
            return;
        }

        $filter = new \mkwhelpers\FilterDescriptor();
        if ($ids) {
            $filter->addFilter('id', 'IN', $ids);
        }
        $partnerek = $this->getRepo()->getAll($filter);
        /** @var Partner $partner */
        foreach ($partnerek as $partner) {
            /** @var PartnerTermekkategoriaKedvezmeny $kdv */
            foreach ($partner->getTermekkategoriakedvezmenyek() as $kdv) {
                if ($kdv->getTermekfaId() == $termekfaid) {
                    $kdv->setKedvezmeny($kedvvalt);
                    $this->getEm()->persist($kdv);
                }
            }
        }
        $this->getEm()->flush();
    }

    public function setflag()
    {
        $id = $this->params->getIntRequestParam('id');
        $kibe = $this->params->getBoolRequestParam('kibe');
        $flag = $this->params->getStringRequestParam('flag');
        /** @var \Entities\Partner $obj */
        $obj = $this->getRepo()->find($id);
        if ($obj) {
            switch ($flag) {
                case 'inaktiv':
                    $obj->setInaktiv($kibe);
                    break;
            }
            $this->getEm()->persist($obj);
            $this->getEm()->flush();
        }
    }

    /**
     * A partnerlista „Új jelszó" gombja: generált jelszó, a Beállításokban választott sablonnal kiküldve.
     * A jelszó csak sikeres küldés után íródik a partnerre, így hibánál a régi marad érvényben.
     */
    public function sendGeneratedJelszo()
    {
        /** @var Partner $partner */
        $partner = $this->getRepo()->find($this->params->getIntRequestParam('id'));
        /** @var Emailtemplate $sablon */
        $sablon = $this->getRepo(Emailtemplate::class)->find((int)\mkw\store::getParameter(\mkw\consts::PartnerJelszoSablon));
        $hiba = match (true) {
            !$partner => at('A partner nem található.'),
            (bool)$partner->getVendeg() => at('Vendég partnernek nem adható jelszó.'),
            !$partner->getEmail() => at('A partnernek nincs emailcíme.'),
            \mkw\store::isKatalogus() => at('Ebben a webshopban nincs partner belépés.'),
            !$sablon => at('Nincs beállítva a partner új jelszó levél sablonja (Beállítások).'),
            default => '',
        };
        if ($hiba) {
            echo json_encode(['ok' => false, 'msg' => $hiba]);
            return;
        }

        $jelszo = \mkw\store::generatePassword(10);
        $vars = [
            'partner' => $partner->toLista(),
            'jelszo' => $jelszo,
            'email' => $partner->getEmail(),
            'belepesurl' => \mkw\store::getRouter()->generate('showlogin', true),
        ];
        $subject = \mkw\store::getTemplateFactory()->createMainView('string:' . $sablon->getTargy());
        $body = \mkw\store::getTemplateFactory()->createMainView(
            'string:' . str_replace('&#39;', '\'', html_entity_decode($sablon->getHTMLSzoveg()))
        );
        foreach ($vars as $nev => $ertek) {
            $subject->setVar($nev, $ertek);
            $body->setVar($nev, $ertek);
        }
        if (\mkw\store::getConfigValue('developer')) {
            \mkw\store::writelog($subject->getTemplateResult(), 'partnerjelszoemail.html');
            \mkw\store::writelog($body->getTemplateResult(), 'partnerjelszoemail.html');
        } else {
            $mailer = \mkw\store::getMailer();
            $mailer->withoutBcc();
            $mailer->addTo($partner->getEmail());
            $mailer->setSubject($subject->getTemplateResult());
            $mailer->setMessage($body->getTemplateResult());
            $mailer->send();
            if (!empty($mailer->ErrorInfo)) {
                echo json_encode(['ok' => false, 'msg' => at('A levél nem ment ki, a jelszó nem változott') . ': ' . $mailer->ErrorInfo]);
                return;
            }
        }

        $partner->setJelszo($jelszo);
        $partner->clearPasswordreminder();
        $this->getEm()->persist($partner);
        $this->getEm()->flush();
        echo json_encode(['ok' => true, 'msg' => sprintf(at('Az új jelszót kiküldtük: %s'), htmlspecialchars($partner->getEmail()))]);
    }

    public function sendEmailSablonok()
    {
        $ids = $this->params->getArrayRequestParam('ids');
        $sablon = $this->getRepo(Emailtemplate::class)->find($this->params->getIntRequestParam('sablon'));
        if ($sablon) {
            foreach ($ids as $id) {
                /** @var \Entities\Partner $partner */
                $partner = $this->getRepo()->find($id);
                if ($partner) {
                    $partner->sendEmailSablon($sablon);
                }
            }
        }
    }

}
