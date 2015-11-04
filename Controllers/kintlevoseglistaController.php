<?php

namespace Controllers;


class kintlevoseglistaController extends \mkwhelpers\MattableController {

    public function view() {
        $view = $this->createView('kintlevoseglista.tpl');

        $view->setVar('toldatum', date(\mkw\Store::$DateFormat));
        $view->setVar('igdatum', date(\mkw\Store::$DateFormat));
        $view->setVar('datumtipus', 'teljesites');

        $pcc = new partnercimkekatController($this->params);
        $view->setVar('cimkekat', $pcc->getWithCimkek(null));

        $view->printTemplateResult();
    }

    public function createLista() {
        $tolstr = $this->params->getStringRequestParam('tol');
        $tolstr = date(\mkw\Store::$DateFormat, strtotime(\mkw\Store::convDate($tolstr)));

        $igstr = $this->params->getStringRequestParam('ig');
        $igstr = date(\mkw\Store::$DateFormat, strtotime(\mkw\Store::convDate($igstr)));

        $mt = $this->params->getStringRequestParam('datumtipus');
        switch ($mt) {
            case 'kelt':
                $datummezo = 'kelt';
                break;
            case 'teljesites':
                $datummezo = 'teljesites';
                break;
            case 'esedekesseg':
                $datummezo = 'esedekesseg';
                break;
            default:
                $datummezo = 'teljesites';
        }

        $partnerkodok = $this->getRepo('Entities\Partner')->getByCimkek($this->params->getArrayRequestParam('cimkefilter'));

        echo $datummezo . '<br>';
        echo $tolstr . '<br>';
        echo $igstr . '<br>';
        print_r($partnerkodok);
    }
}