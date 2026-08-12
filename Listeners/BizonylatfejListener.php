<?php

namespace Listeners;

use Doctrine\ORM\Event\LifecycleEventArgs;
use Doctrine\ORM\Event\OnFlushEventArgs;
use Entities\Afa;
use Entities\Bankbizonylatfej;
use Entities\Bankbizonylattetel;
use Entities\Bizonylatfej;
use Entities\Bizonylatstatusznaplo;
use Entities\Bizonylatvaltozasnaplo;
use Entities\Bizonylattetel;
use Entities\Bizonylattipus;
use Entities\Feketelista;
use Entities\Folyoszamla;
use Entities\Jogcim;
use Entities\Kupon;
use Entities\Partner;
use Entities\Penztar;
use Entities\Penztarbizonylatfej;
use Entities\Penztarbizonylattetel;
use Entities\Szallitasimod;
use Entities\Termek;

class BizonylatfejListener
{

    /** Az automatikusan képzett pénztárbizonylat megjegyzése – ettől ismerhető fel a listákon. */
    private const AUTOPENZTARMEGJEGYZES = 'Automatikus pénztárbizonylat';

    /** A bizonylat pénzügyi viselkedését eldöntő mezők – ezek változását naplózzuk. */
    private const NAPLOZOTTMEZOK = [
        'fizmod' => 'Fizetési mód',
        'penztmozgat' => 'Kintlévőséget/tartozást képez',
        'penztar' => 'Pénztár',
    ];

    /**
     * Ezek átállítása teszi kérdésessé a bizonylathoz tartozó, már létrejött pénzmozgásokat –
     * ilyenkor kérdez rá a mentés (lásd bizonylathelper.js), és a válasz szerint rontunk.
     * A pénztár váltását nem soroljuk ide: azt a meglévő összevetés amúgy is kezeli.
     */
    private const PENZMOZGASTERINTOMEZOK = ['fizmod', 'penztmozgat'];

    private $em;
    private $uow;
    private $bizonylatfejmd;
    private $penztarbizonylatfejmd;
    private $penztarbizonylattetelmd;
    private $bankbizonylatfejmd;
    private $bankbizonylattetelmd;
    private $bizonylattetelmd;
    private $folyoszamlamd;
    private $kuponmd;
    private $bizonylatstatusznaplomd;
    private $bizonylatvaltozasnaplomd;

    /**
     * @param \Entities\Bizonylatfej $bizonylat
     * @param $szam
     */
    private function createFSzla($bizonylat, $szam)
    {
        $fszla = new \Entities\Folyoszamla();
        $fszla->setDatum($bizonylat->getKelt());
        $fszla->setFizmod($bizonylat->getFizmod());
        $fszla->setPartner($bizonylat->getPartner());
        $fszla->setBizonylattipus($bizonylat->getBizonylattipus());
        $fszla->setRontott($bizonylat->getRontott());
        $fszla->setStorno($bizonylat->getStorno());
        $fszla->setStornozott($bizonylat->getStornozott());
        $fszla->setHivatkozottbizonylat($bizonylat->getId());
        $fszla->setUzletkoto($bizonylat->getUzletkoto());
        $fszla->setValutanem($bizonylat->getValutanem());
        $fszla->setIrany($bizonylat->getIrany() * -1);
        switch ($szam) {
            case 0:
                $fszla->setBrutto($bizonylat->getFizetendo());
                $fszla->setHivatkozottdatum($bizonylat->getEsedekessegStr());
                break;
            case 1:
                $fszla->setBrutto($bizonylat->getFizetendo1());
                $fszla->setHivatkozottdatum($bizonylat->getEsedekesseg1Str());
                break;
            case 2:
                $fszla->setBrutto($bizonylat->getFizetendo2());
                $fszla->setHivatkozottdatum($bizonylat->getEsedekesseg2Str());
                break;
            case 3:
                $fszla->setBrutto($bizonylat->getFizetendo3());
                $fszla->setHivatkozottdatum($bizonylat->getEsedekesseg3Str());
                break;
            case 4:
                $fszla->setBrutto($bizonylat->getFizetendo4());
                $fszla->setHivatkozottdatum($bizonylat->getEsedekesseg4Str());
                break;
            case 5:
                $fszla->setBrutto($bizonylat->getFizetendo5());
                $fszla->setHivatkozottdatum($bizonylat->getEsedekesseg5Str());
                break;
        }
        $fszla->setBizonylatfej($bizonylat);
        $bizonylat->addFolyoszamla($fszla);
        $this->em->persist($fszla);
        $this->uow->computeChangeSet($this->folyoszamlamd, $fszla);
    }

    /**
     * @param \Entities\Bizonylatfej $bizonylat
     */
    private function createFolyoszamla($bizonylat)
    {
        // a bizonylat folyószámla sorai minden mentéskor újraképződnek
        foreach ($bizonylat->getFolyoszamlak() as $fsz) {
            $this->em->remove($fsz);
        }
        $bizonylat->clearFolyoszamlak();

        if (!$bizonylat->getPenztmozgat()) {
            return;
        }
        // Készpénzes fizetési módnál csak akkor képzünk folyószámlát, ha a kpfolyoszamla
        // beállítás be van kapcsolva. Kivéve, ha automatikus pénztárbizonylatot képzünk:
        // annak a PenztarbizonylatfejListener ellentétes előjelű folyószámla sort csinál,
        // ami pár nélkül hamis túlfizetésnek látszana a partner egyenlegében.
        $fizmod = $bizonylat->getFizmod();
        if ($fizmod && $fizmod->getTipus() === 'P'
            && !\mkw\store::isKPFolyoszamla() && !$bizonylat->getBizonylattipus()?->getAutopenztarbizonylat()) {
            return;
        }

        if (\mkw\store::isOsztottFizmod()) {
            $volt = false;
            if ($bizonylat->getFizetendo1()) {
                $this->createFSzla($bizonylat, 1);
                $volt = true;
            }
            if ($bizonylat->getFizetendo2()) {
                $this->createFSzla($bizonylat, 2);
                $volt = true;
            }
            if ($bizonylat->getFizetendo3()) {
                $this->createFSzla($bizonylat, 3);
                $volt = true;
            }
            if ($bizonylat->getFizetendo4()) {
                $this->createFSzla($bizonylat, 4);
                $volt = true;
            }
            if ($bizonylat->getFizetendo5()) {
                $this->createFSzla($bizonylat, 5);
                $volt = true;
            }
            if (!$volt) {
                $this->createFSzla($bizonylat, 0);
            }
        } else {
            $this->createFSzla($bizonylat, 0);
        }
    }

