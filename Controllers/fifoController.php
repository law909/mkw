<?php
namespace Controllers;

use mkw\store;

class fifoController extends \mkwhelpers\Controller {

    public function view() {
        $view = $this->createView('fifo.tpl');

        $view->setVar('pagetitle', t('Készletérték'));

        $view->printTemplateResult(false);
    }

    private function toiso($mit, $kell) {
        if ($kell) {
            if (is_array($mit)) {
                return array_map(function($el) {
                    return mb_convert_encoding($el, 'ISO-8859-2', 'UTF8');
                }, $mit);
            }
            return mb_convert_encoding($mit, 'ISO-8859-2', 'UTF8');
        }
        return $mit;
    }

    private function num2xls($s, $kell) {
        if ($kell) {
            return str_replace('.', ',', $s);
        }
        return $s;
    }

    public function teszt() {
        $id = $this->params->getIntRequestParam('id');
        $vid = $this->params->getIntRequestParam('vid');
        $type = $this->params->getStringRequestParam('type', 'pre');
        $cikksz = $this->params->getStringRequestParam('cikkszam');
        $stornokell = $this->params->getBoolRequestParam('storno', false);
        $rep = \mkw\Store::getEm()->getRepository('Entities\Fifo');
        $rep->loadData($stornokell, $id, $vid, $cikksz);
        $rep->calculate();
        switch($type) {
            case 'pre':
                echo '<pre>' . print_r($rep->getData(), true) . '</pre>';
                break;
            case 'csv':
                header("Content-type: text/csv");
                header("Pragma: no-cache");
                header("Expires: 0");
                echo implode(';', $rep->getDataHeader()) . "\r\n";
                $d = $rep->getData();
                foreach($d as $dt) {
                    echo implode(';', $dt) . "\r\n";
                }
                break;
        }
    }

    public function calculate() {
        $stornokell = $this->params->getBoolRequestParam('storno', false);
        $rep = \mkw\Store::getEm()->getRepository('Entities\Fifo');
        $rep->loadData($stornokell);
        $rep->calculate();
        $rep->saveData();
    }

    public function getAlapadat() {
        $toexcel = $this->params->getBoolRequestParam('toexcel', false);
        $iso = $this->params->getBoolRequestParam('toiso', false);
        if ($iso) {
            header("Content-type: text/csv; charset=iso-8859-2");
        }
        else {
            header("Content-type: text/csv; charset=utf-8");
        }
        header("Pragma: no-cache");
        header("Expires: 0");

        $fp = fopen('php://output', 'w');

        $rep = \mkw\Store::getEm()->getRepository('Entities\Fifo');
        $fifok = $rep->getWithJoins(array(), array());
        fputcsv($fp, $this->toiso(array(
            'Raktár',
            'Termék id',
            'Változat id',
            'Gyártó',
            'Cikkszám',
            'Termék név',
            'Változat',
            'KI biz.szám',
            'KI er.biz.szám',
            'KI teljesítés',
            'KI partner neve',
            'KI partner címe',
            'KI belső megjegyzés',
            'KI nettó egys.ár',
            'KI bruttó egys.ár',
            'BE biz.szám',
            'BE er.biz.szám',
            'BE teljesítés',
            'BE partner neve',
            'BE belső megjegyzés',
            'BE nettó egys.ár',
            'BE bruttó egys.ár',
            'Mennyiség'
        ), $iso), ';', '"');
        //fseek($fp, -1, SEEK_CUR);
        //fwrite($fp, "\r\n");
        foreach($fifok as $f) {
            $raktar = $f->getRaktar();
            $termek = $f->getTermek();
            $valtozat = $f->getTermekvaltozat();
            $kifej = $f->getKibizonylatfej();
            $kitetel = $f->getKibizonylattetel();
            $befej = $f->getBebizonylatfej();
            $betetel = $f->getBebizonylattetel();
            fputcsv($fp, $this->toiso(array(
                $raktar->getNev(),
                $termek->getId(),
                $valtozat ? $valtozat->getId() : '',
                $termek->getGyartoNev(),
                $termek->getCikkszam(),
                $termek->getNev(),
                $valtozat ? $valtozat->getNev() : '',
                $kifej ? $kifej->getId() : '',
                $kifej ? $kifej->getErbizonylatszam() : '',
                $kifej ? $kifej->getTeljesitesStr() : '',
                $kifej ? $kifej->getPartnernev() : '',
                $kifej ? $kifej->getPartnerCim() : '',
                $kifej ? $kifej->getBelsomegjegyzes() : '',
                $kitetel ? $this->num2xls($kitetel->getNettoegysar(), $toexcel) : 0,
                $kitetel ? $this->num2xls($kitetel->getBruttoegysar(), $toexcel) : 0,
                $befej ? $befej->getId() : '',
                $befej ? $befej->getErbizonylatszam() : '',
                $befej ? $befej->getTeljesitesStr() : '',
                $befej ? $befej->getPartnernev() : '',
                $befej ? $befej->getBelsomegjegyzes() : '',
                $betetel ? $this->num2xls($betetel->getNettoegysar(), $toexcel) : 0,
                $betetel ? $this->num2xls($betetel->getBruttoegysar(), $toexcel) : 0,
                $this->num2xls($f->getMennyiseg() * ($kifej ? $kifej->getIrany() : 1), $toexcel)
            ), $iso), ';', '"');
            //fseek($fp, -1, SEEK_CUR);
            //fwrite($fp, "\r\n");
        }
        fclose($fp);
    }

