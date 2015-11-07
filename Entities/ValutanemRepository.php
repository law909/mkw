<?php
namespace Entities;

class ValutanemRepository extends \mkwhelpers\Repository {

    public function __construct($em, \Doctrine\ORM\Mapping\ClassMetadata $class) {
        parent::__construct($em, $class);
        $this->entityname = 'Entities\Valutanem';
    }

    public function getAll($filter = array(), $order = array(), $offset = 0, $elemcount = 0) {
        return $this->_em->createQuery('SELECT _xx, b'
            . ' FROM Entities\Valutanem _xx'
            . ' LEFT JOIN _xx.bankszamla b'
            . $this->getFilterString($filter)
            . $this->getOrderString($order))
            ->setParameters($this->getQueryParameters($filter))
            ->getResult();
    }
}