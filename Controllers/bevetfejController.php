<?php

namespace Controllers;

use mkw\store;

class BevetfejController extends bizonylatfejController {

    public function __construct($params) {
        $this->biztipus = 'bevet';
        parent::__construct($params);
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

        $this->setVarsForKarb($view, $record);

        $partner = new partnerController($this->params);
        $filter = array();
        $filter['fields'][] = 'szallito';
        $filter['clauses'][] = '=';
        $filter['values'][] = true;
        $view->setVar('partnerlist', $partner->getSelectList(($record ? $record->getPartnerId() : 0), $filter));

        $view->setVar('esedekessegalap', store::getParameter(\mkw\consts::Esedekessegalap, 1));
        return $view->getTemplateResult();
    }

}
