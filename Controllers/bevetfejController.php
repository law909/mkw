<?php

namespace Controllers;

use mkw\store;

class BevetfejController extends bizonylatfejController {

    const BIZTIPUS = 'bevet';

    public function __construct($params) {
        $this->setEntityName('Entities\Bizonylatfej');
        $this->setKarbFormTplName('bizonylatfejkarbform.tpl');
        $this->setKarbTplName('bizonylatfejkarb.tpl');
        $this->setListBodyRowTplName('bizonylatfejlista_tbody_tr.tpl');
        $this->setListBodyRowVarName('_egyed');
        parent::__construct($params);
    }

    public function setVars($view) {
        $bt = $this->getRepo('Entities\Bizonylattipus')->find(self::BIZTIPUS);
        $bt->setTemplateVars($view);
        parent::setVars($view);
        $a = date(\mkw\Store::$DateFormat, strtotime('-1 week'));
        $view->setVar('datumtolfilter', $a);
    }

    public function getlistbody() {
        $view = $this->createView('bizonylatfejlista_tbody.tpl');

        $this->setVars($view);

        $filter = array();
        $filter = $this->loadFilters($filter);

        $filter['fields'][] = 'bizonylattipus';
        $filter['clauses'][] = '=';
        $filter['values'][] = $this->getRepo('Entities\Bizonylattipus')->find(self::BIZTIPUS);

        $this->initPager($this->getRepo()->getCount($filter));

        $egyedek = $this->getRepo()->getWithJoins(
                $filter, $this->getOrderArray(), $this->getPager()->getOffset(), $this->getPager()->getElemPerPage());

        echo json_encode($this->loadDataToView($egyedek, 'egyedlista', $view));
    }

    public function viewselect() {
        $view = $this->createView('bizonylatfejlista.tpl');

        $view->setVar('pagetitle', t('Bevételezések'));
        $view->setVar('controllerscript', 'bevetfej.js');
        $this->setVars($view);
        $view->printTemplateResult();
    }

    public function viewlist() {
        $view = $this->createView('bizonylatfejlista.tpl');

        $view->setVar('pagetitle', t('Bevételezések'));
        $view->setVar('controllerscript', 'bevetfej.js');
        $view->setVar('orderselect', $this->getRepo()->getOrdersForTpl());
        $view->setVar('batchesselect', $this->getRepo()->getBatchesForTpl());
        $this->setVars($view);
        $view->printTemplateResult();
    }

    public function _getkarb($tplname, $id = null, $oper = null) {
        if (!$id) {
            $id = $this->params->getRequestParam('id', 0);
        }
        if (!$oper) {
            $oper = $this->params->getRequestParam('oper', '');
        }
        $view = $this->createView($tplname);

        $view->setVar('pagetitle', t('Bevételezés'));
        $view->setVar('controllerscript', 'bevetfej.js');
        $view->setVar('formaction', '/admin/bevetfej/save');
        $view->setVar('oper', $oper);
        $this->setVars($view);

        $record = $this->getRepo()->findWithJoins($id);
        $egyed = $this->loadVars($record, true);

        if ($oper == 'inherit') {
            $egyed['id'] = store::createUID();
            $egyed['parentid'] = $id;
            $kelt = date(\mkw\Store::$DateFormat);
            $egyed['keltstr'] = $kelt;
            $egyed['teljesitesstr'] = $kelt;
            $egyed['esedekessegstr'] = \mkw\Store::calcEsedekesseg($kelt, $record->getFizmod(), $record->getPartner());
            $ttk = array();
            $cikl = 1;
            foreach($egyed['tetelek'] as $tetel) {
                $tetel['parentid'] = $tetel['id'];
                $tetel['id'] = store::createUID($cikl);
                $tetel['oper'] = 'inherit';
                $ttk[] = $tetel;
                $cikl++;
            }
            $egyed['tetelek'] = $ttk;
        }

        $view->setVar('egyed', $egyed);

        $partner = new partnerController($this->params);
        $filter = array();
        $filter['fields'][] = 'szallito';
        $filter['clauses'][] = '=';
        $filter['values'][] = true;
        $view->setVar('partnerlist', $partner->getSelectList(($record ? $record->getPartnerId() : 0), $filter));

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
        $l = $this->getRepo()->getWithJoins($filter, array('kelt' => 'ASC'));
        $ret = array();
        foreach ($l as $it) {
            $ret[] = $it->toLista();
        }
        return $ret;
    }

    protected function setFields($obj, $parancs) {
        $obj->setBizonylattipus($this->getRepo('Entities\Bizonylattipus')->find(self::BIZTIPUS));
        return parent::setFields($obj, $parancs);
    }

    public function doPrint() {
        $o = $this->getRepo()->find($this->params->getStringRequestParam('id'));
        if ($o) {
            $biztip = $this->getRepo('Entities\Bizonylattipus')->find(self::BIZTIPUS);
            if ($biztip && $biztip->getTplname()) {
                $view = $this->createView($biztip->getTplname());
                $this->setVars($view);
                $view->setVar('egyed', $o->toLista());
                $view->setVar('afaosszesito',$this->getRepo()->getAFAOsszesito($o));
                echo $view->getTemplateResult();
            }
        }
    }

}
