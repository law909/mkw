<?php
namespace Entities;

class ValutanemRepository extends \mkwhelpers\Repository {

    public function __construct($em, \Doctrine\ORM\Mapping\ClassMetadata $class) {
        parent::__construct($em, $class);
        $this->entityname = 'Entities\Valutanem';
        $this->setOrders([
            '1' => ['caption' => 'név szerint növekvő', 'order' => ['_xx.nev' => 'ASC']],
            '2' => ['caption' => 'név szerint csökkenő', 'order' => ['_xx.nev' => 'DESC']],
        ]);
    }

    public function getAll($filter = array(), $order = array(), $offset = 0, $elemcount = 0) {
        $q = $this->_em->createQuery('SELECT _xx, b'
            . ' FROM Entities\Valutanem _xx'
            . ' LEFT JOIN _xx.bankszamla b'
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