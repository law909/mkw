<?php

namespace Entities;

use Doctrine\ORM\Query\ResultSetMapping;

class MPTNGYSzakmaianyagRepository extends \mkwhelpers\Repository {

	public function __construct($em, \Doctrine\ORM\Mapping\ClassMetadata $class) {
		parent::__construct($em, $class);
		$this->setEntityname(MPTNGYSzakmaianyag::class);
		$this->setOrders(array(
			'1' => array('caption' => 'név szerint növekvő', 'order' => array('nev' => 'ASC'))
		));

        $btch = array();
		$this->setBatches($btch);
	}

	public function getWithJoins($filter, $order, $offset = 0, $elemcount = 0) {
		$a = $this->alias;
		$q = $this->_em->createQuery('SELECT ' . $a
				. ' FROM ' . $this->entityname . ' ' . $a
				. $this->getFilterString($filter)
				. $this->getOrderString($order));
		$q->setParameters($this->getQueryParameters($filter));
		if ($offset > 0) {
			$q->setFirstResult($offset);
		}
		if ($elemcount > 0) {
			$q->setMaxResults($elemcount);
		}
		return $q->getResult();
	}

	public function getCount($filter) {
		$a = $this->alias;
		$q = $this->_em->createQuery('SELECT COUNT(' . $a . ') FROM ' . $this->entityname . ' ' . $a
				. ' LEFT JOIN ' . $a . '.fizmod fm'
				. ' LEFT JOIN ' . $a . '.uzletkoto uk '
				. ' LEFT JOIN ' . $a . '.valutanem v '
				. ' LEFT JOIN ' . $a . '.szallitasimod szm '
				. $this->getFilterString($filter));
		$q->setParameters($this->getQueryParameters($filter));
		return $q->getSingleScalarResult();
	}

	public function countByEmail($email) {
		$filter = new \mkwhelpers\FilterDescriptor();
        $filter
            ->addFilter('email', '=', $email)
            ->addFilter('vendeg', '=', false);
		return $this->getCount($filter);
	}

}
