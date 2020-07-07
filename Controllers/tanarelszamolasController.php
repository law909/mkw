<?php

namespace Controllers;

use mkwhelpers\FilterDescriptor;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;

class tanarelszamolasController extends \mkwhelpers\Controller {

    private $tolstr;
    private $igstr;

    public function view() {
        $view = $this->createView('tanarelszamolas.tpl');

        $view->setVar('pagetitle', t('Tanár elszámolás'));

        $view->printTemplateResult(false);

    }

    public function getData($ptol = null, $pig = null) {

        if (!$ptol) {
            $ptol = $this->params->getStringRequestParam('tol');
        }
        if (!$pig) {
            $pig = $this->params->getStringRequestParam('ig');
        }

        $tol = new \DateTime(\mkw\store::convDate($ptol));
        $ig = new \DateTime(\mkw\store::convDate($pig));
        $kul = $tol->diff($ig);
        $hokulonbseg = $kul->y * 12 + $kul->m;

        $tolstr = date(\mkw\store::$DateFormat, strtotime(\mkw\store::convDate($ptol)));
        $igstr = date(\mkw\store::$DateFormat, strtotime(\mkw\store::convDate($pig)));

        $filter = new FilterDescriptor();
        if ($tolstr) {
            $filter->addFilter('_xx.datum', '>=', $tolstr);
        }
        if ($igstr) {
            $filter->addFilter('_xx.datum', '<=', $igstr);
        }

        $filter->addFilter('_xx.tisztaznikell', '=', false);

        $tetelek = $this->getRepo('Entities\JogaReszvetel')->getTanarOsszesito($filter, $hokulonbseg);

        return $tetelek;
    }

    protected function reszletezoExport() {

        function x($o) {
            if ($o <= 26) {
                return chr(65 + $o);
            }
            return chr(65 + floor($o / 26)) . chr(65 + ($o % 26));
        }

        $tol = new \DateTime(\mkw\store::convDate($this->params->getStringRequestParam('tol')));
        $ig = new \DateTime(\mkw\store::convDate($this->params->getStringRequestParam('ig')));
        $kul = $tol->diff($ig);
        $hokulonbseg = $kul->y * 12 + $kul->m;

        $tolstr = date(\mkw\store::$DateFormat, strtotime(\mkw\store::convDate($this->params->getStringRequestParam('tol'))));
        $igstr = date(\mkw\store::$DateFormat, strtotime(\mkw\store::convDate($this->params->getStringRequestParam('ig'))));
        $tanarid = $this->params->getIntRequestParam('id');
        $tanar = $this->getRepo('\Entities\Dolgozo')->find($tanarid);
        if ($tanar) {
            $tanarnev = $tanar->getNev();
        }

        $filter = new FilterDescriptor();
        if ($tolstr) {
            $filter->addFilter('_xx.datum', '>=', $tolstr);
        }
        if ($igstr) {
            $filter->addFilter('_xx.datum', '<=', $igstr);
        }
        $filter->addFilter('_xx.tanar', '=', $tanarid);

        $adat = $this->getRepo('Entities\JogaReszvetel')->getWithJoins($filter, arraY('datum' => 'ASC'));

        $excel = new Spreadsheet();

        $excel->setActiveSheetIndex(0)
            ->setCellValue('A1', t('Dátum'))
            ->setCellValue('B1', t('Nap'))
            ->setCellValue('C1', t('Név'))
            ->setCellValue('D1', t('Jegy típus'))
            ->setCellValue('E1', t('Jutalék'));

        $sor = 2;
        /** @var \Entities\JogaReszvetel $item */
        foreach ($adat as $item) {
            $fm = $item->getFizmod();
            if (\mkw\store::isAYCMFizmod($fm)) {
                $termeknev = 'AYCM';
            }
            else {
                $termeknev = $item->getTermekNev();
            }
            $excel->setActiveSheetIndex(0)
                ->setCellValue('A' . $sor, $item->getDatumStr())
                ->setCellValue('B' . $sor, $item->getDatumNapnev())
                ->setCellValue('C' . $sor, $item->getPartnerNev())
                ->setCellValue('D' . $sor, $termeknev)
                ->setCellValue('E' . $sor, $item->getJutalek());

            $sor++;
        }
        $excel->setActiveSheetIndex(0)
            ->setCellValue('A' . $sor, '')
            ->setCellValue('B' . $sor, '')
            ->setCellValue('C' . $sor, '')
            ->setCellValue('D' . $sor, 'Járulék levonás')
            ->setCellValue('E' . $sor, $tanar->getHavilevonas() * -1 * $hokulonbseg);

        $sor++;
        $excel->setActiveSheetIndex(0)
            ->setCellValue('A' . $sor, 'Összesen')
            ->setCellValue('E' . $sor, '=SUM(E2:E' . ($sor - 1) . ')');

        $excel->getActiveSheet()->getStyle('E1')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_RIGHT);
        $excel->getActiveSheet()->getStyle('A1:E1')->applyFromArray(
            [
                'font' => [
                    'bold' => true,
                ],
                'borders' => [
                    'bottom' => [
                        'borderStyle' => Border::BORDER_THIN,
                    ],
                ],
            ]
        );
        $excel->getActiveSheet()->getStyle('A' . $sor . ':E' . $sor)->applyFromArray(
            [
                'font' => [
                    'bold' => true,
                ],
                'borders' => [
                    'top' => [
                        'borderStyle' => Border::BORDER_THIN,
                    ],
                ],
            ]
        );
        $excel->getActiveSheet()->getColumnDimension('A')->setAutoSize(true);
        $excel->getActiveSheet()->getColumnDimension('B')->setAutoSize(true);
        $excel->getActiveSheet()->getColumnDimension('C')->setAutoSize(true);
        $excel->getActiveSheet()->getColumnDimension('D')->setAutoSize(true);
        $excel->getActiveSheet()->getColumnDimension('E')->setAutoSize(true);

