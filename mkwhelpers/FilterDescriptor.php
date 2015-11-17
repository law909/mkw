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

    public function getFilterString($palias = '_xx') {
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
                        $alias = $palias . '.';
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

    public function getQueryParameters() {
        $filter = $this->getFilter();
        $paramarr = array();
        if (array_key_exists('values', $filter)) {
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

}