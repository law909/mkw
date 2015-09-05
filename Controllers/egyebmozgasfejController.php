<?php

namespace Controllers;

use mkw\store;

class EgyebmozgasfejController extends bizonylatfejController {

    public function __construct($params) {
        $this->biztipus = 'egyeb';
        parent::__construct($params);
    }

    public function viewselect() {
        $view = $this->createView('bizonylatfejlista.tpl');

        $view->setVar('pagetitle', t('Egyéb mozgások'));
        $view->setVar('controllerscript', 'egyebmozgasfej.js');
        $this->setVars($view);
        $view->printTemplateResult();
    }

    public function viewlist() {
        $view = $this->createView('bizonylatfejlista.tpl');

        $view->setVar('pagetitle', t('Egyéb mozgások'));
        $view->setVar('controllerscript', 'egyebmozgasfej.js');
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

        $view->setVar('pagetitle', t('Egyéb mozgások'));
        $view->setVar('controllerscript', 'egyebmozgasfej.js');
        $view->setVar('formaction', '/admin/egyebfej/save');
        $view->setVar('oper', $oper);
        $this->setVars($view);

        $record = $this->getRepo()->findWithJoins($id);
        $egyed = $this->loadVars($record, true);

        $view->setVar('egyed', $egyed);

        $this->setVarsForKarb($view, $record);

        $view->setVar('esedekessegalap', store::getParameter(\mkw\consts::Esedekessegalap, 1));
        return $view->getTemplateResult();
    }

}
