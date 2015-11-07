<?php
namespace Entities;

class JelenletiivRepository extends \mkwhelpers\Repository {

    public function __construct($em, \Doctrine\ORM\Mapping\ClassMetadata $class) {
        parent::__construct($em, $class);
        $this->setEntityname('Entities\Jelenletiiv');
        $this->setOrders(array(
            '1' => array('caption' => 'dátum és dolgozó szerint növekvő', 'order' => array('_xx.datum' => 'ASC', 'd.nev' => 'ASC')),
            '2' => array('caption' => 'dolgozó és dátum szerint növekvő', 'order' => array('d.nev' => 'ASC', '_xx.datum' => 'ASC'))
        ));
    }

    public function getWithJoins($filter, $order, $offset = 0, $elemcount = 0) {
        $q = $this->_em->createQuery('SELECT _xx,d,j '
            . ' FROM Entities\Jelenletiiv _xx'
            . ' LEFT JOIN _xx.dolgozo d'
            . ' LEFT JOIN _xx.jelenlettipus j'
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
            . ' FROM Entities\Jelenletiiv _xx'
            . ' LEFT JOIN _xx.dolgozo d'
            . ' LEFT JOIN _xx.jelenlettipus j'
            . $this->getFilterString($filter));
        $q->setParameters($this->getQueryParameters($filter));
        return $q->getSingleScalarResult();
    }
}