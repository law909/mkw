<?php

namespace Controllers;

use Entities\Emailtemplate;
use mkw\store;

class emailtemplateController extends \mkwhelpers\MattableController
{

    public function __construct()
    {
        $this->setEntityName(Emailtemplate::class);
        $this->setKarbFormTplName('emailtemplatekarbform.tpl');
        $this->setKarbTplName('emailtemplatekarb.tpl');
        $this->setListBodyRowTplName('emailtemplatelista_tbody_tr.tpl');
        $this->setListBodyRowVarName('_egyed');
        parent::__construct();
    }

    protected function loadVars($t)
    {
        if (!$t) {
            $t = new \Entities\Emailtemplate();
            $this->getEm()->detach($t);
        }
        return $this->getEntityFieldsArray($t);
    }

    /**
     * @param Emailtemplate $obj
     *
     * @return mixed
     */
    protected function setFields($obj)
    {
        return $this->setEntityFieldsFromRequest($obj, ['raw' => ['szoveg']]);
    }

    public function getlistbody()
    {
        $view = $this->createView('emailtemplatelista_tbody.tpl');

        $filter = new \mkwhelpers\FilterDescriptor();
        if (!is_null($this->params->getRequestParam('nevfilter', null))) {
            $filter->addFilter('nev', 'LIKE', '%' . $this->params->getStringRequestParam('nevfilter') . '%');
        }

        $this->initPager($this->getRepo()->getCount($filter));

        $egyedek = $this->getRepo()->getAll(
            $filter,
            $this->getOrderArray(),
            $this->getPager()->getOffset(),
            $this->getPager()->getElemPerPage()
        );

        echo json_encode($this->loadDataToView($egyedek, 'egyedlista', $view));
    }

    public function viewselect()
    {
        $view = $this->createView('emailtemplatelista.tpl');

        $view->setVar('pagetitle', t('emailtemplate'));
        $view->printTemplateResult(false);
    }

    public function viewlist()
    {
        $view = $this->createView('emailtemplatelista.tpl');

        $view->setVar('pagetitle', t('emailtemplate'));
        $view->setVar('orderselect', $this->getRepo()->getOrdersForTpl());
        $view->setVar('batchesselect', $this->getRepo()->getBatchesForTpl());
        $view->printTemplateResult(false);
    }

    protected function _getkarb($tplname)
    {
        $id = $this->params->getRequestParam('id', 0);
        $oper = $this->params->getRequestParam('oper', '');
        $view = $this->createView($tplname);

        $view->setVar('pagetitle', t('emailtemplate'));
        $view->setVar('formaction', '/admin/emailtemplate/save');
        $view->setVar('oper', $oper);
        $record = $this->getRepo()->find($id);
        $view->setVar('egyed', $this->loadVars($record));
        return $view->getTemplateResult();
    }

    public function getSelectList($selid = null)
    {
        $rec = $this->getRepo()->getAll([], ['nev' => 'ASC']);
        $res = [];
        foreach ($rec as $sor) {
            $res[] = ['id' => $sor->getId(), 'caption' => $sor->getNev(), 'selected' => ($sor->getId() == $selid)];
        }
        return $res;
    }

    public function convertToCKEditor()
    {
        $rec = $this->getRepo()->getAll();
        /** @var \Entities\Emailtemplate $sor */
        foreach ($rec as $sor) {
            $sor->convertForCKEditor();
            $this->getEm()->persist($sor);
            $this->getEm()->flush();
        }
        echo 'OK';
    }

}
