<?php
namespace Controllers;

use mkw\store;

class felhasznaloController extends \mkwhelpers\JQGridController {

    public function __construct($params) {
        $this->setEntityName('Entities\Felhasznalo');
        parent::__construct($params);
    }

    protected function loadCells($obj) {
        return array($obj->getNev(), $obj->getFelhasznalonev(), $obj->getJelszo(), $obj->getUzletkotoNev());
    }

    protected function setFields($obj) {
        $obj->setNev($this->params->getStringRequestParam('nev', $obj->getNev()));
        $obj->setFelhasznalonev($this->params->getStringRequestParam('felhasznalonev', $obj->getFelhasznalonev()));
        $obj->setJelszo($this->params->getStringRequestParam('jelszo', $obj->getJelszo()));
        $ck = store::getEm()->getRepository('Entities\Uzletkoto')->find($this->params->getIntRequestParam('uzletkoto', 0));
        if ($ck) {
            $obj->setUzletkoto($ck);
        }
        else {
        }
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

    public function getSelectList($selid) {
        $rec = $this->getRepo()->getAll(array(), array('nev' => 'ASC'));
        $res = array();
        foreach ($rec as $sor) {
            $res[] = array('id' => $sor->getId(), 'caption' => $sor->getNev(), 'selected' => ($sor->getId() == $selid));
        }
        return $res;
    }
}