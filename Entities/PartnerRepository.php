<?php

namespace Entities;

use mkw\store;

class PartnerRepository extends \mkwhelpers\Repository {

	public function __construct($em, \Doctrine\ORM\Mapping\ClassMetadata $class) {
		parent::__construct($em, $class);
		$this->setEntityname('Entities\Partner');
		$this->setOrders(array(
			'1' => array('caption' => 'név szerint', 'order' => array('nev' => 'ASC')),
			'2' => array('caption' => 'cím szerint', 'order' => array('irszam' => 'ASC', 'varos' => 'ASC', 'utca' => 'ASC'))
		));
		$this->setBatches(array(
			'1' => 'címke hozzáadás',
			'2' => 'címke törlés'
		));
	}

	public function getWithJoins($filter, $order, $offset = 0, $elemcount = 0) {
		$a = $this->alias;
		$q = $this->_em->createQuery('SELECT ' . $a . ',fm,uk'
				. ' FROM ' . $this->entityname . ' ' . $a
				. ' LEFT JOIN ' . $a . '.fizmod fm'
				. ' LEFT JOIN ' . $a . '.uzletkoto uk '
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
				. $this->getFilterString($filter));
		$q->setParameters($this->getQueryParameters($filter));
		return $q->getSingleScalarResult();
	}

	public function countByEmail($email) {
		$filter = array();
		$filter['fields'][] = 'email';
		$filter['clauses'][] = '=';
		$filter['values'][] = $email;
		$filter['fields'][] = 'vendeg';
		$filter['clauses'][] = '=';
		$filter['values'][] = false;
		return $this->getCount($filter);
	}

	public function findByUserPass($user, $pass) {
		$filter = array();
		$filter['fields'][] = 'email';
		$filter['clauses'][] = '=';
		$filter['values'][] = $user;
		$filter['fields'][] = 'jelszo';
		$filter['clauses'][] = '=';
		$filter['values'][] = sha1(strtoupper(md5($pass)) . store::getSalt());
		return $this->getAll($filter, array());
	}

	public function findVendegByEmail($email) {
		$filter = array();
		$filter['fields'][] = 'email';
		$filter['clauses'][] = '=';
		$filter['values'][] = $email;
		$filter['fields'][] = 'vendeg';
		$filter['clauses'][] = '=';
		$filter['values'][] = true;
		return $this->getAll($filter, array());
	}

	public function findByIdSessionid($id, $sessionid) {
		$filter = array();
		$filter['fields'][] = 'id';
		$filter['clauses'][] = '=';
		$filter['values'][] = $id;
		$filter['fields'][] = 'sessionid';
		$filter['clauses'][] = '=';
		$filter['values'][] = $sessionid;
		return $this->getAll($filter, array());
	}

    public function findNemVendegByEmail($email) {
		$filter = array();
		$filter['fields'][] = 'email';
		$filter['clauses'][] = '=';
		$filter['values'][] = $email;
		$filter['fields'][] = 'vendeg';
		$filter['clauses'][] = '=';
		$filter['values'][] = false;
		return $this->getAll($filter, array());
    }

}