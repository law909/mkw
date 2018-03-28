<?php
namespace Entities;

class RaktarRepository extends \mkwhelpers\Repository {

	public function __construct($em, \Doctrine\ORM\Mapping\ClassMetadata $class) {
		parent::__construct($em,$class);
		$this->setEntityname('Entities\Raktar');
	}

	public function getAllActive() {
	    $filter = new \mkwhelpers\FilterDescriptor();
	    $filter->addFilter('archiv', '<>', 1);
	    return $this->getAll($filter);
    }
}