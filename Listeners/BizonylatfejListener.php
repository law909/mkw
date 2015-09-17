<?php

namespace Listeners;

use Doctrine\ORM\Event\OnFlushEventArgs;

class BizonylatfejListener {

    public function onFlush(OnFlushEventArgs $args) {

        $em = $args->getEntityManager();
        $uow = $em->getUnitOfWork();

        $entities = array_merge(
            $uow->getScheduledEntityInsertions(),
            $uow->getScheduledEntityUpdates()
        );

        foreach($entities as $entity) {
            if ($entity instanceof \Entities\Bizonylatfej) {
                foreach($entity->getBizonylattetelek() as $tetel) {
                    $tetel->setMozgat();
                    $tetel->setFoglal();
                    $md = $em->getClassMetadata('Entities\Bizonylattetel');
                    $uow->recomputeSingleEntityChangeSet($md, $tetel);
                }
            }
        }
    }

}