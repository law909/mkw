<?php

namespace Entities;

use Doctrine\ORM\Query\ResultSetMapping;

class TermekRepository extends \mkwhelpers\Repository {

    public function __construct($em, \Doctrine\ORM\Mapping\ClassMetadata $class) {
        parent::__construct($em, $class);
        $this->setEntityname('Entities\Termek');
        $this->setOrders(array(
            '1' => array('caption' => 'név szerint', 'order' => array('nev' => 'ASC')),
            '2' => array('caption' => 'cikkszám szerint', 'order' => array('cikkszam' => 'ASC'))
        ));
        $this->setBatches(array(
            'arexport' => 'Export árazáshoz'
        ));
    }

    protected function addAktivLathatoFilter($filter) {
        $filter['fields'][] = 'inaktiv';
        $filter['clauses'][] = '=';
        $filter['values'][] = false;
        $filter['fields'][] = 'lathato';
        $filter['clauses'][] = '=';
        $filter['values'][] = true;
        $filter['fields'][] = 'fuggoben';
        $filter['clauses'][] = '=';
        $filter['values'][] = false;
        return $filter;
    }

    public function getAllForSelectList($filter, $order, $offset = 0, $elemcount = 0) {
        $a = $this->alias;
        $q = $this->_em->createQuery('SELECT ' . $a . '.id,' . $a . '.nev '
                . ' FROM ' . $this->entityname . ' ' . $a
                . $this->getFilterString($filter)
                . $this->getOrderString($order));
        $q->setParameters($this->getQueryParameters($filter));
        if ($offset > 0) {
            $q->setFirstResult($offset);
        }
        if ($elemcount > 0) {
            $q->setMaxResults($elemcount);
        }
        \mkw\Store::setTranslationHint($q);
        return $q->getScalarResult();
    }

    public function getAllForExport() {
        $filter = array();
        $filter = $this->addAktivLathatoFilter($filter);
        $filter['fields'][] = 'termekexportbanszerepel';
        $filter['clauses'][] = '=';
        $filter['values'][] = true;
        $filter['fields'][] = 'nemkaphato';
        $filter['clauses'][] = '=';
        $filter['values'][] = false;
        $a = $this->alias;
        $q = $this->_em->createQuery('SELECT ' . $a
                . ' FROM ' . $this->entityname . ' ' . $a
                . $this->getFilterString($filter)
                . $this->getOrderString(array()));
        $q->setParameters($this->getQueryParameters($filter));
        \mkw\Store::setTranslationHint($q);
        return $q->getResult();
    }

    public function getWithJoins($filter, $order, $offset = 0, $elemcount = 0) {
        $a = $this->alias;
        $q = $this->_em->createQuery('SELECT ' . $a . ',vtsz,afa,fa1,fa2,fa3'
                . ' FROM ' . $this->entityname . ' ' . $a
                . ' LEFT JOIN ' . $a . '.vtsz vtsz'
                . ' LEFT JOIN ' . $a . '.afa afa'
                . ' LEFT JOIN ' . $a . '.termekfa1 fa1'
                . ' LEFT JOIN ' . $a . '.termekfa2 fa2'
                . ' LEFT JOIN ' . $a . '.termekfa3 fa3'
                . $this->getFilterString($filter)
                . $this->getOrderString($order));
        $q->setParameters($this->getQueryParameters($filter));
        if ($offset > 0) {
            $q->setFirstResult($offset);
        }
        if ($elemcount > 0) {
            $q->setMaxResults($elemcount);
        }
        \mkw\Store::setTranslationHint($q);
        return $q->getResult();
    }

