<?php

namespace Listeners;

use Doctrine\ORM\Event\LifecycleEventArgs;
use Doctrine\ORM\Event\OnFlushEventArgs;
use Entities\Arsav;

class ArsavListener
{

    private $em;
    private $uow;
    private $arsavmd;

    public function onFlush(OnFlushEventArgs $args)
    {
        $this->em = $args->getObjectManager();
        $this->uow = $this->em->getUnitOfWork();

        $this->arsavmd = $this->em->getClassMetadata(Arsav::class);

        $torlendok = $this->uow->getScheduledEntityDeletions();
        foreach ($torlendok as $entity) {
            if ($entity instanceof Arsav) {
                \mkw\store::clearParameterIf(\mkw\consts::Arsav, $entity->getId());
                \mkw\store::clearParameterIf(\mkw\consts::ShowTermekArsav, $entity->getId());

                \mkw\store::clearParameterIf(\mkw\consts::Webshop2Price, $entity->getId());
                \mkw\store::clearParameterIf(\mkw\consts::Webshop2Discount, $entity->getId());
                \mkw\store::clearParameterIf(\mkw\consts::Webshop3Price, $entity->getId());
                \mkw\store::clearParameterIf(\mkw\consts::Webshop3Discount, $entity->getId());
                \mkw\store::clearParameterIf(\mkw\consts::Webshop4Price, $entity->getId());
                \mkw\store::clearParameterIf(\mkw\consts::Webshop4Discount, $entity->getId());
                \mkw\store::clearParameterIf(\mkw\consts::Webshop5Price, $entity->getId());
                \mkw\store::clearParameterIf(\mkw\consts::Webshop5Discount, $entity->getId());
            }
        }
    }
}