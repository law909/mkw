<?php

namespace Services\Cron;

use Services\ArfolyamService;

/**
 * MNB árfolyamok letöltése – ugyanaz, amit az admin „Árfolyam letöltés" gombja csinál
 * (\Services\ArfolyamService), csak ütemezve.
 *
 * Alapból a mai napra fut; a `--datum=2026.08.11` egy adott napot kér le, a `--napok=5`
 * pedig a mai naptól visszafelé ennyi napot (hétvégére eső hiányt így lehet pótolni).
 *
 * Hétvégén és ünnepnapon az MNB nem ad árfolyamot: ilyenkor a feladat 0 sorral, rendben fut le.
 * A meglévő – akár kézzel javított – árfolyamot nem írja felül.
 */
class ArfolyamTask implements CronTask
{

    private const NAPOKMAX = 30;

    public function getDescription(): string
    {
        return 'MNB árfolyamok letöltése (--datum=ÉÉÉÉ.HH.NN, --napok=N)';
    }

    public function isEnabled(): bool
    {
        return (bool)\mkw\store::isMultiValuta();
    }

    public function run(array $options = []): string
    {
        $service = new ArfolyamService();
        if (!$service->getValutanemNevek()) {
            throw new CronWarning('Nincs letöltendő valutanem az elszámolási valutanemen kívül.');
        }

        $napok = $this->getNapok($options);
        $kapott = 0;
        $mentve = 0;
        $reszletek = [];
        foreach ($napok as $datum) {
            $result = $service->download($datum);
            $kapott += $result['kapott'];
            $mentve += $result['mentve'];
            if ($result['mentve']) {
                $reszletek[] = $result['datum'] . ': ' . $result['mentve'];
            }
        }

        return sprintf(
            '%d nap, %d árfolyam az MNB-től, %d új sor',
            count($napok),
            $kapott,
            $mentve
        ) . ($reszletek ? ' (' . implode(', ', $reszletek) . ')' : '');
    }

    /**
     * A lekérendő napok. Alapból a mai; --datum egy adott napot, --napok a mától visszafelé
     * számolt ablakot adja.
     *
     * @return string[]
     */
    private function getNapok(array $options)
    {
        if (!empty($options['datum'])) {
            return [(string)$options['datum']];
        }
        $napok = isset($options['napok']) ? (int)$options['napok'] : 1;
        $napok = max(1, min($napok, self::NAPOKMAX));

        $ret = [];
        for ($i = $napok - 1; $i >= 0; $i--) {
            $ret[] = date(\mkw\store::$DateFormat, strtotime('-' . $i . ' day'));
        }
        return $ret;
    }
}
