<?php
namespace Entities;

use matt, \Doctrine\ORM;

class FizmodRepository extends matt\Repository {

	public function __construct($em, \Doctrine\ORM\Mapping\ClassMetadata $class) {
		parent::__construct($em,$class);
		$this->setEntityname('Entities\Fizmod');
	}
}