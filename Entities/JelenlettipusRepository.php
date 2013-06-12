<?php
namespace Entities;

use Entities\Jelenlettipus;
use matt, \Doctrine\ORM;

class JelenlettipusRepository extends matt\Repository {

	public function __construct($em, \Doctrine\ORM\Mapping\ClassMetadata $class) {
		parent::__construct($em,$class);
		$this->setEntityname('Entities\Jelenlettipus');
		$this->setOrders(array(
			'1'=>array('caption'=>'név szerint','order'=>array('_xx.nev'=>'ASC'))
		));
	}
}