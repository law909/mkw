<?php

namespace Controllers;

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
        return $x;
    }

    protected function setFields($obj) {
        $obj->setNev($this->params->getStringRequestParam('nev'));
        $obj->setSzoveg($this->params->getOriginalStringRequestParam('szoveg'));
        $obj->setTargy($this->params->getStringRequestParam('targy'));
        return $obj;
    }

    public function getlistbody() {
        $view = $this->createView('emailtemplatelista_tbody.tpl');

        $filter = array();
        if (!is_null($this->params->getRequestParam('nevfilter', NULL))) {
            $filter['fields'][] = 'nev';
            $filter['values'][] = $this->params->getStringRequestParam('nevfilter');
        }

        $this->initPager($this->getRepo()->getCount($filter));

        $egyedek = $this->getRepo()->getAll(
                $filter, $this->getOrderArray(), $this->getPager()->getOffset(), $this->getPager()->getElemPerPage());

        echo json_encode($this->loadDataToView($egyedek, 'egyedlista', $view));
    }

    public function viewselect() {
        $view = $this->createView('emailtemplatelista.tpl');

        $view->setVar('pagetitle', t('emailtemplate'));
        $view->printTemplateResult();
    }

    public function viewlist() {
        $view = $this->createView('emailtemplatelista.tpl');

        $view->setVar('pagetitle', t('emailtemplate'));
        $view->setVar('orderselect', $this->getRepo()->getOrdersForTpl());
        $view->setVar('batchesselect', $this->getRepo()->getBatchesForTpl());
        $view->printTemplateResult();
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

}
