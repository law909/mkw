<?php

namespace Controllers;

use mkw\store;

class vtszController extends \mkwhelpers\JQGridController {

    public function __construct($params) {
        $this->setEntityName('Entities\Vtsz');
        parent::__construct($params);
    }

    protected function loadCells($sor) {
        $afa = $sor->getAfa();
        return array(
            $sor->getSzam(),
            $sor->getNev(),
            (isset($afa) ? $afa->getNev() : ''),
            $sor->getKozvetitett());
    }

    protected function setFields($obj) {
        $obj->setSzam($this->params->getStringRequestParam('szam', $obj->getSzam()));
        $obj->setNev($this->params->getStringRequestParam('nev', $obj->getNev()));
        $afa = store::getEm()->getReference('Entities\Afa', $this->params->getIntRequestParam('afa', $obj->getAfa()));
        $obj->setAfa($afa);
        $obj->setKozvetitett($this->params->getBoolRequestParam('kozvetitett'));
        return $obj;
    }

    public function jsonlist() {
        $filter = array();
        if ($this->params->getBoolRequestParam('_search', false)) {
            if (!is_null($this->params->getRequestParam('afa', NULL))) {
                $filter['fields'][] = 'a.nev';
                $filter['values'][] = $this->params->getStringRequestParam('afa');
            }
            if (!is_null($this->params->getRequestParam('nev', NULL))) {
                $filter['fields'][] = 'nev';
                $filter['values'][] = $this->params->getStringRequestParam('nev');
            }
        }
        $rec = $this->getRepo()->getAll($filter, $this->getOrderArray());
        echo json_encode($this->loadDataToView($rec));
    }

    public function getSelectList($selid) {
        // TODO ezen gyorsitani kell, getAll helyett ScalarResult
        $rec = $this->getRepo()->getAll(array(), array('szam' => 'ASC', 'nev' => 'ASC'));
        $res = array();
        foreach ($rec as $sor) {
            $res[] = array(
                'id' => $sor->getId(),
                'caption' => $sor->getSzam() . ' ' . $sor->getNev(),
                'selected' => ($sor->getId() == $selid),
                'afa' => $sor->getAfaId()
            );
        }
        return $res;
    }

}
