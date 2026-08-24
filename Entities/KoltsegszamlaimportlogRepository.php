<?php

namespace Entities;

class KoltsegszamlaimportlogRepository extends \mkwhelpers\Repository
{

    public function __construct($em, \Doctrine\ORM\Mapping\ClassMetadata $class)
    {
        parent::__construct($em, $class);
        $this->setEntityname(Koltsegszamlaimportlog::class);
        $this->setOrders([
            '1' => ['caption' => 'időpont szerint csökkenő', 'order' => ['_xx.created' => 'DESC', '_xx.id' => 'DESC']],
            '2' => ['caption' => 'időpont szerint növekvő', 'order' => ['_xx.created' => 'ASC', '_xx.id' => 'ASC']],
            '3' => ['caption' => 'számlaszám szerint', 'order' => ['_xx.szamlaszam' => 'ASC']],
            '4' => ['caption' => 'szállító szerint', 'order' => ['_xx.szallito' => 'ASC']],
        ]);
    }

}
