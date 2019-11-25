<?php
namespace Controllers;

use mkw\store;

class rendezvenyallapotController extends \mkwhelpers\JQGridController {

    public function __construct($params) {
        $this->setEntityName('Entities\Rendezvenyallapot');
        parent::__construct($params);
    }

    protected function getOrderArray() {
        $order = array();
        $order[$this->params->getRequestParam('sidx', 'sorrend')] = $this->params->getRequestParam('sord', 'ASC');
        return $order;
    }

    protected function loadCells($obj) {
        return array($obj->getNev(), $obj->getSorrend(), $obj->isOrarendbenszerepel());
    }

    protected function setFields($obj) {
        $obj->setNev($this->params->getStringRequestParam('nev', $obj->getNev()));
        $obj->setSorrend($this->params->getIntRequestParam('sorrend', $obj->getSorrend()));
        $obj->setOrarendbenszerepel($this->params->getBoolRequestParam('orarendbenszerepel', $obj->isOrarendbenszerepel()));
        return $obj;
    }

    public function jsonlist() {
        $filter = new \mkwhelpers\FilterDescriptor();
        if ($this->params->getBoolRequestParam('_search', false)) {
            if (!is_null($this->params->getRequestParam('nev', NULL))) {
                $filter->addFilter('nev', 'LIKE', '%' . $this->params->getStringRequestParam('nev') . '%');
            }
        }
        $rec = $this->getRepo()->getAll($filter, $this->getOrderArray());
        echo json_encode($this->loadDataToView($rec));
    }

    public function getSelectList($selid = null) {
        $rec = $this->getRepo()->getAll(array(), array('sorrend' => 'ASC'));
        $res = array();
        foreach ($rec as $sor) {
            $res[] = array('id' => $sor->getId(), 'caption' => $sor->getNev(), 'selected' => ($sor->getId() == $selid));
        }
        return $res;
    }

    public function htmllist() {
        $rec = $this->getRepo()->getAll(array(), array('sorrend' => 'asc'));
        $ret = '<select>';
        foreach ($rec as $sor) {
            $ret .= '<option value="' . $sor->getId() . '">' . $sor->getNev() . '</option>';
        }
        $ret .= '</select>';
        echo $ret;
    }
}