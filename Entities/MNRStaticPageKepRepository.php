<?php

namespace Entities;

use Doctrine\ORM\Query\ResultSetMapping;
use mkwhelpers\FilterDescriptor;

class MNRStaticPageKepRepository extends \mkwhelpers\Repository
{

    public function __construct($em, \Doctrine\ORM\Mapping\ClassMetadata $class)
    {
        parent::__construct($em, $class);
        $this->setEntityname(MNRStaticPageKep::class);
    }

    public function getByPage($page)
    {
        $filter = new FilterDescriptor();
        if ($page) {
            $filter->addFilter('mnrstaticpage', '=', $page);
            return $this->getAll($filter, []);
        }
        return [];
    }

}