    /**
     * @param $ktg
     * @param \Entities\Bizonylatfej $bizfej
     * @param mixed $termekid
     *
     * @return void
     */
    private function createBiztetel($ktg, \Entities\Bizonylatfej $bizfej, mixed $termekid): void
    {
        $ktg = $ktg * 1;

        if ($ktg) {
            $afaoverride = Partner::calcAFAOverride(
                $bizfej->getPartnerszallorszag(),
                $bizfej->getPartnerorszag(),
                $bizfej->getPartnerSzamlatipus(),
                $bizfej->getPartnereuadoszam()
            );
            $termek = $this->em->getRepository(Termek::class)->find($termekid);
            $k = null;
            foreach ($bizfej->getBizonylattetelek() as $btetel) {
                if ($btetel->getTermekId() == $termekid) {
                    $k = $btetel;
                }
            }
            if ($k) {
                $k->setMennyiseg(1);
                if ($afaoverride) {
                    $k->setAfa($afaoverride);
                } else {
                    $k->setAfa($termek->getAfa());
                }
                $k->setBruttoegysar($ktg);
                $k->setBruttoegysarhuf($ktg * $k->getArfolyam());
                $k->calc();
                $this->em->persist($k);
                $this->uow->recomputeSingleEntityChangeSet($this->bizonylattetelmd, $k);
            } else {
                $k = new \Entities\Bizonylattetel();
                $bizfej->addBizonylattetel($k);
                $k->setPersistentData();
                $k->setArvaltoztat(0);
                if ($termek) {
                    $k->setTermek($termek);
                }
                $k->setMozgat();
                $k->setFoglal();
                $k->setMennyiseg(1);
                if ($afaoverride) {
                    $k->setAfa($afaoverride);
                } else {
                    $k->setAfa($termek->getAfa());
                }
                $k->setBruttoegysar($ktg);
                $k->setBruttoegysarhuf($ktg * $k->getArfolyam());
                $k->calc();
                $this->em->persist($k);
                $this->uow->computeChangeSet($this->bizonylattetelmd, $k);
            }
        } else {
            $this->removeBiztetel($bizfej, $termekid);
        }
    }

    /**
     * A beszúrt / módosított / törölt tételek bizonylatfejei, amelyek maguktól nem
     * kerülnének a flush-listára. Törléskor a tételről már le van csatolva a fej,
     * ilyenkor a UnitOfWork eredeti adataiból vesszük.
     *
     * @param array $marbenne a már feldolgozásra kerülő entitások
     *
     * @return \Entities\Bizonylatfej[]
     */
    private function tetelekBizonylatfejei(array $marbenne)
    {
        $ismert = [];
        foreach ($marbenne as $entity) {
            if ($entity instanceof \Entities\Bizonylatfej) {
                $ismert[spl_object_id($entity)] = true;
            }
        }

        $result = [];
        $tetelek = array_merge(
            $this->uow->getScheduledEntityInsertions(),
            $this->uow->getScheduledEntityUpdates(),
            $this->uow->getScheduledEntityDeletions()
        );
        foreach ($tetelek as $tetel) {
            if (!($tetel instanceof \Entities\Bizonylattetel)) {
                continue;
            }
            $fej = $tetel->getBizonylatfej();
            if (!$fej) {
                $eredeti = $this->uow->getOriginalEntityData($tetel);
                $fej = $eredeti['bizonylatfej'] ?? null;
            }
            if (!($fej instanceof \Entities\Bizonylatfej) || $this->uow->isScheduledForDelete($fej)) {
                continue;
            }
            $oid = spl_object_id($fej);
            if (!isset($ismert[$oid])) {
                $ismert[$oid] = true;
                $result[] = $fej;
            }
        }
        return $result;
    }

    private function removeBiztetel($bizfej, $termekid)
    {
        if ($termekid) {
            foreach ($bizfej->getBizonylattetelek() as $tetel) {
                if ($tetel->getTermekId() == $termekid) {
                    $tetel->setNettoegysar(0);
                    $tetel->setNettoegysarhuf(0);
                    $tetel->calc();
                    $this->em->persist($tetel);
                    $this->uow->recomputeSingleEntityChangeSet($this->bizonylattetelmd, $tetel);
                    /*
                    $bizfej->removeBizonylattetel($tetel);
                    $this->em->remove($tetel);
                    $this->uow->scheduleForDelete($tetel);
                    */
                    //$this->uow->computeChangeSet($this->bizonylattetelmd, $tetel); // must use this for uow->remove()
                }
            }
        }
    }

    /**
     * @param \Entities\Bizonylatfej $bizfej
     * @param \Entities\Kupon $kupon
     */
    private function createSzallitasiKtg($bizfej, $kupon)
    {
        if (!$bizfej->isKellszallitasikoltsegetszamolni()) {
            return;
        }
        $szamol = true;

        $bizsum = $bizfej->calcBruttoWithoutKtgs();
        if ($kupon && $kupon->isErvenyes() && $kupon->isMinimumosszegMegvan($bizsum->brutto) && $kupon->isIngyenSzallitas()) {
            $szamol = false;
        }

        $bruttoegysar = $bizfej->getSzallitasikoltsegbrutto();

        $szallmod = $bizfej->getSzallitasimod();
        if ($szallmod) {
            $szamol = $szallmod->getVanszallitasiktg();
        }

        $termekid = \mkw\store::getParameter(\mkw\consts::SzallitasiKtgTermek);
        if ($termekid) {
            // $bruttoegysar csak vatera megrendeles importkor van megadva, ilyenkor mindegy, hogy milyen szall.mod van
            if ($szamol || $bruttoegysar) {
                if ($bizsum->cnt != 0) {
                    if (!$bruttoegysar) {
                        $ktg = $this->em->getRepository(Szallitasimod::class)->getSzallitasiKoltseg(
                            $szallmod,
                            $bizfej->getPartnerSzallorszagOrOrszag(),
                            $bizfej->getValutanem(),
                            $bizsum->brutto
                        );
                    } else {
                        $ktg = $bruttoegysar;
                    }
                    $this->createBiztetel($ktg, $bizfej, $termekid);
                } else {
                    $this->removeBiztetel($bizfej, $termekid);
                }
            } else {
                $this->removeBiztetel($bizfej, $termekid);
            }
        }
    }

