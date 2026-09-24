<?php

namespace Traits;

/** The grouped table and the chart series shared by the revenue, sales and pay reports. */
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
}
