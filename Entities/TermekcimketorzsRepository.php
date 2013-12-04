<?php

namespace Entities;

use Doctrine\ORM\Query\ResultSetMapping;

class TermekcimketorzsRepository extends \mkwhelpers\Repository {

	public function __construct($em, \Doctrine\ORM\Mapping\ClassMetadata $class) {
		parent::__construct($em, $class);
		$this->setEntityname('Entities\Termekcimketorzs');
		$this->setOrders(array(
			'1' => array('caption' => 'név szerint', 'order' => array('_xx.nev' => 'ASC')),
			'2' => array('caption' => 'csoport szerint', 'order' => array('ck.nev' => 'ASC', '_xx.nev' => 'ASC'))
		));
		$this->setBatches(array(
			'1' => 'áthelyezés másik címkecsoportba'
		));
	}

	public function getWithJoins($filter, $order, $offset = 0, $elemcount = 0) {
		$a = $this->alias;
		$q = $this->_em->createQuery('SELECT ' . $a . ',ck'
				. ' FROM ' . $this->entityname . ' ' . $a
				. ' JOIN ' . $a . '.kategoria ck '
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
				. ' JOIN ' . $a . '.kategoria ck '
				. $this->getFilterString($filter));
		$q->setParameters($this->getQueryParameters($filter));
		return $q->getSingleScalarResult();
	}

	public function getAllNative() {
		$rsm = new ResultSetMapping();
		$rsm->addScalarResult('termek_id', 'termek_id');
		$rsm->addScalarResult('cimketorzs_id', 'cimketorzs_id');
		$q = $this->_em->createNativeQuery('SELECT * FROM termek_cimkek', $rsm);
		$res = $q->getScalarResult();
		$ret = $res;
		return $ret;
	}

	public function getTermekIdsWithCimke($cimkekodok) {
		$a = $this->alias;
		$filter = array();
		$filter['fields'][] = 'id';
		$filter['values'][] = $cimkekodok;
		$q = $this->_em->createQuery('SELECT t.id FROM ' . $this->entityname . ' ' . $a
				. ' JOIN ' . $a . '.termekek t '
				. $this->getFilterString($filter));
		$q->setParameters($this->getQueryParameters($filter));
		return $q->getScalarResult();
	}

	public function getTermekIdsWithCimkeAnd($cimkekodok) {
		$rsm = new ResultSetMapping();
		$rsm->addScalarResult('termek_id', 'termek_id');
		$r = array();
		foreach ($cimkekodok as $cimkefej) {
			$cimkestr = implode(',', $cimkefej);
			$q = $this->_em->createNativeQuery('SELECT tc.termek_id FROM termek_cimkek tc '
					. 'LEFT OUTER JOIN termek t ON (t.id=tc.termek_id) '
					. 'WHERE (tc.cimketorzs_id IN (' . $cimkestr . ')) AND (t.inaktiv=0) AND (t.lathato=1)', $rsm);
			$res = $q->getScalarResult();
			foreach ($res as $sor) {
				if (array_key_exists($sor['termek_id'], $r) && $r[$sor['termek_id']] > 0) {
					$r[$sor['termek_id']]++;
				}
				else {
					$r[$sor['termek_id']] = 1;
				}
			}
		}
		$kelldb = count($cimkekodok);
		$ret = array();
		foreach ($r as $tid => $db) {
			if ($db == $kelldb) {
				$ret[] = array('termek_id' => $tid);
			}
		}
		unset($r);
		unset($res);
		return $ret;
	}

	public function getByNevAndKategoria($nev, $kat) {
		$a = $this->alias;
		$q = $this->_em->createQuery('SELECT ' . $a . ' FROM ' . $this->entityname . ' ' . $a .
				' WHERE ' . $a . '.nev=?1 AND ' . $a . '.kategoria=?2');
		$q->setParameter(1, $nev);
		$q->setParameter(2, $kat);
		$t = $q->getResult();
		if ($t) {
			return $t[0];
		}
		return false;
	}

    public function getKiemelt() {
        $filter = array();
        $filter['fields'][] = 'kiemelt';
        $filter['clauses'][] = '=';
        $filter['values'][] = true;
        return $this->getWithJoins($filter, array());
    }

}