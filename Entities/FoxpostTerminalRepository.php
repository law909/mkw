<?php

namespace Entities;

use mkwhelpers\FilterDescriptor;

class FoxpostTerminalRepository extends \mkwhelpers\Repository {

    public function __construct($em, \Doctrine\ORM\Mapping\ClassMetadata $class) {
        parent::__construct($em, $class);
        $this->setEntityname('Entities\FoxpostTerminal');
    }

    public function getCsoportok($tipus = null) {
        if ($tipus) {
            $filter = new FilterDescriptor();
            $filter->addFilter('tipus', '=', $tipus);
            $filter->addFilter('inaktiv', '=', false);
            $q = $this->_em->createQuery('SELECT DISTINCT _xx.csoport'
                . ' FROM Entities\FoxpostTerminal _xx'
                . $this->getFilterString($filter)
                . ' ORDER BY _xx.csoport');
            $q->setParameters($this->getQueryParameters($filter));
            return $q->getScalarResult();
        }
        return null;
    }

    public function getByCsoport($csoport = null, $tipus = null) {
        if ($tipus) {
            $filter = new FilterDescriptor();
            $filter->addFilter('tipus', '=', $tipus);
            if ($csoport) {
                $filter->addFilter('csoport', '=', $csoport);
            }
            $filter->addFilter('inaktiv', '=', false);
            $rec = $this->getRepo('Entities\FoxpostTerminal')->getAll($filter);
            return $rec;
        }
        return null;
    }
}