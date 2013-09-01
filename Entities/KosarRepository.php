<?php
namespace Entities;

class KosarRepository extends \mkwhelpers\Repository {

	public function __construct($em, \Doctrine\ORM\Mapping\ClassMetadata $class) {
		parent::__construct($em,$class);
		$this->setEntityname('Entities\Kosar');
		$this->setOrders(array(
			'1'=>array('caption'=>'session szerint','order'=>array('_xx.sessionid'=>'ASC')),
			'2'=>array('caption'=>'létrehozás dátuma szerint','order'=>array('_xx.created'=>'ASC'))
		));
	}

	public function getWithJoins($filter,$order,$offset=0,$elemcount=0) {
		$a=$this->alias;
		$q=$this->_em->createQuery('SELECT '.$a.',p,t '
			.' FROM '.$this->entityname.' '.$a
			.' LEFT OUTER JOIN '.$a.'.partner p'
			.' LEFT JOIN '.$a.'.termek t'
			.$this->getFilterString($filter)
			.$this->getOrderString($order));
		$q->setParameters($this->getQueryParameters($filter));
		if ($offset>0) {
			$q->setFirstResult($offset);
		}
		if ($elemcount>0) {
			$q->setMaxResults($elemcount);
		}
		return $q->getResult();
	}

	public function getCount($filter) {
		$a=$this->alias;
		$q=$this->_em->createQuery('SELECT COUNT('.$a.') FROM '.$this->entityname.' '.$a
			.' LEFT OUTER JOIN '.$a.'.partner p'
			.' LEFT JOIN '.$a.'.termek t'
			.$this->getFilterString($filter));
		$q->setParameters($this->getQueryParameters($filter));
		return $q->getSingleScalarResult();
	}

	public function getMiniDataBySessionId($sessionid) {
		$filter=array();
		$filter['fields'][]='sessionid';
		$filter['clauses'][]='=';
		$filter['values'][]=$sessionid;
		$a=$this->alias;
		$q=$this->_em->createQuery('SELECT SUM('.$a.'.mennyiseg),SUM('.$a.'.bruttoegysar*'.$a.'.mennyiseg)'
			.' FROM '.$this->entityname.' '.$a
			.$this->getFilterString($filter));
		$q->setParameters($this->getQueryParameters($filter));
		return $q->getScalarResult();
	}

	public function getDataBySessionId($sessionid) {
		$filter=array();
		$filter['fields'][]='sessionid';
		$filter['clauses'][]='=';
		$filter['values'][]=$sessionid;
		return $this->getWithJoins($filter,array($this->alias.'.id'=>'ASC'));
	}

	public function getDataByPartner($partner) {
		$filter=array();
		$filter['fields'][]='partner';
		$filter['clauses'][]='=';
		$filter['values'][]=$partner;
		return $this->getWithJoins($filter,array($this->alias.'.id'=>'ASC'));
	}

	public function getTetelsor($sessionid,$partnerid,$termekid,$valtozatid,$valutanem) {
		$filter=array();
		if ($sessionid) {
			$filter['fields'][]='sessionid';
			$filter['clauses'][]='=';
			$filter['values'][]=$sessionid;
		}
		if ($partnerid) {
			$filter['fields'][]='partner';
			$filter['clauses'][]='=';
			$filter['values'][]=$partnerid;
		}
		if ($termekid) {
			$filter['fields'][]='termek';
			$filter['clauses'][]='=';
			$filter['values'][]=$termekid;
		}
		if ($valtozatid) {
			$filter['fields'][]='termekvaltozat';
			$filter['clauses'][]='=';
			$filter['values'][]=$valtozatid;
		}
		if ($sessionid) {
			$filter['fields'][]='valutanem';
			$filter['clauses'][]='=';
			$filter['values'][]=$valutanem;
		}
		if (count($filter)==0) {
			$filter['fields'][]='id';
			$filter['clauses'][]='<';
			$filter['values'][]='0';
		}
		$a=$this->alias;
		$q=$this->_em->createQuery('SELECT '.$a
			.' FROM '.$this->entityname.' '.$a
			.$this->getFilterString($filter));
		$q->setParameters($this->getQueryParameters($filter));
		$r=$q->getResult();
		if (count($r)>0) {
			return $r[0];
		}
		return null;
	}
}