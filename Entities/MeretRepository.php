<?php

namespace Entities;

class MeretRepository extends \mkwhelpers\Repository
{

    public function __construct($em, \Doctrine\ORM\Mapping\ClassMetadata $class)
    {
        parent::__construct($em, $class);
        $this->setEntityname(Meret::class);
        $this->setOrders([
            '1' => ['caption' => 'sorrend és név szerint', 'order' => ['sorrend' => 'ASC', 'nev' => 'ASC']],
        ]);
    }

    public function getAll($filter = [], $order = [], $offset = 0, $elemcount = 0)
    {
        $order = ['sorrend' => 'ASC', 'nev' => 'ASC'];
        return parent::getAll($filter, $order, $offset, $elemcount);
    }
}