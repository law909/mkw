<?php

namespace Entities;

class TermekMinkeszletRepository extends \mkwhelpers\Repository
{

    public function __construct($em, \Doctrine\ORM\Mapping\ClassMetadata $class)
    {
        parent::__construct($em, $class);
        $this->setEntityname('Entities\TermekMinkeszlet');
    }

    /**
     * Kötegelt olvasás a feloldó service-nek.
     *
     * @param int[] $termekids
     * @param int|null $raktarid csak az adott raktár sorai
     *
     * @return array [ termek_id => [ raktar_id => érték ] ]
     */
    public function getByTermekIds(array $termekids, $raktarid = null): array
    {
        $ret = [];
        $ids = array_values(array_unique(array_map('intval', array_filter($termekids))));
        if (!$ids) {
            return $ret;
        }
        $dql = 'SELECT IDENTITY(_xx.termek) AS termekid, IDENTITY(_xx.raktar) AS raktarid,'
            . ' _xx.minkeszlet AS ertek'
            . ' FROM Entities\TermekMinkeszlet _xx'
            . ' JOIN _xx.termek t'
            . ' WHERE t.id IN (:ids)';
        if ($raktarid) {
            $dql .= ' AND _xx.raktar = :raktarid';
        }
        $q = $this->_em->createQuery($dql);
        $q->setParameter('ids', $ids);
        if ($raktarid) {
            $q->setParameter('raktarid', $raktarid);
        }
        foreach ($q->getScalarResult() as $sor) {
            $ret[(int)$sor['termekid']][(int)$sor['raktarid']] = $sor['ertek'];
        }
        return $ret;
    }

    /**
     * Egy termék összes raktáras sora a termékszerkesztő mátrixához.
     *
     * @return array [ raktar_id => \Entities\TermekMinkeszlet ]
     */
    public function getRowsByTermek($termekid): array
    {
        $ret = [];
        if (!$termekid) {
            return $ret;
        }
        $filter = new \mkwhelpers\FilterDescriptor();
        $filter->addFilter('termek', '=', $termekid);
        /** @var TermekMinkeszlet $sor */
        foreach ($this->getAll($filter, []) as $sor) {
            $ret[$sor->getRaktarId()] = $sor;
        }
        return $ret;
    }

}
