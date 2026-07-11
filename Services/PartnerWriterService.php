<?php

namespace Services;

use Entities\Fizmod;
use Entities\MPTNGYEgyetem;
use Entities\MPTNGYKar;
use Entities\MPTNGYSzerepkor;
use Entities\MPTSzekcio;
use Entities\MPTTagozat;
use Entities\MPTTagsagforma;
use Entities\Orszag;
use Entities\Partner;
use Entities\PartnerTermekcsoportKedvezmeny;
use Entities\PartnerTermekKedvezmeny;
use Entities\Szallitasimod;
use Entities\Termek;
use Entities\Termekcsoport;
use mkwhelpers\ParameterHandler;

class PartnerWriterService
{
    private Partner $partner;
    private ParameterHandler $params;
    private $em;

    public function __construct(Partner $partner, ParameterHandler $params)
    {
        $this->partner = $partner;
        $this->params = $params;
        $this->em = \mkw\store::getEm();
    }

    public function partner(): Partner
    {
        return $this->partner;
    }

    public function nev(): self
    {
        $this->partner->setVezeteknev($this->params->getStringRequestParam('vezeteknev'));
        $this->partner->setKeresztnev($this->params->getStringRequestParam('keresztnev'));
        if (\mkw\store::isMindentkapni()) {
            $this->partner->setNev($this->params->getStringRequestParam('vezeteknev') . ' ' . $this->params->getStringRequestParam('keresztnev'));
        }
        return $this;
    }

    public function kapcsolat(): self
    {
        $this->partner->setEmail($this->params->getStringRequestParam('email'));
        if (\mkw\store::isMindentkapni()) {
            $telkorzet = $this->params->getStringRequestParam('telkorzet');
            $telszam = preg_replace('/[^0-9+]/', '', $this->params->getStringRequestParam('telszam'));
            $this->partner->setTelkorzet($telkorzet);
            $this->partner->setTelszam($telszam);
            $this->partner->setTelefon('+36' . $telkorzet . $telszam);
        } else {
            $this->partner->setTelefon($this->params->getStringRequestParam('telefon'));
        }
        return $this;
    }

    public function munkahely(): self
    {
        $this->partner->setMunkahelyneve($this->params->getStringRequestParam('munkahelyneve'));
        $this->partner->setFoglalkozas($this->params->getStringRequestParam('foglalkozas'));
        return $this;
    }

    public function hirlevel(): self
    {
        $this->partner->setAkcioshirlevelkell($this->params->getBoolRequestParam('akcioshirlevelkell'));
        $this->partner->setUjdonsaghirlevelkell($this->params->getBoolRequestParam('ujdonsaghirlevelkell'));
        return $this;
    }

    public function szamlacim(): self
    {
        $this->partner->setAdoszam(substr($this->params->getStringRequestParam('adoszam'), 0, 13));
        $this->partner->setIrszam(substr($this->params->getStringRequestParam('irszam'), 0, 10));
        $this->partner->setVaros($this->params->getStringRequestParam('varos'));
        $this->partner->setUtca($this->params->getStringRequestParam('utca'));
        $this->partner->setHazszam($this->params->getStringRequestParam('hazszam'));
        $orszag = \mkw\store::getEm()->getRepository(Orszag::class)->find($this->params->getIntRequestParam('orszag', 0));
        if ($orszag) {
            $this->partner->setOrszag($orszag);
        } else {
            $this->partner->setOrszag(null);
        }
        return $this;
    }

    public function szallcim(): self
    {
        $this->partner->setSzallnev($this->params->getStringRequestParam('szallnev'));
        $this->partner->setSzallirszam(substr($this->params->getStringRequestParam('szallirszam'), 0, 10));
        $this->partner->setSzallvaros($this->params->getStringRequestParam('szallvaros'));
        $this->partner->setSzallutca($this->params->getStringRequestParam('szallutca'));
        $this->partner->setSzallhazszam($this->params->getStringRequestParam('szallhazszam'));
        $orszag = \mkw\store::getEm()->getRepository(Orszag::class)->find($this->params->getIntRequestParam('szallorszag', 0));
        if ($orszag) {
            $this->partner->setSzallorszag($orszag);
        } else {
            $this->partner->setSzallorszag(null);
        }
        return $this;
    }

