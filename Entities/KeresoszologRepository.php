<?php

namespace Entities;

class KeresoszologRepository extends \mkwhelpers\Repository
{

    public function __construct($em, \Doctrine\ORM\Mapping\ClassMetadata $class)
    {
        parent::__construct($em, $class);
        $this->setEntityname(Keresoszolog::class);
        $this->setOrders([
            '1' => ['caption' => 'név szerint növekvő', 'order' => ['_xx.nev' => 'ASC']]
        ]);
    }

    /**
     * @param \mkwhelpers\FilterDescriptor $filter
     */
    public function getWithCount($filter)
    {
        $q = $this->_em->createQuery(
            'SELECT _xx.szo, COUNT(_xx) AS db'
            . ' FROM Entities\Keresoszolog _xx '
            . $filter->getFilterString()
            . ' GROUP BY _xx.szo'
            . ' ORDER BY db DESC'
        );
        $q->setParameters($filter->getQueryParameters());
        return $q->getScalarResult();
    }

}