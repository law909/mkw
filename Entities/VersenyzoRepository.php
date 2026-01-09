<?php

namespace Entities;

class VersenyzoRepository extends \mkwhelpers\Repository
{

    public function __construct($em, \Doctrine\ORM\Mapping\ClassMetadata $class)
    {
        parent::__construct($em, $class);
        $this->setEntityname(Versenyzo::class);
        $this->setOrders([
            '1' => ['caption' => 'név szerint', 'order' => ['nev' => 'ASC']],
        ]);
    }

    public function getWithJoins($filter, $order, $offset = 0, $elemcount = 0)
    {
        $q = $this->_em->createQuery(
            'SELECT _xx, cs FROM Entities\Versenyzo _xx '
            . ' LEFT JOIN _xx.csapat cs '
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
}
