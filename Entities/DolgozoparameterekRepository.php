<?php

namespace Entities;

class DolgozoparameterekRepository extends \mkwhelpers\Repository
{

    public function __construct($em, \Doctrine\ORM\Mapping\ClassMetadata $class)
    {
        parent::__construct($em, $class);
        $this->setEntityname(Dolgozoparameterek::class);
        $this->setOrders([
            '1' => ['caption' => 'paraméter szerint növekvő', 'order' => ['_xx.par' => 'ASC']]
        ]);
    }

    /**
     * Egy dolgozó összes paramétere kulcs => érték tömbként.
     * A service ezzel tölti fel a kérésen belüli cache-ét, hogy paraméterenként
     * ne menjen külön SELECT.
     */
    public function getAllForDolgozo($dolgozoid): array
    {
        if (!$dolgozoid) {
            return [];
        }
        $q = $this->_em->createQuery(
            'SELECT _xx.par,_xx.ertek FROM ' . $this->entityname . ' _xx'
            . ' WHERE _xx.dolgozo=:dolgozo'
        );
        $q->setParameter('dolgozo', $dolgozoid);
        $result = [];
        foreach ($q->getScalarResult() as $row) {
            $result[$row['par']] = $row['ertek'];
        }
        return $result;
    }

    /**
     * @return \Entities\Dolgozoparameterek|null
     */
    public function getByDolgozoAndPar($dolgozoid, $par)
    {
        if (!$dolgozoid) {
            return null;
        }
        return $this->findOneBy(['dolgozo' => $dolgozoid, 'par' => $par]);
    }

}
