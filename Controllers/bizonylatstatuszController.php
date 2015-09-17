<?php

namespace Controllers;

use mkw\store;

class bizonylatstatuszController extends \mkwhelpers\MattableController {

    public function __construct($params) {
        $this->setEntityName('Entities\Bizonylatstatusz');
        $this->setKarbFormTplName('bizonylatstatuszkarbform.tpl');
        $this->setKarbTplName('bizonylatstatuszkarb.tpl');
        $this->setListBodyRowTplName('bizonylatstatuszlista_tbody_tr.tpl');
        $this->setListBodyRowVarName('_egyed');
        parent::__construct($params);
    }

    protected function loadVars($t) {
        $x = array();
        if (!$t) {
            $t = new \Entities\Bizonylatstatusz();
            $this->getEm()->detach($t);
        }
        $x['id'] = $t->getId();
        $x['nev'] = $t->getNev();
        $x['sorrend'] = $t->getSorrend();
        $x['csoport'] = $t->getCsoport();
        $x['foglal'] = $t->getFoglal();
        $x['emailtemplatenev'] = $t->getEmailtemplateNev();
        return $x;
    }

    protected function setFields($obj) {
        $obj->setNev($this->params->getStringRequestParam('nev'));
        $obj->setSorrend($this->params->getIntRequestParam('sorrend'));
        $obj->setCsoport($this->params->getStringRequestParam('csoport'));
        $obj->setFoglal($this->params->getBoolRequestParam('foglal'));
        $ck = store::getEm()->getRepository('Entities\Emailtemplate')->find($this->params->getIntRequestParam('emailtemplate'));
        if ($ck) {
            $obj->setEmailtemplate($ck);
        }
        return $obj;
    }

    public function getlistbody() {
        $view = $this->createView('bizonylatstatuszlista_tbody.tpl');

        $filter = array();
        if (!is_null($this->params->getRequestParam('nevfilter', NULL))) {
            $filter['fields'][] = 'nev';
            $filter['values'][] = $this->params->getStringRequestParam('nevfilter');
        }

        $this->initPager(
                $this->getRepo()->getCount($filter), $this->params->getIntRequestParam('elemperpage', 30), $this->params->getIntRequestParam('pageno', 1));

        $egyedek = $this->getRepo()->getWithJoins(
                $filter, $this->getOrderArray(), $this->getPager()->getOffset(), $this->getPager()->getElemPerPage());

        echo json_encode($this->loadDataToView($egyedek, 'egyedlista', $view));
    }

    public function viewselect() {
        $view = $this->createView('bizonylatstatuszlista.tpl');

        $view->setVar('pagetitle', t('bizonylatstatusz'));
        $view->setVar('controllerscript', 'bizonylatstatuszlista.js');
        $view->printTemplateResult();
    }

    public function viewlist() {
        $view = $this->createView('bizonylatstatuszlista.tpl');

        $view->setVar('pagetitle', t('bizonylatstatusz'));
        $view->setVar('controllerscript', 'bizonylatstatuszlista.js');
        $view->setVar('orderselect', $this->getRepo()->getOrdersForTpl());
        $view->setVar('batchesselect', $this->getRepo()->getBatchesForTpl());
        $view->printTemplateResult();
    }

    protected function _getkarb($tplname) {
        $id = $this->params->getRequestParam('id', 0);
        $oper = $this->params->getRequestParam('oper', '');
        $view = $this->createView($tplname);

        $view->setVar('pagetitle', t('bizonylatstatusz'));
        $view->setVar('controllerscript', 'bizonylatstatuszkarb.js');
        $view->setVar('formaction', '/admin/bizonylatstatusz/save');
        $view->setVar('oper', $oper);
        $record = $this->getRepo()->findWithJoins($id);
        $etpl = new emailtemplateController($this->params);
        $view->setVar('emailtemplatelist', $etpl->getSelectList(($record ? $record->getEmailtemplateId() : 0)));
        $view->setVar('egyed', $this->loadVars($record));
        return $view->getTemplateResult();
    }

   	public function getSelectList($selid = null) {
		$rec = $this->getRepo()->getAll(array(),array('sorrend'=>'ASC', 'nev' => 'ASC'));
		$res = array();
		foreach($rec as $sor) {
			$res[] = array('id' => $sor->getId(), 'caption' => $sor->getNev(), 'selected' => ($sor->getId() == $selid));
		}
		return $res;
	}

    public function getCsoportSelectList($sel = null) {
		$rec = $this->getRepo()->getExistingCsoportok($sel);
        $res = array();
        foreach ($rec as $sor) {
            $res[] = array(
                'id' => $sor['csoport'],
                'caption' => $sor['csoport'],
                'selected' => ($sor['csoport'] == $sel));
        }
        return $res;
    }

}
