<?php

namespace Entities;

use mkwhelpers\FilterDescriptor;

class SzallitasimodFizmodNoveloRepository extends \mkwhelpers\Repository {

    public function __construct($em, \Doctrine\ORM\Mapping\ClassMetadata $class) {
        parent::__construct($em, $class);
        $this->setEntityname('Entities\SzallitasimodFizmodNovelo');
        $this->setOrders(array(
            '1' => array('caption' => 'név szerint', 'order' => array('_xx.nev' => 'ASC'))
        ));
    }

    public function getBySzallitasimod($szallmod) {
        $filter = new FilterDescriptor();
        $filter->addFilter('szallitasimod', '=', $szallmod);

        return $this->getAll($filter, array());
    }

    public function getBySzallitasimodFizmod($szallmod, $fizmod) {
        $filter = new FilterDescriptor();
        $filter
            ->addFilter('szallitasimod', '=', $szallmod)
            ->addFilter('fizmod', '=', $fizmod);

        $t = $this->getAll($filter, array());
        if ($t) {
            return $t[0];
        }
        return false;
    }

}
