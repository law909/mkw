<?php

namespace Controllers;

use mkw\store;

class szallitasimodController extends \mkwhelpers\MattableController {

    public function __construct($params) {
        $this->setEntityName('Entities\Szallitasimod');
        $this->setKarbFormTplName('szallitasimodkarbform.tpl');
        $this->setKarbTplName('szallitasimodkarb.tpl');
        $this->setListBodyRowTplName('szallitasimodlista_tbody_tr.tpl');
        $this->setListBodyRowVarName('_egyed');
        parent::__construct($params);
    }

    protected function loadVars($t) {
        $x=array();
        if (!$t) {
            $t = new \Entities\Szallitasimod();
            $this->getEm()->detach($t);
        }
        $x['id'] = $t->getId();
        $x['nev'] = $t->getNev();
        $x['webes'] = $t->getWebes();
        $x['leiras'] = $t->getLeiras();
        $x['fizmodok'] = $t->getFizmodok();
        $x['sorrend'] = $t->getSorrend();
        $x['vanszallitasiktg'] = $t->getVanszallitasiktg();
        return $x;
    }

    protected function setFields($obj) {
        $obj->setNev($this->params->getStringRequestParam('nev', $obj->getNev()));
        $obj->setWebes($this->params->getBoolRequestParam('webes'));
        $obj->setLeiras($this->params->getStringRequestParam('leiras'));
        $obj->setFizmodok($this->params->getStringRequestParam('fizmodok'));
        $obj->setSorrend($this->params->getIntRequestParam('sorrend'));
        $obj->setVanszallitasiktg($this->params->getBoolRequestParam('vanszallitasiktg'));
        return $obj;
    }

    public function getlistbody() {
        $view = $this->createView('szallitasimodlista_tbody.tpl');

        $filter = array();
        if (!is_null($this->params->getRequestParam('nevfilter', NULL))) {
            $filter['fields'][] = 'nev';
            $filter['values'][] = $this->params->getStringRequestParam('nevfilter');
        }

        $this->initPager(
            $this->getRepo()->getCount($filter),
            $this->params->getIntRequestParam('elemperpage', 30),
            $this->params->getIntRequestParam('pageno', 1));

        $egyedek = $this->getRepo()->getAll(
            $filter,
            $this->getOrderArray(),
            $this->getPager()->getOffset(),
            $this->getPager()->getElemPerPage());

        echo json_encode($this->loadDataToView($egyedek, 'egyedlista', $view));
    }

    public function viewlist() {
        $view = $this->createView('szallitasimodlista.tpl');

        $view->setVar('pagetitle', t('Szállítási módok'));
        $view->setVar('orderselect', $this->getRepo()->getOrdersForTpl());
        $view->setVar('batchesselect', $this->getRepo()->getBatchesForTpl());
        $view->printTemplateResult();
    }

    protected function _getkarb($tplname) {
        $id = $this->params->getRequestParam('id', 0);
        $oper = $this->params->getRequestParam('oper', '');
        $view = $this->createView($tplname);

        $view->setVar('pagetitle', t('Szállítási mód'));
        $view->setVar('formaction', \mkw\Store::getRouter()->generate('adminszallitasimodsave'));
        $view->setVar('oper', $oper);
        $record = $this->getRepo()->find($id);
        $view->setVar('egyed', $this->loadVars($record));
        return $view->getTemplateResult();
    }

    public function getSelectList($selid = null, $mind = false) {
        $foxpostid = \mkw\Store::getParameter(\mkw\consts::FoxpostSzallitasiMod);
        if ($mind) {
            $rec = $this->getRepo()->getAll(array(),array('sorrend'=>'ASC','nev'=>'ASC'));
        }
        else {
            $rec = $this->getRepo()->getAllWebes();
        }
        $res = array();
        // mkwnál ki kell választani az elsőt
        $vanvalasztott = \mkw\Store::getTheme() !== 'mkwcansas';
        foreach ($rec as $sor) {
            $r = array(
                'id' => $sor->getId(),
                'caption' => $sor->getNev(),
                'leiras' => $sor->getLeiras(),
                'foxpost' => ($sor->getId() == $foxpostid)
            );
            if ($selid) {
                $r['selected'] = $sor->getId() == $selid;
            }
            else {
                if (!$mind) {
                    if (!$vanvalasztott) {
                        $r['selected'] = true;
                        $vanvalasztott = true;
                    }
                    else {
                        $r['selected'] = false;
                    }
                }
            }
            $res[] = $r;
        }
        return $res;
    }

    public function htmllist() {
        $rec = $this->getRepo()->getAll(array(), array('nev' => 'asc'));
        $ret = '<select>';
        foreach ($rec as $sor) {
            $ret.='<option value="' . $sor->getId() . '">' . $sor->getNev() . '</option>';
        }
        $ret.='</select>';
        echo $ret;
    }

}
