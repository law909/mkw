<?php

namespace Entities;

class TermekValtozatArRepository extends \mkwhelpers\Repository
{

    public function __construct($em, \Doctrine\ORM\Mapping\ClassMetadata $class)
    {
        parent::__construct($em, $class);
        $this->setEntityname(TermekValtozatAr::class);
    }

    /** @return TermekValtozatAr[] */
    public function getByValtozat($valtozat)
    {
        return $this->findBy(['termekvaltozat' => $valtozat]);
    }

    /**
     * The variants that have a price of their own in any band, per product.
     *
     * @param int[] $termekids
     * @return array [termek_id => TermekValtozat[]]
     */
    public function getSajatArasValtozatok(array $termekids): array
    {
        $ids = array_values(array_unique(array_filter(array_map('intval', $termekids))));
        if (!$ids) {
            return [];
        }
        $valtozatok = $this->_em->createQuery(
            'SELECT v FROM Entities\TermekValtozat v WHERE IDENTITY(v.termek) IN (:ids) AND v.id IN'
            . ' (SELECT IDENTITY(va.termekvaltozat) FROM Entities\TermekValtozatAr va WHERE va.netto <> 0 OR va.brutto <> 0)'
            . ' ORDER BY v.id ASC'
        )
            ->setParameter('ids', $ids)
            ->getResult();
        $result = [];
        foreach ($valtozatok as $v) {
            $result[$v->getTermek()->getId()][] = $v;
        }
        return $result;
    }

    /**
     * The variant's own price in the band, or with no band the first default band it has a price in
     * (same order as TermekArRepository::getArsavAr). Rows without a price don't count.
     */
    public function getArsavAr($valtozat, $valutanem = null, $arsav = null): ?TermekValtozatAr
    {
        $id = is_object($valtozat) ? $valtozat->getId() : (int)$valtozat;
        return $id ? ($this->getArsavArByValtozat([$id], $valutanem, $arsav)[$id] ?? null) : null;
    }

    /**
     * @param int[] $valtozatids
     * @return array [termekvaltozat_id => TermekValtozatAr]
     */
    public function getArsavArByValtozat(array $valtozatids, $valutanem = null, $arsav = null): array
    {
        $ids = array_values(array_unique(array_filter(array_map('intval', $valtozatids))));
        $arsavids = $arsav
            ? [(int)(is_object($arsav) ? $arsav->getId() : $arsav)]
            : array_values(array_filter(TermekArRepository::getDefaultArsavIds()));
        $valutanemid = (int)(is_object($valutanem) ? $valutanem->getId() : ($valutanem ?: \mkw\store::getParameter(\mkw\consts::Valutanem)));
        if (!$ids || !$arsavids || !$valutanemid) {
            return [];
        }
        $rows = $this->_em->createQuery(
            'SELECT _xx, v FROM Entities\TermekValtozatAr _xx JOIN _xx.termekvaltozat v'
            . ' WHERE v.id IN (:ids) AND IDENTITY(_xx.valutanem) = :valutanem AND IDENTITY(_xx.arsav) IN (:arsavok)'
            . ' ORDER BY _xx.id ASC'
        )
            ->setParameters(['ids' => $ids, 'valutanem' => $valutanemid, 'arsavok' => $arsavids])
            ->getResult();
        $bySav = [];
        /** @var TermekValtozatAr $row */
        foreach ($rows as $row) {
            if ($row->hasPrice()) {
                $bySav[$row->getTermekvaltozat()->getId()][$row->getArsavId()] ??= $row;
            }
        }
        $result = [];
        foreach ($bySav as $vid => $savok) {
            foreach ($arsavids as $sid) {
                if (isset($savok[$sid])) {
                    $result[$vid] = $savok[$sid];
                    break;
                }
            }
        }
        return $result;
    }
}
