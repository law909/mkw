<?php

namespace Entities;

class DolgozoberRepository extends \mkwhelpers\Repository
{

    public function __construct($em, \Doctrine\ORM\Mapping\ClassMetadata $class)
    {
        parent::__construct($em, $class);
        $this->setEntityname(Dolgozober::class);
        $this->setOrders([
            '1' => ['caption' => 'dátum szerint csökkenő', 'order' => ['_xx.datum' => 'DESC', 'd.nev' => 'ASC', '_xx.id' => 'DESC']],
            '2' => ['caption' => 'dátum szerint növekvő', 'order' => ['_xx.datum' => 'ASC', 'd.nev' => 'ASC', '_xx.id' => 'ASC']],
            '3' => ['caption' => 'dolgozó és dátum szerint', 'order' => ['d.nev' => 'ASC', '_xx.datum' => 'ASC', '_xx.id' => 'ASC']],
        ]);
    }

    public function getWithJoins($filter, $order, $offset = 0, $elemcount = 0): mixed
    {
        $q = $this->_em->createQuery(
            'SELECT _xx,d,j'
            . ' FROM Entities\Dolgozober _xx'
            . ' LEFT JOIN _xx.dolgozo d'
            . ' LEFT JOIN _xx.berjogcim j'
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
            . ' FROM Entities\Dolgozober _xx'
            . ' LEFT JOIN _xx.dolgozo d'
            . ' LEFT JOIN _xx.berjogcim j'
            . $this->getFilterString($filter)
        );
        $q->setParameters($this->getQueryParameters($filter));
        return $q->getSingleScalarResult();
    }

    /** The sum of the filtered lines, voided ones never count. */
    public function getOsszeg(\mkwhelpers\FilterDescriptor $filter)
    {
        $filter = clone $filter;
        $filter->addFilter('rontott', '=', false);
        $q = $this->_em->createQuery(
            'SELECT SUM(_xx.osszeg)'
            . ' FROM Entities\Dolgozober _xx'
            . ' LEFT JOIN _xx.dolgozo d'
            . ' LEFT JOIN _xx.berjogcim j'
            . $this->getFilterString($filter)
        );
        $q->setParameters($this->getQueryParameters($filter));
        return (float)$q->getSingleScalarResult();
    }

    /**
     * Paid amounts summed by the grouping, voided lines left out.
     *
     * @param array $csoport any of 'ev', 'honap' (exclusive), 'dolgozo', 'berjogcim'
     */
    public function getKimutatas($datumtol, $datumig, array $csoport): array
    {
        $select = [];
        $group = [];
        $order = [];
        if (in_array('ev', $csoport, true) || in_array('honap', $csoport, true)) {
            $select[] = "DATE_FORMAT(b.datum, '" . (in_array('ev', $csoport, true) ? '%Y' : '%Y-%m') . "') AS idoszak";
            $group[] = 'idoszak';
            $order[] = 'idoszak';
        }
        if (in_array('dolgozo', $csoport, true)) {
            $select[] = 'b.dolgozo_id AS dolgozoid';
            $select[] = 'MAX(d.nev) AS dolgozonev';
            $group[] = 'dolgozoid';
            $order = array_merge($order, ['dolgozonev', 'dolgozoid']);
        }
        if (in_array('berjogcim', $csoport, true)) {
            $select[] = 'b.berjogcim_id AS berjogcimid';
            $select[] = 'MAX(j.nev) AS berjogcimnev';
            $group[] = 'berjogcimid';
            $order = array_merge($order, ['berjogcimnev', 'berjogcimid']);
        }
        $select[] = 'SUM(b.osszeg) AS ertek';

        $where = ['b.rontott = 0'];
        $params = [];
        if ($datumtol) {
            $where[] = 'b.datum >= ?';
            $params[] = \mkw\store::convDate($datumtol);
        }
        if ($datumig) {
            $where[] = 'b.datum <= ?';
            $params[] = \mkw\store::convDate($datumig);
        }

        $rows = $this->_em->getConnection()->fetchAllAssociative(
            'SELECT ' . implode(',', $select)
            . ' FROM dolgozober b'
            . ' INNER JOIN dolgozo d ON (b.dolgozo_id = d.id)'
            . ' INNER JOIN berjogcim j ON (b.berjogcim_id = j.id)'
            . ' WHERE ' . implode(' AND ', $where)
            . ($group ? ' GROUP BY ' . implode(',', $group) : '')
            . ($order ? ' ORDER BY ' . implode(',', $order) : ''),
            $params
        );
        foreach ($rows as &$row) {
            $row['ertek'] = round((float)$row['ertek'], 2);
        }
        return $rows;
    }
}
