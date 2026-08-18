<?php

namespace Controllers;

use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use Services\FolyoszamlaCheckService;

class folyoszamlaellenorzesController extends \mkwhelpers\MattableController
{

    public function view()
    {
        $view = $this->createView('folyoszamlaellenorzes.tpl');
        $view->setVar('rowlimit', FolyoszamlaCheckService::ROWLIMIT);
        $view->printTemplateResult();
    }

    public function createLista()
    {
        $checkSvc = new FolyoszamlaCheckService();
        $report = $checkSvc->getReport();

        $view = $this->createView('rep_folyoszamlaellenorzes.tpl');
        $view->setVar('ellenorzesek', $report);
        $view->setVar('osszesen', $this->sumTalalat($report));
        $view->setVar('rowlimit', FolyoszamlaCheckService::ROWLIMIT);
        $view->setVar('keltstr', date(\mkw\store::$DateTimeFormat));
        $view->printTemplateResult();
    }

    /**
     * Két munkalap: az összesítő az ellenőrzésenkénti darabszámmal, a tételek pedig egyetlen
     * lapon, az ellenőrzés nevével az első oszlopban – így az Excelben szűrhető és pivotálható.
     */
    public function exportLista()
    {
        $checkSvc = new FolyoszamlaCheckService();
        $report = $checkSvc->getReport(FolyoszamlaCheckService::EXPORTROWLIMIT);

        $excel = new Spreadsheet();

        $osszesito = $excel->setActiveSheetIndex(0);
        $osszesito->setTitle(t('Összesítő'));
        $osszesito
            ->setCellValue('A1', t('Ellenőrzés'))
            ->setCellValue('B1', t('Talált sor'))
            ->setCellValue('C1', t('Kiírt sor'))
            ->setCellValue('D1', t('Leírás'));
        $sor = 2;
        foreach ($report as $ell) {
            $osszesito
                ->setCellValue('A' . $sor, $ell['nev'])
                ->setCellValue('B' . $sor, $ell['db'])
                ->setCellValue('C' . $sor, count($ell['rows']))
                ->setCellValue('D' . $sor, $ell['leiras']);
            $sor++;
        }
        $osszesito
            ->setCellValue('A' . $sor, t('Összesen'))
            ->setCellValue('B' . $sor, $this->sumTalalat($report));

        $tetelek = $excel->createSheet();
        $tetelek->setTitle(t('Tételek'));
        $tetelek
            ->setCellValue('A1', t('Ellenőrzés'))
            ->setCellValue('B1', t('Pénzmozgás'))
            ->setCellValue('C1', t('Bizonylat'))
            ->setCellValue('D1', t('Partner'))
            ->setCellValue('E1', t('Dátum'))
            ->setCellValue('F1', t('Összeg'))
            ->setCellValue('G1', t('Valutanem'))
            ->setCellValue('H1', t('Megjegyzés'));
        $sor = 2;
        foreach ($report as $ell) {
            foreach ($ell['rows'] as $item) {
                $tetelek
                    ->setCellValue('A' . $sor, $ell['nev'])
                    ->setCellValue('B' . $sor, $item['penzmozgas'])
                    ->setCellValue('C' . $sor, $item['bizonylat'])
                    ->setCellValue('D' . $sor, $item['partner'])
                    ->setCellValue('E' . $sor, $item['datum'])
                    ->setCellValue('F' . $sor, $item['osszeg'] * 1)
                    ->setCellValue('G' . $sor, $item['valutanem'])
                    ->setCellValue('H' . $sor, $item['megjegyzes']);
                $sor++;
            }
        }
        $tetelek->setAutoFilter('A1:H' . max(1, $sor - 1));

        $excel->setActiveSheetIndex(0);
        $writer = IOFactory::createWriter($excel, 'Xlsx');

        $filename = uniqid('folyoszamlaellenorzes') . '.xlsx';
        $filepath = \mkw\store::storagePath($filename);
        $writer->save($filepath);

        header('Cache-Control: private');
        header('Content-Type: application/stream');
        header('Content-Length: ' . filesize($filepath));
        header('Content-Disposition: attachment; filename=' . $filename);

        readfile($filepath);

        \unlink($filepath);
    }

    /**
     * Az elavult folyószámla sorok újraképzése. A gomb mögötti végpont; a riport ettől lesz
     * "nulla" azokon a tételeken, amiket csak a régi sorok hoztak be.
     */
    public function regenerate()
    {
        header('Content-Type: application/json; charset=utf-8');
        $checkSvc = new FolyoszamlaCheckService();
        $eredmeny = $checkSvc->regenerate();
        echo json_encode([
            'ok' => true,
            'penztar' => $eredmeny['penztar'],
            'bank' => $eredmeny['bank'],
        ]);
    }

    private function sumTalalat($report)
    {
        $osszes = 0;
        foreach ($report as $ell) {
            $osszes += $ell['db'];
        }
        return $osszes;
    }
}
