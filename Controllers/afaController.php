<?php

namespace Controllers;

use mkw\store;

class afaController extends \mkwhelpers\JQGridController {

    public function __construct($params) {
        $this->setEntityName('Entities\Afa');
        parent::__construct($params);
    }

    protected function loadCells($sor) {
        return array($sor->getNev(), $sor->getErtek(), $sor->getRLBkod());
    }

    protected function setFields($obj) {
        $obj->setNev($this->params->getStringRequestParam('nev', $obj->getNev()));
        $obj->setErtek($this->params->getIntRequestParam('ertek', $obj->getErtek()));
        $obj->setRLBkod($this->params->getIntRequestParam('rlbkod', $obj->getRLBkod()));
        return $obj;
    }

    public function jsonlist() {
        $filter = new \mkwhelpers\FilterDescriptor();
        if ($this->params->getBoolRequestParam('_search', false)) {
            if (!is_null($this->params->getRequestParam('ertek', NULL))) {
                $filter->addFilter('ertek', '=', $this->params->getIntRequestParam('ertek'));
            }
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
                'selected' => ($sor->getId() == $selid),
                'afakulcs' => $sor->getErtek()
            );
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

}
