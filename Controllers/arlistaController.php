<?php

namespace Controllers;

use Entities\Partner;
use Entities\Partnercimketorzs;
use Entities\Termek;
use Entities\TermekFa;
use mkwhelpers\FilterDescriptor;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use Services\PartnerArlistaService;

class arlistaController extends \mkwhelpers\Controller
{

    /**
     * A partner sávos árlistájának PDF-je a partner nyelvén. Az oldalak háttere (fejléc, lábléc) az
     * exporttemplates/arlista_<téma>.pdf első oldala, ha van ilyen fájl.
     */
    public function printPartnerArlista()
    {
        /** @var Partner $partner */
        $partner = $this->getRepo(Partner::class)->find($this->params->getIntRequestParam('partner'));
        if (!$partner) {
            echo t('Nincs ilyen partner.');
            return;
        }
        $data = (new PartnerArlistaService())->getPrintData($partner, $this->params->getArrayRequestParam('cimkek'));
        $valutanem = (string)$partner->getValutanem()?->getNev();

        $view = $this->createView('partnerarlistapdf.tpl');
        $view->setVar('savok', $data['savok']);
        $view->setVar('csoportok', $data['csoportok']);
        $view->setVar('fejszoveg', $this->params->getStringRequestParam('fejszoveg'));
        $view->setVar('labszoveg', $this->params->getStringRequestParam('labszoveg'));
        $view->setVar('penznem', ['EUR' => '€', 'USD' => '$', 'HUF' => 'Ft'][$valutanem] ?? $valutanem);
        $view->setVar('tizedes', $valutanem === 'HUF' ? 0 : 2);
        $view->setVar('feliratok', $data['locale'] === 'hu_hu'
            ? ['kiskerar' => 'KISKER ÁR', 'savok' => 'Vásárlási sávok kedvezménye (' . $valutanem . ')', 'ures' => 'Az árlistán nincs termék.']
            : ['kiskerar' => 'RETAIL PRICE', 'savok' => 'Purchase limits discount in ' . $valutanem, 'ures' => 'There are no products on this price list.']);

        $pdf = new \mkw\mkwmpdf($view->getTemplateResult());
        $background = \mkw\store::exporttemplatePath('arlista_' . \mkw\store::getTheme() . '.pdf');
        if (is_file($background)) {
            $pdf->getEngine()->SetDocTemplate($background, true);
        }
        $pdf->inline('arlista-' . $partner->getId() . '.pdf');
    }

    public function view()
    {
        $view = $this->createView('arlista.tpl');

        $partner = new partnerController();
        $view->setVar('partnerlist', $partner->getSelectList());

        $pcc = new partnercimkekatController();
        $view->setVar('cimkekat', $pcc->getWithCimkek(null));

        $view->printTemplateResult(false);
    }

    protected function createPartnerFilter()
    {
        $filter = new FilterDescriptor();

        $partnerid = $this->params->getIntRequestParam('partner');
        if ($partnerid) {
            $filter->addFilter('id', '=', $partnerid);
        } else {
            $partnerkodok = $this->getRepo(Partner::class)->getByCimkek($this->params->getArrayRequestParam('cimkefilter'));
            $this->partnerkodok = $partnerkodok;
            if ($partnerkodok) {
                $filter->addFilter('id', 'IN', $partnerkodok);
            }
        }

        return $filter;
    }

    protected function createTermekFilter()
    {
        $fafilter = $this->params->getArrayRequestParam('fafilter');

        $termekfilter = new FilterDescriptor();
        if (!empty($fafilter)) {
            $ff = new FilterDescriptor();
            $ff->addFilter('id', 'IN', $fafilter);
            $res = \mkw\store::getEm()->getRepository(TermekFa::class)->getAll($ff, []);
            $faszuro = [];
            foreach ($res as $sor) {
                $faszuro[] = $sor->getKarkod() . '%';
            }
            if ($faszuro) {
                $termekfilter->addFilter(['termekfa1karkod', 'termekfa2karkod', 'termekfa3karkod'], 'LIKE', $faszuro);
            }
        }

        return $termekfilter;
    }

    protected function getData($partnerfilter, $termekfilter)
    {
        $x = 0;
        $result = [];
        $partnerek = $this->getRepo(Partner::class)->getAll($partnerfilter, ['nev' => 'ASC']);
        $termekek = $this->getRepo(Termek::class)->getAllWithLocale(
            $termekfilter,
            ['cikkszam' => 'ASC', 'nev' => 'ASC'],
            0,
            0,
            'en_us'
        );
        /** @var Partner $partner */
        foreach ($partnerek as $partner) {
            /** @var Termek $termek */
            foreach ($termekek as $termek) {
                $result[] = [
                    'partnernev' => $partner->getNev(),
                    'cikkszam' => $termek->getCikkszam(),
                    'termeknev' => $termek->getNev(),
                    'netto' => $termek->getNettoAr(null, $partner),
                    'brutto' => $termek->getBruttoAr(null, $partner),
                    'kedvezmeny' => $termek->getKedvezmeny($partner)
                ];
            }
        }
        return $result;
    }

    public function createLista()
    {
        $mind = $this->getData(
            $this->createPartnerFilter(),
            $this->createTermekFilter()
        );

        $cimkenevek = $this->getRepo(Partnercimketorzs::class)->getCimkeNevek($this->params->getArrayRequestParam('cimkefilter'));

        $report = $this->createView('rep_arlista.tpl');
        $report->setVar('lista', $mind);
        $report->setVar('cimkenevek', $cimkenevek);
        $report->printTemplateResult();
    }

    public function exportLista()
    {
        $excel = new Spreadsheet();
        $excel->setActiveSheetIndex(0)
            ->setCellValue('A1', t('Partner'))
            ->setCellValue('B1', t('Cikkszám'))
            ->setCellValue('C1', t('Termék neve'))
            ->setCellValue('D1', t('Nettó ár'))
            ->setCellValue('E1', t('Bruttó ár'))
            ->setCellValue('F1', t('Érvényesített kedvezmény'));

        $mind = $this->getData(
            $this->createPartnerFilter(),
            $this->createTermekFilter()
        );

        $sor = 2;
        foreach ($mind as $item) {
            $excel->setActiveSheetIndex(0)
                ->setCellValue('A' . $sor, $item['partnernev'])
                ->setCellValue('B' . $sor, $item['cikkszam'])
                ->setCellValue('C' . $sor, $item['termeknev'])
                ->setCellValue('D' . $sor, $item['netto'])
                ->setCellValue('E' . $sor, $item['brutto'])
                ->setCellValue('F' . $sor, $item['kedvezmeny']);
            $sor++;
        }
        $writer = IOFactory::createWriter($excel, 'Xlsx');

        $filename = uniqid('arlista') . '.xlsx';
        $filepath = \mkw\store::storagePath($filename);
        $writer->save($filepath);

        $fileSize = filesize($filepath);

        // Output headers.
        header('Cache-Control: private');
        header('Content-Type: application/stream');
        header('Content-Length: ' . $fileSize);
        header('Content-Disposition: attachment; filename=' . $filename);

        readfile($filepath);

        \unlink($filepath);
    }
}