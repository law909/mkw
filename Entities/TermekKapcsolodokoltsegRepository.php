<?php

namespace Entities;

class TermekKapcsolodokoltsegRepository extends \mkwhelpers\Repository
{

    public function __construct($em, \Doctrine\ORM\Mapping\ClassMetadata $class)
    {
        parent::__construct($em, $class);
        $this->setEntityname(TermekKapcsolodokoltseg::class);
    }

}
