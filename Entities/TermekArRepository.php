<?php

namespace Entities;

use Doctrine\ORM\Query\ResultSetMapping;
use mkwhelpers\FilterDescriptor;

class TermekArRepository extends \mkwhelpers\Repository
{

    public function __construct($em, \Doctrine\ORM\Mapping\ClassMetadata $class)
    {
        parent::__construct($em, $class);
        $this->setEntityname('Entities\TermekAr');
        $this->setOrders([
            '1' => ['caption' => 'azonosító szerint növekvő', 'order' => ['_xx.azonosito' => 'ASC']]
        ]);
    }

    public function getByTermek($termek)
    {
        $filter = new FilterDescriptor();
        $filter->addFilter('termek', '=', $termek);
        return $this->getAll($filter, ['created' => 'ASC']);
    }

    // TODO: arsav
    public function getExistingArsavok()
    {
        $rsm = new ResultSetMapping();
        $rsm->addScalarResult('id', 'id');
        $rsm->addScalarResult('azonosito', 'azonosito');
        $rsm->addScalarResult('nev', 'valutanem');
        $rsm->addScalarResult('valutanem_id', 'valutanemid');
        $q = $this->_em->createNativeQuery(
            'SELECT DISTINCT a.id, v.nev, a.nev AS azonosito, valutanem_id '
            . 'FROM termekar t '
            . 'LEFT OUTER JOIN valutanem v ON (t.valutanem_id=v.id) '
            . 'LEFT OUTER JOIN arsav a ON (t.arsav_id=a.id) '
            . 'ORDER BY azonosito',
            $rsm
        );
        return $q->getScalarResult();
    }

    /**
     * The default price bands in priority order: very discounted, discounted, then the plain
     * default band. The last one is always the Arsav setting (may be 0 when it is not set).
     *
     * @return int[]
     */
    public static function getDefaultArsavIds(): array
    {
        $alap = (int)\mkw\store::getParameter(\mkw\consts::Arsav);
        $ids = [];
        foreach ([\mkw\consts::NagyonAkciosArsav, \mkw\consts::AkciosArsav] as $par) {
            $id = (int)\mkw\store::getParameter($par);
            if ($id && $id !== $alap && !in_array($id, $ids, true)) {
                $ids[] = $id;
            }
        }
        $ids[] = $alap;
        return $ids;
    }

    private static function hasPrice($termekar): bool
    {
        return $termekar && ((float)$termekar->getNetto() != 0 || (float)$termekar->getBrutto() != 0);
    }

    public function getArsavAr($termek, $valutanem = null, $arsav = null)
    {
        if (!$arsav) {
            // the discounted bands count only when the product has a price in them
            $defaults = self::getDefaultArsavIds();
            $alap = array_pop($defaults);
            foreach ($defaults as $id) {
                $ar = $this->getArsavAr($termek, $valutanem, $id);
                if (self::hasPrice($ar)) {
                    return $ar;
                }
            }
            $arsav = $alap;
        }
        if (!is_a($arsav, Arsav::class)) {
            $arsav = \mkw\store::getEm()->getRepository(Arsav::class)->find($arsav);
        }

        if (!$valutanem) {
            $valutanem = \mkw\store::getParameter(\mkw\consts::Valutanem);
        }
        if (!is_a($valutanem, Valutanem::class)) {
            $valutanem = \mkw\store::getEm()->getRepository(Valutanem::class)->find($valutanem);
        }

        $filter = new FilterDescriptor();
        $filter
            ->addFilter('termek', '=', $termek)
            ->addFilter('valutanem', '=', $valutanem)
            ->addFilter('arsav', '=', $arsav);

        $sav = $this->getAll($filter, []);
        if ($sav && is_array($sav)) {
            return $sav[0];
        }
        return $sav;
    }

    /**
     * Kötegelt ársávos ár sok termékre egyetlen lekérdezéssel — a getArsavAr() soronkénti
     * (N+1) hívása helyett (pl. a keszletlista számára). Termékenként az adott ársáv+valutanem
     * TermekAr sorát adja vissza. A getArsavAr null-feloldását (paraméter-alapértelmezés) tükrözi.
     *
     * @param int[] $termekids termék id-k
     * @param \Entities\Valutanem | int | null $valutanem
     * @param \Entities\Arsav | int | null $arsav
     *
     * @return array [ termek_id => \Entities\TermekAr ]
     */
    public function getArsavArByTermek(array $termekids, $valutanem = null, $arsav = null)
    {
        $result = [];

        if (!$arsav) {
            // same priority as getArsavAr(): a product takes the first discounted band it has a price in
            $defaults = self::getDefaultArsavIds();
            $alap = array_pop($defaults);
            foreach ($defaults as $id) {
                $hianyzo = array_diff(array_map('intval', array_filter($termekids)), array_keys($result));
                foreach ($this->getArsavArByTermek($hianyzo, $valutanem, $id) as $tid => $ar) {
                    if (self::hasPrice($ar)) {
                        $result[$tid] = $ar;
                    }
                }
            }
            $hianyzo = array_diff(array_map('intval', array_filter($termekids)), array_keys($result));
            return $alap ? $result + $this->getArsavArByTermek($hianyzo, $valutanem, $alap) : $result;
        }
        if (!is_a($arsav, Arsav::class)) {
            $arsav = \mkw\store::getEm()->getRepository(Arsav::class)->find($arsav);
        }
        if (!$valutanem) {
            $valutanem = \mkw\store::getParameter(\mkw\consts::Valutanem);
        }
        if (!is_a($valutanem, Valutanem::class)) {
            $valutanem = \mkw\store::getEm()->getRepository(Valutanem::class)->find($valutanem);
        }
        if (!$arsav || !$valutanem) {
            return $result;
        }

        $ids = array_values(array_unique(array_map('intval', array_filter($termekids))));
        if (empty($ids)) {
            return $result;
        }

        $q = $this->_em->createQuery(
            'SELECT _xx FROM Entities\TermekAr _xx'
            . ' JOIN _xx.termek t'
            . ' WHERE _xx.valutanem = :valutanem AND _xx.arsav = :arsav AND t.id IN (:ids)'
            . ' ORDER BY _xx.id ASC'
        );
        $q->setParameter('valutanem', $valutanem);
        $q->setParameter('arsav', $arsav);
        $q->setParameter('ids', $ids);
        foreach ($q->getResult() as $ta) {
            $tid = $ta->getTermek()->getId();
            // egy termékre több sor esetén az elsőt (legkisebb id) tartjuk meg, mint a getArsavAr()[0]
            if (!isset($result[$tid])) {
                $result[$tid] = $ta;
            }
        }

        return $result;
    }

}
