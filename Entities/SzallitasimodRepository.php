<?php
namespace Entities;

use mkwhelpers\FilterDescriptor;

class SzallitasimodRepository extends \mkwhelpers\Repository {

    public function __construct($em, \Doctrine\ORM\Mapping\ClassMetadata $class) {
        parent::__construct($em, $class);
        $this->setEntityname('Entities\Szallitasimod');
        $this->setOrders(array(
            '1' => array('caption' => 'név szerint növekvő', 'order' => array('_xx.nev' => 'ASC'))
        ));
    }

    public function getAllWebes() {
        $filter = new FilterDescriptor();
        $filter->addFilter('webes', '=', true);
        return $this->getAll($filter, array('sorrend' => 'ASC', 'nev' => 'ASC'));
    }

}