<?php

namespace Entities;

class BankszamlaRepository extends \mkwhelpers\Repository {

	public function __construct($em, \Doctrine\ORM\Mapping\ClassMetadata $class) {
		parent::__construct($em, $class);
		$this->setEntityname('Entities\Bankszamla');
	}

	public function getAll($filter, $order) {
		return $this->_em->createQuery('SELECT ' . $this->alias . ',v FROM ' . $this->entityname . ' ' . $this->alias
								. ' LEFT JOIN ' . $this->alias . '.valutanem v'
								. $this->getFilterString($filter)
								. $this->getOrderString($order))
						->setParameters($this->getQueryParameters($filter))
						->getResult();
	}

	public function getByValutanem($valutanem) {
		$filter['fields'][] = 'valutanem';
		$filter['clauses'][] = '=';
		$filter['values'][] = $valutanem;
		$r = $this->getAll($filter, array());
		if (count($r) > 0) {
			return $r[0];
		}
		return null;
	}

}