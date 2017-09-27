<?php

namespace Controllers;

class emagController extends \mkwhelpers\Controller {

    protected function calcHash($data) {
        return sha1(http_build_query($data) . sha1(\mkw\store::getParameter(\mkw\consts::EmagPassword)));
    }

    protected function sendRequest($resource, $action, $data) {
        $requestData = array(
            'code' => \mkw\store::getParameter(\mkw\consts::EmagUsercode),
            'username' => \mkw\store::getParameter(\mkw\consts::EmagUsername),
            'data' => $data,
            'hash' => $this->calcHash($data));
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, \mkw\store::getParameter(\mkw\consts::EmagAPIUrl) . '/' . $resource . '/' . $action);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'POST');
        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($requestData));
        $result = curl_exec($ch);
        return json_decode($result, true);
    }

    protected function checkResult($res) {
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
                else {
                    \mkw\store::writelog('ERROR: ' . print_r($r['messages'], true), 'emag.txt');
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
        $excel = new \PHPExcel();
        $excel->setActiveSheetIndex(0)
            ->setCellValue('A1', 'Id')
            ->setCellValue('B1', 'Parent Id')
            ->setCellValue('C1', 'Name')
            ->setCellValue('D1', 'Allowed');

        $t = $this->getCategories();
        $sor = 2;
        foreach ($t as $elem) {
            $excel->setActiveSheetIndex(0)
                ->setCellValue(x(0) . $sor, $elem['id'])
                ->setCellValue(x(1) . $sor, $elem['parent_id'])
                ->setCellValue(x(2) . $sor, $elem['name'])
                ->setCellValue(x(3) . $sor, $elem['is_allowed']);

            $sor++;
        }
        $writer = \PHPExcel_IOFactory::createWriter($excel, 'Excel2007');

        $filepath = uniqid('emag_categories_') . '.xlsx';
        $writer->save($filepath);

        $fileSize = filesize($filepath);

        // Output headers.
        header("Cache-Control: private");
        header("Content-Type: application/stream");
        header("Content-Length: " . $fileSize);
        header("Content-Disposition: attachment; filename=" . $filepath);

        readfile($filepath);

        \unlink($filepath);
    }
}