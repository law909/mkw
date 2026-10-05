<?php

namespace Controllers;

use Entities\Menucsoport;

class menucsoportController extends \mkwhelpers\MattableController
{

    public function __construct()
    {
        $this->setEntityName(Menucsoport::class);
        $this->setKarbFormTplName('menucsoportkarbform.tpl');
        $this->setKarbTplName('menucsoportkarb.tpl');
        $this->setListBodyRowTplName('menucsoportlista_tbody_tr.tpl');
        $this->setListBodyRowVarName('_egyed');
        parent::__construct();
    }

    public function loadVars($t, $forKarb = false)
    {
        if (!$t) {
            $t = new Menucsoport();
            $this->getEm()->detach($t);
        }
        return $this->getEntityFieldsArray($t);
    }

    /**
     * @param \Entities\Menucsoport $obj
     *
     * @return \Entities\Menucsoport
     */
    protected function setFields($obj)
    {
        return $this->setEntityFieldsFromRequest($obj);
    }

    protected function beforeRemove($o)
    {
        $filter = new \mkwhelpers\FilterDescriptor();
        $filter->addFilter('menucsoport', '=', $o->getId());
        if ($this->getRepo(\Entities\Menu::class)->getCount($filter)) {
            throw new \mkwhelpers\Exceptions\UserMessageException(t('A menücsoportban menüpontok vannak, ezért nem törölhető.'));
        }
    }

    public function getlistbody()
    {
        if (!$this->sysadminOnly()) {
            return;
        }
        $view = $this->createView('menucsoportlista_tbody.tpl');

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
        if (!$this->sysadminOnly()) {
            return;
        }
        $view = $this->createView('menucsoportlista.tpl');

        $view->setVar('pagetitle', t('Menücsoportok'));
        $view->setVar('orderselect', $this->getRepo()->getOrdersForTpl());
        $view->setVar('batchesselect', $this->getRepo()->getBatchesForTpl());
        $view->printTemplateResult();
    }

    protected function _getkarb($tplname)
    {
        if (!$this->sysadminOnly()) {
            return '';
        }
        $id = $this->params->getRequestParam('id', 0);
        $oper = $this->params->getRequestParam('oper', '');
        $view = $this->createView($tplname);

        $view->setVar('pagetitle', t('Menücsoport'));
        $view->setVar('formaction', \mkw\store::getRouter()->generate('adminmenucsoportsave'));
        $view->setVar('oper', $oper);
        $view->setVar('egyed', $this->loadVars($this->getRepo()->find($id), true));
        return $view->getTemplateResult();
    }

    public function save()
    {
        if (!$this->sysadminOnly()) {
            return;
        }
        parent::save();
    }

    public function getSelectList($selid = null)
    {
        $rec = $this->getRepo()->getAll([], ['sorrend' => 'ASC', 'nev' => 'ASC']);
        $res = [];
        foreach ($rec as $sor) {
            $res[] = ['id' => $sor->getId(), 'caption' => $sor->getNev(), 'selected' => ($sor->getId() == $selid)];
        }
        return $res;
    }
}
