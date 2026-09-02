<?php

namespace Controllers;

use Entities\MPTFolyoszamla;
use Entities\Partner;
use Services\PartnerWriterService;

class mptpartnerController extends partnerController
{

    public function saveRegistration()
    {
        $hibas = false;
        $hibak = [];

        $email = $this->params->getStringRequestParam('email');
        $jelszo1 = $this->params->getStringRequestParam('jelszo1');

        $r = $this->checkPartnerData('jelszo');
        $hibas = $hibas || $r['hibas'];
        $hibak = array_merge($hibak, $r['hibak']);
        if (!$hibas) {
            $ps = $this->getRepo()->findByEmail($email);
            if (count($ps) > 0) {
                $t = $ps[0];
            } else {
                $t = new \Entities\Partner();
            }
            (new PartnerWriterService($t, $this->params))
                ->nev()
                ->kapcsolat()
                ->munkahely()
                ->hirlevel()
                ->bank()
                ->szamlacim()
                ->szallcim()
                ->kedvezmenyek()
                ->MPT();

            $this->getEm()->persist($t);
            $this->getEm()->flush();
            $this->login($email, $jelszo1);
            \mkw\session::writeClose();
            echo json_encode([
                'url' => \mkw\store::getRouter()->generate('mptngyszakmaianyagok', true)
            ]);
        } else {
            echo json_encode($hibak);
        }
    }

    /**
     * A tagi felület adatai egy körben: a saját törzsadatok, a választható törzsek
     * és a folyószámla.
     */
    public function getAdataim()
    {
        /** @var Partner $p */
        $p = $this->checkloggedin() ? $this->getRepo()->getLoggedInUser() : null;
        if (!$p) {
            echo json_encode(['hiba' => t('Nincs bejelentkezve.')]);
            return;
        }
        echo json_encode([
            'partner' => $this->getMPTPartnerData($p),
            'tagsagformalist' => (new mpttagsagformaController())->getSelectList(),
            'tagozatlist' => (new mpttagozatController())->getSelectList(),
            'szekciolist' => (new mptszekcioController())->getSelectList(),
            'folyoszamla' => $this->getFolyoszamlaData($p)
        ]);
    }

    public function saveAdataim()
    {
        /** @var Partner $p */
        $p = $this->checkloggedin() ? $this->getRepo()->getLoggedInUser() : null;
        if (!$p) {
            echo json_encode(['hiba' => t('Nincs bejelentkezve.')]);
            return;
        }

        $hibak = $this->checkAdataim($p);
        if ($hibak) {
            echo json_encode(['hibak' => $hibak]);
            return;
        }

        (new PartnerWriterService($p, $this->params))
            ->nev()
            ->kapcsolat()
            ->szamlacim()
            ->MPTPublic();
        $this->getEm()->persist($p);
        $this->getEm()->flush();

        echo json_encode(['partner' => $this->getMPTPartnerData($p)]);
    }

    public function savePassword()
    {
        /** @var Partner $p */
        $p = $this->checkloggedin() ? $this->getRepo()->getLoggedInUser() : null;
        if (!$p) {
            echo json_encode(['hiba' => t('Nincs bejelentkezve.')]);
            return;
        }
        $jelszo1 = $this->params->getStringRequestParam('jelszo1');
        $jelszo2 = $this->params->getStringRequestParam('jelszo2');
        if (mb_strlen($jelszo1) < 8) {
            echo json_encode(['hiba' => t('A jelszó legalább 8 karakter legyen.')]);
            return;
        }
        if ($jelszo1 !== $jelszo2) {
            echo json_encode(['hiba' => t('A két jelszó nem egyezik.')]);
            return;
        }
        $p->setJelszo($jelszo1);
        $this->getEm()->persist($p);
        $this->getEm()->flush();
        echo json_encode(['uzenet' => t('A jelszó módosítva.')]);
    }

