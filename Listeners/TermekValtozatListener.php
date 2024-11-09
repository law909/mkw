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
    private $termekmd;
    private $toadd;

    public function onFlush(OnFlushEventArgs $args)
    {
        $this->em = $args->getObjectManager();
        $this->uow = $this->em->getUnitOfWork();
        $this->termekmd = $this->em->getClassMetadata(TermekValtozat::class);

        $this->toadd = $this->uow->getScheduledEntityInsertions();
        $entities = $this->uow->getScheduledEntityUpdates();
        foreach ($entities as $entity) {
            if ($entity instanceof TermekValtozat && !$entity->dontUploadToWC) {
                if (\mkw\store::isWoocommerceOn()) {
                    \mkw\store::writelog('onFlush: ' . $entity->getId());
                    $entity->uploadToWC(false);
                    $this->uow->recomputeSingleEntityChangeSet($this->termekmd, $entity);
                }
            }
        }
    }

    public function postFlush(PostFlushEventArgs $args)
    {
        $this->em = $args->getObjectManager();
        $this->uow = $this->em->getUnitOfWork();
        $this->termekmd = $this->em->getClassMetadata(TermekValtozat::class);
        foreach ($this->toadd as $entity) {
            if ($entity instanceof TermekValtozat && !$entity->dontUploadToWC) {
                if (\mkw\store::isWoocommerceOn()) {
                    \mkw\store::writelog('postFlush: ' . $entity->getId());
                    $entity->uploadToWC(false);
                    $this->uow->recomputeSingleEntityChangeSet($this->termekmd, $entity);
                }
            }
        }
    }

}