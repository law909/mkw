<?php
namespace Entities;

use Entities\Raktar;
use matt, \Doctrine\ORM;

class RaktarRepository extends matt\Repository {

	public function __construct($em, \Doctrine\ORM\Mapping\ClassMetadata $class) {
		parent::__construct($em,$class);
		$this->setEntityname('Entities\Raktar');
	}
}