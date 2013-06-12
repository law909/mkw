<?php
namespace Entities;

use Entities\Emailtemplate;
use matt, \Doctrine\ORM;

class EmailtemplateRepository extends matt\Repository {

	public function __construct($em, \Doctrine\ORM\Mapping\ClassMetadata $class) {
		parent::__construct($em,$class);
		$this->setEntityname('Entities\Emailtemplate');
		$this->setOrders(array(
			'1'=>array('caption'=>'név szerint','order'=>array('_xx.nev'=>'ASC'))
		));
	}

}