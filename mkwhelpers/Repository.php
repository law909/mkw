<?php

namespace mkwhelpers;

use \Doctrine\ORM\EntityRepository;

class Repository extends EntityRepository {

    protected $alias = '_xx';
    protected $entityname;
    protected $orders;
    protected $batches;

    public function getRepo($entityname) {
        return $this->_em->getRepository($entityname);
    }

    public function getAll($filter = array(), $order = array(), $offset = 0, $elemcount = 0) {
        $q = $this->_em->createQuery('SELECT ' . $this->alias . ' FROM ' . $this->entityname . ' ' . $this->alias
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

    public function getAllSQL($filter = array(), $order = array(), $offset = 0, $elemcount = 0) {
        $q = $this->_em->createQuery('SELECT ' . $this->alias . ' FROM ' . $this->entityname . ' ' . $this->alias
                . $this->getFilterString($filter)
                . $this->getOrderString($order));
        $q->setParameters($this->getQueryParameters($filter));
        if ($offset > 0) {
            $q->setFirstResult($offset);
        }
        if ($elemcount > 0) {
            $q->setMaxResults($elemcount);
        }
        return $q->getSQL();
    }

    public function findWithJoins($id) {
        $filter = array();
        $filter['fields'][] = 'id';
        $filter['clauses'][] = '=';
        $filter['values'][] = $id;
        $res = $this->getWithJoins($filter, array());
        if (count($res)) {
            return $res[0];
        }
        return null;
    }

    public function getCount($filter) {
        $a = $this->alias;
        $q = $this->_em->createQuery('SELECT COUNT(' . $a . ') FROM ' . $this->entityname . ' ' . $a
                . $this->getFilterString($filter));
        $q->setParameters($this->getQueryParameters($filter));
        return $q->getSingleScalarResult();
    }

    protected function getFilterString($filter) {
        if (is_array($filter) && array_key_exists('fields', $filter) && array_key_exists('values', $filter)) {
            $fno = 1;
            $filterarr = array();
            $fields = $filter['fields'];
            $values = $filter['values'];
            foreach ($fields as $cnt => $field) {
                $value = $values[$cnt];
                if (isset($filter['clauses']) && $filter['clauses'][$cnt]) {
                    $felt = $filter['clauses'][$cnt];
                }
                else {
                    if (is_string($value)) {
                        $felt = 'LIKE';
                    }
                    elseif (is_numeric($value)) {
                        $felt = '=';
                    }
                    elseif (is_bool($value)) {
                        $felt = '=';
                    }
                    elseif (is_array($value)) {
                        $felt = 'IN';
                    }
                    else {
                        $felt = '';
                    }
                }
                if (is_array($field)) { // tobb mezoben szurjuk ugyanazt az erteket
                    $innerfilter = array();
                    foreach ($field as $v) {
                        if (strpos($v, '.') === false) {
                            $alias = $this->alias . '.';
                        }
                        else {
                            $alias = '';
                        }
                        if (is_array($value)) { // tobb ertek van, IN lesz
                            if (isset($filter['clauses']) && $filter['clauses'][$cnt]) {
                                $vcnt = 1;
                                $ize = array();
                                foreach ($value as $va) { // az IN minden ertekenek csinalunk egy-egy parametert
                                    $innerfilter[] = '(' . $alias . $v . ' ' . $felt . ' :p' . $fno . 'v' . $vcnt . ')';
                                    $vcnt++;
                                }
                            }
                            else {
                                $vcnt = 1;
                                $ize = array();
                                foreach ($value as $va) { // az IN minden ertekenek csinalunk egy-egy parametert
                                    $ize[] = ':p' . $fno . 'v' . $vcnt;
                                    $vcnt++;
                                }
                                $innerfilter[] = '(' . $alias . $v . ' ' . $felt . ' (' . implode(',', $ize) . '))';
                            }
                        }
                        else {
                            $innerfilter[] = '(' . $alias . $v . ' ' . $felt . ' :p' . $fno . ')';
                        }
                    }
                    $filterarr[] = '(' . implode(' OR ', $innerfilter) . ')';
                }
                else { // egy mezoben szurunk
                    if (strpos($field, '.') === false) {
                        $alias = $this->alias . '.';
                    }
                    else {
                        $alias = '';
                    }
                    if (is_array($value) || $felt == 'IN') { // tobb ertek van, ez egy IN lesz
                        $vcnt = 1;
                        $ize = array();
                        foreach ($value as $v) {
                            $ize[] = ':p' . $fno . 'v' . $vcnt;
                            $vcnt++;
                        }
                        $filterarr[] = '(' . $alias . $field . ' ' . $felt . ' (' . implode(',', $ize) . '))';
                    }
                    else { // egy ertek van
                        $filterarr[] = '(' . $alias . $field . ' ' . $felt . ' :p' . $fno . ')';
                    }
                }
                $fno++;
            }
            if (array_key_exists('sql', $filter)) {
                $sql = $filter['sql'];
                foreach ($sql as $cnt => $s) {
                    $filterarr[] = '(' . $s . ')';
                }
            }
            $filterstring = implode(' AND ', $filterarr);
            if ($filterstring != '') {
                $filterstring = ' WHERE ' . $filterstring;
            }
            return $filterstring;
        }
        elseif (is_string($filter) && ($filter <> '')) {
            return ' WHERE ' . $filter;
        }
        else {
            return '';
        }
    }

    protected function getQueryParameters($filter) {
        $paramarr = array();
        if (is_array($filter) && array_key_exists('values', $filter)) {
            $values = $filter['values'];
            $fno = 1;
            foreach ($values as $value) {
                if (is_string($value)) {
                    if (array_key_exists('clauses', $filter) && $filter['clauses'][$fno - 1]) {
                        $paramarr['p' . $fno] = $value;
                    }
                    else {
                        $paramarr['p' . $fno] = '%' . $value . '%';
                    }
                }
                elseif (is_numeric($value)) {
                    $paramarr['p' . $fno] = $value;
                }
                elseif (is_bool($value)) {
                    $paramarr['p' . $fno] = (int) $value;
                }
                elseif (is_array($value)) {
                    $vno = 1;
                    foreach ($value as $v) {
                        $paramarr['p' . $fno . 'v' . $vno] = $v;
                        $vno++;
                    }
                }
                elseif (is_object($value)) {
                    $paramarr['p' . $fno] = $value;
                }
                $fno++;
            }
        }
        return $paramarr;
    }

    protected function getOrderString($order) {
        // TODO SQLINJECTION
        $orderarr = array();
        $orderstring = '';
        if ($order) {
            foreach ($order as $field => $irany) {
                if (strpos($field, '.') === false) {
                    $orderarr[] = $this->alias . '.' . $field . ' ' . $irany;
                }
                else {
                    if (strpos($field, '.') === 0) {
                        $orderarr[] = substr($field, 1) . ' ' . $irany;
                    }
                    else {
                        $orderarr[] = $field . ' ' . $irany;
                    }
                }
            }
            $orderstring = implode(',', $orderarr);
            if ($orderstring != '') {
                $orderstring = ' ORDER BY ' . $orderstring;
            }
        }
        return $orderstring;
    }

    protected function getLimitString($offset, $limit) {
        if ($offset && $limit) {
            return ' LIMIT ' . $offset . ', ' . $limit;
        }
        elseif ($limit) {
            return ' LIMIT ' . $limit;
        }
        return '';
    }

    public function getOrder($id) {
        if (!is_array($this->orders)) {
            return array();
        }
        return $this->orders[$id]['order'];
    }

    public function getBatchesForTpl() {
        $batchtpl = array();
        if ($this->batches) {
            foreach ($this->batches as $bid => $bcaption) {
                $batchtpl[] = array('id' => $bid, 'caption' => $bcaption);
            }
        }
        return $batchtpl;
    }

    public function getOrdersForTpl() {
        $orders = $this->getOrders();
        $ordertpl = array();
        foreach ($orders as $oid => $odata) {
            $ordertpl[] = array(
                'id' => $oid,
                'caption' => $odata['caption'],
                'selected' => $oid == 1
            );
        }
        return $ordertpl;
    }

    public function getOrders() {
        return $this->orders;
    }

    public function setOrders($orders) {
        $this->orders = $orders;
    }

    public function getEntityname() {
        return $this->entityname;
    }

    public function setEntityname($entityname) {
        $this->entityname = $entityname;
    }

    public function getBatches() {
        return $this->batches;
    }

    public function setBatches($batches) {
        $this->batches = $batches;
    }

    public function addToBatches($batches) {
        if (!is_array($this->batches)) {
            $this->batches = $batches;
        }
        else {
            $this->batches = array_merge_recursive($this->batches, $batches);
        }
    }
}
