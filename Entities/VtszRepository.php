<?php
namespace Entities;

class VtszRepository extends \mkwhelpers\Repository {

    public function __construct($em, \Doctrine\ORM\Mapping\ClassMetadata $class) {
        parent::__construct($em, $class);
        $this->entityname = 'Entities\Vtsz';
        $this->setOrders([
            '1' => ['caption' => 'szám szerint növekvő', 'order' => ['_xx.szam' => 'ASC']],
            '2' => ['caption' => 'szám szerint csökkenő', 'order' => ['_xx.szam' => 'DESC']],
            '3' => ['caption' => 'név szerint növekvő', 'order' => ['_xx.nev' => 'ASC']],
        ]);
    }

    public function getAll($filter = array(), $order = array(), $offset = 0, $elemcount = 0) {
        $q = $this->_em->createQuery('SELECT _xx, a'
            . ' FROM Entities\Vtsz _xx'
            . ' LEFT JOIN _xx.afa a'
            . $this->getFilterString($filter)
            . $this->getOrderString($order));
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