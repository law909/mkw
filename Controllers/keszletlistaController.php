<?php

namespace Controllers;

use Doctrine\ORM\Query\ResultSetMapping;
use mkwhelpers\FilterDescriptor;

class keszletlistaController extends \mkwhelpers\MattableController {

    private $datumstr;
    private $raktarnev;
    private $nevfilter;
    private $foglalasstr;

    public function view() {
        $view = $this->createView('keszletlista.tpl');

        $view->setVar('datum', date(\mkw\store::$DateFormat));

        $rc = new raktarController($this->params);
        $view->setVar('raktarlist', $rc->getSelectList());

        $view->setVar('nyelvlist', \mkw\store::getLocaleSelectList());

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
        $this->datumstr = date(\mkw\store::$DateFormat, strtotime(\mkw\store::convDate($this->datumstr)));

        $foglalas = $this->params->getBoolRequestParam('foglalasszamit');
        if ($foglalas) {
            $this->foglalasstr = \mkw\store::translate('Foglalás számít');
        }

        $filter = new FilterDescriptor();
        $filter
            ->addFilter('bf.rontott', '=', false)
            ->addFilter('bf.teljesites', '<=', $this->datumstr);

        if ($foglalas) {
            $filter->addSql('((bt.mozgat=1) OR (bt.foglal=1))');
        }
        else {
            $filter->addFilter('bt.mozgat', '=', true);
        }
        if ($raktar) {
            $filter->addFilter('bf.raktar_id', '=', $raktar);
        }

        return $filter;
    }

    protected function createTermekFilter() {
        $filter = new FilterDescriptor();
        $fv = $this->params->getArrayRequestParam('fafilter');
        if (!empty($fv)) {
            $ff = new FilterDescriptor();
            $ff->addFilter('id', 'IN', $fv);
            $res = \mkw\store::getEm()->getRepository('Entities\TermekFa')->getAll($ff, array());
            $faszuro = array();
            foreach ($res as $sor) {
                $faszuro[] = $sor->getKarkod() . '%';
            }
            if ($faszuro) {
                $filter->addFilter(array('t.termekfa1karkod', 't.termekfa2karkod', 't.termekfa3karkod'), 'LIKE', $faszuro);
            }
        }
        $this->nevfilter = $this->params->getRequestParam('nevfilter', NULL);
        if (!is_null($this->nevfilter)) {
            $filter->addFilter(array('t.nev', 't.rovidleiras', 't.cikkszam', 't.vonalkod'), 'LIKE', '%' . $this->nevfilter . '%');
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

        $locale = \mkw\store::toLocale($this->params->getStringRequestParam('nyelv'));
        if ($locale) {
            $termeknevmezo = 'COALESCE(tt.content, t.nev)';
            $translationjoin = ' LEFT JOIN termek_translations tt ON (t.id=tt.object_id) AND (field="nev") AND (locale="' . $locale . '")';
        }
        else {
            $termeknevmezo = 't.nev';
            $translationjoin = '';
        }

        $keszlettipus = '';

        switch ($this->params->getIntRequestParam('keszlet')) {
            case 1:
                $keszlettipus = '';
                break;
            case 2:
                $keszlettipus = ' HAVING keszlet>0';
                break;
            case 3:
                $keszlettipus = ' HAVING (keszlet<=0) OR (keszlet IS NULL)';
                break;
            case 4:
                $keszlettipus = ' HAVING keszlet<0';
                break;
        }

        $termekfilter = $this->createTermekFilter();

        $q = $this->getEm()->createNativeQuery('SELECT _xx.termek_id, _xx.id, ' . $termeknevmezo . ' AS termeknev, _xx.ertek1, _xx.ertek2, t.cikkszam,'
            . ' (SELECT SUM(bt.mennyiseg * bt.irany)'
            . ' FROM bizonylattetel bt'
            . ' LEFT JOIN bizonylatfej bf ON (bt.bizonylatfej_id=bf.id)'
            . $filter->getFilterString('_xx', 'p') . ' AND (_xx.id=bt.termekvaltozat_id) ) AS keszlet'
            . ' FROM termekvaltozat _xx'
            . ' LEFT JOIN termek t ON (_xx.termek_id=t.id)'
            . $translationjoin
            . $termekfilter->getFilterString('_xx', 'r')
            . $keszlettipus
            . ' ORDER BY t.cikkszam, ' . $termeknevmezo . ', _xx.ertek1, _xx.ertek2', $rsm);

        $q->setParameters(array_merge_recursive($filter->getQueryParameters('p'), $termekfilter->getQueryParameters('r')));
        return $q->getScalarResult();
    }

    public function createLista() {

        $report = $this->createView('rep_keszlet.tpl');
        $report->setVar('lista', $this->getData());
        $report->setVar('datumstr', $this->datumstr);
        $report->setVar('raktar', $this->raktarnev);
        $report->setVar('nevfilter', $this->nevfilter);
        $report->setVar('foglalasstr', $this->foglalasstr);
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