    /**
     * @param \Entities\Bizonylatfej $bizfej
     * @param \Entities\Kupon $kupon
     */
    private function createUtanvetKtg($bizfej, $kupon)
    {
        if (!$bizfej->isKellszallitasikoltsegetszamolni()) {
            return;
        }
        $szamol = true;

        $bizsum = $bizfej->calcBruttoWithoutKtgs();
        if ($kupon && $kupon->isErvenyes() && $kupon->isMinimumosszegMegvan($bizsum->brutto) && $kupon->isIngyenSzallitas()) {
            $szamol = false;
        }

        $szallmod = $bizfej->getSzallitasimod();
        if ($szallmod) {
            $szamol = $szallmod->getVanszallitasiktg();
        }

        $termekid = \mkw\store::getParameter(\mkw\consts::UtanvetKtgTermek);
        if ($szamol) {
            if ($bizsum->cnt != 0) {
                $ktg = $this->em->getRepository(Szallitasimod::class)->getUtanvetKoltseg(
                    $szallmod,
                    $bizfej->getFizmod(),
                    $bizsum->brutto
                );
                $this->createBiztetel($ktg, $bizfej, $termekid);
            } else {
                $this->removeBiztetel($bizfej, $termekid);
            }
        } else {
            $this->removeBiztetel($bizfej, $termekid);
        }
    }

    /**
     * @param \Entities\Bizonylatfej $bizfej
     */
    private function createKezelesiKoltseg($bizfej)
    {
        $szallmod = $bizfej->getSzallitasimod();
        $kezktg = $szallmod?->getTermek();
        if ($kezktg) {
            $afaoverride = Partner::calcAFAOverride(
                $bizfej->getPartnerszallorszag(),
                $bizfej->getPartnerorszag(),
                $bizfej->getPartnerSzamlatipus(),
                $bizfej->getPartnereuadoszam()
            );
            $k = null;
            foreach ($bizfej->getBizonylattetelek() as $btetel) {
                if ($btetel->getTermekId() == $kezktg->getId()) {
                    $k = $btetel;
                }
            }
            if ($k) {
                $k->setMennyiseg(1);
                if ($afaoverride) {
                    $k->setAfa($afaoverride);
                }
                $k->setBruttoegysar($kezktg->getBruttoAr());
                $k->setBruttoegysarhuf($kezktg->getBruttoAr() * $k->getArfolyam());
                $k->calc();
                $this->em->persist($k);
                $this->uow->recomputeSingleEntityChangeSet($this->bizonylattetelmd, $k);
            } else {
                $k = new \Entities\Bizonylattetel();
                $bizfej->addBizonylattetel($k);
                $k->setPersistentData();
                $k->setArvaltoztat(0);
                if ($kezktg) {
                    $k->setTermek($kezktg);
                }
                $k->setMozgat();
                $k->setFoglal();
                $k->setMennyiseg(1);
                if ($afaoverride) {
                    $k->setAfa($afaoverride);
                } else {
                    $k->setAfa($kezktg->getAfa());
                }
                $k->setBruttoegysar($kezktg->getBruttoAr());
                $k->setBruttoegysarhuf($kezktg->getBruttoAr() * $k->getArfolyam());
                $k->calc();
                $this->em->persist($k);
                $this->uow->computeChangeSet($this->bizonylattetelmd, $k);
            }
        } else {
            $ktgs = $this->em->getRepository(Szallitasimod::class)->getKezelesiKoltsegTermekek();
            foreach ($ktgs as $ktg) {
                $this->removeBiztetel($bizfej, $ktg);
            }
        }
    }

    private function rontPenztarBizonylat($bizfej)
    {
        /** @var \Entities\PenztarbizonylatfejRepository $prep */
        $pfrep = $this->em->getRepository(Penztarbizonylatfej::class);
        $filter = new \mkwhelpers\FilterDescriptor();
        $filter->addFilter('pt.hivatkozottbizonylat', '=', $bizfej->getId());
        $pbizek = $pfrep->getAllByHivatkozottBizonylat($filter);
        /** @var \Entities\Penztarbizonylatfej $pbiz */
        foreach ($pbizek as $pbiz) {
            $this->rontPenztarBizonylatfej($pbiz);
        }
    }

    /**
     * A Penztarbizonylatfej::setRontott() a tételeket is átállítja, de a bennük lévő
     * változást a UnitOfWork-kel is fel kell vetetni – e nélkül a penztarbizonylattetel.rontott
     * soha nem kerül ki az adatbázisba.
     *
     * @param \Entities\Penztarbizonylatfej $pbiz
     */
    private function rontPenztarBizonylatfej($pbiz)
    {
        $pbiz->setRontott(true);
        $this->em->persist($pbiz);
        $this->uow->recomputeSingleEntityChangeSet($this->penztarbizonylatfejmd, $pbiz);
        /** @var \Entities\Penztarbizonylattetel $pt */
        foreach ($pbiz->getBizonylattetelek() as $pt) {
            // a most beszúrásra ütemezetteknek még nincs eredeti adatuk, azokra a
            // recompute kivételt dobna
            if ($this->uow->isScheduledForInsert($pt)) {
                continue;
            }
            $this->uow->recomputeSingleEntityChangeSet($this->penztarbizonylattetelmd, $pt);
        }
    }

