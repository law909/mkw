<?php
namespace Entities;

use mkwhelpers\FilterDescriptor;

class FizmodHatarRepository extends \mkwhelpers\Repository {

    public function __construct($em, \Doctrine\ORM\Mapping\ClassMetadata $class) {
        parent::__construct($em, $class);
        $this->setEntityname('Entities\FizmodHatar');
        $this->setOrders(array(
            '1' => array('caption' => 'név szerint', 'order' => array('_xx.nev' => 'ASC'))
        ));
    }

    public function getByValutanem($valutanem) {
        $filter = new FilterDescriptor();
        $filter->addFilter('valutanem', '=', $valutanem);

        return $this->getAll($filter, array());
    }

    public function getByFizmod($fizmod) {
        $filter = new FilterDescriptor();
        $filter->addFilter('fizmod', '=', $fizmod);

        return $this->getAll($filter, array());
    }

    public function getByValutanemHatar($valutanem, $hatar) {
        $filter = new FilterDescriptor();
        $filter
            ->addFilter('valutanem', '=', $valutanem)
            ->addFilter('hatarertek', '<=', $hatar);

        $t = $this->getAll($filter, array('hatarertek' => 'DESC'));
        if ($t) {
            return $t[0];
        }
        return false;
    }
}
