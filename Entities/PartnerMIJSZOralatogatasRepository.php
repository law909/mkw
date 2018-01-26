<?php

namespace Entities;

use Doctrine\ORM\Query\ResultSetMapping;
use mkwhelpers\FilterDescriptor;

class PartnerMIJSZOralatogatasRepository extends \mkwhelpers\Repository {

    public function __construct($em, \Doctrine\ORM\Mapping\ClassMetadata $class) {
        parent::__construct($em, $class);
        $this->setEntityname('Entities\PartnerMIJSZOralatogatas');
    }

    public function getWithJoins($filter, $order, $offset = 0, $elemcount = 0) {
        $q = $this->_em->createQuery('SELECT _xx,partner'
            . ' FROM Entities\PartnerMIJSZOralatogatas _xx'
            . ' LEFT JOIN _xx.partner partner'
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

    public function getReszletezes($ev) {
        $filter = new FilterDescriptor();
        $filter->addSql('(p.partnertipus_id=1) AND ((o.ev=' . $ev . ') OR (o.ev IS NULL))');

        $rsm = new ResultSetMapping();
        $rsm->addScalarResult('id', 'id');
        $rsm->addScalarResult('nev', 'nev');
        $rsm->addScalarResult('email', 'email');
        $rsm->addScalarResult('tanar', 'tanar');
        $rsm->addScalarResult('tanaregyeb', 'tanaregyeb');
        $rsm->addScalarResult('helyszin', 'helyszin');
        $rsm->addScalarResult('mikor', 'mikor');
        $rsm->addScalarResult('oraszam', 'oraszam');

        $q = $this->_em->createNativeQuery('SELECT p.id,p.nev,p.email,t.nev as tanar,tanaregyeb,helyszin,mikor,oraszam'
            . ' FROM partner p'
            . ' LEFT OUTER JOIN partnermijszoralatogatas o ON (p.id=o.partner_id)'
            . ' LEFT OUTER JOIN partner t ON (o.tanar_id=t.id)'
            . $this->getFilterString($filter)
            . ' ORDER BY p.nev', $rsm);

        $q->setParameters($this->getQueryParameters($filter));
        return $q->getScalarResult();
    }

}
