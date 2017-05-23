<?php

namespace Listeners;

use Doctrine\ORM\Event\LifecycleEventArgs;
use Doctrine\ORM\Event\OnFlushEventArgs;

class PenztarbizonylatfejListener {

    private $em;
    private $uow;
    private $bizonylatfejmd;
    private $bizonylattetelmd;
    private $folyoszamlamd;


    /**
     * @param \Entities\Penztarbizonylatfej $entity
     * @param null $from
     */
    public function generateId($entity, $from = null) {
        if ($entity->getId()) {
            return $entity->getId();
        }

        $bt = $entity->getBizonylattipus();
        $penztarid = $entity->getPenztarId();
        $irany = $entity->getIrany();
        $szam = 0;
        if ($bt && is_null($entity->getId())) {
            $azon = $bt->getAzonosito();
            if (is_null($azon)) {
                $azon = '';
            }
            $azon = $azon . $penztarid;
            switch ($entity->getIrany()) {
                case 1:
                    $azon = $azon . 'B';
                    break;
                case -1:
                    $azon = $azon . 'K';
                    break;
            }
            $kezdo = $bt->getKezdosorszam();
            $ev = $entity->getKelt()->format('Y');
            if (!$from) {
                $q = $this->em->createQuery('SELECT COUNT(bf) FROM Entities\Penztarbizonylatfej bf WHERE (bf.bizonylattipus=:p) AND (bf.penztar=:pid) AND (bf.irany=:pir)');
                $q->setParameters(array(
                    'p' => $bt,
                    'pid' => $penztarid,
                    'pir' => $irany
                ));
                if ($q->getSingleScalarResult() > 0) {
                    $kezdo = 1;
                }
                if (!$kezdo) {
                    $kezdo = 1;
                }
                $szam = $kezdo;
                $q = $this->em->createQuery('SELECT MAX(bf.id) FROM Entities\Penztarbizonylatfej bf WHERE (bf.bizonylattipus=:p1) AND (YEAR(bf.kelt)=:p2) AND (bf.penztar=:pid) AND (bf.irany=:pir)');
                $q->setParameters(array(
                    'p1' => $bt,
                    'p2' => $ev,
                    'pid' => $penztarid,
                    'pir' => $irany
                ));
                $max = $q->getSingleScalarResult();
                if ($max) {
                    $szam = explode('/', $max);
                    if (is_array($szam)) {
                        $szam = $szam[1] + 1;
                    }
                }
            }
            else {
                $szam = $from;
                $q = $this->em->createQuery('SELECT MAX(bf.id) FROM Entities\Penztarbizonylatfej bf WHERE (bf.bizonylattipus=:p1) AND (YEAR(bf.kelt)=:p2) AND (bf.penztar=:pid) AND (bf.irany=:pir)');
                $q->setParameters(array(
                    'p1' => $bt,
                    'p2' => $ev,
                    'pid' => $penztarid,
                    'pir' => $irany
                ));
                $max = $q->getSingleScalarResult();
                if ($max) {
                    $szam = explode('/', $max);
                    if (is_array($szam)) {
                        $szam = $szam[1] + 1;
                    }
                }
                if ($szam < $from) {
                    $szam = $from;
                }
            }
            $entity->setId(\mkw\store::createBizonylatszam($azon, $ev, $szam));
        }
        return $szam;
    }

