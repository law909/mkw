<?php

namespace Controllers;

use mkw\store;

class vtszController extends \mkwhelpers\JQGridController
{

    public function __construct($params)
    {
        $this->setEntityName('Entities\Vtsz');
        parent::__construct($params);
    }

    protected function loadCells($sor)
    {
        $afa = $sor->getAfa();
        $csk = $sor->getCsk();
        $kt = $sor->getKt();
        return [
            $sor->getSzam(),
            $sor->getNev(),
            (isset($afa) ? $afa->getNev() : ''),
            (isset($csk) ? $csk->getNev() : ''),
            (isset($kt) ? $kt->getNev() : '')
        ];
    }

    protected function setFields($obj)
    {
        $obj->setSzam($this->params->getStringRequestParam('szam', $obj->getSzam()));
        $obj->setNev($this->params->getStringRequestParam('nev', $obj->getNev()));
        $afa = store::getEm()->getReference('Entities\Afa', $this->params->getIntRequestParam('afa', $obj->getAfa()));
        $obj->setAfa($afa);
        $csk = store::getEm()->getReference('Entities\Csk', $this->params->getIntRequestParam('csk', $obj->getCsk()));
        if ($this->params->getIntRequestParam('csk', $obj->getCsk()) && $csk) {
            $obj->setCsk($csk);
        }
        $kt = store::getEm()->getReference('Entities\Csk', $this->params->getIntRequestParam('kt', $obj->getKt()));
        if ($this->params->getIntRequestParam('kt', $obj->getKt()) && $kt) {
            $obj->setKt($kt);
        }
        return $obj;
    }

    public function jsonlist()
    {
        $filter = new \mkwhelpers\FilterDescriptor();
        if ($this->params->getBoolRequestParam('_search', false)) {
            if (!is_null($this->params->getRequestParam('afa', null))) {
                $filter->addFilter('a.nev', 'LIKE', '%' . $this->params->getStringRequestParam('afa') . '%');
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
        // TODO ezen gyorsitani kell, getAll helyett ScalarResult
        $rec = $this->getRepo()->getAll([], ['szam' => 'ASC', 'nev' => 'ASC']);
        $res = [];
        foreach ($rec as $sor) {
            $res[] = [
                'id' => $sor->getId(),
                'caption' => $sor->getSzam() . ' ' . $sor->getNev(),
                'selected' => ($sor->getId() == $selid),
                'afa' => $sor->getAfaId(),
                'csk' => $sor->getCskId(),
                'kt' => $sor->getKtId()
            ];
        }
        return $res;
    }

}
