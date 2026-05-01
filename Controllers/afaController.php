<?php

namespace Controllers;

use Entities\Afa;
use mkw\store;

class afaController extends \mkwhelpers\JQGridController
{

    public function __construct($params)
    {
        $this->setEntityName(Afa::class);
        parent::__construct($params);
    }

    /**
     * @param \Entities\Afa $sor
     *
     * @return mixed
     */
    protected function loadCells($sor)
    {
        return [$sor->getNev(), $sor->getErtek(), $sor->getNavcase(), $sor->getRLBkod()];
    }

    /**
     * @param \Entities\Afa $obj
     *
     * @return mixed
     */
    protected function setFields($obj)
    {
        $obj->setNev($this->params->getStringRequestParam('nev', $obj->getNev()));
        $obj->setErtek($this->params->getIntRequestParam('ertek', $obj->getErtek()));
        $obj->setRLBkod($this->params->getIntRequestParam('rlbkod', $obj->getRLBkod()));
        $obj->setNavcase($this->params->getStringRequestParam('navcase', $obj->getNavcase()));
        return $obj;
    }

    public function jsonlist()
    {
        $filter = new \mkwhelpers\FilterDescriptor();
        if ($this->params->getBoolRequestParam('_search', false)) {
            if (!is_null($this->params->getRequestParam('ertek', null))) {
                $filter->addFilter('ertek', '=', $this->params->getIntRequestParam('ertek'));
            }
            if (!is_null($this->params->getParam('nev', null))) {
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
                'afakulcs' => $sor->getErtek()
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

    public function navcaselist()
    {
        $cases = $this->getRepo()->getNavcaseList();
        $ret = '<select>';
        foreach ($cases as $case) {
            $ret .= '<option value="' . $case['id'] . '">' . $case['caption'] . '</option>';
        }
        $ret .= '</select>';
        echo $ret;
    }
}
