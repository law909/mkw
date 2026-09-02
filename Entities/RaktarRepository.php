<?php
namespace Entities;

class RaktarRepository extends \mkwhelpers\Repository {

	public function __construct($em, \Doctrine\ORM\Mapping\ClassMetadata $class) {
		parent::__construct($em,$class);
		$this->setEntityname('Entities\Raktar');
		$this->setOrders([
		    '1' => ['caption' => 'név szerint növekvő', 'order' => ['_xx.nev' => 'ASC']],
		    '2' => ['caption' => 'név szerint csökkenő', 'order' => ['_xx.nev' => 'DESC']],
		]);
	}

	public function getAllActive() {
	    $filter = new \mkwhelpers\FilterDescriptor();
	    // az archiv nullable és alapérték nélküli: a puszta `<> 1` a NULL sorokat is kizárná
	    $filter->addSql('((_xx.archiv IS NULL) OR (_xx.archiv <> 1))');
	    return $this->getAll($filter, ['nev' => 'ASC']);
    }
}