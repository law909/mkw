<?php

namespace Controllers;

use Entities\Bizonylatfej;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Spreadsheet;

class arbevetellistaController extends \mkwhelpers\Controller
{

    // a stacked bar with more series than this is unreadable, the rest goes into one
    protected const MAXSERIES = 10;

    public function view()
    {
        $this->showView('arbevetellista.tpl', t('Árbevétel kimutatás'));
    }

    protected function showView(string $tplname, string $pagetitle)
    {
        $view = $this->createView($tplname);

        $view->setVar('pagetitle', $pagetitle);
        $view->setVar('datumtipus', 'teljesites');
        $view->setVar('toldatum', date('Y.01.01'));
        $view->setVar('igdatum', date(\mkw\store::$DateFormat));
        $partner = new partnerController();
        $view->setVar('partnerlist', $partner->getSelectList());
        $view->setVar('gyartolist', $partner->getGyartoSelectList(0));
        $view->setVar('partnertipuslist', (new partnertipusController())->getSelectList(0));
        $view->setVar('webshopfilterlist', \mkw\store::getWebshopSelectList('', true));

        $view->printTemplateResult(false);
    }

    protected function getData(string $repoMethod = 'getArbevetelLista'): array
    {
        $csoport = [];
        $idoszakcsoport = $this->params->getStringRequestParam('idoszakcsoport');
        if (in_array($idoszakcsoport, ['ev', 'honap'], true)) {
            $csoport[] = $idoszakcsoport;
        }
        if ($this->params->getBoolRequestParam('gyartocsoport')) {
            $csoport[] = 'gyarto';
        }
        if ($this->params->getBoolRequestParam('webshopcsoport')) {
            $csoport[] = 'webshop';
        }
        $brutto = $this->params->getStringRequestParam('ertektipus') === 'brutto';

        $rows = $this->getRepo(Bizonylatfej::class)->$repoMethod(
            $this->params->getStringRequestParam('datumtipus'),
            $this->params->getStringRequestParam('tol'),
            $this->params->getStringRequestParam('ig'),
            $brutto,
            $csoport,
            $this->params->getIntRequestParam('partner'),
            $this->params->getIntRequestParam('partnertipus'),
            $this->params->getIntRequestParam('gyarto'),
            $this->params->getArrayRequestParam('fafilter'),
            $this->params->getStringRequestParam('webshopnum')
        );

        return [
            'rows' => $rows,
            'brutto' => $brutto,
            'idoszak' => (bool)array_intersect(['ev', 'honap'], $csoport),
            'gyarto' => in_array('gyarto', $csoport, true),
            'webshop' => in_array('webshop', $csoport, true),
        ];
    }

    protected function seriesLabel(array $row, array $data): string
    {
        $parts = [];
        if ($data['gyarto']) {
            $parts[] = $row['gyartonev'];
        }
        if ($data['webshop']) {
            $parts[] = $row['webshopnev'];
        }
        return $parts ? implode(' / ', $parts) : t('Árbevétel');
    }

    /** Chart.js labels + datasets: periods on the x axis, one stacked series per manufacturer/webshop. */
    private function buildChart(array $data): array
    {
        if (!$data['idoszak']) {
            $rows = $data['rows'];
            usort($rows, fn($a, $b) => $b['ertek'] <=> $a['ertek']);
            return [
                'stacked' => false,
                'legend' => false,
                'labels' => array_map(fn($row) => $this->seriesLabel($row, $data), $rows),
                'datasets' => [['label' => t('Árbevétel'), 'data' => array_column($rows, 'ertek')]],
            ];
        }

        $labels = [];
        $series = [];
        foreach ($data['rows'] as $row) {
            $labels[$row['idoszak']] = true;
            $nev = $this->seriesLabel($row, $data);
            $series[$nev][$row['idoszak']] = ($series[$nev][$row['idoszak']] ?? 0) + $row['ertek'];
        }
        $labels = array_keys($labels);
        uasort($series, fn($a, $b) => array_sum($b) <=> array_sum($a));
        if (count($series) > self::MAXSERIES) {
            $egyeb = [];
            foreach (array_slice($series, self::MAXSERIES - 1, null, true) as $ertekek) {
                foreach ($ertekek as $idoszak => $ertek) {
                    $egyeb[$idoszak] = ($egyeb[$idoszak] ?? 0) + $ertek;
                }
            }
            $series = array_slice($series, 0, self::MAXSERIES - 1, true) + [t('Egyéb') => $egyeb];
        }
        $datasets = [];
        foreach ($series as $nev => $ertekek) {
            $datasets[] = [
                'label' => (string)$nev,
                'data' => array_map(fn($idoszak) => round($ertekek[$idoszak] ?? 0, 2), $labels),
            ];
        }
        return ['stacked' => true, 'legend' => $data['gyarto'] || $data['webshop'], 'labels' => $labels, 'datasets' => $datasets];
    }

    public function refresh()
    {
        $data = $this->getData();
        $view = $this->createView('arbevetellistatetel.tpl');
        foreach ($data as $key => $value) {
            $view->setVar($key, $value);
        }
        $view->setVar('osszesen', array_sum(array_column($data['rows'], 'ertek')));
        header('Content-Type: application/json');
        echo json_encode([
            'html' => $view->getTemplateResult(),
            'chart' => $this->buildChart($data),
        ]);
    }

    public function export()
    {
        $data = $this->getData();

        $fejlec = [];
        if ($data['idoszak']) {
            $fejlec['idoszak'] = t('Időszak');
        }
        if ($data['gyarto']) {
            $fejlec['gyartonev'] = t('Gyártó');
        }
        if ($data['webshop']) {
            $fejlec['webshopnev'] = t('Webshop');
        }
        $fejlec['ertek'] = $data['brutto'] ? t('Bruttó HUF') : t('Nettó HUF');

        $excel = new Spreadsheet();
        $sheet = $excel->getActiveSheet();
        $sheet->fromArray(array_values($fejlec), null, 'A1');
        $sor = 2;
        foreach ($data['rows'] as $row) {
            $sheet->fromArray(array_map(fn($key) => $row[$key], array_keys($fejlec)), null, 'A' . $sor);
            $sor++;
        }

        $filename = uniqid('arbevetel') . '.xlsx';
        $filepath = \mkw\store::storagePath($filename);
        IOFactory::createWriter($excel, 'Xlsx')->save($filepath);

        header('Cache-Control: private');
        header('Content-Type: application/stream');
        header('Content-Length: ' . filesize($filepath));
        header('Content-Disposition: attachment; filename=' . $filename);
        readfile($filepath);
        \unlink($filepath);
    }
}
