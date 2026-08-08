<?php
/**
 * Az UNAS képmappa árva fájljainak kitakarítása parancssorból. A munkát a
 * {@see \Services\UnasKepService::cleanupOrphans()} végzi, ugyanaz, ami az admin
 * „UNAS képtakarítás" menüpont mögött fut.
 *
 *   php unaskepcleanup.php                    próba menet, csak riport
 *   php unaskepcleanup.php --list             a törlendő fájlok kilistázva
 *   php unaskepcleanup.php --apply            törlés
 *   php unaskepcleanup.php --dir=kepek/regi/  másik mappára (az admin felület nem enged ki
 *                                             az UNAS képmappájából – csak a DB-ből hivatkozott
 *                                             fájlokat ismeri élőnek, egy sablonból vagy CSS-ből
 *                                             hivatkozott képet árvának látna)
 *
 * Ne fusson képimporttal egy időben: a menet közben letöltött, de még el nem mentett kép
 * hivatkozás nélkül áll a lemezen, és ez árvának látná. A beragadt zárolást a `--nolock`
 * kapcsolóval lehet figyelmen kívül hagyni.
 */

use Services\MediatarService;
use Services\UnasKepService;
use Services\UnasTermekImportService;

if (PHP_SAPI !== 'cli') {
    http_response_code(404);
    exit;
}

chdir(__DIR__);
require_once __DIR__ . '/bootstrap.php';

// a fordítás az index.php-ban él, ide csak a szolgáltatás hibaüzeneteihez kell
if (!function_exists('t')) {
    function t($msgid)
    {
        return $msgid;
    }
}

$options = parseOptions($argv);
$apply = isset($options['apply']);

if ($apply && !isset($options['nolock']) && (new UnasTermekImportService())->isLocked()) {
    fwrite(STDERR, 'Éppen fut egy UNAS import. Várd meg a végét, vagy add hozzá: --nolock' . PHP_EOL);
    exit(1);
}

$service = new UnasKepService($options['dir'] ?? null);
$report = $service->cleanupOrphans($apply, isset($options['force']));

echo 'Képmappa:   ', $report['mappa'], PHP_EOL;
echo 'URL előtag: ', $report['urlprefix'], PHP_EOL, PHP_EOL;

if ($report['megallt'] && !$report['fajl']) {
    fwrite(STDERR, $report['uzenet'] . PHP_EOL);
    exit(1);
}

echo 'Hivatkozott képnév az adatbázisban: ', $report['hivatkozott'],
    ' (', $report['oszlop'], ' oszlop átnézve)', PHP_EOL;
echo 'Fájl a mappában: ', $report['fajl'],
    ' (', MediatarService::formatSize($report['megtartva_meret'] + $report['arva_meret']), ')', PHP_EOL;
echo '  megtartva: ', $report['megtartva'], ' (', MediatarService::formatSize($report['megtartva_meret']), ')', PHP_EOL;
echo '  árva:      ', $report['arva'], ' (', MediatarService::formatSize($report['arva_meret']), ')', PHP_EOL;
if ($report['almappa']) {
    echo 'Almappa: ', $report['almappa'], ' – nem nézzük végig', PHP_EOL;
}
if ($report['hianyzo_db']) {
    echo 'Hiányzó fájlra mutató hivatkozás: ', $report['hianyzo_db'],
        ' – az adatbázishoz nem nyúlunk', PHP_EOL;
    printList(array_slice($report['hianyzo'], 0, 10), '  ! ', $report['hianyzo_db']);
}
if (isset($options['list'])) {
    printList($report['lista'], '  - ', $report['arva']);
}

if ($report['megallt']) {
    echo PHP_EOL, $report['uzenet'], PHP_EOL;
    if ($report['arva']) {
        echo 'Ha ez tényleg így helyes, a törléshez add hozzá a --force kapcsolót is.', PHP_EOL;
    }
    exit(1);
}
if (!$report['arva']) {
    echo PHP_EOL, 'Nincs mit törölni.', PHP_EOL;
    exit(0);
}
if (!$apply) {
    echo PHP_EOL, 'PRÓBA MENET – semmi nem törlődött.', PHP_EOL,
        'Éles törlés: php ', basename(__FILE__), ' --apply', PHP_EOL;
    exit(0);
}

echo PHP_EOL, 'Törölve: ', $report['torolve'], ' fájl (', MediatarService::formatSize($report['torolve_meret']), ')', PHP_EOL;
if ($report['hiba_db']) {
    echo 'Nem törölhető: ', $report['hiba_db'], PHP_EOL;
    printList(array_slice($report['hiba'], 0, 10), '  ! ', $report['hiba_db']);
    exit(1);
}
exit(0);

// ------------------------------------------------------------------

function parseOptions(array $argv)
{
    $options = [];
    foreach (array_slice($argv, 1) as $arg) {
        if (strncmp($arg, '--', 2) !== 0) {
            continue;
        }
        $arg = substr($arg, 2);
        $eq = strpos($arg, '=');
        if ($eq === false) {
            $options[$arg] = true;
        } else {
            $options[substr($arg, 0, $eq)] = substr($arg, $eq + 1);
        }
    }
    return $options;
}

function printList(array $names, $prefix, $total)
{
    foreach ($names as $name) {
        echo $prefix, $name, PHP_EOL;
    }
    if ($total > count($names)) {
        echo $prefix, '… és további ', $total - count($names), PHP_EOL;
    }
}

