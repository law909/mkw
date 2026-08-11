<?php

namespace Listeners;

use Doctrine\ORM\Event\OnFlushEventArgs;
use Doctrine\ORM\Event\PostFlushEventArgs;
use Entities\Bizonylatfej;
use Entities\Bizonylattetel;
use Services\KeszletService;

class BizonylattetelListener
{

    private $willmodify = [];


    public function onFlush(OnFlushEventArgs $args)
    {
        $uow = $args->getObjectManager()->getUnitOfWork();

        $this->willmodify = [];

        $entities = array_merge(
            $uow->getScheduledEntityInsertions(),
            $uow->getScheduledEntityUpdates(),
            $uow->getScheduledEntityDeletions()
        );
        foreach ($entities as $entity) {
            // a fejen a teljesítés és a raktár is beleszól a készletlekérdezésbe
            if ($entity instanceof Bizonylattetel || $entity instanceof Bizonylatfej) {
                $this->willmodify[] = $entity;
            }
        }
    }

    public function postFlush(PostFlushEventArgs $args)
    {
        if ($this->willmodify) {
            KeszletService::clearKeszletCache();
        }
        $this->willmodify = [];
    }

}