<?php

namespace Entities;

class SzinRepository extends \mkwhelpers\Repository
{

    public function __construct($em, \Doctrine\ORM\Mapping\ClassMetadata $class)
    {
        parent::__construct($em, $class);
        $this->setEntityname(Szin::class);
        $this->setOrders([
            '1' => ['caption' => 'név szerint', 'order' => ['nev' => 'ASC']],
        ]);
    }

    public function getAll($filter = [], $order = [], $offset = 0, $elemcount = 0)
    {
        $order = array_merge($order, ['sorrend' => 'ASC', 'nev' => 'ASC']);
        return parent::getAll($filter, $order, $offset, $elemcount);
    }

    public function getWithJoins($filter, $order = [], $offset = 0, $elemcount = 0)
    {
        return $this->getAll($filter, $order, $offset, $elemcount);
    }

}