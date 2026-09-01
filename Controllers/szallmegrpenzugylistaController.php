<?php

namespace Controllers;

use Entities\Bizonylatfej;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Spreadsheet;

/**
 * Szállítói megrendelések pénzügyi kimutatása: a megrendelés értéke, a hozzá kapcsolt bizonylatok
 * pénzügyi teljesítése és a még fizetendő összeg – a Rendelt / beérkezett lista mintájára.
 */
class szallmegrpenzugylistaController extends \mkwhelpers\Controller
{

    public function view()
    {
        $view = $this->createView('szallmegrpenzugylista.tpl');

        $view->setVar('pagetitle', t('Szállítói megrendelések pénzügyi állása'));
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

        return $this->getRepo(Bizonylatfej::class)->getSzallmegrPenzugyiLista($partnerid, $datumtol, $datumig);
    }

    public function refresh()
    {
        $view = $this->createView('szallmegrpenzugylistatetel.tpl');
        $view->setVar('sorok', $this->getData());
        $view->printTemplateResult();
    }

    public function export()
    {
        $excel = new Spreadsheet();
        $excel->setActiveSheetIndex(0)
            ->setCellValue('A1', t('Megrendelés'))
            ->setCellValue('B1', t('Partner'))
            ->setCellValue('C1', t('Kelt'))
            ->setCellValue('D1', t('Érték'))
            ->setCellValue('E1', t('Valutanem'))
            ->setCellValue('F1', t('Kapcsolt bizonylatok'))
            ->setCellValue('G1', t('Fizetve'))
            ->setCellValue('H1', t('Még fizetendő'));

        $sor = 2;
        foreach ($this->getData() as $item) {
            $kapcsoltak = [];
            foreach ($item['kapcsoltak'] as $k) {
                $kapcsoltak[] = $k['tipusnev'] . ' ' . $k['id'] . ' (' . \bizformat($k['brutto']) . ', '
                    . t('fizetve') . ' ' . \bizformat($k['fizetve']) . ')';
            }
            $excel->setActiveSheetIndex(0)
                ->setCellValue('A' . $sor, $item['id'])
                ->setCellValue('B' . $sor, $item['partnernev'])
                ->setCellValue('C' . $sor, $item['keltstr'])
                ->setCellValue('D' . $sor, $item['brutto'])
                ->setCellValue('E' . $sor, $item['valutanemnev'])
                ->setCellValue('F' . $sor, implode('; ', $kapcsoltak))
                ->setCellValue('G' . $sor, $item['osszesfizetve'])
                ->setCellValue('H' . $sor, $item['hatravan']);
            $sor++;
        }

        $writer = IOFactory::createWriter($excel, 'Xlsx');

        $filepath = \mkw\store::storagePath(uniqid('szallmegrpenzugy') . '.xlsx');
        $writer->save($filepath);

        header('Cache-Control: private');
        header('Content-Type: application/stream');
        header('Content-Length: ' . filesize($filepath));
        header('Content-Disposition: attachment; filename="szallitoi_megrendeles_penzugy.xlsx"');

        readfile($filepath);

        \unlink($filepath);
    }

}