    public function getWithArak($filter, $order, $offset = 0, $elemcount = 0) {
        $a = $this->alias;
        $q = $this->_em->createQuery('SELECT ' . $a . ',vtsz,afa,fa1,fa2,fa3,ar,valutanem'
                . ' FROM ' . $this->entityname . ' ' . $a
                . ' LEFT JOIN ' . $a . '.vtsz vtsz'
                . ' LEFT JOIN ' . $a . '.afa afa'
                . ' LEFT JOIN ' . $a . '.termekfa1 fa1'
                . ' LEFT JOIN ' . $a . '.termekfa2 fa2'
                . ' LEFT JOIN ' . $a . '.termekfa3 fa3'
                . ' LEFT JOIN ' . $a . '.termekarak ar'
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
        \mkw\Store::setTranslationHint($q);
        return $q->getResult();
    }

    public function getIdsWithJoins($filter, $order, $offset = 0, $elemcount = 0) {
        $a = $this->alias;
        $q = $this->_em->createQuery('SELECT ' . $a . '.id'
                . ' FROM ' . $this->entityname . ' ' . $a
                . ' LEFT JOIN ' . $a . '.vtsz vtsz'
                . ' LEFT JOIN ' . $a . '.afa afa'
                . ' LEFT JOIN ' . $a . '.termekfa1 fa1'
                . ' LEFT JOIN ' . $a . '.termekfa2 fa2'
                . ' LEFT JOIN ' . $a . '.termekfa3 fa3'
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
        $a = $this->alias;
        $q = $this->_em->createQuery('SELECT COUNT(' . $a . ') FROM ' . $this->entityname . ' ' . $a
                . ' LEFT JOIN ' . $a . '.vtsz vtsz'
                . ' LEFT JOIN ' . $a . '.afa afa'
                . ' LEFT JOIN ' . $a . '.termekfa1 fa1'
                . ' LEFT JOIN ' . $a . '.termekfa2 fa2'
                . ' LEFT JOIN ' . $a . '.termekfa3 fa3'
                . $this->getFilterString($filter));
        $q->setParameters($this->getQueryParameters($filter));
        \mkw\Store::setTranslationHint($q);
        return $q->getSingleScalarResult();
    }

    public function getTermekLista($filter, $order, $offset = null, $elemcount = null) {
        switch (\mkw\Store::getTheme()) {
            case 'mkwcansas':
                $rsm = new ResultSetMapping();
                $rsm->addScalarResult('id', 'id');
                $rsm->addScalarResult('valtozatid', 'valtozatid');
                $rsm->addScalarResult('o1', 'o1');

                $order = array_merge_recursive(array('.o1'=>'ASC'), $order);
                $filter = $this->addAktivLathatoFilter($filter);
                if (\mkw\Store::isArsavok()) {
                    $sql = 'SELECT _xx.id,v.id AS valtozatid,'
                            . ' IF(_xx.nemkaphato,9,0) AS o1'
                            . ' FROM termek _xx'
                            . ' LEFT JOIN termekfa fa1 ON (_xx.termekfa1_id=fa1.id)'
                            . ' LEFT JOIN termekfa fa2 ON (_xx.termekfa2_id=fa2.id)'
                            . ' LEFT JOIN termekfa fa3 ON (_xx.termekfa3_id=fa3.id)'
                            . ' LEFT JOIN termekvaltozat v ON (_xx.id=v.termek_id) AND (v.lathato=1) AND (v.elerheto=1)'
                            . $this->getFilterString($filter)
                            . $this->getOrderString($order)
                            . $this->getLimitString($offset, $elemcount);
                }
                else {
                    $sql = 'SELECT _xx.id,v.id AS valtozatid,'
                            . ' IF(_xx.nemkaphato,9,0)+IF((akciostart<=now() AND akciostop>=now()) OR (akciostart<=now() AND akciostop is null) OR (akciostart is null AND akciostop>=now()),-1,0) AS o1'
                            . ' FROM termek _xx'
                            . ' LEFT JOIN termekfa fa1 ON (_xx.termekfa1_id=fa1.id)'
                            . ' LEFT JOIN termekfa fa2 ON (_xx.termekfa2_id=fa2.id)'
                            . ' LEFT JOIN termekfa fa3 ON (_xx.termekfa3_id=fa3.id)'
                            . ' LEFT JOIN termekvaltozat v ON (_xx.id=v.termek_id) AND (v.lathato=1) AND (v.elerheto=1)'
                            . $this->getFilterString($filter)
                            . $this->getOrderString($order)
                            . $this->getLimitString($offset, $elemcount);
                }
                $q = $this->_em->createNativeQuery($sql, $rsm);
                $params = $this->getQueryParameters($filter);
                $q->setParameters($params);
                return $q->getScalarResult();
            case 'superzone':
                $filter = $this->addAktivLathatoFilter($filter);
                return $this->getWithJoins($filter, $order);
            default :
                throw new Exception('ISMERETLEN THEME: ' . \mkw\Store::getTheme());
        }
    }

    public function getTermekListaCount($filter) {
        $filter = $this->addAktivLathatoFilter($filter);
        $a = $this->alias;
        $q = $this->_em->createQuery('SELECT COUNT(' . $a . ') FROM ' . $this->entityname . ' ' . $a
                . ' LEFT JOIN ' . $a . '.termekfa1 fa1'
                . ' LEFT JOIN ' . $a . '.termekfa2 fa2'
                . ' LEFT JOIN ' . $a . '.termekfa3 fa3'
                . ' LEFT JOIN ' . $a . '.valtozatok v WITH v.lathato=true AND v.elerheto=true'
                . $this->getFilterString($filter));
        $q->setParameters($this->getQueryParameters($filter));
        \mkw\Store::setTranslationHint($q);
        return $q->getSingleScalarResult();
    }

    public function getTermekListaMaxAr($filter) {
        $filter = $this->addAktivLathatoFilter($filter);
        $a = $this->alias;
        $q = $this->_em->createQuery('SELECT MAX(' . $a . '.brutto+IF(v.brutto IS NULL,0,v.brutto)) FROM ' . $this->entityname . ' ' . $a
                . ' LEFT JOIN ' . $a . '.termekfa1 fa1'
                . ' LEFT JOIN ' . $a . '.termekfa2 fa2'
                . ' LEFT JOIN ' . $a . '.termekfa3 fa3'
                . ' LEFT JOIN ' . $a . '.valtozatok v WITH v.lathato=true AND v.elerheto=true'
                . $this->getFilterString($filter));
        $q->setParameters($this->getQueryParameters($filter));
        \mkw\Store::setTranslationHint($q);
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
                . ' WHERE (inaktiv=0) AND (fuggoben=0) AND (lathato=1)'
                . ' ORDER BY id', $rsm);
        return $q->getScalarResult();
    }

    public function getTermekIds($filter, $order) {
        $filter = $this->addAktivLathatoFilter($filter);
        $a = $this->alias;
        $q = $this->_em->createQuery('SELECT DISTINCT ' . $a . '.id '
                . ' FROM ' . $this->entityname . ' ' . $a
                . ' LEFT JOIN ' . $a . '.termekfa1 fa1'
                . ' LEFT JOIN ' . $a . '.termekfa2 fa2'
                . ' LEFT JOIN ' . $a . '.termekfa3 fa3'
                . ' LEFT JOIN ' . $a . '.valtozatok v WITH v.lathato=true AND v.elerheto=true'
                . $this->getFilterString($filter)
                . $this->getOrderString($order));
        $q->setParameters($this->getQueryParameters($filter));
        \mkw\Store::setTranslationHint($q);
        return $q->getScalarResult();
    }

    public function getFeedTermek() {
        $filter = array();
        $filter = $this->addAktivLathatoFilter($filter);
        $filter['fields'][] = 'nemkaphato';
        $filter['clauses'][] = '=';
        $filter['values'][] = false;
        $a = $this->alias;
        $q = $this->_em->createQuery('SELECT ' . $a
                . ' FROM ' . $this->entityname . ' ' . $a
                . $this->getFilterString($filter)
                . ' ORDER BY ' . $a . '.id DESC');
        $q->setParameters($this->getQueryParameters($filter));
        $q->setFirstResult(0);
        $q->setMaxResults(\mkw\Store::getParameter(\mkw\consts::Feedtermekdb, 30));
        \mkw\Store::setTranslationHint($q);
        return $q->getResult();
    }

    public function getAjanlottTermekek($db) {
        $filter = array();
        $filter = $this->addAktivLathatoFilter($filter);
        $filter['fields'][] = 'ajanlott';
        $filter['clauses'][] = '=';
        $filter['values'][] = true;

        $ids = $this->getIdsWithJoins($filter, array());
        $r = array();
        foreach ($ids as $id) {
            $r[] = $id['id'];
        }
        if (count($r) > 0) {
            shuffle($r);
            $filter = array();
            $rand = array_rand($r, min($db, count($r)));
            $v = array();
            foreach ((array) $rand as $kulcs) {
                $v[] = $r[$kulcs];
            }
            $filter['fields'][] = 'id';
            $filter['clauses'][] = 'IN';
            $filter['values'][] = $v;
            return $this->getWithJoins($filter, array(), 0, $db);
        }
        else {
            return array();
        }
    }

    public function getKiemeltTermekek($filter, $db) {
        $kiemeltfilter = array();
        $kiemeltfilter = $this->addAktivLathatoFilter($kiemeltfilter);
        $kiemeltfilter['fields'][] = 'kiemelt';
        $kiemeltfilter['clauses'][] = '=';
        $kiemeltfilter['values'][] = 1;
        $kiemeltret = $this->getIdsWithJoins(array_merge_recursive($filter, $kiemeltfilter), array());
        $r = array();
        foreach ($kiemeltret as $kiemeltr) {
            $r[] = $kiemeltr['id'];
        }
        if (count($r) > 0) {
            shuffle($r);

            $kiemeltfilter = array();
            $rand = array_rand($r, min($db, count($r)));
            $v = array();
            foreach ((array) $rand as $kulcs) {
                $v[] = $r[$kulcs];
            }
            $kiemeltfilter['fields'][] = 'id';
            $kiemeltfilter['clauses'][] = 'IN';
            $kiemeltfilter['values'][] = $v;

            $a = $this->alias;
            $q = $this->_em->createQuery('SELECT ' . $a . '.id,v.id AS valtozatid'
                    . ' FROM ' . $this->entityname . ' ' . $a
                    . ' LEFT JOIN ' . $a . '.termekfa1 fa1'
                    . ' LEFT JOIN ' . $a . '.termekfa2 fa2'
                    . ' LEFT JOIN ' . $a . '.termekfa3 fa3'
                    . ' LEFT JOIN ' . $a . '.valtozatok v WITH v.lathato=true AND v.elerheto=true'
                    . $this->getFilterString($kiemeltfilter));

            $q->setParameters($this->getQueryParameters($kiemeltfilter));
            $q->setMaxResults($db);
            \mkw\Store::setTranslationHint($q);
            return $q->getScalarResult();
        }
        else {
            return array();
        }
    }

    public function getLegnepszerubbTermekek($db) {
        $filter = array();
        $filter = $this->addAktivLathatoFilter($filter);
        $filter['fields'][] = 'nemkaphato';
        $filter['clauses'][] = '=';
        $filter['values'][] = false;
        $order = array('_xx.megtekintesdb' => 'DESC');

        return $this->getWithJoins($filter, $order, 0, $db);
    }

    public function getLegujabbTermekek($db) {
        $filter = array();
        $filter = $this->addAktivLathatoFilter($filter);
        $filter['fields'][] = 'nemkaphato';
        $filter['clauses'][] = '=';
        $filter['values'][] = false;
        $order = array('_xx.id' => 'DESC');

        return $this->getWithJoins($filter, $order, 0, $db);
    }

    public function getHasonloTermekek($termek, $db, $arszaz) {
        $filter = array();
        $filter = $this->addAktivLathatoFilter($filter);
        $filter['fields'][] = 'nemkaphato';
        $filter['clauses'][] = '=';
        $filter['values'][] = false;

        $filter['fields'][] = array('termekfa1', 'termekfa2', 'termekfa3');
        $filter['clauses'][] = '=';
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
        $filter['values'][] = $a;

        $filter['fields'][] = 'brutto';
        $filter['clauses'][] = '>=';
        $filter['values'][] =  $termek->getBrutto() * (100 - $arszaz * 1) / 100;

        $filter['fields'][] = 'brutto';
        $filter['clauses'][] = '<=';
        $filter['values'][] =  $termek->getBrutto() * (100 + $arszaz * 1) / 100;

        $filter['fields'][] = 'id';
        $filter['clauses'][] = '<>';
        $filter['values'][] = $termek->getId();

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
            foreach($r as $_r) {
                $rr[] = $_r['termek_id'];
            }

            $filter = array();
            $filter = $this->addAktivLathatoFilter($filter);
            $filter['fields'][] = 'nemkaphato';
            $filter['clauses'][] = '=';
            $filter['values'][] = false;

            if ($rr) {
                $filter['fields'][] = 'id';
                $filter['clauses'][] = 'IN';
                $filter['values'][] = $rr;
            }
            else {
                $filter['fields'][] = 'id';
                $filter['clauses'][] = '=';
                $filter['values'][] = '-1';
            }

            return $this->getWithJoins($filter, array());
        }
        return false;
    }

