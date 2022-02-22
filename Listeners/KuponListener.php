<?php

namespace Listeners;

use Doctrine\ORM\Event\LifecycleEventArgs;

class KuponListener {

    private $em;
    private $uow;

    public function prePersist(LifecycleEventArgs $args) {

        $this->em = $args->getEntityManager();
        $this->uow = $this->em->getUnitOfWork();

        $entity = $args->getEntity();
        if ($entity instanceof \Entities\Kupon) {
            \mkw\store::writelog('listener' . $entity->getId());
            $entity->generateId();
        }
    }
}