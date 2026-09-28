<?php

namespace Entities;

use mkwhelpers\FilterDescriptor;
use Doctrine\ORM\Query\ResultSetMapping;


class TermekValtozatRepository extends \mkwhelpers\Repository
{

    public function __construct($em, \Doctrine\ORM\Mapping\ClassMetadata $class)
    {
        parent::__construct($em, $class);
        $this->setEntityname(TermekValtozat::class);
        $this->setOrders([
            '1' => ['caption' => 'név szerint növekvő', 'order' => ['_xx.nev' => 'ASC']]
        ]);
    }

    public function getWithJoins($filter, $order, $offset = 0, $elemcount = 0): mixed
    {
        $q = $this->_em->createQuery(
            'SELECT _xx '
            . 'FROM Entities\TermekValtozat _xx '
            . 'JOIN _xx.szin sz '
            . 'JOIN _xx.meret m '
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
     * A megadott termekekhez visszaadja azt a valtozatot, amelynek cikkszama a keresett
     * szovegre konkretan illeszkedik. Pontos cikkszam-egyezes elsobbseget elvez; ha az adott
     * termeknel nincs pontos talalat es tobb valtozat is csak reszben illeszkedik, akkor ahhoz
     * a termekhez nem adunk vissza valtozatot (a felhasznalo valasszon a valtozat selectbol).
     *
     * @param int[] $termekids
     * @param string $keresett
     *
     * @return array  termekid => valtozatid
     */
    /**
     * A megadott termékek változatainak azonosítói – a készlet kötegelt betöltéséhez
     * (\Services\KeszletService::preloadStock()) kell, még a változatok hidratálása előtt.
     *
     * @return int[]
     */
    public function getIdsByTermekIds($termekids)
    {
        if (!$termekids) {
            return [];
        }
        $q = $this->_em->createQuery(
            'SELECT v.id AS id FROM Entities\TermekValtozat v WHERE IDENTITY(v.termek) IN (:ids)'
        );
        $q->setParameter('ids', $termekids);
        $ret = [];
        foreach ($q->getScalarResult() as $sor) {
            $ret[] = (int)$sor['id'];
        }
        return $ret;
    }

    /** @return array<int,array<int,array{valtozatid:int,termekid:int,cikkszam:string}>> variants whose cikkszam contains the term, by termek */
    public function getCikkszamMatches($termekids, $keresett)
    {
        $byTermek = [];
        if (!$termekids || ((string)$keresett === '')) {
            return $byTermek;
        }
        $q = $this->_em->createQuery(
            'SELECT v.id AS valtozatid, IDENTITY(v.termek) AS termekid, v.cikkszam AS cikkszam'
            . ' FROM Entities\TermekValtozat v'
            . ' WHERE IDENTITY(v.termek) IN (:ids) AND v.cikkszam LIKE :keresett'
            . ' ORDER BY v.cikkszam ASC'
        );
        $q->setParameter('ids', $termekids);
        $q->setParameter('keresett', '%' . $keresett . '%');
        foreach ($q->getScalarResult() as $row) {
            $byTermek[$row['termekid']][] = $row;
        }
        return $byTermek;
    }

    /**
     * Product autocomplete row labels: a product without variants shows its cikkszam, one with variants
     * only its name (its own cikkszam identifies no variant), followed by the variant codes matching the term.
     *
     * @param array<int,array{nev:string,cikkszam:?string}> $termekek by termek id
     * @param array|null $matches getCikkszamMatches() of the same ids, when the caller already has it
     *
     * @return array<int,string> by termek id
     */
    public function getAutocompleteLabels(array $termekek, $keresett, $matches = null)
    {
        $ids = array_keys($termekek);
        $matches ??= $this->getCikkszamMatches($ids, $keresett);
        $valtozatos = [];
        if ($ids) {
            $q = $this->_em->createQuery(
                'SELECT DISTINCT IDENTITY(v.termek) AS termekid FROM Entities\TermekValtozat v WHERE IDENTITY(v.termek) IN (:ids)'
            );
            $q->setParameter('ids', $ids);
            $valtozatos = array_flip(array_column($q->getScalarResult(), 'termekid'));
        }
        $ret = [];
        foreach ($termekek as $id => $t) {
            $ret[$id] = (isset($valtozatos[$id]) ? $t['nev'] : trim($t['cikkszam'] . ' ' . $t['nev']))
                . self::cikkszamMatchLabel($matches[$id] ?? []);
        }
        return $ret;
    }

    /**
     * The autocomplete label suffix of a product row: the matching variants' cikkszam, so a hit on a
     * variant code is visible even though the list has one row per product.
     *
     * @param array<int,array{cikkszam:string}> $matches one product's rows from getCikkszamMatches()
     */
    private static function cikkszamMatchLabel($matches, $max = 5)
    {
        if (!$matches) {
            return '';
        }
        $kodok = array_column(array_slice($matches, 0, $max), 'cikkszam');
        return ' – ' . t('változat') . ': ' . implode(', ', $kodok) . (count($matches) > $max ? ', …' : '');
    }

    public function getCikkszamMatchMap($termekids, $keresett, $byTermek = null)
    {
        $ret = [];
        $byTermek ??= $this->getCikkszamMatches($termekids, $keresett);
        foreach ($byTermek as $termekid => $matches) {
            $valtozatid = null;
            foreach ($matches as $m) {
                if ((string)$m['cikkszam'] === (string)$keresett) {  // pontos cikkszam-talalat
                    $valtozatid = $m['valtozatid'];
                    break;
                }
            }
            if ($valtozatid === null && count($matches) === 1) {  // egyetlen reszleges talalat
                $valtozatid = $matches[0]['valtozatid'];
            }
            if ($valtozatid !== null) {
                $ret[$termekid] = $valtozatid;
            }
        }
        return $ret;
    }

    public function getByProperties($termekid, $adattipusok, $ertekek)
    {
        $filter = new FilterDescriptor();
        $filter->addFilter('termek', '=', $termekid);

        if (count($adattipusok) == 1) {
            if ($ertekek[0]) {
                $filter->addSql(
                    '((_xx.adattipus1=' . $adattipusok[0] . ') AND (_xx.ertek1=\'' . $ertekek[0] . '\') AND (_xx.adattipus2 IS NULL)) OR '
                    . '((_xx.adattipus2=' . $adattipusok[0] . ') AND (_xx.ertek2=\'' . $ertekek[0] . '\') AND (_xx.adattipus1 IS NULL))'
                );
            }
        } elseif (count($adattipusok) > 1) {
            if ($ertekek[0] || $ertekek[1]) {
                $stra = $strb = '(1=1)';
                if ($ertekek[0]) {
                    $stra = '((_xx.adattipus1=' . $adattipusok[0] . ') AND (_xx.ertek1=\'' . $ertekek[0] . '\')) OR ((_xx.adattipus2=' . $adattipusok[0] . ') AND (_xx.ertek2=\'' . $ertekek[0] . '\'))';
                }
                if ($ertekek[1]) {
                    $strb = '((_xx.adattipus2=' . $adattipusok[1] . ') AND (_xx.ertek2=\'' . $ertekek[1] . '\')) OR ((_xx.adattipus1=' . $adattipusok[1] . ') AND (_xx.ertek1=\'' . $ertekek[1] . '\'))';
                }
                $filter->addSql('((' . $stra . ') AND (' . $strb . '))');
            }
        }
        $res = $this->getAll($filter, []);
        return $res[0];
    }

    public function getByColorSize($termekid, $color, $size)
    {
        $at = [
            \mkw\store::getParameter(\mkw\consts::ValtozatTipusSzin),
            \mkw\store::getParameter(\mkw\consts::ValtozatTipusMeret)
        ];
        $ert = [
            $color,
            $size
        ];
        return $this->getByProperties($termekid, $at, $ert);
    }

    public function getSizesByColor($termekid, $color)
    {
        $filter = new FilterDescriptor();
        $filter->addFilter('termek', '=', $termekid);
        $filter->addFilter('szin', '=', $color);
        $ret = $this->getWithJoins($filter, ['sz.sorrend' => 'ASC', 'm.sorrend' => 'ASC']);
        return $ret;
    }

    public function getOtherProperties($termekid, $adattipusok, $ertekek)
    {
        $filter = new FilterDescriptor();
        $filter->addFilter('termek', '=', $termekid);

        if (count($adattipusok) == 1) {
            if ($ertekek[0]) {
                $filter->addSql(
                    '((_xx.adattipus1=' . $adattipusok[0] . ') AND (_xx.ertek1=\'' . $ertekek[0] . '\')) OR '
                    . '((_xx.adattipus2=' . $adattipusok[0] . ') AND (_xx.ertek2=\'' . $ertekek[0] . '\'))'
                );
            }
        }
        return $this->getAll($filter, []);
    }

    public function getDistinctErtek1()
    {
        $rsm = new ResultSetMapping();
        $rsm->addScalarResult('ertek1', 'ertek');
        $q = $this->_em->createNativeQuery('SELECT DISTINCT ertek1 FROM termekvaltozat', $rsm);
        return $q->getScalarResult();
    }

    public function getDistinctErtek2()
    {
        $rsm = new ResultSetMapping();
        $rsm->addScalarResult('ertek2', 'ertek');
        $q = $this->_em->createNativeQuery('SELECT DISTINCT ertek2 FROM termekvaltozat', $rsm);
        return $q->getScalarResult();
    }

    public function getTipusErtek()
    {
        $rsm = new ResultSetMapping();
        $rsm->addScalarResult('adattipus', 'adattipus');
        $rsm->addScalarResult('ertek', 'ertek');
        $q = $this->_em->createNativeQuery(
            '(SELECT adattipus1_id AS adattipus,ertek1 AS ertek FROM termekvaltozat) '
            . 'UNION (SELECT adattipus2_id AS adattipus,ertek2 AS ertek FROM termekvaltozat)',
            $rsm
        );
        return $q->getScalarResult();
    }

}