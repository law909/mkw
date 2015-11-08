<?php

namespace Controllers;


class jutaleklistaController extends \mkwhelpers\MattableController {

    private $tolstr;
    private $igstr;

    public function view() {
        $view = $this->createView('jutaleklista.tpl');

        $view->setVar('toldatum', date(\mkw\Store::$DateFormat));
        $view->setVar('igdatum', date(\mkw\Store::$DateFormat));

        $pcc = new partnercimkekatController($this->params);
        $view->setVar('cimkekat', $pcc->getWithCimkek(null));

        $view->printTemplateResult();
    }

    protected function  createFilter() {
        $this->tolstr = $this->params->getStringRequestParam('tol');
        $this->tolstr = date(\mkw\Store::$DateFormat, strtotime(\mkw\Store::convDate($this->tolstr)));

        $this->igstr = $this->params->getStringRequestParam('ig');
        $this->igstr = date(\mkw\Store::$DateFormat, strtotime(\mkw\Store::convDate($this->igstr)));

        $datummezo = 'datum';

        $filter = new \mkwhelpers\FilterDescriptor();
        $filter
            ->addFilter($datummezo, '>=', $this->tolstr)
            ->addFilter($datummezo, '<=', $this->igstr)
            ->addFilter('irany', '=', 1);

        $partnerkodok = $this->getRepo('Entities\Partner')->getByCimkek($this->params->getArrayRequestParam('cimkefilter'));
        if ($partnerkodok) {
            $filter->addFilter('partner_id', 'IN', $partnerkodok);
        }
        return $filter;
    }

    public function createLista() {
        $filter = $this->createFilter();

        $cimkenevek = $this->getRepo('Entities\Partnercimketorzs')->getCimkeNevek($this->params->getArrayRequestParam('cimkefilter'));

        /** @var \Entities\BankbizonylattetelRepository $btrepo */
        $btrepo = $this->getRepo('Entities\Bankbizonylattetel');

        $mind = $btrepo->getAllHivatkozottJoin($filter,
            array('datum' => 'ASC'));

        $report = $this->createView('rep_jutalek.tpl');
        $report->setVar('lista', $mind);
        $report->setVar('tolstr', $this->tolstr);
        $report->setVar('igstr', $this->igstr);
        $report->setVar('cimkenevek', $cimkenevek);
        $report->printTemplateResult();
    }

    public function exportLista() {

        function x($o) {
            if ($o <= 26) {
                return chr(65 + $o);
            }
            return chr(65 + floor($o / 26)) . chr(65 + ($o % 26));
        }

        $excel = new \PHPExcel();
        $excel->setActiveSheetIndex(0)
            ->setCellValue('A1', 'Payment Due')
            ->setCellValue('B1', 'Date of income')
            ->setCellValue('C1', 'Invoice nr.')
            ->setCellValue('D1', 'Customer')
            ->setCellValue('E1', 'Agent')
            ->setCellValue('F1', 'Income')
            ->setCellValue('G1', 'Comission %')
            ->setCellValue('H1', 'Comission value');

        $filter = $this->createFilter();

        /** @var \Entities\BankbizonylattetelRepository $btrepo */
        $btrepo = $this->getRepo('Entities\Bankbizonylattetel');

        $mind = $btrepo->getAllHivatkozottJoin($filter);

        $sor = 2;
        foreach ($mind as $item) {
            $excel->setActiveSheetIndex(0)
                ->setCellValue('A' . $sor, $item['hivatkozottdatum'])
                ->setCellValue('B' . $sor, $item['datum'])
                ->setCellValue('C' . $sor, $item['hivatkozottbizonylat'])
                ->setCellValue('D' . $sor, $item['partnernev'])
                ->setCellValue('E' . $sor, $item['uzletkotonev'])
                ->setCellValue('F' . $sor, bizformat($item['brutto']))
                ->setCellValue('G' . $sor, $item['uzletkotojutalek'])
                ->setCellValue('H' . $sor, bizformat($item['brutto'] * $item['uzletkotojutalek'] / 100));
            $sor++;
        }

        $writer = \PHPExcel_IOFactory::createWriter($excel, 'Excel2007');

        $filepath = uniqid('comission') . '.xlsx';
        $writer->save($filepath);

        $fileSize = filesize($filepath);

        // Output headers.
        header("Cache-Control: private");
        header("Content-Type: application/stream");
        header("Content-Length: " . $fileSize);
        header("Content-Disposition: attachment; filename=" . $filepath);

        readfile($filepath);

        \unlink($filepath);
    }
}