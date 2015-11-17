<?php

namespace Controllers;

use Entities\Termekcimketorzs;
use mkw\store;

class termekcimkeController extends \mkwhelpers\MattableController {

    public function __construct($params) {
        $this->setEntityName('Entities\Termekcimketorzs');
        $this->setKarbFormTplName('cimkekarbform.tpl');
        $this->setKarbTplName('cimkekarb.tpl');
        $this->setListBodyRowTplName('cimkelista_tbody_tr.tpl');
        $this->setListBodyRowVarName('_cimke');
        parent::__construct($params);
    }

    protected function loadVars($t) {
        $x = array();
        if (!$t) {
            $t = new \Entities\Termekcimketorzs();
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
        $x['menu1lathato'] = $t->getMenu1Lathato();
        $x['menu2lathato'] = $t->getMenu2Lathato();
        $x['menu3lathato'] = $t->getMenu3Lathato();
        $x['menu4lathato'] = $t->getMenu4Lathato();
        $x['kepurl'] = $t->getKepurl();
        $x['kepurlsmall'] = $t->getKepurlSmall();
        $x['kepurlmedium'] = $t->getKepurlMedium();
        $x['kepurllarge'] = $t->getKepurlLarge();
        $x['kepleiras'] = $t->getKepleiras();
        $x['sorrend'] = $t->getSorrend();
        $x['kiemelt'] = $t->getKiemelt();
        return $x;
    }

    protected function setFields($obj) {
        $ck = store::getEm()->getRepository('Entities\Termekcimkekat')->find($this->params->getIntRequestParam('cimkecsoport'));
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
        $obj->setKepurl($this->params->getStringRequestParam('kepurl', ''));
        $obj->setKepleiras($this->params->getStringRequestParam('kepleiras', ''));
        $obj->setSorrend($this->params->getIntRequestParam('sorrend'));
        $obj->setKiemelt($this->params->getBoolRequestParam('kiemelt'));
        return $obj;
    }

    public function getlistbody() {
        $view = $this->createView('cimkelista_tbody.tpl');
        $view->setVar('kellkep', true);

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

        $view->setVar('pagetitle', t('Termékcímkék'));
        $view->setVar('kellkep', true);
        $view->setVar('controllerscript', 'termekcimke.js');
        $view->setVar('orderselect', $this->getRepo()->getOrdersForTpl());
        $view->setVar('batchesselect', $this->getRepo()->getBatchesForTpl());
        $ckat = new termekcimkekatController($this->params);
        $view->setVar('cimkecsoportlist', $ckat->getSelectList(0));
        $view->printTemplateResult();
    }

    protected function _getkarb($tplname) {
        $id = $this->params->getRequestParam('id', 0);
        $oper = $this->params->getRequestParam('oper', '');
        $view = $this->createView($tplname);
        $view->setVar('pagetitle', t('Termékcímke'));
        $view->setVar('controllerscript', 'termekcimke.js');
        $view->setVar('kellkep', true);
        $view->setVar('formaction', '/admin/termekcimke/save');
        $view->setVar('oper', $oper);
        $record = $this->getRepo()->findWithJoins($id);
        $view->setVar('cimke', $this->loadVars($record));
        $ckat = new termekcimkekatController($this->params);
        $view->setVar('cimkecsoportlist', $ckat->getSelectList(($record ? $record->getKategoriaId() : 0)));
        return $view->getTemplateResult();
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
                case 5:
                    $obj->setKiemelt($kibe);
                    break;
            }
            $this->getEm()->persist($obj);
            $this->getEm()->flush();
        }
    }

    public function add() {
        $obj = new Termekcimketorzs();
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

        $view->setVar('pagetitle', t('Termékcímkék'));
        $view->setVar('kellkep', true);
        $view->setVar('controllerscript', 'termekcimke.js');
        $tc = store::getEm()->getRepository('Entities\Termekcimkekat')->getWithJoins(array(), array('_xx.nev' => 'asc', 'c.nev' => 'asc'));
        $view->setVar('cimkekat', $this->cimkekToArray($tc));
        $view->printTemplateResult();
    }

    public function getformenu($menu) {
        $tc = $this->getRepo('Entities\Termekcimkekat')->getAllHasTermek($menu);
        return $this->cimkekToArray($tc);
    }

    public function getKiemeltList() {
        $tc = $this->getRepo()->getKiemelt();
        $ret = array();
        foreach($tc as $cimke) {
            $ret[] = $cimke->toLista();
        }
        return $ret;
    }

    public function showMarkak() {
        $view = $this->getTemplateFactory()->createMainView('markak.tpl');
		store::fillTemplate($view);
        $tc = $this->getRepo()->getMarkak();
        $m = array();
        foreach($tc as $c) {
            $m[] = $c->toLista();
        }
        $view->setVar('markalista', $m);
        $view->printTemplateResult();
    }

}
