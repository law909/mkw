<?php

namespace Entities;

class SzinRepository extends \mkwhelpers\Repository
{

    public function __construct($em, \Doctrine\ORM\Mapping\ClassMetadata $class)
    {
        parent::__construct($em, $class);
        $this->setEntityname(Szin::class);
        $this->setOrders([
            '1' => ['caption' => 'sorrend és név szerint', 'order' => ['sorrend' => 'ASC', 'nev' => 'ASC']],
            '2' => ['caption' => 'név szerint', 'order' => ['nev' => 'ASC']],
        ]);
    }
    
    public function getWithJoins($filter, $order = [], $offset = 0, $elemcount = 0)
    {
        return $this->getAll($filter, $order, $offset, $elemcount);
    }

}