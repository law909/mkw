<?php

namespace Controllers;


use mkwhelpers\FilterDescriptor;

class bizonylattetellistaController extends \mkwhelpers\Controller {

    private $tolstr;
    private $igstr;
    private $befdatumstr;
    private $datummezo;
    private $datumnev;
    private $uknev;
    private $partnernev;
    private $cimkenevek;
    private $fizmodnev;
    private $raktarnev;

    public function view() {
        $view = $this->createView('bizonylattetellista.tpl');

        $view->setVar('pagetitle', t('Bizonylattétel lista'));
        $view->setVar('datumtipus', 'teljesites');
        $rc = new raktarController($this->params);
        $view->setVar('raktarlista', $rc->getSelectList());
        $partner = new partnerController($this->params);
        $view->setVar('partnerlist', $partner->getSelectList());
        $view->setVar('gyartolist', $partner->getSzallitoSelectList(0));
        $arsav = new termekarController($this->params);
        $view->setVar('arsavlist', $arsav->getSelectList());
        $uk = new uzletkotoController($this->params);
        $view->setVar('uklist', $uk->getSelectList());
        $fm = new fizmodController($this->params);
        $view->setVar('fizmodlist', $fm->getSelectList());


        $view->setVar('nyelvlist', \mkw\store::getLocaleSelectList());

        $bsc = new bizonylatstatuszController($this->params);
        $view->setVar('bizonylatstatuszlist', $bsc->getSelectList());
        $view->setVar('bizonylatstatuszcsoportlist', $bsc->getCsoportSelectList());

        $btc = new bizonylattipusController($this->params);
        $view->setVar('bizonylattipuslist', $btc->getSelectList());

        $pcc = new partnercimkekatController($this->params);
        $view->setVar('cimkekat', $pcc->getWithCimkek());

        $view->printTemplateResult(false);

    }

    protected function getData() {
        $partnerid = $this->params->getIntRequestParam('partner');
        $raktarid = $this->params->getIntRequestParam('raktar');
        $datumtipus = $this->params->getStringRequestParam('datumtipus');
        $datumtolstr = $this->params->getStringRequestParam('tol');
        $datumigstr = $this->params->getStringRequestParam('ig');
        $ertektipus = $this->params->getIntRequestParam('ertektipus');
        $arsav = $this->params->getStringRequestParam('arsav');
        $fafilter = $this->params->getArrayRequestParam('fafilter');
        $nevfilter = $this->params->getRequestParam('nevfilter', NULL);
        $gyartoid = $this->params->getIntRequestParam('gyarto');
        $fizmodid = $this->params->getIntRequestParam('fizmod');
        $nyelv = \mkw\store::toLocale($this->params->getStringRequestParam('nyelv'));
        $bizstatusz = $this->params->getIntRequestParam('bizonylatstatusz');
        $bizstatuszcsoport = $this->params->getStringRequestParam('bizonylatstatuszcsoport');
        $bizonylattipusfilter = $this->params->getArrayRequestParam('bizonylattipus');
        $partnercimkefilter = $this->params->getArrayRequestParam('partnercimkefilter');
        $csoportositas = $this->params->getIntRequestParam('csoportositas');
        $keszletkell = $this->params->getBoolRequestParam('keszletkell');
        $uzletkotoid = $this->params->getIntRequestParam('uzletkoto');
        $csakfoglalas = $this->params->getBoolRequestParam('csakfoglalas');

        $this->tolstr = $datumtolstr;
        $this->tolstr = date(\mkw\store::$DateFormat, strtotime(\mkw\store::convDate($this->tolstr)));

        $this->igstr = $datumigstr;
        $this->igstr = date(\mkw\store::$DateFormat, strtotime(\mkw\store::convDate($this->igstr)));

        switch ($datumtipus) {
            case 'kelt':
                $this->datumnev = 'Kelt';
                break;
            case 'teljesites':
                $this->datumnev = 'Teljesítés';
                break;
            case 'esedekesseg':
                $this->datumnev = 'Esedékesség';
                break;
            case 'hatarido':
                $this->datumnev = 'Határidő';
                break;
            default:
                $this->datumnev = 'Teljesítés';
        }

        if ($partnerid) {
            $partner = $this->getRepo('Entities\Partner')->find($partnerid);
            if ($partner) {
                $this->partnernev = $partner->getNev();
            }
        }
        else {
            $this->cimkenevek = $this->getRepo('Entities\Partnercimketorzs')->getCimkeNevek($partnercimkefilter);
            $this->cimkenevek = implode(',', $this->cimkenevek);
        }

        if ($uzletkotoid) {
            $uzletkoto = $this->getRepo('Entities\Uzletkoto')->find($uzletkotoid);
            if ($uzletkoto) {
                $this->uknev = $uzletkoto->getNev();
            }
        }

        if ($raktarid) {
            $raktar = $this->getRepo('Entities\Raktar')->find($raktarid);
            if ($raktar) {
                $this->raktarnev = $raktar->getNev();
            }
        }

        if ($fizmodid) {
            $fizmod = $this->getRepo('Entities\Fizmod')->find($fizmodid);
            if ($fizmod) {
                $this->fizmodnev = $fizmod->getNev();
            }
        }

        $tetelek = $this->getRepo('Entities\Bizonylatfej')->getBizonylatTetelLista($raktarid, $partnerid, $uzletkotoid, $datumtipus, $datumtolstr, $datumigstr, $ertektipus,
            $arsav, $fafilter, $nevfilter, $gyartoid, $nyelv, $bizstatusz, $bizstatuszcsoport, $bizonylattipusfilter, $partnercimkefilter, $csoportositas, $fizmodid, $csakfoglalas);

        switch ($csoportositas) {
            case 1:
            case 2:
                $raktarfilter = new \mkwhelpers\FilterDescriptor();
                $raktarfilter->addFilter('archiv', '=', false);

                if ($keszletkell) {
                    $raktarak = $this->getRepo('Entities\Raktar')->getAll($raktarfilter);
                    foreach ($tetelek as $key => $tetel) {
                        $termekid = $tetel['termekid'];
                        $tvid = $tetel['termekvaltozat_id'];
                        if ($tvid) {
                            $tv = $this->getRepo('Entities\TermekValtozat')->find($tvid);
                            if ($tv) {
                                foreach ($raktarak as $raktar) {
                                    $keszlet = $tv->getKeszlet(null, $raktar->getId());
                                    $tetelek[$key]['keszletinfo'][$raktar->getId()] = $keszlet;
                                }
                            }
                        }
                        else {
                            if ($termekid) {
                                $tv = $this->getRepo('Entities\Termek')->find($tvid);
                                if ($tv) {
                                    foreach ($raktarak as $raktar) {
                                        $keszlet = $tv->getKeszlet(null, $raktar->getId());
                                        $tetelek[$key]['keszletinfo'][$raktar->getId()] = $keszlet;
                                    }
                                }
                            }
                        }
                    }
                }

                $raktarak = $this->getRepo('Entities\Raktar')->getAll($raktarfilter);
                $raktarlista = array();
                foreach ($raktarak as $raktar) {
                    $raktarlista[] = $raktar->getNev();
                }
                break;
            default:
                $raktarlista = array();
        }

        return array(
            'ertektipus' => $ertektipus,
            'tetelek' => $tetelek,
            'raktarlista' => $raktarlista
        );
    }

