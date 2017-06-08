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
            ->addFilter($datummezo, '<=', $this->igstr)
            ->addFilter('rontott', '=', false);

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
            ->addFilter($datummezo, '<', $this->tolstr)
            ->addFilter('rontott', '=', false);

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

        $nyito = $repo->getSum($nyitofilter);
        $mind = $repo->getWithJoins($filter, array('_xx.kelt' => 'ASC', '_xx.id' => 'ASC'));
        $adat = array(array(
            'kelt' => '',
            'id' => 'Nyitó',
            'partnernev' => '',
            'brutto' => $nyito,
            'irany' => ($nyito < 0 ? -1 : 1)
        ));
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
            ->setCellValue('A1', 'Dátum')
            ->setCellValue('B1', 'Bizonylatszám')
            ->setCellValue('C1', 'Partner')
            ->setCellValue('D1', 'Befizetés')
            ->setCellValue('E1', 'Kifizetés')
            ->setCellValue('F1', 'Egyenleg');

        $nyitofilter = $this->createNyitoFilter();
        $filter = $this->createFilter();

        /** @var \Entities\PenztarbizonylatfejRepository $repo */
        $repo = $this->getRepo('Entities\Penztarbizonylatfej');

        $nyito = $repo->getSum($nyitofilter);
        $mind = $repo->getWithJoins($filter, array('_xx.kelt' => 'ASC', '_xx.id' => 'ASC'));

        $sor = 2;
        $egyenleg = 0;
        $excel->setActiveSheetIndex(0)
            ->setCellValue('B' . $sor, 'Nyitó');
        if ($nyito > 0) {
            $egyenleg = $egyenleg + $nyito;
            $excel->setActiveSheetIndex(0)
                ->setCellValue('F' . $sor, $egyenleg);
        }
        else {
            $egyenleg = $egyenleg - $nyito;
            $excel->setActiveSheetIndex(0)
                ->setCellValue('F' . $sor, $egyenleg);
        }
        $sor++;
        /** @var \Entities\Penztarbizonylatfej $item */
        foreach ($mind as $item) {
            $excel->setActiveSheetIndex(0)
                ->setCellValue('A' . $sor, $item->getKeltStr())
                ->setCellValue('B' . $sor, $item->getId())
                ->setCellValue('C' . $sor, $item->getPartnernev());
            if ($item->getIrany() > 0) {
                $egyenleg = $egyenleg + $item->getBrutto();
                $excel->setActiveSheetIndex(0)
                    ->setCellValue('D' . $sor, $item->getBrutto())
                    ->setCellValue('E' . $sor, 0)
                    ->setCellValue('F' . $sor, $egyenleg);
            }
            else {
                $egyenleg = $egyenleg - $item->getBrutto();
                $excel->setActiveSheetIndex(0)
                    ->setCellValue('D' . $sor, 0)
                    ->setCellValue('E' . $sor, $item->getBrutto())
                    ->setCellValue('F' . $sor, $egyenleg);
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