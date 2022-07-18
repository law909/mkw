<?php

namespace Entities;

use mkwhelpers\FilterDescriptor;

class CsomagTerminalRepository extends \mkwhelpers\Repository {

    public function __construct($em, \Doctrine\ORM\Mapping\ClassMetadata $class) {
        parent::__construct($em, $class);
        $this->setEntityname('Entities\CsomagTerminal');
    }

    public function getCsoportok($tipus = null) {
        if ($tipus) {
            $filter = new FilterDescriptor();
            $filter->addFilter('tipus', '=', $tipus);
            $filter->addFilter('inaktiv', '=', false);
            $q = $this->_em->createQuery('SELECT DISTINCT _xx.csoport'
                . ' FROM Entities\CsomagTerminal _xx'
                . $this->getFilterString($filter)
                . ' ORDER BY _xx.csoport');
            $q->setParameters($this->getQueryParameters($filter));
            return $q->getScalarResult();
        }
        return null;
    }

    public function getByCsoport($csoport = null, $tipus = null, $order = null) {
        if ($tipus) {
            $filter = new FilterDescriptor();
            $filter->addFilter('tipus', '=', $tipus);
            if ($csoport) {
                $filter->addFilter('csoport', '=', $csoport);
            }
            else {
                $filter->addSql('1=0');
            }
            $filter->addFilter('inaktiv', '=', false);
            $rec = $this->getRepo('Entities\CsomagTerminal')->getAll($filter, $order);
            return $rec;
        }
        return null;
    }
}