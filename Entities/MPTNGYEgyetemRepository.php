<?php

namespace Entities;

class MPTNGYEgyetemRepository extends \mkwhelpers\Repository
{

    public function __construct($em, \Doctrine\ORM\Mapping\ClassMetadata $class)
    {
        parent::__construct($em, $class);
        $this->setEntityname(MPTNGYEgyetem::class);
    }

}
