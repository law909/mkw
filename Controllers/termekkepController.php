<?php

namespace Controllers;

class termekkepController extends \mkwhelpers\MattableController {

    public function __construct($params) {
        $this->setEntityName('Entities\TermekKep');
//		$this->setKarbFormTplName('?howto?karbform.tpl');
//		$this->setKarbTplName('?howto?karb.tpl');
//		$this->setListBodyRowTplName('?howto?lista_tbody_tr.tpl');
//		$this->setListBodyRowVarName('_egyed');
        parent::__construct($params);
    }

    public function loadVars($t) {
        $x = array();
        if (!$t) {
            $t = new \Entities\TermekKep();
            $this->getEm()->detach($t);
            $x['oper'] = 'add';
            $x['id'] = \mkw\store::createUID();
        }
        else {
            $x['oper'] = 'edit';
            $x['id'] = $t->getId();
        }
        $x['url'] = $t->getUrl();
        $x['urlsmall'] = $t->getUrlSmall();
        $x['urlmedium'] = $t->getUrlMedium();
        $x['urllarge'] = $t->getUrlLarge();
        $x['leiras'] = $t->getLeiras();
        return $x;
    }

    protected function setFields($obj) {
        $obj->setLeiras($this->params->getStringRequestParam('leiras'));
        $obj->setUrl($this->params->getStringRequestParam('url'));
        return $obj;
    }

    public function getemptyrow() {
        $view = $this->createView('termektermekkepkarb.tpl');
        $view->setVar('kep', $this->loadVars(null));
        echo $view->getTemplateResult();
    }

    public function getSelectList($termek, $selid) {
        $kepek = $this->getRepo()->getByTermek($termek);
        $keplista = array();
        foreach ($kepek as $kep) {
            $keplista[] = array('id' => $kep->getId(), 'caption' => $kep->getUrl(), 'selected' => $kep->getId() == $selid, 'url' => $kep->getUrlMini());
        }
        return $keplista;
    }

    public function del() {
        $mainpath = \mkw\store::changeDirSeparator(\mkw\store::getConfigValue('mainpath'));
        if ($mainpath) {
            $mainpath = rtrim($mainpath, DIRECTORY_SEPARATOR) . DIRECTORY_SEPARATOR;
        }
        $kep = $this->getRepo()->find($this->params->getNumRequestParam('id'));
        if ($kep) {
            /* 			unlink($mainpath . $kep->getUrl(''));
              unlink($mainpath . $kep->getUrlMini(''));
              unlink($mainpath . $kep->getUrlSmall(''));
              unlink($mainpath . $kep->getUrlMedium(''));
              unlink($mainpath . $kep->getUrlLarge(''));
             */
            $this->getEm()->remove($kep);
            $this->getEm()->flush();
        }
        echo $this->params->getNumRequestParam('id');
    }

}
