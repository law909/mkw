<?php

namespace Entities;

use Doctrine\ORM\EntityRepository;

class BankbizonylattetelRepository extends \mkwhelpers\Repository {

    public function __construct($em, \Doctrine\ORM\Mapping\ClassMetadata $class) {
        parent::__construct($em, $class);
        $this->setEntityname('Entities\Bankbizonylattetel');
    }

    public function calcSumByValutanem($filter = array(), $order = array()) {
        $a = $this->alias;
        return $this->_em->createQuery('SELECT v.nev, SUM(' . $a . '.brutto) AS osszeg'
            . ' FROM ' . $this->getEntityname() . ' ' . $this->alias
            . ' LEFT JOIN ' . $this->alias . '.valutanem v'
            . $this->getFilterString($filter)
            . ' GROUP BY v.nev'
            . $this->getOrderString($order))
            ->setParameters($this->getQueryParameters($filter))
            ->getResult();
    }
}
