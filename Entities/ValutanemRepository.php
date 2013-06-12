<?php
namespace Entities;

use matt, \Doctrine\ORM;

class ValutanemRepository extends matt\Repository {

	public function __construct($em, \Doctrine\ORM\Mapping\ClassMetadata $class) {
		parent::__construct($em,$class);
		$this->entityname='Entities\Valutanem';
	}

	public function getAll($filter,$order) {
		return $this->_em->createQuery('SELECT '.$this->alias.',b FROM '.$this->entityname.' '.$this->alias
			.' LEFT JOIN '.$this->alias.'.bankszamla b'
			.$this->getFilterString($filter)
			.$this->getOrderString($order))
			->setParameters($this->getQueryParameters($filter))
			->getResult();
	}
}