    public function bank(): self
    {
        $this->partner->setBanknev($this->params->getStringRequestParam('banknev'));
        $this->partner->setBankcim($this->params->getStringRequestParam('bankcim'));
        $this->partner->setIban($this->params->getStringRequestParam('iban'));
        $this->partner->setSwift($this->params->getStringRequestParam('swift'));
        return $this;
    }

    public function jelszo(): self
    {
        $this->partner->setJelszo($this->params->getStringRequestParam('jelszo1'));
        return $this;
    }

    public function kedvezmenyek(): self
    {
        $kdids = $this->params->getArrayRequestParam('kedvezmenyid');
        foreach ($kdids as $kdid) {
            $oper = $this->params->getStringRequestParam('kedvezmenyoper_' . $kdid);
            $termekcsoport = $this->em->getRepository(Termekcsoport::class)->find(
                $this->params->getIntRequestParam('kedvezmenytermekcsoport_' . $kdid)
            );
            if ($termekcsoport) {
                if ($oper === 'add') {
                    $kedv = new \Entities\PartnerTermekcsoportKedvezmeny();
                    $kedv->setPartner($this->partner);
                    $kedv->setTermekcsoport($termekcsoport);
                    $kedv->setKedvezmeny($this->params->getNumRequestParam('kedvezmeny_' . $kdid));
                    $this->em->persist($kedv);
                } elseif ($oper === 'edit') {
                    $kedv = $this->em->getRepository(PartnerTermekcsoportKedvezmeny::class)->find($kdid);
                    if ($kedv) {
                        $kedv->setPartner($this->partner);
                        $kedv->setTermekcsoport($termekcsoport);
                        $kedv->setKedvezmeny($this->params->getNumRequestParam('kedvezmeny_' . $kdid));
                        $this->em->persist($kedv);
                    }
                }
            }
        }
        $kdids = $this->params->getArrayRequestParam('termekkedvezmenyid');
        foreach ($kdids as $kdid) {
            $oper = $this->params->getStringRequestParam('termekkedvezmenyoper_' . $kdid);
            $termek = $this->em->getRepository(Termek::class)->find($this->params->getIntRequestParam('termekkedvezmenytermek_' . $kdid));
            if ($termek) {
                if ($oper === 'add') {
                    $kedv = new \Entities\PartnerTermekKedvezmeny();
                    $kedv->setPartner($this->partner);
                    $kedv->setTermek($termek);
                    $kedv->setKedvezmeny($this->params->getNumRequestParam('termekkedvezmeny_' . $kdid));
                    $this->em->persist($kedv);
                } elseif ($oper === 'edit') {
                    $kedv = $this->em->getRepository(PartnerTermekKedvezmeny::class)->find($kdid);
                    if ($kedv) {
                        $kedv->setPartner($this->partner);
                        $kedv->setTermek($termek);
                        $kedv->setKedvezmeny($this->params->getNumRequestParam('termekkedvezmeny_' . $kdid));
                        $this->em->persist($kedv);
                    }
                }
            }
        }
        return $this;
    }

    public function regisztracio(): self
    {
        $this->partner->setVezeteknev($this->params->getStringRequestParam('vezeteknev'));
        $this->partner->setKeresztnev($this->params->getStringRequestParam('keresztnev'));
        if (\mkw\store::isMindentkapni()) {
            $this->partner->setNev($this->params->getStringRequestParam('vezeteknev') . ' ' . $this->params->getStringRequestParam('keresztnev'));
        }
        $email = $this->params->getStringRequestParam('kapcsemail');
        if ($email) {
            $this->partner->setEmail($email);
        } else {
            $this->partner->setEmail($this->params->getStringRequestParam('email'));
        }
        $this->partner->setJelszo($this->params->getStringRequestParam('jelszo1'));
        $this->partner->setVendeg(false);
        $this->partner->setSessionid(\mkw\session::getId());
        $this->partner->setIp($_SERVER['REMOTE_ADDR']);
        $this->partner->setReferrer(\mkw\store::getMainSession()->referrer);
        $this->partner->setAkcioshirlevelkell($this->params->getBoolRequestParam('akcioshirlevelkell'));
        $this->partner->setUjdonsaghirlevelkell($this->params->getBoolRequestParam('ujdonsaghirlevelkell'));
        $this->partner->setBizonylatnyelv(\mkw\store::getWebshopLongLocale());
        $fizmod = \mkw\store::getEm()->getRepository(Fizmod::class)->find($this->params->getIntRequestParam('fizetesimod', 0));
        if ($fizmod) {
            $this->partner->setFizmod($fizmod);
        }
        $szallmod = \mkw\store::getEm()->getRepository(Szallitasimod::class)->find($this->params->getIntRequestParam('szallitasimod', 0));
        if ($szallmod) {
            $this->partner->setSzallitasimod($szallmod);
        }
        return $this;
    }

