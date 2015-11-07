<?php

namespace Entities;

use Doctrine\ORM\Query\ResultSetMapping;
use mkwhelpers\FilterDescriptor;

class TermekKepRepository extends \mkwhelpers\Repository {

    public function __construct($em, \Doctrine\ORM\Mapping\ClassMetadata $class) {
        parent::__construct($em, $class);
        $this->setEntityname('Entities\TermekKep');
    }

    public function getByTermek($termek) {
        $filter = new FilterDescriptor();
        if ($termek) {
            $filter->addFilter('termek', '=', $termek);
            return $this->getAll($filter, array());
        }
        return array();
    }

    public function getByTermekForSitemapXml($termek) {
        $rsm = new ResultSetMapping();
        $rsm->addScalarResult('url', 'url');
        $rsm->addScalarResult('leiras', 'leiras');
        $q = $this->_em->createNativeQuery('SELECT url,leiras'
            . ' FROM termekkep '
            . ' WHERE termek_id = ' . $termek, $rsm);
        return $q->getScalarResult();
    }

}
