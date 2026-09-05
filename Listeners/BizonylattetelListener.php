<?php

namespace Listeners;

use Doctrine\ORM\Event\OnFlushEventArgs;
use Doctrine\ORM\Event\PostFlushEventArgs;
use Entities\Bizonylatfej;
use Entities\Bizonylattetel;
use Services\KeszletService;

class BizonylattetelListener
{

    private $willmodify = [];

    /** a FIFO-ban újraszámolandó készletcsoportok: "raktarid|termekid|valtozatid" => hármas */
    private $fifocsoportok = [];


    public function onFlush(OnFlushEventArgs $args)
    {
        $em = $args->getObjectManager();
        $uow = $em->getUnitOfWork();

        $this->willmodify = [];
        $this->fifocsoportok = [];

        $entities = array_merge(
            $uow->getScheduledEntityInsertions(),
            $uow->getScheduledEntityUpdates(),
            $uow->getScheduledEntityDeletions()
        );
        $fifokell = \mkw\store::isFifo();
        foreach ($entities as $entity) {
            // a fejen a teljesítés és a raktár is beleszól a készletlekérdezésbe
            if ($entity instanceof Bizonylattetel || $entity instanceof Bizonylatfej) {
                $this->willmodify[] = $entity;
                if ($fifokell) {
                    // a csoportokat MÉG ITT kell kiolvasni: a postFlush-ban a törölt tétel
                    // fejére mutató proxy már nem tölthető, a fej tételsorai pedig – a DB
                    // oldali cascade miatt – nyomtalanul eltűnnek
                    $this->collectFifo($em, $uow, $entity);
                }
            }
        }
    }

    public function postFlush(PostFlushEventArgs $args)
    {
        if ($this->willmodify) {
            KeszletService::clearKeszletCache();
        }
        if ($this->fifocsoportok) {
            $this->writeFifo($args->getObjectManager());
        }
        $this->willmodify = [];
        $this->fifocsoportok = [];
    }

    /**
     * Egy változó entitás érintett készletcsoportjai. Mindig a régi ÉS az új értéket is
     * felvesszük: egy raktár- vagy változatcsere két csoportot mozgat.
     */
    private function collectFifo($em, $uow, $entity): void
    {
        $valtozas = $uow->getEntityChangeSet($entity);

        if ($entity instanceof Bizonylattetel) {
            $fej = $entity->getBizonylatfej();
            $raktarid = $fej ? $fej->getRaktarId() : null;
            // a fejcsere a régi fej raktárát is érinti
            if (isset($valtozas['bizonylatfej'][0]) && $valtozas['bizonylatfej'][0]) {
                $this->addFifo($valtozas['bizonylatfej'][0]->getRaktarId(), $entity->getTermekId(), $entity->getTermekvaltozatId());
            }
            foreach ($this->regiEsUj($valtozas, 'termek', $entity->getTermekId()) as $termekid) {
                foreach ($this->regiEsUj($valtozas, 'termekvaltozat', $entity->getTermekvaltozatId()) as $valtozatid) {
                    $this->addFifo($raktarid, $termekid, $valtozatid);
                }
            }
            return;
        }

        // Bizonylatfej: a raktár, a teljesítés és a rontott/stornó állapot az összes tételét
        // átmozgatja. A tételeket nyers SQL-lel kérdezzük le – a lusta kollekció bejárása
        // flush közben entitásokat hidratálna, nagy bizonylaton ráadásul drágán.
        if ($uow->isScheduledForInsert($entity)) {
            // új fejnek a tételei külön entitásként jönnek, azokból már megvan a csoport
            return;
        }
        $erdekes = ['raktar', 'teljesites', 'rontott', 'storno', 'stornozott'];
        if (!$uow->isScheduledForDelete($entity) && !array_intersect($erdekes, array_keys($valtozas))) {
            return;
        }
        $raktarak = [$entity->getRaktarId()];
        if (isset($valtozas['raktar'][0]) && $valtozas['raktar'][0]) {
            $raktarak[] = $valtozas['raktar'][0]->getId();
        }
        foreach ($this->getTetelKulcsok($em, $entity->getId()) as [$termekid, $valtozatid]) {
            foreach ($raktarak as $raktarid) {
                $this->addFifo($raktarid, $termekid, $valtozatid);
            }
        }
    }

    /** A changeset régi és új értéke azonosítóként; ha a mező nem változott, csak a mostani. */
    private function regiEsUj(array $valtozas, string $mezo, $mostani): array
    {
        $ret = [$mostani];
        if (isset($valtozas[$mezo][0]) && $valtozas[$mezo][0]) {
            $ret[] = $valtozas[$mezo][0]->getId();
        }
        return array_unique($ret, SORT_REGULAR);
    }

    private function getTetelKulcsok($em, $bizonylatfejid): array
    {
        if (!$bizonylatfejid) {
            return [];
        }
        $sorok = $em->getConnection()->fetchAllNumeric(
            'SELECT DISTINCT termek_id, termekvaltozat_id FROM bizonylattetel WHERE bizonylatfej_id = ?',
            [$bizonylatfejid]
        );
        return $sorok;
    }

    private function addFifo($raktarid, $termekid, $valtozatid): void
    {
        if (!$termekid) {
            return;
        }
        $csoport = [(int)$raktarid, (int)$termekid, (int)$valtozatid];
        $this->fifocsoportok[implode('|', $csoport)] = $csoport;
    }

    /**
     * A megjelölt csoportok a `fifovaltozas` táblába. Csak jelölünk – a számítás az éjszakai
     * cronban fut, a bizonylatmentés útjába nem tesszük bele.
     */
    private function writeFifo($em): void
    {
        $ertekek = [];
        foreach ($this->fifocsoportok as $csoport) {
            foreach ($csoport as $v) {
                $ertekek[] = $v;
            }
            $ertekek[] = date('Y-m-d H:i:s');
        }
        try {
            $em->getConnection()->executeStatement(
                'INSERT IGNORE INTO fifovaltozas (raktar_id, termek_id, termekvaltozat_id, created) VALUES '
                . implode(',', array_fill(0, count($this->fifocsoportok), '(?,?,?,?)')),
                $ertekek
            );
        } catch (\Exception $e) {
            // a jelölés elmaradása nem ok arra, hogy a bizonylatmentés elszálljon:
            // a teljes újraszámolás úgyis rendbe teszi
            @file_put_contents(
                \mkw\store::logsPath('fifo.log'),
                date('Y-m-d H:i:s') . ' fifovaltozas: ' . $e->getMessage() . "\n",
                FILE_APPEND
            );
        }
    }

}
