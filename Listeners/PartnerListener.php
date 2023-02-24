<?php

namespace Listeners;

use Doctrine\ORM\Event\OnFlushEventArgs;
use Entities\MPTNGYSzakmaianyag;
use mkwhelpers\FilterDescriptor;

class PartnerListener
{
    private $em;
    private $uow;
    private $mptszakmaianyagmd;

    public function onFlush(OnFlushEventArgs $args)
    {
        $this->em = $args->getObjectManager();
        $this->uow = $this->em->getUnitOfWork();

        $this->mptszakmaianyagmd = $this->em->getClassMetadata(\Entities\MPTNGYSzakmaianyag::class);

        $entities = $this->uow->getScheduledEntityInsertions();
        foreach ($entities as $entity) {
            if ($entity instanceof \Entities\Partner) {
                $email = $entity->getEmail();

                $filter = new FilterDescriptor();
                $filter->addSql(
                    "(_xx.szerzo1email = '$email') OR "
                    . "(_xx.szerzo2email = '$email') OR "
                    . "(_xx.szerzo3email = '$email') OR "
                    . "(_xx.szerzo4email = '$email') OR "
                    . "(_xx.szerzo5email = '$email') OR "
                    . "(_xx.beszelgetopartneremail = '$email')"
                );
                $anyagok = \mkw\store::getEm()->getRepository(\Entities\MPTNGYSzakmaianyag::class)->getAll($filter);
                /** @var MPTNGYSzakmaianyag $anyag */
                foreach ($anyagok as $anyag) {
                    if ($anyag->getBeszelgetopartneremail() === $email) {
                        $anyag->setBeszelgetopartner($entity);
                    }
                    if ($anyag->getSzerzo1email() === $email) {
                        $anyag->setSzerzo1($entity);
                    }
                    if ($anyag->getSzerzo2email() === $email) {
                        $anyag->setSzerzo2($entity);
                    }
                    if ($anyag->getSzerzo3email() === $email) {
                        $anyag->setSzerzo3($entity);
                    }
                    if ($anyag->getSzerzo4email() === $email) {
                        $anyag->setSzerzo4($entity);
                    }
                    if ($anyag->getSzerzo5email() === $email) {
                        $anyag->setSzerzo5($entity);
                    }
                    $this->em->persist($anyag);
                    $this->uow->recomputeSingleEntityChangeSet($this->mptszakmaianyagmd, $anyag);
                }
            }
        }
    }

}
