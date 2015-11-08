<?php

namespace Entities;

use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Query\ResultSetMapping;

class BankbizonylattetelRepository extends \mkwhelpers\Repository {

    public function __construct($em, \Doctrine\ORM\Mapping\ClassMetadata $class) {
        parent::__construct($em, $class);
        $this->setEntityname('Entities\Bankbizonylattetel');
    }

    public function getAllHivatkozottJoin($filter = array(), $order = array()) {
        $rsm = new ResultSetMapping();
        $rsm->addScalarResult('id', 'id');
        $rsm->addScalarResult('bankbizonylatfej_id', 'bankbizonylatfej_id');
        $rsm->addScalarResult('brutto', 'brutto');
        $rsm->addScalarResult('valutanem_id', 'valutanem_id');
        $rsm->addScalarResult('valutanemnev', 'valutanemnev');
        $rsm->addScalarResult('datum', 'datum');
        $rsm->addScalarResult('hivatkozottdatum', 'hivatkozottdatum');
        $rsm->addScalarResult('hivatkozottbizonylat', 'hivatkozottbizonylat');
        $rsm->addScalarResult('uzletkoto_id', 'uzletkoto_id');
        $rsm->addScalarResult('uzletkotonev', 'uzletkotonev');
        $rsm->addScalarResult('uzletkotojutalek', 'uzletkotojutalek');
        $rsm->addScalarResult('partnernev', 'partnernev');

        $q = $this->_em->createNativeQuery('SELECT _xx.id, _xx.bankbizonylatfej_id, _xx.brutto, _xx.valutanem_id, _xx.valutanemnev,'
            . '_xx.datum, _xx.hivatkozottdatum, _xx.hivatkozottbizonylat,'
            . 'bf.uzletkoto_id, bf.uzletkotonev, bf.uzletkotojutalek, bf.partnernev '
            . ' FROM bankbizonylattetel _xx '
            . ' JOIN bizonylatfej bf ON (_xx.hivatkozottbizonylat=bf.id)'
            . $this->getFilterString($filter)
            . $this->getOrderString($order)
            , $rsm);
        $q->setParameters($this->getQueryParameters($filter));
        return $q->getResult();
    }

    public function getAllWithHivatkozottbizonylat($filter = array(), $order = array()) {
        $q = $this->_em->createQuery('SELECT _xx,'
            . ' FROM Entities\Bankbizonylattetel _xx'
        );
        $q->setParameters($this->getQueryParameters($filter));
        return $q->getResult();
    }

    public function calcSumByValutanem($filter = array(), $order = array()) {
        $a = $this->alias;
        return $this->_em->createQuery('SELECT v.nev, SUM(_xx.brutto) AS osszeg'
            . ' FROM Entities\Bankbizonylattetel _xx'
            . ' LEFT JOIN _xx.valutanem v'
            . $this->getFilterString($filter)
            . ' GROUP BY v.nev'
            . $this->getOrderString($order))
            ->setParameters($this->getQueryParameters($filter))
            ->getResult();
    }
}
