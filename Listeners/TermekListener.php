<?php

namespace Listeners;

use Doctrine\ORM\Event\OnFlushEventArgs;
use Doctrine\ORM\Event\PostFlushEventArgs;
use Entities\Termek;

class TermekListener
{
    private $em;
    private $uow;
    private $termekmd;
    private $toadd;
    private $isprocessingpostflush = false;

    public function onFlush(OnFlushEventArgs $args)
    {
        $this->em = $args->getObjectManager();
        $this->uow = $this->em->getUnitOfWork();
        $this->termekmd = $this->em->getClassMetadata(Termek::class);

        $this->toadd = $this->uow->getScheduledEntityInsertions();
        $entities = $this->uow->getScheduledEntityUpdates();
        foreach ($entities as $entity) {
            if ($entity instanceof Termek && !$entity->dontUploadToWC) {
                if (\mkw\store::isWoocommerceOn()) {
                    \mkw\store::writelog('onFlush: ' . $entity->getId() . ': ' . $entity->getNev());
                    $entity->uploadToWC(false);
                    $this->uow->recomputeSingleEntityChangeSet($this->termekmd, $entity);
                }
            }
        }
    }

    public function postFlush(PostFlushEventArgs $args)
    {
        if ($this->isprocessingpostflush) {
            return;
        }
        $flush = false;
        $this->isprocessingpostflush = true;
        $this->em = $args->getObjectManager();
        $this->uow = $this->em->getUnitOfWork();
        $this->termekmd = $this->em->getClassMetadata(Termek::class);
        foreach ($this->toadd as $entity) {
            if ($entity instanceof Termek && !$entity->dontUploadToWC) {
                if (\mkw\store::isWoocommerceOn()) {
                    \mkw\store::writelog('postFlush: ' . $entity->getId() . ': ' . $entity->getNev());
                    $flush = true;
                    $entity->uploadToWC(false);
                }
            }
        }
        if ($flush) {
            \mkw\store::getEm()->flush();
        }
        $this->isprocessingpostflush = false;
    }

}