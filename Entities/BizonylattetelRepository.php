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

    public function getWithJoins($filter, $order, $offset = 0, $elemcount = 0): mixed
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

    /**
     * Bizonylattételeken szereplő egyedi azonosítók termékkel együtt, a teljes törzsből – a
     * munkalap fejében ebből választható ki a gép/gépjármű. Ugyanaz az azonosító több tételen is
     * szerepel (bevét, majd eladás), de ugyanarra a termékre, ezért a legkisebb tételazonosítójú
     * sor termékét adjuk vissza.
     *
     * @return array<int,array{azonosito:string,termekid:int,termeknev:string,cikkszam:string}>
     */
    public function searchEgyediazonosito($term, $termekid = null, $limit = 20)
    {
        $dql = 'SELECT bt.termekegyediazonosito AS azonosito, MIN(bt.id) AS elsotetel'
            . ' FROM Entities\Bizonylattetel bt'
            . ' WHERE bt.termekegyediazonosito IS NOT NULL'
            . " AND bt.termekegyediazonosito <> ''"
            . ' AND bt.termekegyediazonosito LIKE :term';
        $params = ['term' => '%' . $term . '%'];
        if ($termekid) {
            $dql .= ' AND bt.termek = :termekid';
            $params['termekid'] = $termekid;
        }
        $dql .= ' GROUP BY bt.termekegyediazonosito'
            . ' ORDER BY bt.termekegyediazonosito ASC';
        $q = $this->_em->createQuery($dql);
        $q->setParameters($params);
        if ($limit > 0) {
            $q->setMaxResults($limit);
        }
        $ret = [];
        foreach ($q->getScalarResult() as $r) {
            $sor = $this->egyediazonositoAdat($this->find($r['elsotetel']));
            if ($sor) {
                $ret[] = $sor;
            }
        }
        return $ret;
    }

    /**
     * Egy egyedi azonosító gépadatai a bizonylattételéből: termék, változat, cikkszám.
     *
     * @param \Entities\Bizonylattetel|null $tetel
     *
     * @return array{azonosito:string,termekid:int,termeknev:string,cikkszam:string,valtozatid:int|string,valtozatnev:string}|null
     */
    public function egyediazonositoAdat($tetel)
    {
        $termek = $tetel?->getTermek();
        if (!$termek) {
            return null;
        }
        $valtozat = $tetel->getTermekvaltozat();
        return [
            'azonosito' => $tetel->getTermekegyediazonosito(),
            'termekid' => $termek->getId(),
            'termeknev' => $termek->getNev(),
            'cikkszam' => $termek->getCikkszam(),
            'valtozatid' => $valtozat ? $valtozat->getId() : '',
            'valtozatnev' => $valtozat ? $valtozat->getNev() : '',
        ];
    }

    /**
     * Egy egyedi azonosítóhoz tartozó termék. Az azonosító több tételen is szerepelhet, de
     * ugyanarra a termékre – az elsőt vesszük.
     *
     * @return \Entities\Termek|null
     */
    public function findTermekByEgyediazonosito($azonosito)
    {
        return $this->findByEgyediazonosito($azonosito)?->getTermek();
    }

    /**
     * Az egyedi azonosítót viselő (első) bizonylattétel.
     *
     * @return \Entities\Bizonylattetel|null
     */
    public function findByEgyediazonosito($azonosito)
    {
        return $this->findOneBy(['termekegyediazonosito' => $azonosito]);
    }

    /**
     * Az a bizonylattétel, amelyikről a példány gazdája (a munkalap partnere) olvasható. Ugyanaz az
     * azonosító a bevéten (szállító) és az eladáson (vevő) is szerepel, ezért a kimenő irányú
     * bizonylatok mennek előre, azok közül a legutolsó. Rontott, stornó és stornózott bizonylat
     * nem jöhet szóba.
     *
     * @return \Entities\Bizonylattetel|null
     */
    public function findOwnerBizonylattetel($azonosito)
    {
        $q = $this->_em->createQuery(
            'SELECT bt FROM Entities\Bizonylattetel bt'
            . ' JOIN bt.bizonylatfej bf'
            . ' WHERE bt.termekegyediazonosito = :azonosito'
            . ' AND bf.rontott = false AND bf.storno = false AND bf.stornozott = false'
            . ' AND bf.partner IS NOT NULL'
            . ' ORDER BY bf.irany ASC, bf.kelt DESC, bt.id DESC'
        );
        $q->setParameter('azonosito', $azonosito);
        $q->setMaxResults(1);
        $res = $q->getResult();
        return $res ? $res[0] : null;
    }

}