<?php

namespace Listeners;

use Doctrine\ORM\Event\OnFlushEventArgs;

class BizonylattetelListener
{

    private $em;
    private $uow;
    private $bizonylattetelmd;
    private $bizonylatteteltranslationmd;


    /**
     * @param \Entities\Bizonylattetel $entity
     */
    private function addTermekTranslations($entity)
    {
        $termek = $entity->getTermek();
        if ($termek) {
            foreach ($termek->getTranslations() as $trans) {
                if ($trans->getField() === 'nev') {
                    $volttrans = false;
                    foreach ($entity->getTranslations() as $translation) {
                        if ($translation->getLocale() == $trans->getLocale() && $translation->getField() === 'termeknev') {
                            $volttrans = true;
                            $translation->setContent($trans->getContent());
                            $this->em->persist($translation);
                            $this->uow->recomputeSingleEntityChangeSet($this->bizonylatteteltranslationmd, $translation);
                        }
                    }
                    if (!$volttrans) {
                        $uj = new \Entities\BizonylattetelTranslation($trans->getLocale(), 'termeknev', $trans->getContent());
                        $entity->addTranslation($uj);
                        $this->em->persist($uj);
                        $this->uow->computeChangeSet($this->bizonylatteteltranslationmd, $uj);
                    }
                }
            }
        }
    }

    public function onFlush(OnFlushEventArgs $args)
    {
        $this->em = $args->getObjectManager();
        $this->uow = $this->em->getUnitOfWork();

        $this->bizonylattetelmd = $this->em->getClassMetadata('Entities\Bizonylattetel');
        $this->bizonylatteteltranslationmd = $this->em->getClassMetadata('Entities\BizonylattetelTranslation');

        $entities = array_merge(
            $this->uow->getScheduledEntityInsertions(),
            $this->uow->getScheduledEntityUpdates()
        );

        //foreach ($entities as $entity) {
        //    if ($entity instanceof \Entities\Bizonylattetel) {
        //        $this->addTermekTranslations($entity);
        //    }
        //}
    }

}