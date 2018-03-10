<?php

namespace Entities;

use Doctrine\ORM\Query\ResultSetMapping;
use mkwhelpers\FilterDescriptor;

class PartnerDokRepository extends \mkwhelpers\Repository {

    public function __construct($em, \Doctrine\ORM\Mapping\ClassMetadata $class) {
        parent::__construct($em, $class);
        $this->setEntityname('Entities\PartnerDok');
    }

    public function getByPartner($r) {
        $filter = new FilterDescriptor();
        if ($r) {
            $filter->addFilter('partner', '=', $r);
            return $this->getAll($filter, array());
        }
        return array();
    }

}
