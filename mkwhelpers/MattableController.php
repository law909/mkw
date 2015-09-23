<?php

namespace mkwhelpers;

class MattableController extends Controller {

    protected $operationName = 'oper';
    protected $idName = 'id';
    protected $addOperation = 'add';
    protected $addreopenOperation = 'addreopen';
    protected $editOperation = 'edit';
    protected $delOperation = 'del';
    protected $inheritOperation = 'inherit';
    protected $stornoOperation = 'storno';
    private $pager;
    private $listBodyRowTplName;
    private $listBodyRowVarName;
    private $karbFormTplName;
    private $karbTplName;
    private $pagetitle;
    private $pluralpagetitle;

    public function __construct($params) {
        parent::__construct($params);
        $this->setTemplateFactory(\mkw\Store::getTemplateFactory());
    }

    public function setPageTitle($val) {
        $this->pagetitle = $val;
    }

    public function getPageTitle() {
        return $this->pagetitle;
    }

    public function setPluralPageTitle($val) {
        $this->pluralpagetitle = $val;
    }

    public function getPluralPageTitle() {
        return $this->pluralpagetitle;
    }

    protected function getPager() {
        return $this->pager;
    }

    public function getListBodyRowTplName() {
        return $this->listBodyRowTplName;
    }

    public function setListBodyRowTplName($name) {
        $this->listBodyRowTplName = $name;
    }

    public function getListBodyRowVarName() {
        return $this->listBodyRowVarName;
    }

    public function setListBodyRowVarName($name) {
        $this->listBodyRowVarName = $name;
    }

    public function getKarbFormTplName() {
        return $this->karbFormTplName;
    }

    public function setKarbFormTplName($name) {
        $this->karbFormTplName = $name;
    }

    public function getKarbTplName() {
        return $this->karbTplName;
    }

    public function setKarbTplName($name) {
        $this->karbTplName = $name;
    }

    protected function setVars($view) {

    }

    protected function beforeRemove($o) {

    }

    protected function afterSave($o) {

    }

    protected function saveData() {
        $parancs = $this->params->getRequestParam($this->operationName, '');
        $id = $this->params->getRequestParam($this->idName, 0);
        try {
            switch ($parancs) {
                case $this->addOperation:
                case $this->addreopenOperation:
                case $this->inheritOperation:
                case $this->stornoOperation:
                    $cl = $this->entityName;
                    $obj = new $cl();
                    $this->getEm()->persist($this->setFields($obj, $parancs));
                    $this->getEm()->flush();
                    $this->afterSave($obj);
                    break;
                case $this->editOperation:
                    $obj = $this->getRepo()->find($id);
                    $this->getEm()->persist($this->setFields($obj, $parancs));
                    $this->getEm()->flush();
                    $this->afterSave($obj);
                    break;
                case $this->delOperation:
                    $obj = $this->getRepo()->find($id);
                    if ($obj) {
                        $this->beforeRemove($obj);
                        $this->getEm()->remove($obj);
                        $this->getEm()->flush();
                        $this->afterSave($obj);
                    }
                    break;
            }
            return array('id' => $id, 'obj' => $obj, 'operation' => $parancs);
        }
        catch (Exception $e) {
            throw $e;
        }
    }

    public function save() {
        try {
            $ret = $this->saveData();
            switch ($ret['operation']) {
                case $this->addOperation:
                case $this->addreopenOperation:
                case $this->editOperation:
                case $this->inheritOperation:
                    echo json_encode($this->getListBodyRow($ret['obj'], $ret['operation']));
                    break;
                case $this->stornoOperation:
                    break;
                case $this->delOperation:
                    echo $ret['id'];
            }
        }
        catch (Exception $ex)
        {
//            echo json_encode(array('error' => $ex->getMessage()));
        }
    }

    protected function getOrderArray() {
        //TODO SQLINJECTION
        return $this->getRepo()->getOrder($this->params->getRequestParam('order', 1));
    }

    protected function initPager($elemcount, $elemperpage = null, $pageno = null) {
        if (!$elemperpage) {
            $elemperpage = $this->params->getIntRequestParam('elemperpage', \mkw\Store::getParameter(\mkw\consts::Termeklistatermekdb, 30));
        }
        if (!$pageno) {
            $pageno = $this->params->getIntRequestParam('pageno', 1);
        }
        $this->pager = new PagerCalc($elemcount, $elemperpage, $pageno);
    }

    protected function loadPagerValues($ide) {
        if ($this->pager) {
            return $this->pager->loadValues($ide);
        }
        return $ide;
    }

    protected function loadDataToView($data, $datavarname = '', $view = null) {
        $vl = array();
        foreach ($data as $t) {
            $vl[] = $this->loadVars($t);
        }
        $view->setVar($datavarname, $vl);
        $result = array();
        $result['html'] = $view->getTemplateResult();
        $result = $this->loadPagerValues($result);
        return $result;
    }

    protected function getListBodyRow($obj, $oper) {
        $view = $this->createView($this->listBodyRowTplName);
        $this->setVars($view);
        $vl = $this->loadVars($obj);
        $view->setVar($this->listBodyRowVarName, $vl);
        $result = array();
        if (is_object($obj)) {
            $result['id'] = $obj->getId();
        }
        else {
            $result['id'] = $obj['id'];
        }
        $result['oper'] = $oper;
        $result['html'] = $view->getTemplateResult();
        return $result;
    }

    public function getkarb() {
        echo $this->_getkarb($this->karbFormTplName);
    }

    public function viewkarb() {
        echo $this->_getkarb($this->karbTplName);
    }

}
