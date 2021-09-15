<?php

namespace Controllers;

use Entities\BizonylatDok;

class bizonylatdokController extends \mkwhelpers\MattableController {

    public function __construct($params) {
        $this->setEntityName(BizonylatDok::class);
//		$this->setKarbFormTplName('?howto?karbform.tpl');
//		$this->setKarbTplName('?howto?karb.tpl');
//		$this->setListBodyRowTplName('?howto?lista_tbody_tr.tpl');
//		$this->setListBodyRowVarName('_egyed');
        parent::__construct($params);
    }

    public function loadVars($t) {
        $x = array();
        if (!$t) {
            $t = new \Entities\BizonylatDok();
            $this->getEm()->detach($t);
            $x['oper'] = 'add';
            $x['id'] = \mkw\store::createUID();
        }
        else {
            $x['oper'] = 'edit';
            $x['id'] = $t->getId();
        }
        $x['url'] = $t->getUrl();
        $x['leiras'] = $t->getLeiras();
        $x['path'] = $t->getPath();
        return $x;
    }

    protected function setFields($obj) {
        $obj->setLeiras($this->params->getStringRequestParam('leiras'));
        $obj->setUrl(\mkw\store::addHttp($this->params->getStringRequestParam('url')));
        $obj->setPath($this->params->getBoolRequestParam('path'));
        return $obj;
    }

    public function getemptyrow() {
        $view = $this->createView('dokumentumtarkarb.tpl');
        $view->setVar('dok', $this->loadVars(null));
        echo $view->getTemplateResult();
    }

    public function getSelectList($termek, $selid) {
        $dokok = $this->getRepo()->getByTermek($termek);
        $doklista = array();
        foreach ($dokok as $dok) {
            $doklista[] = array('id' => $dok->getId(), 'caption' => $dok->getUrl(), 'selected' => $dok->getId() == $selid, 'url' => $dok->getUrl(), 'path' => $dok->getPath());
        }
        return $doklista;
    }

    public function del() {
        $dok = $this->getRepo()->find($this->params->getNumRequestParam('id'));
        if ($dok) {
            $this->getEm()->remove($dok);
            $this->getEm()->flush();
        }
        echo $this->params->getNumRequestParam('id');
    }

}
