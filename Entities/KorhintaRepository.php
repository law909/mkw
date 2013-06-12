<?php
namespace Entities;

use Entities\Korhinta;
use matt, \Doctrine\ORM;

class KorhintaRepository extends matt\Repository {

	public function __construct($em, \Doctrine\ORM\Mapping\ClassMetadata $class) {
		parent::__construct($em,$class);
		$this->setEntityname('Entities\Korhinta');
		$this->setOrders(array(
			'1'=>array('caption'=>'név szerint','order'=>array('_xx.nev'=>'ASC')),
			'2'=>array('caption'=>'sorrend szerint','order'=>array('_xx.sorrend'=>'ASC'))
		));
	}

	public function getAllLathato() {
		$filter=array();
		$filter['fields'][]='lathato';
		$filter['clauses'][]='=';
		$filter['values'][]='1';
		$order=array('sorrend'=>'ASC','nev'=>'ASC');
		return $this->getAll($filter,$order);
	}
}