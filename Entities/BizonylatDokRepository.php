<?php

namespace Entities;

use Doctrine\ORM\Query\ResultSetMapping;
use mkwhelpers\FilterDescriptor;

class BizonylatDokRepository extends \mkwhelpers\Repository {

    public function __construct($em, \Doctrine\ORM\Mapping\ClassMetadata $class) {
        parent::__construct($em, $class);
        $this->setEntityname(BizonylatDok::class);
    }

    public function getByBizonylat($r) {
        $filter = new FilterDescriptor();
        if ($r) {
            $filter->addFilter('bizonylat', '=', $r);
            return $this->getAll($filter, array());
        }
        return array();
    }

}
