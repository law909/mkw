<?php

namespace Entities;

use Doctrine\ORM\Query\ResultSetMapping;
use mkwhelpers\FilterDescriptor;

class OrarendhelyettesitesRepository extends \mkwhelpers\Repository {

    public function __construct($em, \Doctrine\ORM\Mapping\ClassMetadata $class) {
        parent::__construct($em, $class);
        $this->setEntityname('Entities\Orarendhelyettesites');
        $this->setOrders(array(
            '1' => array('caption' => 'dátum szerint', 'order' => array('datum' => 'DESC'))
        ));
    }

    public function getWithJoins($filter, $order = array(), $offset = 0, $elemcount = 0) {
        $q = $this->_em->createQuery('SELECT _xx,helyettesito,orarend,dolgozo,jogaterem,jogaoratipus'
            . ' FROM Entities\Orarendhelyettesites _xx'
            . ' LEFT JOIN _xx.helyettesito helyettesito'
            . ' LEFT JOIN _xx.orarend orarend'
            . ' LEFT JOIN orarend.dolgozo dolgozo'
            . ' LEFT JOIN orarend.jogaterem jogaterem'
            . ' LEFT JOIN orarend.jogaoratipus jogaoratipus'
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
            . ' FROM Entities\Orarendhelyettesites _xx'
            . ' LEFT JOIN _xx.helyettesito helyettesito'
            . ' LEFT JOIN _xx.orarend orarend'
            . ' LEFT JOIN orarend.dolgozo dolgozo'
            . ' LEFT JOIN orarend.jogaterem jogaterem'
            . ' LEFT JOIN orarend.jogaoratipus jogaoratipus'
            . $this->getFilterString($filter));
        $q->setParameters($this->getQueryParameters($filter));
        return $q->getSingleScalarResult();
    }

    public function getHelyettesito($orarend, $datum) {
        /** @var Orarendhelyettesites $obj */
        $obj = $this->findOneBy(['orarend' => $orarend, 'datum' => $datum]);
        if ($obj && $obj->getHelyettesito()) {
            return $obj->getHelyettesito();
        }
        return null;
    }

}