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
        $tids = [];
        foreach ($this->willmodify as $entity) {
            if ($entity instanceof \Entities\Bizonylattetel) {
                if (\mkw\store::isWoocommerceOn()) {
                    if (!$tids[$entity->getTermek()->getId()]) {
                        $tids[$entity->getTermek()->getId()] = true;
                        $termekek[] = $entity->getTermek()->getStockInfoForWC(true);
                    }
                    if ($entity->getTermekvaltozat()) {
                        \mkw\store::writelog('BizonylattetelListener termekvaltozat->sendkeszlet START');
                        $entity->getTermekvaltozat()->sendKeszletToWC();
                        \mkw\store::writelog('STOP');
                    }
                }
            }
        }
        if ($termekek && \mkw\store::isWoocommerceOn()) {
            $wc = store::getWcClient();
            $tosend = [];
            foreach ($termekek as $index => $termek) {
                $tosend['update'][] = $termek;
                if (($index + 1) % 100 == 0 || $index + 1 == count($termekek)) {
                    try {
                        \mkw\store::writelog('BizonylattetelListener sendKeszlet->termekek START: ' . json_encode($tosend));
                        $result = $wc->post('products/batch', $tosend);
                        \mkw\store::writelog('STOP');
                        $tosend = [];
                    } catch (HttpClientException $e) {
                        \mkw\store::writelog('BizonylattetelListener sendKeszlet->termekek: :HIBA: ' . $e->getResponse()->getBody());
                    }
                }
            }
        }
    }

}