<?php

namespace Entities;

class PenztarRepository extends \mkwhelpers\Repository {

    public function __construct($em, \Doctrine\ORM\Mapping\ClassMetadata $class) {
        parent::__construct($em, $class);
        $this->setEntityname('Entities\Penztar');
    }

    public function getAll($filter = array(), $order = array(), $offset = 0, $elemcount = 0) {
        return $this->_em->createQuery('SELECT _xx, v '
            . ' FROM Entities\Penztar _xx'
            . ' LEFT JOIN _xx.valutanem v'
            . $this->getFilterString($filter)
            . $this->getOrderString($order))
            ->setParameters($this->getQueryParameters($filter))
            ->getResult();
    }

    public function getByValutanem($valutanem) {
        $filter = new \mkwhelpers\FilterDescriptor();
        $filter->addFilter('valutanem', '=', $valutanem);
        $r = $this->getAll($filter);
        if (count($r) > 0) {
            return $r[0];
        }
        return null;
    }

}