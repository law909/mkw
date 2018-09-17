<?php

namespace Entities;

use Doctrine\ORM\Query\ResultSetMapping;
use mkwhelpers\FilterDescriptor;

class BizonylatfejRepository extends \mkwhelpers\Repository {

    public function __construct($em, \Doctrine\ORM\Mapping\ClassMetadata $class) {
        parent::__construct($em, $class);
        $this->setEntityname('Entities\Bizonylatfej');
        $this->setOrders(array(
            '1' => array('caption' => 'biz.szám szerint csökkenő', 'order' => array('_xx.id' => 'DESC')),
            '2' => array('caption' => 'biz.szám szerint növekvő', 'order' => array('_xx.id' => 'ASC')),
            '3' => array('caption' => 'kelt szerint csökkenő', 'order' => array('_xx.kelt' => 'DESC', '_xx.id' => 'DESC')),
            '4' => array('caption' => 'kelt szerint növekvő', 'order' => array('_xx.kelt' => 'ASC', '_xx.id' => 'DESC')),
            '5' => array('caption' => 'er.biz.szám szerint csökkenő', 'order' => array('_xx.erbizonylatszam' => 'DESC')),
            '6' => array('caption' => 'er.biz.szám szerint növekvő', 'order' => array('_xx.erbizonylatszam' => 'ASC'))
        ));
    }

    public function getReportfileSelectList($sel, $biztip) {
        $elo = 'biz_' . $biztip;
        $files = dir(\mkw\store::getAdminDefaultTemplatePath());
        $list = array();
        while (false !== ($entry = $files->read())) {
            if (($entry != '.') && ($entry != '..')) {
                $path_parts = pathinfo($entry);
                $xx = substr($path_parts['basename'], 0, strlen($elo));
                if ($path_parts['extension']
                    && ($path_parts['extension'] == 'tpl')
                    && ($xx == $elo)
                ) {
                    $list[$entry] = $entry;
                }
            }
        }
        $files = dir(\mkw\store::getAdminTemplatePath());
        while (false !== ($entry = $files->read())) {
            if (($entry != '.') && ($entry != '..')) {
                $path_parts = pathinfo($entry);
                $xx = substr($path_parts['basename'], 0, strlen($elo));
                if ($path_parts['extension']
                    && ($path_parts['extension'] == 'tpl')
                    && ($xx == $elo)
                ) {
                    $list[$entry] = $entry;
                }
            }
        }
        $ret = array();
        foreach ($list as $l) {
            $ret[] = array(
                'id' => $l,
                'caption' => $l,
                'selected' => ($l === $sel)
            );
        }
        return $ret;
    }

    public function findWithJoins($id) {
        return parent::findWithJoins((string)$id);
    }

    public function getWithJoins($filter, $order = array(), $offset = 0, $elemcount = 0) {
        $q = $this->_em->createQuery('SELECT _xx'
            . ' FROM Entities\Bizonylatfej _xx'
            . $this->getFilterString($filter)
            . $this->getOrderString($order));
        $q->setParameters($this->getQueryParameters($filter));
        if ($offset > 0) {
            $q->setFirstResult($offset);
        }
        if ($elemcount > 0) {
            $q->setMaxResults($elemcount);
        }
        return $q->getResult();
    }

    public function getWithTetelek($filter, $order = array(), $offset = 0, $elemcount = 0, $locale = false) {
        $q = $this->_em->createQuery('SELECT _xx, bt'
            . ' FROM Entities\Bizonylatfej _xx'
            . ' LEFT JOIN _xx.bizonylattetelek bt'
            . $this->getFilterString($filter)
            . $this->getOrderString($order));
        $q->setParameters($this->getQueryParameters($filter));
        if ($offset > 0) {
            $q->setFirstResult($offset);
        }
        if ($elemcount > 0) {
            $q->setMaxResults($elemcount);
        }
        if ($locale) {
            \mkw\store::setTranslationHint($q, $locale);
        }
        return $q->getResult();
    }

    public function calcSumWithJoins($filter, $order = array(), $offset = 0, $elemcount = 0) {
        $q = $this->_em->createQuery('SELECT SUM(_xx.brutto) AS brutto, SUM(_xx.netto) AS netto, SUM(_xx.afa) AS afa,'
            . ' SUM(_xx.bruttohuf) AS bruttohuf, SUM(_xx.nettohuf) AS nettohuf, SUM(_xx.afahuf) AS afahuf'
            . ' FROM Entities\Bizonylatfej _xx'
            . $this->getFilterString($filter)
            . $this->getOrderString($order));
        $q->setParameters($this->getQueryParameters($filter));
        if ($offset > 0) {
            $q->setFirstResult($offset);
        }
        if ($elemcount > 0) {
            $q->setMaxResults($elemcount);
        }
        $res = $q->getScalarResult();
        return $res[0];
    }

    public function calcTeljesitmeny($filter) {
        $filter->addFilter('rontott', '=', false);
        $filter->addFilter('storno', '=', false);
        $filter->addFilter('stornozott', '=', false);
        $rsm = new ResultSetMapping();
        $rsm->addScalarResult('ev', 'ev');
        $rsm->addScalarResult('netto', 'netto');
        $rsm->addScalarResult('db', 'db');
        $q = $this->_em->createNativeQuery('SELECT YEAR(kelt) AS ev, SUM(netto) AS netto, COUNT(*) AS db'
            . ' FROM bizonylatfej _xx'
            . $this->getFilterString($filter)
            . ' GROUP BY ev'
            . ' ORDER BY ev', $rsm);
        $q->setParameters($this->getQueryParameters($filter));
        $res = $q->getScalarResult();
        return $res;
    }

    public function getCount($filter) {
        $q = $this->_em->createQuery('SELECT COUNT(_xx)'
            . ' FROM Entities\Bizonylatfej _xx'
            . $this->getFilterString($filter));
        $q->setParameters($this->getQueryParameters($filter));
        return $q->getSingleScalarResult();
    }

    /**
     * @param $bizszam
     * @param $termekid
     * @param null $valtozatid
     * @return null
     */
    public function getTetelsor($bizszam, $termekid, $valtozatid = null) {
        $filter = new \mkwhelpers\FilterDescriptor();
        if ($bizszam) {
            $filter->addFilter('bizonylatfej', '=', $bizszam);
        }
        if ($termekid) {
            $filter->addFilter('termek', '=', $termekid);
        }
        if ($valtozatid) {
            $filter->addFilter('termekvaltozat', '=', $valtozatid);
        }
        if (count($filter) == 0) {
            $filter->addFilter('id', '<', '0');
        }
        $q = $this->_em->createQuery('SELECT _xx'
            . ' FROM Entities\Bizonylattetel _xx'
            . $this->getFilterString($filter));
        $q->setParameters($this->getQueryParameters($filter));
        $r = $q->getResult();
        if (count($r) > 0) {
            return $r[0];
        }
        return null;
    }

