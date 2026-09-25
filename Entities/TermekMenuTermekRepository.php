<?php

namespace Entities;

class TermekMenuTermekRepository extends \mkwhelpers\Repository
{

    public function __construct($em, \Doctrine\ORM\Mapping\ClassMetadata $class)
    {
        parent::__construct($em, $class);
        $this->setEntityname(TermekMenuTermek::class);
    }
}
