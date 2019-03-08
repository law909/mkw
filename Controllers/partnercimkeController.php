<?php

namespace Controllers;

use Entities\Partnercimketorzs;
use mkw\store;

class partnercimkeController extends \mkwhelpers\MattableController {

    public function __construct($params) {
        $this->setEntityName('Entities\Partnercimketorzs');
        $this->setKarbFormTplName('cimkekarbform.tpl');
        $this->setKarbTplName('cimkekarb.tpl');
        $this->setListBodyRowTplName('cimkelista_tbody_tr.tpl');
        $this->setListBodyRowVarName('_cimke');
        parent::__construct($params);
    }

    protected function loadVars($t) {
        $x = array();
        if (!$t) {
            $t = new \Entities\Partnercimketorzs();
            $this->getEm()->detach($t);
        }
        $x['id'] = $t->getId();
        $x['nev'] = $t->getNev();
        $x['leiras'] = $t->getLeiras();
        $x['oldalcim'] = $t->getOldalcim();
        if ($kat = $t->getKategoria()) {
            $x['cimkekatnev'] = $kat->getNev();
        }
        else {
            $x['cimkekatnev'] = '';
        }
        $x['menu1lathato'] = $t->getMenu1lathato();
        $x['menu2lathato'] = $t->getMenu2lathato();
        $x['menu3lathato'] = $t->getMenu3lathato();
        $x['menu4lathato'] = $t->getMenu4lathato();
        return $x;
    }

    protected function setFields($obj) {
        $ck = store::getEm()->getRepository('Entities\Partnercimkekat')->find($this->params->getIntRequestParam('cimkecsoport', 0));
        if ($ck) {
            $obj->setKategoria($ck);
        }
        $obj->setNev($this->params->getStringRequestParam('nev'));
        $obj->setLeiras($this->params->getOriginalStringRequestParam('leiras'));
        $obj->setOldalcim($this->params->getStringRequestParam('oldalcim'));
        $obj->setMenu1Lathato($this->params->getBoolRequestParam('menu1lathato'));
        $obj->setMenu2Lathato($this->params->getBoolRequestParam('menu2lathato'));
        $obj->setMenu3Lathato($this->params->getBoolRequestParam('menu3lathato'));
        $obj->setMenu4Lathato($this->params->getBoolRequestParam('menu4lathato'));
        return $obj;
    }

    public function getlistbody() {
        $view = $this->createView('cimkelista_tbody.tpl');
        $view->setVar('cimketipus', 'partner');

        $filter = new \mkwhelpers\FilterDescriptor();
        if (!is_null($this->params->getRequestParam('nevfilter', NULL))) {
            $filter->addFilter('nev', 'LIKE', '%' . $this->params->getStringRequestParam('nevfilter') . '%');
        }
        $fv = $this->params->getIntRequestParam('ckfilter');
        if ($fv > 0) {
            $filter->addFilter('ck.id', '=', $fv);
        }

        $this->initPager($this->getRepo()->getCount($filter));

        $egyedek = $this->getRepo()->getWithJoins(
            $filter, $this->getOrderArray(), $this->getPager()->getOffset(), $this->getPager()->getElemPerPage());

        echo json_encode($this->loadDataToView($egyedek, 'cimkelista', $view));
    }

    public function viewlist() {
        $view = $this->createView('cimkelista.tpl');

        $view->setVar('pagetitle', t('Partnercímkék'));
        $view->setVar('cimketipus', 'partner');
        $view->setVar('controllerscript', 'partnercimke.js');
        $view->setVar('orderselect', $this->getRepo()->getOrdersForTpl());
        $view->setVar('batchesselect', $this->getRepo()->getBatchesForTpl());
        $ckat = new partnercimkekatController($this->params);
        $view->setVar('cimkecsoportlist', $ckat->getSelectList(0));
        $view->printTemplateResult();
    }

    protected function _getkarb($tplname) {
        $id = $this->params->getRequestParam('id', 0);
        $oper = $this->params->getRequestParam('oper', '');
        $view = $this->createView($tplname);

        $view->setVar('pagetitle', t('Partnercímke'));
        $view->setVar('controllerscript', 'partnercimke.js');
        $view->setVar('cimketipus', 'partner');
        $view->setVar('formaction', '/admin/partnercimke/save');
        $view->setVar('oper', $oper);
        $record = $this->getRepo()->findWithJoins($id);
        $view->setVar('cimke', $this->loadVars($record));
        $ckat = new partnercimkekatController($this->params);
        $view->setVar('cimkecsoportlist', $ckat->getSelectList(($record ? $record->getKategoriaId() : 0)));
        return $view->getTemplateResult();
    }

    protected function beforeRemove($o) {
        $r = $o->getPartnerek();
        echo count($r);
        $o->getPartnerek()->clear();
        $r = $o->getPartnerek();
        echo count($r);
        $this->getEm()->persist($o);
        $this->getEm()->flush();
    }

    public function setmenulathato() {
        $id = $this->params->getIntRequestParam('id');
        $kibe = $this->params->getBoolRequestParam('kibe');
        $num = $this->params->getIntRequestParam('num');
        $obj = $this->getRepo()->find($id);
        if ($obj) {
            switch ($num) {
                case 1:
                    $obj->setMenu1Lathato($kibe);
                    break;
                case 2:
                    $obj->setMenu2Lathato($kibe);
                    break;
                case 3:
                    $obj->setMenu3Lathato($kibe);
                    break;
                case 4:
                    $obj->setMenu4Lathato($kibe);
                    break;
            }
            store::getEm()->persist($obj);
            store::getEm()->flush();
        }
    }

    public function add() {
        $obj = new Partnercimketorzs();
        $this->setFields($obj);
        $this->getEm()->persist($obj);
        $this->getEm()->flush();
        $view = $this->createView('cimkeselector.tpl');
        $view->setVar('_cimke', array('id' => $obj->getId(), 'caption' => $obj->getNev(), 'selected' => false));
        echo $view->getTemplateResult();
    }

    private function cimkekToArray($cimkekat) {
        $res = array();
        foreach ($cimkekat as $kat) {
            $adat = array();
            $cimkek = $kat->getCimkek();
            foreach ($cimkek as $cimke) {
                $adat[] = array(
                    'id' => $cimke->getId(),
                    'caption' => $cimke->getNev(),
                    'slug' => $cimke->getSlug()
                );
            }
            $res[] = array(
                'id' => $kat->getId(),
                'caption' => $kat->getNev(),
                'sanitizedcaption' => $kat->getSlug(),
                'cimkek' => $adat
            );
        }
        return $res;
    }

    public function viewselect() {
        $view = $this->createView('cimkelista.tpl');

        $view->setVar('pagetitle', t('Partnercímkék'));
        $view->setVar('cimketipus', 'partner');
        $view->setVar('controllerscript', 'partnercimke.js');
        $tc = store::getEm()->getRepository('Entities\Partnercimkekat')->getWithJoins(array(), array('_xx.nev' => 'asc', 'c.nev' => 'asc'));
        $view->setVar('cimkekat', $this->cimkekToArray($tc));
        $view->printTemplateResult();
    }

    public function getSelectList($selid) {
        $rec = $this->getRepo()->getAll(array(), array('nev' => 'ASC'));
        $res = array();
        foreach ($rec as $sor) {
            $res[] = array('id' => $sor->getId(), 'caption' => $sor->getNev(), 'selected' => ($sor->getId() == $selid));
        }
        return $res;
    }

}
