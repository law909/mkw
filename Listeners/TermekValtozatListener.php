<?php

namespace Listeners;

use Doctrine\ORM\Event\OnFlushEventArgs;
use Doctrine\ORM\Event\PostFlushEventArgs;
use Entities\Termek;
use Entities\TermekValtozat;

class TermekValtozatListener
{
    private $em;
    private $uow;
    private $toadd;
    private $isprocessingpostflush = false;

    public function onFlush(OnFlushEventArgs $args)
    {
        $this->em = $args->getObjectManager();
        $this->uow = $this->em->getUnitOfWork();
        $termekmd = $this->em->getClassMetadata(Termek::class);

        $this->toadd = $this->uow->getScheduledEntityInsertions();
        $entities = $this->uow->getScheduledEntityUpdates();
        foreach ($entities as $entity) {
            if ($entity instanceof TermekValtozat && !$entity->dontUploadToWC) {
                if (\mkw\store::isWoocommerceOn()) {
                    \mkw\store::writelog('onFlush: ' . $entity->getId());
                    $entity->getTermek()?->setWcdate(null);
                    \mkw\store::getEm()->persist($entity->getTermek());
                    $this->uow->recomputeSingleEntityChangeSet($termekmd, $entity->getTermek());
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
        $this->em = $args->getObjectManager();
        $this->uow = $this->em->getUnitOfWork();
        foreach ($this->toadd as $entity) {
            if ($entity instanceof TermekValtozat && !$entity->dontUploadToWC) {
                if (\mkw\store::isWoocommerceOn()) {
                    \mkw\store::writelog('postFlush: ' . $entity->getId());
                    $flush = true;
                    $entity->getTermek()?->setWcdate(null);
                    \mkw\store::getEm()->persist($entity->getTermek());
                }
            }
        }
        if ($flush) {
            \mkw\store::getEm()->flush();
        }
        $this->isprocessingpostflush = false;
    }

}