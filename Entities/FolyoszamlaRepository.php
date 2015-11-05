<?php

namespace Entities;

use Doctrine\ORM\Query\ResultSetMapping;

class FolyoszamlaRepository extends \mkwhelpers\Repository {

    public function __construct($em, \Doctrine\ORM\Mapping\ClassMetadata $class) {
        parent::__construct($em, $class);
        $this->setEntityname('Entities\Folyoszamla');
    }

    public function getSumByHivatkozottBizonylat($bizszam) {
        $a = $this->alias;
        $filter = array();
        $filter['fields'][] = 'hivatkozottbizonylat';
        $filter['clauses'][] = '=';
        $filter['values'][] = $bizszam;
        $q = $this->_em->createQuery('SELECT SUM(' . $a . '.brutto * ' . $a . '.irany) FROM ' . $this->getEntityname() . ' ' . $a
            . $this->getFilterString($filter));
        $q->setParameters($this->getQueryParameters($filter));
        return $q->getSingleScalarResult();
    }

    public function getSumByHivatkozottBizonylatDatum($bizszam) {
        $a = $this->alias;
        $filter = array();
        $filter['fields'][] = 'hivatkozottbizonylat';
        $filter['clauses'][] = '=';
        $filter['values'][] = $bizszam;
        $q = $this->_em->createQuery('SELECT _xx.hivatkozottdatum,SUM(' . $a . '.brutto * ' . $a . '.irany) AS egyenleg FROM ' . $this->getEntityname() . ' ' . $a
            . $this->getFilterString($filter)
            . ' GROUP BY _xx.hivatkozottdatum'
            . ' ORDER BY _xx.hivatkozottdatum');
        $q->setParameters($this->getQueryParameters($filter));
        return $q->getResult();
    }

    public function getSumByPartner($partnerid) {
        $a = $this->alias;
        $filter = array();
        $filter['fields'][] = 'partner';
        $filter['clauses'][] = '=';
        $filter['values'][] = $partnerid;
        $filter['fields'][] = 'storno';
        $filter['clauses'][] = '=';
        $filter['values'][] = false;
        $filter['fields'][] = 'stornozott';
        $filter['clauses'][] = '=';
        $filter['values'][] = false;
        $filter['fields'][] = 'rontott';
        $filter['clauses'][] = '=';
        $filter['values'][] = false;
        $q = $this->_em->createQuery('SELECT _xx.hivatkozottbizonylat,_xx.hivatkozottdatum,SUM(' . $a . '.brutto * ' . $a . '.irany) AS egyenleg FROM ' . $this->getEntityname() . ' ' . $a
            . $this->getFilterString($filter)
            . ' GROUP BY _xx.hivatkozottbizonylat,_xx.hivatkozottdatum'
            . ' HAVING egyenleg<>0'
        );
        $q->setParameters($this->getQueryParameters($filter));
        return $q->getResult();
    }

    public function getLejartKintlevosegByValutanem($partnerkodok = null) {
        $pluszsql = '';
        if ($partnerkodok) {
            $pluszsql = ' WHERE (partner_id IN (' . implode(',', $partnerkodok) . '))';
        }
        $rsm = new ResultSetMapping();
        $rsm->addScalarResult('nev', 'nev');
        $rsm->addScalarResult('egyenleg', 'egyenleg');
        $sql = 'SELECT v.nev,SUM(egyenleg) AS egyenleg FROM ('
            . ' SELECT valutanem_id,hivatkozottbizonylat,hivatkozottdatum,sum(brutto*irany) AS egyenleg'
            . ' FROM folyoszamla'
            . $pluszsql
            . ' GROUP BY valutanem_id,hivatkozottbizonylat,hivatkozottdatum) AS egyen'
            . ' LEFT JOIN valutanem v ON (egyen.valutanem_id=v.id)'
            . ' WHERE egyen.hivatkozottdatum<now()'
            . ' GROUP BY v.nev';
        $q = $this->_em->createNativeQuery($sql, $rsm);
        return $q->getScalarResult();
    }

    public function getKintlevosegByValutanem($partnerkodok = null) {
        $pluszsql = '';
        if ($partnerkodok) {
            $pluszsql = ' WHERE (f.partner_id IN (' . implode(',', $partnerkodok) . '))';
        }
        $rsm = new ResultSetMapping();
        $rsm->addScalarResult('nev', 'nev');
        $rsm->addScalarResult('egyenleg', 'egyenleg');
        $sql = 'SELECT v.nev,SUM(f.brutto * f.irany) AS egyenleg '
            . ' FROM folyoszamla f'
            . ' LEFT JOIN valutanem v ON (f.valutanem_id=v.id)'
            . $pluszsql
            . ' GROUP BY v.nev';
        $q = $this->_em->createNativeQuery($sql, $rsm);
        return $q->getScalarResult();
    }
}