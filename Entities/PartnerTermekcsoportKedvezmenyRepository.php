<?php

namespace Entities;

class PartnerTermekcsoportKedvezmenyRepository extends \mkwhelpers\Repository {

    public function __construct($em, \Doctrine\ORM\Mapping\ClassMetadata $class) {
        parent::__construct($em, $class);
        $this->setEntityname('Entities\PartnerTermekcsoportKedvezmeny');
    }

    public function getByPartnerTermekcsoport($partner, $tcs) {
        $filter = array();
        $filter['fields'][] = 'partner';
        $filter['clauses'][] = '=';
        $filter['values'][] = $partner;
        $filter['fields'][] = 'termekcsoport';
        $filter['clauses'][] = '=';
        $filter['values'][] = $tcs;
        $kdv = $this->getAll($filter);
        if ($kdv) {
            $kdv = $kdv[0];
        }
        return $kdv;
    }
}