    /**
     * Készpénzes fizetési módú bizonylathoz automatikus pénztárbizonylatot képez.
     * Ha már van hozzá ilyen és az összeg változott, a régit rontja és újat képez.
     *
     * @param \Entities\Bizonylatfej $bizfej
     */
    private function createPenztarBizonylat($bizfej)
    {
        if (!$bizfej->getBizonylattipus()?->getAutopenztarbizonylat() || !$bizfej->getId()) {
            return;
        }
        // a hívó maga rögzíti a pénzmozgást
        if ($bizfej->isNincsautopenztarbizonylat()) {
            return;
        }
        // A stornózott (eredeti) bizonylat pénztárbizonylatához nem nyúlunk: a pénz annak
        // idején valóban befolyt. A visszafizetést a storno bizonylat saját, ellentétes
        // irányú pénztárbizonylata rögzíti – ezért a storno bizonylat NEM kivétel itt.
        // A rontott bizonylatot a hívó oldali ront-ág kezeli.
        if ($bizfej->getStornozott() || $bizfej->getRontott()) {
            return;
        }
        // A fizetési mód / pénzmozgás jelölő átállítása kihat a bizonylathoz tartozó, már létrejött
        // pénzmozgásokra. Hogy mi legyen velük, azt a felhasználó dönti el a mentéskor feltett
        // kérdésre adott válasszal – ha nem kérte a rontást, hozzájuk sem nyúlunk.
        $rontkerve = $bizfej->isPenzugyimezovaltozott() && $bizfej->isRontkapcsolodopenzmozgas();
        if ($rontkerve) {
            $this->rontKapcsolodoPenzmozgas($bizfej);
        }

        // Az alábbi három ág mindegyike azt jelenti, hogy a bizonylathoz MOST nem való
        // automatikus pénztárbizonylat.
        if (!$bizfej->getPenztmozgat() || !$bizfej->getPartner()) {
            return;
        }
        $fizmod = $bizfej->getFizmod();
        if (!$fizmod || $fizmod->getTipus() !== 'P') {
            return;
        }
        // részletfizetési ütemezésnél nincs egyösszegű készpénzes kiegyenlítés
        if (\mkw\store::isOsztottFizmod()
            && ($bizfej->getFizetendo1() || $bizfej->getFizetendo2() || $bizfej->getFizetendo3()
                || $bizfej->getFizetendo4() || $bizfej->getFizetendo5())) {
            return;
        }
        // A bizonylatot már kiegyenlítette egy élő bankbizonylat: utalásról készpénzre váltva
        // sem szabad melléképezni egy pénztárbizonylatot, mert az túlfizetést csinálna. (A rontást
        // kérve a fenti ág már lerontotta, tehát ilyenkor ez nem fog fogni.)
        if ($this->vanEloBankKiegyenlites($bizfej)) {
            return;
        }

        // A pénztárbizonylat iránya a bizonylat irányának ellentettje (számla -1 -> bevét,
        // költségszámla +1 -> kiadás), storno bizonylatnál pedig megfordul, mert a pénz
        // visszafelé mozog. Ugyanez a storno-szorzó fordítja az összeg előjelét is, így a
        // szokásos negatív összegű stornóból pozitív kiadás lesz, a ritka pozitív összegűből
        // pedig negatív. A pénztárbizonylat sorszáma is az irányból képződik ('B' / 'K').
        //
        //   számla  (-1)  +10  ->  bevét  +10        számla (-1)  -10  ->  bevét  -10
        //   storno  (-1)  -10  ->  kiadás +10        storno (-1)  +10  ->  kiadás -10
        //   bevét   (+1)  +10  ->  kiadás +10
        $stornoszorzo = $bizfej->getStorno() ? -1 : 1;
        $irany = $bizfej->getIrany() * -1 * $stornoszorzo;
        $osszeg = $bizfej->getFizetendo() * $stornoszorzo;

        // új bizonylathoz még nem tartozhat pénztárbizonylat, ilyenkor a lekérdezés
        // fölösleges (tömeges importnál bizonylatonként egy query)
        $regi = $this->uow->isScheduledForInsert($bizfej) ? null : $this->getAutoPenztarBizonylat($bizfej);
        if (!$regi && !$osszeg) {
            return;
        }

        // a pénztárat már itt föl kell oldani, mert a meglévő pénztárbizonylat
        // összevetésének is része: a bizonylat pénztárát átállítva a régit rontani,
        // az újat pedig a másik pénztárban kell képezni
        $penztar = $this->getAutoPenztar($bizfej);

        if ($regi) {
            if ($this->autoPenztarBizonylatEgyezik($regi, $bizfej, $irany, $osszeg, $penztar)) {
                return;
            }
            $this->rontPenztarBizonylatfej($regi);
        }
        if (!$osszeg) {
            return;
        }

        $jogcim = $this->getAutoJogcim();
        if (!$penztar || !$jogcim) {
            \mkw\store::writelog(
                $bizfej->getId() . ': nincs beállítva pénztár vagy jogcím',
                'autopenztarbizonylat.log'
            );
            return;
        }
        if ($this->penztarZartIdoszak($penztar, $bizfej->getKelt())) {
            \mkw\store::writelog(
                $bizfej->getId() . ': a pénztár időszaka zárt (' . $penztar->getId() . ')',
                'autopenztarbizonylat.log'
            );
            return;
        }

        $pbfej = new Penztarbizonylatfej();
        $pbtetel = new Penztarbizonylattetel();
        $pbfej->addBizonylattetel($pbtetel);

        // A sorrend kötött: a PenztarbizonylatfejListener::generateId() a persist()-kor
        // meghívódó prePersist-ben fut le, és a bizonylattipus + penztar + irany + kelt
        // mezőket használja, tehát azoknak addigra készen kell lenniük.
        $pbfej->setBizonylattipus($this->em->getRepository(Bizonylattipus::class)->find('penztar'));
        $pbfej->setIrany($irany);
        $pbfej->setPenztar($penztar);
        $pbfej->setKelt($bizfej->getKeltStr());
        // a setPartner() a partner valutanemét is ráteszi a fejre, ezért utána
        // írjuk vissza a bizonylatét
        $pbfej->setPartner($bizfej->getPartner());
        $pbfej->setValutanem($bizfej->getValutanem());
        $pbfej->setArfolyam($bizfej->getArfolyam() ?: 1);
        $pbfej->setMegjegyzes(self::AUTOPENZTARMEGJEGYZES);

        $pbtetel->setJogcim($jogcim);
        $pbtetel->setHivatkozottbizonylat($bizfej->getId());
        $pbtetel->setHivatkozottdatum($bizfej->getEsedekessegStr() ?: $bizfej->getKeltStr());
        $pbtetel->setBrutto($osszeg);

        $this->em->persist($pbfej);
        $this->uow->computeChangeSet($this->penztarbizonylatfejmd, $pbfej);
        $this->em->persist($pbtetel);
        $this->uow->computeChangeSet($this->penztarbizonylattetelmd, $pbtetel);
    }

