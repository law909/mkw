<?php

namespace Controllers;

use Entities\Rendezveny;

class helyszinController extends \mkwhelpers\MattableController {

    public function __construct($params) {
        $this->setEntityName('Entities\Helyszin');
        $this->setKarbFormTplName('helyszinkarbform.tpl');
        $this->setKarbTplName('helyszinkarb.tpl');
        $this->setListBodyRowTplName('helyszinlista_tbody_tr.tpl');
        $this->setListBodyRowVarName('_egyed');
        parent::__construct($params);
    }

    protected function loadVars($t, $forKarb = false) {
        $x = array();
        if (!$t) {
            $t = new \Entities\Helyszin();
            $this->getEm()->detach($t);
        }
        $x['id'] = $t->getId();
        $x['nev'] = $t->getNev();
        $x['emailsablon'] = $t->getEmailsablon();
        $x['ar'] = $t->getAr();

        if ($forKarb) {
        }
        return $x;
    }

    /**
     * @param \Entities\Helyszin $obj
     * @param $oper
     * @return mixed
     */
    protected function setFields($obj, $oper) {
        $obj->setNev($this->params->getStringRequestParam('nev'));
        $obj->setEmailsablon($this->params->getOriginalStringRequestParam('emailsablon'));
        $obj->setAr($this->params->getNumRequestParam('ar'));
        return $obj;
    }

    public function getlistbody() {
        $view = $this->createView('helyszinlista_tbody.tpl');

        $filterarr = new \mkwhelpers\FilterDescriptor();
        if (!is_null($this->params->getRequestParam('nevfilter', NULL))) {
            $filterarr->addFilter('nev', 'LIKE', '%' . $this->params->getStringRequestParam('nevfilter') . '%');
        }

        $this->initPager($this->getRepo()->getCount($filterarr));

        $egyedek = $this->getRepo()->getWithJoins(
            $filterarr, $this->getOrderArray(), $this->getPager()->getOffset(), $this->getPager()->getElemPerPage());

        echo json_encode($this->loadDataToView($egyedek, 'egyedlista', $view));
    }

    public function getSelectList($selid = null) {
        $rec = $this->getRepo()->getAll(array(), array('nev' => 'ASC'));
        $res = array();
        /** @var \Entities\Helyszin $sor */
        foreach ($rec as $sor) {
            $res[] = array('id' => $sor->getId(), 'caption' => $sor->getNev(), 'selected' => ($sor->getId() == $selid));
        }
        return $res;
    }

    public function viewselect() {
        $view = $this->createView('helyszinlista.tpl');

        $view->setVar('pagetitle', t('Helyszínek'));
        $view->printTemplateResult(false);
    }

    public function viewlist() {
        $view = $this->createView('helyszinlista.tpl');

        $view->setVar('pagetitle', t('Helyszínek'));
        $view->setVar('orderselect', $this->getRepo()->getOrdersForTpl());
        $view->setVar('batchesselect', $this->getRepo()->getBatchesForTpl());
        $view->printTemplateResult(false);
    }

    protected function _getkarb($tplname) {
        $id = $this->params->getRequestParam('id', 0);
        $oper = $this->params->getRequestParam('oper', '');
        $view = $this->createView($tplname);

        $view->setVar('pagetitle', t('Helyszin'));
        $view->setVar('formaction', '/admin/helyszin/save');
        $view->setVar('oper', $oper);
        $record = $this->getRepo()->findWithJoins($id);
        $view->setVar('egyed', $this->loadVars($record, true));
        return $view->getTemplateResult();
    }

}