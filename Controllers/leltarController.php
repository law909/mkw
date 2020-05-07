<?php

namespace Controllers;

use Doctrine\ORM\Query\ResultSetMapping;
use mkwhelpers\FilterDescriptor;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Spreadsheet;

class leltarController extends \mkwhelpers\Controller {

    public function view() {
        $view = $this->createView('leltar.tpl');

        $rc = new raktarController($this->params);
        $view->setVar('raktarlist', $rc->getSelectList());

        $tac = new termekarController($this->params);
        $view->setVar('arsavlist', $tac->getSelectList());

        $view->printTemplateResult();
    }

    protected function createFilter() {
        $raktar = $this->params->getIntRequestParam('raktar');
        if ($raktar) {
            $r = $this->getRepo('Entities\Raktar')->find($raktar);
        }

        $foglalas = $this->params->getBoolRequestParam('foglalasszamit');

        $filter = new FilterDescriptor();
        $filter
            ->addFilter('bf.rontott', '=', false);

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

        return $filter;
    }

    protected function getData() {
        $rsm = new ResultSetMapping();
        $rsm->addScalarResult('termek_id', 'termek_id');
        $rsm->addScalarResult('id', 'termekvaltozat_id');
        $rsm->addScalarResult('termeknev', 'termeknev');
        $rsm->addScalarResult('keszlet', 'keszlet');
        $rsm->addScalarResult('ertek1', 'ertek1');
        $rsm->addScalarResult('ertek2', 'ertek2');
        $rsm->addScalarResult('cikkszam', 'cikkszam');

        $filter = $this->createFilter();

        $termeknevmezo = 't.nev';
        $translationjoin = '';

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
            . $filter->getFilterString() . ' AND (_xx.id=bt.termekvaltozat_id) ) AS keszlet'
            . ' FROM termekvaltozat _xx'
            . ' LEFT JOIN termek t ON (_xx.termek_id=t.id)'
            . $translationjoin
            . $termekfilter->getFilterString('_xx', 'r')
            . $keszlettipus
            . ' ORDER BY t.cikkszam, ' . $termeknevmezo . ', _xx.ertek1, _xx.ertek2', $rsm);

        $q->setParameters(array_merge_recursive($filter->getQueryParameters('p'), $termekfilter->getQueryParameters('r')));
        $d = $q->getScalarResult();

        $nettobrutto = $this->params->getStringRequestParam('nettobrutto');
        switch ($nettobrutto) {
            case 'netto':
                $this->nettobruttostr = t('Nettó');
                break;
            case 'brutto':
                $this->nettobruttostr = t('Bruttó');
                break;
            default:
                break;
        }

        $as = explode('_', $this->params->getStringRequestParam('arsav'));
        $arsav = $as[0];
        $valutanem = $as[1];
        $ret = array();
        foreach ($d as $sor) {
            if ($as) {
                /** @var \Entities\Termek $t */
                $t = $this->getRepo('Entities\Termek')->find($sor['termek_id']);
                if ($t) {
                    switch ($nettobrutto) {
                        case 'netto':
                            $sor['ar'] = $t->getNettoAr($sor['id'], null, $valutanem, $arsav);
                            break;
                        case 'brutto':
                            $sor['ar'] = $t->getBruttoAr($sor['id'], null, $valutanem, $arsav);
                            break;
                        default:
                            $sor['ar'] = 0;
                            break;
                    }
                }
            }
            $ret[] = $sor;
        }
        return $ret;
    }

    public function exportLista() {

        function x($o) {
            if ($o <= 26) {
                return chr(65 + $o);
            }
            return chr(65 + floor($o / 26)) . chr(65 + ($o % 26));
        }

        $excel = new Spreadsheet();
        $excel->setActiveSheetIndex(0)
            ->setCellValue('A1', t('Termék ID'))
            ->setCellValue('B1', t('Változat ID'))
            ->setCellValue('C1', t('Cikkszám'))
            ->setCellValue('D1', t('Termék'))
            ->setCellValue('E1', t('Változat'))
            ->setCellValue('F1', t('Ár'))
            ->setCellValue('G1', t('Készlet'))
            ->setCellValue('H1', t('Tény készlet'));

        $mind = $this->getData();

        $sor = 2;
        foreach ($mind as $item) {
            $excel->setActiveSheetIndex(0)
                ->setCellValue('A' . $sor, $item['termek_id'])
                ->setCellValue('B' . $sor, $item['termekvaltozat_id'])
                ->setCellValue('C' . $sor, $item['cikkszam'])
                ->setCellValue('D' . $sor, $item['termeknev'])
                ->setCellValue('E' . $sor, $item['ertek1'] . ' ' . $item['ertek2'])
                ->setCellValue('F' . $sor, $item['ar'])
                ->setCellValue('G' . $sor, $item['keszlet'])
                ->setCellValue('H' . $sor, $item['keszlet']);
            $sor++;
        }

        $writer = IOFactory::createWriter($excel, 'Xlsx');

        $filepath = \mkw\store::storagePath(uniqid('leltar') . '.xlsx');
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