    /**
     * A bizonylathoz tartozó összes élő pénzmozgás (pénztár- ÉS bankbizonylat) rontása, mert a
     * felhasználó ezt kérte a fizetési mód / pénzmozgás jelölő átállításakor. A kézzel rögzítettre
     * is vonatkozik – épp ez a kérdés értelme.
     *
     * Nem törlünk: a rontott bizonylat a sorszámával együtt megmarad, a listán és a naplóban
     * továbbra is látszik, csak a pénzmozgásból esik ki. Új bizonylathoz még nem tartozhat ilyen.
     *
     * @param \Entities\Bizonylatfej $bizfej
     */
    private function rontKapcsolodoPenzmozgas($bizfej)
    {
        if ($this->uow->isScheduledForInsert($bizfej)) {
            return;
        }
        foreach ($this->getEloPenztarBizonylatok($bizfej) as $pbiz) {
            $this->rontPenztarBizonylatfej($pbiz);
        }
        foreach ($this->getEloBankBizonylatok($bizfej) as $bbiz) {
            $this->rontBankBizonylatfej($bbiz);
        }
    }

    /**
     * @param \Entities\Bizonylatfej $bizfej
     *
     * @return \Entities\Penztarbizonylatfej[]
     */
    private function getEloPenztarBizonylatok($bizfej)
    {
        $filter = new \mkwhelpers\FilterDescriptor();
        $filter
            ->addFilter('pt.hivatkozottbizonylat', '=', $bizfej->getId())
            ->addFilter('rontott', '=', false);
        return $this->em->getRepository(Penztarbizonylatfej::class)->getAllByHivatkozottBizonylat($filter);
    }

    /**
     * @param \Entities\Bizonylatfej $bizfej
     *
     * @return \Entities\Bankbizonylatfej[]
     */
    private function getEloBankBizonylatok($bizfej)
    {
        $filter = new \mkwhelpers\FilterDescriptor();
        $filter
            ->addFilter('bt.hivatkozottbizonylat', '=', $bizfej->getId())
            ->addFilter('bt.rontott', '=', false)
            ->addFilter('rontott', '=', false);
        return $this->em->getRepository(Bankbizonylatfej::class)->getAllByHivatkozottBizonylat($filter);
    }

    /**
     * A Bankbizonylatfej::setRontott() a tételeket is átállítja, de a UnitOfWork-kel külön fel kell
     * vetetni a változást – ugyanaz a csapda, mint a pénztárbizonylatnál.
     *
     * @param \Entities\Bankbizonylatfej $bbiz
     */
    private function rontBankBizonylatfej($bbiz)
    {
        $bbiz->setRontott(true);
        $this->em->persist($bbiz);
        $this->uow->recomputeSingleEntityChangeSet($this->bankbizonylatfejmd, $bbiz);
        /** @var \Entities\Bankbizonylattetel $bt */
        foreach ($bbiz->getBizonylattetelek() as $bt) {
            if ($this->uow->isScheduledForInsert($bt)) {
                continue;
            }
            $this->uow->recomputeSingleEntityChangeSet($this->bankbizonylattetelmd, $bt);
        }
    }

    /**
     * Van-e a bizonylatra hivatkozó, nem rontott bankbizonylat tétel – azaz kiegyenlítették-e
     * már banki úton. A getAutoPenztarBizonylat() csak a pénztárbizonylatokat látja, ezért ezt
     * külön kell megnézni.
     *
     * @param \Entities\Bizonylatfej $bizfej
     *
     * @return bool
     */
    private function vanEloBankKiegyenlites($bizfej)
    {
        $filter = new \mkwhelpers\FilterDescriptor();
        $filter
            ->addFilter('bt.hivatkozottbizonylat', '=', $bizfej->getId())
            ->addFilter('bt.rontott', '=', false)
            ->addFilter('rontott', '=', false);
        return (bool)count(
            $this->em->getRepository(Bankbizonylatfej::class)->getAllByHivatkozottBizonylat($filter)
        );
    }

    /**
     * A bizonylathoz tartozó élő pénztárbizonylat. A kézzel rögzítettet is beleértve:
     * a bizonylathoz tartozó pénzmozgásból egyszerre csak egy élhet, a fölöslegeset
     * (akárhogy is keletkezett) rontjuk.
     *
     * @param \Entities\Bizonylatfej $bizfej
     *
     * @return \Entities\Penztarbizonylatfej|null
     */
    private function getAutoPenztarBizonylat($bizfej)
    {
        $filter = new \mkwhelpers\FilterDescriptor();
        $filter
            ->addFilter('pt.hivatkozottbizonylat', '=', $bizfej->getId())
            ->addFilter('rontott', '=', false);
        $pbizek = $this->em->getRepository(Penztarbizonylatfej::class)->getAllByHivatkozottBizonylat($filter);
        if (!count($pbizek)) {
            return null;
        }
        // elvileg csak egy lehet; ha mégis több, a fölöslegeseket rontjuk
        for ($i = 1; $i < count($pbizek); $i++) {
            $this->rontPenztarBizonylatfej($pbizek[$i]);
        }
        return $pbizek[0];
    }

