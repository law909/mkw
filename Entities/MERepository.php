<?php

namespace Entities;

class MERepository extends \mkwhelpers\Repository
{

    public function __construct($em, \Doctrine\ORM\Mapping\ClassMetadata $class)
    {
        parent::__construct($em, $class);
        $this->setEntityname(ME::class);
        $this->setOrders([
            '1' => ['caption' => 'név szerint', 'order' => ['_xx.nev' => 'ASC']]
        ]);
    }

}