    public function remove($bizszam, $termekid, $valtozatid = null) {
        $t = $this->getTetelsor($bizszam, $termekid, $valtozatid);
        if ($t) {
            $this->_em->remove($t);
            $this->_em->flush();
        }
    }

    /**
     * @param \Entities\Bizonylatfej $o
     * @return array
     */
    public function getAFAOsszesito($o) {
        $ret = array();
        /** @var \Entities\Bizonylattetel $tetel */
        foreach ($o->getBizonylattetelek() as $tetel) {
            $a = $tetel->getAfa();
            if (!array_key_exists($tetel->getAfaId(), $ret)) {
                $ret[$tetel->getAfaId()] = array(
                    'netto' => 0,
                    'afa' => 0,
                    'brutto' => 0,
                    'nettohuf' => 0,
                    'afahuf' => 0,
                    'bruttohuf' => 0,
                    'afakulcs' => $tetel->getAfakulcs(),
                    'caption' => $tetel->getAfanev(),
                    'rlbkod' => ($a ? $a->getRLBkod() : 0)
                );
            }
            $ret[$tetel->getAfaId()]['netto'] += $tetel->getNetto();
            $ret[$tetel->getAfaId()]['afa'] += $tetel->getAfaertek();
            $ret[$tetel->getAfaId()]['brutto'] += $tetel->getBrutto();
            $ret[$tetel->getAfaId()]['nettohuf'] += $tetel->getNettohuf();
            $ret[$tetel->getAfaId()]['afahuf'] += $tetel->getAfaertekhuf();
            $ret[$tetel->getAfaId()]['bruttohuf'] += $tetel->getBruttohuf();
        }
        return $ret;
    }

    public function findForPrint($id) {
        $locale = false;
        if ($id) {
            $b = $this->find($id);
            if ($b) {
                $locale = $b->getBizonylatnyelv();
            }
        }

        $filter = new \mkwhelpers\FilterDescriptor();
        $filter->addFilter('id', '=', $id);

        $q = $this->_em->createQuery('SELECT _xx, bt'
            . ' FROM Entities\Bizonylatfej _xx'
            . ' LEFT JOIN _xx.bizonylattetelek bt'
            . $this->getFilterString($filter));

        $q->setParameters($this->getQueryParameters($filter));

        \mkw\store::setTranslationHint($q, $locale);

        $res = $q->getResult();
        if (count($res)) {
            return $res[0];
        }
        return false;
    }

    public function haveSzallitasiKtg($bf) {
        $bizfej = $bf;
        if (is_string($bf)) {
            $bizfej = $this->find($bf);
        }
        $termekid = \mkw\store::getIntParameter(\mkw\consts::SzallitasiKtgTermek);
        $cnt = 0;
        foreach ($bizfej->getBizonylattetelek() as $btetel) {
            if ($btetel->getTermekId() === $termekid) {
                $cnt++;
            }
        }
        return $cnt > 0;
    }

    public function getSzallitasiKtgTetel($bf) {
        $bizfej = $bf;
        if (is_string($bf)) {
            $bizfej = $this->find($bf);
        }
        $termekid = \mkw\store::getIntParameter(\mkw\consts::SzallitasiKtgTermek);
        $ret = null;
        foreach ($bizfej->getBizonylattetelek() as $btetel) {
            if ($btetel->getTermekId() === $termekid) {
                $ret = $btetel;
                break;
            }
        }
        return $ret;
    }

    public function getSzamlaKelt($bizszam) {
        $filter = new \mkwhelpers\FilterDescriptor();
        $filter->addFilter('bizonylattipus', '=', 'szamla');
        $filter->addFilter('id', '=', $bizszam);

        $q = $this->_em->createQuery('SELECT YEAR(_xx.kelt) AS ev'
            . ' FROM Entities\Bizonylatfej _xx'
            . $this->getFilterString($filter));

        $q->setParameters($this->getQueryParameters($filter));

        $res = $q->getScalarResult();
        if (count($res)) {
            return $res[0];
        }
        return false;
    }

    public function getMaxSzamlaDatum($datumtipus, $filter) {
        $rsm = new ResultSetMapping();
        $rsm->addScalarResult('datum', 'datum');
        $q = $this->_em->createNativeQuery('SELECT MAX(' . $datumtipus . ') AS datum'
            . ' FROM bizonylatfej _xx'
            . $this->getFilterString($filter), $rsm);
        $q->setParameters($this->getQueryParameters($filter));
        return $q->getScalarResult();
    }

