<?php

namespace mkwhelpers;

class JQGridController extends Controller {

    protected $operationName = 'oper';
    protected $idName = 'id';
    protected $addOperation = 'add';
    protected $editOperation = 'edit';
    protected $delOperation = 'del';

    protected function getOrderArray() {
        // TODO SQLINJECTION
        $order = array();
        $order[$this->params->getRequestParam('sidx', 'nev')] = $this->params->getRequestParam('sord', 'ASC');
        return $order;
    }

    protected function loadDataToView($data) {
        $res = new \stdClass();
        $res->page = 1;
        $res->total = 1;
        $res->records = count($data);
        $i = 0;
        foreach ($data as $sor) {
            $res->rows[$i]['id'] = $sor->getId();
            $res->rows[$i]['cell'] = $this->loadCells($sor);
            $i++;
        }
        return $res;
    }

    public function save() {
        $parancs = $this->params->getRequestParam($this->operationName, '');
        $id = $this->params->getRequestParam($this->idName, 0);
        switch ($parancs) {
            case $this->addOperation:
                $cl = $this->entityName;
                $obj = new $cl();
                $this->getEm()->persist($this->setFields($obj));
                $this->getEm()->flush();
                break;
            case $this->editOperation:
                $obj = $this->getRepo()->find($id);
                $this->getEm()->persist($this->setFields($obj));
                $this->getEm()->flush();
                break;
            case $this->delOperation:
                $obj = $this->getRepo()->find($id);
                $this->getEm()->remove($obj);
                $this->getEm()->flush();
                break;
        }
    }

}
