<?php

namespace Controllers;

use Entities\Termek;
use Entities\TermekValtozat;
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

    public function getHandlingTime() {
        $params = array(
            'currentPage' => 1,
            'itemsPerPage' => 10
        );
        $r = $this->sendRequest('handling_time', 'read', $params);
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

    protected function sendProduct($data) {
        $ret = $this->sendRequest('product_offer', 'save', json_encode($data));
        return $ret;
        /*
        if ($this->checkResult($ret)) {
            return $ret['results'];
        }
        return false;
        */
    }

    public function uploadTermek() {
        $eredmeny = array();
        $tid = $this->params->getIntRequestParam('tid');
        /** @var Termek $termek */
        $termek = $this->getRepo(Termek::class)->find($tid);
        if ($termek) {
            if ($termek->getEmagtiltva()) {
                echo 'EMAG tiltva';
            }
            else {
                $valtozatok = $termek->getValtozatok();
                if (count($valtozatok)) {
                    /** @var TermekValtozat $valt */
                    foreach ($valtozatok as $valt) {
                        if (!$valt->getVonalkod()) {
                            $valt->generateVonalkod();
                            $this->getEm()->persist($valt);
                            $this->getEm()->flush();
                        }
                        $eredmeny[] = $this->sendProduct($valt->toEmag());
                    }
                }
                else {
                    if (!$termek->getVonalkod()) {
                        $termek->generateVonalkod();
                        $this->getEm()->persist($termek);
                        $this->getEm()->flush();
                    }
                    $eredmeny = $this->sendProduct($termek->toEmag());
                }
            }
        }
        echo '<pre>';
        print_r($eredmeny);
        echo '</pre>';
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

    public function printHandlingTime() {
        $t = $this->getHandlingTime();
        if ($t) {
            echo '<pre>';
            print_r($t);
            echo '</pre>';
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

    public function printCharacteristics() {
        function x($o) {
            return \mkw\store::getExcelCoordinate($o, '');
        }

        $chars = array();
        $t = $this->getCategories();
        foreach ($t as $cat) {
            foreach ($cat['characteristics'] as $char) {
                $chars[$char['id']] = $char;
            }
        }

        $excel = new Spreadsheet();
        $excel->setActiveSheetIndex(0)
            ->setCellValue('A1', 'Id')
            ->setCellValue('B1', 'Name')
            ->setCellValue('C1', 'Type id')
            ->setCellValue('D1', 'Display order')
            ->setCellValue('E1', 'Is mandatory')
            ->setCellValue('F1', 'Is mandatory for mktp')
            ->setCellValue('G1', 'Allow new value')
            ->setCellValue('H1', 'Is filter')
        ;

        $sor = 2;
        foreach ($chars as $elem) {

            $excel->setActiveSheetIndex(0)
                ->setCellValue(x(0) . $sor, $elem['id'])
                ->setCellValue(x(1) . $sor, $elem['name'])
                ->setCellValue(x(2) . $sor, $elem['type_id'])
                ->setCellValue(x(3) . $sor, $elem['display_order'])
                ->setCellValue(x(4) . $sor, $elem['is_mandatory'])
                ->setCellValue(x(5) . $sor, $elem['is_mandatory_for_mktp'])
                ->setCellValue(x(6) . $sor, $elem['allow_new_value'])
                ->setCellValue(x(7) . $sor, $elem['is_filter'])
            ;
            $sor++;
        }
        $writer = IOFactory::createWriter($excel, 'Xlsx');

        $filename = uniqid('emag_characteristics_') . '.xlsx';
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

    public function printTermek() {
        $tid = $this->params->getIntRequestParam('tid');
        /** @var Termek $termek */
        $termek = $this->getRepo(Termek::class)->find($tid);
        if ($termek) {
            if ($termek->getEmagtiltva()) {
                echo 'EMAG tiltva';
            }
            else {
                $valtozatok = $termek->getValtozatok();
                if (count($valtozatok)) {
                    foreach ($valtozatok as $valt) {
                        echo '<pre>';
                        print_r($valt->toEmag());
                        echo '</pre>';
                        echo '<br><br><br>';
                    }
                }
                else {
                    echo '<pre>';
                    print_r($termek->toEmag());
                    echo '</pre>';
                }
            }
        }
    }
}

/*
product mezők
- part number = cikkszám nem jó, egyedinek kell lennie
- warranty, integer hónapok száma
- vonalkod terméknek és változatnak is kell; importáláskor is kell generálni új felvitelkopr
- sale price = eladási ár + 10%
- min sale price = sale price
- max sale price = min sale price + 20%
- handling time = value:1
- supply lead time = 7



    Mi az a product family? példa? dokumentáció?
    Kötött characteristic értékeit honnan tudjuk meg?
    part_number_key erteke mi?
    EMAG documentation standard/EMAG product documentation standard emlitve van API doksiban, de hol talalhato?
*/