<?php

namespace Controllers;


use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Spreadsheet;

class partnermijszoralatogatasosszesitolistaController extends \mkwhelpers\Controller {

    public function view() {
        $view = $this->createView('partnermijszoralatogatasosszesitolista.tpl');

        $view->setVar('pagetitle', t('Óralátogatás összesítő lista'));
        $view->setVar('ev', date('Y') * 1 - 1);

        $view->printTemplateResult(false);

    }

    protected function getData() {
        $ev = $this->params->getIntRequestParam('ev');

        $tetelek = $this->getRepo('Entities\PartnerMIJSZOralatogatas')->getReszletezes($ev);

        return array(
            'ev' => $ev,
            'tetelek' => $tetelek
        );
    }

    public function refresh() {
        $res = $this->getData();
        $view = $this->createView('partnermijszoralatogatasosszesitolistatetel.tpl');
        $view->setVar('tetelek', $res['tetelek']);
        $view->setVar('ev', $res['ev']);
        $view->printTemplateResult();
    }

    public function export() {
        function x($o) {
            if ($o <= 26) {
                return chr(65 + $o);
            }
            return chr(65 + floor($o / 26)) . chr(65 + ($o % 26));
        }

        $excel = new Spreadsheet();
        $excel->setActiveSheetIndex(0)
            ->setCellValue('A1', t('Tag neve'))
            ->setCellValue('B1', t('Email'))
            ->setCellValue('C1', t('Tanár neve'))
            ->setCellValue('D1', t('Tanár egyéb'))
            ->setCellValue('E1', t('Helyszín'))
            ->setCellValue('F1', t('Mikor'))
            ->setCellValue('G1', t('Óraszám'));

        $res = $this->getData();
        $mind = $res['tetelek'];

        $sor = 2;
        foreach ($mind as $item) {
            $excel->setActiveSheetIndex(0)
                ->setCellValue('A' . $sor, $item['nev'])
                ->setCellValue('B' . $sor, $item['email'])
                ->setCellValue('C' . $sor, $item['tanar'])
                ->setCellValue('D' . $sor, $item['tanaregyeb'])
                ->setCellValue('E' . $sor, $item['helyszin'])
                ->setCellValue('F' . $sor, $item['mikor'])
                ->setCellValue('G' . $sor, $item['oraszam']);
            $sor++;
        }
        $writer = IOFactory::createWriter($excel, 'Xlsx');

        $filepath = \mkw\store::storagePath(uniqid('oralatogatasosszesito') . '.xlsx');
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
}