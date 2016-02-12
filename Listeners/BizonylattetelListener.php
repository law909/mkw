<?php

namespace Listeners;

use Doctrine\ORM\Event\OnFlushEventArgs;

class BizonylattetelListener {

    private $em;
    private $uow;
    private $bizonylattetelmd;
    private $bizonylatteteltranslationmd;


    /**
     * @param \Entities\Bizonylattetel $entity
     */
    private function addTermekTranslations($entity) {

        foreach ($entity->getTranslations() as $translation) {
            $this->em->remove($translation);
        }
        $entity->getTranslations()->clear();

        $termek = $entity->getTermek();
        if ($termek) {
            foreach ($termek->getTranslations() as $trans) {
                $uj = new \Entities\BizonylattetelTranslation($trans->getLocale(), 'termeknev', $trans->getContent());
                $entity->addTranslation($uj);
                $this->em->persist($uj);
                $this->uow->computeChangeSet($this->bizonylatteteltranslationmd, $uj);
            }
        }
    }

    public function onFlush(OnFlushEventArgs $args) {

        $this->em = $args->getEntityManager();
        $this->uow = $this->em->getUnitOfWork();

        $this->bizonylattetelmd = $this->em->getClassMetadata('Entities\Bizonylattetel');
        $this->bizonylatteteltranslationmd = $this->em->getClassMetadata('Entities\BizonylattetelTranslation');

        $entities = array_merge(
            $this->uow->getScheduledEntityInsertions(),
            $this->uow->getScheduledEntityUpdates()
        );

        foreach ($entities as $entity) {
            if ($entity instanceof \Entities\Bizonylattetel) {

                $this->addTermekTranslations($entity);

            }
        }
    }

}