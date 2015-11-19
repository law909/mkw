<?php

namespace Controllers;

use Doctrine\ORM\Query\ResultSetMapping;
use mkwhelpers\FilterDescriptor;

class kintlevoseglistaController extends \mkwhelpers\MattableController {

    private $tolstr;
    private $igstr;
    private $befdatumstr;
    private $datummezo;
    private $datumnev;
    private $uknev;
    private $partnernev;
    private $cimkenevek;

    public function view() {
        $view = $this->createView('kintlevoseglista.tpl');

        $view->setVar('toldatum', date(\mkw\Store::$DateFormat));
        $view->setVar('igdatum', date(\mkw\Store::$DateFormat));
        $view->setVar('befdatum', date(\mkw\Store::$DateFormat));
        $view->setVar('datumtipus', 'teljesites');

        $uk = new uzletkotoController($this->params);
        $view->setVar('uklist', $uk->getSelectList());

        $partner = new partnerController($this->params);
        $view->setVar('partnerlist', $partner->getSelectList());

        $pcc = new partnercimkekatController($this->params);
        $view->setVar('cimkekat', $pcc->getWithCimkek(null));

        $view->printTemplateResult();
    }

    protected function createBefdatumFilter() {
        $filter = new FilterDescriptor();

        $this->befdatumstr = $this->params->getStringRequestParam('befdatum');
        $this->befdatumstr = date(\mkw\Store::$DateFormat, strtotime(\mkw\Store::convDate($this->befdatumstr)));

        $filter->addFilter('fa.datum', '<=', $this->befdatumstr);
        return $filter;
    }

    protected function createFilter() {
        $filter = new FilterDescriptor();

        $this->tolstr = $this->params->getStringRequestParam('tol');
        $this->tolstr = date(\mkw\Store::$DateFormat, strtotime(\mkw\Store::convDate($this->tolstr)));

        $this->igstr = $this->params->getStringRequestParam('ig');
        $this->igstr = date(\mkw\Store::$DateFormat, strtotime(\mkw\Store::convDate($this->igstr)));

        $mt = $this->params->getStringRequestParam('datumtipus');
        switch ($mt) {
            case 'kelt':
                $this->datummezo = 'bf.kelt';
                $this->datumnev = 'Kelt';
                break;
            case 'teljesites':
                $this->datummezo = 'bf.teljesites';
                $this->datumnev = 'Teljesítés';
                break;
            case 'esedekesseg':
                $this->datummezo = 'f.hivatkozottdatum';
                $this->datumnev = 'Esedékesség';
                break;
            default:
                $this->datummezo = 'bf.teljesites';
                $this->datumnev = 'Teljesítés';
        }

        $ukkod = $this->params->getIntRequestParam('uzletkoto');
        if ($ukkod) {
            $filter->addFilter('bf.uzletkoto_id', '=', $ukkod);
            $uk = $this->getRepo('Entities\Uzletkoto')->find($ukkod);
            if ($uk) {
                $this->uknev = $uk->getNev();
            }
        }

        $partnerkod = $this->params->getIntRequestParam('partner');
        if ($partnerkod) {
            $filter->addFilter('f.partner_id', '=', $partnerkod);
            $partner = $this->getRepo('Entities\Partner')->find($partnerkod);
            if ($partner) {
                $this->partnernev = $partner->getNev();
            }
        }
        else {
            $partnerkodok = $this->getRepo('Entities\Partner')->getByCimkek($this->params->getArrayRequestParam('cimkefilter'));
            if ($partnerkodok) {
                $filter->addFilter('f.partner_id', 'IN', $partnerkodok);
            }
            $this->cimkenevek = $this->getRepo('Entities\Partnercimketorzs')->getCimkeNevek($this->params->getArrayRequestParam('cimkefilter'));
            $this->cimkenevek = implode(',', $this->cimkenevek);
        }

        $filter
            ->addFilter('f.irany', '>', 0)
            ->addFilter($this->datummezo, '>=', $this->tolstr)
            ->addFilter($this->datummezo, '<=', $this->igstr)
            ->addFilter('bf.rontott', '=', false)
            ->addFilter('bf.storno', '=', false)
            ->addFilter('bf.stornozott', '=', false);
        return $filter;
    }

