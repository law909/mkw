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

        if (empty($_FILES['toimport']['tmp_name']) || !is_uploaded_file($_FILES['toimport']['tmp_name'])) {
            echo json_encode(['ok' => false, 'error' => t('Nem érkezett feltöltött fájl.')]);
            return;
        }
        $filepath = \mkw\store::storagePath(uniqid('minkeszletimport-') . '-' . basename($_FILES['toimport']['name']));
        if (!move_uploaded_file($_FILES['toimport']['tmp_name'], $filepath)) {
            echo json_encode(['ok' => false, 'error' => t('A fájl mentése nem sikerült.')]);
            return;
        }

        try {
            $eredmeny = (new \Services\MinKeszletExcelService())->import($filepath);
        } catch (\Exception $e) {
            \unlink($filepath);
            echo json_encode(['ok' => false, 'error' => $e->getMessage()]);
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
