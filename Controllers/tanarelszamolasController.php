<?php

namespace Controllers;

use Entities\Dolgozo;
use mkwhelpers\FilterDescriptor;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;

class tanarelszamolasController extends \mkwhelpers\Controller
{

    private $tolstr;
    private $igstr;

    public function view()
    {
        $view = $this->createView('tanarelszamolas.tpl');

        $view->setVar('pagetitle', t('Tanár elszámolás'));

        $view->printTemplateResult(false);
    }

    public function getData($tanarid = null, $ptol = null, $pig = null)
    {
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
        if ($tanarid) {
            $filter->addFilter('_xx.tanar', '=', $tanarid);
        }
        $filter->addFilter('_xx.tisztaznikell', '=', false);

        $tetelek = $this->getRepo('Entities\JogaReszvetel')->getTanarOsszesito($filter, $hokulonbseg);

        return $tetelek;
    }

    protected function reszletezoExport()
    {
        function x($o)
        {
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
        /** @var Dolgozo $tanar */
        $tanar = $this->getRepo(Dolgozo::class)->find($tanarid);
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

        $adat = $this->getRepo('Entities\JogaReszvetel')->getWithJoins($filter, array('datum' => 'ASC'));

        $excel = new Spreadsheet();

        $excel->setActiveSheetIndex(0)
            ->setCellValue('A1', t('Dátum'))
            ->setCellValue('B1', t('Nap'))
            ->setCellValue('C1', t('Név'))
            ->setCellValue('D1', t('Jegy típus'))
            ->setCellValue('E1', t('Jutalék'));

        $napokszama = [];
        $sor = 2;
        /** @var \Entities\JogaReszvetel $item */
        foreach ($adat as $item) {
            $napokszama[$item->getDatumStr()] = 1;
            $fm = $item->getFizmod();
            if (\mkw\store::isAYCMFizmod($fm)) {
                $termeknev = 'AYCM';
            } else {
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
            ->setCellValue('D' . $sor, 'Havi járulék levonás')
            ->setCellValue('E' . $sor, $tanar->getHavilevonas() * -1 * $hokulonbseg);
        $sor++;
        $excel->setActiveSheetIndex(0)
            ->setCellValue('A' . $sor, '')
            ->setCellValue('B' . $sor, '')
            ->setCellValue('C' . $sor, '')
            ->setCellValue('D' . $sor, 'Napi járulék levonás')
            ->setCellValue('E' . $sor, $tanar->getNapilevonas() * -1 * count($napokszama));
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

    public function refresh()
    {
        $res = $this->getData();

        $view = $this->createView('tanarelszamolastanarsum.tpl');

        $view->setVar('tetelek', $res);
        $view->setVar('tol', $this->params->getStringRequestParam('tol'));
        $view->setVar('ig', $this->params->getStringRequestParam('ig'));
        $view->printTemplateResult();
    }

    public function reszletezo()
    {
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

    public function sendEmail()
    {
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
            $adat = $this->getData($tanarid);

            $subject = \mkw\store::getTemplateFactory()->createMainView('string:' . $emailtpl->getTargy());
            $subject->setVar('tol', $tolstr);
            $subject->setVar('ig', $igstr);
            $body = \mkw\store::getTemplateFactory()->createMainView('string:' . str_replace('&#39;', '\'', html_entity_decode($emailtpl->getHTMLSzoveg())));
            $body->setVar('tol', $tolstr);
            $body->setVar('ig', $igstr);
            $body->setVar('nev', $tanarnev);
            $body->setVar('fizmodtipus', $tanar->getFizmodTipus());

            /** @var \Entities\Valutanem $defavaluta */
            $defavaluta = \mkw\store::getEm()->getRepository('Entities\Valutanem')->find(\mkw\store::getParameter(\mkw\consts::Valutanem));
            $defakerekit = true;
            $mincimlet = 0;
            if ($defavaluta) {
                $defakerekit = $defavaluta->getKerekit();
                $mincimlet = $defavaluta->getMincimlet();
            }
            $osszeg = $adat[0]['jutalek'];
            if ($defakerekit) {
                $osszeg = round($osszeg);
            }
            if ($tanar->getFizmodTipus() === 'P') {
                $osszeg = \mkw\store::kerekit($osszeg, $mincimlet);
            }
            $body->setVar('osszeg', $osszeg);

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