    public function refresh() {
        $csoportositas = $this->params->getIntRequestParam('csoportositas');
        $keszletkell = $this->params->getBoolRequestParam('keszletkell');

        $res = $this->getData();

        switch ($csoportositas) {
            case 1:
                $view = $this->createView('bizonylattetellistatetel.tpl');
                break;
            case 2:
                $view = $this->createView('bizonylattetellistatetelpartner.tpl');
                break;
            case 3:
                $view = $this->createView('bizonylattetellistatetelukpartner.tpl');
                break;
            case 4:
                $view = $this->createView('bizonylattetellistatetelbizonylat.tpl');
                break;
        }

        $view->setVar('raktarlista', $res['raktarlista']);
        $view->setVar('ertektipus', $res['ertektipus']);
        $view->setVar('keszletkell', $keszletkell);
        $view->setVar('tetelek', $res['tetelek']);
        $view->printTemplateResult();
    }

    public function export() {

        $csoportositas = $this->params->getIntRequestParam('csoportositas');
        $keszletkell = $this->params->getBoolRequestParam('keszletkell');

        function x($o) {
            if ($o <= 26) {
                return chr(65 + $o);
            }
            return chr(65 + floor($o / 26)) . chr(65 + ($o % 26));
        }

        $excel = new \PHPExcel();

        switch ($csoportositas) {
            case 1:
                $res = $this->getData();
                $mind = $res['tetelek'];
                $raktarlista = $res['raktarlista'];

                $excel->setActiveSheetIndex(0)
                    ->setCellValue('A1', t('Cikkszám'))
                    ->setCellValue('B1', t('Név'))
                    ->setCellValue('C1', t('Változat 1'))
                    ->setCellValue('D1', t('Változat 2'))
                    ->setCellValue('E1', t('Mennyiség'))
                    ->setCellValue('F1', t('Érték'));

                $i = 5;
                foreach ($raktarlista as $r) {
                    $i++;
                    $excel->setActiveSheetIndex(0)->setCellValue(x($i) . '1', $r);
                }

                $sor = 2;
                foreach ($mind as $item) {
                    $excel->setActiveSheetIndex(0)
                        ->setCellValue('A' . $sor, $item['cikkszam'])
                        ->setCellValue('B' . $sor, $item['nev'])
                        ->setCellValue('C' . $sor, $item['ertek1'])
                        ->setCellValue('D' . $sor, $item['ertek2'])
                        ->setCellValue('E' . $sor, $item['mennyiseg'])
                        ->setCellValue('F' . $sor, $item['ertek']);

                    if (array_key_exists('keszletinfo', $item)) {
                        $i = 5;
                        foreach ($item['keszletinfo'] as $kkey => $keszlet) {
                            $i++;
                            $excel->setActiveSheetIndex(0)->setCellValue(x($i) . $sor, $keszlet);
                        }
                    }
                    $sor++;
                }
                break;
            case 2:
                $res = $this->getData();
                $mind = $res['tetelek'];
                $raktarlista = $res['raktarlista'];

                $excel->setActiveSheetIndex(0)
                    ->setCellValue('A1', t('Partner'))
                    ->setCellValue('B1', t('Partner cím'))
                    ->setCellValue('C1', t('Cikkszám'))
                    ->setCellValue('D1', t('Név'))
                    ->setCellValue('E1', t('Változat 1'))
                    ->setCellValue('F1', t('Változat 2'))
                    ->setCellValue('G1', t('Mennyiség'))
                    ->setCellValue('H1', t('Érték'));

                $i = 7;
                foreach ($raktarlista as $r) {
                    $i++;
                    $excel->setActiveSheetIndex(0)->setCellValue(x($i) . '1', $r);
                }

                $sor = 2;
                foreach ($mind as $item) {
                    $excel->setActiveSheetIndex(0)
                        ->setCellValue('A' . $sor, $item['partnernev'])
                        ->setCellValue('B' . $sor, $item['partnerirszam'] . ' ' . $item['partnervaros'] . ' ' . $item['partnerutca'])
                        ->setCellValue('C' . $sor, $item['cikkszam'])
                        ->setCellValue('D' . $sor, $item['nev'])
                        ->setCellValue('E' . $sor, $item['ertek1'])
                        ->setCellValue('F' . $sor, $item['ertek2'])
                        ->setCellValue('G' . $sor, $item['mennyiseg'])
                        ->setCellValue('H' . $sor, $item['ertek']);

                    if (array_key_exists('keszletinfo', $item)) {
                        $i = 7;
                        foreach ($item['keszletinfo'] as $kkey => $keszlet) {
                            $i++;
                            $excel->setActiveSheetIndex(0)->setCellValue(x($i) . $sor, $keszlet);
                        }
                    }

                    $sor++;
                }
                break;
            case 3:
                $res = $this->getData();
                $mind = $res['tetelek'];

                $excel->setActiveSheetIndex(0)
                    ->setCellValue('A1', t('Üzletkötő'))
                    ->setCellValue('B1', t('Partner'))
                    ->setCellValue('C1', t('Partner cím'))
                    ->setCellValue('D1', t('Érték'));

                $sor = 2;
                foreach ($mind as $item) {
                    $excel->setActiveSheetIndex(0)
                        ->setCellValue('A' . $sor, $item['uzletkotonev'])
                        ->setCellValue('B' . $sor, $item['partnernev'])
                        ->setCellValue('C' . $sor, $item['partnerirszam'] . ' ' . $item['partnervaros'] . ' ' . $item['partnerutca'])
                        ->setCellValue('D' . $sor, $item['ertek']);

                    $sor++;
                }
                break;

        }
        $writer = \PHPExcel_IOFactory::createWriter($excel, 'Excel2007');

        $filepath = \mkw\store::storagePath(uniqid('bizonylattetel') . '.xlsx');
        $writer->save($filepath);

        $fileSize = filesize($filepath);

        // Output headers.
        header('Cache-Control: private');
        header('Content-Type: application/stream');
        header('Content-Length: ' . $fileSize);
        header('Content-Disposition: attachment; filename=' . $filepath);

        readfile($filepath);

        \unlink($filepath);
    }

