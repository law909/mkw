<?php

namespace Entities;

class ElallasRepository extends \mkwhelpers\Repository
{

    public function __construct($em, \Doctrine\ORM\Mapping\ClassMetadata $class)
    {
        parent::__construct($em, $class);
        $this->setEntityname(Elallas::class);
        $this->setOrders([
            '1' => ['caption' => 'dátum szerint', 'order' => ['_xx.created' => 'DESC']],
            '2' => ['caption' => 'név szerint', 'order' => ['_xx.nev' => 'ASC']],
            '3' => ['caption' => 'bizonylat szerint', 'order' => ['_xx.bizonylat' => 'ASC']]
        ]);
    }

}
