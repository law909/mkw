<?php

namespace Entities;

class BizonylatstatuszRepository extends \mkwhelpers\Repository {

    public function __construct($em, \Doctrine\ORM\Mapping\ClassMetadata $class) {
        parent::__construct($em, $class);
        $this->setEntityname('Entities\Bizonylatstatusz');
        $this->setOrders(array(
            '1' => array('caption' => 'sorrend szerint növekvő', 'order' => array('_xx.sorrend' => 'ASC', '_xx.nev' => 'ASC')),
            '2' => array('caption' => 'név szerint növekvő', 'order' => array('_xx.nev' => 'ASC', '_xx.sorrend' => 'ASC'))
        ));
    }

    public function getWithJoins($filter, $order, $offset = 0, $elemcount = 0) {
        $a = $this->alias;
        $q = $this->_em->createQuery('SELECT ' . $a . ',et'
                . ' FROM ' . $this->entityname . ' ' . $a
                . ' LEFT JOIN ' . $a . '.emailtemplate et'
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
        $a = $this->alias;
        $q = $this->_em->createQuery('SELECT COUNT(' . $a . ') FROM ' . $this->entityname . ' ' . $a
                . ' LEFT JOIN ' . $a . '.emailtemplate et'
                . $this->getFilterString($filter));
        $q->setParameters($this->getQueryParameters($filter));
        return $q->getSingleScalarResult();
    }

}
