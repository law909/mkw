<?php

namespace Entities;

use Doctrine\ORM\Query\ResultSetMapping;

class PopupRepository extends \mkwhelpers\Repository
{

    public function __construct($em, \Doctrine\ORM\Mapping\ClassMetadata $class)
    {
        parent::__construct($em, $class);
        $this->setEntityname(Popup::class);
        $this->setOrders([
            '1' => ['caption' => 'név szerint növekvő', 'order' => ['nev' => 'ASC']],
        ]);
    }

}