    /**
     * @param \Entities\Penztarbizonylatfej $pbiz
     * @param \Entities\Bizonylatfej $bizfej
     * @param int $irany
     * @param float $osszeg
     * @param \Entities\Penztar|null $penztar
     *
     * @return bool
     */
    private function autoPenztarBizonylatEgyezik($pbiz, $bizfej, $irany, $osszeg, $penztar)
    {
        if ($pbiz->getIrany() != $irany) {
            return false;
        }
        if ($pbiz->getValutanemId() != $bizfej->getValutanemId()) {
            return false;
        }
        // pénztárat váltva a régit rontani kell, hogy az újat a mostani pénztár kapja
        if ($penztar && ($pbiz->getPenztarId() != $penztar->getId())) {
            return false;
        }
        $tetelek = $pbiz->getBizonylattetelek();
        if (count($tetelek) !== 1) {
            return false;
        }
        // a tétel bruttóját nézzük, mert a fejet a PenztarbizonylatfejListener::calcOsszesen()
        // címletre kerekíti, a folyószámla viszont a tétel összegével nettózik
        return abs($tetelek->first()->getBrutto() * 1 - $osszeg) < 0.005;
    }

    /**
     * @param \Entities\Bizonylatfej $bizfej
     *
     * @return \Entities\Penztar|null
     */
    private function getAutoPenztar($bizfej)
    {
        /** @var \Entities\PenztarRepository $prep */
        $prep = $this->em->getRepository(Penztar::class);
        // a bizonylatra kézzel kiválasztott pénztár erősebb a globális beállításnál
        $penztar = $bizfej->getPenztar();
        if (!$penztar) {
            $penztarid = \mkw\store::getParameter(\mkw\consts::AutoPenztarbizonylatPenztar);
            $penztar = $penztarid ? $prep->find($penztarid) : null;
        }
        // beállítás híján – vagy ha a kiválasztott pénztár más valutanemű – a bizonylat
        // valutaneme szerinti pénztárba kerül
        if (!$penztar || ($penztar->getValutanemId() != $bizfej->getValutanemId())) {
            $penztar = $prep->getByValutanem($bizfej->getValutanem());
        }
        return $penztar;
    }

    /**
     * @return \Entities\Jogcim|null
     */
    private function getAutoJogcim()
    {
        $jogcimid = \mkw\store::getParameter(\mkw\consts::AutoPenztarbizonylatJogcim);
        return $jogcimid ? $this->em->getRepository(Jogcim::class)->find($jogcimid) : null;
    }

    /**
     * @param \Entities\Penztar $penztar
     * @param \DateTime|null $kelt
     *
     * @return bool
     */
    private function penztarZartIdoszak($penztar, $kelt)
    {
        $zart = \mkw\store::getParameter(\mkw\consts::PenztarZarva . $penztar->getId());
        if (!$zart || !$kelt) {
            return false;
        }
        // a zárás dátuma Y-m-d alakban van tárolva, azon a lexikografikus összehasonlítás jó
        return $kelt->format(\mkw\store::$SQLDateFormat) <= $zart;
    }

    /**
     * @param \Entities\Bizonylatfej $bizfej
     * @param \Entities\Kupon $kupon
     */
    private function createVasarlasiUtalvany($bizfej, $kupon)
    {
        if (!$kupon || !$kupon->isVasarlasiUtalvany() || !$kupon->isErvenyes()) {
            return;
        }

        $bruttoegysar = $kupon->getOsszeg() * -1;

        $termekid = \mkw\store::getParameter(\mkw\consts::VasarlasiUtalvanyTermek);
        $termek = $this->em->getRepository(Termek::class)->find($termekid);

        if ($termek && $bruttoegysar != 0) {
            $afaoverride = Partner::calcAFAOverride(
                $bizfej->getPartnerszallorszag(),
                $bizfej->getPartnerorszag(),
                $bizfej->getPartnerSzamlatipus(),
                $bizfej->getPartnereuadoszam()
            );
            $k = null;
            foreach ($bizfej->getBizonylattetelek() as $btetel) {
                if ($btetel->getTermekId() == $termekid) {
                    $k = $btetel;
                }
            }
            if ($k) {
                $k->setMennyiseg(1);
                if ($afaoverride) {
                    $k->setAfa($afaoverride);
                } else {
                    $k->setAfa($termek->getAfa());
                }
                $k->setBruttoegysar($bruttoegysar);
                $k->setBruttoegysarhuf($bruttoegysar * $k->getArfolyam());
                $k->calc();
                $this->em->persist($k);
                $this->uow->recomputeSingleEntityChangeSet($this->bizonylattetelmd, $k);
            } else {
                $k = new \Entities\Bizonylattetel();
                $bizfej->addBizonylattetel($k);
                $k->setPersistentData();
                $k->setArvaltoztat(0);
                if ($termek) {
                    $k->setTermek($termek);
                }
                $k->setMozgat();
                $k->setFoglal();
                $k->setMennyiseg(1);
                if ($afaoverride) {
                    $k->setAfa($afaoverride);
                } else {
                    $k->setAfa($termek->getAfa());
                }
                $k->setBruttoegysar($bruttoegysar);
                $k->setBruttoegysarhuf($bruttoegysar * $k->getArfolyam());
                $k->calc();
                $this->em->persist($k);
                $this->uow->computeChangeSet($this->bizonylattetelmd, $k);
            }
        }
    }

