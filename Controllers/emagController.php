<?php

namespace Controllers;

use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Spreadsheet;

class emagController extends \mkwhelpers\Controller {

    protected function sendRequest($resource, $action, $data) {
        $requestData = array(
            'data' => $data
        );
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, \mkw\store::getParameter(\mkw\consts::EmagAPIUrl) . '/' . $resource . '/' . $action);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
        curl_setopt($ch, CURLOPT_USERPWD, \mkw\store::getParameter(\mkw\consts::EmagUsername) . ":" . \mkw\store::getParameter(\mkw\consts::EmagPassword));
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'POST');
        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($requestData));
        $result = curl_exec($ch);
        return json_decode($result, true);
    }

    protected function checkResult($res) {
        if (array_key_exists('isError', $res) && $res['isError']) {
            \mkw\store::writelog(print_r($res['messages'], true), 'emag.txt');
        }
        return array_key_exists('isError', $res) && !$res['isError'];
    }

    public function getVAT() {
        $params = array(
            'currentPage' => 1,
            'itemsPerPage' => 10
        );
        $r = $this->sendRequest('vat', 'read', $params);
        if ($this->checkResult($r)) {
            return $r['results'];
        }
        return false;
    }

    public function countCategories() {
        $ret = $this->sendRequest('category', 'count', array());
        if ($this->checkResult($ret)) {
            return $ret['results'];
        }
        return false;
    }

    public function getCategories() {
        $count = $this->countCategories();
        $cats = array();
        if ($count !== false) {
            $szor = $count['noOfPages'];
            for ($i = 0; $i < $szor; $i++) {
                $r = $this->sendRequest(
                    'category',
                    'read',
                    array(
                        'currentPage' => $i,
                        'itemsPerPage' => $count['itemsPerPage']
                    )
                );
                if ($this->checkResult($r)) {
                    foreach ($r['results'] as $elem) {
                        $cats[] = $elem;
                    }
                }
            }
        }
        return $cats;
    }

    public function printVAT() {
        $t = $this->getVAT();
        if ($t) {
            echo '<table><thead><tr><td>Id</td><td>VAT Rate</td></tr></thead><tbody>';
            foreach ($t as $vat) {
                echo '<tr>';
                echo '<td>' . $vat['vat_id'] . '</td>';
                echo '<td>' . $vat['vat_rate'] . '</td>';
                echo '</tr>';
            }
            echo '</tbody></table>';
        }
    }

    public function printCategories() {
        function x($o) {
            return \mkw\store::getExcelCoordinate($o, '');
        }
        $excel = new Spreadsheet();
        $excel->setActiveSheetIndex(0)
            ->setCellValue('A1', 'Id')
            ->setCellValue('B1', 'Parent Id')
            ->setCellValue('C1', 'Name')
            ->setCellValue('D1', 'Allowed')
            ->setCellValue('E1', 'EAN mandatory')
            ->setCellValue('F1', 'Warranty mandatory')
        ;

        $t = $this->getCategories();
        $sor = 2;
        foreach ($t as $elem) {

            \mkw\store::writelog(print_r($elem, true), 'emag.txt');

            $excel->setActiveSheetIndex(0)
                ->setCellValue(x(0) . $sor, $elem['id'])
                ->setCellValue(x(1) . $sor, $elem['parent_id'])
                ->setCellValue(x(2) . $sor, $elem['name'])
                ->setCellValue(x(3) . $sor, $elem['is_allowed'])
                ->setCellValue(x(4) . $sor, $elem['is_ean_mandatory'])
                ->setCellValue(x(5) . $sor, $elem['is_warranty_mandatory'])
            ;
            $sor++;
        }
        $writer = IOFactory::createWriter($excel, 'Xlsx');

        $filename = uniqid('emag_categories_') . '.xlsx';
        $filepath = \mkw\store::storagePath($filename);
        $writer->save($filepath);

        $fileSize = filesize($filepath);

        // Output headers.
        header("Cache-Control: private");
        header("Content-Type: application/stream");
        header("Content-Length: " . $fileSize);
        header("Content-Disposition: attachment; filename=" . $filename);

        readfile($filepath);

        \unlink($filepath);
    }
}