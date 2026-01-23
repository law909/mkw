<?php

namespace Controllers;

use Entities\Blokk;
use mkw\store;

class blokkController extends \mkwhelpers\MattableController
{

    public function __construct($params)
    {
        $this->setEntityName(Blokk::class);
        $this->setKarbTplName('blokkkarb.tpl');
        $this->setKarbFormTplName('blokkkarbform.tpl');
        $this->setListBodyRowVarName('_blokk');
        $this->setListBodyRowTplName('blokklista_tbody_tr.tpl');
        parent::__construct($params);
    }

    public function loadVars($t, $forKarb = false)
    {
        $v = [];
        if (!$t) {
            $t = new Blokk();
            $this->getEm()->detach($t);
        }
        $v['id'] = $t->getId();
        $v['nev'] = $t->getNev();
        $v['tipus'] = $t->getTipus();
        $tipuslist = $this->getRepo()->getTipusList();
        $v['tipusnev'] = $tipuslist[$t->getTipus()];
        $sziglist = $this->getRepo()->getSzovegigazitasList();
        $bmlist = $this->getRepo()->getBlokkmagassagList();
        $v['cssclass'] = $t->getCssclass();
        $v['cssstyle'] = $t->getCssstyle();
        $v['sorrend'] = $t->getSorrend();
        $v['lathato'] = $t->isLathato();
        $v['hatterkepurl'] = $t->getHatterkepurl();
        $v['videourl'] = $t->getVideourl();
        $v['cim'] = $t->getCim();
        $v['leiras'] = $t->getLeiras();
        $v['gombfelirat'] = $t->getGombfelirat();
        $v['gomburl'] = $t->getGomburl();
        $v['szovegigazitas'] = $t->getSzovegigazitas();
        $v['szovegigazitasnev'] = $sziglist[$t->getSzovegigazitas()];
        $v['blokkmagassag'] = $t->getBlokkmagassag();
        $v['blokkmagassagnev'] = $bmlist[$t->getBlokkmagassag()];
        $v['hatterkepurl2'] = $t->getHatterkepurl2();
        $v['videourl2'] = $t->getVideourl2();
        $v['cim2'] = $t->getCim2();
        $v['leiras2'] = $t->getLeiras2();
        $v['gombfelirat2'] = $t->getGombfelirat2();
        $v['gomburl2'] = $t->getGomburl2();
        $v['szovegigazitas2'] = $t->getSzovegigazitas2();
        $v['szovegigazitas2nev'] = $sziglist[$t->getSzovegigazitas2()];
        $v['blokkmagassag2'] = $t->getBlokkmagassag2();
        $v['blokkmagassag2nev'] = $bmlist[$t->getBlokkmagassag2()];
        return $v;
    }

    protected function setFields($obj)
    {
        $obj->setNev($this->params->getStringRequestParam('nev'));
        $obj->setTipus($this->params->getIntRequestParam('tipus'));
        $obj->setCssclass($this->params->getStringRequestParam('cssclass'));
        $obj->setCssstyle($this->params->getStringRequestParam('cssstyle'));
        $obj->setSorrend($this->params->getIntRequestParam('sorrend'));
        $obj->setLathato($this->params->getBoolRequestParam('lathato'));
        $obj->setHatterkepurl($this->params->getStringRequestParam('hatterkepurl'));
        $obj->setVideourl($this->params->getStringRequestParam('videourl'));
        $obj->setCim($this->params->getStringRequestParam('cim'));
        $obj->setLeiras($this->params->getStringRequestParam('leiras'));
        $obj->setGombfelirat($this->params->getStringRequestParam('gombfelirat'));
        $obj->setGomburl($this->params->getStringRequestParam('gomburl'));
        $obj->setSzovegigazitas($this->params->getIntRequestParam('szovegigazitas'));
        $obj->setBlokkmagassag($this->params->getIntRequestParam('blokkmagassag'));
        $obj->setHatterkepurl2($this->params->getStringRequestParam('hatterkepurl2'));
        $obj->setVideourl2($this->params->getStringRequestParam('videourl2'));
        $obj->setCim2($this->params->getStringRequestParam('cim2'));
        $obj->setLeiras2($this->params->getStringRequestParam('leiras2'));
        $obj->setGombfelirat2($this->params->getStringRequestParam('gombfelirat2'));
        $obj->setGomburl2($this->params->getStringRequestParam('gomburl2'));
        $obj->setSzovegigazitas2($this->params->getIntRequestParam('szovegigazitas2'));
        $obj->setBlokkmagassag2($this->params->getIntRequestParam('blokkmagassag2'));
        return $obj;
    }

