<?php

namespace Entities;

class MenucsoportRepository extends \mkwhelpers\Repository
{

    public function __construct($em, \Doctrine\ORM\Mapping\ClassMetadata $class)
    {
        parent::__construct($em, $class);
        $this->setEntityname(Menucsoport::class);
        $this->setOrders([
            '1' => ['caption' => 'sorrend szerint', 'order' => ['_xx.sorrend' => 'ASC', '_xx.nev' => 'ASC']],
            '2' => ['caption' => 'név szerint növekvő', 'order' => ['_xx.nev' => 'ASC']],
        ]);
    }
}