<?php

namespace Controllers;


class idoszakipenztarjelenteslistaController extends \mkwhelpers\MattableController {

    private $tolstr;
    private $igstr;
    private $penztarid;
    private $penztarnev;

    public function view() {
        $view = $this->createView('idoszakipenztarjelenteslista.tpl');

        $view->setVar('toldatum', date(\mkw\store::$DateFormat));
        $view->setVar('igdatum', date(\mkw\store::$DateFormat));

        $penztar = new penztarController($this->params);
        $view->setVar('penztarlist', $penztar->getSelectList());

        $view->printTemplateResult();
    }

    protected function createFilter() {

        $this->tolstr = $this->params->getStringRequestParam('tol');
        $this->tolstr = date(\mkw\store::$DateFormat, strtotime(\mkw\store::convDate($this->tolstr)));

        $this->igstr = $this->params->getStringRequestParam('ig');
        $this->igstr = date(\mkw\store::$DateFormat, strtotime(\mkw\store::convDate($this->igstr)));

        $datummezo = 'kelt';

        $filter = new \mkwhelpers\FilterDescriptor();
        $filter
            ->addFilter($datummezo, '>=', $this->tolstr)
            ->addFilter($datummezo, '<=', $this->igstr);

        $pt = $this->getRepo('Entities\Penztar')->find($this->params->getIntRequestParam('penztar'));
        $this->penztarid = null;
        $this->penztarnev = null;
        if ($pt) {
            $filter->addFilter('_xx.penztar', '=', $pt->getId());
            $this->penztarid = $pt->getId();
            $this->penztarnev = $pt->getNev();
        }

        return $filter;
    }

    protected function createNyitoFilter() {

        $this->tolstr = $this->params->getStringRequestParam('tol');
        $this->tolstr = date(\mkw\store::$DateFormat, strtotime(\mkw\store::convDate($this->tolstr)));

        $datummezo = 'kelt';

        $filter = new \mkwhelpers\FilterDescriptor();
        $filter
            ->addFilter($datummezo, '<', $this->tolstr);

        $pt = $this->getRepo('Entities\Penztar')->find($this->params->getIntRequestParam('penztar'));
        $this->penztarid = null;
        $this->penztarnev = null;
        if ($pt) {
            $filter->addFilter('_xx.penztar', '=', $pt->getId());
            $this->penztarid = $pt->getId();
            $this->penztarnev = $pt->getNev();
        }

        return $filter;
    }

    public function createLista() {

        $nyitofilter = $this->createNyitoFilter();
        $filter = $this->createFilter();

        /** @var \Entities\PenztarbizonylatfejRepository $repo */
        $repo = $this->getRepo('Entities\Penztarbizonylatfej');

        $nyito = $repo->getWithJoins($nyitofilter, array('_xx.kelt' => 'ASC', '_xx.id' => 'ASC'));
        $mind = $repo->getWithJoins($filter, array('_xx.kelt' => 'ASC', '_xx.id' => 'ASC'));
        $adat = array();
        foreach ($mind as $elem) {
            $adat[] = array(
                'kelt' => $elem->getKeltStr(),
                'id' => $elem->getId(),
                'partnernev' => $elem->getPartnernev(),
                'brutto' => $elem->getBrutto(),
                'irany' => $elem->getIrany()
            );
        }

        $report = $this->createView('rep_idoszakipenztarjelentes.tpl');
        $report->setVar('lista', $adat);
        $report->setVar('tolstr', $this->tolstr);
        $report->setVar('igstr', $this->igstr);
        $report->setVar('penztarnev', $this->penztarnev);
        $report->printTemplateResult();
    }

    public function exportLista() {

        function x($o) {
            if ($o <= 26) {
                return chr(65 + $o);
            }
            return chr(65 + floor($o / 26)) . chr(65 + ($o % 26));
        }

        $excel = new \PHPExcel();
        $excel->setActiveSheetIndex(0)
            ->setCellValue('A1', 'Sorszám')
            ->setCellValue('B1', 'Dátum')
            ->setCellValue('C1', 'Bizonylatszám')
            ->setCellValue('D1', 'Partner')
            ->setCellValue('E1', 'Befizetés')
            ->setCellValue('F1', 'Kifizetés')
            ->setCellValue('G1', 'Egyenleg');

        $nyitofilter = $this->createNyitoFilter();
        $filter = $this->createFilter();

        /** @var \Entities\PenztarbizonylatfejRepository $repo */
        $repo = $this->getRepo('Entities\Penztarbizonylatfej');

        $nyito = $repo->getWithJoins($nyitofilter, array('_xx.kelt' => 'ASC', '_xx.id' => 'ASC'));
        $mind = $repo->getWithJoins($filter, array('_xx.kelt' => 'ASC', '_xx.id' => 'ASC'));

        $sor = 2;
        $sorszam = 0;
        $egyenleg = 0;
        /** @var \Entities\Penztarbizonylatfej $item */
        foreach ($mind as $item) {
            $sorszam++;
            $excel->setActiveSheetIndex(0)
                ->setCellValue('A' . $sor, $sorszam)
                ->setCellValue('B' . $sor, $item->getKeltStr())
                ->setCellValue('C' . $sor, $item->getId())
                ->setCellValue('D' . $sor, $item->getPartnernev());
            if ($item->getIrany() > 0) {
                $egyenleg = $egyenleg + $item->getBrutto();
                $excel->setActiveSheetIndex(0)
                    ->setCellValue('E' . $sor, $item->getBrutto())
                    ->setCellValue('F' . $sor, 0)
                    ->setCellValue('G' . $sor, $egyenleg);
            }
            else {
                $egyenleg = $egyenleg - $item->getBrutto();
                $excel->setActiveSheetIndex(0)
                    ->setCellValue('E' . $sor, 0)
                    ->setCellValue('F' . $sor, $item->getBrutto())
                    ->setCellValue('G' . $sor, $egyenleg);
            }
            $sor++;
        }

        $writer = \PHPExcel_IOFactory::createWriter($excel, 'Excel2007');

        $filepath = uniqid('idoszakipenztarjelentes') . '.xlsx';
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
}