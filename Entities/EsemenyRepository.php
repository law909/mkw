<?php
namespace Entities;

class EsemenyRepository extends \mkwhelpers\Repository {

    public function __construct($em, \Doctrine\ORM\Mapping\ClassMetadata $class) {
        parent::__construct($em, $class);
        $this->setEntityname('Entities\Esemeny');
        $this->setOrders(array(
            '1' => array('caption' => 'esemény szerint növekvő', 'order' => array('_xx.bejegyzes' => 'ASC')),
            '2' => array('caption' => 'partner szerint növekvő', 'order' => array('a.nev' => 'ASC')),
            '3' => array('caption' => 'esedékesség növekvő', 'order' => array('_xx.esedekes' => 'ASC'))
        ));
    }

    public function getWithJoins($filter, $order, $offset = 0, $elemcount = 0) {
        $q = $this->_em->createQuery('SELECT _xx'
            . ' FROM Entities\Esemeny _xx'
            . ' LEFT JOIN _xx.partner a'
            . $this->getFilterString($filter)
            . $this->getOrderString($order));
        if ($offset > 0) {
            $q->setFirstResult($offset);
        }
        if ($elemcount > 0) {
            $q->setMaxResults($elemcount);
        }
        $q->setParameters($this->getQueryParameters($filter));
        return $q->getResult();
    }

    public function getCount($filter) {
        $q = $this->_em->createQuery('SELECT COUNT(_xx)'
            . ' FROM Entities\Esemeny _xx'
            . ' LEFT JOIN _xx.partner a'
            . $this->getFilterString($filter));
        $q->setParameters($this->getQueryParameters($filter));
        return $q->getSingleScalarResult();
    }
}