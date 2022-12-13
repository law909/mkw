<?php

namespace Controllers;

use Entities\Emailtemplate;
use mkw\store;

class emailtemplateController extends \mkwhelpers\MattableController {

    public function __construct($params) {
        $this->setEntityName('Entities\Emailtemplate');
        $this->setKarbFormTplName('emailtemplatekarbform.tpl');
        $this->setKarbTplName('emailtemplatekarb.tpl');
        $this->setListBodyRowTplName('emailtemplatelista_tbody_tr.tpl');
        $this->setListBodyRowVarName('_egyed');
        parent::__construct($params);
    }

    protected function loadVars($t) {
        $x = array();
        if (!$t) {
            $t = new \Entities\Emailtemplate();
            $this->getEm()->detach($t);
        }
        $x['id'] = $t->getId();
        $x['nev'] = $t->getNev();
        $x['targy'] = $t->getTargy();
        $x['szoveg'] = $t->getSzoveg();
        $x['aszfcsatolaskell'] = $t->isAszfcsatolaskell();
        return $x;
    }

    /**
     * @param Emailtemplate $obj
     *
     * @return mixed
     */
    protected function setFields($obj) {
        $obj->setNev($this->params->getStringRequestParam('nev'));
        $obj->setSzoveg($this->params->getOriginalStringRequestParam('szoveg'));
        $obj->setTargy($this->params->getStringRequestParam('targy'));
        $obj->setAszfcsatolaskell($this->params->getBoolRequestParam('aszfcsatolaskell'));
        return $obj;
    }

    public function getlistbody() {
        $view = $this->createView('emailtemplatelista_tbody.tpl');

        $filter = new \mkwhelpers\FilterDescriptor();
        if (!is_null($this->params->getRequestParam('nevfilter', NULL))) {
            $filter->addFilter('nev', 'LIKE', '%' . $this->params->getStringRequestParam('nevfilter') . '%');
        }

        $this->initPager($this->getRepo()->getCount($filter));

        $egyedek = $this->getRepo()->getAll(
            $filter, $this->getOrderArray(), $this->getPager()->getOffset(), $this->getPager()->getElemPerPage());

        echo json_encode($this->loadDataToView($egyedek, 'egyedlista', $view));
    }

    public function viewselect() {
        $view = $this->createView('emailtemplatelista.tpl');

        $view->setVar('pagetitle', t('emailtemplate'));
        $view->printTemplateResult(false);
    }

    public function viewlist() {
        $view = $this->createView('emailtemplatelista.tpl');

        $view->setVar('pagetitle', t('emailtemplate'));
        $view->setVar('orderselect', $this->getRepo()->getOrdersForTpl());
        $view->setVar('batchesselect', $this->getRepo()->getBatchesForTpl());
        $view->printTemplateResult(false);
    }

    protected function _getkarb($tplname) {
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

    public function getSelectList($selid = null) {
        $rec = $this->getRepo()->getAll(array(), array('nev' => 'ASC'));
        $res = array();
        foreach ($rec as $sor) {
            $res[] = array('id' => $sor->getId(), 'caption' => $sor->getNev(), 'selected' => ($sor->getId() == $selid));
        }
        return $res;
    }

    public function convertToCKEditor() {
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
