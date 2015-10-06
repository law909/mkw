<?php

namespace Entities;

class SzallitasimodHatarRepository extends \mkwhelpers\Repository {

    public function __construct($em, \Doctrine\ORM\Mapping\ClassMetadata $class) {
        parent::__construct($em, $class);
        $this->setEntityname('Entities\SzallitasimodHatar');
        $this->setOrders(array(
            '1' => array('caption' => 'név szerint', 'order' => array('_xx.nev' => 'ASC'))
        ));
    }

    public function getBySzallitasimod($szallmod) {
        $filter = array();
        $filter['fields'][] = 'szallitasimod';
        $filter['clauses'][] = '=';
        $filter['values'][] = $szallmod;
        return $this->getAll($filter, array());
    }

    public function getBySzallitasimodValutanemHatar($szallmod, $valutanem, $hatar) {
        $filter = array();
        $filter['fields'][] = 'szallitasimod';
        $filter['clauses'][] = '=';
        $filter['values'][] = $szallmod;
        $filter['fields'][] = 'valutanem';
        $filter['clauses'][] = '=';
        $filter['values'][] = $valutanem;
        $filter['fields'][] = 'hatarertek';
        $filter['clauses'][] = '<=';
        $filter['values'][] =  $hatar;
        $t = $this->getAll($filter, array('hatarertek' => 'DESC'));
        if ($t) {
            return $t[0];
        }
        return false;
    }

}
