<?php

namespace Entities;

use mkwhelpers\FilterDescriptor;

class MeretsorRepository extends \mkwhelpers\Repository
{

    public function __construct($em, \Doctrine\ORM\Mapping\ClassMetadata $class)
    {
        parent::__construct($em, $class);
        $this->setEntityname(Meretsor::class);
        $this->setOrders([
            '1' => ['caption' => 'név szerint növekvő', 'order' => ['nev' => 'ASC']],
        ]);

        $btch = [];
        $this->setBatches($btch);
    }

}