    public function MPT(): self
    {
        $this->partner->setMptUsername($this->params->getStringRequestParam('mpt_username'));
        $this->partner->setMptPassword($this->params->getStringRequestParam('mpt_password'));
        $this->partner->setMptUserid($this->params->getIntRequestParam('mpt_userid'));
        $this->partner->setMptMunkahelynev($this->params->getStringRequestParam('mpt_munkahelynev'));
        $this->partner->setMptMunkahelyirszam($this->params->getStringRequestParam('mpt_munkahelyirszam'));
        $this->partner->setMptMunkahelyvaros($this->params->getStringRequestParam('mpt_munkahelyvaros'));
        $this->partner->setMptMunkahelyutca($this->params->getStringRequestParam('mpt_munkahelyutca'));
        $this->partner->setMptMunkahelyhazszam($this->params->getStringRequestParam('mpt_munkahelyhazszam'));
        $this->partner->setMptLakcimirszam($this->params->getStringRequestParam('mpt_lakcimirszam'));
        $this->partner->setMptLakcimvaros($this->params->getStringRequestParam('mpt_lakcimvaros'));
        $this->partner->setMptLakcimutca($this->params->getStringRequestParam('mpt_lakcimutca'));
        $this->partner->setMptLakcimhazszam($this->params->getStringRequestParam('mpt_lakcimhazszam'));
        $this->partner->setMptTagkartya($this->params->getStringRequestParam('mpt_tagkartya'));
        $this->partner->setMptMegszolitas($this->params->getStringRequestParam('mpt_megszolitas'));
        $this->partner->setMptFokozat($this->params->getStringRequestParam('mpt_fokozat'));
        $this->partner->setMptVegzettseg($this->params->getStringRequestParam('mpt_vegzettseg'));
        $this->partner->setMptSzuleteseve($this->params->getIntRequestParam('mpt_szuleteseve'));
        $this->partner->setMptDiplomaeve($this->params->getIntRequestParam('mpt_diplomaeve'));
        $this->partner->setMptDiplomahely($this->params->getStringRequestParam('mpt_diplomahely'));
        $this->partner->setMptEgyebdiploma($this->params->getStringRequestParam('mpt_egyebdiploma'));
        $this->partner->setMptPrivatemail($this->params->getStringRequestParam('mpt_privatemail'));
        $this->partner->setMptTagsagdate($this->params->getStringRequestParam('mpt_tagsagdate'));
        $this->partner->setMptSzamlazasinev($this->params->getStringRequestParam('mpt_szamlazasinev'));
        $mptszekcio = \mkw\store::getEm()->getRepository(MPTSzekcio::class)->find($this->params->getIntRequestParam('mpt_szekcio1', 0));
        if ($mptszekcio) {
            $this->partner->setMptSzekcio1($mptszekcio);
        } else {
            $this->partner->setMptSzekcio1(null);
        }
        $mptszekcio = \mkw\store::getEm()->getRepository(MPTSzekcio::class)->find($this->params->getIntRequestParam('mpt_szekcio2', 0));
        if ($mptszekcio) {
            $this->partner->setMptSzekcio2($mptszekcio);
        } else {
            $this->partner->setMptSzekcio2(null);
        }
        $mptszekcio = \mkw\store::getEm()->getRepository(MPTSzekcio::class)->find($this->params->getIntRequestParam('mpt_szekcio3', 0));
        if ($mptszekcio) {
            $this->partner->setMptSzekcio3($mptszekcio);
        } else {
            $this->partner->setMptSzekcio3(null);
        }
        $mpttagozat = \mkw\store::getEm()->getRepository(MPTTagozat::class)->find($this->params->getIntRequestParam('mpt_tagozat', 0));
        if ($mpttagozat) {
            $this->partner->setMptTagozat($mpttagozat);
        } else {
            $this->partner->setMptTagozat(null);
        }
        $mpttagsagforma = \mkw\store::getEm()->getRepository(MPTTagsagforma::class)->find($this->params->getIntRequestParam('mpt_tagsagforma', 0));
        if ($mpttagsagforma) {
            $this->partner->setMptTagsagforma($mpttagsagforma);
        } else {
            $this->partner->setMptTagsagforma(null);
        }

        return $this;
    }

