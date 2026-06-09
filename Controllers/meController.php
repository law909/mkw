<?php

namespace Controllers;

use Entities\ME;
use mkw\store;

class meController extends \mkwhelpers\JQGridController
{

    public function __construct()
    {
        $this->setEntityName(ME::class);
        parent::__construct();
    }

    public function jsonlist()
    {
        $filter = [];
        if ($this->params->getBoolRequestParam('_search', false)) {
            if (!is_null($this->params->getRequestParam('nev', null))) {
                $filter['fields'][] = 'nev';
                $filter['values'][] = $this->params->getStringRequestParam('nev');
            }
        }
        $rec = $this->getRepo()->getAll($filter, $this->getOrderArray());
        echo json_encode($this->loadDataToView($rec));
    }

    public function getSelectList($selid)
    {
        $rec = $this->getRepo()->getAll([], ['nev' => 'ASC']);
        $res = [];
        foreach ($rec as $sor) {
            $res[] = ['id' => $sor->getId(), 'caption' => $sor->getNev(), 'selected' => ($sor->getId() == $selid)];
        }
        return $res;
    }

    public function htmllist()
    {
        $rec = $this->getRepo()->getAll([], ['nev' => 'asc']);
        $ret = '<select>';
        foreach ($rec as $sor) {
            $ret .= '<option value="' . $sor->getId() . '">' . $sor->getNev() . '</option>';
        }
        $ret .= '</select>';
        echo $ret;
    }

    public function navtipuslist()
    {
        $ar = [
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
        ];
        $ret = '<select>';
        foreach ($ar as $e) {
            $ret .= '<option value="' . $e . '">' . $e . '</option>';
        }
        $ret .= '</select>';
        echo $ret;
    }

    protected function loadCells($obj)
    {
        return [$obj->getNev(), $obj->getNavtipus()];
    }

    protected function setFields($obj)
    {
        $obj->setNev($this->params->getStringRequestParam('nev', $obj->getNev()));
        $obj->setNavtipus($this->params->getStringRequestParam('navtipus', $obj->getNavtipus()));
        return $obj;
    }
}