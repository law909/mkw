<?php

namespace Listeners;

use Doctrine\ORM\Event\LifecycleEventArgs;

class RendezvenyListener
{

    private $em;
    private $uow;

    public function prePersist(LifecycleEventArgs $args)
    {
        $this->em = $args->getObjectManager();
        $this->uow = $this->em->getUnitOfWork();

        $entity = $args->getObject();
        if ($entity instanceof \Entities\Rendezveny) {
            $entity->generateUId();
        }
    }
}
