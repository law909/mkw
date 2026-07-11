<?php

namespace Controllers;


use Entities\Bankbizonylattetel;
use Entities\Bankszamla;
use Entities\Partner;
use Entities\Partnercimketorzs;

class penzbelistaController extends \mkwhelpers\MattableController
{

    public function view()
    {
        $view = $this->createView('penzbelista.tpl');

        $view->setVar('toldatum', date(\mkw\store::$DateFormat));
        $view->setVar('igdatum', date(\mkw\store::$DateFormat));

        $pcc = new partnercimkekatController();
        $view->setVar('cimkekat', $pcc->getWithCimkek(null));

        $partner = new partnerController();
        $view->setVar('partnerlist', $partner->getSelectList());

        $bsz = new bankszamlaController();
        $view->setVar('bankszamlalist', $bsz->getSelectList());

        $view->printTemplateResult();
    }

    public function createLista()
    {
        $bankszamlaid = $this->params->getIntRequestParam('bankszamla');
        $bsz = $this->getRepo(Bankszamla::class)->find($bankszamlaid);
        if ($bsz) {
            $bankszamlaszam = $bsz->getSzamlaszam();
        }

        $tolstr = $this->params->getStringRequestParam('tol');
        $tolstr = date(\mkw\store::$DateFormat, strtotime(\mkw\store::convDate($tolstr)));

        $igstr = $this->params->getStringRequestParam('ig');
        $igstr = date(\mkw\store::$DateFormat, strtotime(\mkw\store::convDate($igstr)));

        $datummezo = 'datum';

        $filter = new \mkwhelpers\FilterDescriptor();
        $filter
            ->addFilter($datummezo, '>=', $tolstr)
            ->addFilter($datummezo, '<=', $igstr)
            ->addFilter('irany', '=', 1);

        $selpartner = $this->params->getIntRequestParam('partner');
        if ($selpartner) {
            $filter->addFilter('partner', '=', $selpartner);
        } else {
            $partnerkodok = $this->getRepo(Partner::class)->getByCimkek($this->params->getArrayRequestParam('cimkefilter'));
            if ($partnerkodok) {
                $filter->addFilter('partner', 'IN', $partnerkodok);
            }
            $cimkenevek = $this->getRepo(Partnercimketorzs::class)->getCimkeNevek($this->params->getArrayRequestParam('cimkefilter'));
        }

        if ($bankszamlaid) {
            $filter->addFilter('bf.bankszamla', '=', $bankszamlaid);
        }
        /** @var \Entities\BankbizonylattetelRepository $btrepo */
        $btrepo = $this->getRepo(Bankbizonylattetel::class);

        $mind = $btrepo->getAllWithFej(
            $filter,
            ['datum' => 'ASC', 'partnernev' => 'ASC']
        );

        $lista = [];
        /** @var \Entities\Bankbizonylattetel $item */
        foreach ($mind as $item) {
            $lista[] = [
                'datum' => $item->getDatumStr(),
                'hivatkozottbizonylat' => $item->getHivatkozottbizonylat(),
                'partnerid' => $item->getPartnerId(),
                'partnernev' => $item->getPartnernev(),
                'osszeg' => $item->getBrutto(),
                'valutanem' => $item->getValutanemnev()
            ];
        }

        $valsum = $btrepo->calcSumByValutanem($filter);

        $report = $this->createView('rep_penzbe.tpl');
        $report->setVar('lista', $lista);
        $report->setVar('valutanemosszesito', $valsum);
        $report->setVar('tolstr', $tolstr);
        $report->setVar('igstr', $igstr);
        $report->setVar('cimkenevek', $cimkenevek);
        $report->setVar('bankszamlaszam', $bankszamlaszam);
        $report->printTemplateResult();
    }
}