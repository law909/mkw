<?php

namespace Controllers;

use Entities\MPTNGYKar;

class mptngykarController extends \mkwhelpers\MattableController
{

    public function __construct()
    {
        $this->setEntityName(MPTNGYKar::class);
        $this->setKarbFormTplName('mptngykarkarbform.tpl');
        $this->setKarbTplName('mptngykarkarb.tpl');
        $this->setListBodyRowTplName('mptngykarlista_tbody_tr.tpl');
        $this->setListBodyRowVarName('_egyed');
        parent::__construct();
    }

    public function loadVars($t, $forKarb = false)
    {
        if (!$t) {
            $t = new MPTNGYKar();
            $this->getEm()->detach($t);
        }
        $x = $this->getEntityFieldsArray($t);
        $x['egyetemid'] = $t->getEgyetem() ? $t->getEgyetem()->getId() : null;
        $x['egyetemnev'] = $t->getEgyetem() ? $t->getEgyetem()->getNev() : '';
        if ($forKarb) {
            $egyetemc = new mptngyegyetemController();
            $x['egyetemlist'] = $egyetemc->getSelectList($x['egyetemid']);
        }
        return $x;
    }

    /**
     * @param \Entities\MPTNGYKar $obj
     *
     * @return \Entities\MPTNGYKar
     */
    protected function setFields($obj)
    {
        $obj = $this->setEntityFieldsFromRequest($obj);

        $egyetem = $this->getRepo(\Entities\MPTNGYEgyetem::class)->find($this->params->getIntRequestParam('egyetem', 0));
        $obj->setEgyetem($egyetem ?: null);

        return $obj;
    }

    public function getlistbody()
    {
        $view = $this->createView('mptngykarlista_tbody.tpl');

        $filter = new \mkwhelpers\FilterDescriptor();
        if (!is_null($this->params->getRequestParam('nevfilter', null))) {
            $filter->addFilter('nev', 'LIKE', '%' . $this->params->getStringRequestParam('nevfilter') . '%');
        }

        $this->initPager(
            $this->getRepo()->getCount($filter),
            $this->params->getIntRequestParam('elemperpage', 30),
            $this->params->getIntRequestParam('pageno', 1)
        );

        $egyedek = $this->getRepo()->getAll(
            $filter,
            $this->getOrderArray(),
            $this->getPager()->getOffset(),
            $this->getPager()->getElemPerPage()
        );

        echo json_encode($this->loadDataToView($egyedek, 'egyedlista', $view));
    }

    public function viewlist()
    {
        $view = $this->createView('mptngykarlista.tpl');

        $view->setVar('pagetitle', t('MPT NGY karok'));
        $view->setVar('orderselect', $this->getRepo()->getOrdersForTpl());
        $view->setVar('batchesselect', $this->getRepo()->getBatchesForTpl());
        $view->printTemplateResult();
    }

    protected function _getkarb($tplname)
    {
        $id = $this->params->getRequestParam('id', 0);
        $oper = $this->params->getRequestParam('oper', '');
        $view = $this->createView($tplname);

        $view->setVar('pagetitle', t('MPT NGY kar'));
        $view->setVar('formaction', \mkw\store::getRouter()->generate('adminmptngykarsave'));
        $view->setVar('oper', $oper);
        $record = $this->getRepo()->find($id);
        $view->setVar('egyed', $this->loadVars($record, true));
        return $view->getTemplateResult();
    }

    public function getSelectList($selid = null)
    {
        $rec = $this->getRepo()->getAll([], ['nev' => 'ASC']);
        $res = [];
        foreach ($rec as $sor) {
            $res[] = [
                'id' => $sor->getId(),
                'caption' => $sor->getNev(),
                'selected' => ($sor->getId() == $selid),
            ];
        }
        return $res;
    }

    public function getList()
    {
        $res = [];
        $egyetem = $this->params->getIntRequestParam('egyetem', 0);
        $filter = new \mkwhelpers\FilterDescriptor();
        if ($egyetem) {
            $filter->addFilter('egyetem', '=', $egyetem);
        }
        $rec = $this->getRepo()->getAll($filter, ['nev' => 'asc']);
        foreach ($rec as $sor) {
            $res[] = [
                'id' => $sor->getId(),
                'caption' => $sor->getNev(),
            ];
        }
        echo json_encode($res);
    }
}
