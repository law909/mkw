<?php
namespace Entities;

use matt, \Doctrine\ORM;

class KontaktcimketorzsRepository extends matt\Repository {

	public function __construct($em, \Doctrine\ORM\Mapping\ClassMetadata $class) {
		parent::__construct($em,$class);
		$this->setEntityname('Entities\Kontaktcimketorzs');
		$this->setOrders(array(
			'1'=>array('caption'=>'név szerint','order'=>array('_xx.nev'=>'ASC')),
			'2'=>array('caption'=>'csoport szerint','order'=>array('ck.nev'=>'ASC','_xx.nev'=>'ASC'))
		));
		$this->setBatches(array(
			'1'=>'áthelyezés másik címkecsoportba'
		));
	}

	public function getWithJoins($filter,$order,$offset=0,$elemcount=0) {
		$a=$this->alias;
		$q=$this->_em->createQuery('SELECT '.$a.',ck'
			.' FROM '.$this->entityname.' '.$a
			.' JOIN '.$a.'.kategoria ck '
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
		$q=$this->_em->createQuery('SELECT COUNT('.$a.') FROM '.$this->entityname.' '.$a
			.' JOIN '.$a.'.kategoria ck '
			.$this->getFilterString($filter));
		$q->setParameters($this->getQueryParameters($filter));
		return $q->getSingleScalarResult();
	}
}