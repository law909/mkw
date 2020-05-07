<?php

namespace Listeners;

use Doctrine\ORM\Event\LifecycleEventArgs;
use Doctrine\ORM\Event\OnFlushEventArgs;

class JogareszvetelListener {

    private $em;
    private $uow;
    private $jogaberletmd;


    public function onFlush(OnFlushEventArgs $args) {

        $this->em = $args->getEntityManager();
        $this->uow = $this->em->getUnitOfWork();

        $this->jogaberletmd = $this->em->getClassMetadata('Entities\JogaBerlet');

        $ujak = $this->uow->getScheduledEntityInsertions();
        foreach ($ujak as $entity) {
            if ($entity instanceof \Entities\JogaReszvetel) {

                $berlet = $entity->getJogaberlet();
                if ($berlet) {
                    $berlet->calcLejart(1);
                    $this->uow->recomputeSingleEntityChangeSet($this->jogaberletmd, $berlet);
                }
            }
        }

/*
        $entities = $this->uow->getScheduledEntityUpdates();
        foreach ($entities as $entity) {
            if ($entity instanceof \Entities\JogaReszvetel) {

                $berlet = $entity->getJogaberlet();
                $berlet->calcLejart();
                $this->uow->recomputeSingleEntityChangeSet($this->jogaberletmd, $berlet);

            }
        }
*/
    }
}