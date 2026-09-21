<?php

namespace Entities;

class EppjelszoRepository extends \mkwhelpers\Repository
{

    public function __construct($em, \Doctrine\ORM\Mapping\ClassMetadata $class)
    {
        parent::__construct($em, $class);
        $this->setEntityname(Eppjelszo::class);
        $this->setOrders([
            '1' => ['caption' => 'létrehozás szerint csökkenő', 'order' => ['_xx.id' => 'DESC']],
            '2' => ['caption' => 'oldal szerint', 'order' => ['_xx.oldalid' => 'ASC', '_xx.lejarat' => 'DESC']],
            '3' => ['caption' => 'lejárat szerint növekvő', 'order' => ['_xx.lejarat' => 'ASC']],
            '4' => ['caption' => 'lejárat szerint csökkenő', 'order' => ['_xx.lejarat' => 'DESC']],
        ]);
    }

    /**
     * Az oldal nem visszavont, le nem járt jelszava ezzel a hash-sel.
     */
    public function findAktivByHash(int $oldalid, string $jelszohash, \DateTimeInterface $most): ?Eppjelszo
    {
        return $this->_em->createQuery(
            'SELECT _xx FROM Entities\Eppjelszo _xx'
            . ' WHERE _xx.oldalid = :oldalid AND _xx.jelszohash = :hash'
            . ' AND _xx.visszavonvaon IS NULL AND _xx.lejarat > :most'
        )
            ->setParameters(['oldalid' => $oldalid, 'hash' => $jelszohash, 'most' => $most])
            ->setMaxResults(1)
            ->getOneOrNullResult();
    }

    public function findOneByAzonosito(string $azonosito): ?Eppjelszo
    {
        return $this->findOneBy(['azonosito' => $azonosito]);
    }

}
