<?php
namespace Entities;

class BankszamlaRepository extends \mkwhelpers\Repository {

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