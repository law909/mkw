<?php

namespace Listeners;

use Doctrine\ORM\Event\OnFlushEventArgs;
use Entities\Bizonylatfej;
use Entities\Unasoutbox;
use Services\UnasSetOrderService;

/**
 * Az UNAS-ba visszaírandó változások felvétele a kimenő sorba. Csak SORT ír – a `setOrder`
 * hívás a cronból megy (lásd `Services\UnasSetOrderService`), mert egy HTTP hívás a flush
 * közepén blokkol, hibázhat és bezárja az EntityManagert.
 *
 * Amit figyel: `unaskey`-es bizonylat státuszváltása és csomagszám-változása, illetve a belőle
 * képzett számla beszúrása. A csomagszámot a GLS / Fedex / Foxpost service-ek és a kézi rögzítés
 * is a `fuvarlevelszam` mezőn keresztül állítja, tehát mindegyiket elkapja.
 */
class UnasOutboxListener
{

    private $em;
    private $uow;
    private $unasoutboxmd;

    /** kötegelt importnál ugyanarra a kulcsra és típusra egy sor elég */
    private $mostSorbaTett = [];

    public function onFlush(OnFlushEventArgs $args)
    {
        if (!\mkw\store::isUnas()) {
            return;
        }
        $this->em = $args->getObjectManager();
        $this->uow = $this->em->getUnitOfWork();
        $this->unasoutboxmd = $this->em->getClassMetadata(Unasoutbox::class);
        $this->mostSorbaTett = [];

        foreach ($this->uow->getScheduledEntityInsertions() as $entity) {
            if ($entity instanceof Bizonylatfej) {
                $this->checkSzamla($entity);
            }
        }
        foreach ($this->uow->getScheduledEntityUpdates() as $entity) {
            if ($entity instanceof Bizonylatfej) {
                $this->checkValtozasok($entity);
            }
        }
    }

    /**
     * A beszúrt számla a MEGRENDELÉSRE generál visszaírást – az UNAS a rendelést ismeri.
     * A láncot végigjárjuk: a megrendelés → szállítólevél → számla útnál a számla szülője a
     * szállítólevél, aminek nincs `unaskey`-e.
     *
     * @param Bizonylatfej $entity
     */
    private function checkSzamla($entity)
    {
        if (!in_array($entity->getBizonylattipusId(), UnasSetOrderService::SZAMLATIPUSOK, true)) {
            return;
        }
        if ($entity->getStorno() || $entity->getRontott()) {
            return;
        }
        $megrendeles = UnasSetOrderService::findUnasFej($entity);
        if ($megrendeles) {
            $this->enqueue($megrendeles, Unasoutbox::TIPUSSZAMLA);
        }
    }

    /**
     * @param Bizonylatfej $entity
     */
    private function checkValtozasok($entity)
    {
        $changeset = $this->uow->getEntityChangeSet($entity);
        if (isset($changeset['bizonylatstatusz'])) {
            [$regi, $uj] = $changeset['bizonylatstatusz'];
            if ($regi !== $uj) {
                $this->enqueue($entity, Unasoutbox::TIPUSSTATUSZ);
            }
        }
        if (isset($changeset['fuvarlevelszam'])) {
            [$regi, $uj] = $changeset['fuvarlevelszam'];
            if (trim((string)$regi) !== trim((string)$uj) && trim((string)$uj) !== '') {
                $this->enqueue($entity, Unasoutbox::TIPUSCSOMAG);
            }
        }
    }

    /**
     * @param Bizonylatfej $fej
     */
    private function enqueue($fej, $tipus)
    {
        $unaskey = trim((string)$fej->getUnaskey());
        if ($unaskey === '') {
            return;
        }
        // az importból érkező változás nem írhat vissza: azt épp az UNAS-tól kaptuk
        if (!empty($fej->unasSkipWriteback)) {
            return;
        }
        if (!UnasSetOrderService::isEnabled($tipus)) {
            return;
        }
        $jel = $unaskey . '|' . $tipus;
        if (isset($this->mostSorbaTett[$jel])) {
            return;
        }
        /** @var \Entities\UnasoutboxRepository $repo */
        $repo = $this->em->getRepository(Unasoutbox::class);
        // a függő sor a bizonylat AKKORI állapotát küldi majd, tehát egy elég belőle
        if ($repo->findPending($unaskey, $tipus)) {
            return;
        }
        $this->mostSorbaTett[$jel] = true;

        $sor = UnasSetOrderService::build($fej, $tipus);
        $this->em->persist($sor);
        $this->uow->computeChangeSet($this->unasoutboxmd, $sor);
    }

}
