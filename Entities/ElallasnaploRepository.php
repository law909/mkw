<?php

namespace Entities;

class ElallasnaploRepository extends \mkwhelpers\Repository
{

    public function __construct($em, \Doctrine\ORM\Mapping\ClassMetadata $class)
    {
        parent::__construct($em, $class);
        $this->setEntityname(Elallasnaplo::class);
        $this->setOrders([
            '1' => ['caption' => 'dátum szerint', 'order' => ['_xx.created' => 'ASC']]
        ]);
    }

}
