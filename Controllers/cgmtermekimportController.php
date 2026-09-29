<?php

namespace Controllers;

/**
 * CGM termékfeltöltő XLSX importja; a tartalmi munka a \Services\CgmTermekImportService-ben van.
 */
class cgmtermekimportController extends \mkwhelpers\Controller
{

    public function view()
    {
        $view = $this->createView('cgmtermekimport.tpl');
        $view->setVar('pagetitle', t('CGM termék import'));
        $view->printTemplateResult();
    }

    public function import()
    {
        header('Content-Type: application/json; charset=utf-8');

        $file = $_FILES['toimport'] ?? null;
        $hiba = \mkwhelpers\UploadError::postMaxSizeMessage()
            ?? (!$file ? \mkwhelpers\UploadError::noFileMessage() : \mkwhelpers\UploadError::uploadMessage($file['error']));
        if ($hiba !== null) {
            $this->jsonFail($hiba);
            return;
        }
        $filepath = \mkw\store::moveUploadedFile('toimport', 'cgmtermekimport', ['xlsx', 'xls']);
        if (!$filepath) {
            $this->jsonFail(t('Csak .xlsx vagy .xls fájl tölthető fel.'));
            return;
        }

        try {
            $eredmeny = (new \Services\CgmTermekImportService())->import($filepath);
        } catch (\Exception $e) {
            \unlink($filepath);
            $this->jsonFail($e->getMessage());
            return;
        }
        \unlink($filepath);

        echo json_encode([
            'ok' => true,
            'msg' => sprintf(
                t('%d sor feldolgozva: %d új termék, %d új változat. Már létezett: %d termék, %d változat.'),
                $eredmeny['sorok'],
                $eredmeny['termek'],
                $eredmeny['valtozat'],
                $eredmeny['letezotermek'],
                $eredmeny['letezovaltozat']
            ),
            'hibak' => $eredmeny['hibak'],
        ]);
    }

}
