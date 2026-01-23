<?php

namespace Entities;

use Doctrine\ORM\Query\ResultSetMapping;
use mkwhelpers\FilterDescriptor;

class CsapatKepRepository extends \mkwhelpers\Repository
{

    public function __construct($em, \Doctrine\ORM\Mapping\ClassMetadata $class)
    {
        parent::__construct($em, $class);
        $this->setEntityname(CsapatKep::class);
    }

    public function getByCsapat($csapat)
    {
        $filter = new FilterDescriptor();
        if ($csapat) {
            $filter->addFilter('csapat', '=', $csapat);
            return $this->getAll($filter, []);
        }
        return [];
    }

    public function getByCsapatForSitemapXml($csapat)
    {
        $rsm = new ResultSetMapping();
        $rsm->addScalarResult('url', 'url');
        $rsm->addScalarResult('leiras', 'leiras');
        $q = $this->_em->createNativeQuery(
            'SELECT url,leiras'
            . ' FROM csapatkep '
            . ' WHERE csapat_id = ' . $csapat,
            $rsm
        );
        return $q->getScalarResult();
    }

}
