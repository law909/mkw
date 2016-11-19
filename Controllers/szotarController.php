<?php

namespace Controllers;

use mkw\store;

class szotarController extends \mkwhelpers\JQGridController {

    public function __construct($params) {
        $this->setEntityName('Entities\Szotar');
        parent::__construct($params);
    }

    protected function loadCells($sor) {
        return array($sor->getMit(), $sor->getMire());
    }

    protected function setFields($obj) {
        $obj->setMit($this->params->getStringRequestParam('mit', $obj->getMit()));
        $obj->setMire($this->params->getStringRequestParam('mire', $obj->getMire()));
        return $obj;
    }

    public function jsonlist() {
        $filter = new \mkwhelpers\FilterDescriptor();
        if ($this->params->getBoolRequestParam('_search', false)) {
            if (!is_null($this->params->getRequestParam('mit', NULL))) {
                $filter->addFilter('mit', '=', $this->params->getStringRequestParam('mit'));
            }
            if (!is_null($this->params->getParam('mire', NULL))) {
                $filter->addFilter('mire', 'LIKE', '%' . $this->params->getStringRequestParam('mire') . '%');
            }
        }
        $rec = $this->getRepo()->getAll($filter, $this->getOrderArray());
        echo json_encode($this->loadDataToView($rec));
    }

    public function getSelectList($selid) {
        $rec = $this->getRepo()->getAll(array(), array('mit' => 'ASC'));
        $res = array();
        foreach ($rec as $sor) {
            $res[] = array(
                'id' => $sor->getId(),
                'mit' => $sor->getMit(),
                'selected' => ($sor->getId() == $selid),
                'mire' => $sor->getMire()
            );
        }
        return $res;
    }

    public function htmllist() {
        $rec = $this->getRepo()->getAll(array(), array('mit' => 'asc'));
        $ret = '<select>';
        foreach ($rec as $sor) {
            $ret .= '<option value="' . $sor->getId() . '">' . $sor->getMire() . '</option>';
        }
        $ret .= '</select>';
        echo $ret;
    }

}
