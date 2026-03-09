<?php

namespace Entities;

use Doctrine\ORM\Query\ResultSetMapping;
use mkwhelpers\FilterDescriptor;

class TermekSzinKepRepository extends \mkwhelpers\Repository
{

    public function __construct($em, \Doctrine\ORM\Mapping\ClassMetadata $class)
    {
        parent::__construct($em, $class);
        $this->setEntityname(TermekSzinKep::class);
    }

    public function getByTermekAndSzin($termek, $szin)
    {
        $filter = new FilterDescriptor();
        if ($termek && $szin) {
            $filter->addFilter('termek', '=', $termek);
            $filter->addFilter('szin', '=', $szin);
            return $this->getAll($filter, ['sorrend' => 'ASC']);
        }
        return [];
    }

}