    protected function createSecFilter() {
        $filter = new FilterDescriptor();

        $filter
            ->addFilter('f.rontott', '=', false)
            ->addFilter('f.datum', '<=', $this->befdatumstr);

        $partnerkod = $this->params->getIntRequestParam('partner');
        if ($partnerkod) {
            $filter->addFilter('f.partner_id', '=', $partnerkod);
        }
        else {
            $partnerkodok = $this->getRepo('Entities\Partner')->getByCimkek($this->params->getArrayRequestParam('cimkefilter'));
            if ($partnerkodok) {
                $filter->addFilter('f.partner_id', 'IN', $partnerkodok);
            }
        }

        return $filter;
    }

    protected function getData() {
        $rsm = new ResultSetMapping();
        $rsm->addScalarResult('bizonylatfej_id', 'bizonylatfej_id');
        $rsm->addScalarResult('partner_id', 'partner_id');
        $rsm->addScalarResult('nev', 'nev');
        $rsm->addScalarResult('telefon', 'telefon');
        $rsm->addScalarResult('mobil', 'mobil');
        $rsm->addScalarResult('email', 'email');
        $rsm->addScalarResult('irszam', 'irszam');
        $rsm->addScalarResult('varos', 'varos');
        $rsm->addScalarResult('utca', 'utca');
        $rsm->addScalarResult('kelt', 'kelt');
        $rsm->addScalarResult('teljesites', 'teljesites');
        $rsm->addScalarResult('esedekesseg', 'esedekesseg');
        $rsm->addScalarResult('datum', 'datum');
        $rsm->addScalarResult('hivatkozottdatum', 'hivatkozottdatum');
        $rsm->addScalarResult('brutto', 'brutto');
        $rsm->addScalarResult('tartozas', 'tartozas');
        $rsm->addScalarResult('valutanemnev', 'valutanemnev');

        $beffilter = $this->createBefdatumFilter();
        $filter = $this->createFilter();
        $secfilter = $this->createSecFilter();

        $q = $this->getEm()->createNativeQuery(
            '(SELECT f.bizonylatfej_id, bf.partner_id, p.nev, p.telefon, p.mobil, p.email, p.irszam,'
            . ' p.varos, p.utca, bf.kelt, bf.teljesites, bf.esedekesseg, f.datum, f.hivatkozottdatum, SUM(f.brutto * f.irany) AS brutto,'
            . ' IFNULL('
            . '  (SELECT SUM(fa.brutto * fa.irany)'
            . '   FROM folyoszamla fa '
            . $beffilter->getFilterString('', 'bef')
            . '   AND (fa.hivatkozottbizonylat = f.bizonylatfej_id) AND (fa.hivatkozottdatum = f.hivatkozottdatum) AND (bizonylatfej_id IS NULL)'
            . '   AND (fa.rontott = 0)),0)'
            . ' + SUM(f.brutto * f.irany) AS tartozas, bf.valutanemnev'
            . ' FROM folyoszamla f'
            . ' LEFT OUTER JOIN bizonylatfej bf ON (f.hivatkozottbizonylat = bf.id)'
            . ' LEFT OUTER JOIN partner p ON (f.partner_id = p.id)'
            . $filter->getFilterString('', 'par')
            . ' AND (f.hivatkozottbizonylat = f.bizonylatfej_id)'
            . ' GROUP BY f.partner_id , hivatkozottbizonylat, f.hivatkozottdatum, bf.kelt, bf.teljesites'
            . ' HAVING (tartozas <> 0))'
            . ' UNION'
            . ' (SELECT f.bankbizonylatfej_id AS bizonylat, f.partner_id, p.nev, p.telefon, p.mobil, p.email, p.irszam,'
            . ' p.varos, p.utca, f.datum AS kelt, f.datum AS teljesites, f.datum AS esedekesseg, f.datum, f.hivatkozottdatum, 0 AS brutto,'
            . ' SUM(f.brutto * f.irany) AS tartozas, v.nev AS valutanemnev '
            . ' FROM folyoszamla f'
            . ' LEFT OUTER JOIN partner p ON (f.partner_id = p.id)'
            . ' LEFT OUTER JOIN valutanem v ON (f.valutanem_id = v.id)'
            . $secfilter->getFilterString('', 'sec')
            . ' AND (f.hivatkozottbizonylat IS NULL)'
            . ' GROUP BY f.partner_id, f.datum'
            . ' HAVING (tartozas <> 0)'
            . ')'
            . ' ORDER BY nev, partner_id, kelt'
            , $rsm);
        $q->setParameters(array_merge_recursive(
            $filter->getQueryParameters('par'),
            $beffilter->getQueryParameters('bef'),
            $secfilter->getQueryParameters('sec')
        ));

        $lejartfilter = $this->params->getIntRequestParam('lejartfilter');

        $d = $q->getScalarResult();
        $ret = array();
        $ma = new \DateTime(date(\mkw\Store::$SQLDateFormat));
        $mastr = date(\mkw\Store::$SQLDateFormat);
        foreach($d as $sor) {
            $sor['lejart'] = $sor['hivatkozottdatum'] <= $mastr;
            $es = new \DateTime($sor['hivatkozottdatum']);
            $diff = $ma->diff($es);
            if ($sor['lejart']) {
                $sor['lejartnap'] = $diff->days;
            }
            else {
                $sor['lejartnap'] = 0;
            }
            switch ($lejartfilter) {
                case 1:
                    $ret[] = $sor;
                    break;
                case 2:
                    if ($sor['lejart']) {
                        $ret[] = $sor;
                    }
                    break;
                case 3:
                    if (!$sor['lejart']) {
                        $ret[] = $sor;
                    }
                    break;
            }
        }

        return $ret;
    }

