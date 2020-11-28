<?php

namespace Entities;

use Doctrine\ORM\Query\ResultSetMapping;
use mkwhelpers\FilterDescriptor;

class TermekRepository extends \mkwhelpers\Repository {

    public function __construct($em, \Doctrine\ORM\Mapping\ClassMetadata $class) {
        parent::__construct($em, $class);
        $this->setEntityname('Entities\Termek');
        $this->setOrders(array(
            '1' => array('caption' => 'név szerint', 'order' => array('nev' => 'ASC')),
            '2' => array('caption' => 'cikkszám szerint', 'order' => array('cikkszam' => 'ASC'))
        ));
        $this->setBatches(array(
            'arexport' => 'Export árazáshoz',
            'tcsset' => 'Termékcsoport módosítás'
        ));
    }

    protected function addAktivLathatoFilter($filter) {
        $filter->addFilter('inaktiv', '=', false);
        $filter->addFilter(\mkw\store::getWebshopFieldName('lathato'), '=', true);
        $filter->addFilter('fuggoben', '=', false);
    }

    public function getAkciosFilterSQL($date = null) {
        if (!$date) {
            $date = date(\mkw\store::$SQLDateFormat);
        }
        return '
                        (
                            (_xx.akciostart IS NOT NULL) OR (_xx.akciostop IS NOT NULL)
                        ) AND 
                        (
                            (_xx.akciostart <= \'' . $date . '\' AND _xx.akciostop >= \'' . $date . '\') OR
                            (_xx.akciostart <= \'' . $date . '\' AND (_xx.akciostop IS NULL)) OR
                            ((_xx.akciostart IS NULL) AND _xx.akciostop >= \'' . $date . '\')
                        )
                ';
    }

    public function getAllForSelectList($filter, $order = array(), $offset = 0, $elemcount = 0) {
        $q = $this->_em->createQuery('SELECT _xx.id,_xx.nev '
            . ' FROM Entities\Termek _xx'
            . $this->getFilterString($filter)
            . $this->getOrderString($order));
        $q->setParameters($this->getQueryParameters($filter));
        if ($offset > 0) {
            $q->setFirstResult($offset);
        }
        if ($elemcount > 0) {
            $q->setMaxResults($elemcount);
        }
        if (\mkw\store::isMainMode()) {
            \mkw\store::setTranslationHint($q, \mkw\store::getLocale());
        }
        return $q->getScalarResult();
    }

    public function getAllForExport($addedfilter = false, $locale = false) {
        $filter = new FilterDescriptor();
        $this->addAktivLathatoFilter($filter);
        $filter->addFilter('termekexportbanszerepel', '=', true);
        $filter->addFilter('nemkaphato', '=', false);
        if ($addedfilter) {
            $filter = $filter->merge($addedfilter);
        }
        $q = $this->_em->createQuery('SELECT _xx'
            . ' FROM Entities\Termek _xx'
            . $this->getFilterString($filter)
            . $this->getOrderString(array()));
        $q->setParameters($this->getQueryParameters($filter));
        if (\mkw\store::isMainMode()) {
            if (!$locale) {
                $locale = \mkw\store::getLocale();
            }
            \mkw\store::setTranslationHint($q, $locale);
        }
        else {
            if ($locale) {
                \mkw\store::setTranslationHint($q, $locale);
            }
        }
        return $q->getResult();
    }

    public function getAllValtozatForExport($addedfilter = false, $locale = false) {
        $filter = new FilterDescriptor();
        if (\mkw\store::isSuperzoneB2B()) {
            $filter->addFilter('inaktiv', '=', false);
            $filter->addFilter('fuggoben', '=', false);
        }
        else {
            $this->addAktivLathatoFilter($filter);
        }
        $filter->addFilter('termekexportbanszerepel', '=', true);
        $filter->addFilter('nemkaphato', '=', false);
        if ($addedfilter) {
            $filter = $filter->merge($addedfilter);
        }
        $q = $this->_em->createQuery('SELECT _xx, v'
            . ' FROM Entities\Termek _xx'
            . ' LEFT JOIN _xx.valtozatok v WITH ' . \mkw\store::getWebshopFieldName('v.lathato') . '=true AND ' . \mkw\store::getWebshopFieldName('v.elerheto') . '=true'
            . $this->getFilterString($filter)
            . $this->getOrderString(array()));
        $q->setParameters($this->getQueryParameters($filter));
        if (\mkw\store::isMainMode()) {
            if (!$locale) {
                $locale = \mkw\store::getLocale();
            }
            \mkw\store::setTranslationHint($q, $locale);
        }
        else {
            if ($locale) {
                \mkw\store::setTranslationHint($q, $locale);
            }
        }
        return $q->getResult();
    }

    public function getSuperzonehuExport($addedfilter = false) {
        $filter = new FilterDescriptor();
        $filter->addFilter('fuggoben', '=', false);
        $filter->addFilter('nemkaphato', '=', false);
        if ($addedfilter) {
            $filter = $filter->merge($addedfilter);
        }
        $q = $this->_em->createQuery('SELECT _xx, v'
            . ' FROM Entities\Termek _xx'
            . ' LEFT JOIN _xx.valtozatok v'
            . $this->getFilterString($filter)
            . $this->getOrderString(array()));
        $q->setParameters($this->getQueryParameters($filter));
        return $q->getResult();
    }

    public function getWithJoins($filter, $order = array(), $offset = 0, $elemcount = 0) {
        $q = $this->_em->createQuery('SELECT _xx,vtsz,afa,fa1,fa2,fa3'
            . ' FROM Entities\Termek _xx'
            . ' LEFT JOIN _xx.vtsz vtsz'
            . ' LEFT JOIN _xx.afa afa'
            . ' LEFT JOIN _xx.termekfa1 fa1'
            . ' LEFT JOIN _xx.termekfa2 fa2'
            . ' LEFT JOIN _xx.termekfa3 fa3'
            . $this->getFilterString($filter)
            . $this->getOrderString($order));
        $q->setParameters($this->getQueryParameters($filter));
        if ($offset > 0) {
            $q->setFirstResult($offset);
        }
        if ($elemcount > 0) {
            $q->setMaxResults($elemcount);
        }
        if (\mkw\store::isMainMode()) {
            \mkw\store::setTranslationHint($q, \mkw\store::getLocale());
        }
        return $q->getResult();
    }

    public function getWithAr($arsavid, $valutanemid, $filter, $order, $offset = 0, $elemcount = 0) {
        $q = $this->_em->createQuery('SELECT _xx,vtsz,afa,fa1,fa2,fa3,ar,valutanem'
            . ' FROM Entities\Termek _xx'
            . ' LEFT JOIN _xx.vtsz vtsz'
            . ' LEFT JOIN _xx.afa afa'
            . ' LEFT JOIN _xx.termekfa1 fa1'
            . ' LEFT JOIN _xx.termekfa2 fa2'
            . ' LEFT JOIN _xx.termekfa3 fa3'
            . ' LEFT JOIN _xx.termekarak ar WITH (ar.azonosito = :X1A) AND (ar.valutanem = :X1V)'
            . ' LEFT JOIN ar.valutanem valutanem'
            . $this->getFilterString($filter)
            . $this->getOrderString($order));
        $paramarr = $this->getQueryParameters($filter);
        $paramarr['X1A'] = $arsavid;
        $paramarr['X1V'] = $valutanemid;
        $q->setParameters($paramarr);
        if ($offset > 0) {
            $q->setFirstResult($offset);
        }
        if ($elemcount > 0) {
            $q->setMaxResults($elemcount);
        }
        if (\mkw\store::isMainMode()) {
            \mkw\store::setTranslationHint($q, \mkw\store::getLocale());
        }
        return $q->getResult();
    }

    public function getWithArak($filter, $order, $offset = 0, $elemcount = 0) {
        $q = $this->_em->createQuery('SELECT _xx,vtsz,afa,fa1,fa2,fa3,ar,valutanem'
            . ' FROM Entities\Termek _xx'
            . ' LEFT JOIN _xx.vtsz vtsz'
            . ' LEFT JOIN _xx.afa afa'
            . ' LEFT JOIN _xx.termekfa1 fa1'
            . ' LEFT JOIN _xx.termekfa2 fa2'
            . ' LEFT JOIN _xx.termekfa3 fa3'
            . ' LEFT JOIN _xx.termekarak ar'
            . ' LEFT JOIN ar.valutanem valutanem'
            . $this->getFilterString($filter)
            . $this->getOrderString($order));
        $q->setParameters($this->getQueryParameters($filter));
        if ($offset > 0) {
            $q->setFirstResult($offset);
        }
        if ($elemcount > 0) {
            $q->setMaxResults($elemcount);
        }
        if (\mkw\store::isMainMode()) {
            \mkw\store::setTranslationHint($q, \mkw\store::getLocale());
        }
        return $q->getResult();
    }

    public function getIdsWithJoins($filter, $order, $offset = 0, $elemcount = 0) {
        $q = $this->_em->createQuery('SELECT _xx.id'
            . ' FROM Entities\Termek _xx'
            . ' LEFT JOIN _xx.vtsz vtsz'
            . ' LEFT JOIN _xx.afa afa'
            . ' LEFT JOIN _xx.termekfa1 fa1'
            . ' LEFT JOIN _xx.termekfa2 fa2'
            . ' LEFT JOIN _xx.termekfa3 fa3'
            . $this->getFilterString($filter)
            . $this->getOrderString($order));
        $q->setParameters($this->getQueryParameters($filter));
        if ($offset > 0) {
            $q->setFirstResult($offset);
        }
        if ($elemcount > 0) {
            $q->setMaxResults($elemcount);
        }
        return $q->getScalarResult();
    }

    public function getCount($filter) {
        $q = $this->_em->createQuery('SELECT COUNT(_xx)'
            . ' FROM Entities\Termek _xx'
            . ' LEFT JOIN _xx.vtsz vtsz'
            . ' LEFT JOIN _xx.afa afa'
            . ' LEFT JOIN _xx.termekfa1 fa1'
            . ' LEFT JOIN _xx.termekfa2 fa2'
            . ' LEFT JOIN _xx.termekfa3 fa3'
            . $this->getFilterString($filter));
        $q->setParameters($this->getQueryParameters($filter));
        if (\mkw\store::isMainMode()) {
            \mkw\store::setTranslationHint($q, \mkw\store::getLocale());
        }
        return $q->getSingleScalarResult();
    }

    public function getTermekLista($filter, $order, $offset = null, $elemcount = null) {
        switch (true) {

            case \mkw\store::isMindentkapni():
                $rsm = new ResultSetMapping();
                $rsm->addScalarResult('id', 'id');
                $rsm->addScalarResult('valtozatid', 'valtozatid');
                $rsm->addScalarResult('o1', 'o1');

                $order = array_merge_recursive(array('.o1' => 'ASC'), $order);
                $this->addAktivLathatoFilter($filter);
                $sql = 'SELECT _xx.id,v.id AS valtozatid,'
                    . ' IF(_xx.nemkaphato,9,0)+IF((akciostart<=now() AND akciostop>=now()) OR (akciostart<=now() AND akciostop is null) OR (akciostart is null AND akciostop>=now()),-1,0) AS o1'
                    . ' FROM termek _xx'
                    . ' LEFT JOIN termekfa fa1 ON (_xx.termekfa1_id=fa1.id)'
                    . ' LEFT JOIN termekfa fa2 ON (_xx.termekfa2_id=fa2.id)'
                    . ' LEFT JOIN termekfa fa3 ON (_xx.termekfa3_id=fa3.id)'
                    . ' LEFT JOIN termekvaltozat v ON (_xx.id=v.termek_id) AND (' . \mkw\store::getWebshopFieldName('v.lathato') . '=1) AND (' . \mkw\store::getWebshopFieldName('v.elerheto') . '=1)'
                    . $this->getFilterString($filter)
                    . $this->getOrderString($order)
                    . $this->getLimitString($offset, $elemcount);
                $q = $this->_em->createNativeQuery($sql, $rsm);
                $params = $this->getQueryParameters($filter);
                $q->setParameters($params);
                return $q->getScalarResult();

            case \mkw\store::isMugenrace():
                $rsm = new ResultSetMapping();
                $rsm->addScalarResult('id', 'id');
                $rsm->addScalarResult('valtozatid', 'valtozatid');
                $rsm->addScalarResult('o1', 'o1');

                $order = array_merge_recursive(array('.o1' => 'ASC'), $order);
                $this->addAktivLathatoFilter($filter);
                $sql = 'SELECT _xx.id,null AS valtozatid,'
                    . ' IF(_xx.nemkaphato,9,0) AS o1'
                    . ' FROM termek _xx'
                    . ' LEFT JOIN termekfa fa1 ON (_xx.termekfa1_id=fa1.id)'
                    . ' LEFT JOIN termekfa fa2 ON (_xx.termekfa2_id=fa2.id)'
                    . ' LEFT JOIN termekfa fa3 ON (_xx.termekfa3_id=fa3.id)'
                    . $this->getFilterString($filter)
                    . $this->getOrderString($order)
                    . $this->getLimitString($offset, $elemcount);
                $q = $this->_em->createNativeQuery($sql, $rsm);
                $params = $this->getQueryParameters($filter);
                $q->setParameters($params);
                return $q->getScalarResult();

            default:
                $this->addAktivLathatoFilter($filter);
                return $this->getWithJoins($filter, $order);
        }
    }

    public function getTermekListaCount($filter) {
        switch (true) {

            case \mkw\store::isMugenrace():
                $this->addAktivLathatoFilter($filter);
                $q = $this->_em->createQuery('SELECT COUNT(_xx)'
                    . ' FROM Entities\Termek _xx'
                    . ' LEFT JOIN _xx.termekfa1 fa1'
                    . ' LEFT JOIN _xx.termekfa2 fa2'
                    . ' LEFT JOIN _xx.termekfa3 fa3'
                    . $this->getFilterString($filter));
                $q->setParameters($this->getQueryParameters($filter));
                if (\mkw\store::isMainMode()) {
                    \mkw\store::setTranslationHint($q, \mkw\store::getLocale());
                }
                break;

            default:
                $this->addAktivLathatoFilter($filter);
                $q = $this->_em->createQuery('SELECT COUNT(_xx)'
                    . ' FROM Entities\Termek _xx'
                    . ' LEFT JOIN _xx.termekfa1 fa1'
                    . ' LEFT JOIN _xx.termekfa2 fa2'
                    . ' LEFT JOIN _xx.termekfa3 fa3'
                    . ' LEFT JOIN _xx.valtozatok v WITH ' . \mkw\store::getWebshopFieldName('v.lathato') . '=true AND ' . \mkw\store::getWebshopFieldName('v.elerheto') . '=true'
                    . $this->getFilterString($filter));
                $q->setParameters($this->getQueryParameters($filter));
                if (\mkw\store::isMainMode()) {
                    \mkw\store::setTranslationHint($q, \mkw\store::getLocale());
                }
                break;
        }
        return $q->getSingleScalarResult();
    }

    public function getTermekListaMaxAr($filter) {
        $this->addAktivLathatoFilter($filter);
        $q = $this->_em->createQuery('SELECT MAX(_xx.brutto+IF(v.brutto IS NULL,0,v.brutto))'
            . ' FROM Entities\Termek _xx'
            . ' LEFT JOIN _xx.termekfa1 fa1'
            . ' LEFT JOIN _xx.termekfa2 fa2'
            . ' LEFT JOIN _xx.termekfa3 fa3'
            . ' LEFT JOIN _xx.valtozatok v WITH ' . \mkw\store::getWebshopFieldName('v.lathato') . '=true AND ' . \mkw\store::getWebshopFieldName('v.elerheto') . '=true'
            . $this->getFilterString($filter));
        $q->setParameters($this->getQueryParameters($filter));
        if (\mkw\store::isMainMode()) {
            \mkw\store::setTranslationHint($q, \mkw\store::getLocale());
        }
        return $q->getSingleScalarResult();
    }

    public function getForSitemapXml() {
        $rsm = new ResultSetMapping();
        $rsm->addScalarResult('id', 'id');
        $rsm->addScalarResult('slug', 'slug');
        $rsm->addScalarResult('lastmod', 'lastmod');
        $rsm->addScalarResult('kepurl', 'kepurl');
        $rsm->addScalarResult('kepleiras', 'kepleiras');
        $q = $this->_em->createNativeQuery('SELECT id,slug,lastmod,kepurl,kepleiras'
            . ' FROM termek '
            . ' WHERE (inaktiv=0) AND (fuggoben=0) AND (' . \mkw\store::getWebshopFieldName('lathato') . '=1)'
            . ' ORDER BY id', $rsm);
        return $q->getScalarResult();
    }

    public function getTermekIds($filter, $order = array()) {
        $this->addAktivLathatoFilter($filter);
        $q = $this->_em->createQuery('SELECT DISTINCT _xx.id '
            . ' FROM Entities\Termek _xx'
            . ' LEFT JOIN _xx.termekfa1 fa1'
            . ' LEFT JOIN _xx.termekfa2 fa2'
            . ' LEFT JOIN _xx.termekfa3 fa3'
            . ' LEFT JOIN _xx.valtozatok v WITH ' . \mkw\store::getWebshopFieldName('v.lathato') . '=true AND ' . \mkw\store::getWebshopFieldName('v.elerheto') . '=true'
            . $this->getFilterString($filter)
            . $this->getOrderString($order));
        $q->setParameters($this->getQueryParameters($filter));
        if (\mkw\store::isMainMode()) {
            \mkw\store::setTranslationHint($q, \mkw\store::getLocale());
        }
        return $q->getScalarResult();
    }

    public function getFeedTermek() {
        $filter = new FilterDescriptor();
        $this->addAktivLathatoFilter($filter);
        $filter->addFilter('nemkaphato', '=', false);

        $q = $this->_em->createQuery('SELECT _xx'
            . ' FROM Entities\Termek _xx'
            . $this->getFilterString($filter)
            . ' ORDER BY _xx.id DESC');
        $q->setParameters($this->getQueryParameters($filter));
        $q->setFirstResult(0);
        $q->setMaxResults(\mkw\store::getParameter(\mkw\consts::Feedtermekdb, 30));
        if (\mkw\store::isMainMode()) {
            \mkw\store::setTranslationHint($q, \mkw\store::getLocale());
        }
        return $q->getResult();
    }

    public function getAjanlottTermekek($db) {
        $filter = new FilterDescriptor();
        $this->addAktivLathatoFilter($filter);
        $filter->addFilter('ajanlott', '=', true);
        if (\mkw\store::isMugenrace()) {
            $karkod = $this->getRepo('Entities\TermekFa')->getKarkod(\mkw\store::getParameter(\mkw\consts::MugenraceKatId));
            if ($karkod) {
                $filter->addFilter(array('termekfa1karkod', 'termekfa2karkod', 'termekfa3karkod'), 'LIKE', $karkod . '%'); // Mugenrace
            }
        }

        $ids = $this->getIdsWithJoins($filter, array());
        $r = array();
        foreach ($ids as $id) {
            $r[] = $id['id'];
        }
        if (count($r) > 0) {
            shuffle($r);
            $filter = new FilterDescriptor();
            $rand = array_rand($r, min($db, count($r)));
            $v = array();
            foreach ((array)$rand as $kulcs) {
                $v[] = $r[$kulcs];
            }
            $filter->addFilter('id', 'IN', $v);
            return $this->getWithJoins($filter, array(), 0, $db);
        }
        else {
            return array();
        }
    }

    public function getAkciosTermekek($db) {
        $filter = new FilterDescriptor();
        $this->addAktivLathatoFilter($filter);
        $filter->addSQL($this->getAkciosFilterSQL());
        if (\mkw\store::isMugenrace()) {
            $karkod = $this->getRepo('Entities\TermekFa')->getKarkod(\mkw\store::getParameter(\mkw\consts::MugenraceKatId));
            if ($karkod) {
                $filter->addFilter(array('termekfa1karkod', 'termekfa2karkod', 'termekfa3karkod'), 'LIKE', $karkod . '%'); // Mugenrace
            }
        }

        $ids = $this->getIdsWithJoins($filter, array());
        $r = array();
        foreach ($ids as $id) {
            $r[] = $id['id'];
        }
        if (count($r) > 0) {
            shuffle($r);
            $filter = new FilterDescriptor();
            $rand = array_rand($r, min($db, count($r)));
            $v = array();
            foreach ((array)$rand as $kulcs) {
                $v[] = $r[$kulcs];
            }
            $filter->addFilter('id', 'IN', $v);
            return $this->getWithJoins($filter, array(), 0, $db);
        }
        else {
            return array();
        }
    }

    public function getKiemeltTermekek($filter, $db) {
        $kiemeltfilter = new FilterDescriptor();
        $this->addAktivLathatoFilter($kiemeltfilter);
        $kiemeltfilter->addFilter('kiemelt', '=', true);
        if (\mkw\store::isMugenrace()) {
            $karkod = $this->getRepo('Entities\TermekFa')->getKarkod(\mkw\store::getParameter(\mkw\consts::MugenraceKatId));
            if ($karkod) {
                $kiemeltfilter->addFilter(array('termekfa1karkod', 'termekfa2karkod', 'termekfa3karkod'), 'LIKE', $karkod . '%'); // Mugenrace
            }
        }
        $kiemeltret = $this->getIdsWithJoins($kiemeltfilter->merge($filter), array());
        $r = array();
        foreach ($kiemeltret as $kiemeltr) {
            $r[] = $kiemeltr['id'];
        }
        if (count($r) > 0) {
            shuffle($r);

            $kiemeltfilter = new FilterDescriptor();
            $rand = array_rand($r, min($db, count($r)));
            $v = array();
            foreach ((array)$rand as $kulcs) {
                $v[] = $r[$kulcs];
            }
            $kiemeltfilter->addFilter('id', 'IN', $v);

            $q = $this->_em->createQuery('SELECT _xx.id,v.id AS valtozatid'
                . ' FROM Entities\Termek _xx'
                . ' LEFT JOIN _xx.termekfa1 fa1'
                . ' LEFT JOIN _xx.termekfa2 fa2'
                . ' LEFT JOIN _xx.termekfa3 fa3'
                . ' LEFT JOIN _xx.valtozatok v WITH ' . \mkw\store::getWebshopFieldName('v.lathato') . '=true AND ' . \mkw\store::getWebshopFieldName('v.elerheto') . '=true'
                . $this->getFilterString($kiemeltfilter));

            $q->setParameters($this->getQueryParameters($kiemeltfilter));
            $q->setMaxResults($db);
            if (\mkw\store::isMainMode()) {
                \mkw\store::setTranslationHint($q, \mkw\store::getLocale());
            }
            return $q->getScalarResult();
        }
        else {
            return array();
        }
    }

    public function getLegnepszerubbTermekek($db) {
        $filter = new FilterDescriptor();
        $this->addAktivLathatoFilter($filter);
        $filter->addFilter('nemkaphato', '=', false);
        if (\mkw\store::isMugenrace()) {
            $karkod = $this->getRepo('Entities\TermekFa')->getKarkod(\mkw\store::getParameter(\mkw\consts::MugenraceKatId));
            if ($karkod) {
                $filter->addFilter(array('termekfa1karkod', 'termekfa2karkod', 'termekfa3karkod'), 'LIKE', $karkod . '%'); // Mugenrace
            }
        }
        $order = array('_xx.nepszeruseg' => 'DESC', 'RAND()' => 'ASC');

        return $this->getWithJoins($filter, $order, 0, $db);
    }

    public function getLegujabbTermekek($db) {
        $filter = new FilterDescriptor();
        $this->addAktivLathatoFilter($filter);
        $filter->addFilter('nemkaphato', '=', false);
        if (\mkw\store::isMugenrace()) {
            $karkod = $this->getRepo('Entities\TermekFa')->getKarkod(\mkw\store::getParameter(\mkw\consts::MugenraceKatId));
            if ($karkod) {
                $filter->addFilter(array('termekfa1karkod', 'termekfa2karkod', 'termekfa3karkod'), 'LIKE', $karkod . '%'); // Mugenrace
            }
        }
        $order = array('_xx.id' => 'DESC');

        return $this->getWithJoins($filter, $order, 0, $db);
    }

    public function getHasonloTermekek($termek, $db, $arszaz) {
        $filter = new FilterDescriptor();
        $this->addAktivLathatoFilter($filter);
        if (\mkw\store::isMugenrace()) {
            $karkod = $this->getRepo('Entities\TermekFa')->getKarkod(\mkw\store::getParameter(\mkw\consts::MugenraceKatId));
            if ($karkod) {
                $filter->addFilter(array('termekfa1karkod', 'termekfa2karkod', 'termekfa3karkod'), 'LIKE', $karkod . '%'); // Mugenrace
            }
        }
        $a = array();
        if ($termek->getTermekfa1Id() > 1) {
            $a[] = $termek->getTermekfa1();
        }
        if ($termek->getTermekfa2Id() > 1) {
            $a[] = $termek->getTermekfa2();
        }
        if ($termek->getTermekfa3Id() > 1) {
            $a[] = $termek->getTermekfa3();
        }
        if ($a) {
            $filter->addFilter(array('termekfa1', 'termekfa2', 'termekfa3'), '=', $a);
        }
        $filter->addFilter('brutto', '>=', $termek->getBrutto() * (100 - $arszaz * 1) / 100)
            ->addFilter('brutto', '<=', $termek->getBrutto() * (100 + $arszaz * 1) / 100)
            ->addFilter('id', '<>', $termek->getId())
            ->addFilter('nemkaphato', '=', false);

        return $this->getWithJoins($filter, array(), 0, $db);
    }

    public function getHozzavasaroltTermekek($termek) {
        if ($termek) {
            if (is_array($termek)) {
                $x = array();
                if ($termek instanceof \Entities\Termek) {
                    $x[] = $termek->getId();
                }
                else {
                    $x = $termek;
                }
                $tlist = implode(',', $x);
            }
            else {
                if ($termek instanceof \Entities\Termek) {
                    $tlist = $termek->getId();
                }
                else {
                    $tlist = $termek * 1;
                }
            }
            $rsm = new ResultSetMapping();
            $rsm->addScalarResult('termek_id', 'termek_id');
            $q = $this->_em->createNativeQuery('SELECT DISTINCT termek_id'
                . ' FROM bizonylattetel bt'
                . ' WHERE (bizonylatfej_id IN (SELECT bizonylatfej_id FROM bizonylattetel albt WHERE (albt.termek_id IN (' . $tlist . ')) AND (albt.irany<1)))'
                . ' AND (bt.termek_id NOT IN (' . $tlist . ')) AND (bt.irany<1)'
                , $rsm);
            $r = $q->getScalarResult();
            $rr = array();
            foreach ($r as $_r) {
                $rr[] = $_r['termek_id'];
            }

            $filter = new FilterDescriptor();
            $this->addAktivLathatoFilter($filter);
            $filter->addFilter('nemkaphato', '=', 'false');
            if (\mkw\store::isMugenrace()) {
                $karkod = $this->getRepo('Entities\TermekFa')->getKarkod(\mkw\store::getParameter(\mkw\consts::MugenraceKatId));
                if ($karkod) {
                    $filter->addFilter(array('termekfa1karkod', 'termekfa2karkod', 'termekfa3karkod'), 'LIKE', $karkod . '%'); // Mugenrace
                }
            }

            if ($rr) {
                $filter->addFilter('id', 'IN', $rr);
            }
            else {
                $filter->addFilter('id', '=', -1);
            }

            return $this->getWithJoins($filter, array());
        }
        return false;
    }

    public function getNevek($keresett) {
        $filter = new FilterDescriptor();
        $this->addAktivLathatoFilter($filter);
        $filter->addFilter('_xx.nev', 'LIKE', '%' . $keresett . '%');
        $order = array('_xx.nev' => 'ASC');
        $q = $this->_em->createQuery('SELECT _xx.nev'
            . ' FROM Entities\Termek _xx'
            . ' LEFT JOIN _xx.vtsz vtsz'
            . ' LEFT JOIN _xx.afa afa'
            . ' LEFT JOIN _xx.termekfa1 fa1'
            . ' LEFT JOIN _xx.termekfa2 fa2'
            . ' LEFT JOIN _xx.termekfa3 fa3'
            . $this->getFilterString($filter)
            . $this->getOrderString($order));
        $q->setParameters($this->getQueryParameters($filter));
        if (\mkw\store::isMainMode()) {
            \mkw\store::setTranslationHint($q, \mkw\store::getLocale());
        }
        $res = $q->getScalarResult();
        $ret = array();
        foreach ($res as $sor) {
            $ret[] = $sor['nev'];
        }
        return $ret;
    }

    public function getBizonylattetelLista($keresett) {
        $filter = new FilterDescriptor();
        $filter->addFilter(array('_xx.nev', '_xx.cikkszam', '_xx.vonalkod'), 'LIKE', '%' . $keresett . '%');
        $order = array('_xx.nev' => 'ASC');
        $q = $this->_em->createQuery('SELECT _xx'
            . ' FROM Entities\Termek _xx'
            . ' LEFT JOIN _xx.vtsz vtsz'
            . ' LEFT JOIN _xx.afa afa'
            . ' LEFT JOIN _xx.termekfa1 fa1'
            . ' LEFT JOIN _xx.termekfa2 fa2'
            . ' LEFT JOIN _xx.termekfa3 fa3'
            . $this->getFilterString($filter)
            . $this->getOrderString($order));
        $q->setParameters($this->getQueryParameters($filter));
        if (\mkw\store::isMainMode()) {
            \mkw\store::setTranslationHint($q, \mkw\store::getLocale());
        }
        $res = $q->getResult();
        return $res;
    }

    public function getUjTermekId() {
        $filter = new FilterDescriptor();
        $this->addAktivLathatoFilter($filter);
        $filter->addFilter('nemkaphato', '=', false);
        if (\mkw\store::isMugenrace()) {
            $karkod = $this->getRepo('Entities\TermekFa')->getKarkod(\mkw\store::getParameter(\mkw\consts::MugenraceKatId));
            if ($karkod) {
                $filter->addFilter(array('termekfa1karkod', 'termekfa2karkod', 'termekfa3karkod'), 'LIKE', $karkod . '%'); // Mugenrace
            }
        }

        $q = $this->_em->createQuery('SELECT _xx.id'
            . ' FROM Entities\Termek _xx'
            . $this->getFilterString($filter)
            . $this->getOrderString(array('id' => 'DESC'))
        );
        $q->setParameters($this->getQueryParameters($filter));
        $q->setMaxResults(100);
        $res = $q->getScalarResult();
        foreach ($res as $r) {
            $ret = $r['id'];
        }
        return $ret;
    }

    public function getTop10Mennyiseg() {
        $filter = new FilterDescriptor();
        $this->addAktivLathatoFilter($filter);
        $filter->addFilter('nemkaphato', '=', false);
        if (\mkw\store::isMugenrace()) {
            $karkod = $this->getRepo('Entities\TermekFa')->getKarkod(\mkw\store::getParameter(\mkw\consts::MugenraceKatId));
            if ($karkod) {
                $filter->addFilter(array('termekfa1karkod', 'termekfa2karkod', 'termekfa3karkod'), 'LIKE', $karkod . '%'); // Mugenrace
            }
        }

        $q = $this->_em->createQuery('SELECT _xx.megvasarlasdb'
            . ' FROM Entities\Termek _xx'
            . $this->getFilterString($filter)
            . $this->getOrderString(array('megvasarlasdb' => 'DESC'))
        );
        $q->setParameters($this->getQueryParameters($filter));
        $q->setMaxResults(10);
        $res = $q->getScalarResult();
        foreach ($res as $r) {
            $ret = $r['megvasarlasdb'];
        }
        return $ret;
    }

    public function calcKeszlet($filter, $plusfields = '', $groupby = '', $order = array()) {
        if ($plusfields) {
            $plusfields = ' ,' . $plusfields . ' ';
        }
        $q = $this->_em->createQuery('SELECT SUM(bt.mennyiseg * bt.irany) AS mennyiseg,'
            . 'SUM(bt.nettohuf * bt.irany) AS nettohuf, SUM(bt.bruttohuf * bt.irany) AS bruttohuf '
            . $plusfields
            . 'FROM Entities\Bizonylattetel bt '
            . 'LEFT JOIN bt.bizonylatfej bf '
            . 'LEFT JOIN bt.termek t'
            . $this->getFilterString($filter)
            . $groupby
            . $this->getOrderString($order));
        $q->setParameters($this->getQueryParameters($filter));
        $res = $q->getScalarResult();
        return $res;
    }

    public function calcNapijelentes($filter) {

        $rsm = new ResultSetMapping();
        $rsm->addScalarResult('mennyiseg', 'mennyiseg');
        $rsm->addScalarResult('nettohuf', 'nettohuf');
        $rsm->addScalarResult('bruttohuf', 'bruttohuf');

        $q = $this->_em->createNativeQuery('SELECT SUM(bt.mennyiseg) AS mennyiseg, SUM(bt.nettohuf) AS nettohuf, SUM(bt.bruttohuf) AS bruttohuf '
            . 'FROM bizonylattetel bt '
            . 'LEFT JOIN bizonylatfej bf ON (bt.bizonylatfej_id=bf.id) '
            . 'LEFT JOIN termek t ON (bt.termek_id=t.id) '
            . 'LEFT JOIN partner p ON (bf.partner_id=p.id) '
            . 'LEFT JOIN partner_cimkek pc ON (pc.partner_id=p.id) '
            . 'LEFT JOIN fizmod f ON (bf.fizmod_id=f.id)'
            . $this->getFilterString($filter), $rsm);
        $q->setParameters($this->getQueryParameters($filter));

        $res = $q->getScalarResult();
        return $res;
    }

    public function getForImport($gyarto, $inaktiv = null) {
        $filter = new \mkwhelpers\FilterDescriptor();
        $filter->addFilter('gyarto', '=', $gyarto);
        if ($inaktiv === false || $inaktiv === true) {
            $filter->addFilter('inaktiv', '=', $inaktiv);
        }
        $q = $this->_em->createQuery('SELECT _xx.id,_xx.idegenkod,_xx.idegencikkszam,_xx.cikkszam'
            . ' FROM Entities\Termek _xx'
            . $this->getFilterString($filter)
        );
        $q->setParameters($this->getQueryParameters($filter));
        return $q->getScalarResult();
    }

    public function getWithValtozatokForImport($gyarto) {
        $filter = new \mkwhelpers\FilterDescriptor();
        $filter->addFilter('gyarto', '=', $gyarto);
        $q = $this->_em->createQuery('SELECT _xx, v'
            . ' FROM Entities\Termek _xx'
            . ' LEFT JOIN _xx.valtozatok v '
            . $this->getFilterString($filter));
        $q->setParameters($this->getQueryParameters($filter));
        return $q->getResult();
    }

    public function getKarton($filter, $order) {
        $q = $this->_em->createQuery('SELECT bt,bf '
            . 'FROM Entities\Bizonylattetel bt '
            . 'LEFT JOIN bt.bizonylatfej bf '
            . $this->getFilterString($filter)
            . $this->getOrderString($order)
        );
        $q->setParameters($this->getQueryParameters($filter));
        return $q->getResult();
    }

    public function clearNepszeruseg() {
        $q = $this->_em->createQuery('UPDATE Entities\Termek x SET x.nepszeruseg = 0');
        $q->Execute();
    }

    public function getMarkaCount($cimke) {
        $rsm = new ResultSetMapping();
        $rsm->addScalarResult('db', 'db');
        $r = array();
        $q = $this->_em->createNativeQuery('SELECT COUNT(*) AS db '
            . 'FROM termek_cimkek tc '
            . 'LEFT OUTER JOIN termek t ON (t.id=tc.termek_id) '
            . 'WHERE (tc.cimketorzs_id=' . $cimke . ') AND (t.inaktiv=0) AND (' . \mkw\store::getWebshopFieldName('t.lathato') . '=1) AND (t.fuggoben=0)', $rsm);
        $res = $q->getScalarResult();
        return $res[0]['db'] * 1;
    }

    public function getKepekKiveve($termek, $valtozat) {
        $altomb = array();
        $kivet = 0;
        if ($valtozat) {
            if (!$valtozat->getTermekfokep()) {
                $kivet = $valtozat->getKepId();
            }
        }
        if ($termek) {
            foreach ($termek->getTermekKepek(true) as $kep) {
                if ($kep->getId() !== $kivet) {
                    $egyed = array();
                    $egyed['kepurl'] = $kep->getUrlLarge();
                    $egyed['kozepeskepurl'] = $kep->getUrlMedium();
                    $egyed['kiskepurl'] = $kep->getUrlSmall();
                    $egyed['minikepurl'] = $kep->getUrlMini();
                    $egyed['eredetikepurl'] = $kep->getUrl();
                    $egyed['leiras'] = $kep->getLeiras();
                    $altomb[] = $egyed;
                }
            }
            if ($kivet != 0) {
                $egyed = array();
                $egyed['kepurl'] = $termek->getKepurlLarge();
                $egyed['kozepeskepurl'] = $termek->getKepUrlMedium();
                $egyed['kiskepurl'] = $termek->getKepUrlSmall();
                $egyed['minikepurl'] = $termek->getKepurlMini();
                $egyed['eredetikepurl'] = $termek->getKepurl();
                $egyed['leiras'] = $termek->getNev();
                $altomb[] = $egyed;
            }
        }
        return $altomb;
    }
}