    public function getlistbody()
    {
        $view = $this->createView('blokklista_tbody.tpl');

        $filter = new \mkwhelpers\FilterDescriptor();
        if (!is_null($this->params->getRequestParam('nevfilter', null))) {
            $filter->addFilter('nev', 'LIKE', '%' . $this->params->getStringRequestParam('nevfilter') . '%');
        }

        $this->initPager(
            $this->getRepo()->getCount($filter),
            $this->params->getIntRequestParam('elemperpage', 30),
            $this->params->getIntRequestParam('pageno', 1)
        );

        $crec = $this->getRepo()->getAll($filter, $this->getOrderArray(), $this->getPager()->getOffset(), $this->getPager()->getElemPerPage());

        echo json_encode($this->loadDataToView($crec, 'blokklista', $view));
    }

    public function viewlist()
    {
        $view = $this->createView('blokklista.tpl');
        $view->setVar('pagetitle', t('Blokkok'));
        $view->setVar('orderselect', $this->getRepo()->getOrdersForTpl());
        $view->setVar('batchesselect', $this->getRepo()->getBatchesForTpl());
        $view->printTemplateResult();
    }

    protected function _getkarb($tplname)
    {
        $id = $this->params->getRequestParam('id', 0);
        $oper = $this->params->getRequestParam('oper', '');
        $view = $this->createView($tplname);

        $view->setVar('oper', $oper);
        $record = $this->getRepo()->findWithJoins($id);
        $this->setVars($view);
        $view->setVar('blokk', $this->loadVars($record, true));
        $view->setVar('tipuslist', $this->getRepo()->getTipusList());
        $view->setVar('szovegigazitaslist', $this->getRepo()->getSzovegigazitasList());
        $view->setVar('blokkmagassaglist', $this->getRepo()->getBlokkmagassagList());
        $view->printTemplateResult();
    }

    public function getSelectList($selid = null)
    {
        $csapatrepo = $this->getEm()->getRepository(Blokk::class);
        $csapatlist = $csapatrepo->getWithJoins([], ['nev' => 'ASC']);
        $csapatok = [];
        foreach ($csapatlist as $csapat) {
            $csapatok[] = [
                'id' => $csapat->getId(),
                'caption' => $csapat->getNev(),
                'nev' => $csapat->getNev(),
                'selected' => ($csapat->getId() == $selid)
            ];
        }
        return $csapatok;
    }

    public function setflag()
    {
        $id = $this->params->getIntRequestParam('id');
        $kibe = $this->params->getBoolRequestParam('kibe');
        $flag = $this->params->getStringRequestParam('flag');
        /** @var \Entities\Blokk $obj */
        $obj = $this->getRepo()->find($id);
        if ($obj) {
            switch ($flag) {
                case 'lathato':
                    $obj->setLathato($kibe);
                    break;
            }
            $this->getEm()->persist($obj);
            $this->getEm()->flush();
        }
    }

    public function getListAsArray()
    {
        $filter = new \mkwhelpers\FilterDescriptor();
        $filter->addFilter('lathato', '=', true);
        $res = [];
        $blokkok = $this->getRepo()->getAll($filter, ['sorrend' => 'ASC']);
        foreach ($blokkok as $blokk) {
            $res[] = $this->loadVars($blokk);
        }
        return $res;
    }
}
