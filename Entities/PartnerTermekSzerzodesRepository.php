<?php

namespace Entities;

use Doctrine\ORM\Query\ResultSetMapping;
use mkwhelpers\FilterDescriptor;

class PartnerTermekSzerzodesRepository extends \mkwhelpers\Repository {

    public function __construct($em, \Doctrine\ORM\Mapping\ClassMetadata $class) {
        parent::__construct($em, $class);
        $this->setEntityname('Entities\PartnerTermekSzerzodes');
    }

    public function getByPartnerTermek($partner, $termek) {
        $filter = new FilterDescriptor();
        $filter
            ->addFilter('partner', '=', $partner)
            ->addFilter('termek', '=', $termek);

        $kdv = $this->getAll($filter, array());
        if ($kdv) {
            $kdv = $kdv[0];
        }
        return $kdv;
    }

    public function getByPartner($partner) {
        $filter = new FilterDescriptor();
        $filter->addFilter('partner', '=', $partner);

        $kdv = $this->getAll($filter, array());
        return $kdv;
    }

}
