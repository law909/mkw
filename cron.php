<?php
/**
 * A cron feladatok parancssori indítója. Egy hívás = egy feladat, a crontabban külön sorral,
 * mert a feladatok ütemezése különbözik. A regisztert a `Services\CronService` tartja, a
 * részletes leírás a `docs/cron.md`-ben van.
 *
 *   php cron.php --list                 a feladatok és az állapotuk ezen a telepítésen
 *   php cron.php unas                   egy feladat futtatása
 *   php cron.php unas --quiet           siker esetén néma – ez való a crontabba
 *   php cron.php unas --limit=10        feladatfüggő kapcsoló
 *   php cron.php cleanup --nolock       a párhuzamosság-védelem megkerülése (kézi menethez)
 *
 * Kilépési kód – a crontab csak a nem nullánál küld levelet:
 *   0  rendben (a kihagyott és a zárolt menet is: mindkettő normális üzem)
 *   1  a feladat hibára futott
 *   2  ismeretlen feladatnév vagy hiányzó paraméter
 *
 * A futás nyoma a `cronlog` táblába és a `storage/logs/cron.txt`-be kerül; az admin
 * „Cron napló” menüpont ugyanezt mutatja.
 */

if (PHP_SAPI !== 'cli') {
    http_response_code(404);
    exit;
}

chdir(__DIR__);

error_reporting(E_ALL & ~E_WARNING);
date_default_timezone_set('Europe/Budapest');

require_once __DIR__ . '/bootstrap.php';

// a fordítás az index.php-ban él, ide csak a szolgáltatások hibaüzeneteihez kell
if (!function_exists('t')) {
    function t($msgid)
    {
        return $msgid;
    }
}
if (!function_exists('at')) {
    function at($msgid)
    {
        return $msgid;
    }
}

// A fájlnapló nem kötelező (a cronlog tábla a fő nyilvántartás), de ha nem írható, arról tudni kell:
// tipikusan a cron más felhasználóval fut, mint a webszerver, vagy a storage/logs meg sem született
// (a storage/ nincs verziókövetve). Amíg így van, a crontab minden futásról levelet küld.
$__logfile = \mkw\store::logsPath('cron.txt');
$__logdir = dirname($__logfile);
// meglévő fájlnál a fájl joga dönt (appendhez az kell), különben a mappáé (ott jön létre)
$__logwritable = is_file($__logfile) ? is_writable($__logfile) : (is_dir($__logdir) && is_writable($__logdir));
if (!$__logwritable) {
    fwrite(STDERR, 'FIGYELEM: a ' . (is_file($__logfile) ? $__logfile : $__logdir) . ' nem írható,'
        . ' a fájlnapló kimarad (a cronlog tábla vezetése ettől még megy).' . PHP_EOL);
}

$options = [];
$taskname = '';
foreach (array_slice($argv, 1) as $arg) {
    if (strncmp($arg, '--', 2) === 0) {
        $parts = explode('=', substr($arg, 2), 2);
        $options[$parts[0]] = $parts[1] ?? true;
    } elseif ($taskname === '') {
        $taskname = $arg;
    }
}

$service = new \Services\CronService();

if (isset($options['list']) || isset($options['help']) || $taskname === '') {
    $tasks = $service->getTaskList();
    $out = isset($options['list']) || isset($options['help']) ? STDOUT : STDERR;
    fwrite($out, 'Használat: php cron.php <feladat> [--quiet] [--nolock] [egyéb kapcsolók]' . PHP_EOL . PHP_EOL);
    fwrite($out, 'Feladatok (' . \mkw\store::getTheme() . '):' . PHP_EOL);
    foreach ($tasks as $name => $task) {
        // az ékezet miatt a str_pad bájtban számolna, ezért kézzel igazítjuk
        $allapot = $task['bekapcsolva'] ? '[aktív]' : '[kikapcsolt]';
        fwrite($out, '  ' . str_pad($name, 10) . $allapot
            . str_repeat(' ', max(1, 14 - mb_strlen($allapot))) . $task['leiras'] . PHP_EOL);
    }
    exit($taskname === '' && !isset($options['list']) && !isset($options['help'])
        ? \Services\CronService::EXITUNKNOWN
        : \Services\CronService::EXITOK);
}

$result = $service->run($taskname, $options);

$sor = date('Y-m-d H:i:s') . ' ' . $taskname . ': ' . $result['allapot']
    . ' (' . $result['idotartam'] . 's) – ' . $result['uzenet'];

if ($result['exitcode'] !== \Services\CronService::EXITOK) {
    fwrite(STDERR, $sor . PHP_EOL);
} elseif (empty($options['quiet'])) {
    echo $sor, PHP_EOL;
}

exit($result['exitcode']);