    public function getTermekForgalmiLista($raktarid, $partnerid, $datumtipus, $datumtol, $datumig, $ertektipus, $arsav, $fafilter, $nevfilter,
        $gyartoid, $locale, $partnercimkefilter) {
        switch ($datumtipus) {
            case 'kelt':
            case 'teljesites':
            case 'esedekesseg':
                $datumtipus = 'bf.' . $datumtipus;
                break;
            default:
                $datumtipus = 'bf.kelt';
                break;
        }
        $plusparams = array();
        switch ($ertektipus) {
            case 0:
                $ertekmezo1 = ', 0 AS ertek';
                $ertekmezo2 = ', 0 AS ertek';
                $arsavsql = '';
                break;
            case 1:
                $ertekmezo1 = ',SUM(bt.mennyiseg*bt.irany*bt.nettoegysar) AS ertek';
                $ertekmezo2 = ',SUM(bt.mennyiseg*bt.nettoegysar) AS ertek';
                $arsavsql = '';
                break;
            case 2:
                $ertekmezo1 = ',SUM(bt.mennyiseg*bt.irany*bt.bruttoegysar) AS ertek';
                $ertekmezo2 = ',SUM(bt.mennyiseg*bt.bruttoegysar) AS ertek';
                $arsavsql = '';
                break;
            case 3:
                $ertekmezo1 = ',SUM(bt.mennyiseg*bt.irany*bt.nettoegysarhuf) AS ertek';
                $ertekmezo2 = ',SUM(bt.mennyiseg*bt.nettoegysarhuf) AS ertek';
                $arsavsql = '';
                break;
            case 4:
                $ertekmezo1 = ',SUM(bt.mennyiseg*bt.irany*bt.bruttoegysarhuf) AS ertek';
                $ertekmezo2 = ',SUM(bt.mennyiseg*bt.bruttoegysarhuf) AS ertek';
                $arsavsql = '';
                break;
            case 5:
                $ertekmezo1 = ',SUM(bt.mennyiseg*bt.irany*ta.netto) AS ertek';
                $ertekmezo2 = ',SUM(bt.mennyiseg*ta.netto) AS ertek';
                $arsavsql = ' LEFT OUTER JOIN termekar ta ON (bt.termek_id=ta.termek_id) AND (azonosito=:arsavnev) ';
                $plusparams['arsavnev'] = $arsav;
                break;
            case 6:
                $ertekmezo1 = ',SUM(bt.mennyiseg*bt.irany*ta.brutto) AS ertek';
                $ertekmezo2 = ',SUM(bt.mennyiseg*ta.brutto) AS ertek';
                $arsavsql = ' LEFT OUTER JOIN termekar ta ON (bt.termek_id=ta.termek_id) AND (azonosito=:arsavnev) ';
                $plusparams['arsavnev'] = $arsav;
                break;
            case 7:
                $ertekmezo1 = ',SUM(bt.mennyiseg*bt.irany*t.netto) AS ertek';
                $ertekmezo2 = ',SUM(bt.mennyiseg*t.netto) AS ertek';
                $arsavsql = ' LEFT OUTER JOIN termek t ON (bt.termek_id=t.id) ';
                break;
            case 8:
                $ertekmezo1 = ',SUM(bt.mennyiseg*bt.irany*t.brutto) AS ertek';
                $ertekmezo2 = ',SUM(bt.mennyiseg*t.brutto) AS ertek';
                $arsavsql = ' LEFT OUTER JOIN termek t ON (bt.termek_id=t.id) ';
                break;
            default:
                $ertekmezo1 = ', 0 AS ertek';
                $ertekmezo2 = ', 0 AS ertek';
                $arsavsql = '';
                break;
        }
        $rsm = new ResultSetMapping();
        $rsm->addScalarResult('termek_id', 'termek_id');
        $rsm->addScalarResult('termekvaltozat_id', 'termekvaltozat_id');
        $rsm->addScalarResult('mennyiseg', 'mennyiseg');
        $rsm->addScalarResult('ertek', 'ertek');

        $ret = array();

        $termekfilter = new FilterDescriptor();
        if (!empty($fafilter)) {
            $ff = new FilterDescriptor();
            $ff->addFilter('id', 'IN', $fafilter);
            $res = \mkw\store::getEm()->getRepository('Entities\TermekFa')->getAll($ff, array());
            $faszuro = array();
            foreach ($res as $sor) {
                $faszuro[] = $sor->getKarkod() . '%';
            }
            if ($faszuro) {
                $termekfilter->addFilter(array('t.termekfa1karkod', 't.termekfa2karkod', 't.termekfa3karkod'), 'LIKE', $faszuro);
            }
        }
        if (!is_null($nevfilter)) {
            $termekfilter->addFilter(array('t.nev', 't.rovidleiras', 't.cikkszam', 't.vonalkod'), 'LIKE', '%' . $nevfilter . '%');
        }
        if ($gyartoid) {
            $termekfilter->addFilter('t.gyarto_id', '=', $gyartoid);
        }

        $trsm = new ResultSetMapping();
        $trsm->addScalarResult('id', 'id');
        $trsm->addScalarResult('cikkszam', 'cikkszam');
        $trsm->addScalarResult('nev', 'nev');
        $trsm->addScalarResult('tvid', 'tvid');
        $trsm->addScalarResult('ertek1', 'ertek1');
        $trsm->addScalarResult('ertek2', 'ertek2');

        if ($locale) {
            $termeknevmezo = 'COALESCE(tt.content, t.nev)';
            $translationjoin = ' LEFT JOIN termek_translations tt ON (t.id=tt.object_id) AND (tt.field="nev") AND (tt.locale="' . $locale . '")';
        }
        else {
            $termeknevmezo = 't.nev';
            $translationjoin = '';
        }

        $q = $this->_em->createNativeQuery('SELECT t.id,t.cikkszam,' . $termeknevmezo . ' AS nev,tv.id AS tvid,tv.ertek1,tv.ertek2'
            . ' FROM termek t'
            . ' LEFT OUTER JOIN termekvaltozat tv ON (tv.termek_id=t.id)'
            . $translationjoin
            . $this->getFilterString($termekfilter)
            . ' ORDER BY t.cikkszam,' . $termeknevmezo . ',tv.ertek1,tv.ertek2', $trsm
        );
        $q->setParameters($this->getQueryParameters($termekfilter));
        $res = $q->getScalarResult();
        foreach ($res as $rekord) {
            $kulcs = $rekord['id'] . '-' . $rekord['tvid'];
            $ret[$kulcs] = array(
                'cikkszam' => $rekord['cikkszam'],
                'nev' => $rekord['nev'],
                'ertek1' => $rekord['ertek1'],
                'ertek2' => $rekord['ertek2'],
                'nyito' => 0,
                'nyitoertek' => 0,
                'be' => 0,
                'beertek' => 0,
                'ki' => 0,
                'kiertek' => 0,
                'zaro' => 0,
                'zaroertek' => 0
            );
        }

        $partnerkodok = $this->getRepo('Entities\Partner')->getByCimkek($partnercimkefilter);

        /**********************
         * nyito
         */
        $filter = new \mkwhelpers\FilterDescriptor();
        $filter->addFilter('bf.rontott', '=', false);
        $filter->addFilter('bt.mozgat', '=', true);
        if ($partnerid) {
            $filter->addFilter('bf.partner_id', '=', $partnerid);
        }
        else {
            if ($partnerkodok) {
                $filter->addFilter('bf.partner_id', 'IN', $partnerkodok);
            }
        }
        $filter->addFilter($datumtipus, '<', $datumtol);
        if ($raktarid) {
            $filter->addFilter('bf.raktar_id', '=', $raktarid);
        }

        $q = $this->_em->createNativeQuery('SELECT bt.termek_id,bt.termekvaltozat_id,SUM(bt.mennyiseg*bt.irany) AS mennyiseg '
            . $ertekmezo1
            . ' FROM bizonylattetel bt '
            . ' LEFT OUTER JOIN bizonylatfej bf ON (bt.bizonylatfej_id=bf.id)'
            . $arsavsql
            . $this->getFilterString($filter)
            . ' GROUP BY bt.termek_id,bt.termekvaltozat_id'
            , $rsm);
        $q->setParameters(array_merge($this->getQueryParameters($filter), $plusparams));
        $res = $q->getScalarResult();
        foreach ($res as $rekord) {
            $kulcs = $rekord['termek_id'] . '-' . $rekord['termekvaltozat_id'];
            if (array_key_exists($kulcs, $ret)) {
                $ret[$kulcs]['nyito'] = $rekord['mennyiseg'] * 1;
                $ret[$kulcs]['nyitoertek'] = $rekord['ertek'] * 1;
            }
        }

        /**************
         * be
         */
        $filter->clear();
        $filter->addFilter('bf.rontott', '=', false);
        $filter->addFilter('bt.irany', '>', 0);
        $filter->addFilter('bt.mozgat', '=', true);
        if ($partnerid) {
            $filter->addFilter('bf.partner_id', '=', $partnerid);
        }
        else {
            if ($partnerkodok) {
                $filter->addFilter('bf.partner_id', 'IN', $partnerkodok);
            }
        }
        if ($datumtol) {
            $filter->addFilter($datumtipus, '>=', $datumtol);
        }
        if ($datumig) {
            $filter->addFilter($datumtipus, '<=', $datumig);
        }
        if ($raktarid) {
            $filter->addFilter('bf.raktar_id', '=', $raktarid);
        }

        $q = $this->_em->createNativeQuery('SELECT bt.termek_id,bt.termekvaltozat_id,SUM(bt.mennyiseg) AS mennyiseg '
            . $ertekmezo2
            . ' FROM bizonylattetel bt '
            . ' LEFT OUTER JOIN bizonylatfej bf ON (bt.bizonylatfej_id=bf.id)'
            . $arsavsql
            . $this->getFilterString($filter)
            . ' GROUP BY bt.termek_id,bt.termekvaltozat_id'
            , $rsm);
        $q->setParameters(array_merge($this->getQueryParameters($filter), $plusparams));
        $res = $q->getScalarResult();
        foreach ($res as $rekord) {
            $kulcs = $rekord['termek_id'] . '-' . $rekord['termekvaltozat_id'];
            if (array_key_exists($kulcs, $ret)) {
                $ret[$kulcs]['be'] = $rekord['mennyiseg'] * 1;
                $ret[$kulcs]['beertek'] = $rekord['ertek'] * 1;
            }
        }

        /*******************
         * ki
         */
        $filter->clear();
        $filter->addFilter('bf.rontott', '=', false);
        $filter->addFilter('bt.irany', '<', 0);
        $filter->addFilter('bt.mozgat', '=', true);
        if ($partnerid) {
            $filter->addFilter('bf.partner_id', '=', $partnerid);
        }
        else {
            if ($partnerkodok) {
                $filter->addFilter('bf.partner_id', 'IN', $partnerkodok);
            }
        }
        if ($datumtol) {
            $filter->addFilter($datumtipus, '>=', $datumtol);
        }
        if ($datumig) {
            $filter->addFilter($datumtipus, '<=', $datumig);
        }
        if ($raktarid) {
            $filter->addFilter('bf.raktar_id', '=', $raktarid);
        }

        $q = $this->_em->createNativeQuery('SELECT bt.termek_id,bt.termekvaltozat_id,SUM(bt.mennyiseg) AS mennyiseg '
            . $ertekmezo2
            . ' FROM bizonylattetel bt '
            . ' LEFT OUTER JOIN bizonylatfej bf ON (bt.bizonylatfej_id=bf.id)'
            . $arsavsql
            . $this->getFilterString($filter)
            . ' GROUP BY bt.termek_id,bt.termekvaltozat_id'
            , $rsm);
        $q->setParameters(array_merge($this->getQueryParameters($filter), $plusparams));
        $res = $q->getScalarResult();
        foreach ($res as $rekord) {
            $kulcs = $rekord['termek_id'] . '-' . $rekord['termekvaltozat_id'];
            if (array_key_exists($kulcs, $ret)) {
                $ret[$kulcs]['ki'] = $rekord['mennyiseg'] * 1;
                $ret[$kulcs]['kiertek'] = $rekord['ertek'] * 1;
            }
        }

        /****************
         * zaro
         */
        $filter->clear();
        $filter->addFilter('bf.rontott', '=', false);
        $filter->addFilter('bt.mozgat', '=', true);
        if ($partnerid) {
            $filter->addFilter('bf.partner_id', '=', $partnerid);
        }
        else {
            if ($partnerkodok) {
                $filter->addFilter('bf.partner_id', 'IN', $partnerkodok);
            }
        }
        if ($datumig) {
            $filter->addFilter($datumtipus, '<=', $datumig);
        }
        if ($raktarid) {
            $filter->addFilter('bf.raktar_id', '=', $raktarid);
        }

        $q = $this->_em->createNativeQuery('SELECT bt.termek_id,bt.termekvaltozat_id,SUM(bt.mennyiseg*bt.irany) AS mennyiseg '
            . $ertekmezo1
            . ' FROM bizonylattetel bt '
            . ' LEFT OUTER JOIN bizonylatfej bf ON (bt.bizonylatfej_id=bf.id)'
            . $arsavsql
            . $this->getFilterString($filter)
            . ' GROUP BY bt.termek_id,bt.termekvaltozat_id'
            , $rsm);
        $q->setParameters(array_merge($this->getQueryParameters($filter), $plusparams));
        $res = $q->getScalarResult();
        foreach ($res as $rekord) {
            $kulcs = $rekord['termek_id'] . '-' . $rekord['termekvaltozat_id'];
            if (array_key_exists($kulcs, $ret)) {
                $ret[$kulcs]['zaro'] = $rekord['mennyiseg'] * 1;
                $ret[$kulcs]['zaroertek'] = $rekord['ertek'] * 1;
            }
        }

        return $ret;
    }

