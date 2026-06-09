<?php

namespace Controllers;

use Entities\Raktar;
use mkw\store;

class raktarController extends \mkwhelpers\JQGridController
{

    public function __construct()
    {
        $this->setEntityName(Raktar::class);
        parent::__construct();
    }

    /**
     * @param \Entities\Raktar $obj
     *
     * @return mixed
     */
    protected function loadCells($obj)
    {
        return [$obj->getNev(), $obj->getMozgat(), $obj->getArchiv(), $obj->getIdegenkod()];
    }

    /**
     * @param \Entities\Raktar $obj
     *
     * @return mixed
     */
    protected function setFields($obj)
    {
        $obj->setNev($this->params->getStringRequestParam('nev', $obj->getNev()));
        $obj->setMozgat($this->params->getBoolRequestParam('mozgat'));
        $obj->setArchiv($this->params->getBoolRequestParam('archiv'));
        $obj->setIdegenkod($this->params->getStringRequestParam('idegenkod', $obj->getIdegenkod()));
        return $obj;
    }

    public function jsonlist()
    {
        $filter = new \mkwhelpers\FilterDescriptor();
        if ($this->params->getBoolRequestParam('_search', false)) {
            $filter->addFilter('nev', 'LIKE', '%' . $this->params->getStringRequestParam('nev') . '%');
        }
        $rec = $this->getRepo()->getAll($filter, $this->getOrderArray());
        echo json_encode($this->loadDataToView($rec));
    }

    public function getSelectList($selid = null)
    {
        $rec = $this->getRepo()->getAll([], ['nev' => 'ASC']);
        $res = [];
        foreach ($rec as $sor) {
            $res[] = ['id' => $sor->getId(), 'caption' => $sor->getNev(), 'selected' => ($sor->getId() == $selid)];
        }
        return $res;
    }

}