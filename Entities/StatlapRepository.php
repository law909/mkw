<?php
namespace Entities;

use Doctrine\ORM\Query\ResultSetMapping;

class StatlapRepository extends \mkwhelpers\Repository {

	public function __construct($em, \Doctrine\ORM\Mapping\ClassMetadata $class) {
		parent::__construct($em,$class);
		$this->setEntityname('Entities\Statlap');
		$this->setOrders(array(
			'1'=>array('caption'=>'oldalcím szerint növekvő','order'=>array('_xx.oldalcim'=>'ASC'))
		));
	}

	public function getForSitemapXml() {
		$rsm=new ResultSetMapping();
		$rsm->addScalarResult('id', 'id');
		$rsm->addScalarResult('slug','slug');
		$rsm->addScalarResult('lastmod','lastmod');
		$q=$this->_em->createNativeQuery('SELECT id,slug,lastmod'
			.' FROM statlap '
			.' ORDER BY id',$rsm);
		return $q->getScalarResult();
	}
}