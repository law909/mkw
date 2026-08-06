<?php

namespace Entities;

class BankszamlaRepository extends \mkwhelpers\Repository
{

    public function __construct($em, \Doctrine\ORM\Mapping\ClassMetadata $class)
    {
        parent::__construct($em, $class);
        $this->setEntityname(Bankszamla::class);
        $this->setOrders([
            '1' => ['caption' => 'bank neve szerint növekvő', 'order' => ['_xx.banknev' => 'ASC']],
            '2' => ['caption' => 'számlaszám szerint növekvő', 'order' => ['_xx.szamlaszam' => 'ASC']],
        ]);
    }

    public function getAll($filter = [], $order = [], $offset = 0, $elemcount = 0)
    {
        $q = $this->_em->createQuery(
            'SELECT _xx, v '
            . ' FROM Entities\Bankszamla _xx'
            . ' LEFT JOIN _xx.valutanem v'
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

    public function getByValutanem($valutanem)
    {
        $filter = new \mkwhelpers\FilterDescriptor();
        $filter->addFilter('valutanem', '=', $valutanem);
        $r = $this->getAll($filter);
        if (count($r) > 0) {
            return $r[0];
        }
        return null;
    }

}