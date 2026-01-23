<?php

namespace Entities;

class BlokkRepository extends \mkwhelpers\Repository
{

    public function __construct($em, \Doctrine\ORM\Mapping\ClassMetadata $class)
    {
        parent::__construct($em, $class);
        $this->setEntityname(Blokk::class);
        $this->setOrders([
            '1' => ['caption' => 'sorrend szerint', 'order' => ['sorrend' => 'ASC']],
            '2' => ['caption' => 'név szerint', 'order' => ['nev' => 'ASC']],
        ]);
    }

    public function getWithJoins($filter, $order = [], $offset = 0, $elemcount = 0)
    {
        return $this->getAll($filter, $order, $offset, $elemcount);
    }

    public function getTipusList()
    {
        return [
            1 => 'statikus blokk',
            2 => 'dupla statikus blokk',
            3 => 'csapatok',
            4 => 'versenyzők',
            5 => 'ajánlott termékek',
            6 => 'új termékek',
            7 => 'top termékek',
            8 => 'hírek'
        ];
    }

    public function getSzovegigazitasList()
    {
        return [
            1 => 'balra',
            2 => 'középre',
            3 => 'jobbra'
        ];
    }

    public function getBlokkmagassagList()
    {
        return [
            1 => 'alacsony',
            2 => 'normál',
            3 => 'teljes képernyő'
        ];
    }
}
