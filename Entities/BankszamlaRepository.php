<?php
namespace Entities;

use matt, \Doctrine\ORM;

class BankszamlaRepository extends matt\Repository {

	public function __construct($em, \Doctrine\ORM\Mapping\ClassMetadata $class) {
		parent::__construct($em,$class);
		$this->setEntityname('Entities\Bankszamla');
	}

	public function getAll($filter,$order) {
		return $this->_em->createQuery('SELECT '.$this->alias.',v FROM '.$this->entityname.' '.$this->alias
			.' LEFT JOIN '.$this->alias.'.valutanem v'
			.$this->getFilterString($filter)
			.$this->getOrderString($order))
			->setParameters($this->getQueryParameters($filter))
			->getResult();
	}
}