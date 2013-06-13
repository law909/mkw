<?php
namespace Entities;

class SzamlatukorRepository extends \mkwhelpers\Repository {

	public function __construct($em, \Doctrine\ORM\Mapping\ClassMetadata $class) {
		parent::__construct($em,$class);
		$this->setEntityname('Entities\Szamlatukor');
		$this->setOrders(array(
			'1'=>array('caption'=>'fkvi.szám szerint','order'=>array('id'=>'ASC')),
			'2'=>array('caption'=>'név szerint','order'=>array('nev'=>'ASC'))
		));
		$this->setBatches(array(
			'1'=>'címke hozzáadás',
			'2'=>'címke törlés'
		));
	}

	public function getForGrid($filter) {
		$a=$this->alias;
		$q=$this->_em->createQuery('SELECT '.$a.' FROM '.$this->entityname.' '.$a
			.' LEFT JOIN '.$a.'.parent p'
			.' WHERE '.$filter);
		return $q->getResult();
	}

	public function getLeafs() {
		$a=$this->alias;
		$q=$this->_em->createQuery('SELECT '.$a.' FROM '.$this->entityname.' '.$a
			.' LEFT JOIN '.$a.'.children c'
			.' WHERE c.id IS NULL');
		return $q->getResult();
	}

	public function getWithJoins($filter,$order,$offset=0,$elemcount=0) {
		$a=$this->alias;
		$q=$this->_em->createQuery('SELECT '.$a.',af'
			.' FROM '.$this->entityname.' '.$a
			.' LEFT JOIN '.$a.'.afa af'
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
			.' LEFT JOIN '.$a.'.afa af'
			.$this->getFilterString($filter));
		$q->setParameters($this->getQueryParameters($filter));
		return $q->getSingleScalarResult();
	}
}