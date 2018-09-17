<?php

namespace Entities;

use Doctrine\ORM\Query\ResultSetMapping;
use mkwhelpers\FilterDescriptor;

class LeltarfejRepository extends \mkwhelpers\Repository {

    public function __construct($em, \Doctrine\ORM\Mapping\ClassMetadata $class) {
        parent::__construct($em, $class);
        $this->setEntityname('Entities\Leltarfej');
        $this->setOrders(array(
            '1' => array('caption' => 'nyitás szerint csökkenő', 'order' => array('_xx.nyitas' => 'DESC', '_xx.id' => 'DESC')),
            '2' => array('caption' => 'nyitás szerint növekvő', 'order' => array('_xx.nyitas' => 'ASC', '_xx.id' => 'DESC')),
            '3' => array('caption' => 'zárás szerint csökkenő', 'order' => array('_xx.zaras' => 'DESC', '_xx.id' => 'DESC')),
            '4' => array('caption' => 'zárás szerint növekvő', 'order' => array('_xx.zaras' => 'ASC', '_xx.id' => 'DESC'))
        ));
    }

    public function getWithJoins($filter, $order = array(), $offset = 0, $elemcount = 0) {
        $q = $this->_em->createQuery('SELECT _xx'
            . ' FROM Entities\Leltarfej _xx'
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
        $q = $this->_em->createQuery('SELECT _xx, lt'
            . ' FROM Entities\Leltarfej _xx'
            . ' LEFT JOIN _xx.leltartetelek lt'
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

    public function getCount($filter) {
        $q = $this->_em->createQuery('SELECT COUNT(_xx)'
            . ' FROM Entities\Leltarfej _xx'
            . $this->getFilterString($filter));
        $q->setParameters($this->getQueryParameters($filter));
        return $q->getSingleScalarResult();
    }

}