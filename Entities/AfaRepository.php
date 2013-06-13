<?php
namespace Entities;

class AfaRepository extends \mkwhelpers\Repository {

	public function __construct($em, \Doctrine\ORM\Mapping\ClassMetadata $class) {
		parent::__construct($em,$class);
		$this->entityname='Entities\Afa';
	}
}