    public function getBizonylatTetelLista($raktarid, $partnerid, $uzletkotoid, $datumtipus, $datumtol, $datumig, $ertektipus, $arsav, $fafilter, $nevfilter,
                                           $gyartoid, $locale, $bizstatusz, $bizstatuscsoport, $bizonylattipusfilter, $partnercimkefilter,
                                           $csoportositas, $fizmodid, $csakfoglalas) {
        switch ($datumtipus) {
            case 'kelt':
            case 'teljesites':
            case 'esedekesseg':
            case 'hatarido':
                $datumtipus = 'bf.' . $datumtipus;
                break;
            default:
                $datumtipus = 'bf.kelt';
                break;
        }
        $plusparams = array();
        switch ($csoportositas) {
            case 1:
            case 2:
                switch ($ertektipus) {
                    case 0:
                        $ertekmezo1 = ', 0 AS ertek,';
                        $arsavsql = '';
                        break;
                    case 1:
                        $ertekmezo1 = ',SUM(bt.mennyiseg*bt.irany*bt.nettoegysar)*-1 AS ertek,';
                        $arsavsql = '';
                        break;
                    case 2:
                        $ertekmezo1 = ',SUM(bt.mennyiseg*bt.irany*bt.bruttoegysar)*-1 AS ertek,';
                        $arsavsql = '';
                        break;
                    case 3:
                        $ertekmezo1 = ',SUM(bt.mennyiseg*bt.irany*bt.nettoegysarhuf)*-1 AS ertek,';
                        $arsavsql = '';
                        break;
                    case 4:
                        $ertekmezo1 = ',SUM(bt.mennyiseg*bt.irany*bt.bruttoegysarhuf)*-1 AS ertek,';
                        $arsavsql = '';
                        break;
                    case 5:
                        $ertekmezo1 = ',SUM(bt.mennyiseg*bt.irany*ta.netto)*-1 AS ertek,';
                        $arsavsql = ' LEFT OUTER JOIN termekar ta ON (bt.termek_id=ta.termek_id) AND (azonosito=:arsavnev) ';
                        $plusparams['arsavnev'] = $arsav;
                        break;
                    case 6:
                        $ertekmezo1 = ',SUM(bt.mennyiseg*bt.irany*ta.brutto)*-1 AS ertek,';
                        $arsavsql = ' LEFT OUTER JOIN termekar ta ON (bt.termek_id=ta.termek_id) AND (azonosito=:arsavnev) ';
                        $plusparams['arsavnev'] = $arsav;
                        break;
                    case 7:
                        $ertekmezo1 = ',SUM(bt.mennyiseg*bt.irany*t.netto)*-1 AS ertek,';
                        $arsavsql = '';
                        break;
                    case 8:
                        $ertekmezo1 = ',SUM(bt.mennyiseg*bt.irany*t.brutto)*-1 AS ertek,';
                        $arsavsql = '';
                        break;
                    default:
                        $ertekmezo1 = ', 0 AS ertek,';
                        $arsavsql = '';
                        break;
                }
                break;
            case 3:
                switch ($ertektipus) {
                    case 1:
                        $ertekmezo1 = ',SUM(bt.netto) AS ertek';
                        $arsavsql = '';
                        break;
                    case 2:
                        $ertekmezo1 = ',SUM(bt.brutto) AS ertek';
                        $arsavsql = '';
                        break;
                    case 3:
                        $ertekmezo1 = ',SUM(bt.nettohuf) AS ertek';
                        $arsavsql = '';
                        break;
                    case 4:
                        $ertekmezo1 = ',SUM(bt.bruttohuf) AS ertek';
                        $arsavsql = '';
                        break;
                    default:
                        $ertekmezo1 = ', 0 AS ertek';
                        $arsavsql = '';
                        break;
                }
                break;
        }

        $termekfilter = new FilterDescriptor();
        if (!empty($fafilter)) {
            $ff = new FilterDescriptor();
            $ff->addFilter('id', 'IN', $fafilter);
            $res = \mkw\store::getEm()->getRepository('Entities\TermekFa')->getAll($ff, array());
            $faszuro = array();
            foreach ($res as $sor) {
                $faszuro[] = $sor->getKarkod() . '%';
            }
            if ($faszuro) {
                $termekfilter->addFilter(array('t.termekfa1karkod', 't.termekfa2karkod', 't.termekfa3karkod'), 'LIKE', $faszuro);
            }
        }
        if (!is_null($nevfilter)) {
            $termekfilter->addFilter(array('t.nev', 't.rovidleiras', 't.cikkszam', 't.vonalkod'), 'LIKE', '%' . $nevfilter . '%');
        }
        if ($gyartoid) {
            $termekfilter->addFilter('t.gyarto_id', '=', $gyartoid);
        }

        if ($locale) {
            $termeknevmezo = 'COALESCE(tt.content, t.nev)';
            $translationjoin = ' LEFT JOIN termek_translations tt ON (t.id=tt.object_id) AND (tt.field="nev") AND (tt.locale="' . $locale . '")';
        }
        else {
            $termeknevmezo = 't.nev';
            $translationjoin = '';
        }

        $bizstatuszObj = false;
        if ($bizstatusz) {
            $bizstatuszObj = $this->getRepo('Entities\Bizonylatstatusz')->findOneById($bizstatusz);
        }

        $partnerkodok = $this->getRepo('Entities\Partner')->getByCimkek($partnercimkefilter);

        $filter = new \mkwhelpers\FilterDescriptor();
        $filter->addFilter('bf.rontott', '=', false);
        if ($csakfoglalas) {
            $filter->addFilter('bt.foglal', '=', true);
        }
        if ($bizstatuszObj) {
            $filter->addFilter('bf.bizonylatstatusz_id', '=', $bizstatuszObj);
        }
        if ($bizstatuscsoport) {
            $filter->addFilter('bf.bizonylatstatuszcsoport', '=', $bizstatuscsoport);
        }
        if ($bizonylattipusfilter) {
            $filter->addFilter('bf.bizonylattipus_id', 'IN', $bizonylattipusfilter);
        }
        if ($partnerid) {
            $filter->addFilter('bf.partner_id', '=', $partnerid);
        }
        else {
            if ($partnerkodok) {
                $filter->addFilter('bf.partner_id', 'IN', $partnerkodok);
            }
        }
        if ($datumtol) {
            $filter->addFilter($datumtipus, '>=', $datumtol);
        }
        if ($datumig) {
            $filter->addFilter($datumtipus, '<=', $datumig);
        }
        if ($raktarid) {
            $filter->addFilter('bf.raktar_id', '=', $raktarid);
        }
        if ($uzletkotoid) {
            $filter->addFilter('bf.uzletkoto_id', '=', $uzletkotoid);
        }
        if ($fizmodid) {
            $filter->addFilter('bf.fizmod_id', '=', $fizmodid);
        }

        $filter = $filter->merge($termekfilter);

        switch ($csoportositas) {
            case 1:
                $rsm = new ResultSetMapping();
                $rsm->addScalarResult('termek_id', 'termek_id');
                $rsm->addScalarResult('termekvaltozat_id', 'termekvaltozat_id');
                $rsm->addScalarResult('mennyiseg', 'mennyiseg');
                $rsm->addScalarResult('ertek', 'ertek');
                $rsm->addScalarResult('cikkszam', 'cikkszam');
                $rsm->addScalarResult('nev', 'nev');
                $rsm->addScalarResult('ertek1', 'ertek1');
                $rsm->addScalarResult('ertek2', 'ertek2');

                $q = $this->_em->createNativeQuery('SELECT bt.termek_id,bt.termekvaltozat_id,SUM(bt.mennyiseg*bt.irany)*-1 AS mennyiseg '
                    . $ertekmezo1
                    . ' t.cikkszam,' . $termeknevmezo . ' AS nev,tv.ertek1,tv.ertek2 '
                    . ' FROM bizonylattetel bt '
                    . ' LEFT OUTER JOIN bizonylatfej bf ON (bt.bizonylatfej_id=bf.id)'
                    . ' LEFT OUTER JOIN termek t ON (bt.termek_id=t.id)'
                    . ' LEFT OUTER JOIN termekvaltozat tv ON (bt.termekvaltozat_id=tv.id)'
                    . $translationjoin
                    . $arsavsql
                    . $this->getFilterString($filter)
                    . ' GROUP BY bt.termek_id,bt.termekvaltozat_id'
                    . ' ORDER BY t.cikkszam,' . $termeknevmezo . ',tv.ertek1,tv.ertek2'
                    , $rsm);
                break;
            case 2:
                $rsm = new ResultSetMapping();
                $rsm->addScalarResult('partner_id', 'partner_id');
                $rsm->addScalarResult('partnernev', 'partnernev');
                $rsm->addScalarResult('partnerirszam', 'partnerirszam');
                $rsm->addScalarResult('partnervaros', 'partnervaros');
                $rsm->addScalarResult('partnerutca', 'partnerutca');
                $rsm->addScalarResult('partnerhazszam', 'partnerhazszam');
                $rsm->addScalarResult('termek_id', 'termek_id');
                $rsm->addScalarResult('termekvaltozat_id', 'termekvaltozat_id');
                $rsm->addScalarResult('mennyiseg', 'mennyiseg');
                $rsm->addScalarResult('ertek', 'ertek');
                $rsm->addScalarResult('cikkszam', 'cikkszam');
                $rsm->addScalarResult('nev', 'nev');
                $rsm->addScalarResult('ertek1', 'ertek1');
                $rsm->addScalarResult('ertek2', 'ertek2');

                $q = $this->_em->createNativeQuery('SELECT bf.partner_id,bf.partnernev,bf.partnerirszam,bf.partnervaros,bf.partnerutca,bf.partnerhazszam,'
                    . ' bt.termek_id,bt.termekvaltozat_id,SUM(bt.mennyiseg*bt.irany)*-1 AS mennyiseg '
                    . $ertekmezo1
                    . ' t.cikkszam,' . $termeknevmezo . ' AS nev,tv.ertek1,tv.ertek2 '
                    . ' FROM bizonylattetel bt '
                    . ' LEFT OUTER JOIN bizonylatfej bf ON (bt.bizonylatfej_id=bf.id)'
                    . ' LEFT OUTER JOIN termek t ON (bt.termek_id=t.id)'
                    . ' LEFT OUTER JOIN termekvaltozat tv ON (bt.termekvaltozat_id=tv.id)'
                    . $translationjoin
                    . $arsavsql
                    . $this->getFilterString($filter)
                    . ' GROUP BY bf.partner_id,bt.termek_id,bt.termekvaltozat_id'
                    . ' ORDER BY bf.partnernev,bf.partner_id,t.cikkszam,' . $termeknevmezo . ',tv.ertek1,tv.ertek2'
                    , $rsm);
                break;
            case 3:
                $rsm = new ResultSetMapping();
                $rsm->addScalarResult('uzletkoto_id', 'uzletkoto_id');
                $rsm->addScalarResult('uzletkotonev', 'uzletkotonev');
                $rsm->addScalarResult('partner_id', 'partner_id');
                $rsm->addScalarResult('partnernev', 'partnernev');
                $rsm->addScalarResult('partnerirszam', 'partnerirszam');
                $rsm->addScalarResult('partnervaros', 'partnervaros');
                $rsm->addScalarResult('partnerutca', 'partnerutca');
                $rsm->addScalarResult('partnerhazszam', 'partnerhazszam');
                $rsm->addScalarResult('ertek', 'ertek');

                $q = $this->_em->createNativeQuery('SELECT bf.uzletkoto_id,bf.uzletkotonev,bf.partner_id,bf.partnernev,bf.partnerirszam,'
                    . 'bf.partnervaros,bf.partnerutca,bf.partnerhazszam,SUM(bt.mennyiseg*bt.irany)*-1 AS mennyiseg '
                    . $ertekmezo1
                    . ' FROM bizonylattetel bt '
                    . ' LEFT OUTER JOIN bizonylatfej bf ON (bt.bizonylatfej_id=bf.id)'
                    . ' LEFT OUTER JOIN termek t ON (bt.termek_id=t.id)'
                    . ' LEFT OUTER JOIN termekvaltozat tv ON (bt.termekvaltozat_id=tv.id)'
                    . $arsavsql
                    . $this->getFilterString($filter)
                    . ' GROUP BY bf.uzletkoto_id,bf.partner_id'
                    . ' ORDER BY bf.uzletkoto_id,bf.uzletkotonev,bf.partnernev,bf.partner_id'
                    , $rsm);
                break;
            case 4:
                $rsm = new ResultSetMapping();
                $rsm->addScalarResult('id', 'id');
                $rsm->addScalarResult('kelt', 'kelt');
                $rsm->addScalarResult('teljesites', 'teljesites');
                $rsm->addScalarResult('partner_id', 'partner_id');
                $rsm->addScalarResult('partnernev', 'partnernev');
                $rsm->addScalarResult('partnerirszam', 'partnerirszam');
                $rsm->addScalarResult('partnervaros', 'partnervaros');
                $rsm->addScalarResult('partnerutca', 'partnerutca');
                $rsm->addScalarResult('partnerhazszam', 'partnerhazszam');
                $rsm->addScalarResult('mennyiseg', 'mennyiseg');
                $rsm->addScalarResult('cikkszam', 'cikkszam');
                $rsm->addScalarResult('nev', 'nev');
                $rsm->addScalarResult('ertek1', 'ertek1');
                $rsm->addScalarResult('ertek2', 'ertek2');
                $rsm->addScalarResult('statusznev', 'statusznev');

                $q = $this->_em->createNativeQuery('SELECT bf.id,bf.kelt,bf.teljesites,bf.partner_id,bf.partnernev,bf.partnerirszam,'
                    . 'bf.partnervaros,bf.partnerutca,bf.partnerhazszam,bt.mennyiseg, '
                    . ' t.cikkszam,' . $termeknevmezo . ' AS nev,tv.ertek1,tv.ertek2,bs.nev AS statusznev '
                    . ' FROM bizonylattetel bt '
                    . ' LEFT OUTER JOIN bizonylatfej bf ON (bt.bizonylatfej_id=bf.id)'
                    . ' LEFT OUTER JOIN bizonylatstatusz bs ON (bf.bizonylatstatusz_id=bs.id)'
                    . ' LEFT OUTER JOIN termek t ON (bt.termek_id=t.id)'
                    . ' LEFT OUTER JOIN termekvaltozat tv ON (bt.termekvaltozat_id=tv.id)'
                    . $this->getFilterString($filter)
                    . ' ORDER BY bf.id,t.cikkszam'
                    , $rsm);
                break;

        }
        $q->setParameters(array_merge($this->getQueryParameters($filter), $plusparams));
        $res = $q->getScalarResult();

        return $res;
    }

