<?php

namespace Services;

use Entities\Cronlog;
use Services\Cron\CronTask;
use Services\Cron\CronWarning;

/**
 * A cron feladatok regisztere és futtatója: zárolás, időmérés, naplózás, kilépési kód.
 * A belépési pont a `cron.php` a repo gyökerében – lásd `docs/cron.md`.
 *
 * Új feladat felvétele: egy `Services\Cron\<Valami>Task` osztály (a `CronTask` interfésszel)
 * és egy sor a TASKS tömbben. A crontabot kézzel kell bővíteni, a regiszter nem ütemez.
 */
class CronService
{

    /** a crontab CSAK a nem nulla kilépési kódnál (és a stderr-re írt szövegnél) küld levelet */
    public const EXITOK = 0;
    public const EXITERROR = 1;
    public const EXITUNKNOWN = 2;

    /** nem naplózott állapotok: ezekben a feladat hozzá sem kezdett a munkához */
    public const ALLAPOTKIHAGYVA = 'kihagyva';
    public const ALLAPOTISMERETLEN = 'ismeretlen';

    /** feladatnév => osztály */
    private const TASKS = [
        'unasgetorder' => Cron\UnasGetOrderTask::class,
        'unassetorder' => Cron\UnasSetOrderTask::class,
        'cleanup' => Cron\CleanupTask::class,
        'joga' => Cron\JogaBejelentkezesTask::class,
        'test' => Cron\TestTask::class,
    ];

    private const UZENETMAX = 5000;

    /**
     * @return array feladatnév => ['leiras' => string, 'bekapcsolva' => bool]
     */
    public function getTaskList()
    {
        $result = [];
        foreach (array_keys(self::TASKS) as $name) {
            $task = $this->getTask($name);
            $result[$name] = [
                'leiras' => $task->getDescription(),
                'bekapcsolva' => $task->isEnabled()
            ];
        }
        return $result;
    }

    /**
     * @return CronTask|null
     */
    public function getTask($name)
    {
        if (!isset(self::TASKS[$name])) {
            return null;
        }
        $class = self::TASKS[$name];
        return new $class();
    }

    /**
     * Egy feladat lefuttatása a teljes kerettel.
     *
     * @param string $name
     * @param array $options a parancssori kapcsolók; `nolock` esetén nincs zárolás
     *
     * @return array{exitcode: int, allapot: string, uzenet: string, idotartam: float}
     */
    public function run($name, array $options = [])
    {
        $task = $this->getTask($name);
        if (!$task) {
            return [
                'exitcode' => self::EXITUNKNOWN,
                'allapot' => self::ALLAPOTISMERETLEN,
                'uzenet' => 'Ismeretlen feladat: ' . $name . '. A listát a --list kapcsoló adja.',
                'idotartam' => 0.0
            ];
        }
        if (!$task->isEnabled()) {
            // Nincs naplósor: egy tíz percenként futó, kikapcsolt feladat különben tele
            // szemetelné a naplót, miközben a válasz mindig ugyanaz.
            return [
                'exitcode' => self::EXITOK,
                'allapot' => self::ALLAPOTKIHAGYVA,
                'uzenet' => 'A feladat ezen a telepítésen nincs bekapcsolva.',
                'idotartam' => 0.0
            ];
        }

        $lock = empty($options['nolock']) ? $this->lockName($name) : null;
        if ($lock && !$this->acquireLock($lock)) {
            $uzenet = 'Az előző futás még dolgozik, ez a menet kimarad.';
            $this->logSkipped($name, Cronlog::ALLAPOTZAROLT, $uzenet);
            return [
                'exitcode' => self::EXITOK,
                'allapot' => Cronlog::ALLAPOTZAROLT,
                'uzenet' => $uzenet,
                'idotartam' => 0.0
            ];
        }

        $start = microtime(true);
        $logId = $this->logStart($name);
        try {
            $uzenet = $task->run($options);
            $allapot = Cronlog::ALLAPOTOK;
            $exitcode = self::EXITOK;
        } catch (CronWarning $w) {
            $uzenet = $w->getMessage();
            $allapot = Cronlog::ALLAPOTFIGYELEM;
            $exitcode = self::EXITOK;
        } catch (\Throwable $e) {
            $uzenet = get_class($e) . ': ' . $e->getMessage() . ' @ ' . $e->getFile() . ':' . $e->getLine();
            $allapot = Cronlog::ALLAPOTHIBA;
            $exitcode = self::EXITERROR;
        } finally {
            if ($lock) {
                $this->releaseLock($lock);
            }
        }

        $idotartam = round(microtime(true) - $start, 2);
        $this->logFinish($logId, $name, $allapot, $uzenet, $idotartam);

        return [
            'exitcode' => $exitcode,
            'allapot' => $allapot,
            'uzenet' => $uzenet,
            'idotartam' => $idotartam
        ];
    }

