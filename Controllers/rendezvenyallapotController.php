<?php

namespace Controllers;

use Entities\Rendezvenyallapot;
use mkw\store;

class rendezvenyallapotController extends \mkwhelpers\JQGridController
{

    public function __construct()
    {
        $this->setEntityName(Rendezvenyallapot::class);
        parent::__construct();
    }

    protected function getOrderArray()
    {
        $order = [];
        $order[$this->params->getRequestParam('sidx', 'sorrend')] = $this->params->getRequestParam('sord', 'ASC');
        return $order;
    }

    protected function loadCells($obj)
    {
        return [$obj->getNev(), $obj->getSorrend(), $obj->isOrarendbenszerepel()];
    }

    protected function setFields($obj)
    {
        return $this->setEntityFieldsFromRequest($obj);
    }

    public function jsonlist()
    {
        $filter = new \mkwhelpers\FilterDescriptor();
        if ($this->params->getBoolRequestParam('_search', false)) {
            if (!is_null($this->params->getRequestParam('nev', null))) {
                $filter->addFilter('nev', 'LIKE', '%' . $this->params->getStringRequestParam('nev') . '%');
            }
        }
        $rec = $this->getRepo()->getAll($filter, $this->getOrderArray());
        echo json_encode($this->loadDataToView($rec));
    }

    public function getSelectList($selid = null)
    {
        $rec = $this->getRepo()->getAll([], ['sorrend' => 'ASC']);
        $res = [];
        foreach ($rec as $sor) {
            $res[] = ['id' => $sor->getId(), 'caption' => $sor->getNev(), 'selected' => ($sor->getId() == $selid)];
        }
        return $res;
    }

    public function htmllist()
    {
        $rec = $this->getRepo()->getAll([], ['sorrend' => 'asc']);
        $ret = '<select>';
        foreach ($rec as $sor) {
            $ret .= '<option value="' . $sor->getId() . '">' . $sor->getNev() . '</option>';
        }
        $ret .= '</select>';
        echo $ret;
    }
}