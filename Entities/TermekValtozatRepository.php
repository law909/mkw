<?php
namespace Entities;

class TermekValtozatRepository extends \mkwhelpers\Repository {

	public function __construct($em, \Doctrine\ORM\Mapping\ClassMetadata $class) {
		parent::__construct($em,$class);
		$this->setEntityname('Entities\TermekValtozat');
		$this->setOrders(array(
			'1'=>array('caption'=>'név szerint növekvő','order'=>array('_xx.nev'=>'ASC'))
		));
	}

	public function getByProperties($termekid,$adattipusok,$ertekek) {
		$filter=array();
		$filter['fields'][]='termek';
		$filter['clauses'][]='=';
		$filter['values'][]=$termekid;
		if (count($adattipusok)==1) {
			if ($ertekek[0]) {
				$filter['sql'][]='((_xx.adattipus1='.$adattipusok[0].') AND (_xx.ertek1=\''.$ertekek[0].'\') AND (_xx.adattipus2 IS NULL)) OR '
					.'((_xx.adattipus2='.$adattipusok[0].') AND (_xx.ertek2=\''.$ertekek[0].'\') AND (_xx.adattipus1 IS NULL))';
			}
		}
		elseif (count($adattipusok)>1) {
			if ($ertekek[0]||$ertekek[1]) {
				$stra=$strb='(1=1)';
				if ($ertekek[0]) {
					$stra='((_xx.adattipus1='.$adattipusok[0].') AND (_xx.ertek1=\''.$ertekek[0].'\')) OR ((_xx.adattipus2='.$adattipusok[0].') AND (_xx.ertek2=\''.$ertekek[0].'\'))';
				}
				if ($ertekek[1]) {
					$strb='((_xx.adattipus2='.$adattipusok[1].') AND (_xx.ertek2=\''.$ertekek[1].'\')) OR ((_xx.adattipus1='.$adattipusok[1].') AND (_xx.ertek1=\''.$ertekek[1].'\'))';
				}
				$filter['sql'][]='(('.$stra.') AND ('.$strb.'))';
			}
//			$filter['sql'][]='((_xx.adattipus1='.$adattipusok[0].') AND (_xx.ertek1=\''.$ertekek[0].'\') AND (_xx.adattipus2='.$adattipusok[1].') AND (_xx.ertek2=\''.$ertekek[1].'\')) OR '
//				.'((_xx.adattipus1='.$adattipusok[1].') AND (_xx.ertek1=\''.$ertekek[1].'\') AND (_xx.adattipus2='.$adattipusok[0].') AND (_xx.ertek2=\''.$ertekek[0].'\'))';
		}
		$res=$this->getAll($filter,array());
		return $res[0];
	}

/* Ha van JOIN, ezek akkor kellenek
	public function getWithJoins($filter,$order,$offset=0,$elemcount=0) {
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