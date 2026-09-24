<?php

namespace Controllers;

use Entities\BizonylatfejRepository;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Spreadsheet;

/** The revenue report's documents and filters, with sold quantity per product / variant. */
class forgalmilistaController extends arbevetellistaController
{

    private const MAXTERMEK = 20;

    public function view()
    {
        $this->showView(
            'forgalmilista.tpl',
            t('Forgalmi kimutatás'),
            array_values(array_diff(BizonylatfejRepository::ARBEVETEL_BIZONYLATTIPUSOK, [\Services\ElolegService::BIZTIPUS]))
        );
    }

    private function termekLabel(array $row): string
    {
        $valtozat = implode(' ', array_filter([$row['ertek1'], $row['ertek2']], fn($e) => $e !== null && $e !== ''));
        return trim(implode(' ', array_filter([$row['cikkszam'], $row['nev'], $valtozat])));
    }

    /** Chart.js labels + datasets of the quantity: periods on the x axis like the revenue chart. */
    private function buildChart(array $data): array
    {
        $csoportos = $data['kategoria'] || $data['gyarto'] || $data['webshop'];
        if (!$data['idoszak']) {
            $oszlopok = [];
            foreach ($data['rows'] as $row) {
                $nev = $csoportos ? $this->seriesLabel($row, $data) : $this->termekLabel($row);
                $oszlopok[$nev] = ($oszlopok[$nev] ?? 0) + $row['mennyiseg'];
            }
            arsort($oszlopok);
            $note = null;
            // without grouping there is one bar per product: only the best sellers fit
            if (!$csoportos && count($oszlopok) > self::MAXTERMEK) {
                $note = sprintf(t('A diagramon a %d legtöbbet eladott termék látszik a %d közül; a táblázat mindet tartalmazza.'), self::MAXTERMEK, count($oszlopok));
                $oszlopok = array_slice($oszlopok, 0, self::MAXTERMEK, true);
            }
            return [
                'stacked' => false,
                'legend' => false,
                'labels' => array_map('strval', array_keys($oszlopok)),
                'datasets' => [['label' => t('Mennyiség'), 'data' => array_map(fn($m) => round($m, 2), array_values($oszlopok))]],
                'note' => $note,
            ];
        }

        $labels = [];
        $series = [];
        foreach ($data['rows'] as $row) {
            $labels[$row['idoszak']] = true;
            $nev = $csoportos ? $this->seriesLabel($row, $data) : t('Mennyiség');
            $series[$nev][$row['idoszak']] = ($series[$nev][$row['idoszak']] ?? 0) + $row['mennyiseg'];
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
        return ['stacked' => true, 'legend' => $csoportos, 'labels' => $labels, 'datasets' => $datasets, 'note' => $note];
    }

    public function refresh()
    {
        $data = $this->getData('getForgalmiLista');
        $levels = $this->getGroupLevels($data);
        $view = $this->createView('forgalmilistatetel.tpl');
        $view->setVar('items', $this->buildTableItems($data['rows'], $levels, ['mennyiseg', 'ertek']));
        $view->setVar('levelcount', count($levels));
        $view->setVar('valueheader', $data['valueheader']);
        $view->setVar('decimals', $data['currency'] === 'HUF' ? 0 : 2);
        $mennyisegek = array_column($data['rows'], 'mennyiseg');
        $view->setVar('mennyisegdecimals', array_filter($mennyisegek, fn($m) => floor($m) != $m) ? 2 : 0);
        $view->setVar('osszesenmennyiseg', round(array_sum($mennyisegek), 2));
        $view->setVar('osszesen', array_sum(array_column($data['rows'], 'ertek')));
        header('Content-Type: application/json');
        echo json_encode([
            'html' => $view->getTemplateResult(),
            'chart' => $this->buildChart($data),
        ]);
    }

    public function export()
    {
        $data = $this->getData('getForgalmiLista');

        $fejlec = [];
        if ($data['idoszak']) {
            $fejlec['idoszak'] = t('Időszak');
        }
        if ($data['kategoria']) {
            $fejlec['kategorianev'] = t('Kategória');
        }
        if ($data['gyarto']) {
            $fejlec['gyartonev'] = t('Gyártó');
        }
        if ($data['webshop']) {
            $fejlec['webshopnev'] = t('Webshop');
        }
        $fejlec['cikkszam'] = t('Cikkszám');
        $fejlec['nev'] = t('Név');
        $fejlec['ertek1'] = t('Változat 1');
        $fejlec['ertek2'] = t('Változat 2');
        $fejlec['mennyiseg'] = t('Mennyiség');
        $fejlec['me'] = t('ME');
        $fejlec['ertek'] = $data['valueheader'];

        $excel = new Spreadsheet();
        $sheet = $excel->getActiveSheet();
        $sheet->fromArray(array_values($fejlec), null, 'A1');
        $sor = 2;
        foreach ($data['rows'] as $row) {
            $sheet->fromArray(array_map(fn($key) => $row[$key], array_keys($fejlec)), null, 'A' . $sor);
            $sor++;
        }

        $filename = uniqid('forgalmilista') . '.xlsx';
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
