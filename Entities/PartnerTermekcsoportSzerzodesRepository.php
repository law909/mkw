<?php

namespace Entities;

use Doctrine\ORM\Query\ResultSetMapping;
use mkwhelpers\FilterDescriptor;

class PartnerTermekcsoportSzerzodesRepository extends \mkwhelpers\Repository {

    public function __construct($em, \Doctrine\ORM\Mapping\ClassMetadata $class) {
        parent::__construct($em, $class);
        $this->setEntityname('Entities\PartnerTermekcsoportSzerzodes');
    }

    public function getByPartnerTermekcsoport($partner, $tcs) {
        $filter = new FilterDescriptor();
        $filter
            ->addFilter('partner', '=', $partner)
            ->addFilter('termekcsoport', '=', $tcs);

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
