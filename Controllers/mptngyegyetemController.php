<?php

namespace Controllers;

use Entities\MPTNGYEgyetem;
use mkwhelpers\JQGridController;

class mptngyegyetemController extends JQGridController
{
    public function __construct($params)
    {
        $this->setEntityName(MPTNGYEgyetem::class);
        parent::__construct($params);
    }

    /**
     * @param MPTNGYEgyetem $sor
     *
     * @return array
     */
    protected function loadCells($sor)
    {
        return [
            $sor->getNev(),
        ];
    }

    protected function setFields($obj)
    {
        $obj->setNev($this->params->getStringRequestParam('nev', $obj->getNev()));
        return $obj;
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
            $res[] = [
                'id' => $sor->getId(),
                'caption' => $sor->getNev(),
                'selected' => ($sor->getId() == $selid),
            ];
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

    public function getList()
    {
        $res = [];
        $rec = $this->getRepo()->getAll([], ['nev' => 'asc']);
        foreach ($rec as $sor) {
            $res[] = [
                'id' => $sor->getId(),
                'caption' => $sor->getNev(),
            ];
        }
        echo json_encode($res);
    }
}