<?php

namespace Entities;

use Doctrine\ORM\Query\ResultSetMapping;
use mkwhelpers\FilterDescriptor;

class TermekFaRepository extends \mkwhelpers\Repository
{

    private $fatomb;

    public function __construct($em, \Doctrine\ORM\Mapping\ClassMetadata $class)
    {
        parent::__construct($em, $class);
        $this->setEntityname(TermekFa::class);
        $this->setOrders([
            '1' => ['caption' => 'név szerint növekvő', 'order' => ['_xx.nev' => 'ASC']]
        ]);
    }

    public function regenerateKarKod()
    {
        $rsm = new ResultSetMapping();
        $rsm->addScalarResult('id', 'id');
        $rsm->addScalarResult('parent_id', 'parent_id');
        $q = $this->_em->createNativeQuery('SELECT id,parent_id FROM termekfa ORDER BY parent_id,id', $rsm);
        $this->fatomb = $q->getScalarResult();
        $this->_regenerateKarKod(0, '');
    }

    private function _regenerateKarKod($szuloid, $szulokarkod)
    {
        foreach ($this->fatomb as $key => $val) {
            if ($val['parent_id'] == $szuloid) {
                $q = $this->_em->createQuery(
                    'UPDATE Entities\TermekFa x SET x.karkod=\'' . $szulokarkod . sprintf('%05d', $val['id']) . '\' WHERE x.id=' . $val['id']
                );
                $q->Execute();
                $q = $this->_em->createQuery(
                    'UPDATE Entities\Termek x SET x.termekfa1karkod=\'' . $szulokarkod . sprintf('%05d', $val['id']) . '\' WHERE x.termekfa1=' . $val['id']
                );
                $q->Execute();
                $q = $this->_em->createQuery(
                    'UPDATE Entities\Termek x SET x.termekfa2karkod=\'' . $szulokarkod . sprintf('%05d', $val['id']) . '\' WHERE x.termekfa2=' . $val['id']
                );
                $q->Execute();
                $q = $this->_em->createQuery(
                    'UPDATE Entities\Termek x SET x.termekfa3karkod=\'' . $szulokarkod . sprintf('%05d', $val['id']) . '\' WHERE x.termekfa3=' . $val['id']
                );
                $q->Execute();
                $q = $this->_em->createQuery(
                    'UPDATE Entities\Blogposzt x SET x.termekfa1karkod=\'' . $szulokarkod . sprintf('%05d', $val['id']) . '\' WHERE x.termekfa1=' . $val['id']
                );
                $q->Execute();
                $q = $this->_em->createQuery(
                    'UPDATE Entities\Blogposzt x SET x.termekfa2karkod=\'' . $szulokarkod . sprintf('%05d', $val['id']) . '\' WHERE x.termekfa2=' . $val['id']
                );
                $q->Execute();
                $q = $this->_em->createQuery(
                    'UPDATE Entities\Blogposzt x SET x.termekfa3karkod=\'' . $szulokarkod . sprintf('%05d', $val['id']) . '\' WHERE x.termekfa3=' . $val['id']
                );
                $q->Execute();
                $this->_regenerateKarKod($val['id'], $szulokarkod . sprintf('%05d', $val['id']));
            }
        }
    }

    public function regenerateSlug()
    {
        $res = $this->getAll([], []);
        foreach ($res as $a) {
            $orgnev = $a->getNev();
            $a->setNev($orgnev . 'x');
            $this->_em->Persist($a);
            $this->_em->Flush();
            $a->setNev($orgnev);
            $this->_em->Persist($a);
            $this->_em->Flush();
        }
    }

    public function getForMenu($menunum, $webshopnum = null)
    {
        $webshopfilter = '';
        if ($webshopnum) {
            if ($webshopnum == 1) {
                $webshopfilter = ' AND (f.lathato=1) ';
            } else {
                $webshopfilter = ' AND (f.lathato' . $webshopnum . '=1) ';
            }
        }
        $q = $this->_em->createQuery('SELECT f FROM Entities\TermekFa f WHERE f.menu' . $menunum . 'lathato=1 ' . $webshopfilter . ' ORDER BY f.sorrend');
        $res = $q->getResult();
        $ret = [];
        /** @var TermekFa $r */
        foreach ($res as $r) {
            $ret[] = [
                'id' => $r->getId(),
                'caption' => $r->getLocalizedFieldValue('nev'),
                'slug' => $r->getSlug(),
                'leiras' => $r->getLocalizedFieldValue('leiras'),
                'rovidleiras' => $r->getLocalizedFieldValue('rovidleiras'),
                'kepurl' => $r->getKepurl(),
                'kepleiras' => $r->getKepleiras(),
                'sorrend' => $r->getSorrend(),
                'karkod' => $r->getKarkod()
            ];
        }
        return $ret;
    }

