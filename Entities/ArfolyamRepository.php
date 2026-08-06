<?php

namespace Entities;

use mkwhelpers\FilterDescriptor;

class ArfolyamRepository extends \mkwhelpers\Repository
{

    public function __construct($em, \Doctrine\ORM\Mapping\ClassMetadata $class)
    {
        parent::__construct($em, $class);
        $this->setEntityname(Arfolyam::class);
        $this->setOrders([
            '1' => ['caption' => 'dátum szerint csökkenő', 'order' => ['_xx.datum' => 'DESC']],
            '2' => ['caption' => 'dátum szerint növekvő', 'order' => ['_xx.datum' => 'ASC']],
        ]);
    }

    public function getAll($filter = [], $order = [], $offset = 0, $elemcount = 0)
    {
        $q = $this->_em->createQuery(
            'SELECT _xx,v '
            . ' FROM Entities\Arfolyam _xx'
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

    public function getActualArfolyam($valuta, $datum)
    {
        $filter = new FilterDescriptor();
        $filter
            ->addFilter('valutanem', '=', $valuta)
            ->addFilter('datum', '<=', $datum);

        $arf = $this->_em->createQuery(
            'SELECT _xx '
            . 'FROM Entities\Arfolyam _xx'
            . $this->getFilterString($filter)
            . $this->getOrderString(['datum' => 'DESC'])
        )
            ->setMaxResults(1)
            ->setParameters($this->getQueryParameters($filter))
            ->getResult();
        if ($arf) {
            return $arf[0];
        }
        $arf = new \Entities\Arfolyam();
        $arf->setArfolyam(1);
        return $arf;
    }

    public function getArfolyam($valuta, $datum)
    {
        $filter = new FilterDescriptor();
        $filter
            ->addFilter('valutanem', '=', $valuta)
            ->addFilter('datum', '=', $datum);

        $arf = $this->getAll($filter);
        if ($arf) {
            return $arf[0];
        }
        return false;
    }

}
