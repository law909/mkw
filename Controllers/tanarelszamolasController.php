<?php

namespace Controllers;

use mkwhelpers\FilterDescriptor;

class tanarelszamolasController extends \mkwhelpers\Controller {

    private $tolstr;
    private $igstr;

    public function view() {
        $view = $this->createView('tanarelszamolas.tpl');

        $view->setVar('pagetitle', t('Tanár elszámolás'));

        $view->printTemplateResult(false);

    }

    protected function getData() {

        $tolstr = date(\mkw\store::$DateFormat, strtotime(\mkw\store::convDate($this->params->getStringRequestParam('tol'))));
        $igstr = date(\mkw\store::$DateFormat, strtotime(\mkw\store::convDate($this->params->getStringRequestParam('ig'))));

        $filter = new FilterDescriptor();
        if ($tolstr) {
            $filter->addFilter('_xx.datum', '>=', $tolstr);
        }
        if ($igstr) {
            $filter->addFilter('_xx.datum', '<=', $igstr);
        }

        $tetelek = $this->getRepo('Entities\JogaReszvetel')->getTanarOsszesito($filter);

        return $tetelek;
    }

    public function refresh() {

        $res = $this->getData();

        $view = $this->createView('tanarelszamolastanarsum.tpl');

        $view->setVar('tetelek', $res);
        $view->printTemplateResult();
    }

    public function export() {

        function x($o) {
            if ($o <= 26) {
                return chr(65 + $o);
            }
            return chr(65 + floor($o / 26)) . chr(65 + ($o % 26));
        }

        $excel = new \PHPExcel();

        $res = $this->getData();
        $mind = $res['tetelek'];

        $excel->setActiveSheetIndex(0)
            ->setCellValue('A1', t('Cikkszám'))
            ->setCellValue('B1', t('Név'))
            ->setCellValue('C1', t('Változat 1'))
            ->setCellValue('D1', t('Változat 2'))
            ->setCellValue('E1', t('Mennyiség'))
            ->setCellValue('F1', t('Érték'));

        $sor = 2;
        foreach ($mind as $item) {
            $excel->setActiveSheetIndex(0)
                ->setCellValue('A' . $sor, $item['cikkszam'])
                ->setCellValue('B' . $sor, $item['nev'])
                ->setCellValue('C' . $sor, $item['ertek1'])
                ->setCellValue('D' . $sor, $item['ertek2'])
                ->setCellValue('E' . $sor, $item['mennyiseg'])
                ->setCellValue('F' . $sor, $item['ertek']);

            $sor++;
        }

        $writer = \PHPExcel_IOFactory::createWriter($excel, 'Excel2007');

        $filepath = uniqid('tanarelszamolas') . '.xlsx';
        $writer->save($filepath);

        $fileSize = filesize($filepath);

        // Output headers.
        header('Cache-Control: private');
        header('Content-Type: application/stream');
        header('Content-Length: ' . $fileSize);
        header('Content-Disposition: attachment; filename=' . $filepath);

        readfile($filepath);

        \unlink($filepath);
    }

    public function doPrint() {

        $res = $this->getData();

        $view = $this->createView('rep_bizonylattetellistatetel.tpl');

        $view->setVar('tolstr', $this->tolstr);
        $view->setVar('igstr', $this->igstr);
        $view->setVar('tetelek', $res['tetelek']);
        $view->printTemplateResult();
    }
}