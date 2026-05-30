<?php

namespace Entities;

use mkwhelpers\FilterDescriptor;

class OrszagRepository extends \mkwhelpers\Repository
{

    public function __construct($em, \Doctrine\ORM\Mapping\ClassMetadata $class)
    {
        parent::__construct($em, $class);
        $this->setEntityname('Entities\Orszag');
        $this->setOrders([
            '1' => ['caption' => 'név szerint növekvő', 'order' => ['_xx.nev' => 'ASC']],
            '2' => ['caption' => 'név szerint csökkenő', 'order' => ['_xx.nev' => 'DESC']],
            '3' => ['caption' => 'ISO 3166 szerint növekvő', 'order' => ['_xx.iso3166' => 'ASC']],
        ]);
    }

    public function getAllLathato()
    {
        $filter = new FilterDescriptor();
        $filter->addFilter(\mkw\store::getWebshopFieldName('lathato'), '=', true);
        return $this->getAll($filter, ['nev' => 'ASC']);
    }

}