<?php

namespace Listeners;

use Doctrine\ORM\Event\LifecycleEventArgs;
use Doctrine\ORM\Event\OnFlushEventArgs;
use Entities\Afa;
use Entities\Feketelista;
use Entities\Szallitasimod;
use Entities\Termek;

class BizonylatfejListener
{

    private $em;
    private $uow;
    private $bizonylatfejmd;
    private $bizonylatfejtranslationmd;
    private $penztarbizonylatfejmd;
    private $bizonylattetelmd;
    private $bizonylatteteltranslationmd;
    private $folyoszamlamd;
    private $kuponmd;

    /**
     * @param \Entities\Bizonylatfej $entity
     */
    private function generateTrxId($entity)
    {
        $conn = $this->em->getConnection();
        $stmt = $conn->prepare('INSERT INTO bizonylatseq (data) VALUES (1)');
        $stmt->executeStatement();
        $entity->setTrxid($conn->lastInsertId());
        $entity->setMasterPassCorrelationID(\mkw\store::createGUID());
    }

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
     * @param $nullasafa
     * @param $termek
     *
     * @return void
     */
    private function createBiztetel($ktg, \Entities\Bizonylatfej $bizfej, mixed $termekid): void
    {
        $ktg = $ktg * 1;

        if ($ktg) {
            if ($bizfej->getPartner() && ($bizfej->getPartner()->getSzamlatipus() > 0)) {
                $nullasafa = $this->em->getRepository(Afa::class)->find(\mkw\store::getParameter(\mkw\consts::NullasAfa));
            }
            $termek = $this->em->getRepository(Termek::class)->find($termekid);

            foreach ($bizfej->getBizonylattetelek() as $btetel) {
                if ($btetel->getTermekId() == $termekid) {
                    $k = $btetel;
                }
            }
            if ($k) {
                $k->setMennyiseg(1);
                if ($nullasafa) {
                    $k->setAfa($nullasafa);
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
                if ($nullasafa) {
                    $k->setAfa($nullasafa);
                    $k->setNettoegysar($ktg);
                    $k->setNettoegysarhuf($ktg * $k->getArfolyam());
                } else {
                    $k->setAfa($termek->getAfa());
                    $k->setBruttoegysar($ktg);
                    $k->setBruttoegysarhuf($ktg * $k->getArfolyam());
                }
                $k->calc();
                $this->em->persist($k);
                $this->uow->computeChangeSet($this->bizonylattetelmd, $k);
            }
        } else {
            $this->removeBiztetel($bizfej, $termekid);
        }
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
        // $bruttoegysar csak vatera megrendeles importkor van megadva, ilyenkor mindegy, hogy milyen szall.mod van
        if ($szamol || $bruttoegysar) {
            if ($bizsum->cnt != 0) {
                if (!$bruttoegysar) {
                    $ktg = $this->em->getRepository(Szallitasimod::class)->getSzallitasiKoltseg(
                        $szallmod,
                        $bizfej->getPartner()->getOrszag(),
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
        $kezktg = $szallmod->getTermek();
        if ($kezktg) {
            if ($bizfej->getPartner() && ($bizfej->getPartner()->getSzamlatipus() > 0)) {
                $nullasafa = $this->em->getRepository(Afa::class)->find(\mkw\store::getParameter(\mkw\consts::NullasAfa));
            }
            foreach ($bizfej->getBizonylattetelek() as $btetel) {
                if ($btetel->getTermekId() == $kezktg->getId()) {
                    $k = $btetel;
                }
            }
            if ($k) {
                $k->setMennyiseg(1);
                if ($nullasafa) {
                    $k->setAfa($nullasafa);
                }
                $k->setBruttoegysar($kezktg->getBruttoAr());
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
                if ($nullasafa) {
                    $k->setAfa($nullasafa);
                    $k->setNettoegysar($kezktg->getNettoAr());
                    $k->setNettoegysarhuf($kezktg->getNettoAr() * $k->getArfolyam());
                } else {
                    $k->setAfa($kezktg->getAfa());
                    $k->setBruttoegysar($kezktg->getBruttoAr());
                    $k->setBruttoegysarhuf($kezktg->getBruttoAr() * $k->getArfolyam());
                }
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
        $pfrep = $this->em->getRepository('Entities\Penztarbizonylatfej');
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
        $termek = $this->em->getRepository('Entities\Termek')->find($termekid);

        if ($termek && $bruttoegysar != 0) {
            if ($bizfej->getPartner() && ($bizfej->getPartner()->getSzamlatipus() > 0)) {
                $nullasafa = $this->em->getRepository('Entities\Afa')->find(\mkw\store::getParameter(\mkw\consts::NullasAfa));
            }

            foreach ($bizfej->getBizonylattetelek() as $btetel) {
                if ($btetel->getTermekId() == $termekid) {
                    $k = $btetel;
                }
            }
            if ($k) {
                $k->setMennyiseg(1);
                if ($nullasafa) {
                    $k->setAfa($nullasafa);
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
                if ($nullasafa) {
                    $k->setAfa($nullasafa);
                    $k->setNettoegysar($bruttoegysar);
                    $k->setNettoegysarhuf($bruttoegysar * $k->getArfolyam());
                } else {
                    $k->setAfa($termek->getAfa());
                    $k->setBruttoegysar($bruttoegysar);
                    $k->setBruttoegysarhuf($bruttoegysar * $k->getArfolyam());
                }
                $k->calc();
                $this->em->persist($k);
                $this->uow->computeChangeSet($this->bizonylattetelmd, $k);
            }
        }
    }

    /**
     * @param \Entities\Bizonylatfej $entity
     */
    private function addFizmodTranslations($entity)
    {
        $fizmod = $entity->getFizmod();
        if ($fizmod) {
            foreach ($fizmod->getTranslations() as $trans) {
                if ($trans->getField() === 'nev') {
                    $volttrans = false;
                    foreach ($entity->getTranslations() as $translation) {
                        if ($translation->getLocale() == $trans->getLocale() && $translation->getField() === 'fizmodnev') {
                            $volttrans = true;
                            $translation->setContent($trans->getContent());
                            $this->em->persist($translation);
                            $this->uow->recomputeSingleEntityChangeSet($this->bizonylatfejtranslationmd, $translation);
                        }
                    }
                    if (!$volttrans) {
                        $uj = new \Entities\BizonylatfejTranslation($trans->getLocale(), 'fizmodnev', $trans->getContent());
                        $entity->addTranslation($uj);
                        $this->em->persist($uj);
                        $this->uow->computeChangeSet($this->bizonylatfejtranslationmd, $translation);
                    }
                }
            }
        }
    }

    /**
     * @param \Entities\Bizonylatfej $entity
     */
    private function addSzallmodTranslations($entity)
    {
        $szallmod = $entity->getSzallitasimod();
        if ($szallmod) {
            foreach ($szallmod->getTranslations() as $trans) {
                if ($trans->getField() === 'nev') {
                    $volttrans = false;
                    foreach ($entity->getTranslations() as $translation) {
                        if ($translation->getLocale() == $trans->getLocale() && $translation->getField() === 'szallitasimodnev') {
                            $volttrans = true;
                            $translation->setContent($trans->getContent());
                            $this->em->persist($translation);
                            $this->uow->recomputeSingleEntityChangeSet($this->bizonylatfejtranslationmd, $translation);
                        }
                    }
                    if (!$volttrans) {
                        $uj = new \Entities\BizonylatfejTranslation($trans->getLocale(), 'szallitasimodnev', $trans->getContent());
                        $entity->addTranslation($uj);
                        $this->em->persist($uj);
                        $this->uow->computeChangeSet($this->bizonylatfejtranslationmd, $translation);
                    }
                }
            }
        }
    }

    public function prePersist(LifecycleEventArgs $args)
    {
        $this->em = $args->getObjectManager();
        $this->uow = $this->em->getUnitOfWork();

        $this->bizonylatfejmd = $this->em->getClassMetadata('Entities\Bizonylatfej');
        $this->bizonylatfejtranslationmd = $this->em->getClassMetadata('Entities\BizonylatfejTranslation');
        $this->bizonylattetelmd = $this->em->getClassMetadata('Entities\Bizonylattetel');
        $this->bizonylatteteltranslationmd = $this->em->getClassMetadata('Entities\BizonylattetelTranslation');
        $this->penztarbizonylatfejmd = $this->em->getClassMetadata('Entities\Penztarbizonylatfej');
        $this->folyoszamlamd = $this->em->getClassMetadata('Entities\Folyoszamla');

        $entity = $args->getObject();
        if ($entity instanceof \Entities\Bizonylatfej) {
            $entity->generateId();
        }
    }

    public function onFlush(OnFlushEventArgs $args)
    {
        $this->em = $args->getObjectManager();
        $this->uow = $this->em->getUnitOfWork();

        $this->bizonylatfejmd = $this->em->getClassMetadata('Entities\Bizonylatfej');
        $this->bizonylatfejtranslationmd = $this->em->getClassMetadata('Entities\BizonylatfejTranslation');
        $this->bizonylattetelmd = $this->em->getClassMetadata('Entities\Bizonylattetel');
        $this->bizonylatteteltranslationmd = $this->em->getClassMetadata('Entities\BizonylattetelTranslation');
        $this->penztarbizonylatfejmd = $this->em->getClassMetadata('Entities\Penztarbizonylatfej');
        $this->folyoszamlamd = $this->em->getClassMetadata('Entities\Folyoszamla');
        $this->kuponmd = $this->em->getClassMetadata('Entities\Kupon');

        $updatedentities = $this->uow->getScheduledEntityUpdates();
        $entities = array_merge(
            $this->uow->getScheduledEntityInsertions(),
            $updatedentities,
        );

        foreach ($updatedentities as $entity) {
            if ($entity instanceof \Entities\Bizonylatfej && $entity->getWcid() && !$entity->dontUploadToWC) {
                $changeSet = $this->uow->getEntityChangeSet($entity);
                if (isset($changeSet['bizonylatstatusz'])) {
                    [$oldValue, $newValue] = $changeSet['bizonylatstatusz'];
                    if ($oldValue->getId() !== $newValue->getId()) {
                        $entity->sendStatusChangeToWc();
                    }
                }
            }
        }
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
                        $this->createKezelesiKoltseg($entity, $kupon);
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

                    //$this->addFizmodTranslations($entity);
                    //$this->addSzallmodTranslations($entity);

                    $this->createFolyoszamla($entity);

                    if (!$entity->getWebshopnum()) {
                        $entity->setWebshopnum(\mkw\store::getWebshopNum());
                    }

                    if ($entity->getStorno() || $entity->getRontott()) {
                        $this->rontPenztarBizonylat($entity);
                    } else {
                    }
                    $this->uow->recomputeSingleEntityChangeSet($this->bizonylatfejmd, $entity);
                }
            }
        }
    }

}