<?php

namespace Entities;

use mkwhelpers\FilterDescriptor;

class KorhintaRepository extends \mkwhelpers\Repository
{

    public function __construct($em, \Doctrine\ORM\Mapping\ClassMetadata $class)
    {
        parent::__construct($em, $class);
        $this->setEntityname(Korhinta::class);
        $this->setOrders([
            '1' => ['caption' => 'sorrend szerint növekvő', 'order' => ['_xx.sorrend' => 'ASC', '_xx.nev' => 'ASC']],
            '2' => ['caption' => 'név szerint növekvő', 'order' => ['_xx.nev' => 'ASC', '_xx.sorrend' => 'ASC']]
        ]);
    }

    public function getAllLathato()
    {
        $filter = new FilterDescriptor();
        $filter->addFilter('lathato', '=', true);
        $order = ['sorrend' => 'ASC', 'nev' => 'ASC'];
        return $this->getAll($filter, $order);
    }
}