    public function getNevek($keresett) {
        $a = $this->alias;
        $filter = array();
        $filter = $this->addAktivLathatoFilter($filter);
        $filter['fields'][] = '_xx.nev';
        $filter['clauses'][] = 'LIKE';
        $filter['values'][] = '%' . $keresett . '%';
        $order = array('_xx.nev' => 'ASC');
        $q = $this->_em->createQuery('SELECT ' . $a . '.nev'
                . ' FROM ' . $this->entityname . ' ' . $a
                . ' LEFT JOIN ' . $a . '.vtsz vtsz'
                . ' LEFT JOIN ' . $a . '.afa afa'
                . ' LEFT JOIN ' . $a . '.termekfa1 fa1'
                . ' LEFT JOIN ' . $a . '.termekfa2 fa2'
                . ' LEFT JOIN ' . $a . '.termekfa3 fa3'
                . $this->getFilterString($filter)
                . $this->getOrderString($order));
        $q->setParameters($this->getQueryParameters($filter));
        \mkw\Store::setTranslationHint($q);
        $res = $q->getScalarResult();
        $ret = array();
        foreach ($res as $sor) {
            $ret[] = $sor['nev'];
        }
        return $ret;
    }

    public function getBizonylattetelLista($keresett) {
        $a = $this->alias;
        $filter = array();
        $filter['fields'][] = array('_xx.nev','_xx.cikkszam','_xx.vonalkod');
        $filter['clauses'][] = 'LIKE';
        $filter['values'][] = '%' . $keresett . '%';
        $order = array('_xx.nev' => 'ASC');
        $q = $this->_em->createQuery('SELECT ' . $a
                . ' FROM ' . $this->entityname . ' ' . $a
                . ' LEFT JOIN ' . $a . '.vtsz vtsz'
                . ' LEFT JOIN ' . $a . '.afa afa'
                . ' LEFT JOIN ' . $a . '.termekfa1 fa1'
                . ' LEFT JOIN ' . $a . '.termekfa2 fa2'
                . ' LEFT JOIN ' . $a . '.termekfa3 fa3'
                . $this->getFilterString($filter)
                . $this->getOrderString($order));
        $q->setParameters($this->getQueryParameters($filter));
        \mkw\Store::setTranslationHint($q);
        $res = $q->getResult();
        return $res;
    }

