<?php
namespace Entities;

class TermekKepRepository extends \mkwhelpers\Repository {

	public function __construct($em, \Doctrine\ORM\Mapping\ClassMetadata $class) {
		parent::__construct($em,$class);
		$this->setEntityname('Entities\TermekKep');
//		$this->setOrders(array());
	}

	public function getByTermek($termek) {
		$filter=array();
        if ($termek) {
            $filter['fields'][]='termek';
            $filter['clauses'][]='=';
            $filter['values'][]=$termek;
    		return $this->getAll($filter,array());
        }
        return null;
	}
}