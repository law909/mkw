<?php

namespace Controllers;

use Entities\Partnercimkekat;
use mkw\store;

class partnercimkekatController extends \mkwhelpers\JQGridController
{

    public function __construct()
    {
        $this->setEntityName(Partnercimkekat::class);
        parent::__construct();
    }

    protected function loadCells($sor)
    {
        return [$sor->getNev(), $sor->getLathato()];
    }

    protected function setFields($obj)
    {
        $obj->setNev($this->params->getStringRequestParam('nev', $obj->getNev()));
        $obj->setLathato($this->params->getBoolRequestParam('lathato'));
        return $obj;
    }

    public function jsonlist()
    {
        $filter = new \mkwhelpers\FilterDescriptor();
        if ($this->params->getBoolRequestParam('_search', false)) {
            if (!is_null($this->params->getRequestParam('lathato', null))) {
                $filter->addFilter('lathato', '=', $this->params->getBoolRequestParam('lathato'));
            }
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
            $res[] = ['id' => $sor->getId(), 'caption' => $sor->getNev(), 'selected' => ($sor->getId() == $selid)];
        }
        return $res;
    }

    public function getWithCimkek($selected = null)
    {
        $cimkekat = $this->getRepo()->getWithJoins([], ['_xx.nev' => 'asc', 'c.nev' => 'asc']);
        $res = [];
        foreach ($cimkekat as $kat) {
            $adat = [];
            $cimkek = $kat->getCimkek();
            foreach ($cimkek as $cimke) {
                $adat[] = [
                    'id' => $cimke->getId(),
                    'caption' => $cimke->getNev(),
                    'selected' => ($selected && ($selected->contains($cimke)) ? true : false)
                ];
            }
            $res[] = [
                'id' => $kat->getId(),
                'caption' => $kat->getNev(),
                'sanitizedcaption' => str_replace('.', '', $kat->getSlug()),
                'cimkek' => $adat
            ];
        }
        return $res;
    }
}