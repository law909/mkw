<?php
namespace Controllers;

use mkw\store;

class partnercimkekatController extends \mkwhelpers\JQGridController {

    public function __construct($params) {
        $this->setEntityName('Entities\Partnercimkekat');
        parent::__construct($params);
    }

    protected function loadCells($sor) {
        return array($sor->getNev(), $sor->getLathato());
    }

    protected function setFields($obj) {
        $obj->setNev($this->params->getStringRequestParam('nev', $obj->getNev()));
        $obj->setLathato($this->params->getBoolRequestParam('lathato'));
        return $obj;
    }

    public function jsonlist() {
        $filter = new \mkwhelpers\FilterDescriptor();
        if ($this->params->getBoolRequestParam('_search', false)) {
            if (!is_null($this->params->getRequestParam('lathato', NULL))) {
                $filter->addFilter('lathato', '=', $this->params->getBoolRequestParam('lathato'));
            }
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

    public function getWithCimkek($selected = null) {
        $cimkekat = $this->getRepo()->getWithJoins(array(), array('_xx.nev' => 'asc', 'c.nev' => 'asc'));
        $res = array();
        foreach ($cimkekat as $kat) {
            $adat = array();
            $cimkek = $kat->getCimkek();
            foreach ($cimkek as $cimke) {
                $adat[] = array(
                    'id' => $cimke->getId(),
                    'caption' => $cimke->getNev(),
                    'selected' => ($selected && ($selected->contains($cimke)) ? true : false)
                );
            }
            $res[] = array(
                'id' => $kat->getId(),
                'caption' => $kat->getNev(),
                'sanitizedcaption' => str_replace('.', '', $kat->getSlug()),
                'cimkek' => $adat
            );
        }
        return $res;
    }
}