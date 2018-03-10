<?php

namespace Entities;

use Doctrine\ORM\Query\ResultSetMapping;
use mkwhelpers\FilterDescriptor;

class RendezvenyDokRepository extends \mkwhelpers\Repository {

    public function __construct($em, \Doctrine\ORM\Mapping\ClassMetadata $class) {
        parent::__construct($em, $class);
        $this->setEntityname('Entities\RendezvenyDok');
    }

    public function getByRendezveny($r) {
        $filter = new FilterDescriptor();
        if ($r) {
            $filter->addFilter('rendezveny', '=', $r);
            return $this->getAll($filter, array());
        }
        return array();
    }

}
