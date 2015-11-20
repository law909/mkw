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

    public function getNemkaphatoTermekek($order = array()) {
        $filter = new FilterDescriptor();
        $filter->addFilter('t.nemkaphato', '=', true);
        $filter->addSql('_xx.sent IS NULL');
        $q = $this->_em->createQuery('SELECT DISTINCT t.id,t.cikkszam,t.nev,MIN(_xx.created) AS created'
            . ' FROM Entities\TermekErtesito _xx'
            . ' JOIN _xx.termek t'
            . $filter->getFilterString()
            . ' GROUP BY t.id,t.cikkszam,t.nev'
            . $this->getOrderString($order));
        $q->setParameters($filter->getQueryParameters());
        return $q->getScalarResult();
    }

}