<?php

namespace Controllers;

use Entities\Dolgozober;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Spreadsheet;

/** Paid wages over a period, by year/month, employee and pay title; voided lines never count. */
class berkimutatasController extends \mkwhelpers\Controller
{

    use \Traits\GroupedReport;

    // the same right as the pay lines themselves
    private const BERJOG = 40;

    public function view()
    {
        if (!\mkw\store::haveJog(self::BERJOG)) {
            return;
        }
        $view = $this->createView('berkimutatas.tpl');
        $view->setVar('pagetitle', t('Bér kimutatás'));
        $view->setVar('toldatum', date('Y.01.01'));
        $view->setVar('igdatum', date(\mkw\store::$DateFormat));
        $view->printTemplateResult(false);
    }

    private function getData(): array
    {
        $csoport = [];
        $idoszakcsoport = $this->params->getStringRequestParam('idoszakcsoport');
        if (in_array($idoszakcsoport, ['ev', 'honap'], true)) {
            $csoport[] = $idoszakcsoport;
        }
        if ($this->params->getBoolRequestParam('dolgozocsoport')) {
            $csoport[] = 'dolgozo';
        }
        if ($this->params->getBoolRequestParam('berjogcimcsoport')) {
            $csoport[] = 'berjogcim';
        }
        return [
            'rows' => $this->getRepo(Dolgozober::class)->getKimutatas(
                $this->params->getStringRequestParam('tol'),
                $this->params->getStringRequestParam('ig'),
                $csoport
            ),
            'idoszak' => (bool)array_intersect(['ev', 'honap'], $csoport),
            'dolgozo' => in_array('dolgozo', $csoport, true),
            'berjogcim' => in_array('berjogcim', $csoport, true),
        ];
    }

    private function getGroupLevels(array $data): array
    {
        $levels = [];
        if ($data['idoszak']) {
            $levels[] = ['id' => 'idoszak', 'label' => 'idoszak', 'caption' => t('Időszak')];
        }
        if ($data['dolgozo']) {
            $levels[] = ['id' => 'dolgozoid', 'label' => 'dolgozonev', 'caption' => t('Dolgozó')];
        }
        if ($data['berjogcim']) {
            $levels[] = ['id' => 'berjogcimid', 'label' => 'berjogcimnev', 'caption' => t('Jogcím')];
        }
        return $levels;
    }

    private function seriesLabel(array $row, array $data): string
    {
        $parts = [];
        if ($data['dolgozo']) {
            $parts[] = $row['dolgozonev'];
        }
        if ($data['berjogcim']) {
            $parts[] = $row['berjogcimnev'];
        }
        return $parts ? implode(' / ', $parts) : t('Bér');
    }

    /** Periods on the x axis with one stacked series per employee/title, like the revenue chart. */
    private function buildChart(array $data): array
    {
        $csoportos = $data['dolgozo'] || $data['berjogcim'];
        if (!$data['idoszak']) {
            $rows = $data['rows'];
            usort($rows, fn($a, $b) => $b['ertek'] <=> $a['ertek']);
            return [
                'stacked' => false,
                'legend' => false,
                'labels' => array_map(fn($row) => $this->seriesLabel($row, $data), $rows),
                'datasets' => [['label' => t('Bér'), 'data' => array_column($rows, 'ertek')]],
                'unit' => 'Ft',
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
        [$series, $note] = $this->mergeSmallSeries($series);
        $datasets = [];
        foreach ($series as $nev => $ertekek) {
            $datasets[] = [
                'label' => (string)$nev,
                'data' => array_map(fn($idoszak) => round($ertekek[$idoszak] ?? 0, 2), $labels),
            ];
        }
        return [
            'stacked' => true,
            'legend' => $csoportos,
            'labels' => $labels,
            'datasets' => $datasets,
            'unit' => 'Ft',
            'note' => $note,
        ];
    }

    public function refresh()
    {
        if (!\mkw\store::haveJog(self::BERJOG)) {
            $this->jsonError(t('Nincs jogosultsága a művelethez.'), 403);
            return;
        }
        $data = $this->getData();
        $levels = $this->getGroupLevels($data);
        // the last grouping column is the row label, the ones above it become group headers
        $rowLevel = array_pop($levels);
        // the revenue report's grouped table, only the value header differs
        $view = $this->createView('arbevetellistatetel.tpl');
        $view->setVar('items', $this->buildTableItems($data['rows'], $levels, ['ertek']));
        $view->setVar('rowlevel', $rowLevel);
        $view->setVar('levelcount', count($levels));
        $view->setVar('valueheader', t('Összeg HUF'));
        $view->setVar('decimals', 0);
        $view->setVar('osszesen', array_sum(array_column($data['rows'], 'ertek')));
        header('Content-Type: application/json');
        echo json_encode([
            'html' => $view->getTemplateResult(),
            'chart' => $this->buildChart($data),
        ]);
    }

    public function export()
    {
        if (!\mkw\store::haveJog(self::BERJOG)) {
            return;
        }
        $data = $this->getData();

        $fejlec = [];
        foreach ($this->getGroupLevels($data) as $level) {
            $fejlec[$level['label']] = $level['caption'];
        }
        $fejlec['ertek'] = t('Összeg HUF');

        $excel = new Spreadsheet();
        $sheet = $excel->getActiveSheet();
        $sheet->fromArray(array_values($fejlec), null, 'A1');
        $sor = 2;
        foreach ($data['rows'] as $row) {
            $sheet->fromArray(array_map(fn($key) => $row[$key], array_keys($fejlec)), null, 'A' . $sor);
            $sor++;
        }

        $filename = uniqid('berkimutatas') . '.xlsx';
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
