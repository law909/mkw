<?php

namespace Listeners;

use Doctrine\ORM\Event\LifecycleEventArgs;
use Doctrine\ORM\Event\OnFlushEventArgs;
use Entities\MPTNGYSzakmaianyag;

class MPTNGYSzakmaianyagListener
{

    private $em;
    private $uow;
    private $md;


    public function onFlush(OnFlushEventArgs $args)
    {
        $this->em = $args->getObjectManager();
        $this->uow = $this->em->getUnitOfWork();

        $this->md = $this->em->getClassMetadata(MPTNGYSzakmaianyag::class);

        $entities = array_merge(
            $this->uow->getScheduledEntityInsertions(),
            $this->uow->getScheduledEntityUpdates()
        );

        foreach ($entities as $entity) {
            if ($entity instanceof MPTNGYSzakmaianyag) {
                $entity->setKonferencianszerepelhet($entity->calcKonferencianszerepelhet());
                $entity->setPluszbiralokell($entity->calcPluszBiraloKell());
                $entity->setBiralatkesz($entity->calcBiralatkesz());
                $this->uow->recomputeSingleEntityChangeSet($this->md, $entity);
            }
        }
    }
}