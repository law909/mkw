<?php

namespace Entities;

use Doctrine\ORM\Query\ResultSetMapping;
use mkwhelpers\FilterDescriptor;

class FolyoszamlaRepository extends \mkwhelpers\Repository {

    public function __construct($em, \Doctrine\ORM\Mapping\ClassMetadata $class) {
        parent::__construct($em, $class);
        $this->setEntityname('Entities\Folyoszamla');
    }

    public function getSumByHivatkozottBizonylat($bizszam) {
        $filter = new FilterDescriptor();
        $filter->addFilter('hivatkozottbizonylat', '=', $bizszam);

        $q = $this->_em->createQuery('SELECT SUM(_xx.brutto * _xx.irany)'
            . ' FROM Entities\Folyoszamla _xx'
            . $this->getFilterString($filter));
        $q->setParameters($this->getQueryParameters($filter));
        return $q->getSingleScalarResult();
    }

    public function getCountByHivatkozottBizonylat($bizszam) {
        $filter = new FilterDescriptor();
        $filter->addFilter('hivatkozottbizonylat', '=', $bizszam);
        $filter->addSql('_xx.bizonylatfej IS NULL');

        $q = $this->_em->createQuery('SELECT COUNT(_xx)'
            . ' FROM Entities\Folyoszamla _xx'
            . $this->getFilterString($filter));
        $q->setParameters($this->getQueryParameters($filter));
        return $q->getSingleScalarResult();
    }

    public function getSumByHivatkozottBizonylatDatum($bizszam) {
        $filter = new FilterDescriptor();
        $filter->addFilter('hivatkozottbizonylat', '=', $bizszam);

        $q = $this->_em->createQuery('SELECT _xx.hivatkozottdatum,SUM(_xx.brutto * _xx.irany) AS egyenleg'
            . ' FROM Entities\Folyoszamla _xx'
            . $this->getFilterString($filter)
            . ' GROUP BY _xx.hivatkozottdatum'
            . ' ORDER BY _xx.hivatkozottdatum');
        $q->setParameters($this->getQueryParameters($filter));
        return $q->getResult();
    }

    public function getSumByPartner($partnerid) {
        $filter = new FilterDescriptor();
        $filter
            ->addFilter('partner', '=', $partnerid)
            ->addFilter('storno', '=', false)
            ->addFilter('stornozott', '=', false)
            ->addFilter('rontott', '=', false);

        $q = $this->_em->createQuery('SELECT _xx.hivatkozottbizonylat,_xx.hivatkozottdatum,SUM(_xx.brutto * _xx.irany) AS egyenleg'
            . ' FROM Entities\Folyoszamla _xx'
            . $this->getFilterString($filter)
            . ' GROUP BY _xx.hivatkozottbizonylat,_xx.hivatkozottdatum'
            . ' HAVING egyenleg<>0'
            . ' ORDER BY _xx.hivatkozottdatum'
        );
        $q->setParameters($this->getQueryParameters($filter));
        return $q->getResult();
    }

    public function getLejartKintlevosegByValutanem($partnerkodok = null) {
        $pluszsql = ' WHERE (storno=0) AND (stornozott=0)';
        if ($partnerkodok) {
            $pluszsql .= ' AND (partner_id IN (' . implode(',', $partnerkodok) . '))';
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