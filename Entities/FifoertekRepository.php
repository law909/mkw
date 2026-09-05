<?php

namespace Entities;

class FifoertekRepository extends \mkwhelpers\Repository
{

    public function __construct($em, \Doctrine\ORM\Mapping\ClassMetadata $class)
    {
        parent::__construct($em, $class);
        $this->setEntityname(Fifoertek::class);
    }

    /**
     * Egy termék FIFO készletértéke raktáranként és változatonként – a termék karbantartó
     * árak fülének. A nulla és a fedezetlen csoportot nem mutatjuk, azoknak nincs értékük.
     *
     * @return array raktarnev / valtozatnev / mennyiseg / egysegertek / ertek / becsult
     */
    public function getTermekRaktarak($termekid)
    {
        return $this->_em->getConnection()->fetchAllAssociative(
            'SELECT COALESCE(r.nev, ?) AS raktarnev,'
            . ' TRIM(CONCAT(COALESCE(tv.ertek1, ""), " ", COALESCE(tv.ertek2, ""))) AS valtozatnev,'
            . ' f.mennyiseg, f.egysegertek, f.ertek, f.becsult'
            . ' FROM fifoertek f'
            . ' LEFT JOIN raktar r ON (r.id = f.raktar_id)'
            . ' LEFT JOIN termekvaltozat tv ON (tv.id = f.termekvaltozat_id)'
            . ' WHERE f.termek_id = ? AND f.mennyiseg > 0'
            . ' ORDER BY r.nev, tv.ertek1, tv.ertek2',
            [t('Nincs raktár'), (int)$termekid]
        );
    }

    /** A legutolsó FIFO számítás időpontja, vagy null, ha még sosem futott. */
    public function getUtolsoSzamitas()
    {
        return $this->_em->getConnection()->fetchOne('SELECT MAX(szamitva) FROM fifoertek') ?: null;
    }

}
