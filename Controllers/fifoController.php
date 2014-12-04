<?php
namespace Controllers;

use mkw\store;

class fifoController extends \mkwhelpers\Controller {

    public function view() {
        $view = $this->createView('fifo.tpl');

        $view->setVar('pagetitle', t('Készletérték'));

        $view->printTemplateResult(false);
    }

    public function teszt() {
        $id = $this->params->getIntRequestParam('id');
        $vid = $this->params->getIntRequestParam('vid');
        $type = $this->params->getStringRequestParam('type', 'pre');
        $cikksz = $this->params->getStringRequestParam('cikkszam');
        $rep = \mkw\Store::getEm()->getRepository('Entities\Fifo');
        $rep->loadData($id, $vid, $cikksz);
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
        $rep = \mkw\Store::getEm()->getRepository('Entities\Fifo');
        $rep->loadData();
        $rep->calculate();
        $rep->saveData();
    }

    public function getAlapadat() {
        header("Content-type: text/csv");
        header("Pragma: no-cache");
        header("Expires: 0");

        $rep = \mkw\Store::getEm()->getRepository('Entities\Fifo');
        $fifok = $rep->getWithJoins(array(), array());
        echo implode(';', array(
            'Raktár',
            'Cikkszám',
            'Termék név',
            'Változat',
            'KI biz.szám',
            'KI teljesítés',
            'KI nettó egys.ár',
            'KI bruttó egys.ár',
            'BE biz.szám',
            'BE teljesítés',
            'BE nettó egys.ár',
            'BE bruttó egys.ár',
            'Mennyiség'
        )) . "\r\n";
        foreach($fifok as $f) {
            $raktar = $f->getRaktar();
            $termek = $f->getTermek();
            $valtozat = $f->getTermekvaltozat();
            $kifej = $f->getKibizonylatfej();
            $kitetel = $f->getKibizonylattetel();
            $befej = $f->getBebizonylatfej();
            $betetel = $f->getBebizonylattetel();
            echo implode(';', array(
                $raktar->getNev(),
                $termek->getCikkszam(),
                $termek->getNev(),
                $valtozat ? $valtozat->getNev() : '',
                $kifej ? $kifej->getId() : '',
                $kifej ? $kifej->getTeljesitesStr() : '',
                $kitetel ? $kitetel->getNettoegysar() : 0,
                $kitetel ? $kitetel->getBruttoegysar() : 0,
                $befej ? $befej->getId() : '',
                $befej ? $befej->getTeljesitesStr() : '',
                $betetel ? $betetel->getNettoegysar() : 0,
                $betetel ? $betetel->getBruttoegysar() : 0,
                $f->getMennyiseg() * ($kifej ? $kifej->getIrany() : 1)
            )) . "\r\n";
        }
    }
}