    // ------------------------------------------------------------------
    // Zárolás
    // ------------------------------------------------------------------

    /**
     * A GET_LOCK neve az egész MySQL szerverre szól, nem az adatbázisra: két telepítés
     * közös szerveren a puszta feladatnévvel egymást zárná ki. (5.7 fölött 64 karakter a max.)
     */
    private function lockName($name)
    {
        return substr('mkwcron_' . (string)$this->getConnection()->getDatabase() . '_' . $name, 0, 64);
    }

    private function acquireLock($lock)
    {
        return (int)$this->getConnection()->fetchOne('SELECT GET_LOCK(?, 0)', [$lock]) === 1;
    }

    private function releaseLock($lock)
    {
        try {
            $this->getConnection()->executeStatement('SELECT RELEASE_LOCK(?)', [$lock]);
        } catch (\Throwable $e) {
            // a kapcsolat elszállásával a zár magától elenged, nincs mit tenni
        }
    }

    // ------------------------------------------------------------------
    // Naplózás
    // ------------------------------------------------------------------

    /**
     * A naplósorok NEM az ORM-en mennek: ha a feladat Doctrine-hibával áll meg, az
     * EntityManager bezárul, és éppen a hibás futásról nem maradna nyom. A DBAL kapcsolat
     * ilyenkor is él. A fájlnapló pedig akkor is megvan, ha már a DB sem elérhető.
     *
     * @return int|null a napló sor azonosítója
     */
    private function logStart($name)
    {
        try {
            $conn = $this->getConnection();
            $conn->insert('cronlog', [
                'feladat' => $name,
                'allapot' => Cronlog::ALLAPOTFUT,
                'kezdet' => date('Y-m-d H:i:s'),
                'host' => substr((string)gethostname(), 0, 100),
                'pid' => getmypid() ?: null
            ]);
            return (int)$conn->lastInsertId();
        } catch (\Throwable $e) {
            \mkw\store::writelog('cron napló hiba (' . $name . '): ' . $e->getMessage(), 'cron.txt');
            return null;
        }
    }

    private function logFinish($logId, $name, $allapot, $uzenet, $idotartam)
    {
        $uzenet = mb_substr((string)$uzenet, 0, self::UZENETMAX);
        \mkw\store::writelog($name . ' | ' . $allapot . ' | ' . $idotartam . 's | ' . $uzenet, 'cron.txt');
        if (!$logId) {
            return;
        }
        try {
            $this->getConnection()->update(
                'cronlog',
                [
                    'allapot' => $allapot,
                    'veg' => date('Y-m-d H:i:s'),
                    'idotartam' => $idotartam,
                    'uzenet' => $uzenet
                ],
                ['id' => $logId]
            );
        } catch (\Throwable $e) {
            \mkw\store::writelog('cron napló hiba (' . $name . '): ' . $e->getMessage(), 'cron.txt');
        }
    }

    /** Egyetlen sorban lezárt futás: a menet el sem indult (zárolt). */
    private function logSkipped($name, $allapot, $uzenet)
    {
        \mkw\store::writelog($name . ' | ' . $allapot . ' | ' . $uzenet, 'cron.txt');
        try {
            $this->getConnection()->insert('cronlog', [
                'feladat' => $name,
                'allapot' => $allapot,
                'kezdet' => date('Y-m-d H:i:s'),
                'veg' => date('Y-m-d H:i:s'),
                'idotartam' => 0,
                'uzenet' => $uzenet,
                'host' => substr((string)gethostname(), 0, 100),
                'pid' => getmypid() ?: null
            ]);
        } catch (\Throwable $e) {
            \mkw\store::writelog('cron napló hiba (' . $name . '): ' . $e->getMessage(), 'cron.txt');
        }
    }

    /**
     * @return \Doctrine\DBAL\Connection
     */
    private function getConnection()
    {
        return \mkw\store::getEm()->getConnection();
    }
}
