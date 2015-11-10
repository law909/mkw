<?php

namespace Controllers;


class jutaleklistaController extends \mkwhelpers\MattableController {

    private $tolstr;
    private $igstr;

    public function view() {
        $view = $this->createView('jutaleklista.tpl');

        $view->setVar('toldatum', date(\mkw\Store::$DateFormat));
        $view->setVar('igdatum', date(\mkw\Store::$DateFormat));

        $pcc = new partnercimkekatController($this->params);
        $view->setVar('cimkekat', $pcc->getWithCimkek(null));

        $view->printTemplateResult();
    }

    protected function  createFilter() {
        $this->tolstr = $this->params->getStringRequestParam('tol');
        $this->tolstr = date(\mkw\Store::$DateFormat, strtotime(\mkw\Store::convDate($this->tolstr)));

        $this->igstr = $this->params->getStringRequestParam('ig');
        $this->igstr = date(\mkw\Store::$DateFormat, strtotime(\mkw\Store::convDate($this->igstr)));

        $datummezo = 'datum';

        $filter = new \mkwhelpers\FilterDescriptor();
        $filter
            ->addFilter($datummezo, '>=', $this->tolstr)
            ->addFilter($datummezo, '<=', $this->igstr)
            ->addFilter('irany', '=', 1);

        $partnerkodok = $this->getRepo('Entities\Partner')->getByCimkek($this->params->getArrayRequestParam('cimkefilter'));
        if ($partnerkodok) {
            $filter->addFilter('partner_id', 'IN', $partnerkodok);
        }
        return $filter;
    }

    public function addNegativSzallktg($mihez) {
        /** @var \Entities\BizonylatfejRepository $bfrepo */
        $bfrepo = $this->getRepo('Entities\Bizonylatfej');
        /** @var \Entities\BankbizonylattetelRepository $bbtrepo */
        $bbtrepo = $this->getRepo('Entities\Bankbizonylattetel');
        $ret = array();
        foreach ($mihez as $sor) {
            $sor['jutalekosszeg'] = \mkw\Store::kerekit($sor['brutto'] * $sor['uzletkotojutalek'] / 100, 0.01);
            $sor['type'] = 'Item';
            $ret[] = $sor;
            $bf = $bfrepo->find($sor['hivatkozottbizonylat']);
            if ($bf && $bfrepo->haveSzallitasiKtg($bf)
                && ($bbtrepo->isFirstByHivatkozottBizonylat(
                    $sor['id'],
                    $sor['hivatkozottbizonylat'],
                    $sor['datum']))) {
                $bt = $bfrepo->getSzallitasiKtgTetel($bf);
                if ($bt) {
                    $ret[] = array(
                        'id' => 0,
                        'bankbizonylatfej_id' => $sor['bankbizonylatfej_id'],
                        'valutanem_id' => $sor['valutanem_id'],
                        'valutanemnev' => $sor['valutanemnev'],
                        'datum' => $sor['datum'],
                        'hivatkozottdatum' => $sor['hivatkozottdatum'],
                        'hivatkozottbizonylat' => $sor['hivatkozottbizonylat'],
                        'uzletkoto_id' => $sor['uzletkoto_id'],
                        'uzletkotonev' => $sor['uzletkotonev'],
                        'uzletkotojutalek' => $sor['uzletkotojutalek'],
                        'partnernev' => $sor['partnernev'],
                        'brutto' => $bt->getBrutto() * -1,
                        'jutalekosszeg' => \mkw\Store::kerekit($bt->getBrutto() * $sor['uzletkotojutalek'] / 100 * -1, 0.01),
                        'type' => 'Transport cost'
                    );
                }
            }
        }
        return $ret;
    }

    public function createLista() {
        $filter = $this->createFilter();

        $cimkenevek = $this->getRepo('Entities\Partnercimketorzs')->getCimkeNevek($this->params->getArrayRequestParam('cimkefilter'));

        /** @var \Entities\BankbizonylattetelRepository $btrepo */
        $btrepo = $this->getRepo('Entities\Bankbizonylattetel');

        $mind = $btrepo->getAllHivatkozottJoin($filter,
            array('datum' => 'ASC'));

        $report = $this->createView('rep_jutalek.tpl');
        $report->setVar('lista', $this->addNegativSzallktg($mind));
        $report->setVar('tolstr', $this->tolstr);
        $report->setVar('igstr', $this->igstr);
        $report->setVar('cimkenevek', $cimkenevek);
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
            ->setCellValue('A1', 'Payment Due')
            ->setCellValue('B1', 'Date of income')
            ->setCellValue('C1', 'Invoice nr.')
            ->setCellValue('D1', 'Customer')
            ->setCellValue('E1', 'Agent')
            ->setCellValue('F1', 'Type')
            ->setCellValue('G1', 'Income')
            ->setCellValue('H1', 'Comission %')
            ->setCellValue('I1', 'Comission value');

        $filter = $this->createFilter();

        /** @var \Entities\BankbizonylattetelRepository $btrepo */
        $btrepo = $this->getRepo('Entities\Bankbizonylattetel');

        $mind = $btrepo->getAllHivatkozottJoin($filter);

        $mind = $this->addNegativSzallktg($mind);

        $sor = 2;
        foreach ($mind as $item) {
            $excel->setActiveSheetIndex(0)
                ->setCellValue('A' . $sor, $item['hivatkozottdatum'])
                ->setCellValue('B' . $sor, $item['datum'])
                ->setCellValue('C' . $sor, $item['hivatkozottbizonylat'])
                ->setCellValue('D' . $sor, $item['partnernev'])
                ->setCellValue('E' . $sor, $item['uzletkotonev'])
                ->setCellValue('F' . $sor, $item['type'])
                ->setCellValue('G' . $sor, $item['brutto'])
                ->setCellValue('H' . $sor, $item['uzletkotojutalek'])
                ->setCellValue('I' . $sor, $item['jutalekosszeg']);
            $sor++;
        }

        $writer = \PHPExcel_IOFactory::createWriter($excel, 'Excel2007');

        $filepath = uniqid('comission') . '.xlsx';
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