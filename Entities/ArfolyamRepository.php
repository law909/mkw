<?php

namespace Entities;

use mkwhelpers\FilterDescriptor;

class ArfolyamRepository extends \mkwhelpers\Repository {

    public function __construct($em, \Doctrine\ORM\Mapping\ClassMetadata $class) {
        parent::__construct($em, $class);
        $this->setEntityname('Entities\Arfolyam');
    }

    public function getAll($filter = array(), $order = array(), $offset = 0, $elemcount = 0) {
        return $this->_em->createQuery(
            'SELECT _xx,v '
            . ' FROM Entities\Arfolyam _xx'
            . ' LEFT JOIN _xx.valutanem v'
            . $this->getFilterString($filter)
            . $this->getOrderString($order))
            ->setParameters($this->getQueryParameters($filter))
            ->getResult();
    }

    public function getActualArfolyam($valuta, $datum) {
        $filter = new FilterDescriptor();
        $filter
            ->addFilter('valutanem', '=', $valuta)
            ->addFilter('datum', '<=', $datum);

        $arf = $this->_em->createQuery('SELECT _xx '
                . 'FROM Entities\Arfolyam _xx'
                . $this->getFilterString($filter)
                . $this->getOrderString(array('datum' => 'DESC')))
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

    public function getArfolyam($valuta, $datum) {
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
