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

    public function onFlush(OnFlushEventArgs $args)
    {
        $this->em = $args->getObjectManager();
        $this->uow = $this->em->getUnitOfWork();
        $this->termekmd = $this->em->getClassMetadata(Termek::class);

        $entities = array_merge(
            $this->uow->getScheduledEntityInsertions(),
            $this->uow->getScheduledEntityUpdates()
        );
        foreach ($entities as $entity) {
            if ($entity instanceof Termek) {
                if (\mkw\store::getSetupValue('woocommerce')) {
                    \mkw\store::writelog('onFlush: ' . $entity->getId() . ': ' . $entity->getNev());
                    $entity->uploadToWc(false);
                    $this->uow->recomputeSingleEntityChangeSet($this->termekmd, $entity);
                }
            }
        }
    }

}