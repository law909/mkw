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

    /** The root node of the menu (one per menu: the node without parent). */
    public function getRoot(TermekMenuFa $fa): ?TermekMenu
    {
        return $this->findOneBy(['termekmenufa' => $fa, 'parent' => null], ['id' => 'ASC']);
    }

    public function findOneBySlugInFa(TermekMenuFa $fa, string $slug): ?TermekMenu
    {
        return $this->findOneBy(['termekmenufa' => $fa, 'slug' => $slug]);
    }

    /**
     * Only a node with something to show goes to the sitemap: an active child (tile page) or a visible product of its
     * own; an empty category page is thin content the storefront serves noindex anyway.
     */
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