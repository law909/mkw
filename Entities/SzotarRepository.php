<?php
namespace Entities;

class SzotarRepository extends \mkwhelpers\Repository {

	public function __construct($em, \Doctrine\ORM\Mapping\ClassMetadata $class) {
		parent::__construct($em,$class);
		$this->setEntityname('Entities\Szotar');
	}

	public function isAllTranslated() {
        $filter = new \mkwhelpers\FilterDescriptor();
        $filter->addSql('(_xx.mire=\'\') OR (_xx.mire IS NULL)');
        $q = $this->_em->createQuery('SELECT COUNT(_xx)'
            . ' FROM Entities\Szotar _xx'
            . $this->getFilterString($filter)
        );
        $q->setParameters($this->getQueryParameters($filter));
        $res = $q->getScalarResult();
        return $res[0][1] * 1;
    }

	public function translate($mit) {
        $x = $this->find($mit);
        if ($x) {
            return $x->getMire();
        }
        return $mit;
    }
}