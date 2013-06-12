<?php
namespace Entities;

use matt, \Doctrine\ORM;

class ArfolyamRepository extends matt\Repository {

	public function __construct($em, \Doctrine\ORM\Mapping\ClassMetadata $class) {
		parent::__construct($em,$class);
		$this->setEntityname('Entities\Arfolyam');
	}

	public function getAll($filter,$order) {
		return $this->_em->createQuery('SELECT '.$this->alias.',v FROM '.$this->entityname.' '.$this->alias
			.' LEFT JOIN '.$this->alias.'.valutanem v'
			.$this->getFilterString($filter)
			.$this->getOrderString($order))
			->setParameters($this->getQueryParameters($filter))
			->getResult();
	}

	public function getActualArfolyam($valuta,$datum) {
		$filter=array();
		$filter['fields'][]='valutanem';
		$filter['clauses'][]='';
		$filter['values'][]=$valuta;
		$filter['fields'][]='datum';
		$filter['clauses'][]='<=';
		$filter['values'][]=$datum;
		$arf=$this->_em->createQuery('SELECT '.$this->alias.' FROM '.$this->entityname.' '.$this->alias
			.$this->getFilterString($filter)
			.$this->getOrderString(array('datum'=>'DESC')))
			->setMaxResults(1)
			->setParameters($this->getQueryParameters($filter))
			->getResult();
		if ($arf) {
			return $arf[0];
		}
		return $arf;
	}
}