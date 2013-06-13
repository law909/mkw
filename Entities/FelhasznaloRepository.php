<?php
namespace Entities;

class FelhasznaloRepository extends \mkwhelpers\Repository {

	public function __construct($em, \Doctrine\ORM\Mapping\ClassMetadata $class) {
		parent::__construct($em,$class);
		$this->setEntityname('Entities\Felhasznalo');
		$this->setOrders(array(
			'1'=>array('caption'=>'név szerint','order'=>array('_xx.nev'=>'ASC')),
			'2'=>array('caption'=>'felhasználónév szerint','order'=>array('_xx.felhasznalonev'=>'ASC'))
		));
	}

	public function getWithJoins($filter,$order,$offset=0,$elemcount=0) {
		$a=$this->alias;
		$q=$this->_em->createQuery('SELECT '.$a.',uk'
			.' FROM '.$this->entityname.' '.$a
			.' LEFT JOIN '.$a.'.uzletkoto uk'
			.$this->getFilterString($filter)
			.$this->getOrderString($order));
		if ($offset>0) {
			$q->setFirstResult($offset);
		}
		if ($elemcount>0) {
			$q->setMaxResults($elemcount);
		}
		$q->setParameters($this->getQueryParameters($filter));
		return $q->getResult();
	}

	public function getCount($filter) {
		$a=$this->alias;
		$q=$this->_em->createQuery('SELECT COUNT('.$a.') FROM '.$this->entityname.' '.$a
			.' LEFT JOIN '.$a.'.uzletkoto uk'
			.$this->getFilterString($filter));
		$q->setParameters($this->getQueryParameters($filter));
		return $q->getSingleScalarResult();
	}
}