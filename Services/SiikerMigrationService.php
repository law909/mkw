<?php

namespace Services;

use Services\Siiker\MigrationReport;
use Services\Siiker\PartnerMigrator;
use Services\Siiker\SiikerSource;
use Services\Siiker\TermekKepMigrator;
use Services\Siiker\TermekMigrator;
use Services\Siiker\TorzsMigrator;
use mkw\store;

/**
 * A SIIKer törzsadatainak átvitele az MKW adatbázisba (terv: docs/siikerdb-konvert.md).
 *
 * A forrás séma a config.ini `siiker.dbname` kulcsa, ugyanazon a szerveren. A lépések
 * (torzs, partner, termek, ar, kep) egyenként újrafuttathatók: minden lépés a SIIKer kódot
 * (migrid / idegenkod) kulcsként használó upsert, semmit nem töröl. Bizonylat, mozgás, menü nem
 * kerül át. Belépés: `php siikermigrate.php`.
 */
class SiikerMigrationService
{
    const STEPS = ['torzs', 'partner', 'termek', 'ar', 'kep'];

    /**
     * @param array $options step (vesszővel), dry-run, limit, quiet
     */
    public function run(array $options = []): MigrationReport
    {
        $report = new MigrationReport(!empty($options['quiet']));
        $source = SiikerSource::fromConfig();
        $steps = $this->parseSteps($options['step'] ?? null);
        $dryRun = !empty($options['dry-run']);

        $conn = store::getEm()->getConnection();
        $lock = substr('siikermigrate_' . $conn->getDatabase(), 0, 64);
        if ((int)$conn->fetchOne('SELECT GET_LOCK(?, 0)', [$lock]) !== 1) {
            throw new \RuntimeException('Már fut egy SIIKer migráció erre az adatbázisra');
        }

        $report->note('Forrás: ' . $source->getSchema() . ' → cél: ' . $conn->getDatabase() . ($dryRun ? ' (PRÓBA MENET, visszagörgetve)' : ''));
        if ($dryRun) {
            $conn->beginTransaction();
        }
        try {
            foreach ($steps as $step) {
                $report->section($step);
                if ($step === 'kep' && $dryRun) {
                    $report->note('A képlépés próba menetben kimarad (fájlt írna)');
                    continue;
                }
                $this->runStep($step, $source, $report, $options);
            }
            if ($dryRun) {
                $conn->rollBack();
                $report->note('Próba menet: minden változás visszagörgetve');
            }
        } catch (\Throwable $e) {
            if ($dryRun && $conn->isTransactionActive()) {
                $conn->rollBack();
            }
            $report->error('futás', get_class($e) . ': ' . $e->getMessage());
            throw $e;
        } finally {
            $report->finish();
            try {
                $conn->executeStatement('SELECT RELEASE_LOCK(?)', [$lock]);
            } catch (\Throwable $e) {
                // becsukott kapcsolaton a zár magától elenged
            }
        }
        return $report;
    }

    private function runStep(string $step, SiikerSource $source, MigrationReport $report, array $options): void
    {
        switch ($step) {
            case 'torzs':
                (new TorzsMigrator($source, $report, $options))->run();
                break;
            case 'partner':
                (new PartnerMigrator($source, $report, $options))->run();
                break;
            case 'termek':
                (new TermekMigrator($source, $report, $options))->run();
                break;
            case 'ar':
                (new TermekMigrator($source, $report, $options))->runAr();
                break;
            case 'kep':
                (new TermekKepMigrator($source, $report, $options))->run();
                break;
        }
    }

    private function parseSteps($step): array
    {
        if ($step === null || $step === '' || $step === true) {
            return self::STEPS;
        }
        $steps = [];
        foreach (explode(',', (string)$step) as $s) {
            $s = trim($s);
            if (!in_array($s, self::STEPS, true)) {
                throw new \RuntimeException('Ismeretlen lépés: ' . $s . ' (lehet: ' . implode(', ', self::STEPS) . ')');
            }
            $steps[$s] = true;
        }
        // a lépések sorrendje kötött, a megadás sorrendje nem számít
        return array_values(array_filter(self::STEPS, static fn($s) => isset($steps[$s])));
    }
}
