<?php

namespace Controllers;

use Entities\Bankszamla;
use Entities\Valutanem;
use mkw\store;

class bankszamlaController extends \mkwhelpers\JQGridController
{

    public function __construct()
    {
        $this->setEntityName(Bankszamla::class);
        parent::__construct();
    }

    protected function loadCells($sor)
    {
        $valuta = $sor->getValutanem();
        return [
            $sor->getBanknev(),
            $sor->getBankcim(),
            $sor->getSzamlaszam(),
            $sor->getSwift(),
            $sor->getIban(),
            (isset($valuta) ? $valuta->getNev() : '')
        ];
    }

    protected function setFields($obj)
    {
        $obj = $this->setEntityFieldsFromRequest($obj);
        $valutanem = $this->getRepo(Valutanem::class)->find($this->params->getIntRequestParam('valutanem', 0));
        if ($valutanem) {
            $obj->setValutanem($valutanem);
        } else {
            $obj->setValutanem(null);
        }
        return $obj;
    }

    public function jsonlist()
    {
        $filter = new \mkwhelpers\FilterDescriptor();
        if ($this->params->getBoolRequestParam('_search', false)) {
            if (!is_null($this->params->getRequestParam('banknev', null))) {
                $filter->addFilter('banknev', 'LIKE', '%' . $this->params->getStringRequestParam('banknev') . '%');
            }
            if (!is_null($this->params->getStringRequestParam('bankcim', null))) {
                $filter->addFilter('bankcim', 'LIKE', '%' . $this->params->getStringRequestParam('bankcim') . '%');
            }
            if (!is_null($this->params->getStringRequestParam('szamlaszam', null))) {
                $filter->addFilter('szamlaszam', 'LIKE', '%' . $this->params->getStringRequestParam('szamlaszam') . '%');
            }
            if (!is_null($this->params->getStringRequestParam('swift', null))) {
                $filter->addFilter('swift', 'LIKE', '%' . $this->params->getStringRequestParam('swift') . '%');
            }
            if (!is_null($this->params->getStringRequestParam('iban', null))) {
                $filter->addFilter('iban', 'LIKE', '%' . $this->params->getStringRequestParam('iban') . '%');
            }
            if (!is_null($this->params->getStringRequestParam('valutanem', null))) {
                $filter->addFilter('v.nev', 'LIKE', '%' . $this->params->getStringRequestParam('valutanem') . '%');
            }
        }
        $rec = $this->getRepo()->getAll($filter, $this->getOrderArray());
        echo json_encode($this->loadDataToView($rec));
    }

    public function getSelectList($selid = null)
    {
        $rec = $this->getRepo()->getAll([], ['szamlaszam' => 'ASC']);
        $res = [];
        foreach ($rec as $sor) {
            $res[] = ['id' => $sor->getId(), 'caption' => $sor->getSzamlaszam(), 'selected' => ($sor->getId() == $selid)];
        }
        return $res;
    }

    public function htmllist()
    {
        $rec = $this->getRepo()->getAll([], ['szamlaszam' => 'ASC']);
        $ret = '<select>';
        foreach ($rec as $sor) {
            $ret .= '<option value="' . $sor->getId() . '">' . $sor->getSzamlaszam() . '</option>';
        }
        $ret .= '</select>';
        echo $ret;
    }

}
