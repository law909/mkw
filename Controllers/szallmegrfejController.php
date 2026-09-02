<?php

namespace Controllers;

use PhpOffice\PhpSpreadsheet\IOFactory;

class szallmegrfejController extends bizonylatfejController
{

    public function __construct()
    {
        parent::__construct();
        $this->setBiztipus('szallmegr');
        $this->setPageTitle('Szállítói megrendelés');
        $this->setPluralPageTitle('Szállítói megrendelések');
    }

    public function onGetKarb($view, $record, $egyed, $oper, $id)
    {
        if ($oper == 'inherit') {
            $egyed['id'] = \mkw\store::createUID();
            $egyed['parentid'] = $id;
            $kelt = date(\mkw\store::$DateFormat);
            $egyed['keltstr'] = $kelt;
            $egyed['teljesitesstr'] = $kelt;
            $egyed['esedekessegstr'] = \mkw\store::calcEsedekesseg($kelt, $record->getFizmod(), $record->getPartner());
            $egyed['reportfile'] = '';
            $view->setVar('reportfilelist', $this->getRepo()->getReportfileSelectList('', $this->getBiztipusId()));
            $ttk = [];
            $cikl = 1;
            foreach ($egyed['tetelek'] as $tetel) {
                $tetel['parentid'] = $tetel['id'];
                $tetel['id'] = \mkw\store::createUID($cikl);
                $tetel['oper'] = 'inherit';
                $ttk[] = $tetel;
                $cikl++;
            }
            $egyed['tetelek'] = $ttk;
        }
        return $egyed;
    }

    /**
     * Egy megrendelés letöltése a Mir rendelőlapjának formátumában – a lista minden
     * sorában ott a gomb, mindig a saját bizonylatát adja.
     */
    public function mirExport()
    {
        $fej = $this->getRepo()->find($this->params->getStringRequestParam('id'));
        if (!$fej) {
            http_response_code(404);
            return;
        }

        $excel = (new \Services\MirOrderExcelService())->export($fej);
        $writer = IOFactory::createWriter($excel, 'Xlsx');
        $filepath = \mkw\store::storagePath(uniqid('mirorder') . '.xlsx');
        $writer->save($filepath);

        header('Cache-Control: private');
        header('Content-Type: application/stream');
        header('Content-Length: ' . filesize($filepath));
        header('Content-Disposition: attachment; filename=' . $this->getMirFilename($fej));
        readfile($filepath);
        unlink($filepath);
    }

    private function getMirFilename($fej): string
    {
        return 'Order ' . str_replace(['/', '\\'], '-', (string)$fej->getId()) . '.xlsx';
    }

}
