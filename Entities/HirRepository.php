<?php
namespace Entities;

use mkw\store;
use mkwhelpers\FilterDescriptor;

class HirRepository extends \mkwhelpers\Repository {

    public function __construct($em, \Doctrine\ORM\Mapping\ClassMetadata $class) {
        parent::__construct($em, $class);
        $this->setEntityname('Entities\Hir');
        $this->setOrders(array(
            '1' => array('caption' => 'cím szerint növekvő', 'order' => array('_xx.cim' => 'ASC'))
        ));
    }

    public function getMaiHirek() {
        $filter = new FilterDescriptor();
        $filter
            ->addFilter('elsodatum', '<=', date(store::$DateFormat))
            ->addFilter('utolsodatum', '>=', date(store::$DateFormat))
            ->addFilter('lathato', '=', true);

        $order = array('_xx.sorrend' => 'ASC', '_xx.id' => 'DESC');

        $res = $this->getAll($filter, $order);
        return $res;
    }

    public function getFeedHirek() {
        $filter = new FilterDescriptor();
        $filter->addFilter('lathato', '=', true);

        $order = array('_xx.id' => 'DESC');

        $res = $this->getAll($filter, $order, 0, store::getParameter(\mkw\consts::Feedhirdb, 20));
        return $res;
    }

}