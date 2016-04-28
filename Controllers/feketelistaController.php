<?php
namespace Controllers;

use mkw\store;

class feketelistaController extends \mkwhelpers\MattableController {

    public function __construct($params) {
        $this->setEntityName('Entities\Feketelista');
        $this->setKarbFormTplName('feketelistakarbform.tpl');
        $this->setKarbTplName('feketelistakarb.tpl');
        $this->setListBodyRowTplName('feketelistalista_tbody_tr.tpl');
        $this->setListBodyRowVarName('_egyed');
        parent::__construct($params);
    }

    protected function loadVars($t) {
        $x = array();
        if (!$t) {
            $t = new \Entities\Feketelista();
            $this->getEm()->detach($t);
        }
        $x['id'] = $t->getId();
        $x['email'] = $t->getEmail();
        $x['ok'] = $t->getOk();
        $x['created'] = $t->getCreatedStr();
        return $x;
    }

    protected function setFields($obj) {
        $obj->setEmail($this->params->getStringRequestParam('email'));
        $obj->setOk($this->params->getStringRequestParam('ok'));
        return $obj;
    }

    public function getlistbody() {
        $view = $this->createView('feketelistalista_tbody.tpl');

        $filter = array();
        if (!is_null($this->params->getRequestParam('emailfilter', NULL))) {
            $filter['fields'][] = 'email';
            $filter['values'][] = $this->params->getStringRequestParam('emailfilter');
        }

        $this->initPager(
            $this->getRepo()->getCount($filter),
            $this->params->getIntRequestParam('elemperpage', 30),
            $this->params->getIntRequestParam('pageno', 1));

        $egyedek = $this->getRepo()->getAll(
            $filter,
            $this->getOrderArray(),
            $this->getPager()->getOffset(),
            $this->getPager()->getElemPerPage());

        echo json_encode($this->loadDataToView($egyedek, 'egyedlista', $view));
    }

    public function viewselect() {
        $view = $this->createView('feketelistalista.tpl');

        $view->setVar('pagetitle', t('Feketelista'));
        $view->printTemplateResult();
    }

    public function viewlist() {
        $view = $this->createView('feketelistalista.tpl');

        $view->setVar('pagetitle', t('Feketelista'));
        $view->setVar('orderselect', $this->getRepo()->getOrdersForTpl());
        $view->setVar('batchesselect', $this->getRepo()->getBatchesForTpl());
        $view->printTemplateResult();
    }

    protected function _getkarb($tplname) {
        $id = $this->params->getRequestParam('id', 0);
        $oper = $this->params->getRequestParam('oper', '');
        $view = $this->createView($tplname);

        $view->setVar('pagetitle', t('Feketelista'));
        $view->setVar('oper', $oper);
        $record = $this->getRepo()->find($id);
        $view->setVar('egyed', $this->loadVars($record));
        return $view->getTemplateResult();
    }
}