<?php

namespace Entities;

class MunkalapstatuszRepository extends \mkwhelpers\Repository
{

    public function __construct($em, \Doctrine\ORM\Mapping\ClassMetadata $class)
    {
        parent::__construct($em, $class);
        $this->setEntityname(Munkalapstatusz::class);
        $this->setOrders([
            '1' => ['caption' => 'sorrend szerint növekvő', 'order' => ['_xx.sorrend' => 'ASC', '_xx.nev' => 'ASC']],
            '2' => ['caption' => 'név szerint növekvő', 'order' => ['_xx.nev' => 'ASC']],
            '3' => ['caption' => 'kód szerint növekvő', 'order' => ['_xx.kod' => 'ASC']],
        ]);
    }

    public function getWithJoins($filter, $order, $offset = 0, $elemcount = 0): mixed
    {
        $q = $this->_em->createQuery(
            'SELECT _xx,et'
            . ' FROM Entities\Munkalapstatusz _xx'
            . ' LEFT JOIN _xx.emailtemplate et'
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
