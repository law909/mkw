<?php

namespace Controllers;

use mkw\store;

class meController extends \mkwhelpers\JQGridController {

    public function __construct($params) {
        $this->setEntityName('Entities\ME');
        parent::__construct($params);
    }

    public function jsonlist() {
        $filter = array();
        if ($this->params->getBoolRequestParam('_search', false)) {
            if (!is_null($this->params->getRequestParam('nev', NULL))) {
                $filter['fields'][] = 'nev';
                $filter['values'][] = $this->params->getStringRequestParam('nev');
            }
        }
        $rec = $this->getRepo()->getAll($filter, $this->getOrderArray());
        echo json_encode($this->loadDataToView($rec));
    }

    public function getSelectList($selid) {
        $rec = $this->getRepo()->getAll(array(), array('nev' => 'ASC'));
        $res = array();
        foreach ($rec as $sor) {
            $res[] = array('id' => $sor->getId(), 'caption' => $sor->getNev(), 'selected' => ($sor->getId() == $selid));
        }
        return $res;
    }

    public function htmllist() {
        $rec = $this->getRepo()->getAll(array(), array('nev' => 'asc'));
        $ret = '<select>';
        foreach ($rec as $sor) {
            $ret .= '<option value="' . $sor->getId() . '">' . $sor->getNev() . '</option>';
        }
        $ret .= '</select>';
        echo $ret;
    }

    public function navtipuslist() {
        $ar = array(
            'PIECE',
            'KILOGRAM',
            'TON',
            'KWH',
            'DAY',
            'HOUR',
            'MINUTE',
            'MONTH',
            'LITER',
            'KILOMETER',
            'CUBIC_METER',
            'METER',
            'LINEAR_METER',
            'CARTON',
            'PACK',
            'OWN'
        );
        $ret = '<select>';
        foreach ($ar as $e) {
            $ret .= '<option value="' . $e . '">' . $e . '</option>';
        }
        $ret .= '</select>';
        echo $ret;
    }

    protected function loadCells($obj) {
        return array($obj->getNev(), $obj->getNavtipus());
    }

    protected function setFields($obj) {
        $obj->setNev($this->params->getStringRequestParam('nev', $obj->getNev()));
        $obj->setNavtipus($this->params->getStringRequestParam('navtipus', $obj->getNavtipus()));
        return $obj;
    }
}