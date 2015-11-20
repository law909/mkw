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

    public function find($id, $lockMode = null, $lockVersion = null) {
        if (isset($id)) {
            return parent::find($id, $lockMode, $lockVersion);
        }
        return null;
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
        $filter = new \mkwhelpers\FilterDescriptor();
        $filter->addFilter('id', '=', $id);
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

    public function getFilterString($filter) {
        if ($filter instanceof FilterDescriptor) {
            return $filter->getFilterString($this->alias);
        }
        $f = new FilterDescriptor();
        if (is_array($filter)) {
            $f->addArray($filter);
        }
        if (is_string($filter)) {
            $f->addSql($filter);
        }
        return $f->getFilterString($this->alias);
    }

    public function getQueryParameters($filter) {
        if ($filter instanceof FilterDescriptor) {
            return $filter->getQueryParameters();
        }
        $f = new FilterDescriptor();
        if (is_array($filter)) {
            $f->addArray($filter);
        }
        if (is_string($filter)) {
            $f->addSql($filter);
        }
        return $f->getQueryParameters();
    }

    public function getOrderString($order) {
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

    public function getLimitString($offset, $limit) {
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
