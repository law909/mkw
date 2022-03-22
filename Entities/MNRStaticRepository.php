<?php

namespace Entities;

use Doctrine\ORM\Query\ResultSetMapping;
use mkwhelpers\FilterDescriptor;

class MNRStaticRepository extends \mkwhelpers\Repository {

    public function __construct($em, \Doctrine\ORM\Mapping\ClassMetadata $class) {
        parent::__construct($em, $class);
        $this->setEntityname('Entities\MNRStatic');
        $this->setOrders(array(
            '1' => array('caption' => 'név szerint növekvő', 'order' => array('_xx.nev' => 'ASC'))
        ));
    }

    public function getWithJoins($filter, $order = array(), $offset = 0, $elemcount = 0) {
        $q = $this->_em->createQuery('SELECT _xx'
            . ' FROM Entities\MNRStatic _xx'
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

    public function getWithTetelek($filter, $order = array(), $offset = 0, $elemcount = 0, $locale = false) {
        $q = $this->_em->createQuery('SELECT _xx, bt'
            . ' FROM Entities\MNRStatic _xx'
            . ' LEFT JOIN _xx.mnrstaticpages bt'
            . $this->getFilterString($filter)
            . $this->getOrderString($order));
        $q->setParameters($this->getQueryParameters($filter));
        if ($offset > 0) {
            $q->setFirstResult($offset);
        }
        if ($elemcount > 0) {
            $q->setMaxResults($elemcount);
        }
        if ($locale) {
            \mkw\store::setTranslationHint($q, $locale);
        }
        return $q->getResult();
    }

}