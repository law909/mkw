<?php

namespace Listeners;

use Doctrine\ORM\Event\OnFlushEventArgs;
use Entities\PartnerTermekkategoriaKedvezmeny;
use Entities\PartnerTermekkategoriaKedvezmenyNaplo;

/**
 * A partner termékkategória kedvezmény minden felvitelét, módosítását és törlését naplózza, bármelyik mentési útról
 * jön (partner karbantartó, tömeges módosítás, b2b fiók). Kimarad, ami SQL-lel írja a táblát: a partner összefűzés
 * és a runonce migráció.
 */
class PartnerTermekkategoriaKedvezmenyListener
{

    public function onFlush(OnFlushEventArgs $args)
    {
        $em = $args->getObjectManager();
        $uow = $em->getUnitOfWork();

        $entries = [];
        foreach ($uow->getScheduledEntityInsertions() as $entity) {
            if ($entity instanceof PartnerTermekkategoriaKedvezmeny) {
                $entries[] = [$entity->getPartner(), $entity->getTermekfa(), null, $entity->getKedvezmeny()];
            }
        }
        foreach ($uow->getScheduledEntityUpdates() as $entity) {
            if (!$entity instanceof PartnerTermekkategoriaKedvezmeny) {
                continue;
            }
            $changes = $uow->getEntityChangeSet($entity);
            $oldTermekfa = array_key_exists('termekfa', $changes) ? $changes['termekfa'][0] : $entity->getTermekfa();
            $oldKedvezmeny = array_key_exists('kedvezmeny', $changes) ? $changes['kedvezmeny'][0] : $entity->getKedvezmeny();
            if ($oldTermekfa !== $entity->getTermekfa()) {
                // másik ágra került: a régiről lekerült, az újra felkerült
                $entries[] = [$entity->getPartner(), $oldTermekfa, $oldKedvezmeny, null];
                $entries[] = [$entity->getPartner(), $entity->getTermekfa(), null, $entity->getKedvezmeny()];
            } elseif (!$this->isSameKedvezmeny($oldKedvezmeny, $entity->getKedvezmeny())) {
                $entries[] = [$entity->getPartner(), $entity->getTermekfa(), $oldKedvezmeny, $entity->getKedvezmeny()];
            }
        }
        foreach ($uow->getScheduledEntityDeletions() as $entity) {
            if ($entity instanceof PartnerTermekkategoriaKedvezmeny) {
                $entries[] = [$entity->getPartner(), $entity->getTermekfa(), $entity->getKedvezmeny(), null];
            }
        }
        if (!$entries) {
            return;
        }

        $naplomd = $em->getClassMetadata(PartnerTermekkategoriaKedvezmenyNaplo::class);
        $now = new \DateTime();
        $dolgozo = \mkw\store::isAdminMode() ? \mkw\store::getLoggedInDolgozo() : null;
        $modositonev = $this->getModositoNev();
        foreach ($entries as [$partner, $termekfa, $regi, $uj]) {
            $naplo = new PartnerTermekkategoriaKedvezmenyNaplo();
            $naplo->setCreated($now);
            $naplo->setPartner($partner);
            $naplo->setTermekfa($termekfa);
            $naplo->setTermekfanev($this->getTermekfaNev($termekfa));
            $naplo->setRegikedvezmeny($regi);
            $naplo->setUjkedvezmeny($uj);
            $naplo->setDolgozo($dolgozo);
            $naplo->setModositonev($modositonev);
            $em->persist($naplo);
            $uow->computeChangeSet($naplomd, $naplo);
        }
    }

    // a mentés minden sort újraküld, és a '30.0000' → 30 is changeset-nek látszik
    private function isSameKedvezmeny($a, $b)
    {
        if ($a === null || $b === null) {
            return $a === $b;
        }
        return abs((float)$a - (float)$b) < 0.00005;
    }

    private function getTermekfaNev($termekfa)
    {
        if (!$termekfa) {
            return '';
        }
        $parent = $termekfa->getParent();
        return ($parent && $parent->getParent() ? $parent->getNev() . ' / ' : '') . $termekfa->getNev();
    }

    private function getModositoNev()
    {
        if (\mkw\store::isAdminMode()) {
            return \mkw\store::getLoggedInDolgozoNev();
        }
        if (\mkw\store::isMainMode()) {
            $uk = \mkw\store::getLoggedInUK();
            if ($uk) {
                return $uk->getNev() . ' (üzletkötő)';
            }
        }
        return '';
    }
}
