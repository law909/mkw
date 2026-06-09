<?php

namespace Controllers;

use Entities\Penztar;
use mkw\store;

class penztarController extends \mkwhelpers\JQGridController
{

    public function __construct()
    {
        $this->setEntityName(Penztar::class);
        parent::__construct();
    }

    protected function loadCells($sor)
    {
        $valuta = $sor->getValutanem();
        return [$sor->getNev(), (isset($valuta) ? $valuta->getNev() : '')];
    }

    protected function setFields($obj)
    {
        $obj->setNev($this->params->getStringRequestParam('nev'));
        $valutanem = $this->getRepo('Entities\Valutanem')->find($this->params->getIntRequestParam('valutanem', 0));
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
            if (!is_null($this->params->getRequestParam('nev', null))) {
                $filter->addFilter('nev', 'LIKE', '%' . $this->params->getStringRequestParam('nev') . '%');
            }
            if (!is_null($this->getParam('valutanem', null))) {
                $filter->addFilter('v.nev', 'LIKE', '%' . $this->params->getStringRequestParam('valutanem') . '%');
            }
        }
        $rec = $this->getRepo()->getAll($filter, $this->getOrderArray());
        echo json_encode($this->loadDataToView($rec));
    }

    public function getSelectList($selid = null)
    {
        $rec = $this->getRepo()->getAll([], ['nev' => 'ASC']);
        $res = [];
        /** @var \Entities\Penztar $sor */
        foreach ($rec as $sor) {
            $res[] = ['id' => $sor->getId(), 'caption' => $sor->getNev(), 'selected' => ($sor->getId() == $selid), 'valutanem' => $sor->getValutanemId()];
        }
        return $res;
    }

    public function htmllist()
    {
        $rec = $this->getRepo()->getAll([], ['nev' => 'ASC']);
        $ret = '<select>';
        foreach ($rec as $sor) {
            $ret .= '<option value="' . $sor->getId() . '">' . $sor->getNev() . '</option>';
        }
        $ret .= '</select>';
        echo $ret;
    }

}