    public function createLista() {

        $d = $this->getData();
        $report = $this->createView('rep_kintlevoseg.tpl');
        $report->setVar('lista', $d);
        $report->setVar('datumnev', $this->datumnev);
        $report->setVar('tolstr', $this->tolstr);
        $report->setVar('igstr', $this->igstr);
        $report->setVar('befdatumstr', $this->befdatumstr);
        $report->setVar('cimkenevek', $this->cimkenevek);
        $report->setVar('partnernev', $this->partnernev);
        $report->setVar('uknev', $this->uknev);
        $report->setVar('reszletessum', $this->params->getBoolRequestParam('reszletessum'));
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
            ->setCellValue('A1', t('Partner'))
            ->setCellValue('B1', t('Partner cím'))
            ->setCellValue('C1', t('Telefon'))
            ->setCellValue('D1', t('Mobil'))
            ->setCellValue('E1', t('Email'))
            ->setCellValue('F1', t('Bizonylatszám'))
            ->setCellValue('G1', t('Kelt'))
            ->setCellValue('H1', t('Teljesítés'))
            ->setCellValue('I1', t('Esedékesség'))
            ->setCellValue('J1', t('Fizetendő'))
            ->setCellValue('K1', t('Tartozás'))
            ->setCellValue('L1', t('Valutanem'))
            ->setCellValue('M1', t('Lejárt'))
            ->setCellValue('N1', t('Lejárat nap'));

        $mind = $this->getData();

        $sor = 2;
        foreach ($mind as $item) {
            $excel->setActiveSheetIndex(0)
                ->setCellValue('A' . $sor, $item['nev'])
                ->setCellValue('B' . $sor, $item['irszam'] . ' ' . $item['varos'] . ' ' . $item['utca'])
                ->setCellValue('C' . $sor, $item['telefon'])
                ->setCellValue('D' . $sor, $item['mobil'])
                ->setCellValue('E' . $sor, $item['email'])
                ->setCellValue('F' . $sor, $item['bizonylatfej_id'])
                ->setCellValue('G' . $sor, $item['kelt'])
                ->setCellValue('H' . $sor, $item['teljesites'])
                ->setCellValue('I' . $sor, $item['esedekesseg'])
                ->setCellValue('J' . $sor, $item['brutto'])
                ->setCellValue('K' . $sor, $item['tartozas'])
                ->setCellValue('L' . $sor, $item['valutanemnev'])
                ->setCellValue('M' . $sor, $item['lejart'])
                ->setCellValue('N' . $sor, $item['lejartnap']);
            $sor++;
        }

        $writer = \PHPExcel_IOFactory::createWriter($excel, 'Excel2007');

        $filepath = uniqid('kintlevoseg') . '.xlsx';
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