    /**
     * @param \Entities\Penztarbizonylatfej $bizonylat
     */
    protected function createFolyoszamla($bizonylat) {

        foreach($bizonylat->getFolyoszamlak() as $fsz) {
            $this->em->remove($fsz);
        }
        $bizonylat->clearFolyoszamlak();

        /** @var \Entities\Penztarbizonylattetel $tetel */
        foreach($bizonylat->getBizonylattetelek() as $tetel) {
            $bbf = $tetel->getBizonylatfej();
            if ($tetel->getHivatkozottbizonylat()) {
                /** @var \Entities\Bizonylatfej $bf */
                $bf = \mkw\store::getEm()->getRepository('Entities\Bizonylatfej')->find($tetel->getHivatkozottbizonylat());
            }
            $fszla = new \Entities\Folyoszamla();
            $fszla->setDatum($bbf->getKelt());
            $fszla->setPartner($bbf->getPartner());
            if ($bf) {
                $fszla->setUzletkoto($bf->getUzletkoto());
                $fszla->setFizmod($bf->getFizmod());
            }
            $fszla->setBizonylattipus($bbf->getBizonylattipus());
            $fszla->setRontott($tetel->getRontott());
            $fszla->setStorno(false);
            $fszla->setStornozott(false);
            $fszla->setHivatkozottbizonylat($tetel->getHivatkozottbizonylat());
            $fszla->setHivatkozottdatum($tetel->getHivatkozottdatum());
            $fszla->setValutanem($bbf->getValutanem());
            $fszla->setIrany($bbf->getIrany() * -1);
            $fszla->setNetto($tetel->getNetto());
            $fszla->setAfa($tetel->getAfa());
            $fszla->setBrutto($tetel->getBrutto());
            $fszla->setPenztarbizonylatfej($tetel->getBizonylatfej());
            $fszla->setPenztarbizonylattetel($tetel);

            $this->em->persist($fszla);
            $this->uow->computeChangeSet($this->folyoszamlamd, $fszla);
        }
    }

    /**
     * @param \Entities\Penztarbizonylatfej $entity
     */
    public function calcOsszesen($entity) {
        $mincimlet = 0;
        $kerekit = false;
        if ($entity->getValutanem()) {
            $mincimlet = $entity->getValutanem()->getMincimlet();
            $kerekit = $entity->getValutanem()->getKerekit();
        }
        $netto = 0;
        $afa = 0;
        $brutto = 0;
        foreach ($entity->getBizonylattetelek() as $bt) {
            $netto += $bt->getNetto();
            $afa += $bt->getAfa();
            $brutto += $bt->getBrutto();
        }
        $entity->setBrutto($brutto);
        if ($kerekit) {
            $entity->setBrutto(round($brutto));
        }
        if ($mincimlet && ($entity->getBizonylattipusId() === 'penztar')) {
            $entity->setBrutto(\mkw\store::kerekit($entity->getBrutto(), $mincimlet));
        }
    }

    public function prePersist(LifecycleEventArgs $args) {

        $this->em = $args->getEntityManager();
        $this->uow = $this->em->getUnitOfWork();

        $this->bizonylatfejmd = $this->em->getClassMetadata('Entities\Penztarbizonylatfej');
        $this->bizonylattetelmd = $this->em->getClassMetadata('Entities\Penztarbizonylattetel');
        $this->folyoszamlamd = $this->em->getClassMetadata('Entities\Folyoszamla');

        $entity = $args->getEntity();
        if ($entity instanceof \Entities\Penztarbizonylatfej) {
            $this->generateId($entity);
        }
    }

    public function onFlush(OnFlushEventArgs $args) {

        $this->em = $args->getEntityManager();
        $this->uow = $this->em->getUnitOfWork();

        $this->bizonylatfejmd = $this->em->getClassMetadata('Entities\Penztarbizonylatfej');
        $this->bizonylattetelmd = $this->em->getClassMetadata('Entities\Penztarbizonylattetel');
        $this->folyoszamlamd = $this->em->getClassMetadata('Entities\Folyoszamla');

        $entities = array_merge(
            $this->uow->getScheduledEntityInsertions(),
            $this->uow->getScheduledEntityUpdates()
        );

        $ujak = $this->uow->getScheduledEntityInsertions();
        foreach ($ujak as $entity) {
            if ($entity instanceof \Entities\Penztarbizonylatfej) {

                $this->generateId($entity);

                $this->uow->recomputeSingleEntityChangeSet($this->bizonylatfejmd, $entity);
            }
        }

        foreach ($entities as $entity) {
            if ($entity instanceof \Entities\Penztarbizonylatfej) {

                $this->calcOsszesen($entity);
                $this->createFolyoszamla($entity);

                $this->uow->recomputeSingleEntityChangeSet($this->bizonylatfejmd, $entity);
            }
        }
    }

}