    public function getForFilter($webshopnum = null)
    {
        $webshopfilter = '';
        if ($webshopnum) {
            if ($webshopnum == 1) {
                $webshopfilter = ' (f.lathato=1) ';
            } else {
                $webshopfilter = ' (f.lathato' . $webshopnum . '=1) ';
            }
        }
        $q = $this->_em->createQuery('SELECT f FROM Entities\TermekFa f WHERE ' . $webshopfilter . ' ORDER BY f.sorrend,f.nev');
        $res = $q->getResult();
        $ret = [];
        /** @var TermekFa $r */
        foreach ($res as $r) {
            $ret[] = [
                'id' => $r->getId(),
                'caption' => $r->getLocalizedFieldValue('nev'),
                'slug' => $r->getSlug(),
                'leiras' => $r->getLocalizedFieldValue('leiras'),
                'rovidleiras' => $r->getLocalizedFieldValue('rovidleiras'),
                'kepurl' => $r->getKepurl(),
                'kepleiras' => $r->getKepleiras(),
                'sorrend' => $r->getSorrend(),
                'karkod' => $r->getKarkod()
            ];
        }
        return $ret;
    }

    public function getForParentCount($parentid, $menunum = 0)
    {
        $filterstr = '';
        if ($menunum > 0) {
            $filterstr = ' AND menu' . $menunum . 'lathato=1';
        }
        $rsm = new ResultSetMapping();
        $rsm->addScalarResult('darab', 'darab');
        $q = $this->_em->createNativeQuery(
            'SELECT COUNT(*) AS darab '
            . 'FROM termekfa f '
            . 'WHERE parent_id=' . $parentid . $filterstr,
            $rsm
        );
        return $q->getScalarResult();
    }

    public function getForParent($parentid, $menunum = 0)
    {
        $filterstr = '';
        if ($menunum > 0) {
            $filterstr = ' AND menu' . $menunum . 'lathato=1';
        }
        $nevfieldname = \mkw\store::getLocalizedFieldName('nev');
        $leirasfieldname = \mkw\store::getLocalizedFieldName('leiras');
        $rsm = new ResultSetMapping();
        $rsm->addScalarResult('id', 'id');
        $rsm->addScalarResult($nevfieldname, 'caption');
        $rsm->addScalarResult('slug', 'slug');
        $rsm->addScalarResult('karkod', 'karkod');
        $rsm->addScalarResult($leirasfieldname, 'leiras');
        $rsm->addScalarResult('kepurl', 'kepurl');
        $rsm->addScalarResult('kepleiras', 'kepleiras');
        $rsm->addScalarResult('sorrend', 'sorrend');
        $q = $this->_em->createNativeQuery(
            'SELECT id,' . $nevfieldname . ',slug,karkod,' . $leirasfieldname . ',kepurl,kepleiras,'
            . 'sorrend '
            . 'FROM termekfa f '
            . 'WHERE parent_id=' . $parentid . $filterstr . ' '
            . 'ORDER BY sorrend,nev',
            $rsm
        );
        return $q->getScalarResult();
    }

    public function getForSitemapXml()
    {
        $rsm = new ResultSetMapping();
        $rsm->addScalarResult('id', 'id');
        $rsm->addScalarResult('slug', 'slug');
        $rsm->addScalarResult('lastmod', 'lastmod');
        $rsm->addScalarResult('kepurl', 'kepurl');
        $rsm->addScalarResult('kepleiras', 'kepleiras');
        $q = $this->_em->createNativeQuery(
            'SELECT id,slug,lastmod,kepurl,kepleiras'
            . ' FROM termekfa WHERE ((inaktiv=0) OR (inaktiv IS NULL)) AND ((menu1lathato=1) OR (menu2lathato=1) OR (menu3lathato=1) OR (menu4lathato=1))'
            . ' ORDER BY id',
            $rsm
        );
        return $q->getScalarResult();
    }

    public function getKarkod($id)
    {
        $o = $this->find($id);
        if ($o) {
            return $o->getKarkod();
        }
        return false;
    }

    public function getB2BArray()
    {
        $tfrsm = new ResultSetMapping();
        $tfrsm->addScalarResult('id', 'id');
        $tfrsm->addScalarResult('karkod', 'karkod');
        $tfrsm->addScalarResult('sorrend', 'sorrend');
        $tfrsm->addScalarResult('fanev', 'fanev');
        $nevfieldname = \mkw\store::getLocalizedFieldName('tf.nev');
        return $this->_em->createNativeQuery(
            'SELECT tf.id,tf.slug,tf.karkod,tf.sorrend,' . $nevfieldname . ' AS fanev '
            . 'FROM termekfa tf '
            . 'WHERE tf.menu1lathato=1 and tf.lathato=1 '
            . 'ORDER BY sorrend',
            $tfrsm
        )->getScalarResult();
    }

    public function getB2B()
    {
        $filter = new FilterDescriptor();
        $filter->addFilter('menu1lathato', '=', 1);
        $filter->addFilter('lathato', '=', 1);
        return $this->getAll($filter, ['sorrend' => 'ASC']);
    }

}