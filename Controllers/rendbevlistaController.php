<?php

namespace Controllers;

use Entities\Bizonylatfej;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Spreadsheet;

class rendbevlistaController extends \mkwhelpers\Controller
{

    public function view()
    {
        $view = $this->createView('rendbevlista.tpl');

        $view->setVar('pagetitle', t('Rendelt / beérkezett kimutatás'));
        $view->setVar('toldatum', date(\mkw\store::$DateFormat));
        $view->setVar('igdatum', date(\mkw\store::$DateFormat));
        $partner = new partnerController();
        $view->setVar('partnerlist', $partner->getSelectList());

        $view->printTemplateResult(false);
    }

    protected function getData()
    {
        $partnerid = $this->params->getIntRequestParam('partner');
        $datumtolstr = $this->params->getStringRequestParam('datumtol');
        $datumigstr = $this->params->getStringRequestParam('datumig');
        $datumtol = $datumtolstr ? \mkw\store::convDate($datumtolstr) : null;
        $datumig = $datumigstr ? \mkw\store::convDate($datumigstr) : null;

        return $this->getRepo(Bizonylatfej::class)->getRendeltBeerkezettLista(
            $partnerid,
            $datumtol,
            $datumig,
            \mkw\store::getAdminDataLocale()
        );
    }

    public function refresh()
    {
        $view = $this->createView('rendbevlistatetel.tpl');
        $view->setVar('tetelek', $this->getData());
        $view->printTemplateResult();
    }

    public function export()
    {
        $excel = new Spreadsheet();
        $excel->setActiveSheetIndex(0)
            ->setCellValue('A1', t('Cikkszám'))
            ->setCellValue('B1', t('Név'))
            ->setCellValue('C1', t('Változat'))
            ->setCellValue('D1', t('Rendelt'))
            ->setCellValue('E1', t('Beérkezett'))
            ->setCellValue('F1', t('Még érkezni fog'));

        $sor = 2;
        foreach ($this->getData() as $item) {
            $excel->setActiveSheetIndex(0)
                ->setCellValue('A' . $sor, $item['cikkszam'])
                ->setCellValue('B' . $sor, $item['nev'])
                ->setCellValue('C' . $sor, $item['valtozat'])
                ->setCellValue('D' . $sor, $item['rendelt'])
                ->setCellValue('E' . $sor, $item['beerkezett'])
                ->setCellValue('F' . $sor, $item['kulonbozet']);
            $sor++;
        }

        $writer = IOFactory::createWriter($excel, 'Xlsx');

        $filepath = \mkw\store::storagePath(uniqid('rendbev') . '.xlsx');
        $writer->save($filepath);

        $fileSize = filesize($filepath);

        header('Cache-Control: private');
        header('Content-Type: application/stream');
        header('Content-Length: ' . $fileSize);
        header('Content-Disposition: attachment; filename="rendelt_beerkezett.xlsx"');

        readfile($filepath);

        \unlink($filepath);
    }

}
