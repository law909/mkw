<?php

namespace Entities;

use Doctrine\ORM\Query\ResultSetMapping;

class TermekFaRepository extends \mkwhelpers\Repository {

    private $fatomb;

    public function __construct($em, \Doctrine\ORM\Mapping\ClassMetadata $class) {
        parent::__construct($em, $class);
        $this->setEntityname('Entities\TermekFa');
        $this->setOrders(array(
            '1' => array('caption' => 'név szerint növekvő', 'order' => array('_xx.nev' => 'ASC'))
        ));
        /* MINTA
          $this->setBatches(array(
          '1'=>'áthelyezés másik címkecsoportba'
          ));
         */
    }

    public function regenerateKarKod() {
        $rsm = new ResultSetMapping();
        $rsm->addScalarResult('id', 'id');
        $rsm->addScalarResult('parent_id', 'parent_id');
        $q = $this->_em->createNativeQuery('SELECT id,parent_id FROM termekfa ORDER BY parent_id,id', $rsm);
        $this->fatomb = $q->getScalarResult();
        $this->_regenerateKarKod(0, '');
    }

    private function _regenerateKarKod($szuloid, $szulokarkod) {
//		$rsm=new ResultSetMapping();
        foreach ($this->fatomb as $key => $val) {
            if ($val['parent_id'] == $szuloid) {
                $q = $this->_em->createQuery('UPDATE ' . $this->entityname . ' x SET x.karkod=\'' . $szulokarkod . sprintf('%05d', $val['id']) . '\' WHERE x.id=' . $val['id']);
                $q->Execute();
                $q = $this->_em->createQuery('UPDATE Entities\Termek x SET x.termekfa1karkod=\'' . $szulokarkod . sprintf('%05d', $val['id']) . '\' WHERE x.termekfa1=' . $val['id']);
                $q->Execute();
                $q = $this->_em->createQuery('UPDATE Entities\Termek x SET x.termekfa2karkod=\'' . $szulokarkod . sprintf('%05d', $val['id']) . '\' WHERE x.termekfa2=' . $val['id']);
                $q->Execute();
                $q = $this->_em->createQuery('UPDATE Entities\Termek x SET x.termekfa3karkod=\'' . $szulokarkod . sprintf('%05d', $val['id']) . '\' WHERE x.termekfa3=' . $val['id']);
                $q->Execute();
                $this->_regenerateKarKod($val['id'], $szulokarkod . sprintf('%05d', $val['id']));
            }
        }
    }

    public function getForMenu($menunum) {
        $rsm = new ResultSetMapping();
        $rsm->addScalarResult('id', 'id');
        $rsm->addScalarResult('nev', 'caption');
        $rsm->addScalarResult('slug', 'slug');
        $rsm->addScalarResult('leiras', 'leiras');
        $rsm->addScalarResult('rovidleiras', 'rovidleiras');
        $rsm->addScalarResult('kepurl', 'kepurl');
        $rsm->addScalarResult('kepleiras', 'kepleiras');
//		$rsm->addScalarResult('termekdarab', 'termekdarab');
        $rsm->addScalarResult('sorrend', 'sorrend');
        $q = $this->_em->createNativeQuery('SELECT id,nev,slug,leiras,rovidleiras,kepurl,kepleiras,'
//			.'(SELECT COUNT(*) FROM termek t WHERE (t.inaktiv=0) AND (t.lathato=1) AND ((t.termekfa1karkod LIKE CONCAT(f.karkod,\'%\')) OR (t.termekfa2karkod LIKE CONCAT(f.karkod,\'%\')) OR (t.termekfa3karkod LIKE CONCAT(f.karkod,\'%\')))) AS termekdarab,'
                . 'sorrend '
                . 'FROM termekfa f '
                . 'WHERE menu' . $menunum . 'lathato=1 '
                . 'ORDER BY sorrend,nev', $rsm);
        return $q->getScalarResult();
    }

    public function getForParentCount($parentid, $menunum = 0) {
        $filterstr = '';
        if ($menunum > 0) {
            $filterstr = ' AND menu' . $menunum . 'lathato=1';
        }
        $rsm = new ResultSetMapping();
        $rsm->addScalarResult('darab', 'darab');
        $q = $this->_em->createNativeQuery('SELECT COUNT(*) AS darab '
                . 'FROM termekfa f '
                . 'WHERE parent_id=' . $parentid . $filterstr, $rsm);
        return $q->getScalarResult();
    }

    public function getForParent($parentid, $menunum = 0) {
        $filterstr = '';
        if ($menunum > 0) {
            $filterstr = ' AND menu' . $menunum . 'lathato=1';
        }
        $rsm = new ResultSetMapping();
        $rsm->addScalarResult('id', 'id');
        $rsm->addScalarResult('nev', 'caption');
        $rsm->addScalarResult('slug', 'slug');
        $rsm->addScalarResult('leiras', 'leiras');
        $rsm->addScalarResult('kepurl', 'kepurl');
        $rsm->addScalarResult('kepleiras', 'kepleiras');
//		$rsm->addScalarResult('termekdarab', 'termekdarab');
        $rsm->addScalarResult('sorrend', 'sorrend');
        $q = $this->_em->createNativeQuery('SELECT id,nev,slug,leiras,kepurl,kepleiras,'
//			.'(SELECT COUNT(*) FROM termek t WHERE (t.inaktiv=0) AND (t.lathato=1) AND ((t.termekfa1karkod LIKE CONCAT(f.karkod,\'%\')) OR (t.termekfa2karkod LIKE CONCAT(f.karkod,\'%\')) OR (t.termekfa3karkod LIKE CONCAT(f.karkod,\'%\')))) AS termekdarab,'
                . 'sorrend '
                . 'FROM termekfa f '
                . 'WHERE parent_id=' . $parentid . $filterstr . ' '
                . 'ORDER BY sorrend,nev', $rsm);
        return $q->getScalarResult();
    }

    public function getForSitemapXml() {
        $rsm = new ResultSetMapping();
        $rsm->addScalarResult('id', 'id');
        $rsm->addScalarResult('slug', 'slug');
        $rsm->addScalarResult('lastmod', 'lastmod');
        $rsm->addScalarResult('kepurl', 'kepurl');
        $rsm->addScalarResult('kepleiras', 'kepleiras');
        $q = $this->_em->createNativeQuery('SELECT id,slug,lastmod,kepurl,kepleiras'
                . ' FROM termekfa WHERE (inaktiv=0) OR (inaktiv IS NULL)'
                . ' ORDER BY id', $rsm);
        return $q->getScalarResult();
    }
}
