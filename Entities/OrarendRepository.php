<?php

namespace Entities;

use Doctrine\ORM\Query\ResultSetMapping;
use mkwhelpers\FilterDescriptor;

class OrarendRepository extends \mkwhelpers\Repository
{

    public function __construct($em, \Doctrine\ORM\Mapping\ClassMetadata $class)
    {
        parent::__construct($em, $class);
        $this->setEntityname('Entities\Orarend');
        $this->setOrders([
            '1' => ['caption' => 'időpont és név szerint', 'order' => ['nap' => 'ASC', 'kezdet' => 'ASC', 'nev' => 'ASC']],
            '2' => ['caption' => 'név szerint', 'order' => ['nev' => 'ASC']]
        ]);
    }

    public function getWithJoins($filter, $order = [], $offset = 0, $elemcount = 0): mixed
    {
        $q = $this->_em->createQuery(
            'SELECT _xx,dolgozo,jogaterem,jogaoratipus'
            . ' FROM Entities\Orarend _xx'
            . ' LEFT JOIN _xx.dolgozo dolgozo'
            . ' LEFT JOIN _xx.jogaterem jogaterem'
            . ' LEFT JOIN _xx.jogaoratipus jogaoratipus'
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
            . ' FROM Entities\Orarend _xx'
            . ' LEFT JOIN _xx.dolgozo dolgozo'
            . ' LEFT JOIN _xx.jogaterem jogaterem'
            . ' LEFT JOIN _xx.jogaoratipus jogaoratipus'
            . $this->getFilterString($filter)
        );
        $q->setParameters($this->getQueryParameters($filter));
        return $q->getSingleScalarResult();
    }

}