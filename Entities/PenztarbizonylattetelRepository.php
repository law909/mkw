<?php

namespace Entities;

use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Query\ResultSetMapping;

class PenztarbizonylattetelRepository extends \mkwhelpers\Repository
{

    public function __construct($em, \Doctrine\ORM\Mapping\ClassMetadata $class)
    {
        parent::__construct($em, $class);
        $this->setEntityname(Penztarbizonylattetel::class);
        $this->setOrders([
            '1' => ['caption' => 'kelt szerint csökkenő', 'order' => ['bf.kelt' => 'DESC', '_xx.id' => 'DESC']],
            '2' => ['caption' => 'kelt szerint növekvő', 'order' => ['bf.kelt' => 'ASC', '_xx.id' => 'ASC']],
            '3' => ['caption' => 'biz.szám szerint csökkenő', 'order' => ['bf.id' => 'DESC', '_xx.id' => 'DESC']],
            '4' => ['caption' => 'biz.szám szerint növekvő', 'order' => ['bf.id' => 'ASC', '_xx.id' => 'ASC']],
            '5' => ['caption' => 'jogcím szerint', 'order' => ['_xx.jogcimnev' => 'ASC', 'bf.kelt' => 'DESC']],
        ]);
    }

    /**
     * A tétel önmagában keveset mond: a partner, a pénztár, a kelt, a valutanem és az
     * irány mind a fejen van, ezért azt mindig hozzáfűzzük (a szűrők és a rendezés is
     * hivatkozhat rá 'bf' aliasszal).
     */
    public function getWithJoins($filter, $order, $offset = 0, $elemcount = 0): mixed
    {
        $q = $this->_em->createQuery(
            'SELECT _xx, bf'
            . ' FROM Entities\Penztarbizonylattetel _xx'
            . ' LEFT JOIN _xx.bizonylatfej bf'
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

    /**
     * A getWithJoins()-zal azonos join, különben a fejre hivatkozó szűrők itt
     * ismeretlen aliasra futnának.
     */
    public function getCount($filter)
    {
        $q = $this->_em->createQuery(
            'SELECT COUNT(_xx)'
            . ' FROM Entities\Penztarbizonylattetel _xx'
            . ' LEFT JOIN _xx.bizonylatfej bf'
            . $this->getFilterString($filter)
        );
        $q->setParameters($this->getQueryParameters($filter));
        return $q->getSingleScalarResult();
    }

    public function getAllHivatkozottJoin($filter = [], $order = [], $belso = false)
    {
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
        } else {
            $rsm->addScalarResult('uzletkoto_id', 'uzletkoto_id');
            $rsm->addScalarResult('uzletkotonev', 'uzletkotonev');
            $rsm->addScalarResult('uzletkotojutalek', 'uzletkotojutalek');
            $ukfields = 'bf.uzletkoto_id, bf.uzletkotonev, bf.uzletkotojutalek';
        }
        $rsm->addScalarResult('partnernev', 'partnernev');

        $q = $this->_em->createNativeQuery(
            'SELECT _xx.id, _xx.penztarbizonylatfej_id, _xx.brutto, _xx.valutanem_id, _xx.valutanemnev,'
            . '_xx.datum, _xx.hivatkozottdatum, _xx.hivatkozottbizonylat,'
            . $ukfields . ', bf.partnernev '
            . ' FROM penztarbizonylattetel _xx '
            . ' JOIN bizonylatfej bf ON (_xx.hivatkozottbizonylat=bf.id)'
            . $this->getFilterString($filter)
            . $this->getOrderString($order)
            ,
            $rsm
        );
        $q->setParameters($this->getQueryParameters($filter));
        return $q->getResult();
    }
    
    public function getAllWithFej($filter = [], $order = [])
    {
        $q = $this->_em->createQuery(
            'SELECT _xx '
            . ' FROM Entities\penztarbizonylattetel _xx'
            . ' LEFT JOIN _xx.bizonylatfej bf'
            . $this->getFilterString($filter)
            . $this->getOrderString($order)
        );
        $q->setParameters($this->getQueryParameters($filter));
        return $q->getResult();
    }
}
