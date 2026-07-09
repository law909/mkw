<?php

namespace Entities;

class BizonylattetelRepository extends \mkwhelpers\Repository
{

    public function __construct($em, \Doctrine\ORM\Mapping\ClassMetadata $class)
    {
        parent::__construct($em, $class);
        $this->setEntityname(Bizonylattetel::class);
        $this->setOrders([
            '1' => ['caption' => 'biz.szám szerint', 'order' => ['_xx.id' => 'ASC']]
        ]);
    }

    public function getWithJoins($filter, $order, $offset = 0, $elemcount = 0)
    {
        $q = $this->_em->createQuery(
            'SELECT _xx'
            . ' FROM Entities\Bizonylattetel _xx'
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

    public function getCount($filter)
    {
        $q = $this->_em->createQuery(
            'SELECT COUNT(_xx)'
            . ' FROM Entities\Bizonylattetel _xx'
            . $this->getFilterString($filter)
        );
        return $q->getSingleScalarResult();
    }

    /**
     * A megadott termékhez (és változatához, ha van) tartozó, bizonylattételekben szereplő
     * egyedi azonosítók listája autocomplete-hez. Csak a $term-et tartalmazó, kitöltött
     * azonosítókat adja vissza, ABC sorrendben, duplikátumok nélkül.
     */
    public function getEgyediAzonositoLista($termekid, $valtozatid, $term, $limit = 0)
    {
        $dql = 'SELECT DISTINCT bt.termekegyediazonosito AS azonosito'
            . ' FROM Entities\Bizonylattetel bt'
            . ' WHERE bt.termek = :termekid'
            . ' AND bt.termekegyediazonosito IS NOT NULL'
            . " AND bt.termekegyediazonosito <> ''"
            . ' AND bt.termekegyediazonosito LIKE :term';
        $params = [
            'termekid' => $termekid,
            'term' => '%' . $term . '%',
        ];
        if ($valtozatid) {
            $dql .= ' AND bt.termekvaltozat = :valtozatid';
            $params['valtozatid'] = $valtozatid;
        }
        $dql .= ' ORDER BY bt.termekegyediazonosito ASC';
        $q = $this->_em->createQuery($dql);
        $q->setParameters($params);
        if ($limit > 0) {
            $q->setMaxResults($limit);
        }
        $ret = [];
        foreach ($q->getScalarResult() as $r) {
            $ret[] = $r['azonosito'];
        }
        return $ret;
    }

}