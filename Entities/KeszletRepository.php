<?php
namespace Entities;

use Doctrine\ORM\Query\ResultSetMapping;

class KeszletRepository extends \mkwhelpers\Repository {

    public function __construct($em, \Doctrine\ORM\Mapping\ClassMetadata $class) {
        parent::__construct($em, $class);
        $this->setEntityname('Entities\Keszlet');
        $this->setOrders(array(
            '1' => array('caption' => 'név szerint', 'order' => array('_xx.nev' => 'ASC'))
        ));
    }

    public function getWithJoins($filter, $order, $offset = 0, $elemcount = 0) {
        $q = $this->_em->createQuery('SELECT _xx,bebf,bebt,r,t,tv'
            . ' FROM Entities\Keszlet _xx'
            . ' LEFT JOIN _xx.bebizonylatfej bebf'
            . ' LEFT JOIN _xx.bebizonylattetel bebt'
            . ' LEFT JOIN _xx.raktar r'
            . ' LEFT JOIN _xx.termek t'
            . ' LEFT JOIN _xx.termekvaltozat tv'
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

