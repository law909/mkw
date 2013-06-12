<?php
namespace Entities;

use matt, \Doctrine\ORM;

class BizonylattipusRepository extends matt\Repository {

	public function __construct($em, \Doctrine\ORM\Mapping\ClassMetadata $class) {
		parent::__construct($em,$class);
		$this->setEntityname('Entities\Bizonylattipus');
	}
}