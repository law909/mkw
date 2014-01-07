<?php

namespace Entities;

use Doctrine\ORM\Query\ResultSetMapping;

class TermekKepRepository extends \mkwhelpers\Repository {

    public function __construct($em, \Doctrine\ORM\Mapping\ClassMetadata $class) {
        parent::__construct($em, $class);
        $this->setEntityname('Entities\TermekKep');
//		$this->setOrders(array());
    }

    public function getByTermek($termek) {
        $filter = array();
        if ($termek) {
            $filter['fields'][] = 'termek';
            $filter['clauses'][] = '=';
            $filter['values'][] = $termek;
            return $this->getAll($filter, array());
        }
        return null;
    }

    public function getByTermekForSitemapXml($termek) {
        $rsm = new ResultSetMapping();
        $rsm->addScalarResult('url', 'url');
        $rsm->addScalarResult('leiras', 'leiras');
        $q = $this->_em->createNativeQuery('SELECT url,leiras'
                . ' FROM termekkep '
                . ' WHERE termek_id = ' . $termek, $rsm);
        return $q->getScalarResult();
    }

}
