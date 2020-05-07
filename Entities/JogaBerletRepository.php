<?php
namespace Entities;

class JogaBerletRepository extends \mkwhelpers\Repository {

	public function __construct($em, \Doctrine\ORM\Mapping\ClassMetadata $class) {
		parent::__construct($em,$class);
		$this->setEntityname(JogaBerlet::class);
        $this->setOrders(array(
            '1' => array('caption' => 'név, lejárat dátum csökkenő', 'order' => array('p.nev' => 'ASC', '_xx.lejaratdatum' => 'DESC'))
        ));
    }

    public function getWithJoins($filter, $order = array(), $offset = 0, $elemcount = 0) {
        $q = $this->_em->createQuery('SELECT _xx,t,p'
            . ' FROM Entities\JogaBerlet _xx'
            . ' LEFT JOIN _xx.termek t'
            . ' LEFT JOIN _xx.partner p'
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

    public function getCountWithJoins($filter) {
        $q = $this->_em->createQuery('SELECT COUNT(_xx)'
            . ' FROM Entities\JogaBerlet _xx'
            . ' LEFT JOIN _xx.termek t'
            . ' LEFT JOIN _xx.partner p'
            . $this->getFilterString($filter));
        $q->setParameters($this->getQueryParameters($filter));
        return $q->getSingleScalarResult();
    }

}