        $writer = IOFactory::createWriter($excel, 'Xlsx');

        $filepath = \mkw\store::storagePath($tanarnev . ' ' . $tolstr . '-' . $igstr . '.xlsx');
        $writer->save($filepath);

        return $filepath;

    }

    public function refresh() {

        $res = $this->getData();

        $view = $this->createView('tanarelszamolastanarsum.tpl');

        $view->setVar('tetelek', $res);
        $view->setVar('tol', $this->params->getStringRequestParam('tol'));
        $view->setVar('ig', $this->params->getStringRequestParam('ig'));
        $view->printTemplateResult();
    }

    public function reszletezo() {

        $filepath = $this->reszletezoExport();
        $fileSize = filesize($filepath);

        // Output headers.
        header('Cache-Control: private');
        header('Content-Type: application/stream');
        header('Content-Length: ' . $fileSize);
        header('Content-Disposition: attachment; filename=' . $filepath);

        readfile($filepath);

        \unlink($filepath);
    }

    public function export() {

        function x($o) {
            if ($o <= 26) {
                return chr(65 + $o);
            }
            return chr(65 + floor($o / 26)) . chr(65 + ($o % 26));
        }

        $excel = new Spreadsheet();

        $res = $this->getData();
        $mind = $res['tetelek'];

        $excel->setActiveSheetIndex(0)
            ->setCellValue('A1', t('Cikkszám'))
            ->setCellValue('B1', t('Név'))
            ->setCellValue('C1', t('Változat 1'))
            ->setCellValue('D1', t('Változat 2'))
            ->setCellValue('E1', t('Mennyiség'))
            ->setCellValue('F1', t('Érték'));

        $sor = 2;
        foreach ($mind as $item) {
            $excel->setActiveSheetIndex(0)
                ->setCellValue('A' . $sor, $item['cikkszam'])
                ->setCellValue('B' . $sor, $item['nev'])
                ->setCellValue('C' . $sor, $item['ertek1'])
                ->setCellValue('D' . $sor, $item['ertek2'])
                ->setCellValue('E' . $sor, $item['mennyiseg'])
                ->setCellValue('F' . $sor, $item['ertek']);

            $sor++;
        }

        $writer = IOFactory::createWriter($excel, 'Xlsx');

        $filepath = \mkw\store::storagePath(uniqid('tanarelszamolas') . '.xlsx');
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

        $res = $this->getData();

        $view = $this->createView('rep_bizonylattetellistatetel.tpl');

        $view->setVar('tolstr', $this->tolstr);
        $view->setVar('igstr', $this->igstr);
        $view->setVar('tetelek', $res['tetelek']);
        $view->printTemplateResult();
    }

    public function sendEmail() {

        $tanarid = $this->params->getIntRequestParam('id');
        $tolstr = date(\mkw\store::$DateFormat, strtotime(\mkw\store::convDate($this->params->getStringRequestParam('tol'))));
        $igstr = date(\mkw\store::$DateFormat, strtotime(\mkw\store::convDate($this->params->getStringRequestParam('ig'))));
        /** @var \Entities\Dolgozo $tanar */
        $tanar = $this->getRepo('\Entities\Dolgozo')->find($tanarid);
        if ($tanar) {
            $tanaremail = $tanar->getEmail();
            $tanarnev = $tanar->getNev();
        }
        $emailtpl = $this->getRepo('\Entities\Emailtemplate')->find(\mkw\store::getParameter(\mkw\consts::JogaTanarelszamolasSablon));
        if ($tanaremail && $emailtpl) {
            $filepath = $this->reszletezoExport();

            $subject = \mkw\store::getTemplateFactory()->createMainView('string:' . $emailtpl->getTargy());
            $subject->setVar('tol', $tolstr);
            $subject->setVar('ig', $igstr);
            $body = \mkw\store::getTemplateFactory()->createMainView('string:' . str_replace('&#39;', '\'', html_entity_decode($emailtpl->getHTMLSzoveg())));
            $body->setVar('tol', $tolstr);
            $body->setVar('ig', $igstr);
            $body->setVar('nev', $tanarnev);

            $mailer = \mkw\store::getMailer();

            $mailer->setAttachment($filepath);
            $mailer->addTo($tanaremail);
            $mailer->setSubject($subject->getTemplateResult());
            $mailer->setMessage($body->getTemplateResult());

            $mailer->send();

            \unlink($filepath);

        }
    }
}