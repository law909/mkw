<?php

namespace mkwhelpers;


class FilterDescriptor implements \Countable {

    private $filter = array();

    public function addFilter($field, $clause, $value) {
        $this->filter['fields'][] = $field;
        $this->filter['clauses'][] = $clause;
        $this->filter['values'][] = $value;
        return $this;
    }

    public function addSql($sql) {
        $this->filter['sql'][] = $sql;
        return $this;
    }

    public function addArray($array) {
        $this->filter = array_merge_recursive($this->filter, $array);
        return $this;
    }

    public function getFilter() {
        return $this->filter;
    }

    public function getArray() {
        return $this->filter;
    }

    public function merge($masik) {
        if ($masik instanceof FilterDescriptor) {
            $m = $masik->getArray();
        }
        elseif (is_array($masik)) {
            $m = $masik;
        }
        $ret = new FilterDescriptor();
        $ret->addArray($this->getArray());
        if ($m) {
            $ret->addArray($m);
        }
        return $ret;
    }

    public function count() {
        return count($this->filter);
    }
}