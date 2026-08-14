<?php

namespace Entities;

class GLSUtanvetRepository extends \mkwhelpers\Repository
{

    public function __construct($em, \Doctrine\ORM\Mapping\ClassMetadata $class)
    {
        parent::__construct($em, $class);
        $this->setEntityname(GLSUtanvet::class);
        $this->setOrders([
            '1' => ['caption' => 'státusz dátuma szerint csökkenő', 'order' => ['_xx.statuszdatum' => 'DESC']],
            '2' => ['caption' => 'státusz dátuma szerint növekvő', 'order' => ['_xx.statuszdatum' => 'ASC']],
            '3' => ['caption' => 'csomagszám szerint', 'order' => ['_xx.csomagszam' => 'ASC']],
            '4' => ['caption' => 'név szerint', 'order' => ['_xx.nev' => 'ASC']],
        ]);
    }

}
