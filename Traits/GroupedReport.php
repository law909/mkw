<?php

namespace Traits;

/** The grouped table and the chart series shared by the revenue, sales, pay and commission reports. */
trait GroupedReport
{

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

    /**
     * Series (label => values by period) sorted by total, the smallest merged into one when there are too many:
     * a stacked bar with more series than $maxSeries is unreadable.
     * Returns the series and the explanation to show above the chart, null when nothing was merged.
     */
    protected function mergeSmallSeries(array $series, int $maxSeries = 10): array
    {
        uasort($series, fn($a, $b) => array_sum($b) <=> array_sum($a));
        if (count($series) <= $maxSeries) {
            return [$series, null];
        }
        $shown = array_slice($series, 0, $maxSeries - 1, true);
        $merged = array_slice($series, $maxSeries - 1, null, true);
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

    /**
     * Chart.js payload of grouped rows: the period level (else the first level) on the x axis, the other levels
     * together as stacked series. A non-period axis is sorted by total and cut at $maxBars.
     *
     * @param array $levels as for buildTableItems(), period levels have 'idoszak' as their id
     */
    protected function buildLevelChart(array $rows, array $levels, string $valueKey, string $caption, string $unit = '', int $maxBars = 30): array
    {
        $xLevel = null;
        foreach ($levels as $level) {
            if ($level['id'] === 'idoszak') {
                $xLevel = $level;
            }
        }
        $timeAxis = (bool)$xLevel;
        $xLevel = $xLevel ?? ($levels[0] ?? null);
        $seriesLevels = array_values(array_filter($levels, fn($level) => $level !== $xLevel));

        $labels = [];
        $series = [];
        foreach ($rows as $row) {
            $x = $xLevel ? (string)$row[$xLevel['id']] : '';
            $labels[$x] = $xLevel ? (string)$row[$xLevel['label']] : $caption;
            $nev = $seriesLevels ? implode(' / ', array_map(fn($level) => (string)$row[$level['label']], $seriesLevels)) : $caption;
            $series[$nev][$x] = ($series[$nev][$x] ?? 0) + $row[$valueKey];
        }

        $notes = [];
        if ($timeAxis) {
            // the rows come sorted by the levels, a period that is not the first one is not in order ('2025-01' or '2025')
            ksort($labels, SORT_STRING);
        } else {
            $totals = [];
            foreach ($series as $values) {
                foreach ($values as $x => $value) {
                    $totals[$x] = ($totals[$x] ?? 0) + $value;
                }
            }
            arsort($totals);
            if (count($totals) > $maxBars) {
                $notes[] = sprintf(t('A diagramon a %d legnagyobb oszlop látszik a %d közül; a táblázat mindet tartalmazza.'), $maxBars, count($totals));
                $totals = array_slice($totals, 0, $maxBars, true);
            }
            $labels = array_intersect_key(array_replace($totals, $labels), $totals);
        }
        [$series, $note] = $this->mergeSmallSeries($series);
        if ($note) {
            $notes[] = $note;
        }

        $xs = array_keys($labels);
        $datasets = [];
        foreach ($series as $nev => $values) {
            $datasets[] = [
                'label' => (string)$nev,
                'data' => array_map(fn($x) => round($values[$x] ?? 0, 2), $xs),
            ];
        }
        return [
            'stacked' => (bool)$seriesLevels,
            'legend' => (bool)$seriesLevels,
            'labels' => array_map('strval', array_values($labels)),
            'datasets' => $datasets,
            'unit' => $unit,
            'note' => $notes ? implode(' ', $notes) : null,
        ];
    }

    /**
     * Turns the rows around the period level for a cross table: the period leaves the levels, and each remaining
     * row gets $valueKey per period as 'p0', 'p1', … and their total as 'ertek' (what buildTableItems() shares on).
     * The rows must come sorted by the remaining levels, the period last. Null when there is no period level.
     *
     * @param array $rowKeys what else tells two rows apart besides the levels (e.g. the product)
     */
    protected function pivotRows(array $rows, array $levels, string $valueKey, array $rowKeys = []): ?array
    {
        $rest = array_values(array_filter($levels, fn($level) => $level['id'] !== 'idoszak'));
        if (count($rest) === count($levels)) {
            return null;
        }
        $periods = array_values(array_unique(array_map('strval', array_column($rows, 'idoszak'))));
        sort($periods);
        $index = array_flip($periods);
        $keys = array_merge(array_column($rest, 'id'), $rowKeys);

        $pivot = [];
        foreach ($rows as $row) {
            $key = implode("\x1f", array_map(fn($k) => (string)$row[$k], $keys));
            if (!isset($pivot[$key])) {
                $pivot[$key] = $row;
                unset($pivot[$key]['idoszak']);
                foreach (array_keys($periods) as $i) {
                    $pivot[$key]['p' . $i] = 0;
                }
                $pivot[$key]['ertek'] = 0;
            }
            $pivot[$key]['p' . $index[(string)$row['idoszak']]] += $row[$valueKey];
            $pivot[$key]['ertek'] += $row[$valueKey];
        }
        $pivot = array_values($pivot);
        $totals = [];
        foreach (array_keys($periods) as $i) {
            $totals[$i] = array_sum(array_column($pivot, 'p' . $i));
        }
        return [
            'rows' => $pivot,
            'levels' => $rest,
            'periods' => $periods,
            'sumkeys' => array_merge(['ertek'], array_map(fn($i) => 'p' . $i, array_keys($periods))),
            'coltotals' => $totals,
            'total' => array_sum($totals),
        ];
    }

    /** The cross table of pivotRows(); $termeksor: the rows are products under all the levels. */
    protected function renderPivot(array $pivot, bool $termeksor, string $valueheader, int $decimals): string
    {
        $levels = $pivot['levels'];
        $rowLevel = $termeksor ? null : array_pop($levels);
        $view = $this->createView('arbevetelpivot.tpl');
        $view->setVar('items', $this->buildTableItems($pivot['rows'], $levels, $pivot['sumkeys']));
        $view->setVar('rowlevel', $rowLevel);
        $view->setVar('levelcount', count($levels));
        $view->setVar('termeksor', $termeksor);
        $view->setVar('periods', $pivot['periods']);
        $view->setVar('coltotals', $pivot['coltotals']);
        $view->setVar('total', $pivot['total']);
        $view->setVar('valueheader', $valueheader);
        $view->setVar('decimals', $decimals);
        return $view->getTemplateResult();
    }

    /**
     * The report as an inline PDF: the filters, the chart the page posted as a PNG (it is drawn in the browser) and
     * the table of refresh(). A cross table gets a landscape page.
     *
     * @param array $szurok [caption, value] lines under the title
     * @param array $report ['html' => the table, 'chart' => the chart payload]
     */
    protected function outputReportPdf(string $title, array $szurok, array $report, string $filename): void
    {
        $view = $this->createView('kimutataspdf.tpl');
        $view->setVar('title', $title);
        $view->setVar('szurok', $szurok);
        $view->setVar('chart', $this->getChartImage());
        $view->setVar('chartnote', $report['chart']['note'] ?? null);
        $view->setVar('table', $report['html']);
        $view->setVar('landscape', $this->params->getStringRequestParam('megjelenites') === 'kereszttabla');
        $view->setVar('generated', date(\mkw\store::$DateFormat . ' H:i'));
        $pdf = new \mkw\mkwmpdf($view->getTemplateResult());
        $pdf->getEngine()->SetTitle($title);
        $pdf->inline($filename);
    }

    /** The posted chart as a PNG data URI, null unless it really is a PNG. */
    private function getChartImage(): ?string
    {
        $prefix = 'data:image/png;base64,';
        $data = $this->params->getOriginalStringRequestParam('chart');
        if (!str_starts_with($data, $prefix)) {
            return null;
        }
        $png = base64_decode(substr($data, strlen($prefix)), true);
        $info = $png === false ? false : @getimagesizefromstring($png);
        if (!$info || $info[2] !== IMAGETYPE_PNG) {
            return null;
        }
        return $prefix . base64_encode($png);
    }

    /** The PDF's line of the grouping levels, e.g. "hónap › partner (kereszttábla)". */
    protected function getGroupingSzuro(array $levels): array
    {
        $captions = array_map(fn($level) => mb_strtolower($level['caption']), $levels);
        $value = $captions ? implode(' › ', $captions) : t('nincs');
        if ($this->params->getStringRequestParam('megjelenites') === 'kereszttabla') {
            $value .= ' (' . t('kereszttábla') . ')';
        }
        return [t('Csoportosítás'), $value];
    }
}
