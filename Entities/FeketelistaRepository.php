<?php

namespace Entities;

class FeketelistaRepository extends \mkwhelpers\Repository
{

    public function __construct($em, \Doctrine\ORM\Mapping\ClassMetadata $class)
    {
        parent::__construct($em, $class);
        $this->setEntityname(Feketelista::class);
        $this->setOrders([
            '1' => ['caption' => 'dátum szerint', 'order' => ['_xx.created' => 'ASC']],
            '2' => ['caption' => 'email szerint', 'order' => ['_xx.email' => 'ASC']]
        ]);
    }

    public function isEmailOrIP($search1, $search2 = null)
    {
        $a = $this->alias;
        $filter = new \mkwhelpers\FilterDescriptor();
        $filter->addFilter('email', 'IN', [$search1, $search2]);
        $q = $this->_em->createQuery(
            'SELECT COUNT(' . $a . ')'
            . ' FROM ' . $this->entityname . ' ' . $a
            . $this->getFilterString($filter)
        );
        $q->setParameters($this->getQueryParameters($filter));
        return $q->getSingleScalarResult() > 0;
    }

    public function getFeketelistaOk($search1, $search2 = null)
    {
        $a = $this->alias;
        $filter = new \mkwhelpers\FilterDescriptor();
        $filter->addFilter('email', 'IN', [$search1, $search2]);
        $q = $this->_em->createQuery(
            'SELECT ' . $a . '.ok'
            . ' FROM ' . $this->entityname . ' ' . $a
            . $this->getFilterString($filter)
        );
        $q->setParameters($this->getQueryParameters($filter));
        $res = $q->getResult();
        if ($res) {
            return $res[0]['ok'];
        }
        return false;
    }

}
