<?php

namespace Entities;

use mkwhelpers\Filter;
use mkwhelpers\FilterDescriptor;

class FizmodRepository extends \mkwhelpers\Repository {

    public function __construct($em, \Doctrine\ORM\Mapping\ClassMetadata $class) {
        parent::__construct($em, $class);
        $this->setEntityname('Entities\Fizmod');
        $this->setOrders(array(
            '1' => array('caption' => 'név szerint növekvő','order' => array('_xx.nev' => 'ASC')),
            '2' => array('caption' => 'sorrend szerint növekvő', 'order' => array('_xx.sorrend' => 'ASC'))
        ));
    }

    public function getAllWebesBySzallitasimod($szmid, $exc = array()) {
        if (!is_null($szmid)) {
            $szm = $this->_em->getRepository('Entities\Szallitasimod')->find($szmid);
        }
        $filter = new FilterDescriptor();
        $filter->addFilter('webes', '=', true);
        if ($szm) {
            $filter->addFilter('id', 'IN', explode(',', $szm->getFizmodok()));
        }
        if ($exc) {
            $filter->addFilter('id', 'NOT IN', $exc);
        }
        return $this->getAll($filter, array('sorrend' => 'ASC', 'nev' => 'ASC'));
    }

    public function getAllBySzallitasimod($szmid, $exc = array()) {
        if (!is_null($szmid)) {
            $szm = $this->_em->getRepository('Entities\Szallitasimod')->find($szmid);
        }
        $filter = new FilterDescriptor();
        if ($szm) {
            $filter->addFilter('id', 'IN', explode(',', $szm->getFizmodok()));
        }
        if ($exc) {
            $filter->addFilter('id', 'NOT IN', $exc);
        }
        return $this->getAll($filter, array('sorrend' => 'ASC', 'nev' => 'ASC'));
    }

    public function getAllBanki() {
        $filter = new FilterDescriptor();
        $filter->addFilter('tipus', '=', 'B');
        return $this->getAll($filter, array('sorrend' => 'ASC', 'nev' => 'ASC'));
    }

    public function getAllKeszpenzes() {
        $filter = new FilterDescriptor();
        $filter->addFilter('tipus', '=', 'P');
        return $this->getAll($filter, array('sorrend' => 'ASC', 'nev' => 'ASC'));
    }

    public function getAllNormal() {
        $fm = array();
        if (\mkw\store::getParameter(\mkw\consts::SZEPFizmod)) {
            $fm[] = \mkw\store::getParameter(\mkw\consts::SZEPFizmod);
        }
        if (\mkw\store::getParameter(\mkw\consts::SportkartyaFizmod)) {
            $fm[] = \mkw\store::getParameter(\mkw\consts::SportkartyaFizmod);
        }
        if (\mkw\store::getParameter(\mkw\consts::AYCMFizmod)) {
            $fm[] = \mkw\store::getParameter(\mkw\consts::AYCMFizmod);
        }
        $filter = new FilterDescriptor();
        if ($fm) {
            $filter->addSql('_xx.id NOT IN (' . implode(',', $fm) . ')');
        }
        return $this->getAll($filter, array('sorrend' => 'ASC', 'nev' => 'ASC'));
    }

}
