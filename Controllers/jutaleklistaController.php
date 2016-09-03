<?php

namespace Controllers;


class jutaleklistaController extends \mkwhelpers\MattableController {

    private $tolstr;
    private $igstr;
    private $partnerkodok;
    private $ukid;
    private $belso;

    public function view() {
        $view = $this->createView('jutaleklista.tpl');

        $view->setVar('toldatum', date(\mkw\store::$DateFormat));
        $view->setVar('igdatum', date(\mkw\store::$DateFormat));

        $pcc = new partnercimkekatController($this->params);
        $view->setVar('cimkekat', $pcc->getWithCimkek(null));

        $fmc = new uzletkotoController($this->params);
        $view->setVar('uklist', $fmc->getSelectList(false));

        $view->printTemplateResult();
    }

    protected function createFilter() {
        $this->belso = $this->params->getBoolRequestParam('belso');

        $this->tolstr = $this->params->getStringRequestParam('tol');
        $this->tolstr = date(\mkw\store::$DateFormat, strtotime(\mkw\store::convDate($this->tolstr)));

        $this->igstr = $this->params->getStringRequestParam('ig');
        $this->igstr = date(\mkw\store::$DateFormat, strtotime(\mkw\store::convDate($this->igstr)));

        $datummezo = 'datum';

        $filter = new \mkwhelpers\FilterDescriptor();
        $filter
            ->addFilter($datummezo, '>=', $this->tolstr)
            ->addFilter($datummezo, '<=', $this->igstr)
            ->addFilter('irany', '=', 1);

        $partnerkodok = $this->getRepo('Entities\Partner')->getByCimkek($this->params->getArrayRequestParam('cimkefilter'));
        $this->partnerkodok = $partnerkodok;
        if ($partnerkodok) {
            $filter->addFilter('partner_id', 'IN', $partnerkodok);
        }

        $uk = $this->getRepo('Entities\Uzletkoto')->find($this->params->getIntRequestParam('uzletkoto'));
        $this->ukid = null;
        if ($uk) {
            if ($this->belso) {
                $filter->addFilter('bf.belsouzletkoto_id', '=', $uk->getId());
            }
            else {
                $filter->addFilter('bf.uzletkoto_id', '=', $uk->getId());
            }
            $this->ukid = $uk->getId();
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
            $sor['jutalekosszeg'] = \mkw\store::kerekit($sor['brutto'] * $sor['uzletkotojutalek'] / 100, 0.01);
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
                        'jutalekosszeg' => \mkw\store::kerekit($bt->getBrutto() * $sor['uzletkotojutalek'] / 100 * -1, 0.01),
                        'type' => 'Transport cost'
                    );
                }
            }
        }
        return $ret;
    }

    public function addFakeKifizetes($mihez) {
        if (\mkw\store::isFakeKintlevoseg()) {
            $f = $this->getRepo('Entities\Bizonylatfej')->getAllFakeKifizetes($this->tolstr, $this->igstr, $this->partnerkodok, $this->ukid, $this->belso);
            /** @var \Entities\Bizonylatfej $k */
            foreach ($f as $k) {
                $x = array(
                    'id' => 0,
                    'bankbizonylatfej_id' => 0,
                    'valutanem_id' => $k->getValutanemId(),
                    'valutanemnev' => $k->getValutanemnev(),
                    'datum' => $k->getFakekifizetesdatumStr(),
                    'hivatkozottdatum' => $k->getEsedekessegStr(),
                    'hivatkozottbizonylat' => $k->getId(),
                    'partnernev' => $k->getPartnernev(),
                    'brutto' => $k->getBrutto(),
                    'type' => 'Fake'
                );
                if ($this->belso) {
                    $x['uzletkoto_id'] = $k->getBelsouzletkotoId();
                    $x['uzletkotonev'] = $k->getBelsouzletkotonev();
                    $x['uzletkotojutalek'] = $k->getBelsouzletkotojutalek();
                    $x['jutalekosszeg'] = \mkw\store::kerekit($k->getBrutto() * $k->getBelsouzletkotojutalek() / 100, 0.01);
                }
                else {
                    $x['uzletkoto_id'] = $k->getUzletkotoId();
                    $x['uzletkotonev'] = $k->getUzletkotonev();
                    $x['uzletkotojutalek'] = $k->getUzletkotojutalek();
                    $x['jutalekosszeg'] = \mkw\store::kerekit($k->getBrutto() * $k->getUzletkotojutalek() / 100, 0.01);
                }
                $mihez[] = $x;
            }
        }
        return $mihez;
    }

    public function addKeszpenzes($mihez) {
        $f = $this->getRepo('Entities\Bizonylatfej')->getAllKeszpenzes($this->tolstr, $this->igstr, $this->partnerkodok, $this->ukid, $this->belso);
        /** @var \Entities\Bizonylatfej $k */
        foreach ($f as $k) {
            $x = array(
                'id' => 0,
                'bankbizonylatfej_id' => 0,
                'valutanem_id' => $k->getValutanemId(),
                'valutanemnev' => $k->getValutanemnev(),
                'datum' => $k->getFakekifizetesdatumStr(),
                'hivatkozottdatum' => $k->getEsedekessegStr(),
                'hivatkozottbizonylat' => $k->getId(),
                'partnernev' => $k->getPartnernev(),
                'brutto' => $k->getBrutto(),
                'type' => 'KP'
            );
            if ($this->belso) {
                $x['uzletkoto_id'] = $k->getBelsouzletkotoId();
                $x['uzletkotonev'] = $k->getBelsouzletkotonev();
                $x['uzletkotojutalek'] = $k->getBelsouzletkotojutalek();
                $x['jutalekosszeg'] = \mkw\store::kerekit($k->getBrutto() * $k->getBelsouzletkotojutalek() / 100, 0.01);
            }
            else {
                $x['uzletkoto_id'] = $k->getUzletkotoId();
                $x['uzletkotonev'] = $k->getUzletkotonev();
                $x['uzletkotojutalek'] = $k->getUzletkotojutalek();
                $x['jutalekosszeg'] = \mkw\store::kerekit($k->getBrutto() * $k->getUzletkotojutalek() / 100, 0.01);
            }
            $mihez[] = $x;
        }
        return $mihez;
    }

    public function createLista() {
        $filter = $this->createFilter();

        $cimkenevek = $this->getRepo('Entities\Partnercimketorzs')->getCimkeNevek($this->params->getArrayRequestParam('cimkefilter'));

        /** @var \Entities\BankbizonylattetelRepository $btrepo */
        $btrepo = $this->getRepo('Entities\Bankbizonylattetel');

        $mind = $btrepo->getAllHivatkozottJoin($filter,
            array('datum' => 'ASC'));
        $mind = $this->addNegativSzallktg($mind);
        $mind = $this->addKeszpenzes($mind);
        $mind = $this->addFakeKifizetes($mind);

        $report = $this->createView('rep_jutalek.tpl');
        $report->setVar('lista', $mind);
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
        $mind = $this->addKeszpenzes($mind);
        $mind = $this->addFakeKifizetes($mind);

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