    public function doPrint() {
        $csoportositas = $this->params->getIntRequestParam('csoportositas');
        $keszletkell = $this->params->getBoolRequestParam('keszletkell');

        $res = $this->getData();

        switch ($csoportositas) {
            case 1:
                $view = $this->createView('rep_bizonylattetellistatetel.tpl');
                break;
            case 2:
                $view = $this->createView('rep_bizonylattetellistatetelpartner.tpl');
                break;
            case 3:
                $view = $this->createView('rep_bizonylattetellistatetelukpartner.tpl');
                break;
        }

        $view->setVar('datumnev', $this->datumnev);
        $view->setVar('tolstr', $this->tolstr);
        $view->setVar('igstr', $this->igstr);
        $view->setVar('partnernev', $this->partnernev);
        $view->setVar('fizmodnev', $this->fizmodnev);
        $view->setVar('uknev', $this->uknev);
        $view->setVar('raktarnev', $this->raktarnev);
        $view->setVar('cimkenevek', $this->cimkenevek);
        $view->setVar('raktarlista', $res['raktarlista']);
        $view->setVar('ertektipus', $res['ertektipus']);
        $view->setVar('keszletkell', $keszletkell);
        $view->setVar('tetelek', $res['tetelek']);
        $view->printTemplateResult();

    }
}