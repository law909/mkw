<?php

namespace Entities;

use mkwhelpers\Filter;
use mkwhelpers\FilterDescriptor;

class FizmodRepository extends \mkwhelpers\Repository
{

    public function __construct($em, \Doctrine\ORM\Mapping\ClassMetadata $class)
    {
        parent::__construct($em, $class);
        $this->setEntityname(Fizmod::class);
        $this->setOrders([
            '1' => ['caption' => 'név szerint növekvő', 'order' => ['_xx.nev' => 'ASC']],
            '2' => ['caption' => 'sorrend szerint növekvő', 'order' => ['_xx.sorrend' => 'ASC']]
        ]);
    }

    // a webshopos POST-ok ezen keresztül veszik át a fizetési módot: az inaktív nem küldhető vissza
    public function findActive($id)
    {
        $fizmod = $this->find($id);
        return $fizmod && !$fizmod->getInaktiv() ? $fizmod : null;
    }

    public function getAllWebesBySzallitasimod($szmid, $exc = [])
    {
        $szm = false;
        if (!is_null($szmid)) {
            $szm = $this->_em->getRepository(Szallitasimod::class)->find($szmid);
        }
        $filter = new FilterDescriptor();
        $filter->addFilter('webes', '=', true);
        if ($szm) {
            $filter->addFilter('id', 'IN', explode(',', $szm->getFizmodok()));
        }
        if ($exc) {
            $filter->addFilter('id', 'NOT IN', $exc);
        }
        return $this->getAll($filter, ['sorrend' => 'ASC', 'nev' => 'ASC']);
    }

    public function getAllBySzallitasimod($szmid, $exc = [])
    {
        $szm = false;
        if (!is_null($szmid)) {
            $szm = $this->_em->getRepository(Szallitasimod::class)->find($szmid);
        }
        $filter = new FilterDescriptor();
        if ($szm) {
            $filter->addFilter('id', 'IN', explode(',', $szm->getFizmodok()));
        }
        if ($exc) {
            $filter->addFilter('id', 'NOT IN', $exc);
        }
        return $this->getAll($filter, ['sorrend' => 'ASC', 'nev' => 'ASC']);
    }

    public function getAllBanki()
    {
        $filter = new FilterDescriptor();
        $filter->addFilter('tipus', '=', 'B');
        return $this->getAll($filter, ['sorrend' => 'ASC', 'nev' => 'ASC']);
    }

    public function getAllKeszpenzes()
    {
        $filter = new FilterDescriptor();
        $filter->addFilter('tipus', '=', 'P');
        return $this->getAll($filter, ['sorrend' => 'ASC', 'nev' => 'ASC']);
    }

    public function getAllNormal()
    {
        $fm = [];
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
        return $this->getAll($filter, ['sorrend' => 'ASC', 'nev' => 'ASC']);
    }

}
