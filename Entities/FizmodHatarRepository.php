<?php

namespace Entities;

use mkwhelpers\FilterDescriptor;

class FizmodHatarRepository extends \mkwhelpers\Repository
{

    public function __construct($em, \Doctrine\ORM\Mapping\ClassMetadata $class)
    {
        parent::__construct($em, $class);
        $this->setEntityname(FizmodHatar::class);
        $this->setOrders([
            '1' => ['caption' => 'név szerint', 'order' => ['_xx.nev' => 'ASC']]
        ]);
    }

    public function getByValutanem($valutanem)
    {
        $filter = new FilterDescriptor();
        $filter->addFilter('valutanem', '=', $valutanem);

        return $this->getAll($filter, []);
    }

    public function getByFizmod($fizmod)
    {
        $filter = new FilterDescriptor();
        $filter->addFilter('fizmod', '=', $fizmod);

        return $this->getAll($filter, []);
    }

    public function getByValutanemHatar($valutanem, $hatar)
    {
        $filter = new FilterDescriptor();
        $filter
            ->addFilter('valutanem', '=', $valutanem)
            ->addFilter('hatarertek', '<=', $hatar);

        $t = $this->getAll($filter, ['hatarertek' => 'DESC']);
        if ($t) {
            return $t[0];
        }
        return false;
    }
}
