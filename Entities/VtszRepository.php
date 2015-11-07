<?php
namespace Entities;

class VtszRepository extends \mkwhelpers\Repository {

    public function __construct($em, \Doctrine\ORM\Mapping\ClassMetadata $class) {
        parent::__construct($em, $class);
        $this->entityname = 'Entities\Vtsz';
    }

    public function getAll($filter = array(), $order = array(), $offset = 0, $elemcount = 0) {
        return $this->_em->createQuery('SELECT _xx, a'
            . ' FROM Entities\Vtsz _xx'
            . ' LEFT JOIN _xx.afa a'
            . $this->getFilterString($filter)
            . $this->getOrderString($order))
            ->setParameters($this->getQueryParameters($filter))
            ->getResult();
    }
}