    public function getBizomanyosErtekesitesLista($partnerid, $datumtipus, $datumtol, $datumig, $ertektipus, $arsav, $partnercimkefilter) {
        switch ($datumtipus) {
            case 'kelt':
            case 'teljesites':
            case 'esedekesseg':
                $datumtipus = 'bf.' . $datumtipus;
                break;
            default:
                $datumtipus = 'bf.kelt';
                break;
        }
        $plusparams = array();
        $arsavsql = '';
        switch ($ertektipus) {
            case 0:
                $ertekmezo1 = ', 0 AS ertek';
                $ertekmezo2 = ', 0 AS ertek';
                break;
            case 1:
                $ertekmezo1 = ',SUM(bt.mennyiseg*bt.irany*bt.nettoegysar) AS ertek';
                $ertekmezo2 = ',SUM(bt.mennyiseg*bt.nettoegysar) AS ertek';
                break;
            case 2:
                $ertekmezo1 = ',SUM(bt.mennyiseg*bt.irany*bt.bruttoegysar) AS ertek';
                $ertekmezo2 = ',SUM(bt.mennyiseg*bt.bruttoegysar) AS ertek';
                break;
            case 3:
                $ertekmezo1 = ',SUM(bt.mennyiseg*bt.irany*bt.nettoegysarhuf) AS ertek';
                $ertekmezo2 = ',SUM(bt.mennyiseg*bt.nettoegysarhuf) AS ertek';
                break;
            case 4:
                $ertekmezo1 = ',SUM(bt.mennyiseg*bt.irany*bt.bruttoegysarhuf) AS ertek';
                $ertekmezo2 = ',SUM(bt.mennyiseg*bt.bruttoegysarhuf) AS ertek';
                break;
            case 5:
                $ertekmezo1 = ',SUM(bt.mennyiseg*bt.irany*ta.netto) AS ertek';
                $ertekmezo2 = ',SUM(bt.mennyiseg*ta.netto) AS ertek';
                $arsavsql = ' LEFT OUTER JOIN termekar ta ON (bt.termek_id=ta.termek_id) AND (azonosito=:arsavnev) ';
                $plusparams['arsavnev'] = $arsav;
                break;
            case 6:
                $ertekmezo1 = ',SUM(bt.mennyiseg*bt.irany*ta.brutto) AS ertek';
                $ertekmezo2 = ',SUM(bt.mennyiseg*ta.brutto) AS ertek';
                $arsavsql = ' LEFT OUTER JOIN termekar ta ON (bt.termek_id=ta.termek_id) AND (azonosito=:arsavnev) ';
                $plusparams['arsavnev'] = $arsav;
                break;
            case 7:
                $ertekmezo1 = ',SUM(bt.mennyiseg*bt.irany*t.netto) AS ertek';
                $ertekmezo2 = ',SUM(bt.mennyiseg*t.netto) AS ertek';
                break;
            case 8:
                $ertekmezo1 = ',SUM(bt.mennyiseg*bt.irany*t.brutto) AS ertek';
                $ertekmezo2 = ',SUM(bt.mennyiseg*t.brutto) AS ertek';
                break;
            default:
                $ertekmezo1 = ', 0 AS ertek';
                $ertekmezo2 = ', 0 AS ertek';
                break;
        }

        $partnerkodok = $this->getRepo('Entities\Partner')->getByCimkek($partnercimkefilter);

        $rsm = new ResultSetMapping();
        $rsm->addScalarResult('termek_id', 'termek_id');
        $rsm->addScalarResult('termekvaltozat_id', 'termekvaltozat_id');
        $rsm->addScalarResult('mennyiseg', 'mennyiseg');
        $rsm->addScalarResult('ertek', 'ertek');
        $rsm->addScalarResult('cikkszam', 'cikkszam');
        $rsm->addScalarResult('nev', 'nev');
        $rsm->addScalarResult('ertek1', 'ertek1');
        $rsm->addScalarResult('ertek2', 'ertek2');

        $ret = array();

        /**************
         * be
         */
        $filter = new \mkwhelpers\FilterDescriptor();
        $filter->addFilter('bt.mozgat', '=', true);
        $filter->addFilter('bt.irany', '>', 0);
        if ($partnerid) {
            $filter->addFilter('bf.partner_id', '=', $partnerid);
        }
        else {
            if ($partnerkodok) {
                $filter->addFilter('bf.partner_id', 'IN', $partnerkodok);
            }
        }
        if ($datumtol) {
            $filter->addFilter($datumtipus, '>=', $datumtol);
        }
        if ($datumig) {
            $filter->addFilter($datumtipus, '<=', $datumig);
        }

        $q = $this->_em->createNativeQuery('SELECT bt.termek_id,bt.termekvaltozat_id,t.cikkszam,t.nev,tv.ertek1,tv.ertek2,SUM(bt.mennyiseg) AS mennyiseg '
            . $ertekmezo2
            . ' FROM bizonylattetel bt '
            . ' LEFT OUTER JOIN bizonylatfej bf ON (bt.bizonylatfej_id=bf.id)'
            . ' LEFT OUTER JOIN termek t ON (bt.termek_id=t.id)'
            . ' LEFT OUTER JOIN termekvaltozat tv ON (bt.termekvaltozat_id=tv.id)'
            . $arsavsql
            . $this->getFilterString($filter)
            . ' GROUP BY bt.termek_id,bt.termekvaltozat_id'
            , $rsm);
        $q->setParameters(array_merge($this->getQueryParameters($filter), $plusparams));
        $res = $q->getScalarResult();
        foreach ($res as $rekord) {
            $kulcs = $rekord['termek_id'] . '-' . $rekord['termekvaltozat_id'];
            $ret[$kulcs]['cikkszam'] = $rekord['cikkszam'];
            $ret[$kulcs]['nev'] = $rekord['nev'];
            $ret[$kulcs]['ertek1'] = $rekord['ertek1'];
            $ret[$kulcs]['ertek2'] = $rekord['ertek2'];
            $ret[$kulcs]['be'] = $rekord['mennyiseg'] * 1;
            $ret[$kulcs]['beertek'] = $rekord['ertek'] * 1;
            $ret[$kulcs]['ki'] = 0;
            $ret[$kulcs]['kiertek'] = 0;
        }

        /*******************
         * ki
         */
        $filter->clear();
        $filter->addFilter('bt.mozgat', '=', true);
        $filter->addFilter('bt.irany', '<', 0);
        if ($partnerid) {
            $filter->addFilter('bf.partner_id', '=', $partnerid);
        }
        else {
            if ($partnerkodok) {
                $filter->addFilter('bf.partner_id', 'IN', $partnerkodok);
            }
        }
        if ($datumtol) {
            $filter->addFilter($datumtipus, '>=', $datumtol);
        }
        if ($datumig) {
            $filter->addFilter($datumtipus, '<=', $datumig);
        }

        $q = $this->_em->createNativeQuery('SELECT bt.termek_id,bt.termekvaltozat_id,t.cikkszam,t.nev,tv.ertek1,tv.ertek2,SUM(bt.mennyiseg) AS mennyiseg '
            . $ertekmezo2
            . ' FROM bizonylattetel bt '
            . ' LEFT OUTER JOIN bizonylatfej bf ON (bt.bizonylatfej_id=bf.id)'
            . ' LEFT OUTER JOIN termek t ON (bt.termek_id=t.id)'
            . ' LEFT OUTER JOIN termekvaltozat tv ON (bt.termekvaltozat_id=tv.id)'
            . $arsavsql
            . $this->getFilterString($filter)
            . ' GROUP BY bt.termek_id,bt.termekvaltozat_id'
            , $rsm);
        $q->setParameters(array_merge($this->getQueryParameters($filter), $plusparams));
        $res = $q->getScalarResult();
        foreach ($res as $rekord) {
            $kulcs = $rekord['termek_id'] . '-' . $rekord['termekvaltozat_id'];
            $marvan = array_key_exists($kulcs, $ret);
            if (!$marvan) {
                $ret[$kulcs]['cikkszam'] = $rekord['cikkszam'];
                $ret[$kulcs]['nev'] = $rekord['nev'];
                $ret[$kulcs]['ertek1'] = $rekord['ertek1'];
                $ret[$kulcs]['ertek2'] = $rekord['ertek2'];
                $ret[$kulcs]['be'] = 0;
                $ret[$kulcs]['beertek'] = 0;
            }
            $ret[$kulcs]['ki'] = $rekord['mennyiseg'] * 1;
            $ret[$kulcs]['kiertek'] = $rekord['ertek'] * 1;
        }

        return $ret;
    }

