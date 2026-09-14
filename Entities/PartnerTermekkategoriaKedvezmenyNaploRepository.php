<?php

namespace Entities;

class PartnerTermekkategoriaKedvezmenyNaploRepository extends \mkwhelpers\Repository
{

    public function __construct($em, \Doctrine\ORM\Mapping\ClassMetadata $class)
    {
        parent::__construct($em, $class);
        $this->setEntityname(PartnerTermekkategoriaKedvezmenyNaplo::class);
        $this->setOrders([
            '1' => ['caption' => 'idő szerint csökkenő', 'order' => ['_xx.created' => 'DESC', '_xx.id' => 'DESC']],
            '2' => ['caption' => 'idő szerint növekvő', 'order' => ['_xx.created' => 'ASC', '_xx.id' => 'ASC']],
            '3' => ['caption' => 'partner szerint', 'order' => ['_xx.partnernev' => 'ASC', '_xx.created' => 'DESC']],
            '4' => ['caption' => 'kategória szerint', 'order' => ['_xx.termekfanev' => 'ASC', '_xx.created' => 'DESC']]
        ]);
    }

}
