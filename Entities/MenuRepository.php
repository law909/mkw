<?php
namespace Entities;

class MenuRepository extends \mkwhelpers\Repository {

    public function __construct($em, \Doctrine\ORM\Mapping\ClassMetadata $class) {
        parent::__construct($em, $class);
        $this->entityname = 'Entities\Menu';
    }

    public function getAll($filter = array(), $order = array(), $offset = 0, $elemcount = 0) {
        return $this->_em->createQuery('SELECT _xx, m'
            . ' FROM Entities\Menu _xx'
            . ' LEFT JOIN _xx.menucsoport m'
            . $this->getFilterString($filter)
            . $this->getOrderString($order))
            ->setParameters($this->getQueryParameters($filter))
            ->getResult();
    }
}