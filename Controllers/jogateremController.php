<?php

namespace Controllers;

use Entities\Jogaterem;
use mkw\store;

class jogateremController extends \mkwhelpers\JQGridController {

    public function __construct($params) {
        $this->setEntityName(Jogaterem::class);
        parent::__construct($params);
    }

    /**
     * @param $sor \Entities\Jogaterem
     * @return array
     */
    protected function loadCells($sor) {
        return array($sor->getNev(), $sor->getMaxferohely(), $sor->getInaktiv(), $sor->getOrarendclass());
    }

    /**
     * @param $obj \Entities\Jogaterem
     * @return mixed
     */
    protected function setFields($obj) {
        $obj->setNev($this->params->getStringRequestParam('nev', $obj->getNev()));
        $obj->setMaxferohely($this->params->getFloatRequestParam('maxferohely', $obj->getMaxferohely()));
        $obj->setInaktiv($this->params->getBoolRequestParam('inaktiv', $obj->getInaktiv()));
        $obj->setOrarendclass($this->params->getStringRequestParam('orarendclass', $obj->getOrarendclass()));
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

    public function getSelectList($selid = null) {
        $rec = $this->getRepo()->getAll(array(), array('nev' => 'ASC'));
        $res = array();
        /** @var \Entities\Jogaterem $sor */
        foreach ($rec as $sor) {
            $res[] = array(
                'id' => $sor->getId(),
                'caption' => $sor->getNev(),
                'selected' => ($sor->getId() == $selid),
                'maxferohely' => $sor->getMaxferohely()
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
