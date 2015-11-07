<?php
namespace Entities;

class FelhasznaloRepository extends \mkwhelpers\Repository {

    public function __construct($em, \Doctrine\ORM\Mapping\ClassMetadata $class) {
        parent::__construct($em, $class);
        $this->setEntityname('Entities\Felhasznalo');
        $this->setOrders(array(
            '1' => array('caption' => 'név szerint növekvő', 'order' => array('_xx.nev' => 'ASC')),
            '2' => array('caption' => 'felhasználónév szerint növekvő', 'order' => array('_xx.felhasznalonev' => 'ASC'))
        ));
    }

    public function getWithJoins($filter, $order, $offset = 0, $elemcount = 0) {
        $q = $this->_em->createQuery('SELECT _xx,uk'
            . ' FROM Entities\Felhasznalo _xx'
            . ' LEFT JOIN _xx.uzletkoto uk'
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
            . ' FROM Entities\Felhasznalo _xx'
            . ' LEFT JOIN _xx.uzletkoto uk'
            . $this->getFilterString($filter));
        $q->setParameters($this->getQueryParameters($filter));
        return $q->getSingleScalarResult();
    }
}