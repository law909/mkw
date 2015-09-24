<?php

namespace Controllers;

use mkw\store;

class szallitasimodController extends \mkwhelpers\JQGridController {

    public function __construct($params) {
        $this->setEntityName('Entities\Szallitasimod');
        parent::__construct($params);
    }

    protected function loadCells($obj) {
        return array($obj->getNev(), $obj->getWebes(), $obj->getLeiras(), $obj->getFizmodok(), $obj->getSorrend(), $obj->getVanszallitasiktg());
    }

    protected function setFields($obj) {
        $obj->setNev($this->params->getStringRequestParam('nev', $obj->getNev()));
        $obj->setWebes($this->params->getBoolRequestParam('webes'));
        $obj->setLeiras($this->params->getStringRequestParam('leiras'));
        $obj->setFizmodok($this->params->getStringRequestParam('fizmodok'));
        $obj->setSorrend($this->params->getIntRequestParam('sorrend'));
        $obj->setVanszallitasiktg($this->params->getBoolRequestParam('vanszallitasiktg'));
        return $obj;
    }

    public function jsonlist() {
        $filter = array();
        if ($this->params->getBoolRequestParam('_search', false)) {
            if (!is_null($this->params->getRequestParam('nev', NULL))) {
                $filter['fields'][] = 'nev';
                $filter['values'][] = $this->params->getStringRequestParam('nev');
            }
        }
        $rec = $this->getRepo()->getAll($filter, $this->getOrderArray());
        echo json_encode($this->loadDataToView($rec));
    }

    public function getSelectList($selid = null, $mind = false) {
        $foxpostid = \mkw\Store::getParameter(\mkw\consts::FoxpostSzallitasiMod);
        if ($mind) {
            $rec = $this->getRepo()->getAll(array(),array('sorrend'=>'ASC','nev'=>'ASC'));
        }
        else {
            $rec = $this->getRepo()->getAllWebes();
        }
        $res = array();
        // mkwnál ki kell választani az elsőt
        $vanvalasztott = \mkw\Store::getTheme() !== 'mkwcansas';
        foreach ($rec as $sor) {
            $r = array(
                'id' => $sor->getId(),
                'caption' => $sor->getNev(),
                'leiras' => $sor->getLeiras(),
                'foxpost' => ($sor->getId() == $foxpostid)
            );
            if ($selid) {
                $r['selected'] = $sor->getId() == $selid;
            }
            else {
                if (!$mind) {
                    if (!$vanvalasztott) {
                        $r['selected'] = true;
                        $vanvalasztott = true;
                    }
                    else {
                        $r['selected'] = false;
                    }
                }
            }
            $res[] = $r;
        }
        return $res;
    }

    public function htmllist() {
        $rec = $this->getRepo()->getAll(array(), array('nev' => 'asc'));
        $ret = '<select>';
        foreach ($rec as $sor) {
            $ret.='<option value="' . $sor->getId() . '">' . $sor->getNev() . '</option>';
        }
        $ret.='</select>';
        echo $ret;
    }

}
