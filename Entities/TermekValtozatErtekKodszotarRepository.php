<?php
namespace Entities;

class TermekValtozatErtekKodszotarRepository extends \mkwhelpers\Repository {

	public function __construct($em, \Doctrine\ORM\Mapping\ClassMetadata $class) {
		parent::__construct($em,$class);
		$this->setEntityname('Entities\TermekValtozatErtekKodszotar');
	}

	public function isAllTranslated() {
        $filter = new \mkwhelpers\FilterDescriptor();
        $filter->addSql('(_xx.kod=\'\') OR (_xx.kod IS NULL)');
        $q = $this->_em->createQuery('SELECT COUNT(_xx)'
            . ' FROM Entities\TermekValtozatErtekKodszotar _xx'
            . $this->getFilterString($filter)
        );
        $q->setParameters($this->getQueryParameters($filter));
        $res = $q->getScalarResult();
        return $res[0][1] * 1;
    }

	public function translate($mit) {
        $x = $this->findOneBy(array('ertek' => $mit));
        if ($x) {
            return $x->getKod();
        }
        return $mit;
    }
}