<?php
namespace Entities;

class FifoRepository extends \mkwhelpers\Repository {

    private $be;
    private $ki;
    private $ujki = array();

	public function __construct($em, \Doctrine\ORM\Mapping\ClassMetadata $class) {
		parent::__construct($em,$class);
		$this->setEntityname('Entities\Fifo');
		$this->setOrders(array(
			'1'=>array('caption'=>'név szerint','order'=>array('_xx.nev'=>'ASC'))
		));
	}

    public function loadData() {
        $rsm = new ResultSetMapping();
        $rsm->addScalarResult('id', 'id');
        $rsm->addScalarResult('raktar_id', 'raktarid');
        $rsm->addScalarResult('irany', 'irany');
        $rsm->addScalarResult('teljesites', 'teljesites');
        $rsm->addScalarResult('btid', 'tetelid');
        $rsm->addScalarResult('termek_id', 'termekid');
        $rsm->addScalarResult('termekvaltozat_id', 'valtozatid');
        $rsm->addScalarResult('mennyiseg', 'mennyiseg');
        $q = $this->_em->createNativeQuery(
            'SELECT bf.id,bf.raktar_id,bf.irany,bf.teljesites,bt.id AS btid,termek_id,termekvaltozat_id,mennyiseg ' .
            'FROM bizonylatfej bf, bizonylattetel bt ' .
            'WHERE (bf.id=bt.bizonylatfej_id) AND (bt.mozgat=1) AND ' .
                '(((bf.irany>0) AND (bt.mennyiseg>0)) OR ((bf.irany<0) AND (bt.mennyiseg<0))) AND ' .
                '((bf.storno=0) OR (bf.storno IS NULL)) AND ((bf.stornozott=0) OR (bf.stornozott IS NULL)) ' .
            'ORDER BY raktar_id,termek_id,termekvaltozat_id,teljesites'
                , $rsm);
        $this->be = $q->getScalarResult();
        foreach($this->be as $b) {
            $b['mennyiseg'] = $b['mennyiseg'] * $b['irany'];
            $b['maradek'] = $b['mennyiseg'];
        }

        $q = $this->_em->createNativeQuery(
            'SELECT bf.id,bf.raktar_id,bf.irany,bf.teljesites,bt.id AS btid,termek_id,termekvaltozat_id,mennyiseg ' .
            'FROM bizonylatfej bf, bizonylattetel bt ' .
            'WHERE (bf.id=bt.bizonylatfej_id) AND (bt.mozgat=1) AND ' .
                '(((bf.irany<0) AND (bt.mennyiseg>0)) OR ((bf.irany>0) AND (bt.mennyiseg<0))) AND ' .
                '((bf.storno=0) OR (bf.storno IS NULL)) AND ((bf.stornozott=0) OR (bf.stornozott IS NULL)) ' .
            'ORDER BY raktar_id,termek_id,termekvaltozat_id,teljesites'
                , $rsm);
        $this->ki = $q->getScalarResult();
        foreach($this->ki as $k) {
            $k['mennyiseg'] = $k['mennyiseg'] * $k['irany'];
            $k['teljesmennyiseg'] = $k['mennyiseg'];
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
                ($this->be[$cikl]['maradek'] != 0)) {
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
                ($this->be[$cikl]['raktarid'] == $this->ki[$i]['raktarid'])) {
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
        while ($c < $kidb) {
            $raktarid = $this->ki[$c]['raktarid'];
            $termekid = $this->ki[$c]['termekid'];
            $valtozatid = $this->ki[$c]['valtozatid'];
            while (($c < $kidb)&&($raktarid == $this->ki[$c]['raktarid'])&&($termekid == $this->ki[$c]['termekid'])&&($valtozatid == $this->ki[$c]['valtozatid'])) {
                $megkiad = -1 * $this->ki[$c]['mennyiseg'];
                while ($megkiad != 0) {
                    if ($megkiad > 0) {
                        $bi = $this->bevetKeres($c);
                        if ($bi) {
                            if ($this->be[$bi]['maradek'] - $megkiad >= 0) {
                                $this->ki[$c]['beid'] = $this->be[$bi]['id'];
                                $this->ki[$c]['bebtid'] = $this->be[$bi]['btid'];
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
                        if ($bi) {
                            $this->ki[$c]['beid'] = $this->be[$bi]['id'];
                            $this->ki[$c]['bebtid'] = $this->be[$bi]['btid'];
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

    }

    public function getData() {
        foreach($this->ujki as $u) {
            $this->ki[] = $u;
        }
        return $this->ki;
    }

}
