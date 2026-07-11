<?php

namespace Entities;

use Doctrine\ORM\Query\ResultSetMapping;
use mkwhelpers\FilterDescriptor;

class LeltarfejRepository extends \mkwhelpers\Repository
{

    public function __construct($em, \Doctrine\ORM\Mapping\ClassMetadata $class)
    {
        parent::__construct($em, $class);
        $this->setEntityname(Leltarfej::class);
        $this->setOrders([
            '1' => ['caption' => 'nyitás szerint csökkenő', 'order' => ['_xx.nyitas' => 'DESC', '_xx.id' => 'DESC']],
            '2' => ['caption' => 'nyitás szerint növekvő', 'order' => ['_xx.nyitas' => 'ASC', '_xx.id' => 'DESC']],
            '3' => ['caption' => 'zárás szerint csökkenő', 'order' => ['_xx.zaras' => 'DESC', '_xx.id' => 'DESC']],
            '4' => ['caption' => 'zárás szerint növekvő', 'order' => ['_xx.zaras' => 'ASC', '_xx.id' => 'DESC']]
        ]);
    }

    public function getWithJoins($filter, $order = [], $offset = 0, $elemcount = 0): mixed
    {
        $q = $this->_em->createQuery(
            'SELECT _xx'
            . ' FROM Entities\Leltarfej _xx'
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

    public function getWithTetelek($filter, $order = [], $offset = 0, $elemcount = 0, $locale = false)
    {
        $q = $this->_em->createQuery(
            'SELECT _xx, lt'
            . ' FROM Entities\Leltarfej _xx'
            . ' LEFT JOIN _xx.leltartetelek lt'
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
            . ' FROM Entities\Leltarfej _xx'
            . $this->getFilterString($filter)
        );
        $q->setParameters($this->getQueryParameters($filter));
        return $q->getSingleScalarResult();
    }

}