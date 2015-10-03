<?php

namespace Entities;

use Doctrine\ORM\Query\ResultSetMapping;

class PartnerTermekcsoportKedvezmenyRepository extends \mkwhelpers\Repository {

    public function __construct($em, \Doctrine\ORM\Mapping\ClassMetadata $class) {
        parent::__construct($em, $class);
        $this->setEntityname('Entities\PartnerTermekcsoportKedvezmeny');
    }

    public function getWithJoins($filter, $order, $offset = 0, $elemcount = 0) {
        $a = $this->alias;
        $q = $this->_em->createQuery('SELECT ' . $a . ',partner, tcs'
            . ' FROM ' . $this->entityname . ' ' . $a
            . ' LEFT JOIN ' . $a . '.partner partner'
            . ' LEFT JOIN ' . $a . '.termekcsoport tcs'
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

    public function getByPartnerTermekcsoport($partner, $tcs) {
        $filter = array();
        $filter['fields'][] = 'partner';
        $filter['clauses'][] = '=';
        $filter['values'][] = $partner;
        $filter['fields'][] = 'termekcsoport';
        $filter['clauses'][] = '=';
        $filter['values'][] = $tcs;
        $kdv = $this->getWithJoins($filter, array());
        if ($kdv) {
            $kdv = $kdv[0];
        }
        return $kdv;
    }

    public function getByPartner($partner) {
        $filter = array();
        $filter['fields'][] = 'partner';
        $filter['clauses'][] = '=';
        $filter['values'][] = $partner;
        $kdv = $this->getWithJoins($filter, array());
        return $kdv;
    }

    public function getForFiok($partner) {
        $rsm = new ResultSetMapping();
        $rsm->addScalarResult('id', 'id');
        $rsm->addScalarResult('nev', 'nev');
        $rsm->addScalarResult('kedvezmeny', 'kedvezmeny');
        $rsm->addScalarResult('tcsid', 'tcsid');
        $q = $this->_em->createNativeQuery('SELECT kdv.id, tcs.nev, kdv.kedvezmeny, tcs.id AS tcsid'
            . ' FROM termekcsoport tcs'
            . ' LEFT JOIN partnertermekcsoportkedvezmeny kdv ON (tcs.id = kdv.termekcsoport_id) AND (kdv.partner_id=:p)'
            . ' ORDER BY tcs.nev', $rsm);
        $q->setParameter(':p', $partner->getId());
        return $q->getScalarResult();
    }

}
