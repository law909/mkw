<?php

namespace mkwhelpers;


class FilterDescriptor implements \Countable {

    private $filter = array();
    private $join = array();

    public function clear() {
        $this->filter = array();
        $this->join = array();
    }

    public function addFilter($field, $clause, $value) {
        if (!$field || is_null($value)) {
            return $this;
        }
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
        if (is_array($array)) {
            $this->filter = array_merge_recursive($this->filter, $array);
        }
        return $this;
    }

    public function addJoin($str) {
        $this->join[] = $str;
    }

    public function getFilter() {
        return $this->filter;
    }

    public function getJoin() {
        return $this->join;
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

    public function getFilterString($palias = '_xx', $parampre = 'p') {
        $str = implode(' ', $this->join);
        $f = $this->_getFilterString($palias, $parampre);
        return ' ' . implode(' ', array($str, $f));
    }

    protected function _getFilterString($palias = '_xx', $parampre = 'p') {
        $filter = $this->getFilter();
        if (array_key_exists('fields', $filter) && array_key_exists('values', $filter)) {
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
                            $alias = $palias . '.';
                        }
                        else {
                            $alias = '';
                        }
                        if (is_array($value)) { // tobb ertek van, IN lesz
                            if (isset($filter['clauses']) && $filter['clauses'][$cnt]) {
                                $vcnt = 1;
                                $ize = array();
                                foreach ($value as $va) { // az IN minden ertekenek csinalunk egy-egy parametert
                                    $innerfilter[] = '(' . $alias . $v . ' ' . $felt . ' :' . $parampre . $fno . 'v' . $vcnt . ')';
                                    $vcnt++;
                                }
                            }
                            else {
                                $vcnt = 1;
                                $ize = array();
                                foreach ($value as $va) { // az IN minden ertekenek csinalunk egy-egy parametert
                                    $ize[] = ':' . $parampre . $fno . 'v' . $vcnt;
                                    $vcnt++;
                                }
                                if ($ize) {
                                    $innerfilter[] = '(' . $alias . $v . ' ' . $felt . ' (' . implode(',', $ize) . '))';
                                }
                            }
                        }
                        else {
                            $innerfilter[] = '(' . $alias . $v . ' ' . $felt . ' :' . $parampre . $fno . ')';
                        }
                    }
                    if ($innerfilter) {
                        $filterarr[] = '(' . implode(' OR ', $innerfilter) . ')';
                    }
                }
                else { // egy mezoben szurunk
                    if (strpos($field, '.') === false) {
                        $alias = $palias . '.';
                    }
                    else {
                        $alias = '';
                    }
                    if (is_array($value) || $felt == 'IN') { // tobb ertek van, ez egy IN lesz
                        $vcnt = 1;
                        $ize = array();
                        foreach ($value as $v) {
                            $ize[] = ':' . $parampre . $fno . 'v' . $vcnt;
                            $vcnt++;
                        }
                        $filterarr[] = '(' . $alias . $field . ' ' . $felt . ' (' . implode(',', $ize) . '))';
                    }
                    else { // egy ertek van
                        $filterarr[] = '(' . $alias . $field . ' ' . $felt . ' :' . $parampre . $fno . ')';
                    }
                }
                $fno++;
            }
            if (array_key_exists('sql', $filter)) {
                $sql = $filter['sql'];
                foreach ($sql as $cnt => $s) {
                    if ($s) {
                        $filterarr[] = '(' . $s . ')';
                    }
                }
            }
            $filterstring = implode(' AND ', $filterarr);
            if ($filterstring != '') {
                $filterstring = ' WHERE ' . $filterstring;
            }
            return $filterstring;
        }
        elseif (array_key_exists('sql', $filter)) {
            $filterarr = array();
            $sql = $filter['sql'];
            foreach ($sql as $cnt => $s) {
                if ($s) {
                    $filterarr[] = '(' . $s . ')';
                }
            }
            $filterstring = implode(' AND ', $filterarr);
            if ($filterstring != '') {
                $filterstring = ' WHERE ' . $filterstring;
            }
            return $filterstring;
        }
        else {
            return '';
        }
    }

    public function getQueryParameters($parampre = 'p') {
        $filter = $this->getFilter();
        $paramarr = array();
        if (array_key_exists('values', $filter)) {
            $values = $filter['values'];
            $fno = 1;
            foreach ($values as $value) {
                if (is_string($value)) {
                    if (array_key_exists('clauses', $filter) && $filter['clauses'][$fno - 1]) {
                        $paramarr[$parampre . $fno] = $value;
                    }
                    else {
                        $paramarr[$parampre . $fno] = '%' . $value . '%';
                    }
                }
                elseif (is_numeric($value)) {
                    $paramarr[$parampre . $fno] = $value;
                }
                elseif (is_bool($value)) {
                    $paramarr[$parampre . $fno] = (int) $value;
                }
                elseif (is_array($value)) {
                    $vno = 1;
                    foreach ($value as $v) {
                        $paramarr[$parampre . $fno . 'v' . $vno] = $v;
                        $vno++;
                    }
                }
                elseif (is_object($value)) {
                    $paramarr[$parampre . $fno] = $value;
                }
                $fno++;
            }
        }
        return $paramarr;
    }

}