<?php

namespace Controllers;


class keresoszolistaController extends \mkwhelpers\MattableController {

    private $tolstr;
    private $igstr;

    public function view() {
        $view = $this->createView('keresoszolista.tpl');

        $view->setVar('toldatum', date(\mkw\store::$DateFormat));
        $view->setVar('igdatum', date(\mkw\store::$DateFormat));

        $view->printTemplateResult();
    }

    protected function createFilter() {
        $this->tolstr = $this->params->getStringRequestParam('tol');
        $this->tolstr = date(\mkw\store::$DateFormat, strtotime(\mkw\store::convDate($this->tolstr)));

        $this->igstr = $this->params->getStringRequestParam('ig');
        $this->igstr = date(\mkw\store::$DateFormat, strtotime(\mkw\store::convDate($this->igstr)));

        $datummezo = 'datum';

        $filter = new \mkwhelpers\FilterDescriptor();
        $filter
            ->addFilter($datummezo, '>=', $this->tolstr)
            ->addFilter($datummezo, '<=', $this->igstr);

        return $filter;
    }

    public function createLista() {
        $filter = $this->createFilter();

        /** @var \Entities\KeresoszologRepository $repo */
        $repo = $this->getRepo('Entities\Keresoszolog');

        $mind = $repo->getWithCount($filter);

        $report = $this->createView('rep_keresoszo.tpl');
        $report->setVar('lista', $mind);
        $report->setVar('tolstr', $this->tolstr);
        $report->setVar('igstr', $this->igstr);
        $report->printTemplateResult();
    }

}