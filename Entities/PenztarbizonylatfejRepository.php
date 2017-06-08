<?php

namespace Entities;

use Doctrine\ORM\EntityRepository;

class PenztarbizonylatfejRepository extends \mkwhelpers\Repository {

    public function __construct($em, \Doctrine\ORM\Mapping\ClassMetadata $class) {
        parent::__construct($em, $class);
        $this->setEntityname('Entities\Penztarbizonylatfej');
        $this->setOrders(array(
            '1' => array('caption' => 'biz.szám szerint csökkenő', 'order' => array('_xx.id' => 'DESC')),
            '2' => array('caption' => 'biz.szám szerint növekvő', 'order' => array('_xx.id' => 'ASC')),
            '3' => array('caption' => 'kelt szerint csökkenő', 'order' => array('_xx.kelt' => 'DESC','_xx.id' => 'DESC')),
            '4' => array('caption' => 'kelt szerint növekvő', 'order' => array('_xx.kelt' => 'DESC','_xx.id' => 'DESC')),
            '5' => array('caption' => 'er.biz.szám szerint csökkenő', 'order' => array('_xx.erbizonylatszam' => 'DESC')),
            '6' => array('caption' => 'er.biz.szám szerint növekvő', 'order' => array('_xx.erbizonylatszam' => 'ASC'))
        ));
    }

    public function findWithJoins($id) {
        return parent::findWithJoins((string)$id);
    }

    public function getWithJoins($filter, $order, $offset = 0, $elemcount = 0) {
        $q = $this->_em->createQuery('SELECT _xx'
            . ' FROM Entities\Penztarbizonylatfej _xx'
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

    public function getCount($filter) {
        $q = $this->_em->createQuery('SELECT COUNT(_xx)'
            . ' FROM Entities\Penztarbizonylatfej _xx'
            . $this->getFilterString($filter));
        $q->setParameters($this->getQueryParameters($filter));
        return $q->getSingleScalarResult();
    }

    public function getSum($filter) {
        $q = $this->_em->createQuery('SELECT SUM(_xx.irany * _xx.brutto)'
            . ' FROM Entities\Penztarbizonylatfej _xx'
            . $this->getFilterString($filter));
        $q->setParameters($this->getQueryParameters($filter));
        return $q->getSingleScalarResult();
    }

}
