<?php

namespace Entities;

use Doctrine\ORM\Query\ResultSetMapping;

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
        $q = $this->_em->createQuery('SELECT _xx,et'
            . ' FROM Entities\Bizonylatstatusz _xx'
            . ' LEFT JOIN _xx.emailtemplate et'
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
            . ' FROM Entities\Bizonylatstatusz _xx'
            . ' LEFT JOIN _xx.emailtemplate et'
            . $this->getFilterString($filter));
        $q->setParameters($this->getQueryParameters($filter));
        return $q->getSingleScalarResult();
    }

    public function getExistingCsoportok() {
        $rsm = new ResultSetMapping();
        $rsm->addScalarResult('csoport', 'csoport');
        $q = $this->_em->createNativeQuery('SELECT DISTINCT csoport FROM bizonylatstatusz ORDER BY csoport', $rsm);
        return $q->getScalarResult();
    }

}
