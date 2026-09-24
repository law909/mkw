<?php

namespace Entities;

class DolgozoberRepository extends \mkwhelpers\Repository
{

    public function __construct($em, \Doctrine\ORM\Mapping\ClassMetadata $class)
    {
        parent::__construct($em, $class);
        $this->setEntityname(Dolgozober::class);
        $this->setOrders([
            '1' => ['caption' => 'dátum szerint csökkenő', 'order' => ['_xx.datum' => 'DESC', 'd.nev' => 'ASC', '_xx.id' => 'DESC']],
            '2' => ['caption' => 'dátum szerint növekvő', 'order' => ['_xx.datum' => 'ASC', 'd.nev' => 'ASC', '_xx.id' => 'ASC']],
            '3' => ['caption' => 'dolgozó és dátum szerint', 'order' => ['d.nev' => 'ASC', '_xx.datum' => 'ASC', '_xx.id' => 'ASC']],
        ]);
    }

    public function getWithJoins($filter, $order, $offset = 0, $elemcount = 0): mixed
    {
        $q = $this->_em->createQuery(
            'SELECT _xx,d,j'
            . ' FROM Entities\Dolgozober _xx'
            . ' LEFT JOIN _xx.dolgozo d'
            . ' LEFT JOIN _xx.berjogcim j'
            . $this->getFilterString($filter)
            . $this->getOrderString($order)
        );
        $q->setParameters($this->getQueryParameters($filter));
        if ($offset > 0) {
            $q->setFirstResult($offset);
        }
        if ($elemcount > 0) {
            $q->setMaxResults($elemcount);
        }
        return $q->getResult();
    }

    public function getCount($filter)
    {
        $q = $this->_em->createQuery(
            'SELECT COUNT(_xx)'
            . ' FROM Entities\Dolgozober _xx'
            . ' LEFT JOIN _xx.dolgozo d'
            . ' LEFT JOIN _xx.berjogcim j'
            . $this->getFilterString($filter)
        );
        $q->setParameters($this->getQueryParameters($filter));
        return $q->getSingleScalarResult();
    }

    /** The sum of the filtered lines, voided ones never count. */
    public function getOsszeg(\mkwhelpers\FilterDescriptor $filter)
    {
        $filter = clone $filter;
        $filter->addFilter('rontott', '=', false);
        $q = $this->_em->createQuery(
            'SELECT SUM(_xx.osszeg)'
            . ' FROM Entities\Dolgozober _xx'
            . ' LEFT JOIN _xx.dolgozo d'
            . ' LEFT JOIN _xx.berjogcim j'
            . $this->getFilterString($filter)
        );
        $q->setParameters($this->getQueryParameters($filter));
        return (float)$q->getSingleScalarResult();
    }
}
