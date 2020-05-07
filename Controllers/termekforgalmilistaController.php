<?php

namespace Controllers;


use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Spreadsheet;

class termekforgalmilistaController extends \mkwhelpers\Controller {

    public function view() {
        $view = $this->createView('termekforgalmilista.tpl');

        $view->setVar('pagetitle', t('Termékforgalmi lista'));
        $view->setVar('datumtipus', 'teljesites');
        $rc = new raktarController($this->params);
        $view->setVar('raktarlista', $rc->getSelectList());
        $partner = new partnerController($this->params);
        $view->setVar('partnerlist', $partner->getSelectList());
        $view->setVar('gyartolist', $partner->getSzallitoSelectList(0));
        $arsav = new termekarController($this->params);
        $view->setVar('arsavlist', $arsav->getSelectList());

        $view->setVar('nyelvlist', \mkw\store::getLocaleSelectList());

        $pcc = new partnercimkekatController($this->params);
        $view->setVar('cimkekat', $pcc->getWithCimkek());

        $view->printTemplateResult(false);

    }

    protected function getData() {
        $partnerid = $this->params->getIntRequestParam('partner');
        $raktarid = $this->params->getIntRequestParam('raktar');
        $datumtipus = $this->params->getStringRequestParam('datumtipus');
        $datumtolstr = $this->params->getStringRequestParam('tol');
        $datumigstr = $this->params->getStringRequestParam('ig');
        $forgalomfilter = $this->params->getIntRequestParam('forgalomfilter');
        $keszletfilter = $this->params->getIntRequestParam('keszletfilter');
        $ertektipus = $this->params->getIntRequestParam('ertektipus');
        $arsav = $this->params->getStringRequestParam('arsav');
        $fafilter = $this->params->getArrayRequestParam('fafilter');
        $nevfilter = $this->params->getRequestParam('nevfilter', NULL);
        $gyartoid = $this->params->getIntRequestParam('gyarto');
        $nyelv = \mkw\store::toLocale($this->params->getStringRequestParam('nyelv'));
        $partnercimkefilter = $this->params->getArrayRequestParam('partnercimkefilter');

        $tetelek = $this->getRepo('Entities\Bizonylatfej')->getTermekForgalmiLista($raktarid, $partnerid, $datumtipus, $datumtolstr, $datumigstr, $ertektipus,
            $arsav, $fafilter, $nevfilter, $gyartoid, $nyelv, $partnercimkefilter);

        switch ($keszletfilter) {
            case 1: // van keszleten
                foreach ($tetelek as $key => $tetel) {
                    if ($tetel['zaro'] <= 0) {
                        unset($tetelek[$key]);
                    }
                };
                break;
            case 2: // nincs keszleten
                foreach ($tetelek as $key => $tetel) {
                    if ($tetel['zaro'] > 0) {
                        unset($tetelek[$key]);
                    }
                };
                break;
            case 3: // negativ
                foreach ($tetelek as $key => $tetel) {
                    if ($tetel['zaro'] >= 0) {
                        unset($tetelek[$key]);
                    }
                };
                break;
        }

        switch ($forgalomfilter) {
            case 1: // mozgott
                foreach ($tetelek as $key => $tetel) {
                    if ($tetel['be'] == 0 && $tetel['ki'] == 0) {
                        unset($tetelek[$key]);
                    }
                };
                break;
            case 2: // nem mozgott
                foreach ($tetelek as $key => $tetel) {
                    if ($tetel['be'] != 0 || $tetel['ki'] != 0) {
                        unset($tetelek[$key]);
                    }
                };
                break;
        }

        return array(
            'ertektipus' => $ertektipus,
            'tetelek' => $tetelek
        );
    }

    public function refresh() {
        $res = $this->getData();
        $view = $this->createView('termekforgalmilistatetel.tpl');
        $view->setVar('ertektipus', $res['ertektipus']);
        $view->setVar('tetelek', $res['tetelek']);
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
            ->setCellValue('A1', t('Cikkszám'))
            ->setCellValue('B1', t('Név'))
            ->setCellValue('C1', t('Változat 1'))
            ->setCellValue('D1', t('Változat 2'))
            ->setCellValue('E1', t('Nyitó'))
            ->setCellValue('F1', t('Be'))
            ->setCellValue('G1', t('Ki'))
            ->setCellValue('H1', t('Záró'))
            ->setCellValue('I1', t('Nyitó érték'))
            ->setCellValue('J1', t('Be érték'))
            ->setCellValue('K1', t('Ki érték'))
            ->setCellValue('L1', t('Záró érték'));

        $res = $this->getData();
        $mind = $res['tetelek'];

        $sor = 2;
        foreach ($mind as $item) {
            $excel->setActiveSheetIndex(0)
                ->setCellValue('A' . $sor, $item['cikkszam'])
                ->setCellValue('B' . $sor, $item['nev'])
                ->setCellValue('C' . $sor, $item['ertek1'])
                ->setCellValue('D' . $sor, $item['ertek2'])
                ->setCellValue('E' . $sor, $item['nyito'])
                ->setCellValue('F' . $sor, $item['be'])
                ->setCellValue('G' . $sor, $item['ki'])
                ->setCellValue('H' . $sor, $item['zaro'])
                ->setCellValue('I' . $sor, $item['nyitoertek'])
                ->setCellValue('J' . $sor, $item['beertek'])
                ->setCellValue('K' . $sor, $item['kiertek'])
                ->setCellValue('L' . $sor, $item['zaroertek']);
            $sor++;
        }
        $writer = IOFactory::createWriter($excel, 'Xlsx');

        $filepath = \mkw\store::storagePath(uniqid('termekforgalmi') . '.xlsx');
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