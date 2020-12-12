<?php

namespace Controllers;

use mkw\store;

class afaController extends \mkwhelpers\JQGridController {

    public function __construct($params) {
        $this->setEntityName('Entities\Afa');
        parent::__construct($params);
    }

    /**
     * @param \Entities\Afa $sor
     * @return mixed
     */
    protected function loadCells($sor) {
        return array($sor->getNev(), $sor->getErtek(), $sor->getNavcase(), $sor->getRLBkod(), $sor->getEmagid());
    }

    /**
     * @param \Entities\Afa $obj
     * @return mixed
     */
    protected function setFields($obj) {
        $obj->setNev($this->params->getStringRequestParam('nev', $obj->getNev()));
        $obj->setErtek($this->params->getIntRequestParam('ertek', $obj->getErtek()));
        $obj->setRLBkod($this->params->getIntRequestParam('rlbkod', $obj->getRLBkod()));
        $obj->setEmagid($this->params->getIntRequestParam('emagid', $obj->getEmagid()));
        $obj->setNavcase($this->params->getStringRequestParam('navcase', $obj->getNavcase()));
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

    public function navcaselist() {
        $cases = $this->getRepo()->getNavcaseList();
        $ret = '<select>';
        foreach ($cases as $case) {
            $ret .= '<option value="' . $case['id'] . '">' . $case['caption'] . '</option>';
        }
        $ret .= '</select>';
        echo $ret;
    }
}
