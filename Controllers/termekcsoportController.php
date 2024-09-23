<?php

namespace Controllers;


class termekcsoportController extends \mkwhelpers\JQGridController
{

    public function __construct($params)
    {
        $this->setEntityName('Entities\Termekcsoport');
        parent::__construct($params);
    }

    /**
     * @param \Entities\Termekcsoport $obj
     *
     * @return array
     */
    protected function loadCells($obj)
    {
        return [$obj->getNev()];
    }

    /**
     * @param \Entities\Termekcsoport $obj
     *
     * @return \Entities\Termekcsoport
     */
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

}