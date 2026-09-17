<?php

namespace Listeners;

use Doctrine\ORM\Event\OnFlushEventArgs;
use Entities\Termek;

/**
 * A termék `contentmod` mezőjét tartja karban: azt az időpontot, amikor a termék
 * *tartalma* változott — a név, a leírások, a kép, az ár, vagy a kaphatóság.
 *
 * Miért kell: a `lastmod` minden mentéskor frissül (Gedmo Timestampable), így egy napi
 * készlet- vagy árszinkron után az egész kínálat "ma módosult"-nak látszik. A sitemap
 * ilyen lastmod-ja nem mond semmit a keresőnek, sőt zajt csinál. A `contentmod` csak
 * akkor lép, ha a látogató is más oldalt látna.
 */
class TermekListener
{

    /** Ezek változása látszik a terméklapon; minden más (készletszám, statisztika) nem. */
    const CONTENTFIELDS = [
        'nev',
        'rovidleiras',
        'leiras',
        'kepurl',
        'netto',
        'brutto',
        'nemkaphato',
        'cikkszam',
        'me',
        'slug',
    ];

    public function onFlush(OnFlushEventArgs $args)
    {
        $em = $args->getObjectManager();
        $uow = $em->getUnitOfWork();
        $md = $em->getClassMetadata(Termek::class);
        $now = new \DateTime();

        foreach ($uow->getScheduledEntityInsertions() as $entity) {
            if ($entity instanceof Termek && !$entity->getContentmod()) {
                $entity->setContentmod($now);
                $uow->recomputeSingleEntityChangeSet($md, $entity);
            }
        }
        foreach ($uow->getScheduledEntityUpdates() as $entity) {
            if (!($entity instanceof Termek)) {
                continue;
            }
            if (array_intersect(self::CONTENTFIELDS, array_keys($uow->getEntityChangeSet($entity)))) {
                $entity->setContentmod($now);
                $uow->recomputeSingleEntityChangeSet($md, $entity);
            }
        }
    }
}
