<?php

namespace Controllers;

use Entities\Felhasznalo;

class felhasznaloController extends \mkwhelpers\MattableController
{

    public function __construct()
    {
        $this->setEntityName(Felhasznalo::class);
        $this->setKarbFormTplName('felhasznalokarbform.tpl');
        $this->setKarbTplName('felhasznalokarb.tpl');
        $this->setListBodyRowTplName('felhasznalolista_tbody_tr.tpl');
        $this->setListBodyRowVarName('_egyed');
        parent::__construct();
    }

    public function loadVars($t, $forKarb = false)
    {
        if (!$t) {
            $t = new Felhasznalo();
            $this->getEm()->detach($t);
        }
        $x = $this->getEntityFieldsArray($t);
        $x['uzletkotoid'] = $t->getUzletkoto() ? $t->getUzletkoto()->getId() : null;
        $x['uzletkotonev'] = $t->getUzletkoto() ? $t->getUzletkoto()->getNev() : '';
        if ($forKarb) {
            $uzletkotoc = new uzletkotoController();
            $x['uzletkotolist'] = $uzletkotoc->getSelectList($x['uzletkotoid']);
        }
        return $x;
    }

    /**
     * @param \Entities\Felhasznalo $obj
     *
     * @return \Entities\Felhasznalo
     */
    protected function setFields($obj)
    {
        $obj = $this->setEntityFieldsFromRequest($obj);
        if ($this->params->getRequestParam('oper', '') === $this->addOperation) {
            $obj->setFelhasznalonev($this->params->getStringRequestParam('felhasznalonev'));
        }

        $uzletkoto = $this->getRepo(\Entities\Uzletkoto::class)->find($this->params->getIntRequestParam('uzletkoto', 0));
        $obj->setUzletkoto($uzletkoto ?: null);

        return $obj;
    }

    public function getlistbody()
    {
        $view = $this->createView('felhasznalolista_tbody.tpl');

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
        $view = $this->createView('felhasznalolista.tpl');

        $view->setVar('pagetitle', t('Felhasználók'));
        $view->setVar('orderselect', $this->getRepo()->getOrdersForTpl());
        $view->setVar('batchesselect', $this->getRepo()->getBatchesForTpl());
        $view->printTemplateResult();
    }

    protected function _getkarb($tplname)
    {
        $id = $this->params->getRequestParam('id', 0);
        $oper = $this->params->getRequestParam('oper', '');
        $view = $this->createView($tplname);

        $view->setVar('pagetitle', t('Felhasználó'));
        $view->setVar('formaction', \mkw\store::getRouter()->generate('adminfelhasznalosave'));
        $view->setVar('oper', $oper);
        $record = $this->getRepo()->find($id);
        $view->setVar('egyed', $this->loadVars($record, true));
        return $view->getTemplateResult();
    }

    public function getSelectList($selid)
    {
        $rec = $this->getRepo()->getAll([], ['nev' => 'ASC']);
        $res = [];
        foreach ($rec as $sor) {
            $res[] = ['id' => $sor->getId(), 'caption' => $sor->getNev(), 'selected' => ($sor->getId() == $selid)];
        }
        return $res;
    }
}
