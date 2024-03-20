<?php

namespace Entities;

use mkwhelpers\FilterDescriptor;

class SzallitasimodHatarRepository extends \mkwhelpers\Repository
{

    public function __construct($em, \Doctrine\ORM\Mapping\ClassMetadata $class)
    {
        parent::__construct($em, $class);
        $this->setEntityname('Entities\SzallitasimodHatar');
        $this->setOrders([
            '1' => ['caption' => 'név szerint', 'order' => ['_xx.nev' => 'ASC']]
        ]);
    }

    public function getBySzallitasimod($szallmod)
    {
        $filter = new FilterDescriptor();
        $filter->addFilter('szallitasimod', '=', $szallmod);

        return $this->getAll($filter, []);
    }

    /**
     * @param $szallmod
     * @param $valutanem
     * @param $hatar
     *
     * @return false|SzallitasimodHatar
     */
    public function getBySzallitasimodValutanemHatar($szallmod, $valutanem, $hatar)
    {
        $filter = new FilterDescriptor();
        $filter
            ->addFilter('szallitasimod', '=', $szallmod)
            ->addFilter('valutanem', '=', $valutanem)
            ->addFilter('hatarertek', '<=', $hatar);

        \mkw\store::writelog(print_r($filter->getArray(), true));

        $t = $this->getAll($filter, ['hatarertek' => 'DESC']);
        if ($t) {
            return $t[0];
        }
        return false;
    }

}
