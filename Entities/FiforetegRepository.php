<?php

namespace Entities;

use Doctrine\ORM\Query\ResultSetMapping;

class FiforetegRepository extends \mkwhelpers\Repository
{

    public function __construct($em, \Doctrine\ORM\Mapping\ClassMetadata $class)
    {
        parent::__construct($em, $class);
        $this->setEntityname(Fiforeteg::class);
    }

    /**
     * A megadott csoportok rétegbontása, csoportonként a legrégebbi bevéttel kezdve –
     * ez a Készletérték kimutatás második szintje.
     *
     * @param array $csoportok [raktarid, termekid, valtozatid] hármasok
     *
     * @return array soronként raktarid/termekid/valtozatid/bizonylatszam/teljesites/mennyiseg/egysegar/becsult
     */
    public function getRetegek(array $csoportok)
    {
        if (!$csoportok) {
            return [];
        }
        $felt = [];
        foreach ($csoportok as [$raktarid, $termekid, $valtozatid]) {
            $felt[] = '(r.raktar_id <=> ' . (int)$raktarid
                . ' AND r.termek_id <=> ' . (int)$termekid
                . ' AND r.termekvaltozat_id <=> ' . ($valtozatid ? (int)$valtozatid : 'NULL') . ')';
        }

        $rsm = new ResultSetMapping();
        foreach (['raktarid', 'termekid', 'valtozatid', 'bizonylatszam', 'teljesites', 'mennyiseg', 'egysegar', 'becsult'] as $mezo) {
            $rsm->addScalarResult($mezo, $mezo);
        }

        return $this->_em->createNativeQuery(
            'SELECT r.raktar_id AS raktarid, r.termek_id AS termekid, r.termekvaltozat_id AS valtozatid,'
            . ' r.bebizonylatfej_id AS bizonylatszam, r.teljesites, r.mennyiseg, r.egysegar, r.becsult'
            . ' FROM fiforeteg r'
            . ' WHERE ' . implode(' OR ', $felt)
            . ' ORDER BY r.termek_id, r.termekvaltozat_id, r.raktar_id, r.teljesites, r.id',
            $rsm
        )->getScalarResult();
    }

}
