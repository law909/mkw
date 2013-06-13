<?php
namespace Entities;

class PartnerCsoportRepository extends \mkwhelpers\Repository {

	public function __construct($em, \Doctrine\ORM\Mapping\ClassMetadata $class) {
		parent::__construct($em,$class);
		$this->setEntityname('Entities\PartnerCsoport');
	}
}