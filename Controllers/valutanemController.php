<?php

namespace Controllers;

use Entities\Valutanem;
use mkw\store;

class valutanemController extends \mkwhelpers\JQGridController
{

    public function __construct()
    {
        $this->setEntityName(Valutanem::class);
        parent::__construct();
    }

    protected function loadCells($sor)
    {
        $bank = $sor->getBankszamla();
        return [
            $sor->getNev(),
            $sor->getKerekit(),
            $sor->getHivatalos(),
            $sor->getMincimlet(),
            (isset($bank) ? $bank->getSzamlaszam() : '')
        ];
    }

    protected function setFields($obj)
    {
        $obj->setNev($this->params->getStringRequestParam('nev'));
        $obj->setKerekit($this->params->getBoolRequestParam('kerekit'));
        $obj->setHivatalos($this->params->getBoolRequestParam('hivatalos'));
        $obj->setMincimlet($this->params->getIntRequestParam('mincimlet'));
        $bankszla = $this->getRepo('Entities\Bankszamla')->find($this->params->getIntRequestParam('bankszamla'));
        if ($bankszla) {
            $obj->setBankszamla($bankszla);
        } else {
            $obj->setBankszamla(null);
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

    public function getSelectList($selid = null)
    {
        $rec = $this->getRepo()->getAll([], ['nev' => 'ASC']);
        $res = [];
        foreach ($rec as $sor) {
            $res[] = [
                'id' => $sor->getId(),
                'caption' => $sor->getNev(),
                'selected' => ($sor->getId() == $selid),
                'bankszamla' => $sor->getBankszamlaId()
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

    public function getRendszerValuta()
    {
        $p = $this->getRepo()->find(store::getParameter(\mkw\consts::Valutanem));
        return $p;
    }
}