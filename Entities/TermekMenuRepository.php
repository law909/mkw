<?php

namespace Entities;

use Doctrine\ORM\Query\ResultSetMapping;

class TermekMenuRepository extends \mkwhelpers\Repository
{

    private $fatomb;

    public function __construct($em, \Doctrine\ORM\Mapping\ClassMetadata $class)
    {
        parent::__construct($em, $class);
        $this->setEntityname(TermekMenu::class);
        $this->setOrders([
            '1' => ['caption' => 'név szerint növekvő', 'order' => ['_xx.nev' => 'ASC']]
        ]);
    }

    public function regenerateKarKod()
    {
        $rsm = new ResultSetMapping();
        $rsm->addScalarResult('id', 'id');
        $rsm->addScalarResult('parent_id', 'parent_id');
        $q = $this->_em->createNativeQuery('SELECT id,parent_id FROM termekmenu ORDER BY parent_id,id', $rsm);
        $this->fatomb = $q->getScalarResult();
        $this->_regenerateKarKod(0, '');
    }

    private function _regenerateKarKod($szuloid, $szulokarkod)
    {
        foreach ($this->fatomb as $key => $val) {
            if ($val['parent_id'] == $szuloid) {
                $q = $this->_em->createQuery(
                    'UPDATE Entities\TermekMenu x SET x.karkod=\'' . $szulokarkod . sprintf('%05d', $val['id']) . '\' WHERE x.id=' . $val['id']
                );
                $q->Execute();
                $q = $this->_em->createQuery(
                    'UPDATE Entities\Termek x SET x.termekmenu1karkod=\'' . $szulokarkod . sprintf('%05d', $val['id']) . '\' WHERE x.termekmenu1=' . $val['id']
                );
                $q->Execute();
                $q = $this->_em->createQuery(
                    'UPDATE Entities\Blogposzt x SET x.termekmenu1karkod=\'' . $szulokarkod . sprintf(
                        '%05d',
                        $val['id']
                    ) . '\' WHERE x.termekmenu1=' . $val['id']
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
        $q = $this->_em->createQuery('SELECT f FROM Entities\TermekMenu f WHERE f.menu' . $menunum . 'lathato=1 ' . $webshopfilter . ' ORDER BY f.sorrend');
        $res = $q->getResult();
        $ret = [];
        /** @var TermekMenu $r */
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
        $q = $this->_em->createQuery('SELECT f FROM Entities\TermekMenu f WHERE ' . $webshopfilter . ' ORDER BY f.sorrend,f.nev');
        $res = $q->getResult();
        $ret = [];
        /** @var TermekMenu $r */
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
            . 'FROM termekmenu f '
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
            . 'FROM termekmenu f '
            . 'WHERE parent_id=' . $parentid . $filterstr . ' '
            . 'ORDER BY sorrend,nev',
            $rsm
        );
        return $q->getScalarResult();
    }

    /**
     * Csak olyan menüág kerül a sitemapba, aminek van megjeleníthető tartalma: vagy látható
     * alkategóriája (csempés lap), vagy saját, publikus terméke. Az üres kategórialap vékony
     * tartalom, amit a storefront amúgy is noindex-szel ad ki.
     *
     * A láthatóság feltétele ugyanaz, mint a mainController::termekmenu()-ben (inaktiv + lathato):
     * a menuNlathato csak azt mondja meg, melyik menüben jelenik meg az ág, nem azt, hogy elérhető-e.
     */
    /** The root node of the menu (one per menu: the node without parent). */
    public function getRoot(TermekMenuFa $fa): ?TermekMenu
    {
        return $this->findOneBy(['termekmenufa' => $fa, 'parent' => null], ['id' => 'ASC']);
    }

    public function findOneBySlugInFa(TermekMenuFa $fa, string $slug): ?TermekMenu
    {
        return $this->findOneBy(['termekmenufa' => $fa, 'slug' => $slug]);
    }

    /** Active category pages of the menu: a node with an active child or a visible product placed into it. */
    public function getForSitemapXml(TermekMenuFa $fa)
    {
        $rsm = new ResultSetMapping();
        $rsm->addScalarResult('id', 'id');
        $rsm->addScalarResult('slug', 'slug');
        $rsm->addScalarResult('lastmod', 'lastmod');
        $rsm->addScalarResult('kepurl', 'kepurl');
        $rsm->addScalarResult('kepleiras', 'kepleiras');
        $lathato = \mkw\store::getWebshopFieldName('t.lathato');
        $q = $this->_em->createNativeQuery(
            'SELECT m.id,m.slug,m.lastmod,m.kepurl,m.kepleiras'
            . ' FROM termekmenu m'
            . ' WHERE (m.termekmenufa_id = ' . (int)$fa->getId() . ') AND ((m.inaktiv=0) OR (m.inaktiv IS NULL))'
            . ' AND (m.parent_id IS NOT NULL)'
            . ' AND (m.slug IS NOT NULL) AND (m.slug <> "")'
            . ' AND ('
            . '   EXISTS (SELECT 1 FROM termekmenu c WHERE c.parent_id=m.id AND ((c.inaktiv=0) OR (c.inaktiv IS NULL)))'
            . '   OR EXISTS (SELECT 1 FROM termekmenutermek tmt JOIN termek t ON t.id=tmt.termek_id'
            . '     WHERE tmt.termekmenu_id=m.id AND t.inaktiv=0 AND t.fuggoben=0 AND ' . $lathato . '=1)'
            . ' )'
            . ' ORDER BY m.id',
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

}