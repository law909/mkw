<?php
namespace Controllers;

use mkw\store;

class irszamController extends \mkwhelpers\JQGridController {

    public function __construct($params) {
        $this->setEntityName('Entities\Irszam');
        parent::__construct($params);
    }

    protected function loadCells($obj) {
        return array($obj->getSzam(), $obj->getNev());
    }

    protected function setFields($obj) {
        $obj->setSzam($this->params->getStringRequestParam('szam', $obj->getSzam()));
        $obj->setNev($this->params->getStringRequestParam('nev', $obj->getNev()));
        return $obj;
    }

    public function jsonlist() {
        $filter = new \mkwhelpers\FilterDescriptor();
        if ($this->params->getBoolRequestParam('_search', false)) {
            if (!is_null($this->params->getRequestParam('szam', NULL))) {
                $filter->addFilter('szam', 'LIKE', '%' . $this->params->getStringRequestParam('id') . '%');
            }
            if (!is_null($this->params->getRequestParam('nev', NULL))) {
                $filter->addFilter('nev', 'LIKE', '%' . $this->params->getStringRequestParam('nev') . '%');
            }
        }
        $rec = $this->getRepo()->getAll($filter, $this->getOrderArray());
        echo json_encode($this->loadDataToView($rec));
    }

    /* MINTA ha nem kell, dobd ki
    public function getSelectList($selid) {
        $rec=$this->getRepo()->getAll(array(),array('nev'=>'ASC'));
        $res=array();
        foreach($rec as $sor) {
            $res[]=array('id'=>$sor->getId(),'caption'=>$sor->getNev(),'selected'=>($sor->getId()==$selid));
        }
        return $res;
    }
    */

    public function htmllist() {
        $rec = $this->getRepo()->getAll(array(), array('nev' => 'asc'));
        $ret = '<select>';
        foreach ($rec as $sor) {
            $ret .= '<option value="' . $sor->getId() . '">' . $sor->getSzam() . ' ' . $sor->getNev() . '</option>';
        }
        $ret .= '</select>';
        echo $ret;
    }

    public function typeaheadList() {
        $filter = new \mkwhelpers\FilterDescriptor();
        $ret = array();
        $term = $this->params->getStringRequestParam('term');
        $tip = $this->params->getIntRequestParam('tip');
        if ($term) {
            $filter->addFilter('szam', 'LIKE', trim($term) . '%');
        }
        $rec = $this->getRepo()->getAll($filter, array('szam' => 'asc'));
        if ($tip) {
            foreach ($rec as $sor) {
                $ret[] = array(
                    'label' => $sor->getSzam() . ' ' . $sor->getNev(),
                    'value' => $sor->getSzam(),
                    'nev' => $sor->getNev()
                );
            }
        }
        else {
            foreach ($rec as $sor) {
                $ret[] = array(
                    'szam' => $sor->getSzam(),
                    'nev' => $sor->getNev(),
                    'id' => $sor->getSzam() . ' ' . $sor->getNev()
                );
            }
        }
        echo json_encode($ret);
    }

    public function varosTypeaheadList() {
        $filter = array();
        $ret = array();
        $term = $this->params->getStringRequestParam('term');
        $tip = $this->params->getIntRequestParam('tip');
        if ($term) {
            $filter['fields'][] = 'nev';
//			$filter['clauses'][]='LIKE';
            $filter['values'][] = trim($term);
        }
        $rec = $this->getRepo()->getAll($filter, array('nev' => 'asc'));
        if ($tip) {
            foreach ($rec as $sor) {
                $ret[] = array(
                    'label' => $sor->getSzam() . ' ' . $sor->getNev(),
                    'value' => $sor->getNev(),
                    'szam' => $sor->getSzam()
                );
            }
        }
        else {
            foreach ($rec as $sor) {
                $ret[] = array(
                    'szam' => $sor->getSzam(),
                    'nev' => $sor->getNev(),
                    'id' => $sor->getSzam() . ' ' . $sor->getNev()
                );
            }
        }
        echo json_encode($ret);
    }
}