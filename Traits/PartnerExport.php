<?php

namespace Traits;

use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Spreadsheet;

trait PartnerExport
{
    private function x($o, $sor)
    {
        return \mkw\store::getExcelCoordinate($o, $sor);
    }

    public function megjegyzesExport()
    {
        $ids = $this->params->getStringRequestParam('ids');

        $filter = new \mkwhelpers\FilterDescriptor();
        if ($ids) {
            $filter->addFilter('id', 'IN', explode(',', $ids));
        }

        $partnerek = $this->getRepo()->getAll($filter, ['vezeteknev' => 'ASC', 'keresztnev' => 'ASC']);

        $o = 0;
        $excel = new Spreadsheet();
        $excel->setActiveSheetIndex(0)
            ->setCellValue($this->x($o++, 1), 'Név')
            ->setCellValue($this->x($o++, 1), 'Vezetéknév')
            ->setCellValue($this->x($o++, 1), 'Keresztnév')
            ->setCellValue($this->x($o++, 1), 'Nyelv')
            ->setCellValue($this->x($o++, 1), 'Email')
            ->setCellValue($this->x($o++, 1), 'Telefon')
            ->setCellValue($this->x($o++, 1), 'Megjegyzés');

        if ($partnerek) {
            $sor = 2;
            /** @var \Entities\Partner $partner */
            foreach ($partnerek as $partner) {
                $o = 0;
                $excel->setActiveSheetIndex(0)
                    ->setCellValue($this->x($o++, $sor), $partner->getNev())
                    ->setCellValue($this->x($o++, $sor), $partner->getVezeteknev())
                    ->setCellValue($this->x($o++, $sor), $partner->getKeresztnev())
                    ->setCellValue($this->x($o++, $sor), $partner->getBizonylatnyelv())
                    ->setCellValue($this->x($o++, $sor), $partner->getEmail())
                    ->setCellValue($this->x($o++, $sor), $partner->getTelefon())
                    ->setCellValue($this->x($o++, $sor), $partner->getMegjegyzes());

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
        $filter = new \mkwhelpers\FilterDescriptor();
        $filter->addFilter('akcioshirlevelkell', '=', true);
        $filter->addFilter('ujdonsaghirlevelkell', '=', true);

        $partnerek = $this->getRepo()->getAll($filter, ['vezeteknev' => 'ASC', 'keresztnev' => 'ASC']);

        $o = 0;
        $excel = new Spreadsheet();
        $excel->setActiveSheetIndex(0)
            ->setCellValue($this->x($o++, 1), 'Vezetéknév')
            ->setCellValue($this->x($o++, 1), 'Keresztnév')
            ->setCellValue($this->x($o++, 1), 'Név')
            ->setCellValue($this->x($o++, 1), 'Email')
            ->setCellValue($this->x($o++, 1), 'Akciós hírlevél')
            ->setCellValue($this->x($o++, 1), 'Újdonság hírlevél');

        if ($partnerek) {
            $sor = 2;
            /** @var \Entities\Partner $partner */
            foreach ($partnerek as $partner) {
                $o = 0;
                $excel->setActiveSheetIndex(0)
                    ->setCellValue($this->x($o++, $sor), $partner->getVezeteknev())
                    ->setCellValue($this->x($o++, $sor), $partner->getKeresztnev())
                    ->setCellValue($this->x($o++, $sor), $partner->getNev())
                    ->setCellValue($this->x($o++, $sor), $partner->getEmail())
                    ->setCellValue($this->x($o++, $sor), $partner->getAkcioshirlevelkell())
                    ->setCellValue($this->x($o++, $sor), $partner->getUjdonsaghirlevelkell());

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
        $ids = $this->params->getStringRequestParam('ids');

        $filter = new \mkwhelpers\FilterDescriptor();
        if ($ids) {
            $filter->addFilter('id', 'IN', explode(',', $ids));
        }

        $partnerek = $this->getRepo()->getAll($filter, ['nev' => 'ASC']);

        $o = 0;
        $excel = new Spreadsheet();
        $excel->setActiveSheetIndex(0)
            ->setCellValue($this->x($o++, 1), 'Név')
            ->setCellValue($this->x($o++, 1), 'Telefon')
            ->setCellValue($this->x($o++, 1), 'Email')
            ->setCellValue($this->x($o++, 1), 'Számlázási név')
            ->setCellValue($this->x($o++, 1), 'Számlázási cím')
            ->setCellValue($this->x($o++, 1), 'Munkahely')
            ->setCellValue($this->x($o++, 1), 'Csoportos fizetés')
            ->setCellValue($this->x($o++, 1), 'Kapcsolat név')
            ->setCellValue($this->x($o++, 1), 'Nem vesz részt, csak szerző')
            ->setCellValue($this->x($o++, 1), 'MPT tag')
            ->setCellValue($this->x($o++, 1), 'Diák')
            ->setCellValue($this->x($o++, 1), 'Nyugdíjas');

        if ($partnerek) {
            $sor = 2;
            /** @var \Entities\Partner $partner */
            foreach ($partnerek as $partner) {
                $o = 0;
                $excel->setActiveSheetIndex(0)
                    ->setCellValue($this->x($o++, $sor), $partner->getNev())
                    ->setCellValue($this->x($o++, $sor), $partner->getTelefon())
                    ->setCellValue($this->x($o++, $sor), $partner->getEmail())
                    ->setCellValue($this->x($o++, $sor), $partner->getSzlanev())
                    ->setCellValue($this->x($o++, $sor), $partner->getCim())
                    ->setCellValue($this->x($o++, $sor), $partner->getMptMunkahelynev())
                    ->setCellValue($this->x($o++, $sor), $partner->getMptngycsoportosfizetes())
                    ->setCellValue($this->x($o++, $sor), $partner->getMptngykapcsolatnev())
                    ->setCellValue($this->x($o++, $sor), $partner->isMptngynemveszreszt())
                    ->setCellValue($this->x($o++, $sor), $partner->isMptngympttag())
                    ->setCellValue($this->x($o++, $sor), $partner->isMptngydiak())
                    ->setCellValue($this->x($o++, $sor), $partner->isMptngynyugdijas());

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
