<?php

namespace Controllers;

use mkw\store;

class jogaoratipusController extends \mkwhelpers\JQGridController {

    public function __construct($params) {
        $this->setEntityName('Entities\Jogaoratipus');
        parent::__construct($params);
    }

    /**
     * @param $sor \Entities\Jogaoratipus
     * @return array
     */
    protected function loadCells($sor) {
        return array($sor->getNev(), $sor->getArnovelo(), $sor->getSzin(), $sor->getInaktiv(), $sor->getUrl());
    }

    /**
     * @param $obj \Entities\Jogaoratipus
     * @return mixed
     */
    protected function setFields($obj) {
        $obj->setNev($this->params->getStringRequestParam('nev', $obj->getNev()));
        $obj->setArnovelo($this->params->getNumRequestParam('arnovelo', $obj->getArnovelo()));
        $obj->setSzin($this->params->getStringRequestParam('szin', $obj->getSzin()));
        $obj->setInaktiv($this->params->getBoolRequestParam('inaktiv', $obj->getInaktiv()));
        $obj->setUrl($this->params->getStringRequestParam('url', $obj->getUrl()));
        return $obj;
    }

    public function jsonlist() {
        $filter = new \mkwhelpers\FilterDescriptor();
        if ($this->params->getBoolRequestParam('_search', false)) {
            if (!is_null($this->params->getParam('nev', NULL))) {
                $filter->addFilter('nev', 'LIKE', '%' . $this->params->getStringRequestParam('nev') . '%');
            }
        }
        $rec = $this->getRepo()->getAll($filter, $this->getOrderArray());
        echo json_encode($this->loadDataToView($rec));
    }

    public function getSelectList($selid) {
        $rec = $this->getRepo()->getAll(array(), array('nev' => 'ASC'));
        $res = array();
        foreach ($rec as $sor) {
            $res[] = array(
                'id' => $sor->getId(),
                'caption' => $sor->getNev(),
                'selected' => ($sor->getId() == $selid)
            );
        }
        return $res;
    }

    public function htmllist() {
        $rec = $this->getRepo()->getAll(array(), array('nev' => 'asc'));
        $ret = '<select>';
        $ret .= '<option value="0">Válasszon</option>';
        foreach ($rec as $sor) {
            $ret .= '<option value="' . $sor->getId() . '">' . $sor->getNev() . '</option>';
        }
        $ret .= '</select>';
        echo $ret;
    }

}
