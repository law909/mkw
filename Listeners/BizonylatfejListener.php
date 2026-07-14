<?php

namespace Listeners;

use Doctrine\ORM\Event\LifecycleEventArgs;
use Doctrine\ORM\Event\OnFlushEventArgs;
use Entities\Afa;
use Entities\Bizonylatfej;
use Entities\Bizonylatstatusznaplo;
use Entities\Bizonylattetel;
use Entities\Feketelista;
use Entities\Folyoszamla;
use Entities\Kupon;
use Entities\Partner;
use Entities\Penztarbizonylatfej;
use Entities\Szallitasimod;
use Entities\Termek;

class BizonylatfejListener
{

    private $em;
    private $uow;
    private $bizonylatfejmd;
    private $penztarbizonylatfejmd;
    private $bizonylattetelmd;
    private $folyoszamlamd;
    private $kuponmd;
    private $bizonylatstatusznaplomd;

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
        $this->em->persist($fszla);
        $this->uow->computeChangeSet($this->folyoszamlamd, $fszla);
    }

    /**
     * @param \Entities\Bizonylatfej $bizonylat
     */
    private function createFolyoszamla($bizonylat)
    {
        if (!$bizonylat->getPenztmozgat() || $bizonylat->getNincspenzmozgas()) {
            foreach ($bizonylat->getFolyoszamlak() as $fsz) {
                $this->em->remove($fsz);
            }
            $bizonylat->clearFolyoszamlak();
        } else {
            $fm = $bizonylat->getFizmod();
            $fmt = '';
            if ($fm) {
                $fmt = $fm->getTipus();
            }
            if ($fmt !== 'P' || \mkw\store::isKPFolyoszamla()) {
                foreach ($bizonylat->getFolyoszamlak() as $fsz) {
                    $this->em->remove($fsz);
                }
                $bizonylat->clearFolyoszamlak();

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
            } else {
                foreach ($bizonylat->getFolyoszamlak() as $fsz) {
                    $this->em->remove($fsz);
                }
                $bizonylat->clearFolyoszamlak();
            }
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
            $pbiz->setRontott(true);
            $this->em->persist($pbiz);
            $this->uow->recomputeSingleEntityChangeSet($this->penztarbizonylatfejmd, $pbiz);
        }
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
        $this->folyoszamlamd = $this->em->getClassMetadata(Folyoszamla::class);

        $entity = $args->getObject();
        if ($entity instanceof \Entities\Bizonylatfej) {
            $entity->generateId();
        }
    }

    public function onFlush(OnFlushEventArgs $args)
    {
        $this->em = $args->getObjectManager();
        $this->uow = $this->em->getUnitOfWork();

        $this->bizonylatfejmd = $this->em->getClassMetadata(Bizonylatfej::class);
        $this->bizonylattetelmd = $this->em->getClassMetadata(Bizonylattetel::class);
        $this->penztarbizonylatfejmd = $this->em->getClassMetadata(Penztarbizonylatfej::class);
        $this->folyoszamlamd = $this->em->getClassMetadata(Folyoszamla::class);
        $this->kuponmd = $this->em->getClassMetadata(Kupon::class);
        $this->bizonylatstatusznaplomd = $this->em->getClassMetadata(Bizonylatstatusznaplo::class);

        $insertedentities = $this->uow->getScheduledEntityInsertions();
        $updatedentities = $this->uow->getScheduledEntityUpdates();

        // A státuszbeállítást/-változást még a bizonylat feldolgozása (recompute) előtt naplózzuk.
        $this->logStatuszValtozasok($insertedentities, $updatedentities);

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

                    if ($entity->getStorno() || $entity->getRontott()) {
                        $this->rontPenztarBizonylat($entity);
                    }

                    $entity->checkHibak();

                    $this->uow->recomputeSingleEntityChangeSet($this->bizonylatfejmd, $entity);
                }
            }
        }
    }

}