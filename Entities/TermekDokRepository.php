<?php

namespace Entities;

use Doctrine\ORM\Query\ResultSetMapping;
use mkwhelpers\FilterDescriptor;

class TermekDokRepository extends \mkwhelpers\Repository {

    public function __construct($em, \Doctrine\ORM\Mapping\ClassMetadata $class) {
        parent::__construct($em, $class);
        $this->setEntityname('Entities\TermekDok');
    }

    public function getByTermek($r) {
        $filter = new FilterDescriptor();
        if ($r) {
            $filter->addFilter('termek', '=', $r);
            return $this->getAll($filter, array());
        }
        return array();
    }

}
