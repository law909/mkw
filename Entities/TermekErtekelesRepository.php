<?php
namespace Entities;

use mkwhelpers\FilterDescriptor;

class TermekErtekelesRepository extends \mkwhelpers\Repository {

    public function __construct($em, \Doctrine\ORM\Mapping\ClassMetadata $class) {
        parent::__construct($em, $class);
        $this->setEntityname(TermekErtekeles::class);
        $this->setOrders(array(
            '1' => array('caption' => 'dátum szerint csökkenő', 'order' => array('_xx.created' => 'DESC'))
        ));
    }

    public function getWithJoins($filter, $order = array(), $offset = 0, $elemcount = 0) {
        $q = $this->_em->createQuery('SELECT _xx, p, t'
                                     . ' FROM Entities\Termekertekeles _xx'
                                     . ' LEFT JOIN _xx.partner p'
                                     . ' LEFT JOIN _xx.termek t'
                                     . $this->getFilterString($filter)
                                     . $this->getOrderString($order));
        $q->setParameters($this->getQueryParameters($filter));
        if ($offset > 0) {
            $q->setFirstResult($offset);
        }
        if ($elemcount > 0) {
            $q->setMaxResults($elemcount);
        }
        return $q->getResult();
    }

    public function getByPartner($partner) {
        $filter = new FilterDescriptor();
        $filter->addFilter('partner', '=', $partner);
        return $this->getAll($filter, array('created' => 'ASC'));
    }

    public function getByTermek($termek) {
        $filter = new FilterDescriptor();
        $filter->addFilter('termek', '=', $termek);
        return $this->getAll($filter, array('created' => 'ASC'));
    }

    public function getAtlagByTermek($termek) {
        $filter = new FilterDescriptor();
        $filter->addFilter('termek', '=', $termek);
        $filter->addFilter('elutasitva', '<>', true);
        $q = $this->_em->createQuery('SELECT COUNT(_xx), SUM(_xx.ertekeles) '
            . ' FROM Entities\Termekertekeles _xx'
            . $this->getFilterString($filter));
        $q->setParameters($this->getQueryParameters($filter));
        return $q->getArrayResult();
    }

}