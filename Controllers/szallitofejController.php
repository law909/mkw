<?php

namespace Controllers;

use mkw\store;

class SzallitofejController extends bizonylatfejController {

    public function __construct($params) {
        $this->biztipus = 'szallito';
        parent::__construct($params);
    }

    public function viewselect() {
        $view = $this->createView('bizonylatfejlista.tpl');

        $view->setVar('pagetitle', t('Szállítólevelek'));
        $view->setVar('controllerscript', 'szallitofej.js');
        $this->setVars($view);
        $view->printTemplateResult();
    }

    public function viewlist() {
        $view = $this->createView('bizonylatfejlista.tpl');

        $view->setVar('pagetitle', t('Szállítólevelek'));
        $view->setVar('controllerscript', 'szallitofej.js');
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

        $view->setVar('pagetitle', t('Szállítólevél'));
        $view->setVar('controllerscript', 'szallitofej.js');
        $view->setVar('formaction', '/admin/szallitofej/save');
        $view->setVar('oper', $oper);
        $this->setVars($view);

        $record = $this->getRepo()->findWithJoins($id);
        $egyed = $this->loadVars($record, true);

        switch ($oper) {
            case 'inherit':
                $egyed['id'] = store::createUID();
                $egyed['parentid'] = $id;
                $kelt = date(\mkw\Store::$DateFormat);
                $egyed['keltstr'] = $kelt;
                $egyed['esedekessegstr'] = \mkw\Store::calcEsedekesseg($kelt, $record->getFizmod(), $record->getPartner());
                $egyed['megjegyzes'] = 'Szállítólevél: ' . $id;
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
                break;
        }

        $view->setVar('egyed', $egyed);

        $this->setVarsForKarb($view, $record);

        return $view->getTemplateResult();
    }

}
