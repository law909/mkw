<?php

namespace Controllers;

use mkw\store;

class mpttagozatController extends \mkwhelpers\JQGridController
{

    public function __construct()
    {
        $this->setEntityName(\Entities\MPTTagozat::class);
        parent::__construct();
    }

    protected function loadCells($obj)
    {
        return [$obj->getNev()];
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
        $rec = $this->getRepo()->getAll([], ['nev' => 'ASC']);
        $res = [];
        foreach ($rec as $sor) {
            $res[] = ['id' => $sor->getId(), 'caption' => $sor->getNev(), 'selected' => ($sor->getId() == $selid)];
        }
        return $res;
    }

    public function htmllist()
    {
        $rec = $this->getRepo()->getAll([], ['nev' => 'asc']);
        $ret = '<select>';
        foreach ($rec as $sor) {
            $ret .= '<option value="' . $sor->getId() . '">' . $sor->getNev() . '</option>';
        }
        $ret .= '</select>';
        echo $ret;
    }
}