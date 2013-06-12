<?php
namespace Entities;

use matt, \Doctrine\ORM;

class PartnercimkekatRepository extends matt\Repository {

	public function __construct($em, \Doctrine\ORM\Mapping\ClassMetadata $class) {
		parent::__construct($em,$class);
		$this->setEntityname('Entities\Partnercimkekat');
	}

	public function getWithJoins($filter,$order,$offset=0,$elemcount=0) {
		$a=$this->alias;
		$q=$this->_em->createQuery('SELECT '.$this->alias.',c FROM '.$this->entityname.' '.$this->alias
			.' LEFT JOIN '.$this->alias.'.cimkek c '
			.$this->getFilterString($filter)
			.$this->getOrderString($order));
		$q->setParameters($this->getQueryParameters($filter));
		if ($offset>0) {
			$q->setFirstResult($offset);
		}
		if ($elemcount>0) {
			$q->setMaxResults($elemcount);
		}
		return $q->getResult();
	}

	public function getCount($filter) {
		$a=$this->alias;
		$q=$this->_em->createQuery('SELECT COUNT('.$this->alias.'.id) FROM '.$this->entityname.' '.$this->alias
			.' LEFT JOIN '.$this->alias.'.cimkek c '
			.$this->getFilterString($filter));
		$q->setParameters($this->getQueryParameters($filter));
		return $q->getSingleScalarResult();
	}

	public function getAllHasPartner($menu) {
		$a=$this->alias;
		$filter='c.menu'.$menu.'lathato=1';
		$order=array('_xx.nev'=>'asc','c.nev'=>'asc');
		$q=$this->_em->createQuery('SELECT '.$a.',c FROM '.$this->entityname.' '.$a
			.' LEFT JOIN '.$a.'.cimkek c '
			.' INNER JOIN c.partnerek p '
			.$this->getFilterString($filter)
			.$this->getOrderString($order));
		$q->setParameters($this->getQueryParameters($filter));
		if ($offset>0) {
			$q->setFirstResult($offset);
		}
		if ($elemcount>0) {
			$q->setMaxResults($elemcount);
		}
		return $q->getResult();
	}
}