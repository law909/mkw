<?php

namespace Entities;

use Doctrine\ORM\Query\ResultSetMapping;
use mkwhelpers\FilterDescriptor;

class PartnerTermekcsoportKedvezmenyRepository extends \mkwhelpers\Repository {

    public function __construct($em, \Doctrine\ORM\Mapping\ClassMetadata $class) {
        parent::__construct($em, $class);
        $this->setEntityname('Entities\PartnerTermekcsoportKedvezmeny');
    }

    public function getWithJoins($filter, $order, $offset = 0, $elemcount = 0) {
        $q = $this->_em->createQuery('SELECT _xx,partner, tcs'
            . ' FROM Entities\PartnerTermekcsoportKedvezmeny _xx'
            . ' LEFT JOIN _xx.partner partner'
            . ' LEFT JOIN _xx.termekcsoport tcs'
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
        $filter = new FilterDescriptor();
        $filter
            ->addFilter('partner', '=', $partner)
            ->addFilter('termekcsoport', '=', $tcs);

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
        $rsm->addScalarResult('tcsid', 'tcsid');
        if ($partner) {
            $q = $this->_em->createNativeQuery('SELECT kdv.id, tcs.nev, kdv.kedvezmeny, tcs.id AS tcsid'
                . ' FROM termekcsoport tcs'
                . ' LEFT JOIN partnertermekcsoportkedvezmeny kdv ON (tcs.id = kdv.termekcsoport_id) AND (kdv.partner_id=:p)'
                . ' ORDER BY tcs.nev', $rsm);
            $q->setParameter(':p', $partner->getId());
        }
        else {
            $q = $this->_em->createNativeQuery('SELECT null AS id, tcs.nev, null AS kedvezmeny, tcs.id AS tcsid'
                . ' FROM termekcsoport tcs'
                . ' ORDER BY tcs.nev', $rsm);
        }
        return $q->getScalarResult();
    }

}
