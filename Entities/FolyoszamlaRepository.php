<?php

namespace Entities;

use Doctrine\ORM\Query\ResultSetMapping;
use mkwhelpers\FilterDescriptor;

class FolyoszamlaRepository extends \mkwhelpers\Repository
{

    public function __construct($em, \Doctrine\ORM\Mapping\ClassMetadata $class)
    {
        parent::__construct($em, $class);
        $this->setEntityname(Folyoszamla::class);
    }

    public function getAllByHivatkozottBizonylat($bizszam)
    {
        $filter = new FilterDescriptor();
        $filter->addFilter('hivatkozottbizonylat', '=', $bizszam);
        $filter->addFilter('rontott', '=', false);

        $q = $this->_em->createQuery(
            'SELECT _xx, f, bankfej, banktetel, penztarfej, penztartetel'
            . ' FROM Entities\Folyoszamla _xx'
            . ' LEFT JOIN _xx.fizmod f'
            . ' LEFT JOIN _xx.bankbizonylatfej bankfej'
            . ' LEFT JOIN _xx.bankbizonylattetel banktetel'
            . ' LEFT JOIN _xx.penztarbizonylatfej penztarfej'
            . ' LEFT JOIN _xx.penztarbizonylattetel penztartetel'
            . $this->getFilterString($filter)
        );
        $q->setParameters($this->getQueryParameters($filter));
        return $q->getResult();
    }

    public function getBefizetesByHivatkozottBizonylat($bizszam)
    {
        $filter = new FilterDescriptor();
        $filter->addFilter('hivatkozottbizonylat', '=', $bizszam);
        $filter->addFilter('rontott', '=', false);
        $filter->addSql('_xx.bizonylatfej IS NULL');

        $q = $this->_em->createQuery(
            'SELECT _xx, f, bankfej, banktetel, penztarfej, penztartetel'
            . ' FROM Entities\Folyoszamla _xx'
            . ' LEFT JOIN _xx.fizmod f'
            . ' LEFT JOIN _xx.bankbizonylatfej bankfej'
            . ' LEFT JOIN _xx.bankbizonylattetel banktetel'
            . ' LEFT JOIN _xx.penztarbizonylatfej penztarfej'
            . ' LEFT JOIN _xx.penztarbizonylattetel penztartetel'
            . $this->getFilterString($filter)
        );
        $q->setParameters($this->getQueryParameters($filter));
        return $q->getResult();
    }

    public function getSumByHivatkozottBizonylat($bizszam)
    {
        $filter = new FilterDescriptor();
        $filter->addFilter('hivatkozottbizonylat', '=', $bizszam);
        $filter->addFilter('rontott', '=', false);

        $q = $this->_em->createQuery(
            'SELECT SUM(_xx.brutto * _xx.irany)'
            . ' FROM Entities\Folyoszamla _xx'
            . $this->getFilterString($filter)
        );
        $q->setParameters($this->getQueryParameters($filter));
        return $q->getSingleScalarResult();
    }

    public function getCountByHivatkozottBizonylat($bizszam)
    {
        $filter = new FilterDescriptor();
        $filter->addFilter('hivatkozottbizonylat', '=', $bizszam);
        $filter->addFilter('rontott', '=', false);
        $filter->addSql('_xx.bizonylatfej IS NULL');

        $q = $this->_em->createQuery(
            'SELECT COUNT(_xx)'
            . ' FROM Entities\Folyoszamla _xx'
            . $this->getFilterString($filter)
        );
        $q->setParameters($this->getQueryParameters($filter));
        return $q->getSingleScalarResult();
    }

    public function getSumByHivatkozottBizonylatDatum($bizszam)
    {
        $filter = new FilterDescriptor();
        $filter->addFilter('hivatkozottbizonylat', '=', $bizszam);
        $filter->addFilter('rontott', '=', false);

        $q = $this->_em->createQuery(
            'SELECT _xx.hivatkozottdatum,SUM(_xx.brutto * _xx.irany) AS egyenleg'
            . ' FROM Entities\Folyoszamla _xx'
            . $this->getFilterString($filter)
            . ' GROUP BY _xx.hivatkozottdatum'
            . ' ORDER BY _xx.hivatkozottdatum'
        );
        $q->setParameters($this->getQueryParameters($filter));
        return $q->getResult();
    }

