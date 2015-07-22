<?php

namespace Entities;

class FizmodRepository extends \mkwhelpers\Repository {

    public function __construct($em, \Doctrine\ORM\Mapping\ClassMetadata $class) {
        parent::__construct($em, $class);
        $this->setEntityname('Entities\Fizmod');
    }

    public function getAllWebesBySzallitasimod($szmid, $exc = array()) {
        if (!is_null($szmid)) {
            $szm = $this->_em->getRepository('Entities\Szallitasimod')->find($szmid);
        }
        $filter = array();
        $filter['fields'][] = 'webes';
        $filter['clauses'][] = '=';
        $filter['values'][] = true;
        if ($szm) {
            $filter['fields'][] = 'id';
            $filter['clauses'][] = 'IN';
            $filter['values'][] = explode(',', $szm->getFizmodok());
        }
        if ($exc) {
            $filter['fields'][] = 'id';
            $filter['clauses'][] = 'NOT IN';
            $filter['values'][] = $exc;
        }
        return $this->getAll($filter, array('sorrend' => 'ASC', 'nev' => 'ASC'));
    }

}
