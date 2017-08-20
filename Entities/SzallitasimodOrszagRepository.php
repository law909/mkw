<?php

namespace Entities;

use mkwhelpers\FilterDescriptor;

class SzallitasimodOrszagRepository extends \mkwhelpers\Repository {

    public function __construct($em, \Doctrine\ORM\Mapping\ClassMetadata $class) {
        parent::__construct($em, $class);
        $this->setEntityname('Entities\SzallitasimodOrszag');
        $this->setOrders(array(
            '1' => array('caption' => 'név szerint', 'order' => array('_xx.nev' => 'ASC'))
        ));
    }

    public function getBySzallitasimod($szallmod) {
        $filter = new FilterDescriptor();
        $filter->addFilter('szallitasimod', '=', $szallmod);

        return $this->getAll($filter, array());
    }

    public function getBySzallitasimodOrszagValutanemHatar($szallmod, $orszag, $valutanem, $hatar) {
        $filter = new FilterDescriptor();
        $filter
            ->addFilter('szallitasimod', '=', $szallmod)
            ->addFilter('orszag', '=', $orszag)
            ->addFilter('valutanem', '=', $valutanem)
            ->addFilter('hatarertek', '<=', $hatar);

        $t = $this->getAll($filter, array('hatarertek' => 'DESC'));
        if ($t) {
            return $t[0];
        }
        return false;
    }

}
