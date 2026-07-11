<?php

namespace Controllers;

use Entities\Irszam;
use mkw\store;

class irszamController extends \mkwhelpers\JQGridController
{

    public function __construct()
    {
        $this->setEntityName(Irszam::class);
        parent::__construct();
    }

    protected function loadCells($obj)
    {
        return [$obj->getSzam(), $obj->getNev()];
    }

    protected function setFields($obj)
    {
        return $this->setEntityFieldsFromRequest($obj);
    }

    public function jsonlist()
    {
        $filter = new \mkwhelpers\FilterDescriptor();
        if ($this->params->getBoolRequestParam('_search', false)) {
            if (!is_null($this->params->getRequestParam('szam', null))) {
                $filter->addFilter('szam', 'LIKE', '%' . $this->params->getStringRequestParam('id') . '%');
            }
            if (!is_null($this->params->getRequestParam('nev', null))) {
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

    public function htmllist()
    {
        $rec = $this->getRepo()->getAll([], ['nev' => 'asc']);
        $ret = '<select>';
        foreach ($rec as $sor) {
            $ret .= '<option value="' . $sor->getId() . '">' . $sor->getSzam() . ' ' . $sor->getNev() . '</option>';
        }
        $ret .= '</select>';
        echo $ret;
    }

    public function typeaheadList()
    {
        $filter = new \mkwhelpers\FilterDescriptor();
        $ret = [];
        $term = $this->params->getStringRequestParam('term');
        $tip = $this->params->getIntRequestParam('tip');
        if ($term) {
            $filter->addFilter('szam', 'LIKE', trim($term) . '%');
        }
        $rec = $this->getRepo()->getAll($filter, ['szam' => 'asc']);
        if ($tip) {
            foreach ($rec as $sor) {
                $ret[] = [
                    'label' => $sor->getSzam() . ' ' . $sor->getNev(),
                    'value' => $sor->getSzam(),
                    'nev' => $sor->getNev()
                ];
            }
        } else {
            foreach ($rec as $sor) {
                $ret[] = [
                    'szam' => $sor->getSzam(),
                    'nev' => $sor->getNev(),
                    'id' => $sor->getSzam() . ' ' . $sor->getNev()
                ];
            }
        }
        echo json_encode($ret);
    }

    public function varosTypeaheadList()
    {
        $filter = [];
        $ret = [];
        $term = $this->params->getStringRequestParam('term');
        $tip = $this->params->getIntRequestParam('tip');
        if ($term) {
            $filter['fields'][] = 'nev';
//			$filter['clauses'][]='LIKE';
            $filter['values'][] = trim($term);
        }
        $rec = $this->getRepo()->getAll($filter, ['nev' => 'asc']);
        if ($tip) {
            foreach ($rec as $sor) {
                $ret[] = [
                    'label' => $sor->getSzam() . ' ' . $sor->getNev(),
                    'value' => $sor->getNev(),
                    'szam' => $sor->getSzam()
                ];
            }
        } else {
            foreach ($rec as $sor) {
                $ret[] = [
                    'szam' => $sor->getSzam(),
                    'nev' => $sor->getNev(),
                    'id' => $sor->getSzam() . ' ' . $sor->getNev()
                ];
            }
        }
        echo json_encode($ret);
    }
}