    public function getUjTermekId() {
        $a = $this->alias;
        $filter = array();
        $filter = $this->addAktivLathatoFilter($filter);
        $filter['fields'][] = 'nemkaphato';
        $filter['clauses'][] = '=';
        $filter['values'][] = false;

        $q = $this->_em->createQuery('SELECT ' . $a .'.id '
                . ' FROM ' . $this->entityname . ' ' . $a
                . $this->getFilterString($filter)
                . $this->getOrderString(array('id' => 'DESC'))
        );
        $q->setParameters($this->getQueryParameters($filter));
        $q->setMaxResults(100);
        $res = $q->getScalarResult();
        foreach($res as $r) {
            $ret = $r['id'];
        }
        return $ret;
    }

    public function getTop10Mennyiseg() {
        $a = $this->alias;
        $filter = array();
        $filter = $this->addAktivLathatoFilter($filter);
        $filter['fields'][] = 'nemkaphato';
        $filter['clauses'][] = '=';
        $filter['values'][] = false;

        $q = $this->_em->createQuery('SELECT ' . $a .'.megvasarlasdb '
                . ' FROM ' . $this->entityname . ' ' . $a
                . $this->getFilterString($filter)
                . $this->getOrderString(array('megvasarlasdb' => 'DESC'))
        );
        $q->setParameters($this->getQueryParameters($filter));
        $q->setMaxResults(10);
        $res = $q->getScalarResult();
        foreach($res as $r) {
            $ret = $r['megvasarlasdb'];
        }
        return $ret;
    }
}
