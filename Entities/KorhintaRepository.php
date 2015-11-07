<?php
namespace Entities;

use mkwhelpers\FilterDescriptor;

class KorhintaRepository extends \mkwhelpers\Repository {

    public function __construct($em, \Doctrine\ORM\Mapping\ClassMetadata $class) {
        parent::__construct($em, $class);
        $this->setEntityname('Entities\Korhinta');
        $this->setOrders(array(
            '1' => array('caption' => 'sorrend szerint növekvő', 'order' => array('_xx.sorrend' => 'ASC', '_xx.nev' => 'ASC')),
            '2' => array('caption' => 'név szerint növekvő', 'order' => array('_xx.nev' => 'ASC', '_xx.sorrend' => 'ASC'))
        ));
    }

    public function getAllLathato() {
        $filter = new FilterDescriptor();
        $filter->addFilter('lathato', '=', true);
        $order = array('sorrend' => 'ASC', 'nev' => 'ASC');
        return $this->getAll($filter, $order);
    }
}