    /**
     * A partner nyitott tételei bizonylatonként. Az oldalt (kintlevőség / tartozás) a csoport
     * NETTÓ egyenlegének előjele dönti el, nem a soronkénti irány: egy negatív összegű bizonylat
     * (stornó, jóváíró) és a hozzá tartozó pénzmozgás így egy csoportba kerül, és kinullázza
     * egymást. A stornó és stornózott sorok bent maradnak – csak a rontott esik ki.
     *
     * @param mixed $partnerid
     * @param int $irany 1: kintlevőség (a partner tartozik), -1: tartozás (mi tartozunk)
     */
    public function getSumByPartner($partnerid, $irany)
    {
        $filter = new FilterDescriptor();
        $filter
            ->addFilter('partner', '=', $partnerid)
            ->addFilter('rontott', '=', false)
            ->addSql('((_xx.bizonylatfej IS NULL) OR (_xx.bizonylatfej=_xx.hivatkozottbizonylat))');
        switch ($irany) {
            case 1:
                $having = ' HAVING egyenleg>0';
                break;
            case -1:
                $having = ' HAVING egyenleg<0';
                break;
            default:
                $having = ' HAVING egyenleg<>0';
        }

        $q = $this->_em->createQuery(
            'SELECT _xx.hivatkozottbizonylat,_xx.hivatkozottdatum,SUM(_xx.brutto * _xx.irany) AS egyenleg, fm.nev AS fizmodnev'
            . ' FROM Entities\Folyoszamla _xx'
            . ' LEFT JOIN _xx.fizmod fm'
            . $this->getFilterString($filter)
            . ' GROUP BY _xx.hivatkozottbizonylat,_xx.hivatkozottdatum,fm.nev'
            . $having
            . ' ORDER BY _xx.hivatkozottdatum'
        );
        $q->setParameters($this->getQueryParameters($filter));
        return $q->getResult();
    }

    public function getLejartKintlevosegByValutanem($cimkek = null)
    {
        $rsm = new ResultSetMapping();
        $rsm->addScalarResult('nev', 'nev');
        $rsm->addScalarResult('egyenleg', 'egyenleg');
        $sql = 'SELECT v.nev,SUM(egyenleg) AS egyenleg FROM (' . $this->getEgyenlegSql($cimkek) . ') AS egyen'
            . ' LEFT JOIN valutanem v ON (egyen.valutanem_id=v.id)'
            . ' WHERE (egyen.egyenleg>0) AND (egyen.hivatkozottdatum<CURDATE())'
            . ' GROUP BY v.nev';
        $q = $this->_em->createNativeQuery($sql, $rsm);
        return $q->getScalarResult();
    }

    /**
     * Bizonylatonkénti (és esedékességenkénti) nettó egyenleg – a kintlevőség aggregátumok közös
     * belső lekérdezése. Csak a rontott sorok maradnak ki; hogy egy csoport kintlevőség-e vagy
     * tartozás, azt a nettó egyenleg előjele dönti el, nem a soronkénti irány.
     *
     * @param array|null $cimkek partnercímke szűrő
     */
    private function getEgyenlegSql($cimkek = null)
    {
        $join = '';
        if ($cimkek) {
            $join = ' JOIN partner_cimkek pc ON (f.partner_id=pc.partner_id) AND (pc.cimketorzs_id IN ('
                . \mkw\store::getCommaList($cimkek) . '))';
        }
        return ' SELECT valutanem_id,hivatkozottbizonylat,hivatkozottdatum,SUM(brutto*irany) AS egyenleg'
            . ' FROM folyoszamla f'
            . $join
            . ' WHERE (rontott=0) AND ((bizonylatfej_id IS NULL) OR (bizonylatfej_id=hivatkozottbizonylat))'
            . ' GROUP BY valutanem_id,hivatkozottbizonylat,hivatkozottdatum';
    }

    public function getFakeKintlevosegByValutanem($cimkek = null)
    {
        $filter = new FilterDescriptor();

        if ($cimkek) {
            $filter->addJoin('JOIN partner_cimkek pc ON (bf.partner_id=pc.partner_id) AND (pc.cimketorzs_id IN (' . \mkw\store::getCommaList($cimkek) . '))');
        }

        $filter
            ->addFilter('bf.irany', '<', 0)
            ->addFilter('bf.rontott', '=', false)
            ->addFilter('bf.storno', '=', false)
            ->addFilter('bf.stornozott', '=', false)
            ->addFilter('bf.fakekintlevoseg', '=', true)
            ->addFilter('bf.fakekifizetve', '=', false);

        $rsm = new ResultSetMapping();
        $rsm->addScalarResult('nev', 'nev');
        $rsm->addScalarResult('egyenleg', 'egyenleg');
        $q = $this->_em->createNativeQuery(
            'SELECT bf.valutanemnev AS nev, SUM(bf.brutto) AS egyenleg'
            . ' FROM bizonylatfej bf'
            . $filter->getFilterString('', 'par')
            . ' GROUP BY bf.valutanemnev'
            ,
            $rsm
        );
        $q->setParameters($filter->getQueryParameters('par'));
        return $q->getScalarResult();
    }

    public function getKintlevosegByValutanem($cimkek = null)
    {
        $rsm = new ResultSetMapping();
        $rsm->addScalarResult('nev', 'nev');
        $rsm->addScalarResult('egyenleg', 'egyenleg');
        $sql = 'SELECT v.nev,SUM(egyenleg) AS egyenleg FROM (' . $this->getEgyenlegSql($cimkek) . ') AS egyen'
            . ' LEFT JOIN valutanem v ON (egyen.valutanem_id=v.id)'
            . ' WHERE egyen.egyenleg>0'
            . ' GROUP BY v.nev';
        $q = $this->_em->createNativeQuery($sql, $rsm);
        return $q->getScalarResult();
    }
}