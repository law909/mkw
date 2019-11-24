<?php

namespace Entities;

class RendezvenyRepository extends \mkwhelpers\Repository {

    public function __construct($em, \Doctrine\ORM\Mapping\ClassMetadata $class) {
        parent::__construct($em, $class);
        $this->setEntityname('Entities\Rendezveny');
        $this->setOrders(array(
            '1' => array('caption' => 'kezdő dátum szerint csokkenő', 'order' => array('_xx.kezdodatum' => 'DESC')),
            '2' => array('caption' => 'név szerint növekvő', 'order' => array('_xx.nev' => 'ASC'))
        ));
    }

    public function getWithJoins($filter, $order, $offset = 0, $elemcount = 0) {
        $q = $this->_em->createQuery('SELECT _xx,t,ta,te'
            . ' FROM Entities\Rendezveny _xx'
            . ' LEFT JOIN _xx.termek t'
            . ' LEFT JOIN _xx.tanar ta'
            . ' LEFT JOIN _xx.terem te'
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
            . ' FROM Entities\Rendezveny _xx'
            . $this->getFilterString($filter));
        $q->setParameters($this->getQueryParameters($filter));
        return $q->getSingleScalarResult();
    }

}
