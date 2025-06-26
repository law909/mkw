<?php

namespace Entities;

use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Query\ResultSetMapping;

class BankbizonylattetelRepository extends \mkwhelpers\Repository
{

    public function __construct($em, \Doctrine\ORM\Mapping\ClassMetadata $class)
    {
        parent::__construct($em, $class);
        $this->setEntityname('Entities\Bankbizonylattetel');
        $this->setOrders([
            '1' => ['caption' => 'dátum szerint csökkenő', 'order' => ['_xx.datum' => 'DESC', '_xx.id' => 'DESC']],
            '2' => ['caption' => 'dátum szerint növekvő', 'order' => ['_xx.datum' => 'DESC', '_xx.id' => 'DESC']],
        ]);
    }

    public function isFirstByHivatkozottBizonylat($id, $bizszam, $datum)
    {
        $filter = new \mkwhelpers\FilterDescriptor();
        $filter
            ->addFilter('id', '<>', $id)
            ->addFilter('hivatkozottbizonylat', '=', $bizszam)
            ->addFilter('datum', '<', $datum)
            ->addFilter('irany', '>=', 1)
            ->addFilter('rontott', '=', 0);

        $q = $this->_em->createQuery(
            'SELECT COUNT(_xx)'
            . ' FROM Entities\Bankbizonylattetel _xx'
            . $this->getFilterString($filter)
        );
        $q->setParameters($this->getQueryParameters($filter));
        return $q->getSingleScalarResult() * 1 === 0;
    }

    public function getAllHivatkozottJoin($filter = [], $order = [], $belso = false)
    {
        $rsm = new ResultSetMapping();
        $rsm->addScalarResult('id', 'id');
        $rsm->addScalarResult('bankbizonylatfej_id', 'bankbizonylatfej_id');
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
        } else {
            $rsm->addScalarResult('uzletkoto_id', 'uzletkoto_id');
            $rsm->addScalarResult('uzletkotonev', 'uzletkotonev');
            $rsm->addScalarResult('uzletkotojutalek', 'uzletkotojutalek');
            $ukfields = 'bf.uzletkoto_id, bf.uzletkotonev, bf.uzletkotojutalek';
        }
        $rsm->addScalarResult('partnernev', 'partnernev');

        $q = $this->_em->createNativeQuery(
            'SELECT _xx.id, _xx.bankbizonylatfej_id, _xx.brutto, _xx.valutanem_id, _xx.valutanemnev,'
            . '_xx.datum, _xx.hivatkozottdatum, _xx.hivatkozottbizonylat,'
            . $ukfields . ', bf.partnernev '
            . ' FROM bankbizonylattetel _xx '
            . ' JOIN bizonylatfej bf ON (_xx.hivatkozottbizonylat=bf.id)'
            . $this->getFilterString($filter)
            . $this->getOrderString($order)
            ,
            $rsm
        );
        $q->setParameters($this->getQueryParameters($filter));
        return $q->getResult();
    }

    public function getAllWithHivatkozottbizonylat($filter = [], $order = [])
    {
        $q = $this->_em->createQuery(
            'SELECT _xx,'
            . ' FROM Entities\Bankbizonylattetel _xx'
        );
        $q->setParameters($this->getQueryParameters($filter));
        return $q->getResult();
    }

    public function calcSumByValutanem($filter = [], $order = [])
    {
        $a = $this->alias;
        return $this->_em->createQuery(
            'SELECT v.nev, SUM(_xx.brutto) AS osszeg'
            . ' FROM Entities\Bankbizonylattetel _xx'
            . ' LEFT JOIN _xx.bizonylatfej bf'
            . ' LEFT JOIN _xx.valutanem v'
            . $this->getFilterString($filter)
            . ' GROUP BY v.nev'
            . $this->getOrderString($order)
        )
            ->setParameters($this->getQueryParameters($filter))
            ->getResult();
    }

    public function getAllWithFej($filter = [], $order = [])
    {
        $q = $this->_em->createQuery(
            'SELECT _xx '
            . ' FROM Entities\Bankbizonylattetel _xx'
            . ' LEFT JOIN _xx.bizonylatfej bf'
            . $this->getFilterString($filter)
            . $this->getOrderString($order)
        );
        $q->setParameters($this->getQueryParameters($filter));
        return $q->getResult();
    }

    public function getWithJoins($filter, $order, $offset = 0, $elemcount = 0)
    {
        $q = $this->_em->createQuery(
            'SELECT _xx'
            . ' FROM Entities\Bankbizonylattetel _xx'
            . ' LEFT JOIN _xx.bizonylatfej bf'
            . ' LEFT JOIN _xx.partner p'
            . ' LEFT JOIN _xx.valutanem v'
            . ' LEFT JOIN _xx.jogcim j'
            . $this->getFilterString($filter)
            . $this->getOrderString($order)
        );
        $q->setParameters($this->getQueryParameters($filter));
        if ($offset > 0) {
            $q->setFirstResult($offset);
        }
        if ($elemcount > 0) {
            $q->setMaxResults($elemcount);
        }
        return $q->getResult();
    }
}
