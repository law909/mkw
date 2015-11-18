<?php

namespace Controllers;

use Doctrine\ORM\Query\ResultSetMapping;

class keszletlistaController extends \mkwhelpers\MattableController {

    private $datumstr;
    private $raktarnev;

    public function view() {
        $view = $this->createView('keszletlista.tpl');

        $view->setVar('datum', date(\mkw\Store::$DateFormat));

        $rc = new raktarController($this->params);
        $view->setVar('raktarlist', $rc->getSelectList());

        $view->printTemplateResult();
    }

    protected function createFilter() {
        $this->raktarnev = t('Minden raktár');
        $raktar = $this->params->getIntRequestParam('raktar');
        if ($raktar) {
            $r = $this->getRepo('Entities\Raktar')->find($raktar);
            if ($r) {
                $this->raktarnev = $r->getNev();
            }
        }

        $this->datumstr = $this->params->getStringRequestParam('datum');
        $this->datumstr = date(\mkw\Store::$DateFormat, strtotime(\mkw\Store::convDate($this->datumstr)));

        $filter = new \mkwhelpers\FilterDescriptor();
        $filter
            ->addFilter('bf.rontott', '=', false)
            ->addFilter('_xx.mozgat', '=', true)
            ->addFilter('bf.teljesites', '<=', $this->datumstr);

        if ($raktar) {
            $filter->addFilter('bf.raktar_id', '=', $raktar);
        }

        return $filter;
    }

    protected function getData() {
        $rsm = new ResultSetMapping();
        $rsm->addScalarResult('termek_id', 'termek_id');
        $rsm->addScalarResult('termekvaltozat_id', 'termekvaltozat_id');
        $rsm->addScalarResult('termeknev', 'termeknev');
        $rsm->addScalarResult('keszlet', 'keszlet');
        $rsm->addScalarResult('ertek1', 'ertek1');
        $rsm->addScalarResult('ertek2', 'ertek2');
        $rsm->addScalarResult('cikkszam', 'cikkszam');

        $filter = $this->createFilter();

        $q = $this->getEm()->createNativeQuery('SELECT _xx.termek_id, _xx.termekvaltozat_id, t.nev AS termeknev, tv.ertek1, tv.ertek2, t.cikkszam,'
            . ' SUM(_xx.mennyiseg * _xx.irany) AS keszlet'
            . ' FROM bizonylattetel _xx'
            . ' LEFT JOIN bizonylatfej bf ON (_xx.bizonylatfej_id=bf.id)'
            . ' LEFT JOIN termek t ON (_xx.termek_id=t.id)'
            . ' LEFT JOIN termekvaltozat tv ON (_xx.termekvaltozat_id=tv.id)'
            . $filter->getFilterString()
            . ' GROUP BY _xx.termek_id, _xx.termekvaltozat_id'
            . ' HAVING SUM(_xx.mennyiseg * _xx.irany)<>0'
            . ' ORDER BY t.cikkszam, t.nev, tv.ertek1, tv.ertek2', $rsm);

        $q->setParameters($filter->getQueryParameters());
        return $q->getScalarResult();
    }

    public function createLista() {

        $report = $this->createView('rep_keszlet.tpl');
        $report->setVar('lista', $this->getData());
        $report->setVar('datumstr', $this->datumstr);
        $report->setVar('raktar', $this->raktarnev);
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
            ->setCellValue('A1', t('Cikkszám'))
            ->setCellValue('B1', t('Termék'))
            ->setCellValue('C1', t('Változat'))
            ->setCellValue('D1', t('Készlet'));

        $mind = $this->getData();

        $sor = 2;
        foreach ($mind as $item) {
            $excel->setActiveSheetIndex(0)
                ->setCellValue('A' . $sor, $item['cikkszam'])
                ->setCellValue('B' . $sor, $item['termeknev'])
                ->setCellValue('C' . $sor, $item['ertek1'] . ' ' . $item['ertek2'])
                ->setCellValue('D' . $sor, $item['keszlet']);
            $sor++;
        }

        $writer = \PHPExcel_IOFactory::createWriter($excel, 'Excel2007');

        $filepath = uniqid('keszlet') . '.xlsx';
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