    /**
     * Naplózza a bizonylatstátusz beállítását/változását (ki, mikor, miről mire).
     * Új bizonylatnál a kezdő státuszt rögzíti üres "miről" értékkel; meglévő
     * bizonylatnál csak a valódi státuszváltást.
     *
     * @param \Entities\Bizonylatfej[] $insertedentities
     * @param \Entities\Bizonylatfej[] $updatedentities
     */
    private function logStatuszValtozasok($insertedentities, $updatedentities)
    {
        $dolgozo = \mkw\store::getLoggedInDolgozo();

        // Új bizonylat: a kezdő státusz rögzítése, üres "miről".
        foreach ($insertedentities as $entity) {
            if (!($entity instanceof \Entities\Bizonylatfej)) {
                continue;
            }
            $uj = $entity->getBizonylatstatusz();
            if (!$uj) {
                continue;
            }
            $this->createStatuszNaplo($entity, null, $uj, $dolgozo);
        }

        // Meglévő bizonylat: csak akkor, ha a státusz ténylegesen megváltozott.
        foreach ($updatedentities as $entity) {
            if (!($entity instanceof \Entities\Bizonylatfej)) {
                continue;
            }
            $changeset = $this->uow->getEntityChangeSet($entity);
            if (!isset($changeset['bizonylatstatusz'])) {
                continue;
            }
            [$regi, $uj] = $changeset['bizonylatstatusz'];
            if ($regi === $uj) {
                continue;
            }
            $this->createStatuszNaplo(
                $entity,
                $regi instanceof \Entities\Bizonylatstatusz ? $regi : null,
                $uj instanceof \Entities\Bizonylatstatusz ? $uj : null,
                $dolgozo
            );
        }
    }

    /**
     * A pénzügyileg lényeges mezők utólagos változásának naplózása. A fizetési mód, a
     * pénzmozgás jelölő és a pénztár átállítása eddig nyomtalan volt: a bizonylat kintlévősége
     * és a hozzá tartozó pénztárbizonylat is megváltozik tőle, de utólag nem lehetett megmondani,
     * hogy ki és mikor nyúlt hozzá. Csak MEGLÉVŐ bizonylatra naplózunk – az új bizonylat kezdeti
     * értékei magán a bizonylaton látszanak.
     *
     * @param array $updatedentities
     */
    private function logValtozasok($updatedentities)
    {
        $dolgozo = \mkw\store::getLoggedInDolgozo();

        foreach ($updatedentities as $entity) {
            if (!($entity instanceof \Entities\Bizonylatfej)) {
                continue;
            }
            $changeset = $this->uow->getEntityChangeSet($entity);
            $penzugyivaltozas = false;
            foreach (self::NAPLOZOTTMEZOK as $mezo => $mezonev) {
                if (!isset($changeset[$mezo])) {
                    continue;
                }
                [$regi, $uj] = $changeset[$mezo];
                if ($regi === $uj) {
                    continue;
                }
                $regiszoveg = $this->naploErtek($regi);
                $ujszoveg = $this->naploErtek($uj);
                if ($regiszoveg === $ujszoveg) {
                    continue;
                }
                if (in_array($mezo, self::PENZMOZGASTERINTOMEZOK, true)) {
                    $penzugyivaltozas = true;
                }
                $this->createValtozasNaplo($entity, $mezo, $mezonev, $regiszoveg, $ujszoveg, $dolgozo);
            }

            // A createPenztarBizonylat() innen tudja meg, hogy egyáltalán van-e miről dönteni:
            // egy más okból indított mentés nem nyúlhat a meglévő pénzmozgásokhoz.
            $entity->setPenzugyimezovaltozott($penzugyivaltozas);
            if ($penzugyivaltozas && $this->vanKapcsolodoPenzmozgas($entity)) {
                // a felhasználó döntése a mentéskor feltett kérdésre – ez is a bizonylat története
                $this->createValtozasNaplo(
                    $entity,
                    'kapcsolodopenzmozgas',
                    'Kapcsolódó pénzmozgás',
                    '',
                    $entity->isRontkapcsolodopenzmozgas() ? t('rontva') : t('változatlanul hagyva'),
                    $dolgozo
                );
            }
        }
    }

    /**
     * Van-e a bizonylathoz élő pénztár- vagy bankbizonylat. Csak akkor naplózzuk a felhasználó
     * döntését, ha tényleg volt miről dönteni.
     *
     * @param \Entities\Bizonylatfej $bizfej
     *
     * @return bool
     */
    private function vanKapcsolodoPenzmozgas($bizfej)
    {
        if ($this->uow->isScheduledForInsert($bizfej)) {
            return false;
        }
        return (bool)(count($this->getEloPenztarBizonylatok($bizfej)) + count($this->getEloBankBizonylatok($bizfej)));
    }

    /**
     * A naplózandó érték szöveges pillanatképe. Az entitásokat a nevükkel, a jelölőket
     * igen/nem szóval írjuk ki, hogy a napló a törzsadat későbbi átnevezésétől független legyen.
     */
    private function naploErtek($ertek)
    {
        if ($ertek === null || $ertek === '') {
            return '';
        }
        if (is_bool($ertek)) {
            return $ertek ? t('igen') : t('nem');
        }
        if (is_object($ertek) && method_exists($ertek, 'getNev')) {
            return (string)$ertek->getNev();
        }
        return (string)$ertek;
    }

    /**
     * @param \Entities\Bizonylatfej $entity
     * @param \Entities\Dolgozo|null $dolgozo
     */
    private function createValtozasNaplo($entity, $mezo, $mezonev, $regiertek, $ujertek, $dolgozo)
    {
        $naplo = new Bizonylatvaltozasnaplo();
        $naplo->setBizonylatfej($entity);
        $naplo->setCreated(new \DateTime());
        $naplo->setDolgozo($dolgozo);
        $naplo->setMezo($mezo);
        $naplo->setMezonev(t($mezonev));
        $naplo->setRegiertek($regiertek);
        $naplo->setUjertek($ujertek);

        $this->em->persist($naplo);
        $this->uow->computeChangeSet($this->bizonylatvaltozasnaplomd, $naplo);
    }

