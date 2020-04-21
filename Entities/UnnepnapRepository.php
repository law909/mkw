<?php

namespace Entities;

use mkwhelpers\FilterDescriptor;

class UnnepnapRepository extends \mkwhelpers\Repository {

    public function __construct($em, \Doctrine\ORM\Mapping\ClassMetadata $class) {
        parent::__construct($em, $class);
        $this->setEntityname('Entities\Unnepnap');
    }

    public function countUnnepnap($tol, $ig) {
        $filter = new FilterDescriptor();
        $filter->addFilter('datum', '>=', $tol);
        $filter->addFilter('datum', '<=', $ig);
        return $this->getCount($filter);
    }
}
