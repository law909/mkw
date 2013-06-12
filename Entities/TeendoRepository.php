<?php
namespace Entities;
use Entities\Teendo;
use matt, \Doctrine\ORM;

class TeendoRepository extends matt\Repository {

	public function __construct($em, \Doctrine\ORM\Mapping\ClassMetadata $class) {
		parent::__construct($em,$class);
		$this->setEntityname('Entities\Teendo');
		$this->setOrders(array(
			'1'=>array('caption'=>'teendő szerint','order'=>array('_xx.bejegyzes'=>'ASC')),
			'2'=>array('caption'=>'partner szerint','order'=>array('a.nev'=>'ASC')),
			'3'=>array('caption'=>'esedékesség','order'=>array('_xx.esedekes'=>'ASC')),
			'4'=>array('caption'=>'állapot','order'=>array('_xx.elvegezve'=>'ASC'))
		));
	}

	public function getWithJoins($filter,$order,$offset=0,$elemcount=0) {
		$a=$this->alias;
		$q=$this->_em->createQuery('SELECT '.$a
			.' FROM '.$this->entityname.' '.$a
			.' LEFT JOIN '.$this->alias.'.partner a'
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
			.' LEFT JOIN '.$this->alias.'.partner a'
			.$this->getFilterString($filter));
		$q->setParameters($this->getQueryParameters($filter));
		return $q->getSingleScalarResult();
	}
}