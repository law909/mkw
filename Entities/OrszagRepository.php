<?php

namespace Entities;

use mkwhelpers\FilterDescriptor;

class OrszagRepository extends \mkwhelpers\Repository
{

    public function __construct($em, \Doctrine\ORM\Mapping\ClassMetadata $class)
    {
        parent::__construct($em, $class);
        $this->setEntityname('Entities\Orszag');
    }

    public function getAllLathato()
    {
        $filter = new FilterDescriptor();
        $filter->addFilter(\mkw\store::getWebshopFieldName('lathato'), '=', true);
        return $this->getAll($filter, ['nev' => 'ASC']);
    }

}