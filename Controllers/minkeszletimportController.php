<?php

namespace Controllers;

/**
 * A minimum készletek visszatöltése a termékselect "Minimum készlet export" csoportos
 * műveletével készült Excelből. A tartalmi munka a \Services\MinKeszletExcelService-ben van.
 */
class minkeszletimportController extends \mkwhelpers\Controller
{

    public function view()
    {
        $view = $this->createView('minkeszletimport.tpl');
        $view->setVar('pagetitle', t('Minimum készlet import'));
        $view->printTemplateResult();
    }

    public function import()
    {
        header('Content-Type: application/json; charset=utf-8');

        $filepath = \mkw\store::moveUploadedFile('toimport', 'minkeszletimport');
        if (!$filepath) {
            $this->jsonFail(t('Hiányzó vagy nem elfogadott típusú fájl.'));
            return;
        }

        try {
            $eredmeny = (new \Services\MinKeszletExcelService())->import($filepath);
        } catch (\Exception $e) {
            \unlink($filepath);
            $this->jsonFail($e->getMessage());
            return;
        }
        \unlink($filepath);

        echo json_encode([
            'ok' => true,
            'msg' => sprintf(
                t('%d sor feldolgozva: %d termék és %d változat minimum készlete frissült.'),
                $eredmeny['sorok'],
                $eredmeny['termek'],
                $eredmeny['valtozat']
            ),
            'hibak' => $eredmeny['hibak'],
        ]);
    }

}
