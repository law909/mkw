<?php

namespace Traits;

use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Spreadsheet;

trait PartnerExport
{
    public function megjegyzesExport()
    {
        function x($o, $sor)
        {
            return \mkw\store::getExcelCoordinate($o, $sor);
        }

        $ids = $this->params->getStringRequestParam('ids');

        $filter = new \mkwhelpers\FilterDescriptor();
        if ($ids) {
            $filter->addFilter('id', 'IN', explode(',', $ids));
        }

        $partnerek = $this->getRepo()->getAll($filter, ['vezeteknev' => 'ASC', 'keresztnev' => 'ASC']);

        $o = 0;
        $excel = new Spreadsheet();
        $excel->setActiveSheetIndex(0)
            ->setCellValue(x($o++, 1), 'Név')
            ->setCellValue(x($o++, 1), 'Vezetéknév')
            ->setCellValue(x($o++, 1), 'Keresztnév')
            ->setCellValue(x($o++, 1), 'Nyelv')
            ->setCellValue(x($o++, 1), 'Email')
            ->setCellValue(x($o++, 1), 'Telefon')
            ->setCellValue(x($o++, 1), 'Megjegyzés');

        if ($partnerek) {
            $sor = 2;
            /** @var \Entities\Partner $partner */
            foreach ($partnerek as $partner) {
                $o = 0;
                $excel->setActiveSheetIndex(0)
                    ->setCellValue(x($o++, $sor), $partner->getNev())
                    ->setCellValue(x($o++, $sor), $partner->getVezeteknev())
                    ->setCellValue(x($o++, $sor), $partner->getKeresztnev())
                    ->setCellValue(x($o++, $sor), $partner->getBizonylatnyelv())
                    ->setCellValue(x($o++, $sor), $partner->getEmail())
                    ->setCellValue(x($o++, $sor), $partner->getTelefon())
                    ->setCellValue(x($o++, $sor), $partner->getMegjegyzes());

                $sor++;
            }
        }
        $writer = IOFactory::createWriter($excel, 'Xlsx');

        $filepath = \mkw\store::storagePath(uniqid('partnermegjegyzes') . '.xlsx');
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

    public function hirlevelExport()
    {
        function x($o, $sor)
        {
            return \mkw\store::getExcelCoordinate($o, $sor);
        }

        $filter = new \mkwhelpers\FilterDescriptor();
        $filter->addFilter('akcioshirlevelkell', '=', true);
        $filter->addFilter('ujdonsaghirlevelkell', '=', true);

        $partnerek = $this->getRepo()->getAll($filter, ['vezeteknev' => 'ASC', 'keresztnev' => 'ASC']);

        $o = 0;
        $excel = new Spreadsheet();
        $excel->setActiveSheetIndex(0)
            ->setCellValue(x($o++, 1), 'Vezetéknév')
            ->setCellValue(x($o++, 1), 'Keresztnév')
            ->setCellValue(x($o++, 1), 'Név')
            ->setCellValue(x($o++, 1), 'Email')
            ->setCellValue(x($o++, 1), 'Akciós hírlevél')
            ->setCellValue(x($o++, 1), 'Újdonság hírlevél');

        if ($partnerek) {
            $sor = 2;
            /** @var \Entities\Partner $partner */
            foreach ($partnerek as $partner) {
                $o = 0;
                $excel->setActiveSheetIndex(0)
                    ->setCellValue(x($o++, $sor), $partner->getVezeteknev())
                    ->setCellValue(x($o++, $sor), $partner->getKeresztnev())
                    ->setCellValue(x($o++, $sor), $partner->getNev())
                    ->setCellValue(x($o++, $sor), $partner->getEmail())
                    ->setCellValue(x($o++, $sor), $partner->getAkcioshirlevelkell())
                    ->setCellValue(x($o++, $sor), $partner->getUjdonsaghirlevelkell());

                $sor++;
            }
        }
        $writer = IOFactory::createWriter($excel, 'Xlsx');

        $filepath = \mkw\store::storagePath(uniqid('partnerhirlevel') . '.xlsx');
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

    public function mptngyszamlazasExport()
    {
        function x($o, $sor)
        {
            return \mkw\store::getExcelCoordinate($o, $sor);
        }

        $ids = $this->params->getStringRequestParam('ids');

        $filter = new \mkwhelpers\FilterDescriptor();
        if ($ids) {
            $filter->addFilter('id', 'IN', explode(',', $ids));
        }

        $partnerek = $this->getRepo()->getAll($filter, ['nev' => 'ASC']);

        $o = 0;
        $excel = new Spreadsheet();
        $excel->setActiveSheetIndex(0)
            ->setCellValue(x($o++, 1), 'Név')
            ->setCellValue(x($o++, 1), 'Telefon')
            ->setCellValue(x($o++, 1), 'Email')
            ->setCellValue(x($o++, 1), 'Számlázási név')
            ->setCellValue(x($o++, 1), 'Számlázási cím')
            ->setCellValue(x($o++, 1), 'Munkahely')
            ->setCellValue(x($o++, 1), 'Csoportos fizetés')
            ->setCellValue(x($o++, 1), 'Kapcsolat név')
            ->setCellValue(x($o++, 1), 'Nem vesz részt, csak szerző')
            ->setCellValue(x($o++, 1), 'MPT tag')
            ->setCellValue(x($o++, 1), 'Diák')
            ->setCellValue(x($o++, 1), 'Nyugdíjas');

        if ($partnerek) {
            $sor = 2;
            /** @var \Entities\Partner $partner */
            foreach ($partnerek as $partner) {
                $o = 0;
                $excel->setActiveSheetIndex(0)
                    ->setCellValue(x($o++, $sor), $partner->getNev())
                    ->setCellValue(x($o++, $sor), $partner->getTelefon())
                    ->setCellValue(x($o++, $sor), $partner->getEmail())
                    ->setCellValue(x($o++, $sor), $partner->getSzlanev())
                    ->setCellValue(x($o++, $sor), $partner->getCim())
                    ->setCellValue(x($o++, $sor), $partner->getMptMunkahelynev())
                    ->setCellValue(x($o++, $sor), $partner->getMptngycsoportosfizetes())
                    ->setCellValue(x($o++, $sor), $partner->getMptngykapcsolatnev())
                    ->setCellValue(x($o++, $sor), $partner->isMptngynemveszreszt())
                    ->setCellValue(x($o++, $sor), $partner->isMptngympttag())
                    ->setCellValue(x($o++, $sor), $partner->isMptngydiak())
                    ->setCellValue(x($o++, $sor), $partner->isMptngynyugdijas());

                $sor++;
            }
        }
        $writer = IOFactory::createWriter($excel, 'Xlsx');

        $filepath = \mkw\store::storagePath(uniqid('mptngypartnerszamlazas') . '.xlsx');
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
