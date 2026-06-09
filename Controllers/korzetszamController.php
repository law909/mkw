<?php

namespace Controllers;

use Entities\Korzetszam;
use mkw\store;

class korzetszamController extends \mkwhelpers\JQGridController
{

    public function __construct()
    {
        $this->setEntityName(Korzetszam::class);
        parent::__construct();
    }

    /**
     * @param \Entities\Korzetszam $sor
     *
     * @return mixed
     */
    protected function loadCells($sor)
    {
        return [$sor->getId(), $sor->getHossz(), $sor->getSorrend()];
    }

    /**
     * @param \Entities\Korzetszam $obj
     *
     * @return mixed
     */
    protected function setFields($obj)
    {
        $obj->setId($this->params->getStringRequestParam('id', $obj->getId()));
        $obj->setHossz($this->params->getIntRequestParam('hossz', $obj->getHossz()));
        $obj->setSorrend($this->params->getIntRequestParam('sorrend', $obj->getSorrend()));
        return $obj;
    }

    public function jsonlist()
    {
        $filter = new \mkwhelpers\FilterDescriptor();
        if ($this->params->getBoolRequestParam('_search', false)) {
            if (!is_null($this->params->getRequestParam('id', null))) {
                $filter->addFilter('id', '=', $this->params->getIntRequestParam('id'));
            }
        }
        $rec = $this->getRepo()->getAll($filter, $this->getOrderArray());
        echo json_encode($this->loadDataToView($rec));
    }

    public function getSelectList($selid)
    {
        $rec = $this->getRepo()->getAll([], ['sorrend' => 'ASC', 'id' => 'ASC']);
        $res = [];
        foreach ($rec as $sor) {
            $res[] = [
                'id' => $sor->getId(),
                'caption' => $sor->getId(),
                'selected' => ($sor->getId() == $selid),
                'hossz' => $sor->getHossz()
            ];
        }
        return $res;
    }

    public function htmllist()
    {
        $rec = $this->getRepo()->getAll([], ['sorrend' => 'ASC', 'id' => 'ASC']);
        $ret = '<select>';
        foreach ($rec as $sor) {
            $ret .= '<option value="' . $sor->getId() . '" data-hossz="' . $sor->getHossz() . '">' . $sor->getId() . '</option>';
        }
        $ret .= '</select>';
        echo $ret;
    }

}
