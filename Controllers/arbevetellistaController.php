<?php

namespace Controllers;

use Entities\Bizonylatfej;
use Entities\BizonylatfejRepository;
use Entities\Valutanem;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Spreadsheet;

class arbevetellistaController extends \mkwhelpers\Controller
{

    // a stacked bar with more series than this is unreadable, the rest goes into one
    protected const MAXSERIES = 10;

    public function view()
    {
        $this->showView('arbevetellista.tpl', t('Árbevétel kimutatás'), BizonylatfejRepository::ARBEVETEL_BIZONYLATTIPUSOK);
    }

    /** @param array $bizonylattipusok ticked by default in the document type filter (superzoneb2b only) */
    protected function showView(string $tplname, string $pagetitle, array $bizonylattipusok)
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
        $view->setVar('uklist', (new uzletkotoController())->getSelectList());
        $view->setVar('valutanemlist', (new valutanemController())->getSelectList());
        $view->setVar('cimkekat', (new partnercimkekatController())->getWithCimkek());
        $view->setVar('bizonylattipusfilter', \mkw\store::isSuperzoneB2B());
        $view->setVar('bizonylattipuschecked', array_fill_keys($bizonylattipusok, true));

        $view->printTemplateResult(false);
    }

    protected function getData(string $repoMethod = 'getArbevetelLista'): array
    {
        $csoport = [];
        $idoszakcsoport = $this->params->getStringRequestParam('idoszakcsoport');
        if (in_array($idoszakcsoport, ['ev', 'honap'], true)) {
            $csoport[] = $idoszakcsoport;
        }
        $kategoriacsoport = $this->params->getStringRequestParam('kategoriacsoport');
        if (in_array($kategoriacsoport, ['fokategoria', 'kategoria'], true)) {
            $csoport[] = $kategoriacsoport;
        }
        if ($this->params->getBoolRequestParam('gyartocsoport')) {
            $csoport[] = 'gyarto';
        }
        if ($this->params->getBoolRequestParam('webshopcsoport')) {
            $csoport[] = 'webshop';
        }
        $brutto = $this->params->getStringRequestParam('ertektipus') === 'brutto';
        $valutanemid = $this->params->getIntRequestParam('valutanem');
        $valutanem = $valutanemid ? $this->getRepo(Valutanem::class)->find($valutanemid) : null;

        $rows = $this->getRepo(Bizonylatfej::class)->$repoMethod(
            $this->params->getStringRequestParam('datumtipus'),
            $this->params->getStringRequestParam('tol'),
            $this->params->getStringRequestParam('ig'),
            $brutto,
            $csoport,
            [
                'partner' => $this->params->getIntRequestParam('partner'),
                'partnertipus' => $this->params->getIntRequestParam('partnertipus'),
                'partnercimke' => $this->params->getArrayRequestParam('partnercimkefilter'),
                'uzletkoto' => $this->params->getIntRequestParam('uzletkoto'),
                'valutanem' => $valutanem?->getId(),
                'bizonylattipus' => \mkw\store::isSuperzoneB2B() ? $this->params->getArrayRequestParam('bizonylattipus') : [],
                'gyarto' => $this->params->getIntRequestParam('gyarto'),
                'fafilter' => $this->params->getArrayRequestParam('fafilter'),
                'webshopnum' => $this->params->getStringRequestParam('webshopnum'),
                'nev' => $this->params->getStringRequestParam('nev'),
            ]
        );

        $currency = $valutanem ? $valutanem->getNev() : 'HUF';
        return [
            'rows' => $rows,
            'brutto' => $brutto,
            'currency' => $currency,
            'valueheader' => ($brutto ? t('Bruttó') : t('Nettó')) . ' ' . $currency,
            'idoszak' => (bool)array_intersect(['ev', 'honap'], $csoport),
            'kategoria' => (bool)array_intersect(['fokategoria', 'kategoria'], $csoport),
            'gyarto' => in_array('gyarto', $csoport, true),
            'webshop' => in_array('webshop', $csoport, true),
        ];
    }

    protected function seriesLabel(array $row, array $data): string
    {
        $parts = [];
        if ($data['kategoria']) {
            $parts[] = $row['kategorianev'];
        }
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
                'unit' => $data['currency'],
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
            'legend' => $data['kategoria'] || $data['gyarto'] || $data['webshop'],
            'labels' => $labels,
            'datasets' => $datasets,
            'unit' => $data['currency'],
            'note' => $note,
        ];
    }

    /**
     * Series (label => values by period) sorted by total, the smallest merged into one when there are too many.
     * Returns the series and the explanation to show above the chart, null when nothing was merged.
     */
    protected function mergeSmallSeries(array $series): array
    {
        uasort($series, fn($a, $b) => array_sum($b) <=> array_sum($a));
        if (count($series) <= self::MAXSERIES) {
            return [$series, null];
        }
        $shown = array_slice($series, 0, self::MAXSERIES - 1, true);
        $merged = array_slice($series, self::MAXSERIES - 1, null, true);
        $egyeb = [];
        foreach ($merged as $ertekek) {
            foreach ($ertekek as $idoszak => $ertek) {
                $egyeb[$idoszak] = ($egyeb[$idoszak] ?? 0) + $ertek;
            }
        }
        $names = array_map('strval', array_keys($merged));
        $note = sprintf(
            t('Egyéb: a %d legnagyobb csoport után következő további %d csoport együtt (pl. %s).'),
            count($shown),
            count($merged),
            implode(', ', array_slice($names, 0, 3)) . (count($names) > 3 ? ', …' : '')
        );
        // a real group can also be called "Egyéb", e.g. a category
        $isEgyeb = fn($name) => mb_strtolower(trim((string)$name)) === mb_strtolower(t('Egyéb'));
        if ($own = array_filter(array_map('strval', array_keys($shown)), $isEgyeb)) {
            $note .= ' ' . sprintf(t('Az „%s” nevű csoport ettől független, külön sávként látszik.'), reset($own));
        } elseif ($own = array_filter($names, $isEgyeb)) {
            $note .= ' ' . sprintf(t('Az „%s” nevű csoport is ebben az összevonásban van.'), reset($own));
        }
        return [$shown + [sprintf(t('Egyéb (további %d)'), count($merged)) => $egyeb], $note];
    }

    /** The active grouping columns in list order: id decides where a group ends, label is what is shown. */
    protected function getGroupLevels(array $data): array
    {
        $levels = [];
        if ($data['idoszak']) {
            $levels[] = ['id' => 'idoszak', 'label' => 'idoszak', 'caption' => t('Időszak')];
        }
        if ($data['kategoria']) {
            $levels[] = ['id' => 'kategoriakarkod', 'label' => 'kategorianev', 'caption' => t('Kategória')];
        }
        if ($data['gyarto']) {
            $levels[] = ['id' => 'gyartoid', 'label' => 'gyartonev', 'caption' => t('Gyártó')];
        }
        if ($data['webshop']) {
            $levels[] = ['id' => 'webshopnum', 'label' => 'webshopnev', 'caption' => t('Webshop')];
        }
        return $levels;
    }

    /**
     * The rows (sorted by the levels) as a flat render list of 'header', 'row' and 'subtotal' items. A group of a
     * single row gets no subtotal. 'share' is the item's part of its enclosing group, the top level's of the total.
     */
    protected function buildTableItems(array $rows, array $levels, array $sumKeys): array
    {
        $items = [];
        $open = [];
        $topChildren = [];
        $setShares = function (array $children, float $total) use (&$items) {
            foreach ($children as $index) {
                $items[$index]['share'] = $total ? $items[$index]['ertek'] / $total * 100 : null;
            }
        };
        $close = function (int $from) use (&$open, &$items, &$topChildren, $setShares) {
            for ($level = count($open) - 1; $level >= $from; $level--) {
                $group = array_pop($open);
                if ($group['count'] > 1) {
                    $setShares($group['children'], $group['sums']['ertek']);
                    $items[] = ['type' => 'subtotal', 'level' => $level, 'label' => $group['label']] + $group['sums'];
                    $children = [array_key_last($items)];
                } else {
                    // no subtotal line: its only row takes the group's share in the enclosing group
                    $children = $group['children'];
                }
                if ($level > 0) {
                    array_push($open[$level - 1]['children'], ...$children);
                } else {
                    array_push($topChildren, ...$children);
                }
            }
        };
        foreach ($rows as $row) {
            $changed = null;
            foreach ($levels as $level => $def) {
                if (!isset($open[$level]) || $open[$level]['key'] !== (string)$row[$def['id']]) {
                    $changed = $level;
                    break;
                }
            }
            if ($changed !== null) {
                $close($changed);
                for ($level = $changed; $level < count($levels); $level++) {
                    $label = $row[$levels[$level]['label']];
                    $open[$level] = ['key' => (string)$row[$levels[$level]['id']], 'label' => $label, 'sums' => array_fill_keys($sumKeys, 0), 'count' => 0, 'children' => []];
                    $items[] = ['type' => 'header', 'level' => $level, 'label' => $label];
                }
            }
            foreach ($open as &$group) {
                foreach ($sumKeys as $key) {
                    $group['sums'][$key] += $row[$key];
                }
                $group['count']++;
            }
            unset($group);
            $items[] = ['type' => 'row', 'level' => count($levels), 'row' => $row, 'ertek' => $row['ertek']];
            if ($open) {
                $open[count($open) - 1]['children'][] = array_key_last($items);
            } else {
                $topChildren[] = array_key_last($items);
            }
        }
        $close(0);
        $setShares($topChildren, array_sum(array_column($rows, 'ertek')));
        return $items;
    }

    public function refresh()
    {
        $data = $this->getData();
        $levels = $this->getGroupLevels($data);
        // the last grouping column is the row label, the ones above it become group headers
        $rowLevel = array_pop($levels);
        $view = $this->createView('arbevetellistatetel.tpl');
        $view->setVar('items', $this->buildTableItems($data['rows'], $levels, ['ertek']));
        $view->setVar('rowlevel', $rowLevel);
        $view->setVar('levelcount', count($levels));
        $view->setVar('valueheader', $data['valueheader']);
        $view->setVar('decimals', $data['currency'] === 'HUF' ? 0 : 2);
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
        if ($data['kategoria']) {
            $fejlec['kategorianev'] = t('Kategória');
        }
        if ($data['gyarto']) {
            $fejlec['gyartonev'] = t('Gyártó');
        }
        if ($data['webshop']) {
            $fejlec['webshopnev'] = t('Webshop');
        }
        $fejlec['ertek'] = $data['valueheader'];

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
