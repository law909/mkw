<?php
namespace Entities;

use Entities\Hir;
use matt, \Doctrine\ORM, mkw\Store;

class HirRepository extends matt\Repository {

	public function __construct($em, \Doctrine\ORM\Mapping\ClassMetadata $class) {
		parent::__construct($em,$class);
		$this->setEntityname('Entities\Hir');
		$this->setOrders(array(
			'1'=>array('caption'=>'cím szerint','order'=>array('_xx.cim'=>'ASC'))
		));
	}

	public function getMaiHirek() {
		$filter=array();
		$filter['fields'][]='elsodatum';
		$filter['clauses'][]='<=';
		$filter['values'][]=date(store::$DateFormat);
		$filter['fields'][]='utolsodatum';
		$filter['clauses'][]='>=';
		$filter['values'][]=date(store::$DateFormat);
		$filter['fields'][]='lathato';
		$filter['clauses'][]='=';
		$filter['values'][]=true;
		$order=array('_xx.sorrend'=>'ASC','_xx.id'=>'DESC');
		$res=$this->getAll($filter,$order);
		return $res;
	}

	public function getFeedHirek() {
		$filter=array();
		$filter['fields'][]='lathato';
		$filter['clauses'][]='=';
		$filter['values'][]=true;
		$order=array('_xx.id'=>'DESC');
		$res=$this->getAll($filter,$order,0,store::getParameter('feedhirdb',20));
		return $res;
	}

/*	public function getWithJoins($filter,$order,$offset=0,$elemcount=0) {
		$a=$this->alias;
		$q=$this->_em->createQuery('SELECT '.$a
			.' FROM '.$this->entityname.' '.$a
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
			.$this->getFilterString($filter));
		$q->setParameters($this->getQueryParameters($filter));
		return $q->getSingleScalarResult();
	}
 */
}