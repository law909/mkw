<?php

namespace Controllers;

use Entities\MPTNGYEgyetem;
use Entities\MPTNGYKar;
use mkw\store;
use mkwhelpers\JQGridController;

class mptngykarController extends JQGridController
{
    public function __construct($params)
    {
        $this->setEntityName(MPTNGYKar::class);
        parent::__construct($params);
    }

    protected function loadCells($sor)
    {
        $egyetem = $sor->getEgyetem();
        return [
            $sor->getNev(),
            (isset($egyetem) ? $egyetem->getNev() : ''),
        ];
    }

    protected function setFields($obj)
    {
        $obj->setNev($this->params->getStringRequestParam('nev', $obj->getNev()));
        $csk = store::getEm()->getReference(MPTNGYEgyetem::class, $this->params->getIntRequestParam('egyetem', $obj->getEgyetemId()));
        if ($this->params->getIntRequestParam('egyetem', $obj->getEgyetemId()) && $csk) {
            $obj->setEgyetem($csk);
        }
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

    public function getSelectList($selid)
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

    public function getList()
    {
        $res = [];
        $egyetem = $this->params->getIntRequestParam('egyetem', 0);
        $filter = new \mkwhelpers\FilterDescriptor();
        if ($egyetem) {
            $filter->addFilter('egyetem', '=', $egyetem);
        }
        $rec = $this->getRepo()->getAll($filter, ['nev' => 'asc']);
        foreach ($rec as $sor) {
            $res[] = [
                'id' => $sor->getId(),
                'caption' => $sor->getNev(),
            ];
        }
        echo json_encode($res);
    }

}