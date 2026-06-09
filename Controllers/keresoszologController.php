<?php

namespace Controllers;

use Entities\Keresoszolog;
use mkw\store;

class keresoszologController extends \mkwhelpers\MattableController
{

    public function __construct()
    {
        $this->setEntityName(Keresoszolog::class);
        $this->setKarbFormTplName('keresoszologkarbform.tpl');
        $this->setKarbTplName('keresoszologkarb.tpl');
        $this->setListBodyRowTplName('keresoszologlista_tbody_tr.tpl');
        $this->setListBodyRowVarName('_egyed');
        parent::__construct();
    }

    protected function loadVars($t)
    {
        $x = [];
        if (!$t) {
            $t = new \Entities\Keresoszolog('');
            $this->getEm()->detach($t);
        }
        $x['id'] = $t->getId();
        $x['nev'] = $t->getSzo();
        return $x;
    }

    protected function setFields($obj)
    {
        $obj->setSzo($this->params->getStringRequestParam('nev'));
        return $obj;
    }

    public function getlistbody()
    {
        $view = $this->createView('keresoszologlista_tbody.tpl');

        $filter = new \mkwhelpers\FilterDescriptor();
        if (!is_null($this->params->getRequestParam('nevfilter', null))) {
            $filter->addFilter('nev', 'LIKE', '%' . $this->params->getStringRequestParam('nevfilter') . '%');
        }

        $this->initPager($this->getRepo()->getCount($filter));

        $egyedek = $this->getRepo()->getWithJoins(
            $filter,
            $this->getOrderArray(),
            $this->getPager()->getOffset(),
            $this->getPager()->getElemPerPage()
        );

        echo json_encode($this->loadDataToView($egyedek, 'egyedlista', $view));
    }

    public function viewselect()
    {
        $view = $this->createView('keresoszologlista.tpl');

        $view->setVar('pagetitle', t('keresoszolog'));
        $view->printTemplateResult();
    }

    public function viewlist()
    {
        $view = $this->createView('keresoszologlista.tpl');

        $view->setVar('pagetitle', t('keresoszolog'));
        $view->setVar('orderselect', $this->getRepo()->getOrdersForTpl());
        $view->setVar('batchesselect', $this->getRepo()->getBatchesForTpl());
        $view->printTemplateResult();
    }

    protected function _getkarb($tplname)
    {
        $id = $this->params->getRequestParam('id', 0);
        $oper = $this->params->getRequestParam('oper', '');
        $view = $this->createView($tplname);

        $view->setVar('pagetitle', t('keresoszolog'));
        $view->setVar('formaction', '/admin/keresoszolog/save');
        $view->setVar('oper', $oper);
        $record = $this->getRepo()->findWithJoins($id);
        $view->setVar('egyed', $this->loadVars($record));
        return $view->getTemplateResult();
    }
}