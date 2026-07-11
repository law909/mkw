<?php

namespace Entities;

class KuponRepository extends \mkwhelpers\Repository
{

    public function __construct($em, \Doctrine\ORM\Mapping\ClassMetadata $class)
    {
        parent::__construct($em, $class);
        $this->setEntityname(Kupon::class);
        $this->setOrders([
            '1' => ['caption' => 'Létrehozás szerint csökkenő', 'order' => ['_xx.created' => 'DESC']]
        ]);
    }
}
