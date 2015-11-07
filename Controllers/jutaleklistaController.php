<?php

namespace Controllers;


class jutaleklistaController extends \mkwhelpers\MattableController {

    public function view() {
        $view = $this->createView('jutaleklista.tpl');

        $view->setVar('toldatum', date(\mkw\Store::$DateFormat));
        $view->setVar('igdatum', date(\mkw\Store::$DateFormat));

        $pcc = new partnercimkekatController($this->params);
        $view->setVar('cimkekat', $pcc->getWithCimkek(null));

        $partner = new partnerController($this->params);
        $view->setVar('partnerlist', $partner->getSelectList());

        $view->printTemplateResult();
    }

    public function createLista() {
        $tolstr = $this->params->getStringRequestParam('tol');
        $tolstr = date(\mkw\Store::$DateFormat, strtotime(\mkw\Store::convDate($tolstr)));

        $igstr = $this->params->getStringRequestParam('ig');
        $igstr = date(\mkw\Store::$DateFormat, strtotime(\mkw\Store::convDate($igstr)));

        $datummezo = 'datum';

        $filter = array();
        $filter['fields'][] = $datummezo;
        $filter['clauses'][] = '>=';
        $filter['values'][] = $tolstr;
        $filter['fields'][] = $datummezo;
        $filter['clauses'][] = '<=';
        $filter['values'][] = $igstr;
        $filter['fields'][] = 'irany';
        $filter['clauses'][] = '=';
        $filter['values'][] = 1;

        $selpartner = $this->params->getIntRequestParam('partner');
        if ($selpartner) {
            $filter['fields'][] = 'partner';
            $filter['clauses'][] = '=';
            $filter['values'][] = $selpartner;
        }
        else {
            $partnerkodok = $this->getRepo('Entities\Partner')->getByCimkek($this->params->getArrayRequestParam('cimkefilter'));
            if ($partnerkodok) {
                $filter['fields'][] = 'partner';
                $filter['values'][] = $partnerkodok;
            }
            $cimkenevek = $this->getRepo('Entities\Partnercimketorzs')->getCimkeNevek($this->params->getArrayRequestParam('cimkefilter'));
        }

        /** @var \Entities\BankbizonylattetelRepository $btrepo */
        $btrepo = $this->getRepo('Entities\Bankbizonylattetel');

        $mind = $btrepo->getAll($filter,
            array('datum' => 'ASC', 'partnernev' => 'ASC'));

        $lista = array();
        /** @var \Entities\Bankbizonylattetel $item */
        foreach ($mind as $item) {
            $lista[] = array(
                'datum' => $item->getDatumStr(),
                'hivatkozottbizonylat' => $item->getHivatkozottbizonylat(),
                'partnerid' => $item->getPartnerId(),
                'partnernev' => $item->getPartnerNev(),
                'osszeg' => $item->getBrutto(),
                'valutanem' => $item->getValutanemnev()
            );
        }

        $valsum = $btrepo->calcSumByValutanem($filter);

        $report = $this->createView('rep_jutalek.tpl');
        $report->setVar('lista', $lista);
        $report->setVar('valutanemosszesito', $valsum);
        $report->setVar('tolstr', $tolstr);
        $report->setVar('igstr', $igstr);
        $report->setVar('cimkenevek', $cimkenevek);
        $report->printTemplateResult();
    }
}