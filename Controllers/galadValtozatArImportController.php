<?php

namespace Controllers;

class galadValtozatArImportController extends \mkwhelpers\Controller
{

    public function import()
    {
        header('Content-Type: application/json; charset=utf-8');
        @set_time_limit(600);

        $file = $_FILES['toimport'] ?? null;
        $hiba = \mkwhelpers\UploadError::postMaxSizeMessage()
            ?? (!$file ? \mkwhelpers\UploadError::noFileMessage() : \mkwhelpers\UploadError::uploadMessage($file['error']));
        if ($hiba !== null) {
            $this->jsonFail($hiba);
            return;
        }
        $filepath = \mkw\store::moveUploadedFile('toimport', 'galadvaltozatar', ['xlsx', 'xls']);
        if (!$filepath) {
            $this->jsonFail(t('Csak .xlsx vagy .xls fájl tölthető fel.'));
            return;
        }

        try {
            $eredmeny = (new \Services\GaladValtozatArImportService())->import($filepath);
        } catch (\Exception $e) {
            \unlink($filepath);
            $this->jsonFail($e->getMessage());
            return;
        }
        \unlink($filepath);

        echo json_encode(['ok' => true] + $eredmeny, JSON_UNESCAPED_UNICODE);
    }
}
