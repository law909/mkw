<?php

namespace Entities;

class KapcsolodokoltsegRepository extends \mkwhelpers\Repository
{

    public function __construct($em, \Doctrine\ORM\Mapping\ClassMetadata $class)
    {
        parent::__construct($em, $class);
        $this->setEntityname(Kapcsolodokoltseg::class);
        $this->setOrders([
            '1' => ['caption' => 'név szerint növekvő', 'order' => ['_xx.nev' => 'ASC']],
            '2' => ['caption' => 'név szerint csökkenő', 'order' => ['_xx.nev' => 'DESC']],
            '3' => ['caption' => 'csoport szerint növekvő', 'order' => ['_xx.csoport' => 'ASC', '_xx.nev' => 'ASC']],
            '4' => ['caption' => 'ár szerint növekvő', 'order' => ['_xx.ar' => 'ASC']],
            '5' => ['caption' => 'ár szerint csökkenő', 'order' => ['_xx.ar' => 'DESC']],
        ]);
    }

    public function getCsoportList($sel = null): array
    {
        return $this->getEnumList(Kapcsolodokoltseg::CSOPORTOK, $sel);
    }

    public function getSzamitasalapList($sel = null): array
    {
        return $this->getEnumList(Kapcsolodokoltseg::SZAMITASALAPOK, $sel);
    }

    private function getEnumList(array $ertekek, $sel): array
    {
        $ret = [];
        foreach ($ertekek as $id => $caption) {
            $ret[] = ['id' => $id, 'caption' => $caption, 'selected' => ((string)$sel === (string)$id)];
        }
        return $ret;
    }

}
