<?php
namespace Entities;

use Doctrine\ORM\Query\ResultSetMapping;

class FifoRepository extends \mkwhelpers\Repository {

    private $be;
    private $ki;
    private $ujki = array();

    public function __construct($em, \Doctrine\ORM\Mapping\ClassMetadata $class) {
        parent::__construct($em, $class);
        $this->setEntityname('Entities\Fifo');
        $this->setOrders(array(
            '1' => array('caption' => 'név szerint', 'order' => array('_xx.nev' => 'ASC'))
        ));
    }

    public function getWithJoins($filter, $order, $offset = 0, $elemcount = 0) {
        $q = $this->_em->createQuery('SELECT _xx,kibf,kibt,bebf,bebt,r,t,tv'
            . ' FROM Entities\Fifo _xx'
            . ' LEFT JOIN _xx.kibizonylatfej kibf'
            . ' LEFT JOIN _xx.kibizonylattetel kibt'
            . ' LEFT JOIN _xx.bebizonylatfej bebf'
            . ' LEFT JOIN _xx.bebizonylattetel bebt'
            . ' LEFT JOIN _xx.raktar r'
            . ' LEFT JOIN _xx.termek t'
            . ' LEFT JOIN _xx.termekvaltozat tv'
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

    private function clearData() {
        $this->_em->getConnection()->executeUpdate('DELETE FROM fifo');
        $this->_em->getConnection()->executeUpdate('DELETE FROM keszlet');
    }

    public function loadData($stornokell, $tid = null, $vid = null, $cikksz = null) {
        if ($stornokell) {
            $stornosql = '';
        }
        else {
            $stornosql = ' AND ((bf.storno=0) OR (bf.storno IS NULL)) AND ((bf.stornozott=0) OR (bf.stornozott IS NULL)) ';
        }
        $sql1 = '';
        $sql2 = '';
        if ($cikksz) {
            $tr = $this->_em->getRepository('Entities\Termek')->findBy(array('cikkszam' => $cikksz));
            if ($tr) {
                $sql1 = ' AND (bt.termek_id = ' . $tr[0]->getId() . ')';
            }
            else {
                $sql1 = 'AND (1=0)';
            }
        }
        else {
            if ($tid) {
                $sql1 = ' AND (bt.termek_id = ' . $tid . ')';
            }
            if ($vid) {
                $sql2 = ' AND (bt.termekvaltozat_id = ' . $vid . ')';
            }
        }
        $rsm = new ResultSetMapping();
        $rsm->addScalarResult('id', 'id');
        $rsm->addScalarResult('btid', 'tetelid');
        $rsm->addScalarResult('irany', 'irany');
        $rsm->addScalarResult('teljesites', 'teljesites');
        $rsm->addScalarResult('raktar_id', 'raktarid');
        $rsm->addScalarResult('termek_id', 'termekid');
        $rsm->addScalarResult('termekvaltozat_id', 'valtozatid');
        $rsm->addScalarResult('mennyiseg', 'mennyiseg');
        $q = $this->_em->createNativeQuery(
            'SELECT bf.id,bt.id AS btid,bf.irany,bf.teljesites,bf.raktar_id,termek_id,termekvaltozat_id,mennyiseg ' .
            'FROM bizonylatfej bf, bizonylattetel bt ' .
            'WHERE (bf.id=bt.bizonylatfej_id) AND (bt.mozgat=1) AND ' .
            '(((bf.irany>0) AND (bt.mennyiseg>0)) OR ((bf.irany<0) AND (bt.mennyiseg<0))) ' .
            $stornosql . $sql1 . $sql2 .
            ' ORDER BY raktar_id,termek_id,termekvaltozat_id,teljesites'
            , $rsm);
        $this->be = $q->getScalarResult();
        $cikl = 0;
        $db = count($this->be) - 1;
        while ($cikl <= $db) {
            $this->be[$cikl]['mennyiseg'] = $this->be[$cikl]['mennyiseg'] * $this->be[$cikl]['irany'];
            $this->be[$cikl]['maradek'] = $this->be[$cikl]['mennyiseg'];
            $cikl++;
        }

        $q = $this->_em->createNativeQuery(
            'SELECT bf.id,bt.id AS btid,bf.irany,bf.teljesites,bf.raktar_id,termek_id,termekvaltozat_id,mennyiseg ' .
            'FROM bizonylatfej bf, bizonylattetel bt ' .
            'WHERE (bf.id=bt.bizonylatfej_id) AND (bt.mozgat=1) AND ' .
            '(((bf.irany<0) AND (bt.mennyiseg>0)) OR ((bf.irany>0) AND (bt.mennyiseg<0))) ' .
            $stornosql . $sql1 . $sql2 .
            ' ORDER BY raktar_id,termek_id,termekvaltozat_id,teljesites'
            , $rsm);
        $this->ki = $q->getScalarResult();
        $cikl = 0;
        $db = count($this->ki) - 1;
        while ($cikl <= $db) {
            $this->ki[$cikl]['mennyiseg'] = $this->ki[$cikl]['mennyiseg'] * $this->ki[$cikl]['irany'];
            $this->ki[$cikl]['teljesmennyiseg'] = $this->ki[$cikl]['mennyiseg'];
            $cikl++;
        }

        $this->ujki = array();
    }

    private function bevetKeres($i) {
        $cikl = 0;
        $kilep = false;
        $bedb = count($this->be) - 1;
        while (($cikl <= $bedb) && (!$kilep)) {
            if (($this->be[$cikl]['termekid'] == $this->ki[$i]['termekid']) &&
                ($this->be[$cikl]['valtozatid'] == $this->ki[$i]['valtozatid']) &&
                ($this->be[$cikl]['raktarid'] == $this->ki[$i]['raktarid']) &&
                ($this->be[$cikl]['maradek'] != 0)
            ) {
                $kilep = true;
            }
            else {
                $cikl++;
            }
        }
        if ($kilep) {
            return $cikl;
        }
        return false;
    }

    private function bevetKeresDatum($i) {
        $cikl = 0;
        $bedb = count($this->be) - 1;
        $maxdate = 0;
        $maxkod = false;
        while (($cikl <= $bedb)) {
            if (($this->be[$cikl]['termekid'] == $this->ki[$i]['termekid']) &&
                ($this->be[$cikl]['valtozatid'] == $this->ki[$i]['valtozatid']) &&
                ($this->be[$cikl]['raktarid'] == $this->ki[$i]['raktarid'])
            ) {
                if ($this->be[$cikl]['teljesites'] < $maxdate) {
                    $maxdate = $this->be[$cikl]['teljesites'];
                    $maxkod = $cikl;
                }
            }
            $cikl++;
        }
        return $maxkod;
    }

    private function createUjkiadas($c, $bi) {
        $this->ujki[] = array(
            'id' => $this->ki[$c]['id'],
            'tetelid' => $this->ki[$c]['tetelid'],
            'irany' => $this->ki[$c]['irany'],
            'teljesites' => $this->ki[$c]['teljesites'],
            'raktarid' => $this->ki[$c]['raktarid'],
            'termekid' => $this->ki[$c]['termekid'],
            'valtozatid' => $this->ki[$c]['valtozatid'],
            'mennyiseg' => -1 * $this->be[$bi]['maradek'],
            'teljesmennyiseg' => $this->ki[$c]['teljesmennyiseg'],
            'beid' => $this->be[$bi]['id'],
            'betetelid' => $this->be[$bi]['tetelid'],
            'beirany' => $this->be[$bi]['irany'],
            'beteljesites' => $this->be[$bi]['teljesites']
        );
    }

    public function calculate() {
        $c = 0;
        $kidb = count($this->ki) - 1;
        while ($c <= $kidb) {
            $raktarid = $this->ki[$c]['raktarid'];
            $termekid = $this->ki[$c]['termekid'];
            $valtozatid = $this->ki[$c]['valtozatid'];
            while (($c <= $kidb) && ($raktarid == $this->ki[$c]['raktarid']) && ($termekid == $this->ki[$c]['termekid']) && ($valtozatid == $this->ki[$c]['valtozatid'])) {
                $megkiad = -1 * $this->ki[$c]['mennyiseg'];
                while ($megkiad != 0) {
                    if ($megkiad > 0) {
                        $bi = $this->bevetKeres($c);
                        if ($bi !== false) {
                            if ($this->be[$bi]['maradek'] - $megkiad >= 0) {
                                $this->ki[$c]['beid'] = $this->be[$bi]['id'];
                                $this->ki[$c]['betetelid'] = $this->be[$bi]['tetelid'];
                                $this->ki[$c]['beteljesites'] = $this->be[$bi]['teljesites'];
                                $this->ki[$c]['beirany'] = $this->be[$bi]['irany'];
                                $this->ki[$c]['mennyiseg'] = -1 * $megkiad;
                                $this->be[$bi]['maradek'] = $this->be[$bi]['maradek'] - $megkiad;
                                $megkiad = 0;
                            }
                            else {
                                $this->createUjkiadas($c, $bi);
                                $megkiad = $megkiad - $this->be[$bi]['maradek'];
                                $this->be[$bi]['maradek'] = 0;
                            }
                        }
                        else {
                            $this->ki[$c]['mennyiseg'] = -1 * $megkiad;
                            $megkiad = 0;
                        }
                    }
                    else {
                        $bi = $this->bevetKeresDatum($c);
                        if ($bi !== false) {
                            $this->ki[$c]['beid'] = $this->be[$bi]['id'];
                            $this->ki[$c]['betetelid'] = $this->be[$bi]['tetelid'];
                            $this->ki[$c]['beteljesites'] = $this->be[$bi]['teljesites'];
                            $this->ki[$c]['beirany'] = $this->be[$bi]['irany'];
                            $this->be[$bi]['maradek'] = $this->be[$bi]['maradek'] - $megkiad;
                            $megkiad = 0;
                        }
                        else {
                            $megkiad = 0;
                        }
                    }
                }
                $c++;
            }
        }
    }

    public function saveData() {
        $this->clearData();
        $data = $this->getData();
        $q = $this->_em->getConnection()->prepare('INSERT INTO fifo (raktar_id, termek_id, termekvaltozat_id, kibizonylatfej_id, kibizonylattetel_id, bebizonylatfej_id, bebizonylattetel_id, mennyiseg) ' .
            'VALUES (:rid, :tid, :tvid, :id, :tetelid, :beid, :betetelid, :mennyiseg)');
        foreach ($data as $d) {
            $params = array(
                'rid' => $d['raktarid'],
                'tid' => $d['termekid'],
                'tvid' => (array_key_exists('valtozatid', $d) ? $d['valtozatid'] : null),
                'id' => $d['id'],
                'tetelid' => $d['tetelid'],
                'beid' => (array_key_exists('beid', $d) ? $d['beid'] : null),
                'betetelid' => (array_key_exists('betetelid', $d) ? $d['betetelid'] : null),
                'mennyiseg' => $d['mennyiseg']
            );
            $q->executeStatement($params);
        }

        $q = $this->_em->getConnection()->prepare('INSERT INTO keszlet (raktar_id, termek_id, termekvaltozat_id, bebizonylatfej_id, bebizonylattetel_id, mennyiseg) ' .
            'VALUES (:rid, :tid, :tvid, :beid, :betetelid, :mennyiseg)');
        foreach ($this->be as $d) {
            if ($d['maradek'] != 0) {
                $params = array(
                    'rid' => $d['raktarid'],
                    'tid' => $d['termekid'],
                    'tvid' => (array_key_exists('valtozatid', $d) ? $d['valtozatid'] : null),
                    'beid' => (array_key_exists('id', $d) ? $d['id'] : null),
                    'betetelid' => (array_key_exists('tetelid', $d) ? $d['tetelid'] : null),
                    'mennyiseg' => $d['maradek']
                );
                $q->executeStatement($params);
            }
        }
    }

    public function getDataHeader() {
        return array(
            'KI biz.szám',
            'KI tétel id',
            'KI irány',
            'KI teljesítés',
            'KI raktár',
            'KI termék id',
            'KI változat id',
            'KI mennyiség',
            'KI biz.mennyiség',
            'BE biz.szám',
            'BE tétel id',
            'BE teljesítés',
            'BE irány'
        );
    }

    public function getData() {
        foreach ($this->ujki as $u) {
            $this->ki[] = $u;
        }
        return $this->ki;
    }

}
