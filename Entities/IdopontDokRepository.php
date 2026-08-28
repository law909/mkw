<?php

namespace Entities;

use mkwhelpers\FilterDescriptor;

class IdopontDokRepository extends \mkwhelpers\Repository {

    public function __construct($em, \Doctrine\ORM\Mapping\ClassMetadata $class) {
        parent::__construct($em, $class);
        $this->setEntityname('Entities\IdopontDok');
    }

    public function getByIdopont($r) {
        $filter = new FilterDescriptor();
        if ($r) {
            $filter->addFilter('idopont', '=', $r);
            return $this->getAll($filter, array());
        }
        return array();
    }

}
