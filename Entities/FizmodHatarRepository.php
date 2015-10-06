<?php
namespace Entities;

class FizmodHatarRepository extends \mkwhelpers\Repository {

    public function __construct($em, \Doctrine\ORM\Mapping\ClassMetadata $class) {
        parent::__construct($em, $class);
        $this->setEntityname('Entities\FizmodHatar');
        $this->setOrders(array(
            '1' => array('caption' => 'név szerint', 'order' => array('_xx.nev' => 'ASC'))
        ));
    }

    public function getByValutanem($valutanem) {
        $filter = array();
        $filter['fields'][] = 'valutanem';
        $filter['clauses'][] = '=';
        $filter['values'][] = $valutanem;
        return $this->getAll($filter, array());
    }

    public function getByFizmod($fizmod) {
        $filter = array();
        $filter['fields'][] = 'fizmod';
        $filter['clauses'][] = '=';
        $filter['values'][] = $fizmod;
        return $this->getAll($filter, array());
    }

    public function getByValutanemHatar($valutanem, $hatar) {
        $filter = array();
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
