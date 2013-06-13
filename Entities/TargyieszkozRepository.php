<?php
namespace Entities;
use Entities\Targyieszkoz;

class TargyieszkozRepository extends \mkwhelpers\Repository {

	public function __construct($em, \Doctrine\ORM\Mapping\ClassMetadata $class) {
		parent::__construct($em,$class);
		$this->setEntityname('Entities\Targyieszkoz');
		$this->setOrders(array(
			'1'=>array('caption'=>'név szerint','order'=>array('_xx.nev'=>'ASC')),
			'2'=>array('caption'=>'leltári szám szerint','order'=>array('_xx.leltariszam'=>'ASC')),
			'3'=>array('caption'=>'csoport szerint','order'=>array('cs.nev'=>'ASC')),
			'4'=>array('caption'=>'beszerzés dátuma szerint','order'=>array('_xx.beszerzesdatum'=>'ASC')),
			'5'=>array('caption'=>'elszámolás kezdete számviteli tv. szerint','order'=>array('_xx.szvtvelszkezdete'=>'ASC'))
		));
	}

	public function getWithJoins($filter,$order,$offset=0,$elemcount=0) {
		$a=$this->alias;
		$q=$this->_em->createQuery('SELECT '.$a
			.' FROM '.$this->entityname.' '.$a
			.' LEFT JOIN '.$a.'.csoport cs '
			.' LEFT JOIN '.$a.'.alkalmazott alk '
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
			.' LEFT JOIN '.$a.'.csoport cs '
			.' LEFT JOIN '.$a.'.alkalmazott alk '
		.$this->getFilterString($filter));
		$q->setParameters($this->getQueryParameters($filter));
		return $q->getSingleScalarResult();
	}

	public function getHasznalatiHelyek($searchterm) {
		$filter['fields'][]='hasznalatihelyek';
		$filter['values'][]=$searchterm;
		$a=$this->alias;
		$q=$this->_em->createQuery('SELECT DISTINCT '.$a.'.hasznalatihely FROM '.$this->entityname.' '.$a
			.$this->getFilterString($filter)
			.' ORDER BY '.$a.'.hasznalatihely');
		return $q->getScalarResult();
	}

	public function getTipusok() {
		return Targyieszkoz::getTipusok();
	}

	public function getEcselszmodok() {
		return Targyieszkoz::getEcselszmodok();
	}

	public function getAllapotok() {
		return Targyieszkoz::getAllapotok();
	}
}