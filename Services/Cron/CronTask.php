<?php

namespace Services\Cron;

/**
 * Egy crontabból indítható feladat. Új feladat = egy ilyen osztály + egy sor a
 * `Services\CronService::TASKS` regiszterben; a zárolás, a naplózás és a kilépési kód
 * a CronService dolga, itt csak a munkát kell megírni.
 */
interface CronTask
{

    /** Egy sorban, mit csinál – a `php cron.php --list` és a cron napló mutatja. */
    public function getDescription(): string;

    /** Van-e értelme ezen a telepítésen futtatni (owner/feature kapcsoló, API kulcs). */
    public function isEnabled(): bool;

    /**
     * @param array $options a parancssori kapcsolók (`--limit=10` → `['limit' => '10']`)
     *
     * @return string a naplóba kerülő összefoglaló
     * @throws CronWarning       lefutott, de van rajta néznivaló – nem hiba, levelet sem küld
     * @throws \Throwable        bármi más: hibás futás, a crontab levelet küld róla
     */
    public function run(array $options = []): string;
}