    public function getKeszletertek() {
        $toexcel = $this->params->getBoolRequestParam('toexcel', false);
        $iso = $this->params->getBoolRequestParam('toiso', false);
        if ($iso) {
            header("Content-type: text/csv; charset=iso-8859-2");
        }
        else {
            header("Content-type: text/csv; charset=utf-8");
        }
        header("Pragma: no-cache");
        header("Expires: 0");

        $fp = fopen('php://output', 'w');

        $rep = \mkw\Store::getEm()->getRepository('Entities\Keszlet');
        $fifok = $rep->getWithJoins(array(), array());
        fputcsv($fp, $this->toiso(array(
            'Raktár',
            'Termék id',
            'Változat id',
            'Gyártó',
            'Cikkszám',
            'Termék név',
            'Változat',
            'BE biz.szám',
            'BE er.biz.szám',
            'BE teljesítés',
            'BE partner neve',
            'BE belső megjegyzés',
            'BE nettó egys.ár',
            'BE bruttó egys.ár',
            'Mennyiség'
        ), $iso), ';', '"');
        foreach($fifok as $f) {
            $raktar = $f->getRaktar();
            $termek = $f->getTermek();
            $valtozat = $f->getTermekvaltozat();
            $befej = $f->getBebizonylatfej();
            $betetel = $f->getBebizonylattetel();
            fputcsv($fp, $this->toiso(array(
                $raktar->getNev(),
                $termek->getId(),
                $valtozat ? $valtozat->getId() : '',
                $termek->getGyartoNev(),
                $termek->getCikkszam(),
                $termek->getNev(),
                $valtozat ? $valtozat->getNev() : '',
                $befej ? $befej->getId() : '',
                $befej ? $befej->getErbizonylatszam() : '',
                $befej ? $befej->getTeljesitesStr() : '',
                $befej ? $befej->getPartnernev() : '',
                $befej ? $befej->getBelsomegjegyzes() : '',
                $betetel ? $this->num2xls($betetel->getNettoegysar(), $toexcel) : 0,
                $betetel ? $this->num2xls($betetel->getBruttoegysar(), $toexcel) : 0,
                $this->num2xls($f->getMennyiseg(), $toexcel)
            ), $iso), ';', '"');
        }
        fclose($fp);
    }

}