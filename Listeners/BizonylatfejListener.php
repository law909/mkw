<?php

namespace Listeners;

use Doctrine\ORM\Event\LifecycleEventArgs;
use Doctrine\ORM\Event\OnFlushEventArgs;

class BizonylatfejListener {

    private $em;
    private $uow;
    private $bizonylatfejmd;
    private $bizonylattetelmd;
    private $bizonylatteteltranslationmd;
    private $folyoszamlamd;

    /**
     * @param \Entities\Bizonylatfej $entity
     */
    private function generateTrxId($entity) {
        $conn = $this->em->getConnection();
        $stmt = $conn->prepare('INSERT INTO bizonylatseq (data) VALUES (1)');
        $stmt->execute();
        $entity->setTrxid($conn->lastInsertId());
        $entity->setMasterPassCorrelationID(\mkw\Store::createGUID());
    }

    /**
     * @param \Entities\Bizonylatfej $bizonylat
     * @param $szam
     */
    private function createFSzla($bizonylat, $szam) {
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
        }
        $fszla->setBizonylatfej($bizonylat);
        $this->em->persist($fszla);
        $this->uow->computeChangeSet($this->folyoszamlamd, $fszla);
    }

    /**
     * @param \Entities\Bizonylatfej $bizonylat
     */
    private function createFolyoszamla($bizonylat) {
        if (!$bizonylat->getPenztmozgat()) {
            foreach ($bizonylat->getFolyoszamlak() as $fsz) {
                $this->em->remove($fsz);
            }
            $bizonylat->clearFolyoszamlak();
        }
        else {
            $fm = $bizonylat->getFizmod();
            $fmt = '';
            if ($fm) {
                $fmt = $fm->getTipus();
            }
            if ($fmt !== 'P') {
                foreach ($bizonylat->getFolyoszamlak() as $fsz) {
                    $this->em->remove($fsz);
                }
                $bizonylat->clearFolyoszamlak();

                if (\mkw\Store::isOsztottFizmod()) {
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
                    if (!$volt) {
                        $this->createFSzla($bizonylat, 0);
                    }
                }
                else {
                    $this->createFSzla($bizonylat, 0);
                }
            }
        }
    }

    /**
     * @param \Entities\Bizonylatfej $bizfej
     * @param \Entities\Szallitasimod|null $szallmod
     * @param null $bruttoegysar
     */
    private function createSzallitasiKtg($bizfej) {

        if (!$bizfej->isKellszallitasikoltsegetszamolni()) {
            return;
        }

        $szamol = true;

        $bruttoegysar = $bizfej->getSzallitasikoltsegbrutto();

        $szallmod = $bizfej->getSzallitasimod();
        if ($szallmod) {
            $szamol = $szallmod->getVanszallitasiktg();
        }

        $termekid = \mkw\Store::getParameter(\mkw\consts::SzallitasiKtgTermek);
        $termek = $this->em->getRepository('Entities\Termek')->find($termekid);

        // $bruttoegysar csak vatera megrendeles importkor van megadva, ilyenkor mindegy, hogy milyen szall.mod van
        if ($szamol || $bruttoegysar) {

            $ertek = 0;
            $cnt = 0;
            foreach ($bizfej->getBizonylattetelek() as $btetel) {
                if ($btetel->getTermekId() != $termekid) {
                    $cnt++;
                    $ertek = $ertek + $btetel->getBrutto();
                }
            }
            if ($cnt != 0) {
                if ($bizfej->getPartner() && ($bizfej->getPartner()->getSzamlatipus() > 0)) {
                    $nullasafa = $this->em->getRepository('Entities\Afa')->find(\mkw\Store::getParameter(\mkw\consts::NullasAfa));
                }

                if (!$bruttoegysar) {
                    $ktg = $this->em->getRepository('Entities\SzallitasimodHatar')->getBySzallitasimodValutanemHatar($szallmod, $bizfej->getValutanem(), $ertek);
                    $ktg = $ktg ? $ktg->getOsszeg() : 0;
                }
                else {
                    $ktg = $bruttoegysar;
                }
                $ktg = $ktg * 1;

                if ($ktg) {
                    foreach ($bizfej->getBizonylattetelek() as $btetel) {
                        if ($btetel->getTermekId() == $termekid) {
                            $k = $btetel;
                        }
                    }
                    if ($k) {
                        $k->setMennyiseg(1);
                        if ($nullasafa) {
                            $k->setAfa($nullasafa);
                        }
                        else {
                            $k->setAfa($termek->getAfa());
                        }
                        $k->setBruttoegysar($ktg);
                        $k->setBruttoegysarhuf($ktg * $k->getArfolyam());
                        $k->calc();
                        $this->em->persist($k);
                        $this->uow->recomputeSingleEntityChangeSet($this->bizonylattetelmd, $k);
                    }
                    else {
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
                        if ($nullasafa) {
                            $k->setAfa($nullasafa);
                            $k->setNettoegysar($ktg);
                            $k->setNettoegysarhuf($ktg * $k->getArfolyam());
                        }
                        else {
                            $k->setAfa($termek->getAfa());
                            $k->setBruttoegysar($ktg);
                            $k->setBruttoegysarhuf($ktg * $k->getArfolyam());
                        }
                        $k->calc();
                        $this->em->persist($k);
                        $this->uow->computeChangeSet($this->bizonylattetelmd, $k);
                    }
                }
                else {
                    $this->removeBiztetel($bizfej, $termekid);
                }
            }
            else {
                $this->removeBiztetel($bizfej, $termekid);
            }
        }
        else {
            $this->removeBiztetel($bizfej, $termekid);
        }
    }

    private function removeBiztetel($bizfej, $termekid) {
        foreach ($bizfej->getBizonylattetelek() as $tetel) {
            if ($tetel->getTermekId() == $termekid) {
                $bizfej->removeBizonylattetel($tetel);
                $this->em->remove($tetel);
            }
        }
    }

    public function prePersist(LifecycleEventArgs $args) {

        $this->em = $args->getEntityManager();
        $this->uow = $this->em->getUnitOfWork();

        $this->bizonylatfejmd = $this->em->getClassMetadata('Entities\Bizonylatfej');
        $this->bizonylattetelmd = $this->em->getClassMetadata('Entities\Bizonylattetel');
        $this->bizonylatteteltranslationmd = $this->em->getClassMetadata('Entities\BizonylattetelTranslation');
        $this->folyoszamlamd = $this->em->getClassMetadata('Entities\Folyoszamla');

        $entity = $args->getEntity();
        if ($entity instanceof \Entities\Bizonylatfej) {
            $entity->generateId();
        }
    }

    public function onFlush(OnFlushEventArgs $args) {

        $this->em = $args->getEntityManager();
        $this->uow = $this->em->getUnitOfWork();

        $this->bizonylatfejmd = $this->em->getClassMetadata('Entities\Bizonylatfej');
        $this->bizonylattetelmd = $this->em->getClassMetadata('Entities\Bizonylattetel');
        $this->bizonylatteteltranslationmd = $this->em->getClassMetadata('Entities\BizonylattetelTranslation');
        $this->folyoszamlamd = $this->em->getClassMetadata('Entities\Folyoszamla');

        $entities = array_merge(
            $this->uow->getScheduledEntityInsertions(),
            $this->uow->getScheduledEntityUpdates()
        );

        $ujak = $this->uow->getScheduledEntityInsertions();
        foreach ($ujak as $entity) {
            if ($entity instanceof \Entities\Bizonylatfej) {

                $this->generateTrxId($entity);

                $this->uow->recomputeSingleEntityChangeSet($this->bizonylatfejmd, $entity);
            }
        }

        foreach ($entities as $entity) {
            if ($entity instanceof \Entities\Bizonylatfej) {

                foreach ($entity->getBizonylattetelek() as $tetel) {
                    $tetel->setMozgat();
                    if (\mkw\Store::isFoglalas()) {
                        $tetel->setFoglal();
                    }
                    $this->uow->recomputeSingleEntityChangeSet($this->bizonylattetelmd, $tetel);
                }

                $this->createSzallitasiKtg($entity);

                $entity->calcOsszesen();
                $entity->calcRugalmasFizmod();
                $entity->calcOsztottFizetendo();

                $this->createFolyoszamla($entity);
                $this->uow->recomputeSingleEntityChangeSet($this->bizonylatfejmd, $entity);
            }
        }
    }

}