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
        if (!$t) {
            $t = new Blokk();
            $this->getEm()->detach($t);
        }
        $v = $this->getEntityFieldsArray($t);
        $tipuslist = $this->getRepo()->getTipusList();
        $v['tipusnev'] = $tipuslist[$t->getTipus()];
        $sziglist = $this->getRepo()->getSzovegigazitasList();
        $bmlist = $this->getRepo()->getBlokkmagassagList();
        $v['hatterkepurlmedium'] = $t->getHatterkepurlMedium();
        $v['hatterkepurlsmall'] = $t->getHatterkepurlSmall();
        $v['hatterkepurlmini'] = $t->getHatterkepurlMini();
        $v['hatterkepurllarge'] = $t->getHatterkepurlLarge();
        $v['hatterkepurl400'] = $t->getHatterkepurl400();
        $v['hatterkepurl2000'] = $t->getHatterkepurl2000();
        $v['szovegigazitasnev'] = $sziglist[$t->getSzovegigazitas()];
        $v['blokkmagassagnev'] = $bmlist[$t->getBlokkmagassag()];
        $v['hatterkepurl2medium'] = $t->getHatterkepurl2Medium();
        $v['hatterkepurl2small'] = $t->getHatterkepurl2Small();
        $v['hatterkepurl2mini'] = $t->getHatterkepurl2Mini();
        $v['hatterkepurl2large'] = $t->getHatterkepurl2Large();
        $v['hatterkepurl2400'] = $t->getHatterkepurl2400();
        $v['hatterkepurl22000'] = $t->getHatterkepurl22000();
        $v['szovegigazitas2nev'] = $sziglist[$t->getSzovegigazitas2()];
        return $v;
    }

    protected function setFields($obj)
    {
        $this->setEntityFieldsFromRequest($obj);
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
