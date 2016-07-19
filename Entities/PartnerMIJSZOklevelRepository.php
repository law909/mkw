<?php

namespace Entities;

use Doctrine\ORM\Query\ResultSetMapping;
use mkwhelpers\FilterDescriptor;

class PartnerMIJSZOklevelRepository extends \mkwhelpers\Repository {

    public function __construct($em, \Doctrine\ORM\Mapping\ClassMetadata $class) {
        parent::__construct($em, $class);
        $this->setEntityname('Entities\PartnerMIJSZOklevel');
    }

    public function getWithJoins($filter, $order, $offset = 0, $elemcount = 0) {
        $q = $this->_em->createQuery('SELECT _xx,partner, osz, ok'
            . ' FROM Entities\PartnerMIJSZOklevel _xx'
            . ' LEFT JOIN _xx.partner partner'
            . ' LEFT JOIN _xx.mijszoklevelszint osz'
            . ' LEFT JOIN _xx.mijszoklevelkibocsajto ok'
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

        $kdv = $this->getWithJoins($filter, array());
        return $kdv;
    }

    public function getLastByPartner($partner) {
        $filter = new FilterDescriptor();
        $filter->addFilter('partner', '=', $partner);

        $kdv = $this->getWithJoins($filter, array('_xx.oklevelev' => 'DESC'));
        if ($kdv) {
            return $kdv[0];
        }
        return false;
    }

}
