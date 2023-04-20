<?php

namespace Entities;

use Doctrine\ORM\Query\ResultSetMapping;

class MPTNGYSzakmaianyagRepository extends \mkwhelpers\Repository
{

    public function __construct($em, \Doctrine\ORM\Mapping\ClassMetadata $class)
    {
        parent::__construct($em, $class);
        $this->setEntityname(MPTNGYSzakmaianyag::class);
        $this->setOrders([
            '1' => ['caption' => 'azonosító szerint növekvő', 'order' => ['id' => 'ASC']],
            '2' => ['caption' => 'kezdés szerint növekvő', 'order' => ['kezdodatum' => 'ASC', 'kezdoido' => 'ASC']],
            '3' => ['caption' => 'cím szerint növekvő', 'order' => ['cim' => 'ASC']]
        ]);

        $btch = [];
        $this->setBatches($btch);
    }

    public function getWithJoins($filter, $order, $offset = 0, $elemcount = 0)
    {
        $a = $this->alias;
        $q = $this->_em->createQuery(
            'SELECT ' . $a
            . ' FROM ' . $this->entityname . ' ' . $a
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
        $a = $this->alias;
        $q = $this->_em->createQuery(
            'SELECT COUNT(' . $a . ') FROM ' . $this->entityname . ' ' . $a . ' '
            . $this->getFilterString($filter)
        );
        $q->setParameters($this->getQueryParameters($filter));
        return $q->getSingleScalarResult();
    }

}
