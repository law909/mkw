<?php

namespace Controllers;

class partnertipusController extends \mkwhelpers\JQGridController {

    public function __construct($params) {
        $this->setEntityName('Entities\Partnertipus');
        parent::__construct($params);
    }

    protected function loadCells($sor) {
        return array(
            $sor->getNev(),
            $sor->getBelephet(),
            $sor->getBelephet2(),
            $sor->getBelephet3(),
            $sor->getBelephet4(),
            $sor->getBelephet5(),
            $sor->getBelephet6(),
            $sor->getBelephet7(),
            $sor->getBelephet8(),
            $sor->getBelephet9(),
            $sor->getBelephet10(),
            $sor->getBelephet11(),
            $sor->getBelephet12(),
            $sor->getBelephet13(),
            $sor->getBelephet14(),
            $sor->getBelephet15()
        );
    }

    protected function setFields($obj) {
        $obj->setNev($this->params->getStringRequestParam('nev', $obj->getNev()));
        $obj->setBelephet($this->params->getStringRequestParam('belephet'));
        $obj->setBelephet2($this->params->getStringRequestParam('belephet2'));
        $obj->setBelephet3($this->params->getStringRequestParam('belephet3'));
        $obj->setBelephet4($this->params->getStringRequestParam('belephet4'));
        $obj->setBelephet5($this->params->getStringRequestParam('belephet5'));
        $obj->setBelephet6($this->params->getStringRequestParam('belephet6'));
        $obj->setBelephet7($this->params->getStringRequestParam('belephet7'));
        $obj->setBelephet8($this->params->getStringRequestParam('belephet8'));
        $obj->setBelephet9($this->params->getStringRequestParam('belephet9'));
        $obj->setBelephet10($this->params->getStringRequestParam('belephet10'));
        $obj->setBelephet11($this->params->getStringRequestParam('belephet11'));
        $obj->setBelephet12($this->params->getStringRequestParam('belephet12'));
        $obj->setBelephet13($this->params->getStringRequestParam('belephet13'));
        $obj->setBelephet14($this->params->getStringRequestParam('belephet14'));
        $obj->setBelephet15($this->params->getStringRequestParam('belephet15'));
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
        foreach ($rec as $sor) {
            $ret .= '<option value="' . $sor->getId() . '">' . $sor->getNev() . '</option>';
        }
        $ret .= '</select>';
        echo $ret;
    }

}