    public function MPTNGYPublic(): self
    {
        $this->partner->setNev($this->params->getStringRequestParam('nev'));
        $this->partner->setNevelotag($this->params->getStringRequestParam('nevelotag'));
        $this->partner->setSzlanev($this->params->getStringRequestParam('szlanev'));
        $this->partner->setIrszam($this->params->getStringRequestParam('irszam'));
        $this->partner->setVaros($this->params->getStringRequestParam('varos'));
        $this->partner->setUtca($this->params->getStringRequestParam('utca'));
        $this->partner->setMptMunkahelynev($this->params->getStringRequestParam('mpt_munkahelynev'));
        $this->partner->setVatstatus($this->params->getIntRequestParam('vatstatus'));
        $this->partner->setAdoszam(substr($this->params->getStringRequestParam('adoszam'), 0, 13));
        $this->partner->setCsoportosadoszam($this->params->getStringRequestParam('csoportosadoszam'));

        $this->partner->setMptngycsoportosfizetes($this->params->getStringRequestParam('mptngycsoportosfizetes'));
        $this->partner->setMptngykapcsolatnev($this->params->getStringRequestParam('mptngykapcsolatnev'));
        $this->partner->setMptngybankszamlaszam($this->params->getStringRequestParam('mptngybankszamlaszam'));
        $this->partner->setMptngyvipvacsora($this->params->getBoolRequestParam('mptngyvipvacsora'));
        $this->partner->setMptngybankett($this->params->getBoolRequestParam('mptngybankett'));
        $this->partner->setMptngynapreszvetel1($this->params->getBoolRequestParam('mptngynapreszvetel1'));
        $this->partner->setMptngynapreszvetel2($this->params->getBoolRequestParam('mptngynapreszvetel2'));
        $this->partner->setMptngynapreszvetel3($this->params->getBoolRequestParam('mptngynapreszvetel3'));
        $this->partner->setMptngynemveszreszt($this->params->getBoolRequestParam('mptngynemveszreszt'));
        $this->partner->setMptngydiak($this->params->getBoolRequestParam('mptngydiak'));
        $this->partner->setMptngynyugdijas($this->params->getBoolRequestParam('mptngynyugdijas'));
        $this->partner->setMptngyphd($this->params->getBoolRequestParam('mptngyphd'));
        $this->partner->setMptngympttag($this->params->getBoolRequestParam('mptngympttag'));
        $egyetem = \mkw\store::getEm()->getRepository(MPTNGYEgyetem::class)->find($this->params->getIntRequestParam('mptngyegyetem'));
        if ($egyetem) {
            $this->partner->setMptngyegyetem($egyetem);
        } else {
            $this->partner->removeMptngyegyetem();
        }

        $kar = \mkw\store::getEm()->getRepository(MPTNGYKar::class)->find($this->params->getIntRequestParam('mptngykar'));
        if ($kar) {
            $this->partner->setMptngykar($kar);
        } else {
            $this->partner->removeMptngykar();
        }

        $this->partner->setMptngyegyetemegyeb($this->params->getStringRequestParam('mptngyegyetemegyeb'));
        $mptngyszerepkor = \mkw\store::getEm()->getRepository(MPTNGYSzerepkor::class)->find($this->params->getIntRequestParam('mptngyszerepkor', 0));
        if ($mptngyszerepkor) {
            $this->partner->setMptngyszerepkor($mptngyszerepkor);
        } else {
            $this->partner->setMptngyszerepkor(null);
        }
        if ($this->params->getStringRequestParam('jelszo1')) {
            $this->partner->setJelszo($this->params->getStringRequestParam('jelszo1'));
        }

        return $this;
    }

}
