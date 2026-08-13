<?php

namespace Entities;

class TermekValtozatMinkeszletRepository extends \mkwhelpers\Repository
{

    public function __construct($em, \Doctrine\ORM\Mapping\ClassMetadata $class)
    {
        parent::__construct($em, $class);
        $this->setEntityname('Entities\TermekValtozatMinkeszlet');
    }

    /**
     * Kötegelt olvasás a feloldó service-nek.
     *
     * @param int[] $valtozatids
     * @param int|null $raktarid csak az adott raktár sorai
     *
     * @return array [ termekvaltozat_id => [ raktar_id => érték ] ]
     */
    public function getByTermekValtozatIds(array $valtozatids, $raktarid = null): array
    {
        $ret = [];
        $ids = array_values(array_unique(array_map('intval', array_filter($valtozatids))));
        if (!$ids) {
            return $ret;
        }
        $dql = 'SELECT IDENTITY(_xx.termekvaltozat) AS valtozatid, IDENTITY(_xx.raktar) AS raktarid,'
            . ' _xx.minkeszlet AS ertek'
            . ' FROM Entities\TermekValtozatMinkeszlet _xx'
            . ' JOIN _xx.termekvaltozat v'
            . ' WHERE v.id IN (:ids)';
        if ($raktarid) {
            $dql .= ' AND _xx.raktar = :raktarid';
        }
        $q = $this->_em->createQuery($dql);
        $q->setParameter('ids', $ids);
        if ($raktarid) {
            $q->setParameter('raktarid', $raktarid);
        }
        foreach ($q->getScalarResult() as $sor) {
            $ret[(int)$sor['valtozatid']][(int)$sor['raktarid']] = $sor['ertek'];
        }
        return $ret;
    }

    /**
     * Egy termék összes változatának raktáras sora egyetlen lekérdezéssel – a
     * termékszerkesztő mátrixához, ahol változatonként külön kérdezni N+1 lenne.
     *
     * @param int[] $valtozatids
     *
     * @return array [ termekvaltozat_id => [ raktar_id => \Entities\TermekValtozatMinkeszlet ] ]
     */
    public function getRowsByTermekValtozatIds(array $valtozatids): array
    {
        $ret = [];
        $ids = array_values(array_unique(array_map('intval', array_filter($valtozatids))));
        if (!$ids) {
            return $ret;
        }
        $q = $this->_em->createQuery(
            'SELECT _xx FROM Entities\TermekValtozatMinkeszlet _xx'
            . ' JOIN _xx.termekvaltozat v'
            . ' WHERE v.id IN (:ids)'
        );
        $q->setParameter('ids', $ids);
        /** @var TermekValtozatMinkeszlet $sor */
        foreach ($q->getResult() as $sor) {
            $ret[$sor->getTermekvaltozatId()][$sor->getRaktarId()] = $sor;
        }
        return $ret;
    }

}
