<?php
namespace Entities;

class JelenlettipusRepository extends \mkwhelpers\Repository {

	public function __construct($em, \Doctrine\ORM\Mapping\ClassMetadata $class) {
		parent::__construct($em,$class);
		$this->setEntityname('Entities\Jelenlettipus');
		$this->setOrders(array(
			'1'=>array('caption'=>'név szerint növekvő','order'=>array('_xx.nev'=>'ASC'))
		));
	}
}