    /**
     * @return array a hibaüzenetek mezőnként
     */
    private function checkAdataim(Partner $p)
    {
        $hibak = [];
        $email = trim($this->params->getStringRequestParam('email'));
        if (!$this->params->getStringRequestParam('nev')) {
            $hibak['nev'] = t('A név megadása kötelező.');
        }
        if (!$email) {
            $hibak['email'] = t('Az emailcím megadása kötelező.');
        } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $hibak['email'] = t('Az emailcím formátuma hibás.');
        } else {
            // az emailcím a belépési azonosító is, ezért másé nem lehet
            $masik = $this->getRepo()->findOneBy(['email' => $email]);
            if ($masik && $masik->getId() !== $p->getId()) {
                $hibak['email'] = t('Ezt az emailcímet már használja valaki.');
            }
        }
        return $hibak;
    }

    private function getMPTPartnerData(Partner $p)
    {
        return [
            'nev' => $p->getNev(),
            'vezeteknev' => $p->getVezeteknev(),
            'keresztnev' => $p->getKeresztnev(),
            'email' => $p->getEmail(),
            'telefon' => $p->getTelefon(),
            'adoszam' => $p->getAdoszam(),
            'irszam' => $p->getIrszam(),
            'varos' => $p->getVaros(),
            'utca' => $p->getUtca(),
            'hazszam' => $p->getHazszam(),
            'mpt_megszolitas' => $p->getMptMegszolitas(),
            'mpt_privatemail' => $p->getMptPrivatemail(),
            'mpt_szamlazasinev' => $p->getMptSzamlazasinev(),
            'mpt_munkahelynev' => $p->getMptMunkahelynev(),
            'mpt_munkahelyirszam' => $p->getMptMunkahelyirszam(),
            'mpt_munkahelyvaros' => $p->getMptMunkahelyvaros(),
            'mpt_munkahelyutca' => $p->getMptMunkahelyutca(),
            'mpt_munkahelyhazszam' => $p->getMptMunkahelyhazszam(),
            'mpt_lakcimirszam' => $p->getMptLakcimirszam(),
            'mpt_lakcimvaros' => $p->getMptLakcimvaros(),
            'mpt_lakcimutca' => $p->getMptLakcimutca(),
            'mpt_lakcimhazszam' => $p->getMptLakcimhazszam(),
            'mpt_vegzettseg' => $p->getMptVegzettseg(),
            'mpt_fokozat' => $p->getMptFokozat(),
            'mpt_szuleteseve' => $p->getMptSzuleteseve(),
            'mpt_diplomaeve' => $p->getMptDiplomaeve(),
            'mpt_diplomahely' => $p->getMptDiplomahely(),
            'mpt_egyebdiploma' => $p->getMptEgyebdiploma(),
            'mpt_tagsagforma' => $p->getMptTagsagformaId(),
            'mpt_tagozat' => $p->getMptTagozatId(),
            'mpt_szekcio1' => $p->getMptSzekcio1Id(),
            'mpt_szekcio2' => $p->getMptSzekcio2Id(),
            'mpt_szekcio3' => $p->getMptSzekcio3Id(),
            // a tagságot az iroda tartja karban, a tag csak látja
            'mpt_tagkartya' => $p->getMptTagkartya(),
            'mpt_tagsagdate' => $p->getMptTagsagdateStr()
        ];
    }

    private function getFolyoszamlaData(Partner $p)
    {
        $tetelek = [];
        $egyenleg = 0;
        /** @var MPTFolyoszamla $item */
        foreach ($p->getMptfolyoszamlak() as $item) {
            $osszeg = (float)$item->getOsszeg() * $item->getIrany();
            $egyenleg += $osszeg;
            $tetelek[] = [
                'vonatkozoev' => $item->getVonatkozoev(),
                'tipusnev' => $item->getTipusNev(),
                'osszeg' => $osszeg,
                'bizonylatszam' => $item->getBizonylatszam(),
                'datum' => $item->getDatumStr()
            ];
        }
        return ['egyenleg' => $egyenleg, 'tetelek' => $tetelek];
    }

    public function doLogin()
    {
        if (\mkw\store::mustLogin() && \mkw\store::getMainSession()->redirafterlogin) {
            $route = \mkw\store::getMainSession()->redirafterlogin;
            unset(\mkw\store::getMainSession()->redirafterlogin);
        } else {
            $route = \mkw\store::getRouter()->generate('home', true);
        }
        if (!$this->checkloggedin()) {
            if ($this->login($this->params->getStringRequestParam('email'), $this->params->getStringRequestParam('jelszo'))) {
//				\mkw\session::writeClose();
                /** @var \Entities\Partner $partnerobj */
                $partnerobj = \mkw\store::getEm()->getRepository(Partner::class)->find(\mkw\store::getMainSession()->pk);
                if ($partnerobj) {
                    $mc = new mainController();
                    $mc->setOrszagFunc($partnerobj->getOrszagId(), $partnerobj->getAdoszamFilled());
                }
            } else {
                \mkw\store::clearLoggedInUser();
                $mc = new mainController();
                $mc->clearOrszag();
                \mkw\store::getMainSession()->loginerror = true;
                $route = \mkw\store::getRouter()->generate('showlogin', true);
            }
            echo json_encode([
                'url' => $route
            ]);
        } else {
            echo json_encode([
                'url' => $route
            ]);
        }
    }

    public function checkPartnerUnknown()
    {
        $email = $this->params->getStringRequestParam('email');
        $filter = new \mkwhelpers\FilterDescriptor();
        $filter
            ->addFilter('email', '=', $email);
        $cnt = $this->getRepo()->getCount($filter);
        echo json_encode([
            'unknown' => ($cnt === 0)
        ]);
    }
}
