<?php

namespace Entities;

class LeltartetelRepository extends \mkwhelpers\Repository {

    public function __construct($em, \Doctrine\ORM\Mapping\ClassMetadata $class) {
        parent::__construct($em, $class);
        $this->setEntityname('Entities\Leltartetel');
        $this->setOrders(array(
            '1' => array('caption' => 'biz.szám szerint', 'order' => array('_xx.id' => 'ASC'))
        ));
    }

    public function getWithJoins($filter, $order, $offset = 0, $elemcount = 0) {
        $q = $this->_em->createQuery('SELECT _xx,t,tv'
            . ' FROM Entities\Leltartetel _xx'
            . ' LEFT JOIN _xx.termek t'
            . ' LEFT JOIN _xx.termekvaltozat tv'
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
            . ' FROM Entities\Leltartetel _xx'
            . $this->getFilterString($filter));
        return $q->getSingleScalarResult();
    }

}