    public function getAllFakeKifizetes($tol, $ig, $pkodok = null, $ukid = null, $belso = false) {
        $filter = new FilterDescriptor();
        $filter
            ->addFilter('rontott', '=', false)
            ->addFilter('storno', '=', false)
            ->addFilter('stornozott', '=', false)
            ->addFilter('fakekintlevoseg', '=', true)
            ->addFilter('fakekifizetve', '=', true)
            ->addFilter('fakekifizetesdatum', '>=', $tol)
            ->addFilter('fakekifizetesdatum', '<=', $ig);
        if ($pkodok) {
            $filter->addFilter('partner', 'IN', $pkodok);
        }
        if ($ukid) {
            if ($belso) {
                $filter->addFilter('belsouzletkoto', '=', $ukid);
            }
            else {
                $filter->addFilter('uzletkoto', '=', $ukid);
            }
        }

        $q = $this->_em->createQuery('SELECT _xx'
            . ' FROM Entities\Bizonylatfej _xx'
            . $this->getFilterString($filter));
        $q->setParameters($this->getQueryParameters($filter));
        return $q->getResult();
    }

    public function getAllKeszpenzes($tol, $ig, $pkodok = null, $ukid = null, $belso = false) {
        $fizmodok = $this->getRepo('Entities\Fizmod')->getAllKeszpenzes();
        $fmids = array();
        foreach ($fizmodok as $fm) {
            $fmids[] = $fm->getId();
        }

        $filter = new FilterDescriptor();
        $filter
            ->addFilter('bizonylattipus', 'IN', array('szamla', 'keziszamla', 'egyeb'))
            ->addFilter('rontott', '=', false)
            ->addFilter('storno', '=', false)
            ->addFilter('stornozott', '=', false)
            ->addFilter('kelt', '>=', $tol)
            ->addFilter('kelt', '<=', $ig);
        if ($pkodok) {
            $filter->addFilter('partner', 'IN', $pkodok);
        }
        if ($ukid) {
            if ($belso) {
                $filter->addFilter('belsouzletkoto', '=', $ukid);
            }
            else {
                $filter->addFilter('uzletkoto', '=', $ukid);
            }
        }
        if ($fmids) {
            $filter->addFilter('fizmod', 'IN', $fmids);
        }

        $q = $this->_em->createQuery('SELECT _xx'
            . ' FROM Entities\Bizonylatfej _xx'
            . $this->getFilterString($filter));
        $q->setParameters($this->getQueryParameters($filter));
        return $q->getResult();
    }
}