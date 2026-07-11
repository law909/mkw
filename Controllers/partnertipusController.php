<?php

namespace Controllers;

use Entities\Partnertipus;

class partnertipusController extends \mkwhelpers\JQGridController
{

    public function __construct()
    {
        $this->setEntityName(Partnertipus::class);
        parent::__construct();
    }

    protected function loadCells($sor)
    {
        return [
            $sor->getNev(),
            $sor->getBelephet(),
            $sor->getBelephet2(),
            $sor->getBelephet3(),
            $sor->getBelephet4(),
            $sor->getBelephet5(),
            $sor->getBelephet6(),
            $sor->getBelephet7(),
            $sor->getBelephet8(),
            $sor->getBelephet9(),
            $sor->getBelephet10(),
            $sor->getBelephet11(),
            $sor->getBelephet12(),
            $sor->getBelephet13(),
            $sor->getBelephet14(),
            $sor->getBelephet15()
        ];
    }

    protected function setFields($obj)
    {
        return $this->setEntityFieldsFromRequest($obj);
    }

    public function jsonlist()
    {
        $filter = new \mkwhelpers\FilterDescriptor();
        if ($this->params->getBoolRequestParam('_search', false)) {
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
                'selected' => ($sor->getId() == $selid)
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

}
