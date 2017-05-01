<?php

namespace Entities;

use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Query\ResultSetMapping;

class PenztarbizonylattetelRepository extends \mkwhelpers\Repository {

    public function __construct($em, \Doctrine\ORM\Mapping\ClassMetadata $class) {
        parent::__construct($em, $class);
        $this->setEntityname('Entities\Penztarbizonylattetel');
    }

    public function getAllHivatkozottJoin($filter = array(), $order = array(), $belso = false) {
        $rsm = new ResultSetMapping();
        $rsm->addScalarResult('id', 'id');
        $rsm->addScalarResult('penztarbizonylatfej_id', 'penztarbizonylatfej_id');
        $rsm->addScalarResult('brutto', 'brutto');
        $rsm->addScalarResult('valutanem_id', 'valutanem_id');
        $rsm->addScalarResult('valutanemnev', 'valutanemnev');
        $rsm->addScalarResult('datum', 'datum');
        $rsm->addScalarResult('hivatkozottdatum', 'hivatkozottdatum');
        $rsm->addScalarResult('hivatkozottbizonylat', 'hivatkozottbizonylat');
        if ($belso) {
            $rsm->addScalarResult('belsouzletkoto_id', 'uzletkoto_id');
            $rsm->addScalarResult('belsouzletkotonev', 'uzletkotonev');
            $rsm->addScalarResult('belsouzletkotojutalek', 'uzletkotojutalek');
            $ukfields = 'bf.belsouzletkoto_id, bf.belsouzletkotonev, bf.belsouzletkotojutalek';
        }
        else {
            $rsm->addScalarResult('uzletkoto_id', 'uzletkoto_id');
            $rsm->addScalarResult('uzletkotonev', 'uzletkotonev');
            $rsm->addScalarResult('uzletkotojutalek', 'uzletkotojutalek');
            $ukfields = 'bf.uzletkoto_id, bf.uzletkotonev, bf.uzletkotojutalek';
        }
        $rsm->addScalarResult('partnernev', 'partnernev');

        $q = $this->_em->createNativeQuery('SELECT _xx.id, _xx.penztarbizonylatfej_id, _xx.brutto, _xx.valutanem_id, _xx.valutanemnev,'
            . '_xx.datum, _xx.hivatkozottdatum, _xx.hivatkozottbizonylat,'
            . $ukfields . ', bf.partnernev '
            . ' FROM penztarbizonylattetel _xx '
            . ' JOIN bizonylatfej bf ON (_xx.hivatkozottbizonylat=bf.id)'
            . $this->getFilterString($filter)
            . $this->getOrderString($order)
            , $rsm);
        $q->setParameters($this->getQueryParameters($filter));
        return $q->getResult();
    }

    public function getAllWithHivatkozottbizonylat($filter = array(), $order = array()) {
        $q = $this->_em->createQuery('SELECT _xx,'
            . ' FROM Entities\Penztarbizonylattetel _xx'
        );
        $q->setParameters($this->getQueryParameters($filter));
        return $q->getResult();
    }

    public function calcSumByValutanem($filter = array(), $order = array()) {
        $a = $this->alias;
        return $this->_em->createQuery('SELECT v.nev, SUM(_xx.brutto) AS osszeg'
            . ' FROM Entities\Penztarbizonylattetel _xx'
            . ' LEFT JOIN _xx.bizonylatfej bf'
            . ' LEFT JOIN _xx.valutanem v'
            . $this->getFilterString($filter)
            . ' GROUP BY v.nev'
            . $this->getOrderString($order))
            ->setParameters($this->getQueryParameters($filter))
            ->getResult();
    }

    public function getAllWithFej($filter = array(), $order = array()) {
        $q = $this->_em->createQuery('SELECT _xx '
            . ' FROM Entities\penztarbizonylattetel _xx'
            . ' LEFT JOIN _xx.bizonylatfej bf'
            . $this->getFilterString($filter)
            . $this->getOrderString($order));
        $q->setParameters($this->getQueryParameters($filter));
        return $q->getResult();
    }
}
