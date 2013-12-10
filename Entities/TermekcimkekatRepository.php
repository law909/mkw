<?php

namespace Entities;

class TermekcimkekatRepository extends \mkwhelpers\Repository {

    public function __construct($em, \Doctrine\ORM\Mapping\ClassMetadata $class) {
        parent::__construct($em, $class);
        $this->setEntityname('Entities\Termekcimkekat');
    }

    public function getWithJoins($filter, $order, $offset = 0, $elemcount = 0) {
        $a = $this->alias;
        $q = $this->_em->createQuery('SELECT ' . $a . ',c FROM ' . $this->entityname . ' ' . $a
                . ' LEFT JOIN ' . $a . '.cimkek c '
                . $this->getFilterString($filter)
                . $this->getOrderString($order));
        $q->setParameters($this->getQueryParameters($filter));
        if ($offset > 0) {
            $q->setFirstResult($offset);
        }
        if ($elemcount > 0) {
            $q->setMaxResults($elemcount);
        }
        return $q->getResult();
    }

    public function getScalarWithJoins($filter, $order, $offset = 0, $elemcount = 0) {
        $a = $this->alias;
        $q = $this->_em->createQuery('SELECT ' . $a . '.id,' . $a . '.nev,' . $a . '.slug,' . $a . '.sorrend,c.id AS cid,c.nev AS cnev,c.sorrend AS csorrend FROM ' . $this->entityname . ' ' . $this->alias
                . ' LEFT JOIN ' . $this->alias . '.cimkek c '
                . $this->getFilterString($filter)
                . $this->getOrderString($order));
        $q->setParameters($this->getQueryParameters($filter));
        if ($offset > 0) {
            $q->setFirstResult($offset);
        }
        if ($elemcount > 0) {
            $q->setMaxResults($elemcount);
        }
        return $q->getScalarResult();
    }

    public function findWithJoins($id) {
        // TODO SQLINJECTION
        $res = $this->getWithJoins($this->alias . '.id=' . $id, array());
        return $res[0];
    }

    public function getCount($filter) {
        $a = $this->alias;
        $q = $this->_em->createQuery('SELECT COUNT(' . $this->alias . '.id) FROM ' . $this->entityname . ' ' . $this->alias
                . ' LEFT JOIN ' . $this->alias . '.cimkek c '
                . $this->getFilterString($filter));
        $q->setParameters($this->getQueryParameters($filter));
        return $q->getSingleScalarResult();
    }

    public function getForTermekSzuro($termekid, $selectedids) {
        $a = $this->alias;
        if (count($termekid) > 0) {
            $filter = '(' . $a . '.termekszurobenlathato=1) AND (t.id IN (' . implode(',', $termekid) . '))';
            if (count($selectedids) > 0) {
                $filter = $filter . ' OR (c.id IN (' . implode(',', $selectedids) . '))';
            }
        }
        else {
            $filter = 'true=false';
        }
        $order = array('_xx.sorrend' => 'asc', '_xx.nev' => 'asc', 'c.sorrend' => 'asc', 'c.nev' => 'asc');
        $q = $this->_em->createQuery('SELECT ' . $a . ',c,t FROM ' . $this->entityname . ' ' . $a
                . ' LEFT JOIN ' . $a . '.cimkek c '
                . ' INNER JOIN c.termekek t '
                . $this->getFilterString($filter)
                . $this->getOrderString($order));
        $q->setParameters($this->getQueryParameters($filter));
        return $q->getResult();
    }

}
