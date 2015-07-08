<?php

namespace Entities;

use mkw\store;

class PartnerRepository extends \mkwhelpers\Repository {

	public function __construct($em, \Doctrine\ORM\Mapping\ClassMetadata $class) {
		parent::__construct($em, $class);
		$this->setEntityname('Entities\Partner');
		$this->setOrders(array(
			'1' => array('caption' => 'név szerint növekvő', 'order' => array('nev' => 'ASC')),
			'2' => array('caption' => 'cím szerint növekvő', 'order' => array('irszam' => 'ASC', 'varos' => 'ASC', 'utca' => 'ASC'))
		));
		$this->setBatches(array(
			'1' => 'címke hozzáadás',
			'2' => 'címke törlés'
		));
	}

    public function getSzamlatipusList($sel) {
        return array(
            array(
                'id' => 0,
                'caption' => 'magyar',
                'selected' => ($sel == 0)
            ),
            array(
                'id' => 1,
                'caption' => 'EU-n belüli',
                'selected' => ($sel == 1)
            ),
            array(
                'id' => 2,
                'caption' => 'EU-n kívüli',
                'selected' => ($sel == 2)
            )
        );
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

    public function getAllForSelectList($filter, $order, $offset = 0, $elemcount = 0) {
        $a = $this->alias;
        $q = $this->_em->createQuery('SELECT ' . $a . '.id,' . $a . '.nev, ' . $a . '.irszam, ' . $a . '.varos, ' . $a . '.utca '
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
        return $q->getScalarResult();
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

    public function checkloggedin() {
        if (isset(\mkw\Store::getMainSession()->pk)) {
            $users = $this->findByIdSessionid(\mkw\Store::getMainSession()->pk, \Zend_Session::getId());
            return count($users) == 1;
        }
        return false;
    }

    public function getLoggedInUser() {
        if ($this->checkloggedin()) {
            return $this->find(\mkw\Store::getMainSession()->pk);
        }
        return null;
    }

}