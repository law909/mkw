<?php
/**
 * A SIIKer (siiker_lampion) törzsadatainak átvitele az MKW adatbázisba parancssorból. A munkát
 * a {@see \Services\SiikerMigrationService} végzi; a forrás sémát a config.ini `siiker.dbname`
 * kulcsa adja, a cél a config.ini adatbázisa.
 *
 *   php siikermigrate.php                    minden lépés
 *   php siikermigrate.php --dry-run          próba menet: tranzakcióban fut, a végén visszagörget,
 *                                            képet nem ír
 *   php siikermigrate.php --step=torzs,ar    csak a felsorolt lépések (torzs, partner, termek, ar, kep)
 *   php siikermigrate.php --limit=50         lépésenként legfeljebb ennyi partner/termék
 *   php siikermigrate.php --quiet            csak az összesítő
 *
 * Minden lépés újrafuttatható: a SIIKer kód (migrid / idegenkod) szerint frissít, nem töröl.
 */

use Services\SiikerMigrationService;

if (PHP_SAPI !== 'cli') {
    http_response_code(404);
    exit;
}

chdir(__DIR__);
require_once __DIR__ . '/bootstrap.php';

// a fordítás az index.php-ban él, ide csak az entitások hibaüzeneteihez kell
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

// a mentés közben a listenerek az admin munkamenetet is elérik; kimenet után már nem indítható
\mkw\store::getAdminSession();

set_time_limit(0);
ini_set('memory_limit', '1024M');

$options = parseOptions($argv);
if (isset($options['help'])) {
    echo 'Használat: php ', basename(__FILE__), ' [--step=torzs,partner,termek,ar,kep] [--dry-run] [--limit=N] [--quiet]', PHP_EOL;
    exit(0);
}

try {
    $report = (new SiikerMigrationService())->run($options);
} catch (\Throwable $e) {
    fwrite(STDERR, $e->getMessage() . PHP_EOL);
    exit(1);
}
exit($report->hasErrors() ? 1 : 0);

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
