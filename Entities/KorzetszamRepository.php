<?php

namespace Entities;

class KorzetszamRepository extends \mkwhelpers\Repository
{

    public function __construct($em, \Doctrine\ORM\Mapping\ClassMetadata $class)
    {
        parent::__construct($em, $class);
        $this->setEntityname(Korzetszam::class);
        $this->setOrders([
            '1' => ['caption' => 'sorrend szerint', 'order' => ['_xx.sorrend' => 'ASC']],
            '2' => ['caption' => 'szám szerint növekvő', 'order' => ['_xx.id' => 'ASC']],
        ]);
    }
}