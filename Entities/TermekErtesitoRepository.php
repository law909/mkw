<?php
namespace Entities;

use mkwhelpers\FilterDescriptor;

class TermekErtesitoRepository extends \mkwhelpers\Repository {

    public function __construct($em, \Doctrine\ORM\Mapping\ClassMetadata $class) {
        parent::__construct($em, $class);
        $this->setEntityname('Entities\TermekErtesito');
        $this->setOrders(array(
            '1' => array('caption' => 'név szerint növekvő', 'order' => array('_xx.nev' => 'ASC'))
        ));
    }

    public function getByPartner($partner) {
        $filter = new FilterDescriptor();
        $filter->addFilter('partner', '=', $partner);
        $filter->addSql('(_xx.sent IS NULL)');
        return $this->getAll($filter, array('created' => 'ASC'));
    }

    public function getByTermek($termek) {
        $filter = new FilterDescriptor();
        $filter->addFilter('termek', '=', $termek);
        $filter->addSql('(_xx.sent IS NULL)');
        return $this->getAll($filter, array('created' => 'ASC'));
    }

}