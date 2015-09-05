<?php

namespace Controllers;

use mkw\store;

class SzamlafejController extends bizonylatfejController {

    public function __construct($params) {
        $this->biztipus = 'szamla';
        parent::__construct($params);
    }

    public function viewselect() {
        $view = $this->createView('bizonylatfejlista.tpl');

        $view->setVar('pagetitle', t('Számlák'));
        $view->setVar('controllerscript', 'szamlafej.js');
        $this->setVars($view);
        $view->printTemplateResult();
    }

    public function viewlist() {
        $view = $this->createView('bizonylatfejlista.tpl');

        $view->setVar('pagetitle', t('Számlák'));
        $view->setVar('controllerscript', 'szamlafej.js');
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
        $source = $this->params->getStringRequestParam('source', '');
        $view = $this->createView($tplname);

        $view->setVar('pagetitle', t('Számla'));
        $view->setVar('controllerscript', 'szamlafej.js');
        $view->setVar('formaction', '/admin/szamlafej/save');
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
                $egyed['teljesitesstr'] = $kelt;
                $egyed['esedekessegstr'] = \mkw\Store::calcEsedekesseg($kelt, $record->getFizmod(), $record->getPartner());
                switch ($source) {
                    case 'megrendeles':
                        $egyed['megjegyzes'] = 'Rendelés: ' . $id;
                        break;
                    case 'szallito':
                        $egyed['megjegyzes'] = 'Szállítólevél: ' . $id;
                        break;
                }
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
            case 'storno':
                $egyed['id'] = store::createUID();
                $egyed['parentid'] = $id;
                $kelt = date(\mkw\Store::$DateFormat);
                $egyed['keltstr'] = $kelt;
//                $egyed['teljesitesstr'] = $kelt;
//                $egyed['esedekessegstr'] = \mkw\Store::calcEsedekesseg($kelt, $record->getFizmod(), $record->getPartner());
                $egyed['megjegyzes'] = $id . ' stornó bizonylata';
                $ttk = array();
                $cikl = 1;
                foreach($egyed['tetelek'] as $tetel) {
                    $tetel['parentid'] = $tetel['id'];
                    $tetel['id'] = store::createUID($cikl);
                    $tetel['oper'] = 'storno';
                    $ttk[] = $tetel;
                    $cikl++;
                }
                $egyed['tetelek'] = $ttk;
                break;
        }

        $view->setVar('egyed', $egyed);

        $this->setVarsForKarb($view, $record);

        $view->setVar('esedekessegalap', store::getParameter(\mkw\consts::Esedekessegalap, 1));
        return $view->getTemplateResult();
    }

}
