<?php

namespace Listeners;

use Automattic\WooCommerce\HttpClient\HttpClientException;
use Doctrine\ORM\Event\OnFlushEventArgs;
use Doctrine\ORM\Event\PostFlushEventArgs;
use mkw\store;

class BizonylattetelListener
{

    private $em;
    private $uow;
    private $bizonylattetelmd;
    private $bizonylatteteltranslationmd;
    private $willmodify = [];


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

        $entities = array_merge(
            $this->uow->getScheduledEntityInsertions(),
            $this->uow->getScheduledEntityUpdates()
        );
        foreach ($entities as $entity) {
            if ($entity instanceof \Entities\Bizonylattetel) {
                $this->willmodify[] = $entity;
            }
        }
    }

    public function postFlush(PostFlushEventArgs $args)
    {
        $this->em = $args->getObjectManager();
        $this->uow = $this->em->getUnitOfWork();

        $this->bizonylattetelmd = $this->em->getClassMetadata('Entities\Bizonylattetel');

        $termekek = [];
        $valtozatok = [];
        foreach ($this->willmodify as $entity) {
            if ($entity instanceof \Entities\Bizonylattetel) {
                if (\mkw\store::isWoocommerceOn()) {
                    $termekek['update'][] = $entity->getTermek()->getKeszletToWC(true);
                    if ($entity->getTermekvaltozat()) {
                        $entity->getTermekvaltozat()->sendKeszletToWC();
                    }
                }
            }
        }
        if ($termekek) {
            $wc = store::getWcClient();
            try {
                \mkw\store::writelog('BizonylattetelListener sendKeszlet->termekek: ' . json_encode($termekek));
                $result = $wc->post('products/batch', $termekek);
            } catch (HttpClientException $e) {
                \mkw\store::writelog('BizonylattetelListener sendKeszlet->termekek: :HIBA: ' . $e->getResponse()->getBody());
            }
        }
    }

}