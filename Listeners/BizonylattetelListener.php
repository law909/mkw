<?php

namespace Listeners;

use Doctrine\ORM\Event\OnFlushEventArgs;
use Doctrine\ORM\Event\PostFlushEventArgs;
use Entities\Bizonylattetel;
use mkw\store;

class BizonylattetelListener
{

    private $em;
    private $uow;
    private $bizonylattetelmd;
    private $willmodify = [];


    public function onFlush(OnFlushEventArgs $args)
    {
        $this->em = $args->getObjectManager();
        $this->uow = $this->em->getUnitOfWork();

        $this->willmodify = [];

        $entities = array_merge(
            $this->uow->getScheduledEntityInsertions(),
            $this->uow->getScheduledEntityUpdates()
        );
        foreach ($entities as $entity) {
            if ($entity instanceof \Entities\Bizonylattetel) {
                $this->willmodify[] = $entity;
            }
        }
    }

    public function postFlush(PostFlushEventArgs $args)
    {
        $this->em = $args->getObjectManager();
        $this->uow = $this->em->getUnitOfWork();

        $this->bizonylattetelmd = $this->em->getClassMetadata(Bizonylattetel::class);

        $tids = [];
        foreach ($this->willmodify as $entity) {
            if ($entity instanceof \Entities\Bizonylattetel) {
            }
        }

        $this->willmodify = [];
    }

}