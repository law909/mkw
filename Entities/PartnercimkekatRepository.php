<?php
namespace Entities;

class PartnercimkekatRepository extends \mkwhelpers\Repository {

    public function __construct($em, \Doctrine\ORM\Mapping\ClassMetadata $class) {
        parent::__construct($em, $class);
        $this->setEntityname('Entities\Partnercimkekat');
    }

    public function getWithJoins($filter, $order, $offset = 0, $elemcount = 0) {
        $q = $this->_em->createQuery('SELECT _xx,c'
            . ' FROM Entities\Partnercimkekat _xx'
            . ' LEFT JOIN _xx.cimkek c '
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
        $q = $this->_em->createQuery('SELECT COUNT(_xx.id)'
            . ' FROM Entities\Partnercimkekat _xx'
            . ' LEFT JOIN _xx.cimkek c '
            . $this->getFilterString($filter));
        $q->setParameters($this->getQueryParameters($filter));
        return $q->getSingleScalarResult();
    }

    public function getAllHasPartner($menu) {
        $filter = 'c.menu' . $menu . 'lathato=1';
        $order = array('_xx.nev' => 'asc', 'c.nev' => 'asc');
        $q = $this->_em->createQuery('SELECT _xx,c'
            . ' FROM Entities\Partnercimkekat _xx'
            . ' LEFT JOIN _xx.cimkek c '
            . ' INNER JOIN c.partnerek p '
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