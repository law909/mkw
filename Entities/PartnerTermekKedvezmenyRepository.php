<?php

namespace Entities;

use Doctrine\ORM\Query\ResultSetMapping;
use mkwhelpers\FilterDescriptor;

class PartnerTermekKedvezmenyRepository extends \mkwhelpers\Repository {

    public function __construct($em, \Doctrine\ORM\Mapping\ClassMetadata $class) {
        parent::__construct($em, $class);
        $this->setEntityname('Entities\PartnerTermekKedvezmeny');
    }

    public function getWithJoins($filter, $order, $offset = 0, $elemcount = 0) {
        $q = $this->_em->createQuery('SELECT _xx,partner, t'
            . ' FROM Entities\PartnerTermekKedvezmeny _xx'
            . ' LEFT JOIN _xx.partner partner'
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

    public function getByPartnerTermek($partner, $t) {
        $filter = new FilterDescriptor();
        $filter
            ->addFilter('partner', '=', $partner)
            ->addFilter('termek', '=', $t);

        $kdv = $this->getWithJoins($filter, array());
        if ($kdv) {
            $kdv = $kdv[0];
        }
        return $kdv;
    }

    public function getByPartner($partner) {
        $filter = new FilterDescriptor();
        $filter->addFilter('partner', '=', $partner);

        $kdv = $this->getWithJoins($filter, array());
        return $kdv;
    }

    public function getForFiok($partner = null) {
        $rsm = new ResultSetMapping();
        $rsm->addScalarResult('id', 'id');
        $rsm->addScalarResult('nev', 'nev');
        $rsm->addScalarResult('kedvezmeny', 'kedvezmeny');
        $rsm->addScalarResult('tid', 'tid');
        if ($partner) {
            $q = $this->_em->createNativeQuery('SELECT kdv.id, t.nev, kdv.kedvezmeny, t.id AS tid'
                . ' FROM termek t'
                . ' LEFT JOIN partnertermekkedvezmeny kdv ON (t.id = kdv.termek_id) AND (kdv.partner_id=:p)'
                . ' ORDER BY t.nev', $rsm);
            $q->setParameter(':p', $partner->getId());
        }
        else {
            $q = $this->_em->createNativeQuery('SELECT null AS id, t.nev, null AS kedvezmeny, t.id AS tid'
                . ' FROM termek t'
                . ' ORDER BY t.nev', $rsm);
        }
        return $q->getScalarResult();
    }

}
