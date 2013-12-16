<?php

namespace Controllers;

use mkw\store;

class megrendelesfejController extends bizonylatfejController {

    public function __construct($params) {
        $this->setEntityName('Entities\Bizonylatfej');
        $this->setKarbFormTplName('bizonylatfejkarbform.tpl');
        $this->setKarbTplName('bizonylatfejkarb.tpl');
        $this->setListBodyRowTplName('bizonylatfejlista_tbody_tr.tpl');
        $this->setListBodyRowVarName('_egyed');
        parent::__construct($params);
    }

    public function setVars($view) {
        $view->setVar('showteljesites', false);
        $view->setVar('showesedekesseg', false);
        $view->setVar('showhatarido', true);
        $view->setVar('showvalutanem', true);
        $view->setVar('showbizonylatstatuszeditor', true);
    }

    protected function loadVars($t, $forKarb = false) {
		if (!$t) {
			$t = new \Entities\Bizonylatfej();
			$this->getEm()->detach($t);
		}
        $x = parent::loadVars($t, $forKarb);
        $bsc = new bizonylatstatuszController($this->params);
        $x['bizonylatstatuszlist'] = $bsc->getSelectList($t->getBizonylatstatuszId());
        return $x;
    }

    public function getlistbody() {
        $view = $this->createView('bizonylatfejlista_tbody.tpl');

        $this->setVars($view);

        $filter = array();
        if (!is_null($this->params->getRequestParam('idfilter', NULL))) {
            $filter['fields'][] = 'id';
            $filter['values'][] = $this->params->getStringRequestParam('idfilter');
        }

        $filter['fields'][] = 'bizonylattipus';
        $filter['clauses'][] = '=';
        $filter['values'][] = $this->getRepo('Entities\Bizonylattipus')->find('megrendeles');

        $this->initPager($this->getRepo()->getCount($filter));

        $egyedek = $this->getRepo()->getWithJoins(
                $filter, $this->getOrderArray(), $this->getPager()->getOffset(), $this->getPager()->getElemPerPage());

        echo json_encode($this->loadDataToView($egyedek, 'egyedlista', $view));
    }

    public function viewselect() {
        $view = $this->createView('bizonylatfejlista.tpl');

        $view->setVar('pagetitle', t('Megrendelések'));
        $view->setVar('controllerscript', 'megrendelesfej.js');
        $this->setVars($view);
        $view->printTemplateResult();
    }

    public function viewlist() {
        $view = $this->createView('bizonylatfejlista.tpl');

        $view->setVar('pagetitle', t('Megrendelések'));
        $view->setVar('controllerscript', 'megrendelesfej.js');
        $view->setVar('orderselect', $this->getRepo()->getOrdersForTpl());
        $view->setVar('batchesselect', $this->getRepo()->getBatchesForTpl());
        $this->setVars($view);
        $view->printTemplateResult();
    }

    protected function _getkarb($tplname) {
        $id = $this->params->getRequestParam('id', 0);
        $oper = $this->params->getRequestParam('oper', '');
        $view = $this->createView($tplname);

        $view->setVar('pagetitle', t('Megrendelés'));
        $view->setVar('controllerscript', 'megrendelesfej.js');
        $view->setVar('formaction', '/admin/megrendelesfej/save');
        $view->setVar('oper', $oper);
        $this->setVars($view);

        $record = $this->getRepo()->findWithJoins($id);
        $view->setVar('egyed', $this->loadVars($record, true));

        $partner = new partnerController($this->params);
        $view->setVar('partnerlist', $partner->getSelectList(($record ? $record->getPartnerId() : 0)));

        $raktar = new raktarController($this->params);
        if (!$record || !$record->getRaktarId()) {
            $raktarid = store::getParameter(\mkw\consts::Raktar, 0);
        }
        else {
            $raktarid = $record->getRaktarId();
        }
        $view->setVar('raktarlist', $raktar->getSelectList($raktarid));

        $fizmod = new fizmodController($this->params);
        if (!$record || !$record->getFizmodId()) {
            $fmid = \mkw\Store::getParameter(\mkw\consts::Fizmod);
        }
        else {
            $fmid = $record->getFizmodId();
        }
        $view->setVar('fizmodlist', $fizmod->getSelectList($fmid));

        $szallitasimod = new szallitasimodController($this->params);
        $view->setVar('szallitasimodlist', $szallitasimod->getSelectList(($record ? $record->getSzallitasimodId() : 0)));

        $valutanem = new valutanemController($this->params);
        if (!$record || !$record->getValutanemId()) {
            $valutaid = store::getParameter(\mkw\consts::Valutanem, 0);
        }
        else {
            $valutaid = $record->getValutanemId();
        }
        $view->setVar('valutanemlist', $valutanem->getSelectList($valutaid));

        $bankszla = new bankszamlaController($this->params);
        $view->setVar('bankszamlalist', $bankszla->getSelectList(($record ? $record->getBankszamlaId() : 0)));

        $view->setVar('esedekessegalap', store::getParameter(\mkw\consts::Esedekessegalap, 1));
        return $view->getTemplateResult();
    }

    public function getFiokList() {
        $filter = array();
        $filter['fields'][] = 'partner';
        $filter['clauses'][] = '=';
        $filter['values'][] = $this->getRepo('Entities\Partner')->getLoggedInUser();
        $filter['fields'][] = 'bizonylattipus';
        $filter['clauses'][] = '=';
        $filter['values'][] = $this->getRepo('Entities\Bizonylattipus')->find('megrendeles');
        $l = $this->getRepo()->getWithJoins($filter, array('kelt' => 'ASC'));
        $ret = array();
        foreach ($l as $it) {
            $ret[] = $it->toLista();
        }
        return $ret;
    }

    protected function setFields($obj) {
        $obj->setBizonylattipus($this->getRepo('Entities\Bizonylattipus')->find('megrendeles'));
        return parent::setFields($obj);
    }

    protected function afterSave($o) {
        parent::afterSave($o);
        if ($this->params->getBoolRequestParam('bizonylatstatuszertesito')) {
            $statusz = $o->getBizonylatstatusz();
            if ($statusz) {
                $emailtpl = $statusz->getEmailtemplate();
                $o->sendStatuszEmail($emailtpl);
            }
        }
    }

    public function setStatusz() {
        $bf = $this->getRepo()->find($this->params->getStringRequestParam('id'));
        if ($bf) {
            $statusz = $this->getRepo('Entities\Bizonylatstatusz')->find($this->params->getIntRequestParam('statusz'));
            if ($statusz) {
                $bf->setBizonylatstatusz($statusz);
                $this->getEm()->persist($bf);
                $this->getEm()->flush();
                if ($this->params->getBoolRequestParam('bizonylatstatuszertesito')) {
                    $emailtpl = $statusz->getEmailtemplate();
                    $bf->sendStatuszEmail($emailtpl);
                }
            }
        }
    }
}
