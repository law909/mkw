<?php
namespace Entities;

class Rewrite301Repository extends \mkwhelpers\Repository {

	public function __construct($em, \Doctrine\ORM\Mapping\ClassMetadata $class) {
		parent::__construct($em,$class);
		$this->setEntityname('Entities\Rewrite301');
		$this->setOrders([
		    '1' => ['caption' => 'forrás szerint növekvő', 'order' => ['_xx.fromurl' => 'ASC']],
		    '2' => ['caption' => 'forrás szerint csökkenő', 'order' => ['_xx.fromurl' => 'DESC']],
		]);
	}

}
