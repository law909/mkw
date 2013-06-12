<?php
namespace Entities;

use matt, \Doctrine\ORM;

class UzletkotoRepository extends matt\Repository {

	public function __construct($em, \Doctrine\ORM\Mapping\ClassMetadata $class) {
		parent::__construct($em,$class);
		$this->setEntityname('Entities\Uzletkoto');
		$this->setOrders(array(
			'1'=>array('caption'=>'név szerint','order'=>array('nev'=>'ASC')),
			'2'=>array('caption'=>'cím szerint','order'=>array('irszam'=>'ASC','varos'=>'ASC','utca'=>'ASC'))
		));
		$this->setBatches(array(
			'1'=>'címke hozzáadás',
			'2'=>'címke törlés'
		));
	}

	public function getWithJoins($filter,$order,$offset=0,$elemcount=0) {
		$a=$this->alias;
		$q=$this->_em->createQuery('SELECT '.$a
			.' FROM '.$this->entityname.' '.$a
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
			.$this->getFilterString($filter));
		$q->setParameters($this->getQueryParameters($filter));
		return $q->getSingleScalarResult();
	}
}