<?php
namespace Entities;

use Entities\TermekValtozatAdatTipus;
use matt, \Doctrine\ORM;

class TermekValtozatAdatTipusRepository extends matt\Repository {

	public function __construct($em, \Doctrine\ORM\Mapping\ClassMetadata $class) {
		parent::__construct($em,$class);
		$this->setEntityname('Entities\TermekValtozatAdatTipus');
		$this->setOrders(array(
			'1'=>array('caption'=>'név szerint','order'=>array('_xx.nev'=>'ASC'))
		));
	}
}