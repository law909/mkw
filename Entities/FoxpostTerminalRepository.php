<?php

namespace Entities;

class FoxpostTerminalRepository extends \mkwhelpers\Repository {

    public function __construct($em, \Doctrine\ORM\Mapping\ClassMetadata $class) {
        parent::__construct($em, $class);
        $this->setEntityname('Entities\FoxpostTerminal');
    }

    public function getCsoportok() {
        $a = $this->alias;
        $q = $this->_em->createQuery('SELECT DISTINCT ' . $a . '.csoport'
                . ' FROM ' . $this->entityname . ' ' . $a
                . ' ORDER BY ' . $a . '.csoport');
        return $q->getScalarResult();
    }

    public function getByCsoport($csoport = null) {
        if ($csoport) {
            $filter = array();
            $filter['fields'][] = 'csoport';
            $filter['clauses'][] = '=';
            $filter['values'][] = $csoport;
        }
        $rec = $this->getRepo('Entities\FoxpostTerminal')->getAll($filter);
        return $rec;
    }
}