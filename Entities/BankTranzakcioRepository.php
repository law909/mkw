<?php

namespace Entities;

class BankTranzakcioRepository extends \mkwhelpers\Repository
{

    public function __construct($em, \Doctrine\ORM\Mapping\ClassMetadata $class)
    {
        parent::__construct($em, $class);
        $this->setEntityname(BankTranzakcio::class);
        $this->setOrders([
            '1' => ['caption' => 'értéknap szerint csökkenő', 'order' => ['_xx.erteknap' => 'DESC']],
        ]);
    }
    
}