<?php

namespace Entities;

use mkwhelpers\FilterDescriptor;

class UnnepnapRepository extends \mkwhelpers\Repository {

    public function __construct($em, \Doctrine\ORM\Mapping\ClassMetadata $class) {
        parent::__construct($em, $class);
        $this->setEntityname('Entities\Unnepnap');
        $this->setOrders([
            '1' => ['caption' => 'dátum szerint növekvő', 'order' => ['_xx.datum' => 'ASC']],
            '2' => ['caption' => 'dátum szerint csökkenő', 'order' => ['_xx.datum' => 'DESC']],
        ]);
    }

    public function countUnnepnap($tol, $ig) {
        $filter = new FilterDescriptor();
        $filter->addFilter('datum', '>=', $tol);
        $filter->addFilter('datum', '<=', $ig);
        return $this->getCount($filter);
    }
}
