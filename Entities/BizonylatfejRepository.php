<?php

namespace Entities;

class BizonylatfejRepository extends \mkwhelpers\Repository {

	public function __construct($em, \Doctrine\ORM\Mapping\ClassMetadata $class) {
		parent::__construct($em, $class);
		$this->setEntityname('Entities\Bizonylatfej');
		$this->setOrders(array(
			'1' => array('caption' => 'biz.szám szerint csökkenő', 'order' => array('_xx.id' => 'DESC')),
			'2' => array('caption' => 'biz.szám szerint növekvő', 'order' => array('_xx.id' => 'ASC')),
            '3' => array('caption' => 'kelt szerint csökkenő', 'order' => array('_xx.kelt' => 'DESC','_xx.id' => 'DESC')),
            '4' => array('caption' => 'kelt szerint növekvő', 'order' => array('_xx.kelt' => 'DESC','_xx.id' => 'DESC')),
			'5' => array('caption' => 'er.biz.szám szerint csökkenő', 'order' => array('_xx.erbizonylatszam' => 'DESC')),
			'6' => array('caption' => 'er.biz.szám szerint növekvő', 'order' => array('_xx.erbizonylatszam' => 'ASC'))
        ));
	}

    public function getReportfileSelectList($sel, $biztip) {
        $elo = 'biz_' . $biztip;
        $files = dir(\mkw\Store::getAdminDefaultTemplatePath());
        $list = array();
        while (false !== ($entry = $files->read())) {
            if (($entry != '.') && ($entry !='..')) {
                $path_parts = pathinfo($entry);
                $xx = substr($path_parts['basename'], 0, strlen($elo));
                if ($path_parts['extension']
                    && ($path_parts['extension'] == 'tpl')
                    && ($xx == $elo)) {
                    $list[$entry] = $entry;
                }
            }
        }
        $files = dir(\mkw\Store::getAdminTemplatePath());
        while (false !== ($entry = $files->read())) {
            if (($entry != '.') && ($entry !='..')) {
                $path_parts = pathinfo($entry);
                $xx = substr($path_parts['basename'], 0, strlen($elo));
                if ($path_parts['extension']
                    && ($path_parts['extension'] == 'tpl')
                    && ($xx == $elo)) {
                    $list[$entry] = $entry;
                }
            }
        }
        $ret = array();
        foreach($list as $l) {
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

	public function getWithJoins($filter, $order, $offset = 0, $elemcount = 0) {
		$a = $this->alias;
		$q = $this->_em->createQuery('SELECT ' . $a
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
		return $q->getResult();
	}

	public function getCount($filter) {
		$a = $this->alias;
		$q = $this->_em->createQuery('SELECT COUNT(' . $a . ') FROM ' . $this->entityname . ' ' . $a
				. $this->getFilterString($filter));
		$q->setParameters($this->getQueryParameters($filter));
		return $q->getSingleScalarResult();
	}

    public function getTetelsor($bizszam, $termekid, $valtozatid = null) {
        $filter = array();
        if ($bizszam) {
            $filter['fields'][] = 'bizonylatfej';
            $filter['clauses'][] = '=';
            $filter['values'][] = $bizszam;
        }
        if ($termekid) {
            $filter['fields'][] = 'termek';
            $filter['clauses'][] = '=';
            $filter['values'][] = $termekid;
        }
        if ($valtozatid) {
            $filter['fields'][] = 'termekvaltozat';
            $filter['clauses'][] = '=';
            $filter['values'][] = $valtozatid;
        }
        if (count($filter) == 0) {
            $filter['fields'][] = 'id';
            $filter['clauses'][] = '<';
            $filter['values'][] = '0';
        }
        $a = $this->alias;
        $q = $this->_em->createQuery('SELECT ' . $a
                . ' FROM Entities\Bizonylattetel ' . $a
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

    public function createSzallitasiKtg($bizfej, $szallmod = null, $bruttoegysar = null) {
        $szamol = true;
        if ($szallmod) {
            $szm = $this->getRepo('Entities\Szallitasimod')->find($szallmod);
            $szamol = $szm->getVanszallitasiktg();
        }
        $termekid = \mkw\Store::getParameter(\mkw\consts::SzallitasiKtgTermek);
        $termek = $this->getRepo('Entities\Termek')->find($termekid);
        if ($szamol) {
            $ertek = 0;
            $cnt = 0;
            foreach($bizfej->getBizonylattetelek() as $btetel) {
                if ($btetel->getTermekId() != $termekid) {
                    $cnt++;
                    $ertek = $ertek + $btetel->getBrutto();
                }
            }
            if ($cnt != 0) {
                if (!$bruttoegysar) {
                    $ktg = \mkw\Store::calcSzallitasiKoltseg($ertek);
                }
                else {
                    $ktg = $bruttoegysar;
                }
                $k = $this->getTetelsor($bizfej->getId(), $termekid);
                if ($k) {
                    $k->setMennyiseg(1);
                    $k->setBruttoegysar($ktg);
					$k->setBruttoegysarhuf($ktg);
                    $k->calc();
                    $this->_em->persist($k);
                }
                else {
					$tetel = new \Entities\Bizonylattetel();
					$bizfej->addBizonylattetel($tetel);
					$tetel->setPersistentData();
					$tetel->setArvaltoztat(0);
					if ($termek) {
						$tetel->setTermek($termek);
					}
					$tetel->setMozgat();
					$tetel->setMennyiseg(1);
					$tetel->setBruttoegysar($ktg);
					$tetel->setBruttoegysarhuf($ktg);
                    $tetel->calc();
					$this->_em->persist($tetel);
                }
                $this->_em->flush();
            }
            else {
                $this->remove($bizfej->getId(), $termek);
            }
        }
        else {
            $this->remove($bizfej->getId(), $termek);
        }
    }

    public function getAFAOsszesito($o) {
        $ret = array();
        foreach($o->getBizonylattetelek() as $tetel) {
            $a = $tetel->getAfa();
            if (!array_key_exists($tetel->getAfaId(), $ret)) {
                $ret[$tetel->getAfaId()] = array(
                    'netto' => 0,
                    'afa' => 0,
                    'brutto' => 0,
                    'caption' => $tetel->getAfanev(),
                    'rlbkod' => ($a ? $a->getRLBkod() : 0)
                );
            }
            $ret[$tetel->getAfaId()]['netto'] += round($tetel->getNetto());
            $ret[$tetel->getAfaId()]['afa'] += round($tetel->getAfaertek());
            $ret[$tetel->getAfaId()]['brutto'] += round($tetel->getBrutto());
        }
        return $ret;
    }

    public function findForPrint($id) {
        if ($id) {
            $b = $this->find($id);
            if ($b) {
                $locale = $b->getBizonylatnyelv();
            }
        }
        $filter = array();
        $filter['fields'][] = 'id';
        $filter['clauses'][] = '=';
        $filter['values'][] = $id;
		$a = $this->alias;
		$q = $this->_em->createQuery('SELECT ' . $a . ',bt'
			. ' FROM ' . $this->entityname . ' ' . $a
            . ' LEFT JOIN ' . $a . '.bizonylattetelek bt'
			. $this->getFilterString($filter));
		$q->setParameters($this->getQueryParameters($filter));
        \mkw\Store::setTranslationHint($q, $locale);
		$res = $q->getResult();
        if (count($res)) {
            return $res[0];
        }
        return false;
    }
}