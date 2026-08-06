<?php

namespace Controllers;

use Entities\Rewrite301;

class rewrite301Controller extends \mkwhelpers\MattableController
{

    public function __construct()
    {
        $this->setEntityName(Rewrite301::class);
        $this->setKarbFormTplName('rewrite301karbform.tpl');
        $this->setKarbTplName('rewrite301karb.tpl');
        $this->setListBodyRowTplName('rewrite301lista_tbody_tr.tpl');
        $this->setListBodyRowVarName('_egyed');
        parent::__construct();
    }

    public function loadVars($t, $forKarb = false)
    {
        if (!$t) {
            $t = new Rewrite301();
            $this->getEm()->detach($t);
        }
        $x = $this->getEntityFieldsArray($t);
        return $x;
    }

    /**
     * @param \Entities\Rewrite301 $obj
     *
     * @return \Entities\Rewrite301
     */
    protected function setFields($obj)
    {
        $obj = $this->setEntityFieldsFromRequest($obj);

        return $obj;
    }

    public function getlistbody()
    {
        $view = $this->createView('rewrite301lista_tbody.tpl');

        $filter = new \mkwhelpers\FilterDescriptor();
        if (!is_null($this->params->getRequestParam('nevfilter', null))) {
            $filter->addFilter('fromurl', 'LIKE', '%' . $this->params->getStringRequestParam('nevfilter') . '%');
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
        $view = $this->createView('rewrite301lista.tpl');

        $view->setVar('pagetitle', t('Átirányítások (301)'));
        $view->setVar('orderselect', $this->getRepo()->getOrdersForTpl());
        $view->setVar('batchesselect', $this->getRepo()->getBatchesForTpl());
        $view->printTemplateResult();
    }

    protected function _getkarb($tplname)
    {
        $id = $this->params->getRequestParam('id', 0);
        $oper = $this->params->getRequestParam('oper', '');
        $view = $this->createView($tplname);

        $view->setVar('pagetitle', t('Átirányítás'));
        $view->setVar('formaction', \mkw\store::getRouter()->generate('adminrw301save'));
        $view->setVar('oper', $oper);
        $record = $this->getRepo()->find($id);
        $view->setVar('egyed', $this->loadVars($record, true));
        return $view->getTemplateResult();
    }

    public function rewrite()
    {
        $req = $_SERVER['REQUEST_URI'];
        $rec = $this->getRepo()->findOneByFromurl($req);
        if ($rec && $rec->getTourl()) {
            header("HTTP/1.1 301 Moved Permanently");
            header('Location: ' . $rec->getTourl());
        }
    }
}
