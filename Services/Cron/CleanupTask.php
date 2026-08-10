<?php

namespace Services\Cron;

use Entities\Cronlog;

/**
 * Napi takarítás: lejárt full-page cache fájlok, túlnőtt naplók forgatása, régi cron napló sorok.
 *
 * A méret- és korhatárok a config.ini-ből jönnek:
 *   cron.logmaxsize   = 10M   ; ennél nagyobb naplót forgat (0 = nincs forgatás)
 *   cron.logretention = 30    ; ennyi napnál régebbi cron napló sor törlődik (0 = nincs törlés)
 *
 * Az UNAS letöltött termékadatbázisát és a nyers rendelés-XML-eket szándékosan NEM bántja:
 * egy félbehagyott sorablakos import fájljából folytatható a menet (lásd
 * `UnasTermekImportService::protectedFile()`), és a takarításuknak saját, védett útja van.
 */
class CleanupTask implements CronTask
{

    private const LOGMAXSIZE = '10M';
    private const LOGRETENTION = 30;

    /** amit forgatunk: a többi (xml, csv, json, html) generált melléktermék, nem folyamatos napló */
    private const LOGEXTENSIONS = ['txt', 'log'];

    public function getDescription(): string
    {
        return 'Lejárt oldalcache, túlnőtt naplók forgatása, régi cron napló sorok törlése';
    }

    public function isEnabled(): bool
    {
        return true;
    }

    public function run(array $options = []): string
    {
        $cache = \mkw\pagecache::gc();
        $forgatva = $this->rotateLogs();
        $torolve = $this->purgeCronlog();

        return sprintf(
            'oldalcache: %d lejárt fájl, %d félbehagyott .tmp | napló: %d forgatva | cron napló: %d sor törölve',
            $cache['fajl'],
            $cache['tmp'],
            count($forgatva),
            $torolve
        ) . ($forgatva ? ' (' . implode(', ', $forgatva) . ')' : '');
    }

    /**
     * A méretkorlát fölé nőtt naplók `.1`-re nevezése, az előző `.1` eldobásával.
     * Egyszerre csak egy generációt tartunk: ez nem archívum, hanem plafon.
     *
     * @return array a forgatott fájlok neve
     */
    private function rotateLogs()
    {
        $max = \mkw\thumbnail::returnBytes(
            \mkw\store::getConfigValue('cron.logmaxsize', self::LOGMAXSIZE)
        );
        if ($max <= 0) {
            return [];
        }
        $result = [];
        foreach (glob(\mkw\store::logsPath('*')) ?: [] as $abs) {
            if (!is_file($abs) || !in_array(strtolower(pathinfo($abs, PATHINFO_EXTENSION)), self::LOGEXTENSIONS, true)) {
                continue;
            }
            if (filesize($abs) < $max) {
                continue;
            }
            // a writelog() hívásonként nyit-zár, tehát a rename ablaka elhanyagolható
            @unlink($abs . '.1');
            if (@rename($abs, $abs . '.1')) {
                $result[] = basename($abs);
            }
        }
        return $result;
    }

    private function purgeCronlog()
    {
        $napok = (int)\mkw\store::getConfigValue('cron.logretention', self::LOGRETENTION);
        if ($napok <= 0) {
            return 0;
        }
        return \mkw\store::getEm()->getRepository(Cronlog::class)->deleteOlderThan($napok);
    }
}
