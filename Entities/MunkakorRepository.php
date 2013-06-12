<?php
namespace Entities;

use Entities\Munkakor;
use matt, \Doctrine\ORM;

class MunkakorRepository extends matt\Repository {

	public function __construct($em, \Doctrine\ORM\Mapping\ClassMetadata $class) {
		parent::__construct($em,$class);
		$this->setEntityname('Entities\Munkakor');
		$this->setOrders(array(
			'1'=>array('caption'=>'név szerint','order'=>array('_xx.nev'=>'ASC'))
		));
	}
}