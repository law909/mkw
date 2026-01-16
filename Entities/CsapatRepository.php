<?php

namespace Entities;

class CsapatRepository extends \mkwhelpers\Repository
{

    public function __construct($em, \Doctrine\ORM\Mapping\ClassMetadata $class)
    {
        parent::__construct($em, $class);
        $this->setEntityname(Csapat::class);
        $this->setOrders([
            '1' => ['caption' => 'név szerint', 'order' => ['nev' => 'ASC']],
        ]);
    }

    public function getWithJoins($filter, $order = [], $offset = 0, $elemcount = 0)
    {
        return $this->getAll($filter, $order, $offset, $elemcount);
    }
}