    /**
     * @param \Entities\Bizonylatfej $entity
     * @param \Entities\Bizonylatstatusz|null $regi
     * @param \Entities\Bizonylatstatusz|null $uj
     * @param \Entities\Dolgozo|null $dolgozo
     */
    private function createStatuszNaplo($entity, $regi, $uj, $dolgozo)
    {
        $naplo = new Bizonylatstatusznaplo();
        $naplo->setBizonylatfej($entity);
        $naplo->setCreated(new \DateTime());
        $naplo->setDolgozo($dolgozo);
        // A setterek a nevet is elmentik pillanatképként – ha később átnevezik
        // a státuszt vagy a dolgozót, a napló nem változik.
        $naplo->setRegistatusz($regi);
        $naplo->setUjstatusz($uj);

        $this->em->persist($naplo);
        $this->uow->computeChangeSet($this->bizonylatstatusznaplomd, $naplo);
    }

    public function prePersist(LifecycleEventArgs $args)
    {
        $this->em = $args->getObjectManager();
        $this->uow = $this->em->getUnitOfWork();

        $this->bizonylatfejmd = $this->em->getClassMetadata(Bizonylatfej::class);
        $this->bizonylattetelmd = $this->em->getClassMetadata(Bizonylattetel::class);
        $this->penztarbizonylatfejmd = $this->em->getClassMetadata(Penztarbizonylatfej::class);
        $this->penztarbizonylattetelmd = $this->em->getClassMetadata(Penztarbizonylattetel::class);
        $this->folyoszamlamd = $this->em->getClassMetadata(Folyoszamla::class);

        $entity = $args->getObject();
        if ($entity instanceof \Entities\Bizonylatfej) {
            $entity->generateId();
            if (!$entity->getBizonylatnyelv()) {
                $entity->setBizonylatnyelvWithFallback();
            }
        }
    }

    public function onFlush(OnFlushEventArgs $args)
    {
        $this->em = $args->getObjectManager();
        $this->uow = $this->em->getUnitOfWork();

        $this->bizonylatfejmd = $this->em->getClassMetadata(Bizonylatfej::class);
        $this->bizonylattetelmd = $this->em->getClassMetadata(Bizonylattetel::class);
        $this->penztarbizonylatfejmd = $this->em->getClassMetadata(Penztarbizonylatfej::class);
        $this->penztarbizonylattetelmd = $this->em->getClassMetadata(Penztarbizonylattetel::class);
        $this->bankbizonylatfejmd = $this->em->getClassMetadata(Bankbizonylatfej::class);
        $this->bankbizonylattetelmd = $this->em->getClassMetadata(Bankbizonylattetel::class);
        $this->folyoszamlamd = $this->em->getClassMetadata(Folyoszamla::class);
        $this->kuponmd = $this->em->getClassMetadata(Kupon::class);
        $this->bizonylatstatusznaplomd = $this->em->getClassMetadata(Bizonylatstatusznaplo::class);
        $this->bizonylatvaltozasnaplomd = $this->em->getClassMetadata(Bizonylatvaltozasnaplo::class);

        $insertedentities = $this->uow->getScheduledEntityInsertions();
        $updatedentities = $this->uow->getScheduledEntityUpdates();

        // A státuszbeállítást/-változást még a bizonylat feldolgozása (recompute) előtt naplózzuk.
        $this->logStatuszValtozasok($insertedentities, $updatedentities);
        // Ugyanígy a pénzügyi mezőkét: a recompute után a changeset már a származtatott
        // mezőkkel is tele lenne, itt viszont pontosan az látszik, amit a felhasználó átírt.
        $this->logValtozasok($updatedentities);

        $entities = array_merge(
            $insertedentities,
            $updatedentities,
        );

        $entities = array_merge($entities, $this->tetelekBizonylatfejei($entities));

        foreach ($entities as $entity) {
            if ($entity instanceof \Entities\Bizonylatfej) {
                /** @var \Entities\Bizonylattetel $tetel */
                foreach ($entity->getBizonylattetelek() as $tetel) {
                    if (!$tetel->getStorno() && !$tetel->getStornozott()) {
                        $tetel->setMozgat();
                        if (\mkw\store::isFoglalas()) {
                            $tetel->setFoglal();
                        }
                        $this->uow->recomputeSingleEntityChangeSet($this->bizonylattetelmd, $tetel);
                    }
                }

                if (!$entity->isSimpleedit()) {
                    /** @var \Entities\Kupon $kupon */
                    $kupon = $entity->getKuponObject();

                    if (!$entity->getStorno()) {
                        $this->createVasarlasiUtalvany($entity, $kupon);
                        $this->createSzallitasiKtg($entity, $kupon);
                        $this->createUtanvetKtg($entity, $kupon);
                        $this->createKezelesiKoltseg($entity);
                    }
                    $entity->calcOsszesen();
                    $entity->calcRugalmasFizmod();
                    $entity->calcOsztottFizetendo();
                    $entity->calcSzallitasiido();
                    $entity->calcNAVBekuldendo();

                    $feketelistarepo = $this->em->getRepository(Feketelista::class);
                    $fok = $feketelistarepo->getFeketelistaOk($entity->getPartneremail(), $entity->getIp());
                    if ($fok === false) {
                        $entity->setPartnerfeketelistas(false);
                        $entity->setPartnerfeketelistaok(null);
                    } else {
                        $entity->setPartnerfeketelistas(true);
                        $entity->setPartnerfeketelistaok($fok);
                    }

                    if ($kupon) {
                        //$kupon->doFelhasznalt();
                        //$this->uow->recomputeSingleEntityChangeSet($this->kuponmd, $kupon);
                    }

                    $this->createFolyoszamla($entity);

                    if (!$entity->getWebshopnum()) {
                        $entity->setWebshopnum(\mkw\store::getWebshopNum());
                    }

                    // Rontáskor a bizonylat pénztárbizonylata is rontott lesz. Stornónál
                    // viszont nem: a stornózott bizonylat pénztárbizonylata érintetlen
                    // marad, a visszafizetést a storno bizonylat saját pénztárbizonylata
                    // rögzíti (azt a createPenztarBizonylat képzi).
                    if ($entity->getRontott()) {
                        $this->rontPenztarBizonylat($entity);
                    } else {
                        $this->createPenztarBizonylat($entity);
                    }

                    $entity->checkHibak();

                    $this->uow->recomputeSingleEntityChangeSet($this->bizonylatfejmd, $entity);
                }
            }
        }
    }

}