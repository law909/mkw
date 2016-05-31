<?php

namespace Controllers;


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

        $tetelek = $this->getRepo('Entities\Bizonylatfej')->getTermekForgalmiLista($raktarid, $partnerid, $datumtipus, $datumtolstr, $datumigstr, $ertektipus,
            $arsav, $fafilter, $nevfilter, $gyartoid, $nyelv);

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
                    if ($tetel['be'] === 0 && $tetel['ki'] === 0) {
                        unset($tetelek[$key]);
                    }
                };
                break;
            case 2: // nem mozgott
                foreach ($tetelek as $key => $tetel) {
                    if ($tetel['be'] !== 0 || $tetel['ki'] !== 0) {
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

        $excel = new \PHPExcel();
        $excel->setActiveSheetIndex(0)
            ->setCellValue('A1', t('Cikkszám'))
            ->setCellValue('B1', t('Név'))
            ->setCellValue('C1', t('Nyitó'))
            ->setCellValue('D1', t('Be'))
            ->setCellValue('E1', t('Ki'))
            ->setCellValue('F1', t('Záró'))
            ->setCellValue('G1', t('Nyitó érték'))
            ->setCellValue('H1', t('Be érték'))
            ->setCellValue('I1', t('Ki érték'))
            ->setCellValue('J1', t('Záró érték'));

        $res = $this->getData();
        $mind = $res['tetelek'];

        $sor = 2;
        foreach ($mind as $item) {
            $excel->setActiveSheetIndex(0)
                ->setCellValue('A' . $sor, $item['cikkszam'])
                ->setCellValue('B' . $sor, $item['nev'] . ' ' . $item['ertek1'] . ' ' . $item['ertek2'])
                ->setCellValue('C' . $sor, $item['nyito'])
                ->setCellValue('D' . $sor, $item['be'])
                ->setCellValue('E' . $sor, $item['ki'])
                ->setCellValue('F' . $sor, $item['zaro'])
                ->setCellValue('G' . $sor, $item['nyitoertek'])
                ->setCellValue('H' . $sor, $item['beertek'])
                ->setCellValue('I' . $sor, $item['kiertek'])
                ->setCellValue('J' . $sor, $item['zaroertek']);
            $sor++;
        }

        $writer = \PHPExcel_IOFactory::createWriter($excel, 'Excel2007');

        $filepath = uniqid('termekforgalmi') . '.xlsx';
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