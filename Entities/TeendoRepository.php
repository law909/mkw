<?php

namespace Entities;

class TeendoRepository extends \mkwhelpers\Repository
{

    public function __construct($em, \Doctrine\ORM\Mapping\ClassMetadata $class)
    {
        parent::__construct($em, $class);
        $this->setEntityname('Entities\Teendo');
        $this->setOrders([
            '1' => ['caption' => 'teendő szerint növekvő', 'order' => ['_xx.bejegyzes' => 'ASC']],
            '2' => ['caption' => 'partner szerint növekvő', 'order' => ['a.nev' => 'ASC']],
            '3' => ['caption' => 'esedékesség növekvő', 'order' => ['_xx.esedekes' => 'ASC']],
            '4' => ['caption' => 'állapot növekvő', 'order' => ['_xx.elvegezve' => 'ASC']]
        ]);
    }

    public function getWithJoins($filter, $order, $offset = 0, $elemcount = 0): mixed
    {
        $q = $this->_em->createQuery(
            'SELECT _xx'
            . ' FROM Entities\Teendo _xx'
            . ' LEFT JOIN _xx.partner a'
            . $this->getFilterString($filter)
            . $this->getOrderString($order)
        );
        if ($offset > 0) {
            $q->setFirstResult($offset);
        }
        if ($elemcount > 0) {
            $q->setMaxResults($elemcount);
        }
        $q->setParameters($this->getQueryParameters($filter));
        return $q->getResult();
    }

    public function getCount($filter)
    {
        $q = $this->_em->createQuery(
            'SELECT COUNT(_xx)'
            . ' FROM Entities\Teendo _xx'
            . ' LEFT JOIN _xx.partner a'
            . $this->getFilterString($filter)
        );
        $q->setParameters($this->getQueryParameters($filter));
        return $q->getSingleScalarResult();
    }
}