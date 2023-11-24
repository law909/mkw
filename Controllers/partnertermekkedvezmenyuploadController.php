<?php

namespace Controllers;

use Doctrine\DBAL\Statement;
use Entities\Partner;
use Entities\PartnerTermekKedvezmeny;
use Entities\Termek;
use Entities\Termekcimkekat;
use Entities\Termekcimketorzs;
use mkw\store;
use mkwhelpers\FilterDescriptor;
use PhpOffice\PhpSpreadsheet\Cell\Coordinate;
use PhpOffice\PhpSpreadsheet\IOFactory;

class partnertermekkedvezmenyuploadController extends \mkwhelpers\Controller
{

    private function n($mit)
    {
        return ord($mit) - 97;
    }

    private function getNagykerPartner()
    {
        $filter = new FilterDescriptor();
        $filter->addFilter('partnertipus', '=', 2);
        return $this->getRepo(Partner::class)->getAll($filter);
    }

    public function view()
    {
        $view = $this->createView('partnertermekkedvezmenyupload.tpl');
        $view->setVar('pagetitle', t('Partner termék kedvezmény import'));
        $view->printTemplateResult(false);
    }

    public function del()
    {
        $partnerek = $this->getNagykerPartner();
        /** @var Partner $partner */
        foreach ($partnerek as $partner) {
            $st = $this->getEm()->getConnection()->prepare('DELETE FROM partnertermekkedvezmeny WHERE partner_id=' . $partner->getId());
            $st->executeStatement();
        }
        echo 'ok';
    }

    public function upload()
    {
        $orgszazalek = store::getParameter('szazalek', 20);
        $filenev = \mkw\store::storagePath($_FILES['toimport']['name']);
        move_uploaded_file($_FILES['toimport']['tmp_name'], $filenev);
        //pathinfo

        $filetype = IOFactory::identify($filenev);
        $reader = IOFactory::createReader($filetype);
        $reader->setReadDataOnly(true);
        $excel = $reader->load($filenev);
        $sheet = $excel->getActiveSheet();
        $maxrow = (int)$sheet->getHighestRow();
        $partnerek = $this->getNagykerPartner();
        $db = 0;
        for ($row = 1; $row <= $maxrow; ++$row) {
            $termekkod = $sheet->getCell('A' . $row)->getValue();
            $termek = $this->getRepo(Termek::class)->find($termekkod);
            if ($termek) {
                $szazalek = $sheet->getCell('E' . $row)->getValue();
                if (!$szazalek) {
                    $szazalek = $orgszazalek;
                }
                foreach ($partnerek as $partner) {
                    $x = $this->getRepo(PartnerTermekKedvezmeny::class)->findOneBy(['partner' => $partner, 'termek' => $termek]);
                    if (!$x) {
                        $x = new PartnerTermekKedvezmeny();
                        $x->setPartner($partner);
                        $x->setTermek($termek);
                    }
                    $x->setKedvezmeny($szazalek);
                    $this->getEm()->persist($x);
                    $db++;
                    if (($db % 50) === 0) {
                        $this->getEm()->flush();
                    }
                }
            }
        }
        $this->getEm()->flush();
        echo 'ok';
    }
}