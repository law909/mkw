<?php

namespace Entities;

use mkwhelpers\FilterDescriptor;

class FoxpostTerminalRepository extends \mkwhelpers\Repository {

    public function __construct($em, \Doctrine\ORM\Mapping\ClassMetadata $class) {
        parent::__construct($em, $class);
        $this->setEntityname('Entities\FoxpostTerminal');
    }

    public function getCsoportok() {
        $q = $this->_em->createQuery('SELECT DISTINCT _xx.csoport'
                . ' FROM Entities\FoxpostTerminal _xx'
                . ' ORDER BY _xx.csoport');
        return $q->getScalarResult();
    }

    public function getByCsoport($csoport = null) {
        if ($csoport) {
            $filter = new FilterDescriptor();
            $filter->addFilter('csoport', '=', $csoport);
        }
        $rec = $this->getRepo('Entities\FoxpostTerminal')->getAll($filter);
        return $rec;
    }
}